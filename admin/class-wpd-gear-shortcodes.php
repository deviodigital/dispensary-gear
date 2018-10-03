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
 * Gear Shortcode Fuction
 */
function wpdispensary_gear_shortcode( $atts ) {
	// Get permalink base for Gear.
	$wpd_gear_slug = get_option( 'wpd_gear_slug' );

	// If custom base is empty, set default.
	if ( '' == $wpd_gear_slug ) {
		$wpd_gear_slug = 'gear';
	}

	// Capitalize first letter of new slug.
	$wpd_gear_slug_cap = ucfirst( $wpd_gear_slug );

	/* Attributes */
	extract( shortcode_atts(
		array(
			'posts'   => '100',
			'class'   => '',
			'name'    => 'show',
			'info'    => 'show',
			'title'   => $wpd_gear_slug_cap,
			'image'   => 'show',
			'imgsize' => 'dispensary-image',
			'viewall' => '',
		),
		$atts,
		'wpd_gear'
	) );

	/**
	 * Code to create shortcode for Dispensary Gear
	 */
	$wpdquery = new WP_Query(
		array(
			'post_type'      => 'gear',
			'posts_per_page' => $posts,
		)
	);

	if ( 'show' === $viewall ) {
		$gearlink = get_bloginfo( 'url' ) . '/gear/';
		$viewgear = '<span class="wp-dispensary-view-all"><a href="' . apply_filters( 'wpd_gear_shortcode_view_all', $gearlink ) .'">(view all)</a></span>';
	} else {
		$viewgear = '';
	}

	$wpdposts = '<div class="wpdispensary"><h2 class="wpd-title">'. $title .'' . $viewgear .'</h2>';

	while ( $wpdquery->have_posts() ) : $wpdquery->the_post();

		if ( '' === $imgsize ) {
			$imagesize = 'dispensary-image';
		} else {
			$imagesize = $imgsize;
		}

		$thumbnail_id        = get_post_thumbnail_id();
		$thumbnail_url_array = wp_get_attachment_image_src( $thumbnail_id, $imagesize, false );
		$thumbnail_url       = $thumbnail_url_array[0];
		$querytitle          = get_the_title();

		$wp_dispensary_options = get_option( 'wp_dispensary_option_name' ); // Array of All Options.
		if ( ! isset( $wp_dispensary_options['wpd_hide_details'] ) ) {
			$wpd_hide_details = '';
		} else {
			$wpd_hide_details = $wp_dispensary_options['wpd_hide_details'];
		}
		if ( ! isset( $wp_dispensary_options['wpd_hide_pricing'] ) ) {
			$wpd_hide_pricing = '';
		} else {
			$wpd_hide_pricing = $wp_dispensary_options['wpd_hide_pricing'];
		}
		if ( ! isset( $wp_dispensary_options['wpd_content_placement'] ) ) {
			$wpd_content_placement = '';
		} else {
			$wpd_content_placement = $wp_dispensary_options['wpd_content_placement'];
		}
		if ( null === $wp_dispensary_options['wpd_currency'] ) {
			$wpd_currency = 'USD';
		} else {
			$wpd_currency = $wp_dispensary_options['wpd_currency'];
		}
		if ( null === $wp_dispensary_options['wpd_cost_phrase'] ) {
			$wpd_cost_phrase = 'Price';
		} else {
			$wpd_cost_phrase = $wp_dispensary_options['wpd_cost_phrase']; // costphrase.
		}

		if( get_post_type() == 'gear' ) {
			if ( get_post_meta( get_the_ID(), '_priceeach', true ) ) {
				$pricingeach = '<strong>'. $wpd_cost_phrase .':</strong> ' . wpd_currency_code() . '' . get_post_meta( get_the_id(), '_priceeach', true ) . ' each';
			} else {
				$pricingeach = '';
			}
			if ( get_post_meta( get_the_ID(), '_priceperpack', true ) ) {
				$pricingperpack = ' &middot; ' . get_post_meta( get_the_id(), '_unitsperpack', true ) . ' for ' . wpd_currency_code() . '' . get_post_meta( get_the_id(), '_priceperpack', true );
			} else {
				$pricingperpack = '';
			}
		}

		/** Check shortcode options input by user */

		if ( $name == "show" ) {
			$showname = '<p><strong><a href="' . get_permalink() . '">' . $querytitle . '</a></strong></p>';
		} else {
			$showname = '';
		}

		if( get_post_type() == 'gear' ) {
			if ( $info == "show" ) {
				$showinfo = '<span class="wpd-productinfo">' . $pricingeach . '' . $pricingperpack . '</span>';
			} else {
				$showinfo = '';
			}
		}

		if ( 'show' === $image ) {
			if ( null === $thumbnail_url && 'full' === $imagesize ) {
				$wpd_shortcodes_default_image = site_url() . '/wp-content/plugins/wp-dispensary/public/images/wpd-large.jpg';
				$defaultimg                   = apply_filters( 'wpd_shortcodes_default_image', $wpd_shortcodes_default_image );
				$showimage                    = '<a href="' . get_permalink() . '"><img src="' . $defaultimg . '" alt="Menu - ' . $wpd_gear_slug_cap . '" /></a>';
			} elseif ( null !== $thumbnail_url ) {
				$showimage = '<a href="' . get_permalink() . '"><img src="' . $thumbnail_url . '" alt="Menu - ' . $wpd_gear_slug_cap . '" /></a>';
			} else {
				$wpd_shortcodes_default_image = site_url() . '/wp-content/plugins/wp-dispensary/public/images/' . $imagesize . '.jpg';
				$defaultimg                   = apply_filters( 'wpd_shortcodes_default_image', $wpd_shortcodes_default_image );
				$showimage                    = '<a href="' . get_permalink() . '"><img src="' . $defaultimg . '" alt="Menu - ' . $wpd_gear_slug_cap . '" /></a>';
			}
		} else {
			$showimage = '';
		}

		/** Shortcode display */

		ob_start();
			do_action( 'wpd_shortcode_inside_top' );
			$wpd_shortcode_inside_top = ob_get_contents();
		ob_end_clean();

		ob_start();
			do_action( 'wpd_shortcode_top_gear' );
			$wpd_shortcode_top_gear = ob_get_contents();
		ob_end_clean();

		$wpdposts .= '<div class="wpdshortcode wpd-gear ' . $class .'">'. $wpd_shortcode_top_gear .''. $wpd_shortcode_inside_top .''. $showimage;

		ob_start();
			do_action( 'wpd_shortcode_bottom_gear' );
			$wpd_shortcode_bottom_gear = ob_get_contents();
		ob_end_clean();

		ob_start();
			do_action( 'wpd_shortcode_inside_bottom' );
			$wpd_shortcode_inside_bottom = ob_get_contents();
		ob_end_clean();

		$wpdposts .= $showname .''. $showinfo .''. $wpd_shortcode_inside_bottom .''. $wpd_shortcode_bottom_gear .'</div>';

	endwhile;

	wp_reset_postdata();

	return $wpdposts . '</div>';

}
add_shortcode( 'wpd-gear', 'wpdispensary_gear_shortcode' );
