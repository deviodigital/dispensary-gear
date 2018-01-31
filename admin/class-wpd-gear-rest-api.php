<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.wpdispensary.com/
 * @since      1.0.0
 *
 * @package    WPD_Gear
 * @subpackage WPD_Gear/admin
 */

/**
 * Adding featured image URL's to Gear Custom Post Type
 */
function gear_featuredimage( $data, $post, $request ) {
	$_data                       = $data->data;
	$thumbnail_id                = get_post_thumbnail_id( $post->ID );
	$thumbnail                   = wp_get_attachment_image_src( $thumbnail_id, 'full' );
	$_data['featured_image_url'] = $thumbnail[0];
	$data->data                  = $_data;
	return $data;
}
add_filter( 'rest_prepare_gear', 'gear_featuredimage', 10, 3 );

/**
 * Add Category taxonomy for the Gear Custom Post Type
 */
function gear_category( $data, $post, $request ) {
	$_data                  = $data->data;
	$_data['gear_category'] = get_the_term_list( $post->ID, 'gear_category', '', ' ', '' );
	$data->data             = $_data;
	return $data;
}
add_filter( 'rest_prepare_gear', 'gear_category', 10, 3 );

/**
 * This adds the wpdispensary_prices metafields to the
 * API callback for gear
 *
 * @since    1.1.0
 */

add_action( 'rest_api_init', 'slug_register_gear_prices' );

/**
 * Registering Prices
 */
function slug_register_gear_prices() {
	$productsizes = array( '_priceeach', '_priceperpack', '_unitsperpack' );
	foreach ( $productsizes as $size ) {
		register_rest_field(
			array( 'gear' ),
			$size,
			array(
				'get_callback'    => 'slug_get_gear_prices',
				'update_callback' => 'slug_update_gear_prices',
				'schema'          => null,
			)
		);
	} /** /foreach */
}

/**
 * Get Prices
 */
function slug_get_gear_prices( $object, $field_name, $request ) {
	return get_post_meta( $object['id'], $field_name, true );
}

/**
 * Update Prices
 */
function slug_update_gear_prices( $value, $object, $field_name ) {
    return update_post_meta( $object[ 'id' ], $field_name, $value );
}