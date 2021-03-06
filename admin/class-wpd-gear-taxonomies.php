<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.wpdispensary.com/
 * @since      1.0.0
 *
 * @package    WPD_Gear
 * @subpackage WPD_Gear/admin
 */

/**
 * Gear Category Taxonomy
 *
 * Adds the Gear Category taxonomy to all custom post types
 *
 * @since    1.0.0
 */
function wp_dispensary_gear_category() {

	$labels = array(
		'name'              => _x( 'Categories', 'taxonomy general name', 'wpd-gear' ),
		'singular_name'     => _x( 'Category', 'taxonomy singular name', 'wpd-gear' ),
		'search_items'      => esc_attr__( 'Search Categories', 'wpd-gear' ),
		'all_items'         => esc_attr__( 'All Categories', 'wpd-gear' ),
		'parent_item'       => esc_attr__( 'Parent Category', 'wpd-gear' ),
		'parent_item_colon' => esc_attr__( 'Parent Category:', 'wpd-gear' ),
		'edit_item'         => esc_attr__( 'Edit Category', 'wpd-gear' ),
		'update_item'       => esc_attr__( 'Update Category', 'wpd-gear' ),
		'add_new_item'      => esc_attr__( 'Add New Category', 'wpd-gear' ),
		'new_item_name'     => esc_attr__( 'New Category Name', 'wpd-gear' ),
		'not_found'         => esc_attr__( 'No categories found', 'wpd-gear' ),
		'menu_name'         => esc_attr__( 'Categories', 'wpd-gear' ),
	);

	register_taxonomy( 'wpd_gear_category', 'gear', array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'query_var'         => true,
		'rewrite'           => array(
			'slug'       => 'gear/category',
			'with_front' => false,
		),
	) );

}
add_action( 'init', 'wp_dispensary_gear_category', 0 );
