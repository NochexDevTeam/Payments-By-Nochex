<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function wpeppsubNCX_options($atts) {

	// get shortcode id
		$atts = shortcode_atts(array(
			'id' 		=> '',
			'align' 	=> '',
			'widget' 	=> ''
		), $atts);
			
		$post_id = $atts['id'];
		
	// check to see if post exists
	$check_post = get_post($post_id);
	
	if (empty($check_post)) {
		return;
	}

	// amount
	$postAmt = esc_attr(get_post_meta($post_id,'postAmt',true));
	$postAmt = number_format($postAmt, 2);
	
	$wpeppsubNCX_a3 = esc_attr(get_post_meta($post_id,'wpeppsubNCX_a3',true));

	// get settings page values
	$options = get_option('wpeppsubNCX_settingsoptions');
	foreach ($options as $k => $v ) { $value[$k] = esc_attr($v); }
	
	$wpeppsubNCX_button_image = esc_attr(get_post_meta($post_id,'wpeppsubNCX_button_image',true));
	$button_url = "";
	
	if($wpeppsubNCX_button_image == 1){
		$button_url = "https://ssl.nochex.com/Downloads/Nochex%20Payment%20Button/payme4.gif";	
	}elseif($wpeppsubNCX_button_image == 2){
		$button_url = "https://ssl.nochex.com/Downloads/Nochex%20Payment%20Button/payme.gif";	
	}elseif($wpeppsubNCX_button_image == 3){
		$button_url = "https://ssl.nochex.com/Downloads/Nochex%20Payment%20Button/nochex_pay.png";	
	}elseif($wpeppsubNCX_button_image == 4){
		$button_url = "https://ssl.nochex.com/Downloads/Nochex%20Payment%20Button/nochex_checkout.png";	
	}elseif($wpeppsubNCX_button_image == 5){
		$button_url = "https://ssl.nochex.com/Downloads/Nochex%20Payment%20Button/cardsboth1.gif";
	}elseif($wpeppsubNCX_button_image == 6){
		$button_url = "https://ssl.nochex.com/Downloads/Nochex%20Payment%20Button/cardsboth2.gif";	
	}else{
		$button_url = "https://ssl.nochex.com/Downloads/Nochex%20Payment%20Button/nochex_pay.png";	
	}
	
	$wpeppsubNCX_button_name = esc_attr(get_post_meta($post_id,'wpeppsubNCX_button_name',true));
	$wpeppsubNCX_button_sku = esc_attr(get_post_meta($post_id,'wpeppsubNCX_button_sku',true));

	$account = $value['merchant_id']; 
	
	$testmode = $value['testmode']; 
	/*$reqBilling = $value['reqBilling']; */
	$xmlMode = $value['xmlMode']; 
	$hideBilling = $value['hideBillMode'];
		
	// account
	$account_a = esc_attr(get_post_meta($post_id,'wpeppsubNCX_button_account',true));
	if (!empty($account_a)) { $account = $account_a; }

	// custom button size
	$wpeppsubNCX_button_buttonsize = esc_attr(get_post_meta($post_id,'wpeppsubNCX_button_buttonsize',true));
	
	if ($wpeppsubNCX_button_buttonsize != "0") {
		$value['size'] = $wpeppsubNCX_button_buttonsize;
	}
		
	// alignment
	if ($atts['align'] == "left") { $alignment = "style='float: left;'"; }
	if ($atts['align'] == "right") { $alignment = "style='float: right;'"; }
	if ($atts['align'] == "center") { $alignment = "style='margin-left: auto;margin-right: auto;width:220px'"; }
	if (empty($atts['align'])) { $alignment = ""; }
		
	// spacing above buy button
	$spacing = "0";
	
	if ($atts['widget'] == "true") {
		$alignment = "style='margin-left: auto;margin-right: auto;width:220px'";
	}
		$item_description = esc_attr(get_post_meta($post_id,'wpeppsubNCX_button_description',true));
		
		
	$output = "";
	$output .= "<div class='wpeppsubNCX_wrapper' style=\"text-align:center\">";
	  
	$output .= "<div><style>.ncx label{min-width: 450px;clear: both;}</style>
	<form class=\"ncx\" action='https://secure.nochex.com/default.aspx' method='post'>";
	$output .= "<input type='hidden' name='merchant_id' value='$account'>";   
	$output .= "<label style=\"text-align:center;margin:0px 10px;\">
				<ul style=\"padding:0px;margin-left:0px;list-style:none\">
				<li style=\"text-align:center\">Reference: $wpeppsubNCX_button_name<br/><br/></li>
				<li style=\"text-align:center\">Item Description: $item_description<br/><br/></li>
				<li style=\"text-align:center\">Amount: $wpeppsubNCX_a3</li>";
				
				if($postAmt > 0){
				
	$totalAmt = number_format($wpeppsubNCX_a3 + $postAmt, 2);
	$output .= "<li style=\"text-align:center\">Postage: $postAmt<br/></li>
				<li style=\"text-align:center\">Total Amount: $totalAmt</li>";
				
				}
				
	$output .=	"</ul></label>";  
	
if($testmode == 1){ 
	$output .= "<input type='hidden' name='test_transaction' value='100'>";   
}

if($hideBilling == 1){
	$output .= "<input type='hidden' name='hide_billing_details' value='true'>";   
}

if (get_post_meta($post_id,'fullname',true) == 1){
	echo "<style>
	#billing_fullname{
		display:block;
	}
	</style>";
}else{
	echo "<style>
		#billing_fullname{
			display:none;
		}
		</style>";
}

if (get_post_meta($post_id,'address',true) == 1){
echo "<style>
	#billing_details{
		display:block;
	}
	</style>";
}else{
echo "<style>
	#billing_details{
		display:none;
	}
	</style>";
}

if (get_post_meta($post_id,'EmailAd',true) == 1){
echo "<style>
	#emailaddress{
		display:block;
	}
	</style>";

}else{

echo "<style>
	#emailaddress{
		display:none;
	}
	</style>";
}

	if (get_post_meta($post_id,'contactNo',true) == 1){
	echo "<style>
		 #phonenumber{
			display:block;
		}
		</style>";
	}else{
	echo "<style>
		 #phonenumber{
			display:none;
		}
		</style>";
	}

	if($xmlMode == "1"){
		$description = "Payment for #" . $post_id;
		$itemCollection = "<items><item><id></id><name>".$wpeppsubNCX_button_name."</name><description> ". $item_description ."</description><quantity>1</quantity><price>" . $wpeppsubNCX_a3 . "</price></item></items>";
	}else{ 
		$description = $wpeppsubNCX_button_name . " - Amount: " . $wpeppsubNCX_a3 . ", " . $item_description; 
		$itemCollection = "";
	}
  
	$notify_url = add_query_arg( 'wpeppsubNCX-listener', 'IPN', home_url( 'index.php' ) );
	$success_url = add_query_arg( 'wpeppsubNCX-listener', 'SCS', home_url( 'index.php' ) );
	
	$output .= "<div id='billing_fullname'><label>Billing Fullname: <input type='text' name='billing_fullname' autocomplete='name' value=''></label><br/></div>";   
	$output .= "<div id='billing_details'><label>Billing Address: <input type='text' name='billing_address' autocomplete='address-line1' value=''></label><br/><br/>";   
	$output .= "<label>Billing City: <input type='text' name='billing_city' autocomplete='address-level2' value=''></label><br/><br/>";   
	$output .= "<label>Billing Postcode: <input type='text' name='billing_postcode' autocomplete='postal-code' value=''></label><br/></div>";   
	$output .= "<div id='emailaddress'><label>Email Address: <input type='text' name='email_address' autocomplete='email' value=''></label><br/><br/></div>";   
	$output .= "<div id='phonenumber'><label>Phone Number: <input type='text' name='customer_phone_number' autocomplete='tel-national' value=''></label><br/><br/></div>"; 	 
	$output .= "<input type='hidden' name='callback_url' value='$notify_url'>";
	$output .= "<input type='hidden' name='success_url' value='$success_url' />";	 	
	$output .= "<input type='hidden' name='test_success_url' value='$success_url' />";	 
	
	if($postAmt > 0){
		$output .= "<input type='hidden' name='postage' value='$postAmt'>";
	}

	$output .= "<input type='hidden' name='amount' value='$wpeppsubNCX_a3'>";
	$output .= "<input type='hidden' name='optional_1' value='$post_id' />";	
	$output .= "<input type='hidden' name='description' value='$description' />";
	$output .= "<input type='hidden' name='xml_item_collection' value='$itemCollection' />";
	$output .= "<label style=\"text-align:center;float:none;\"> 	
				<input type='submit' style=\"display:none;\" name='submit' value='Pay Now'><img src='$button_url' /></input>			
				</label><br/><br/>";
	$output .= "</form></div>";
	$output .= "</div>";

	return $output;
	
}

// shortcode for button
add_shortcode('wpeppsubNCX', 'wpeppsubNCX_options');
 
 
// login shortcode
function wpeppsubNCX_login() {
	if (!is_user_logged_in()) {
		$args = array(
			'echo'           => true,
			'remember'       => true,
			'form_id'        => 'loginform',
			'id_username'    => 'user_login',
			'id_password'    => 'user_pass',
			'id_remember'    => 'rememberme',
			'id_submit'      => 'wp-submit',
			'label_username' => __( 'Email: ' ),
			'label_password' => __( 'Password: ' ),
			'label_remember' => __( 'Remember Me' ),
			'label_log_in'   => __( 'Log In' ),
			'value_username' => '',
			'value_remember' => false
		);
		wp_login_form( $args );
	}
}

add_shortcode('wpeppsubNCX_login', 'wpeppsubNCX_login');


// logout shortcode
function wpeppsubNCX_logout() {
	if (is_user_logged_in()) {
		echo '<a href="'; echo wp_logout_url( home_url() ); echo'">Logout</a>';
	}
}

add_shortcode('wpeppsubNCX_logout', 'wpeppsubNCX_logout');