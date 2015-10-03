<?php
/**
 * Plugin Name: Posts in Sidebar
 * Plugin URI: http://dev.aldolat.it/projects/posts-in-sidebar/
 * Description: Publish a list of posts in your sidebar
 * Version: 3.0
 * Author: Aldo Latino
 * Author URI: http://www.aldolat.it/
 * Text Domain: posts-in-sidebar
 * Domain Path: /languages/
 * License: GPLv3 or later
 */

/* Copyright (C) 2009, 2015  Aldo Latino  (email : aldolat@gmail.com)

   This program is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program. If not, see <http://www.gnu.org/licenses/>.
*/


/**
 * Prevent direct access to this file.
 *
 * @since 2.0
 */
if ( ! defined( 'WPINC' ) ) {
	exit( 'No script kiddies please!' );
}


/**
 * Launch Posts in Sidebar.
 *
 * @since 1.27
 */
add_action( 'plugins_loaded', 'pis_setup' );


/**
 * Setup Posts in Sidebar.
 *
 * @since 1.27
 */
function pis_setup() {

	/**
	 * Define the version of the plugin.
	 */
	define( 'PIS_VERSION', '3.0' );

	/**
	 * Make plugin available for i18n.
	 * Translations must be archived in the /languages/ directory.
	 * The name of each translation file must be, for example:
	 *
	 * ITALIAN:
	 * posts-in-sidebar-it_IT.po
	 * posts-in-sidebar-it_IT.mo
	 *
	 * GERMAN:
	 * posts-in-sidebar-de_DE.po
	 * posts-in-sidebar-de_DE.po
	 *
	 * and so on.
	 *
	 * @since 0.1
	 */
	load_plugin_textdomain( 'posts-in-sidebar', false, dirname( plugin_basename( __FILE__ ) ) . '/languages');

	/**
	 * Load the plugin's functions.
	 *
	 * @since 1.23
	 */
	require_once( plugin_dir_path( __FILE__ ) . 'inc/pis-functions.php' );

	/**
	 * Load Posts in Sidebar's widgets.
	 */
	add_action( 'widgets_init', 'pis_load_widgets' );

	/**
	 * Load the shortcode.
	 *
	 * @since 3.0
	 */
	require_once( plugin_dir_path( __FILE__ ) . 'inc/pis-shortcode.php' );

	/**
	 * Load the script.
	 *
	 * @since 1.29
	 */
	add_action( 'admin_enqueue_scripts', 'pis_load_scripts' );
}


/**
 * Load the Javascript file.
 * The file will be loaded only in the widgets admin page.
 *
 * @since 1.29
 */
function pis_load_scripts( $hook ) {
 	if ( $hook != 'widgets.php' ) {
		return;
	}

	// Register and enqueue the JS file
	wp_register_script( 'pis_js', plugins_url( 'inc/pis.js', __FILE__ ), array( 'jquery' ), PIS_VERSION, false );
	wp_enqueue_script( 'pis_js' );

	// Register and enqueue the CSS file
	wp_register_style( 'pis_style', plugins_url( 'inc/pis.css', __FILE__ ), array(), PIS_VERSION, 'all' );
	wp_enqueue_style( 'pis_style' );
}


/**
 * Register the widget
 *
 * @since 1.0
 */
function pis_load_widgets() {

	/**
	 * Load the widget's form functions.
	 *
	 * @since 1.12
	 */
	require_once( plugin_dir_path( __FILE__ ) . 'inc/pis-widget-form-functions.php' );

	/**
	 * Load the widget's PHP file.
	 *
	 * @since 1.1
	 */
	require_once( plugin_dir_path( __FILE__ ) . 'inc/pis-widget.php' );

	register_widget( 'PIS_Posts_In_Sidebar' );
}


/**
 * The core function.
 *
 * @since 1.0
 * @param mixed $args The options for the main function.
 * @return string The HTML output.
 */
function pis_get_posts_in_sidebar( $args ) {
	$defaults = array(
		// The custom container class
		'container_class'     => '',

		// The title of the widget
		'title'               => __( 'Posts', 'posts-in-sidebar' ),
		'title_link'          => '',
		'intro'               => '',

		// Posts retrieving
		'post_type'           => 'post',    // post, page, attachment, or any custom post type
		'posts_id'            => '',        // Post/Pages IDs, comma separated
		'author'              => '',        // Author nicename
		'author_in'           => '',        // Author IDs
		'cat'                 => '',        // Category slugs, comma separated
		'tag'                 => '',        // Tag slugs, comma separated
		'post_parent_in'      => '',
		'post_format'         => '',
		'number'              => get_option( 'posts_per_page' ),
		'orderby'             => 'date',
		'order'               => 'DESC',
		'offset_number'       => '',
		'post_status'         => 'publish',
		'post_meta_key'       => '',
		'post_meta_val'       => '',
		/* The 's' (search) parameter must be not declared or must be empty
		 * otherwise it will break sticky posts.
		 */
		'search'              => NULL,
		'ignore_sticky'       => false,

		// Taxonomies
		'relation'            => '',

		'taxonomy_aa'         => '',
		'field_aa'            => 'slug',
		'terms_aa'            => '',
		'operator_aa'         => 'IN',

		'relation_a'          => '',

		'taxonomy_ab'         => '',
		'field_ab'            => 'slug',
		'terms_ab'            => '',
		'operator_ab'         => 'IN',

		'taxonomy_ba'         => '',
		'field_ba'            => 'slug',
		'terms_ba'            => '',
		'operator_ba'         => 'IN',

		'relation_b'          => '',

		'taxonomy_bb'         => '',
		'field_bb'            => 'slug',
		'terms_bb'            => '',
		'operator_bb'         => 'IN',

		// Date query
		'date_year'           => '',
		'date_month'          => '',
		'date_week'           => '',
		'date_day'            => '',
		'date_hour'           => '',
		'date_minute'         => '',
		'date_second'         => '',
		'date_after_year'     => '',
		'date_after_month'    => '',
		'date_after_day'      => '',
		'date_before_year'    => '',
		'date_before_month'   => '',
		'date_before_day'     => '',
		'date_inclusive'      => false,
		'date_column'         => '',

		// Posts exclusion
		'author_not_in'       => '',
		'exclude_current_post'=> false,
		'post_not_in'         => '',
		'cat_not_in'          => '',        // Category ID, comma separated
		'tag_not_in'          => '',        // Tag ID, comma separated
		'post_parent_not_in'  => '',

		// The title of the post
		'display_title'       => true,
		'link_on_title'       => true,
		'title_tooltip'       => __( 'Permalink to', 'posts-in-sidebar' ),
		'arrow'               => false,

		// The featured image of the post
		'display_image'       => false,
		'image_size'          => 'thumbnail',
		'image_align'         => 'no_change',
		'image_before_title'  => false,
		'image_link'          => '',
		'custom_image_url'    => '',
		'custom_img_no_thumb' => true,

		// The text of the post
		'excerpt'             => 'excerpt', // can be "full_content", "rich_content", "content", "more_excerpt", "excerpt", "none"
		'exc_length'          => 20,        // In words
		'the_more'            => __( 'Read more&hellip;', 'posts-in-sidebar' ),
		'exc_arrow'           => false,

		// Author, date and comments
		'display_author'      => false,
		'author_text'         => __( 'By', 'posts-in-sidebar' ),
		'linkify_author'      => false,
		'gravatar_display'    => false,
		'gravatar_size'       => 32,
		'gravatar_default'    => '',
		'gravatar_position'   => 'next_author',
		'display_date'        => false,
		'date_text'           => __( 'Published on', 'posts-in-sidebar' ),
		'linkify_date'        => false,
		'comments'            => false,
		'comments_text'       => __( 'Comments:', 'posts-in-sidebar' ),
		'linkify_comments'    => true,
		'utility_sep'         => '|',
		'utility_after_title' => false,

		// The categories of the post
		'categories'          => false,
		'categ_text'          => __( 'Category:', 'posts-in-sidebar' ),
		'categ_sep'           => ',',

		// The tags of the post
		'tags'                => false,
		'tags_text'           => __( 'Tags:', 'posts-in-sidebar' ),
		'hashtag'             => '#',
		'tag_sep'             => '',

		// The custom taxonomies of the post
		'display_custom_tax'  => false,
		'term_hashtag'        => '',
		'term_sep'            => ',',

		// The custom field
		'custom_field'        => false,
		'custom_field_txt'    => '',
		'meta'                => '',
		'custom_field_key'    => false,
		'custom_field_sep'    => ':',

		// The link to the archive
		'archive_link'        => false,
		'link_to'             => 'category',
		'tax_name'            => '',
		'tax_term_name'       => '',
		'archive_text'        => __( 'Display all posts', 'posts-in-sidebar' ),

		// When no posts found
		'nopost_text'         => __( 'No posts yet.', 'posts-in-sidebar' ),
		'hide_widget'         => false,

		// Styles
		'margin_unit'         => 'px',
		'intro_margin'        => NULL,
		'title_margin'        => NULL,
		'side_image_margin'   => NULL,
		'bottom_image_margin' => NULL,
		'excerpt_margin'      => NULL,
		'utility_margin'      => NULL,
		'categories_margin'   => NULL,
		'tags_margin'         => NULL,
		'terms_margin'        => NULL,
		'custom_field_margin' => NULL,
		'archive_margin'      => NULL,
		'noposts_margin'      => NULL,
		'custom_styles'       => '',

		// Extras
		'list_element'        => 'ul',
		'remove_bullets'      => false,

		// Cache
		'cached'              => false,
		'cache_time'          => 3600,
		'widget_id'           => '',

		// Debug
		'debug_query'         => false,
		'debug_params'        => false,
		'debug_query_number'  => false,

	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );

	/**
	 * Check if $author or $cat or $tag are equal to 'NULL' (string).
	 * If so, make them empty.
	 * For more informations, see inc/posts-in-sidebar-widget.php, function update().
	 *
	 * @since 1.28
	 */
	if ( 'NULL' == $author ) $author = '';
	if ( 'NULL' == $cat ) $cat = '';
	if ( 'NULL' == $tag ) $tag = '';

	/**
	 * Some params accept only an array.
	 */
	if ( $posts_id    && ! is_array( $posts_id ) )    $posts_id    = explode( ',', $posts_id );    else $posts_id    = '';
	if ( $post_not_in && ! is_array( $post_not_in ) ) $post_not_in = explode( ',', $post_not_in ); else $post_not_in = '';
	if ( $cat_not_in  && ! is_array( $cat_not_in ) )  $cat_not_in  = explode( ',', $cat_not_in );  else $cat_not_in  = '';
	if ( $tag_not_in  && ! is_array( $tag_not_in ) )  $tag_not_in  = explode( ',', $tag_not_in );  else $tag_not_in  = '';
	if ( $author_in   && ! is_array( $author_in ) )   $author_in   = explode( ',', $author_in );   else $author_in   = '';
	if ( $author_not_in   && ! is_array( $author_not_in ) )   $author_not_in   = explode( ',', $author_not_in );   else $author_not_in   = '';
	if ( $post_parent_in  && ! is_array( $post_parent_in ) )  $post_parent_in  = explode( ',', $post_parent_in );  else $post_parent_in  = '';
	if ( $post_parent_not_in  && ! is_array( $post_parent_not_in ) )  $post_parent_not_in  = explode( ',', $post_parent_not_in );  else $post_parent_not_in  = '';

	/**
	 * Build $tax_query parameter (if any).
	 * It must be an array of array.
	 *
	 * @since 1.29
	 */
	$tax_query = pis_tax_query( array(
		'relation'    => $relation,
		'taxonomy_aa' => $taxonomy_aa,
		'field_aa'    => $field_aa,
		'terms_aa'    => $terms_aa,
		'operator_aa' => $operator_aa,
		'relation_a'  => $relation_a,
		'taxonomy_ab' => $taxonomy_ab,
		'field_ab'    => $field_ab,
		'terms_ab'    => $terms_ab,
		'operator_ab' => $operator_ab,
		'taxonomy_ba' => $taxonomy_ba,
		'field_ba'    => $field_ba,
		'terms_ba'    => $terms_ba,
		'operator_ba' => $operator_ba,
		'relation_b'  => $relation_b,
		'taxonomy_bb' => $taxonomy_bb,
		'field_bb'    => $field_bb,
		'terms_bb'    => $terms_bb,
		'operator_bb' => $operator_bb,
	) );

	/**
	 * Build the array for date query.
	 * It must be an array of array.
	 *
	 * @since 1.29
	 */
	$date_query = array(
		array(
			'year'      => $date_year,
			'month'     => $date_month,
			'week'      => $date_week,
			'day'       => $date_day,
			'hour'      => $date_hour,
			'minute'    => $date_minute,
			'second'    => $date_second,
			'after'     => array (
				'year'  => $date_after_year,
				'month' => $date_after_month,
				'day'   => $date_after_day,
			),
			'before'    => array (
				'year'  => $date_before_year,
				'month' => $date_before_month,
				'day'   => $date_before_day,
			),
			'inclusive' => $date_inclusive,
			'column'    => $date_column,
		)
	);
	$date_query = pis_array_remove_empty_keys( $date_query, true );

	/**
	 * Get the ID of the current post.
	 * This will be used in case the user do not want to display the same post in the main body and in the sidebar.
	 */
	if ( ( is_single() || is_page() ) && $exclude_current_post ) {
		$post_not_in[] = get_the_id();
	}

	/**
	 * If $post_type is 'attachment', $post_status must be 'inherit'.
	 *
	 * @see https://codex.wordpress.org/Class_Reference/WP_Query#Type_Parameters
	 * @since 1.28
	 */
	if ( 'attachment' == $post_type ) {
		$post_status = 'inherit';
	}

	/**
	 * If $post_in is not empy, make $author empty,
	 * otherwise WordPress will use $author.
	 *
	 * @since 3.0
	 */
	if ( ! empty( $author_in ) ) $author = '';

	// Build the array to get posts
	$params = array(
		'post_type'           => $post_type,
		'post__in'            => $posts_id,    // Uses ids
		'author_name'         => $author,      // Uses nicenames
		'author__in'          => $author_in,   // Uses ids
		'category_name'       => $cat,         // Uses category slugs
		'tag'                 => $tag,         // Uses tag slugs
		'tax_query'           => $tax_query,   // Uses an array of array
		'date_query'          => $date_query,  // Uses an array of array
		'post_parent__in'     => $post_parent_in,
		'post_format'         => $post_format,
		'posts_per_page'      => $number,
		'orderby'             => $orderby,
		'order'               => $order,
		'author__not_in'      => $author_not_in,
		'post__not_in'        => $post_not_in,        // Uses ids
		'category__not_in'    => $cat_not_in,         // Uses ids
		'tag__not_in'         => $tag_not_in,         // uses ids
		'post_parent__not_in' => $post_parent_not_in, // uses ids
		'offset'              => $offset_number,
		'post_status'         => $post_status,
		'meta_key'            => $post_meta_key,
		'meta_value'          => $post_meta_val,
		's'                   => $search,
		'ignore_sticky_posts' => $ignore_sticky
	);

	// If the user has chosen a cached version of the widget output...
	if ( $cached ) {

		// Get the cached query
		$pis_query = get_transient( $widget_id . '_query_cache' );

		// If it does not exist, create a new query and cache it for future uses
		if ( ! $pis_query ) {
			$pis_query = new WP_Query( $params );
			set_transient( $widget_id . '_query_cache', $pis_query, $cache_time );
		}

	// ... otherwise serve a not-cached version of the output.
	} else {

		$pis_query = new WP_Query( $params );

	}

	// If in a single post, get the ID of the post of the main loop. This will be used to add the "current-post" CSS class.
	if ( is_single() ) {
		global $post;
		$single_post_id = $post->ID;
	}

	/**
	 * Define the main variable that will concatenate all the output;
	 *
	 * @since 3.0
	 */
	$pis_output = '';

	/* The Loop */
	if ( $pis_query->have_posts() ) : ?>

		<?php if ( $intro ) {
			$pis_output = '<p ' . pis_paragraph( $intro_margin, $margin_unit, 'pis-intro', 'pis_intro_class' ) . '>' . pis_break_text( $intro ) . '</p>';
		}

		// When updating from 1.14, the $list_element variable is empty.
		if ( ! $list_element ) $list_element = 'ul';

		if ( $remove_bullets && 'ul' == $list_element ) {
			$bullets_style = ' style="list-style-type:none; margin-left:0; padding-left:0;"';
		} else {
			$bullets_style = '';
		}
		$pis_output .= '<' . $list_element . ' ' . pis_class( 'pis-ul', apply_filters( 'pis_ul_class', '' ), false ) . $bullets_style . '>';

			while ( $pis_query->have_posts() ) : $pis_query->the_post(); ?>

				<?php /**
				 * Assign the class 'current-post' if this is the post of the main loop.
				 *
				 * @since 1.6
				 */
				$current_post_class = '';
				if ( is_single() && $single_post_id == $pis_query->post->ID ) {
					$current_post_class = ' current-post';
				}

				/**
				 * Assign the class 'sticky' if the post is sticky.
				 *
				 * @since 1.25
				 */
				 $sticky_class = '';
				 if ( is_sticky() ) {
				 	$sticky_class = ' sticky';
				 }

				$pis_output .= '<li ' . pis_class( 'pis-li' . $current_post_class . $sticky_class, apply_filters( 'pis_li_class', '' ), false ) . '>';

					/* The thumbnail before the title */
					if ( $image_before_title ) {

						if ( 'attachment' == $post_type || ( $display_image && ( has_post_thumbnail() || $custom_image_url ) ) ) {
							$post_link = $title_tooltip . ' ' . the_title_attribute( 'echo=0' );
							$pis_output .= pis_the_thumbnail( array(
								'display_image'       => $display_image,
								'image_align'         => $image_align,
								'side_image_margin'   => $side_image_margin,
								'bottom_image_margin' => $bottom_image_margin,
								'margin_unit'         => $margin_unit,
								'post_link'           => $post_link,
								'pis_query'           => $pis_query,
								'image_size'          => $image_size,
								'thumb_wrap'          => true,
								'custom_image_url'    => $custom_image_url,
								'custom_img_no_thumb' => $custom_img_no_thumb,
								'post_type'           => $post_type,
								'image_link'          => $image_link,
							) );
						}

					}
					// Close if $image_before_title

					/* The title */
					if ( $display_title ) {
						$pis_output .= '<p ' . pis_paragraph( $title_margin, $margin_unit, 'pis-title', 'pis_title_class' ) . '>';
							/* The Gravatar */
							if ( $gravatar_display && 'next_title' == $gravatar_position ) {
								$pis_output .= pis_get_gravatar( array(
									'author'  => get_the_author_meta( 'ID' ),
									'size'    => $gravatar_size,
									'default' => $gravatar_default
								) );
							}
							if ( $link_on_title ) {
								$post_link = $title_tooltip . ' ' . the_title_attribute( 'echo=0' );
								$pis_output .= '<a ' . pis_class( 'pis-title-link', apply_filters( 'pis_title_link_class', '' ), false ) . ' href="' . get_permalink() . '" title="' . esc_attr( $post_link ) . '" rel="bookmark">';
							}
							$pis_output .= get_the_title();
							if ( $arrow ) {
								$pis_output .= pis_arrow();
							}
							if ( $link_on_title ) {
								$pis_output .= '</a>';
							}
						$pis_output .= '</p>';
					} // Close Display title

					/* The author, the date and the comments */
					if ( $utility_after_title ) {

						$pis_output .= pis_utility_section( array(
							'display_author'    => $display_author,
							'display_date'      => $display_date,
							'comments'          => $comments,
							'utility_margin'    => $utility_margin,
							'margin_unit'       => $margin_unit,
							'author_text'       => $author_text,
							'linkify_author'    => $linkify_author,
							'utility_sep'       => $utility_sep,
							'date_text'         => $date_text,
							'linkify_date'      => $linkify_date,
							'comments_text'     => $comments_text,
							'pis_post_id'       => $pis_query->post->ID,
							'link_to_comments'  => $linkify_comments,
							'gravatar_display'  => $gravatar_display,
							'gravatar_position' => $gravatar_position,
							'gravatar_author'   => get_the_author_meta( 'ID' ),
							'gravatar_size'     => $gravatar_size,
							'gravatar_default'  => $gravatar_default,
						) );

					}

					/* The post content */
					if ( ! post_password_required() ) {

						if ( 'attachment' == $post_type || ( $display_image && ( has_post_thumbnail() || $custom_image_url ) ) || 'none' != $excerpt ) :

							$pis_output .= '<p ' . pis_paragraph( $excerpt_margin, $margin_unit, 'pis-excerpt', 'pis_excerpt_class' ) . '>';

								if ( ! $image_before_title ) {

									/* The thumbnail */
									if ( 'attachment' == $post_type || ( $display_image && ( has_post_thumbnail() || $custom_image_url ) ) ) {
										$post_link = $title_tooltip . ' ' . the_title_attribute( 'echo=0' );
										$pis_output .= pis_the_thumbnail( array(
											'display_image'       => $display_image,
											'image_align'         => $image_align,
											'side_image_margin'   => $side_image_margin,
											'bottom_image_margin' => $bottom_image_margin,
											'margin_unit'         => $margin_unit,
											'post_link'           => $post_link,
											'pis_query'           => $pis_query,
											'image_size'          => $image_size,
											'thumb_wrap'          => false,
											'custom_image_url'    => $custom_image_url,
											'custom_img_no_thumb' => $custom_img_no_thumb,
											'post_type'           => $post_type,
											'image_link'          => $image_link,
										) );
									} // Close if ( $display_image && has_post_thumbnail )

								}
								// Close if $image_before_title

								/* The Gravatar */
								if ( $gravatar_display && 'next_post' == $gravatar_position ) {
									$pis_output .= pis_get_gravatar( array(
										'author'  => get_the_author_meta( 'ID' ),
										'size'    => $gravatar_size,
										'default' => $gravatar_default
									) );
								}

								/* The text */
								$pis_output .= pis_the_text( array(
									'excerpt'    => $excerpt,
									'pis_query'  => $pis_query,
									'exc_length' => $exc_length,
									'the_more'   => $the_more,
									'exc_arrow'  => $exc_arrow,
								) );

							$pis_output .= '</p>';

						endif;
						// Close if $display_image

					}
					// Close if post password required

					/* The author, the date and the comments */
					if ( ! $utility_after_title ) {

						$pis_output .= pis_utility_section( array(
							'display_author'    => $display_author,
							'display_date'      => $display_date,
							'comments'          => $comments,
							'utility_margin'    => $utility_margin,
							'margin_unit'       => $margin_unit,
							'author_text'       => $author_text,
							'linkify_author'    => $linkify_author,
							'utility_sep'       => $utility_sep,
							'date_text'         => $date_text,
							'linkify_date'      => $linkify_date,
							'comments_text'     => $comments_text,
							'pis_post_id'       => $pis_query->post->ID,
							'link_to_comments'  => $linkify_comments,
							'gravatar_display'  => $gravatar_display,
							'gravatar_position' => $gravatar_position,
							'gravatar_author'   => get_the_author_meta( 'ID' ),
							'gravatar_size'     => $gravatar_size,
							'gravatar_default'  => $gravatar_default,
						) );

					}

					/* The categories */
					if ( $categories ) {
						$list_of_categories = get_the_term_list( $pis_query->post->ID, 'category', '', $categ_sep . ' ', '' );
						if ( $list_of_categories ) {
							$pis_output .= '<p ' . pis_paragraph( $categories_margin, $margin_unit, 'pis-categories-links', 'pis_categories_class' ) . '>';
								if ( $categ_text ) $pis_output .= $categ_text . '&nbsp';
								$pis_output .= apply_filters(  'pis_categories_list', $list_of_categories );
							$pis_output .= '</p>';
						}
					}

					/* The tags */
					if ( $tags ) {
						$list_of_tags = get_the_term_list( $pis_query->post->ID, 'post_tag', $hashtag, $tag_sep . ' ' . $hashtag, '' );
						if ( $list_of_tags ) {
							$pis_output .= '<p ' . pis_paragraph( $tags_margin, $margin_unit, 'pis-tags-links', 'pis_tags_class' ) . '>';
								if ( $tags_text ) $pis_output .= $tags_text . '&nbsp;';
								$pis_output .= apply_filters( 'pis_tags_list', $list_of_tags );
							$pis_output .= '</p>';
						}
					}

					/* Custom taxonomies */
					if ( $display_custom_tax ) {
						$pis_output .= pis_custom_taxonomies_terms_links( array(
							'postID'       => $pis_query->post->ID,
							'term_hashtag' => $term_hashtag,
							'term_sep'     => $term_sep,
							'terms_margin' => $terms_margin,
							'margin_unit'  => $margin_unit,
						) );
					}

					/* The post meta */
					if ( $custom_field ) {
						$the_custom_field = get_post_meta( $pis_query->post->ID, $meta, false );
						if ( $the_custom_field ) {
							if ( $custom_field_txt )
								$cf_text = '<span class="pis-custom-field-text-before">' . $custom_field_txt . ' </span>';
							else
								$cf_text = '';
							if ( $custom_field_key )
								$key = '<span class="pis-custom-field-key">' . $meta . '</span>' . '<span class="pis-custom-field-divider">' . $custom_field_sep . '</span> ';
							else
								$key = '';
							$cf_value = '<span class="pis-custom-field-value">' . $the_custom_field[0] . '</span>';
							$pis_output .= '<p ' . pis_paragraph( $custom_field_margin, $margin_unit, 'pis-custom-field', 'pis_custom_fields_class' ) . '>';
								$pis_output .= $cf_text . $key . $cf_value;
							$pis_output .= '</p>';
						}
					}

				$pis_output .= '</li>';

			endwhile;
			// Close while

		$pis_output .= '</' . $list_element . '>';
		$pis_output .= '<!-- / ul#pis-ul -->';

		/* The link to the entire archive */
		if ( $archive_link ) {
			$pis_output .= pis_archive_link( array(
				'link_to'        => $link_to,
				'tax_name'       => $tax_name,
				'tax_term_name'  => $tax_term_name,
				'archive_text'   => $archive_text,
				'archive_margin' => $archive_margin,
				'margin_unit'    => $margin_unit
			) );
		} ?>

	<?php /* If we have no posts yet */
	else :

		if ( $nopost_text ) {
			$pis_output .= '<p ' . pis_paragraph( $noposts_margin, $margin_unit, 'pis-noposts noposts', 'pis_noposts_class' ) . '>';
				$pis_output .= $nopost_text;
			$pis_output .= '</p>';
		}
		if ( $hide_widget ) {
			$pis_output .= '<style type="text/css">#' . $widget_id . ' { display: none; }</style>';
		}

	endif;

	/* Debugging */
	$pis_output .= pis_debug( array(
		'debug_query'        => $debug_query,          // bool   If display the parameters for the query.
		'debug_params'       => $debug_params,         // bool   If display the complete set of parameters of the widget.
		'debug_query_number' => $debug_query_number,   // bool   If display the number of queries.
		'params'             => $params,               // array  The parameters for the query.
		'args'               => $args,                 // array  The complete set of parameters of the widget.
		'cached'             => $cached,               // bool   If the cache is active.
	) );

	/* Prints the version of Posts in Sidebar and if the cache is active. */
	$pis_output .= pis_generated( $cached );

	/* Reset the custom query */
	wp_reset_postdata();

	return $pis_output;
}


/**
 * The main function to echo the output.
 *
 * @uses get_pis_posts_in_sidebar()
 * @since 3.0
 */
function pis_posts_in_sidebar( $args ) {
	echo pis_get_posts_in_sidebar( $args );
}


/***********************************************************************
 *                            CODE IS POETRY
 **********************************************************************/
