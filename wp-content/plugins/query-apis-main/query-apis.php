<?php
/**
 * Plugin name: Pixel APIs
 * Plugin URI: https://github.com/pushkar2793/
 * Description: Get information from external APIs in WordPress
 * Author: Pushkar Dahibhate
 * Author URI: https://github.com/pushkar2793/
 * version: 0.1.0
 * License: GPL2 or later.
 * text-domain: pixel-apis
 */

// If this file is access directly, abort!!!
defined( 'ABSPATH' ) or die( 'Unauthorized Access' );

add_shortcode('pixel_api_data', 'techiepress_get_send_data');

function techiepress_get_send_data() {

	$api_key = "563492ad6f91700001000001cf8df85af4cd4a85b9d7aa13d68d555f";
    $url = 'https://api.pexels.com/v1/search?query=Beauty+Salon&per_page=100';
    
   $args = array(
    'method' => 'GET',
    'headers' => array(
        'Authorization' => 'Bearer ' .$api_key
    )
	);

	$response = wp_remote_get( $url, $args );

	if ( is_wp_error( $response ) ) {
		$error_message = $response->get_error_message();
		return "Something went wrong: $error_message";
	} 
		$apiResult = json_decode(wp_remote_retrieve_body( $response ));
		// echo "<pre>";
		// print_r($apiResult);
		
		$html = '';
		$html .= '<div class="containerPixel">';
		$html .= '<div class="galleryPixel">';
		$html .= '<div class="boxPixel">';
		
		foreach($apiResult->photos as $res){
			$html .= '<a href="'.$res->src->medium. '" title="'.$res->photographer.'">';
			$html .= '<img src="'.$res->src->tiny. '" alt="'.$res->photographer.'">';
			$html .= '</a>';
		}
		
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';
		
		return $html;
	
}	

/**
 * Register a custom menu page to view the information queried.
 */
function techiepress_register_my_custom_menu_page() {
	add_menu_page(
		__( 'Query API Test Settings', 'query-apis' ),
		'Pixel API Test',
		'manage_options',
		'api-test.php',
		'techiepress_get_send_data',
		'dashicons-testimonial',
		16
	);
}

add_action( 'admin_menu', 'techiepress_register_my_custom_menu_page' );
