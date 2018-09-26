<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function wpeppsubNCX_plugin_options() {

	if ( !current_user_can( "manage_options" ) )  {
		wp_die( __( "You do not have sufficient permissions to access this page. Please sign in as an administrator." ));
	}

	?>
	
	<form method='post' action='<?php $_SERVER["REQUEST_URI"]; ?>'>
		
		<?php
		// save and update options
		if (isset($_POST['update'])) {

			if (!isset($_POST['action_save']) || ! wp_verify_nonce($_POST['action_save'],'nonce_save') ) {
			   print 'Sorry, your nonce did not verify.';
			   exit;
			}
			
			$options['currency'] =			intval($_POST['currency']);
			if (!$options['currency']) { 	$options['currency'] = "25"; }
				
			$options['language'] = 			intval($_POST['language']);
			if (!$options['language']) { 	$options['language'] = "3";	}
				
			$options['testmode'] = 				intval($_POST['testmode']);
			if (!$options['testmode']) { 		$options['testmode'] = "1";	}
						
			$options['xmlMode'] = 	intval($_POST['xmlMode']);
			if (!$options['xmlMode']) { 		$options['xmlMode'] = "0";	}
						
			$options['hideBillMode'] = 	intval($_POST['hideBillMode']);
			if (!$options['hideBillMode']) { 		$options['hideBillMode'] = "0";	}						
						
			$options['size'] = 				intval($_POST['size']);
			if (!$options['size']) { 		$options['size'] = "1";	}
				
			$options['opens'] = 			intval($_POST['opens']);
			if (!$options['opens']) { 		$options['opens'] = "1"; }
				
			$options['no_shipping'] = 		intval($_POST['no_shipping']);
			if (!$options['no_shipping']) { $options['no_shipping'] = "0"; }
			
			$options['content'] = 			sanitize_text_field($_POST['content']);
			$options['hideadmin'] = 		sanitize_text_field($_POST['hideadmin']);
			$options['subscriber'] = 		sanitize_text_field($_POST['subscriber']);			
			$options['merchant_id'] = 		sanitize_text_field($_POST['merchant_id']); 			
			$options['cancelled_text'] = 	sanitize_text_field($_POST['cancelled_text']);
			$options['guest_text'] = 		sanitize_text_field($_POST['guest_text']);			
			$options['cancel'] = 			sanitize_text_field($_POST['cancel']);
			$options['return'] = 			sanitize_text_field($_POST['return']);
			$options['log'] = 				sanitize_text_field($_POST['log']);
			$options['logging_id'] = 		sanitize_text_field($_POST['logging_id']);
			$options['uninstall'] = 		sanitize_text_field($_POST['uninstall']);
			
			update_option("wpeppsubNCX_settingsoptions", $options);
			
			echo "<br /><div class='updated'><p><strong>"; _e("Settings Updated."); echo "</strong></p></div>";
		}
		
		
		if (isset($_GET['wpeppsubNCX_clear_logs']) && $_GET['wpeppsubNCX_clear_logs'] == "1") {			
			check_admin_referer('clear_log');			
			wpeppsubNCX_clear_log();
			echo'<script>window.location="?page=wpeppsubNCX_settings&hidden_tab_value=4"; </script>';
			exit;
		}
		if (isset($_GET['wpeppsubNCX_reload_logs']) && $_GET['wpeppsubNCX_reload_logs'] == "1") {			
			check_admin_referer('reload');			
			echo'<script>window.location="?page=wpeppsubNCX_settings&hidden_tab_value=4"; </script>';
			exit;
		}
				 
		$options = get_option('wpeppsubNCX_settingsoptions');
		foreach ($options as $k => $v ) {
			
			$value[$k] =  wp_kses($v, array(
					'a' => array(
					'href' => array(),
					'title' => array()
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
			));	
			
		}
		
		$siteurl = get_site_url();
		
		// tabs menu
		?>
		
		<table width='100%'><tr><td width='75%' valign='top'><br />
	
		<table width="100%"><tr><td>
			<br />

			<span style="font-size:20pt;">Nochex Settings</span>
			</td><td valign="bottom">
			<?php echo wp_nonce_field('nonce_save','action_save'); ?>
			<input type="submit" name='btn2' class='button-primary' style='font-size: 14px;height: 30px;float: right;' value="Save Settings">
		</td></tr></table>
			
			<?php
			if (isset($saved)) {
				echo "<div class='updated'><p>Settings Updated.</p></div>";
			}
			?>
		
		<?php
		
		if (isset($_REQUEST['hidden_tab_value'])) {
			$active_tab =  $_REQUEST['hidden_tab_value'];
		} else {
			$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : '1';
		}
		
		// media uploader
		function wpplugin_nochex_button_load_scripts() {
			wp_enqueue_script('media-upload');
			wp_enqueue_script('thickbox');
			wp_enqueue_style('thickbox');
		}
		wpplugin_nochex_button_load_scripts();
		?>

		<script>
			jQuery(document).ready(function() {
				var formfield;
				jQuery('.upload_image_button').click(function() {
					jQuery('html').addClass('Image');
					formfield = jQuery(this).prev().attr('name');
					tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
					return false;
				});
				window.original_send_to_editor = window.send_to_editor;
				window.send_to_editor = function(html){
					if (formfield) {
						fileurl = jQuery('img',html).attr('src');
						jQuery('#'+formfield).val(fileurl);
						tb_remove();
						jQuery('html').removeClass('Image');
					} else {
						window.original_send_to_editor(html);
					}
				};
			});
		</script>
		
		<script type="text/javascript">
			
			window.onload = function(){
			
			var full_url = document.URL; // Get current url
			var url_array = full_url.split('#') // Split the string into an array with / as separator
			var last_segment = url_array[url_array.length-1];  // Get the last part of the array (-1)
			
			if(last_segment == "Started"){			
				newtab("1");
				closetabs("3");			
			}else if(last_segment == "Nochex"){
				newtab("3");
				closetabs("1");			
			}else{			
				newtab("1");
				closetabs("3");			
			}			
			}
			
			function closetabs(ids) {
				var x = ids;
				y = x.split(",");
				
				for(var i = 0; i < y.length; i++) {
					//console.log(y[i]);
					document.getElementById(y[i]).style.display = 'none';
					document.getElementById("id"+y[i]).classList.remove('nav-tab-active');
				}
			}
			
			function newtab(id) {
				var x = id;
				document.getElementById(x).style.display = 'block';
				document.getElementById("id"+x).classList.add('nav-tab-active');
				document.getElementById('hidden_tab_value').value=x;
			}
		</script>
		
		<br />

			<a style='border-bottom:1px solid #ccc' onclick='closetabs("3");newtab("1");' href="#Started" id="id1" class="nav-tab <?php echo $active_tab == '1' ? 'nav-tab-active' : ''; ?>">Information</a>
			<a style='border-bottom:1px solid #ccc' onclick='closetabs("1");newtab("3");' href="#Nochex" id="id3" class="nav-tab <?php echo $active_tab == '3' ? 'nav-tab-active' : ''; ?>">Nochex</a>
	
		<br /><br /><br />
		
		<div id="1" style="display:none;border: 1px solid #CCCCCC;<?php echo $active_tab == '1' ? 'display:block;' : ''; ?>">
			<div style="background-color:#E4E4E4;padding:8px;color:#000;font-size:15px;color:#464646;font-weight: 700;border-bottom: 1px solid #CCCCCC;">
				Information
			</div>
			<div style="background-color:#fff;padding:8px;"> 
				<br />
				This plugin will allow you to accept credit / debit card payments on your website by Nochex.
			<br /><br />	<img src="https://www.nochex.com/logobase-secure-images/logobase-banners/clear-amex-mp.png" alt="Logobase" style="max-width:300px" />
			</div>
		</div>
		
		<div id="3" style="display:none;border: 1px solid #CCCCCC;<?php echo $active_tab == '3' ? 'display:block;' : ''; ?>">
			<div style="background-color:#E4E4E4;padding:8px;color:#000;font-size:15px;color:#464646;font-weight: 700;border-bottom: 1px solid #CCCCCC;">
				&nbsp; Nochex Settings </div>
			<div style="background-color:#fff;padding:8px;">
			
			
			<table><tr><td colspan="2"></td></tr><tr><td>
				
				<b>Merchant ID:</b> </td><td><input type='text' style="width:200px;" name='merchant_id' value='<?php echo $value['merchant_id']; ?>'> Required </td></tr>
				<tr><td></td><td colspan="2"></td></tr>
				
				<tr><td colspan="2">
				
				<br />
				
				<h3>Options</h3></td></tr><tr><td>
				
				<b>Test Mode:</b> </td><td>
				&nbsp; &nbsp; <input <?php if ($value['testmode'] == "1") { echo "checked='checked'"; } ?> type='radio' name='testmode' value='1'>On (Test mode)
				&nbsp; &nbsp;  &nbsp;<input <?php if ($value['testmode'] == "2") { echo "checked='checked'"; } ?> type='radio' name='testmode' value='2'>Off (Live mode)
				
				</td></tr> 
				
				<tr><td>				
				<b>Payment Page - Detailed Information:</b> </td><td>
				&nbsp; &nbsp; <input <?php if ($value['xmlMode'] == "1") { echo "checked='checked'"; } ?>  type='radio' name='xmlMode' value='1'>Yes
				&nbsp; &nbsp;  &nbsp;<input <?php if ($value['xmlMode'] == "2") { echo "checked='checked'"; } ?> type='radio' name='xmlMode' value='2'>No
				&nbsp; &nbsp;  &nbsp; A setting of 'Yes' will show a table structured format on your Nochex payment page.			
				</td></tr>
				
				<tr><td>				
				<b>Hide Billing Details:</b> </td><td>
				&nbsp; &nbsp; <input <?php if ($value['hideBillMode'] == "1") { echo "checked='checked'"; } ?>  type='radio' name='hideBillMode' value='1'>Yes
				&nbsp; &nbsp; &nbsp;<input <?php if ($value['hideBillMode'] == "2") { echo "checked='checked'"; } ?> type='radio' name='hideBillMode' value='2'>No
				&nbsp; &nbsp; &nbsp; A setting of 'Yes' will hide the billing address details. Note: You must have Extra Details enabled in order for this to work.
				</td></tr>
				
				</table>
				
				<br /><br />
			</div>
		</div>
		
		<input type='hidden' name='update'>
		<input type='hidden' name='hidden_tab_value' id="hidden_tab_value" value="<?php echo $active_tab; ?>">
		
	</form>
	
	</td></tr></table>
	
	<?php
	
}
