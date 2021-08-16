<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $current_user;

if (isset($_POST['update'])) {

	if ( !current_user_can( "manage_options" ) )  {
		wp_die( __( "You do not have sufficient permissions to access this page. Please sign in as an administrator." ));
	}
	
	if (isset($_GET['product'])) {
		$post_id = intval($_GET['product']);
	}
	
	// Check for errors
	$message = [];
	if (empty($_POST['wpeppsubNCX_button_name'])) {
		$message[] = "Name Field Required";
		$error = "1";
	}
	if (empty($_POST['wpeppsubNCX_a3'])) {
		$message[] = " Billing amount";
		$error = "1";
	}
	
	// Save data
	
	
	if (!isset($error)) {
	
		$my_post = array(
		  'post_title'    => sanitize_text_field($_POST['wpeppsubNCX_button_name']),
		  'post_status'   => 'publish',
		  'post_author'   => $current_user->ID,
		  'post_type'     => 'wpplugin_sub_button'
		);
		
		$post_id = wp_insert_post($my_post);
		
		$my_post = array(
		'ID'           => $post_id,
		'post_title'   => sanitize_text_field($_POST['wpeppsubNCX_button_name'])
		);
		
		wp_update_post($my_post);
		
		// main
		if (!empty($_POST['wpeppsubNCX_button_show'])) {
			$skip = sanitize_text_field($_POST['wpeppsubNCX_button_show']);
			update_post_meta($post_id, "wpeppsubNCX_button_show", $skip);
		} else {
			update_post_meta($post_id, "wpeppsubNCX_button_show", 0);
		}
		
		update_post_meta($post_id, 'wpeppsubNCX_button_name', sanitize_text_field($_POST['wpeppsubNCX_button_name']));
		
		// id / sku
		$wpeppsubNCX_button_sku = sanitize_text_field($_POST['wpeppsubNCX_button_sku']);
		update_post_meta($post_id, 'wpeppsubNCX_button_sku', $wpeppsubNCX_button_sku);
		
		// description
		$wpeppsubNCX_button_description = sanitize_text_field($_POST['wpeppsubNCX_button_description']);
		update_post_meta($post_id, 'wpeppsubNCX_button_description', $wpeppsubNCX_button_description);		
		
		// language and currency		
		$wpeppsubNCX_button_language = intval($_POST['wpeppsubNCX_button_language']);
		if (!$wpeppsubNCX_button_language) { 	$wpeppsubNCX_button_language = ""; }
		update_post_meta($post_id, 'wpeppsubNCX_button_language', $wpeppsubNCX_button_language);
		
		// amount
		$wpeppsubNCX_a3 = sanitize_meta( 'currency_wpeppsubNCX', $_POST['wpeppsubNCX_a3'], 'post' );
		update_post_meta($post_id, 'wpeppsubNCX_a3', $wpeppsubNCX_a3);
		
		$postAmt = sanitize_meta( 'postAmt', $_POST['postAmt'], 'post' );
		update_post_meta($post_id, 'postAmt', $postAmt); 
		
		$wpeppsubNCX_button_image = sanitize_text_field($_POST['wpeppsubNCX_button_image']);
		if (!wpeppsubNCX_button_image && $wpeppsubNCX_button_image != "0") { 	$wpeppsubNCX_button_image = ""; }
		update_post_meta($post_id, 'wpeppsubNCX_button_image', $wpeppsubNCX_button_image);
		
		// extras
		$wpeppsubNCX_showExtras = sanitize_text_field($_POST['showExtras']);		
		if($wpeppsubNCX_showExtras == ""){ $wpeppsubNCX_showExtras = "off";}
		update_post_meta($post_id, 'showExtras', $wpeppsubNCX_showExtras);
		
		$wpeppsubNCX_fullname = sanitize_text_field($_POST['fullname']);
		if($wpeppsubNCX_fullname == ""){ $wpeppsubNCX_fullname = "off";}
		update_post_meta($post_id, 'fullname', $wpeppsubNCX_fullname);
					
		$wpeppsubNCX_address = sanitize_text_field($_POST['address']);
		if($wpeppsubNCX_address == ""){ $wpeppsubNCX_address = "off";}
		update_post_meta($post_id, 'address', $wpeppsubNCX_address);
					
		$wpeppsubNCX_contactNo = sanitize_text_field($_POST['contactNo']);
		if($wpeppsubNCX_contactNo == ""){ $wpeppsubNCX_contactNo = "off";}
		update_post_meta($post_id, 'contactNo', $wpeppsubNCX_contactNo);
					
		$wpeppsubNCX_EmailAd = sanitize_text_field($_POST['EmailAd']);
		if($wpeppsubNCX_EmailAd == ""){ $wpeppsubNCX_EmailAd = "off";}
		update_post_meta($post_id, 'EmailAd', $wpeppsubNCX_EmailAd);
	 
		echo'<script>window.location="?page=wpeppsubNCX_buttons&message=created";</script>';
		exit;		
	}	 	
}


if ( !current_user_can( "manage_options" ) )  {
	wp_die( __( "You do not have sufficient permissions to access this page. Please sign in as an administrator." ));
}
	
?>

<div style="width:98%;">

	<form method='post' action='<?php echo $_SERVER["REQUEST_URI"]; ?>'>
		
		<table width="100%"><tr><td valign="bottom" width="85%">
			<br />
			<span style="font-size:20pt;">New Button</span>
			</td><td valign="bottom">
			<input type="submit" class="button-primary" style="font-size: 14px;height: 30px;float: right;" value="Save Button">
			</td><td valign="bottom">
			<a href="admin.php?page=wpeppsubNCX_buttons" class="button-secondary" style="font-size: 14px;height: 30px;float: right;">View All Buttons</a>
		</td></tr></table>
		
		<?php
		
		// error
		if (isset($error) && isset($error) && isset($message)) {
			foreach ($message as $messagea) {
				echo "<div class='error'><p>"; echo $messagea; echo"</p></div>";
			}			
		}
		?>
		
		<br />
		
		<div style="background-color:#fff;padding:8px;border: 1px solid #CCCCCC;">			
			<table>
			
			<tr><td><h3>Main</h3> </td><td></td></td></td></tr>			
			<tr><td>
				Item Name: </td><td><input required type="text" title="Required - The name of the item." name="wpeppsubNCX_button_name" value="<?php if(isset($_POST['wpeppsubNCX_button_name'])) { echo esc_attr($_POST['wpeppsubNCX_button_name']); } ?>"></td><td></td></tr><tr><td>
				Item ID: </td><td><input type="text" name="wpeppsubNCX_button_sku" title="Optional - The ID / SKU of the item." value="<?php if(isset($_POST['wpeppsubNCX_button_sku'])) { echo esc_attr($_POST['wpeppsubNCX_button_sku']); } ?>"></td><td></td></tr><tr><td>
				Item Description: </td><td><input type="text" name="wpeppsubNCX_button_description" title="Optional - Description of the item." value="<?php if(isset($_POST['wpeppsubNCX_button_description'])) { echo esc_attr($_POST['wpeppsubNCX_button_description']); } ?>"></td><td></td></tr><tr><td>
				
				<?php
				if(isset($_POST['wpeppsubNCX_button_show'])) { $wpeppsubNCX_button_show = esc_attr($_POST['wpeppsubNCX_button_show']); } else { $wpeppsubNCX_button_show = ""; }
				if ($wpeppsubNCX_button_show == "1") { $show_enable = "CHECKED"; } else { $show_enable = ""; }
				?>
				
				Show Name: </td><td><input title="Optional - Show the name of the item above the button." type="checkbox" name="wpeppsubNCX_button_show" value="1" <?php echo $show_enable; ?>></td></tr>
					<tr><td style="border-bottom: 1px solid #ddd;" colspan=3><br /></td></tr>	
				<tr><td><br />								
				<h3>Amount</h3> </td><td></td></td></td></tr>
				<tr><td valign="top">Billing amount: </td>
				<td valign="top"><input required type="text" title="Required: Total Amount" name="wpeppsubNCX_a3" value="<?php if(isset($_POST['wpeppsubNCX_a3'])) { echo esc_attr($_POST['wpeppsubNCX_a3']); } ?>" style="width:94px;"></td><td></td></tr>
				
				<tr><td valign="top">Postage amount: </td>
				<td valign="top"><input type="text" title="Postage Amount" name="postAmt" value="<?php if(isset($_POST['postAmt'])) { echo esc_attr($_POST['postAmt']); } ?>" style="width:94px;">
				</td><td></td></tr>
				
				<input type="hidden" name="update" value="1">				
				
				<tr><td style="border-bottom: 1px solid #ddd;" colspan=3><br /></td></tr>	
				<tr><td>
				Button Image: </td><td> 
				<input type="radio" name="wpeppsubNCX_button_image"  value="1" <?php if(!empty($_POST['wpeppsubNCX_button_image']) == "1") { echo "checked"; } ?> /><img src="https://ssl.nochex.com/Downloads/Nochex Payment Button/payme4.gif" /><br/><br/>
				<input type="radio" name="wpeppsubNCX_button_image"  value="2" <?php if(!empty($_POST['wpeppsubNCX_button_image']) == "2") { echo "checked"; } ?> /><img src="https://ssl.nochex.com/Downloads/Nochex Payment Button/payme.gif" /><br/><br/>
				<input type="radio" name="wpeppsubNCX_button_image"  value="3" <?php if(!empty($_POST['wpeppsubNCX_button_image']) == "3") { echo "checked"; } ?> /><img src="https://ssl.nochex.com/Downloads/Nochex Payment Button/nochex_pay.png" /><br/><br/>
				<input type="radio" name="wpeppsubNCX_button_image"  value="4" <?php if(!empty($_POST['wpeppsubNCX_button_image']) == "4") { echo "checked"; } ?> /><img src="https://ssl.nochex.com/Downloads/Nochex Payment Button/nochex_checkout.png" /><br/><br/>
				<input type="radio" name="wpeppsubNCX_button_image"  value="5" <?php if(!empty($_POST['wpeppsubNCX_button_image']) == "5") { echo "checked"; } ?> /><img src="https://ssl.nochex.com/Downloads/Nochex Payment Button/cardsboth1.gif" /><br/><br/>
				</td><td></td></tr>
				
				<tr><td style="border-bottom: 1px solid #ddd;" colspan=3><br /></td></tr>

	<tr><td colspan=3>			
			<script type="text/javascript">
			
			window.onload = function (){
				showExts();
			}
			
				function showExts(){					
				var val = document.getElementById("showExtras");	 	
						
				if(val.checked == true){
					document.getElementById("showExtraFields").setAttribute("style","display:block");		
				}else{
					document.getElementById("showExtraFields").setAttribute("style","display:none"); 
				}
				
				}
			</script>
			
			<h3><input type="checkbox" id="showExtras" name="showExtras" onclick="showExts(this);" <?php if(!empty($_POST["showExtras"]) == 1) { echo "checked"; } ?> /> Extra Details</h3> </td><td></td></td></td></tr>

			<tr><td></td><td> 
				
				<div id="showExtraFields" style="display:none;" name="showExtraFields">
					<table>
						<tr><td><h4>Customer Details</h4></td></tr>
						<tr><td><input type="checkbox" name="fullname" <?php if(!empty($_POST["fullname"]) == 1) { echo "checked"; } ?> />Fullname</td></tr>
						<tr><td><input type="checkbox" name="address" <?php if(!empty($_POST["address"]) == 1) { echo "checked"; } ?> />Address</td></tr>
						<tr><td><input type="checkbox" name="contactNo" <?php if(!empty($_POST["contactNo"]) == 1) { echo "checked"; } ?> />Contact Number</td></tr>
						<tr><td><input type="checkbox" name="EmailAd" <?php if(!empty($_POST["EmailAd"]) == 1) { echo "checked"; } ?> />Email Address</td></tr> 
					</table>
				</div>
				
			</td><td></td></tr>
		
				<tr><td style="border-bottom: 1px solid #ddd;" colspan=3><br /></td></tr>		

			</table>	
		</div>
	</form>
</div>
