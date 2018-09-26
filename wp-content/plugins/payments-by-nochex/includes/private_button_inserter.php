<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action('init', 'wpeppsubNCX_button_media_buttons_init');

function wpeppsubNCX_button_media_buttons_init() {
	
	
	global $pagenow, $typenow;

	// add media button for editor page
	if ( in_array( $pagenow, array( 'post.php', 'page.php', 'post-new.php', 'post-edit.php' ) ) && $typenow != 'download' ) {
		
		add_action('admin_footer', 'wpeppsubNCX_button_add_inline_popup_content');
		add_action('media_buttons', 'wpeppsubNCX_button_add_my_media_button', 20);
		
		// button
		function wpeppsubNCX_button_add_my_media_button() {
			echo '<a href="#TB_inline?width=600&height=700&inlineId=wpeppsubNCX_popup_container" title="Insert a Payment Button" id="insert-my-media" class="button thickbox">Insert a Payment Button</a>';
		}
		
		// popup
		function wpeppsubNCX_button_add_inline_popup_content() {
		?>
		
			
		<script type="text/javascript">
			function wpeppsubNCX_button_InsertShortcode() {
				
				var id = document.getElementById("wpeppsubNCX_button_id").value;
				var wpplugin_nochex_alignmentc = document.getElementById("wpplugin_nochex_align");
				var wpplugin_nochex_alignmentb = wpplugin_nochex_alignmentc.options[wpplugin_nochex_alignmentc.selectedIndex].value;
				
				if(id == "No buttons found.") { alert("Error: Please select an existing button from the dropdown or make a new one."); return false; }
				if(id == "") { alert("Error: Please select an existing button from the dropdown or make a new one."); return false; }
				
				if(wpplugin_nochex_alignmentb == "none") { var wpplugin_nochex_alignment = ""; } else { var wpplugin_nochex_alignment = ' align="' + wpplugin_nochex_alignmentb + '"'; };
				
				window.send_to_editor('[wpeppsubNCX id="' + id + '"' + wpplugin_nochex_alignment + ']');
				
				document.getElementById("wpeppsubNCX_button_id").value = "";
				wpeppsubNCX_alignmentc.selectedIndex = null;
			}
		</script>

		
		<div id="wpeppsubNCX_popup_container" style="display:none;">
		 
			<h2>Insert a Payment Button</h2>

			<table><tr><td>Choose an existing Button: </td></tr>
			<tr><td>
			<select id="wpeppsubNCX_button_id" name="wpeppsubNCX_button_id">
				<?php
				$args = array('post_type' => 'wpplugin_sub_button','posts_per_page' => -1);

				$posts = get_posts($args);

				$count = "0";
				
				if (isset($posts)) {
					
					foreach ($posts as $post) {

						$id = $posts[$count]->ID;
						$post_title = $posts[$count]->post_title;
						$price = get_post_meta($id,'wpeppsubNCX_button_price',true);
						$sku = get_post_meta($id,'wpeppsubNCX_button_id',true);

						echo "<option value='$id'>";
						echo "Name: ";
						echo $post_title;
						echo "</option>";

						$count++;
					}
				}
				else {
					echo "<option>No buttons found.</option>";
				}
				
				?>
			</select>
			</td></tr>			
			<tr><td>Make a new Button: <a target="_blank" href="admin.php?page=wpeppsubNCX_buttons&action=new">here</a><br />
					Manage existing Buttons: <a target="_blank" href="admin.php?page=wpeppsubNCX_buttons">here</a></td></tr>
			<tr><td></td></tr>
			<tr><td>Alignment: </td></tr>
			<tr><td>
			<select id="wpplugin_nochex_align" name="wpplugin_nochex_align" style="width:100%;max-width:190px;">
			<option value="none">None</option>
			<option value="left">Left</option>
			<option value="center">Center</option>
			<option value="right">Right</option>
			</select></td></tr>
			<tr><td></td></tr>
			<tr><td></td></tr>
			<tr><td><input type="button" id="wpeppsubNCX-nochex-insert" class="button-primary" onclick="wpeppsubNCX_button_InsertShortcode();" value="Insert Payment Button"></td></tr></table>
		</div>
		<?php
		}
	}
}