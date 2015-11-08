<?php
/**
 * Create the shortcode.
 *
 * @example [pissc post_type="page" post_parent_in=4 exclude_current_post=1]
 * @since 3.0
 */
function pis_shortcode( $atts ) {
	extract( shortcode_atts( array(
		// Posts retrieving
		'post_type'           => 'post',    // post, page, attachment, or any custom post type
		'posts_id'            => '',        // Post/Pages IDs, comma separated
		'author'              => '',        // Author nicename
		'author_in'           => '',        // Aurthor IDs
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
		'display_date'        => false,
		'date_text'           => __( 'Published on', 'posts-in-sidebar' ),
		'linkify_date'        => false,
		'comments'            => false,
		'comments_text'       => __( 'Comments:', 'posts-in-sidebar' ),
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
		'archive_text'        => __( 'Display all posts', 'posts-in-sidebar' ),

		// When no posts found
		'nopost_text'         => __( 'No posts yet.', 'posts-in-sidebar' ),

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

		// Debug
		'debug_query'         => false,
		'debug_params'        => false,
		'debug_query_number'  => false,
	), $atts ) );

	return do_shortcode( pis_get_posts_in_sidebar( $atts ) );
}
add_shortcode('pissc', 'pis_shortcode');