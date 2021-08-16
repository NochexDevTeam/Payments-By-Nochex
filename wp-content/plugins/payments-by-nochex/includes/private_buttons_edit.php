<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if (isset($_POST['update'])) {

	if ( !current_user_can( "manage_options" ) )  {
		wp_die( __( "You do not have sufficient permissions to access this page. Please sign in as an administrator." ));
	}
	
	$post_id = intval($_GET['product']);
	
	if (!$post_id) {
		echo'<script>window.location="admin.php?page=wpeppsubNCX_buttons"; </script>';
		exit;
	}
	
	// Update data
	
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
		
		// amount
		$wpeppsubNCX_a1 = sanitize_meta( 'currency_wpeppsubNCX', $_POST['wpeppsubNCX_a1'], 'post' );
		update_post_meta($post_id, 'wpeppsubNCX_a1', $wpeppsubNCX_a1);

		$postAmt = sanitize_meta( 'postAmt', $_POST['postAmt'], 'post' );
		update_post_meta($post_id, 'postAmt', $postAmt);

		// description
		$wpeppsubNCX_button_description = sanitize_text_field($_POST['wpeppsubNCX_button_description']);
		update_post_meta($post_id, 'wpeppsubNCX_button_description', $wpeppsubNCX_button_description);		
					
		$wpeppsubNCX_button_image = sanitize_text_field($_POST['wpeppsubNCX_button_image']);
		update_post_meta($post_id, 'wpeppsubNCX_button_image', $wpeppsubNCX_button_image);
	
		// extras
		$wpeppsubNCX_showExtras = sanitize_text_field(!empty($_POST['showExtras']));		
		if($wpeppsubNCX_showExtras == ""){ $wpeppsubNCX_showExtras = "off";}
		update_post_meta($post_id, 'showExtras', $wpeppsubNCX_showExtras);
		
		$wpeppsubNCX_fullname = sanitize_text_field(!empty($_POST['fullname']));
		if($wpeppsubNCX_fullname == ""){ $wpeppsubNCX_fullname = "off";}
		update_post_meta($post_id, 'fullname', $wpeppsubNCX_fullname);
					
		$wpeppsubNCX_address = sanitize_text_field(!empty($_POST['address']));
		if($wpeppsubNCX_address == ""){ $wpeppsubNCX_address = "off";}
		update_post_meta($post_id, 'address', $wpeppsubNCX_address);
					
		$wpeppsubNCX_contactNo = sanitize_text_field(!empty($_POST['contactNo']));
		if($wpeppsubNCX_contactNo == ""){ $wpeppsubNCX_contactNo = "off";}
		update_post_meta($post_id, 'contactNo', $wpeppsubNCX_contactNo);
					
		$wpeppsubNCX_EmailAd = sanitize_text_field(!empty($_POST['EmailAd']));
		if($wpeppsubNCX_EmailAd == ""){ $wpeppsubNCX_EmailAd = "off";}
		update_post_meta($post_id, 'EmailAd', $wpeppsubNCX_EmailAd);
	
	// Check for errors
	$message = [];
	if (empty($_POST['wpeppsubNCX_button_name'])) {
		$message[] = "Name Field Required";
		$error = "1";
	}
	if (empty($_POST['wpeppsubNCX_a3'])) {
		$message[] = " Billing amount each cycle Field Required";
		$error = "1";
	}
	
	if (!isset($error)) {
	
		$message[] = "Saved";
	
	}
}


	if ( !current_user_can( "manage_options" ) )  {
		wp_die( __( "You do not have sufficient permissions to access this page. Please sign in as an administrator." ));
	}

?>

<div style="width:98%;">

	<form method='post' action='<?php $_SERVER["REQUEST_URI"]; ?>'>
	
		<?php
		$post_id = intval($_GET['product']);
		
		if (!$post_id) {
			echo'<script>window.location="admin.php?page=wpeppsubNCX_buttons"; </script>';
			exit;
		}
		
		$post_data = get_post($post_id);
		$title = $post_data->post_title;
		
		$siteurl = get_site_url();
		?>
		
		<table width="100%"><tr><td valign="bottom" width="85%">
			<br />
			<span style="font-size:20pt;">Edit Button</span>
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
		// saved
		if (!isset($error)&& !isset($error) && isset($message)) {
			foreach ($message as $messagea) {
				echo "<div class='updated'><p>"; echo $messagea; echo"</p></div>";
			}
		}
		?>
		
		<br />
		<style>
		td input[type=text]{
			width:100%!important;
			padding:5px;
		}
		</style>
		<div style="background-color:#fff;padding:8px;border: 1px solid #CCCCCC;"><br />
		
			<table><tr><td>
			
				<b>Shortcode</b></td><td></td></td></td></tr><tr><td>
				Shortcode: </td><td><input type="text" readonly="true" value="<?php echo "[wpeppsubNCX id=$post_id]"; ?>"></td><td>Put this in a page, post, or in your theme, to show the Nochex Button on your site. <br />You can also use the button inserter found above the page or post editor.
				</td></tr><tr><td style="border-bottom: 1px solid #ddd;" colspan=3><br /></td></tr><tr><td><br />
				
				<b>Main</b> </td><td></td></td></td></tr><tr><td>
				Item Name: </td><td><input type="text" required name="wpeppsubNCX_button_name" value="<?php echo esc_attr($title); ?>"></td><td> Required - The name of the item. </td></tr><tr><td>
				Item ID: </td><td><input type="text" name="wpeppsubNCX_button_sku" value="<?php echo esc_attr(get_post_meta($post_id,'wpeppsubNCX_button_sku',true)); ?>"></td><td> Optional - The ID / SKU of the item. </td></tr>
				<tr><td>
				Item Description: </td><td><input type="text" name="wpeppsubNCX_button_description" title="Optional - Description of the item." value="<?php echo esc_attr(get_post_meta($post_id,'wpeppsubNCX_button_description',true)); ?>"></td><td></td></tr>
				<tr><td style="border-bottom: 1px solid #ddd;" colspan=3><br /></td></tr><tr><td><br /> 
				
				<b>Amount</b> </td><td></td></td></td></tr><tr><td valign="top">
				Billing amount: </td><td valign="top"><input type="text" required name="wpeppsubNCX_a3" value="<?php echo esc_attr(get_post_meta($post_id,'wpeppsubNCX_a3',true)); ?>" style="width:94px;"></td><td> Required	
				</td></tr>
				
				<tr><td valign="top">Postage amount: </td>
				<td valign="top"><input type="text" title="Postage Amount" name="postAmt" value="<?php echo esc_attr(get_post_meta($post_id,'postAmt',true)); ?>" style="width:94px;"></td><td>Optional</td></tr>
				
				<tr><td style="border-bottom: 1px solid #ddd;" colspan=3><br /></td></tr>
				
				<tr><td><h3>Button Custom Image</h3> </td><td></td></td></td></tr>
			
			<tr><td></td><td> 
				<input type="radio" name="wpeppsubNCX_button_image"  value="1"  <?php if(get_post_meta($post_id,'wpeppsubNCX_button_image',true) == "1") { echo "checked"; } ?> /><img src="<?php echo plugins_url('images/payme4.gif', __FILE__ ); ?>" /><br/><br/>
				<input type="radio" name="wpeppsubNCX_button_image"  value="2"  <?php if(get_post_meta($post_id,'wpeppsubNCX_button_image',true) == "2") { echo "checked"; } ?> /><img src="<?php echo plugins_url('images/payme.gif', __FILE__ ); ?>" /><br/><br/>
				<input type="radio" name="wpeppsubNCX_button_image"  value="3"  <?php if(get_post_meta($post_id,'wpeppsubNCX_button_image',true) == "3") { echo "checked"; } ?> /><img src="<?php echo plugins_url('images/nochex_pay.png', __FILE__ ); ?>" /><br/><br/>
				<input type="radio" name="wpeppsubNCX_button_image"  value="4"  <?php if(get_post_meta($post_id,'wpeppsubNCX_button_image',true) == "4") { echo "checked"; } ?> /><img src="<?php echo plugins_url('images/nochex_checkout.png', __FILE__ ); ?>" /><br/><br/>
				<input type="radio" name="wpeppsubNCX_button_image"  value="5"  <?php if(get_post_meta($post_id,'wpeppsubNCX_button_image',true) == "5") { echo "checked"; } ?> /><img src="<?php echo plugins_url('images/cardsboth.gif', __FILE__ ); ?>" /><br/><br/>
				</td><td></td></tr><tr><td>
				<tr><td style="border-bottom: 1px solid #ddd;" colspan="3"><br /></td></tr>
				<tr><td>			
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
			
			<h3><input type="checkbox" id="showExtras" name="showExtras" onclick="showExts(this);" <?php if(get_post_meta($post_id,'showExtras',true) == 1) { echo "checked"; } ?> /> Extra Details</h3> </td><td></td></td></td></tr>

			<tr><td></td><td> 
				
				<div id="showExtraFields" style="display:none;" name="showExtraFields">
					<table>
						<tr><td><h4>Customer Details</h4></td></tr>
						<tr><td><input type="checkbox" name="fullname" <?php if(get_post_meta($post_id,'fullname',true) == 1) { echo "checked"; } ?> />Fullname</td></tr>
						<tr><td><input type="checkbox" name="address" <?php if(get_post_meta($post_id,'address',true) == 1) { echo "checked"; } ?> />Address</td></tr>
						<tr><td><input type="checkbox" name="contactNo" <?php if(get_post_meta($post_id,'contactNo',true) == 1) { echo "checked"; } ?> />Contact Number</td></tr>
						<tr><td><input type="checkbox" name="EmailAd" <?php if(get_post_meta($post_id,'EmailAd',true) == 1) { echo "checked"; } ?> />Email Address</td></tr> 
					</table>
				</div>
				
			</td><td></td></tr><tr><td>

				
				<tr><td style="border-bottom: 1px solid #ddd;" colspan=3><br /></td></tr>
				</table>						
		</div>
	</form>
</div>
