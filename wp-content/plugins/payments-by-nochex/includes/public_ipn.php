<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// IPN post
function wpeppsubNCX_listen_for_nochex_ipn() {

if (isset($_REQUEST['wpeppsubNCX-listener']) && $_REQUEST['wpeppsubNCX-listener'] == 'IPN') {

	$postvars = http_build_query($_POST);

	// Set parameters for the email
	$url = "https://secure.nochex.com/callback/callback.aspx";

	// Curl code to post variables back
	$ch = curl_init(); // Initialise the curl tranfer
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_VERBOSE, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars); // Set POST fields
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$response = curl_exec($ch); // Post back
	
	// stores the response from the Nochex server 
	$debug = "IP -> " . $_SERVER['REMOTE_ADDR'] ."\r\n\r\nPOST DATA:\r\n"; 
	foreach($_POST as $Index => $Value) 
	$debug .= "$Index -> $Value\r\n"; 
	$debug .= "\r\nRESPONSE:\r\n$response"; 		
	
	if ($_POST["transaction_status"] == "100"){
		$testStatus = "test";
	}else{
		$testStatus = "live";
	}
	
	
	if ($response == "AUTHORISED") {  // searches response to see if AUTHORISED is present if it isn’t a failure message is displayed
		$msg = "APC was AUTHORISED. This was a " . $testStatus . " transaction.";// if AUTHORISED was found in the response then it was successful
	} else { 
	   $msg = "APC was not AUTHORISED.\r\n\r\n$debug";  // displays debug message  
	} 
	 
					$post_type = "wpplugin_subscr"; 
					$payer_email = $_POST["email_address"];
					$billing_fullname = $_POST["billing_fullname"];
					$billing_address = $_POST["billing_address"];
					$billing_postcode = $_POST["billing_postcode"];
					$customer_phone_number = $_POST["customer_phone_number"];
					  
						$user_id = username_exists( $payer_email );
						
						if (email_exists($payer_email) == false) {
							$random_password = wp_generate_password($length=12, $include_standard_special_chars=false);
							$user_id = wp_create_user($payer_email, $random_password, $payer_email);
							wp_send_new_user_notifications($user_id);
						} else {
							// user already exists
										
			$emailBody = "Hello ". $billing_fullname. ",<br/><br/>			
							Thank you for signing up, below is your confirmation. Login to your account <a href=". esc_url( wp_login_url( get_permalink() ) ) .">here</a><br/><br/>
							<table>
							<tr><td>Name: </td><td>".$billing_fullname."</td></tr>
							<tr><td>Address: </td><td>".$billing_address."</td></tr>
							<tr><td>Postcode: </td><td>".$billing_postcode."</td></tr>
							<tr><td>Email Address: </td><td>".$payer_email."</td></tr>
							<tr><td>Mobile / Telephone: </td><td>".$customer_phone_number."</td></tr>
							<tr><td>Total Paid: </td><td>".$_POST["amount"]."</td></tr> 
							<tr><td>Paid Date: </td><td>".$_POST["transaction_date"]."</td></tr>
							<tr><td>Order Ref: </td><td>".$_POST["optional_1"]."</td></tr>
						  </table><br/>
						  Kind Regards,<br/>
						  ". $_POST["merchant_id"] ."
						  ";
		
		    $headersfrom  = '';
            $headersfrom .= 'MIME-Version: 1.0' . "\r\n";
            $headersfrom .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headersfrom .= 'From: '. $_POST["merchant_id"] .' '. "\r\n";

			mail($payer_email, "Membership " . $_POST["optional_1"] . " for " . $billing_fullname, $emailBody, $headersfrom); 
 
						} 
				
				// save to db
				$my_post = array(
					'post_title'    => "Nochex Subscription",
					'post_status'   => 'publish',
					'post_type'     => $post_type
				);
				$posts_id = wp_insert_post($my_post);
					
					
				$data = array("order_id"=>$posts_id, "amount"=>$_POST["amount"], "payment_date"=>$_POST["transaction_date"], "payer_email"=>$payer_email, "payment_status"=>"Active", "transaction_id"=>$_POST["transaction_id"], "custom"=>$_POST["optional_1"], "billing_name"=>$billing_fullname, "address"=>$billing_address, "postcode"=>$billing_postcode, "phone"=>$customer_phone_number, "amount3"=>$_POST["optional_2"], "period3"=>$_POST["optional_3"]);
					 
					 
				// save post data*/
				update_post_meta($posts_id, 'wpeppsubNCX_order_data', $data);
						

					$args = array(

							'orderby' 			=> 'ID',

							'order' 			=> 'DESC',

							'posts_per_page'	=> -1,

							'post_type' 		=> 'wpplugin_subscr',

							'meta_query' 		=> array(

								'relation'=>'or',

								array(

									'key' 		=> 'wpeppsubNCX_order_data',

									'value' 	=> $posts_id,

									'compare' 	=> 'LIKE',

							   )

							)

					);
					
					$posts = get_posts($args);
					
					$count = "0";
					foreach ($posts as $post) {
						$id = esc_attr($posts[$count]->ID);
						$count++;
					}

					// not expired yet
					$status = "Active";
					update_post_meta($posts_id, 'wpeppsubNCX_order_status', $status);
					  
					  
					$setExpiry = date('Y-m-d', $date); 
					update_post_meta($posts_id, 'wpeppsubNCX_expiry_date', $setExpiry);
	
}else if (isset($_REQUEST['wpeppsubNCX-listener']) && $_REQUEST['wpeppsubNCX-listener'] == 'SCS'){
 

get_header();

?>
<div id="content" class="site-content">
<div class="wrap">
<h2>Success</h2>
<p>You have successfully paid, please check your email .</p>
</div>
</div>
<?php
get_footer();

}

}

add_action( 'init', 'wpeppsubNCX_listen_for_nochex_ipn' );
