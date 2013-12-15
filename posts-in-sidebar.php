<?php
/**
 * Plugin Name: Posts in Sidebar
 * Description:  Publish a list of posts in your sidebar
 * Plugin URI: http://dev.aldolat.it/projects/posts-in-sidebar/
 * Author: Aldo Latino
 * Author URI: http://www.aldolat.it/
 * Version: 1.15
 * License: GPLv3 or later
 * Text Domain: pis
 * Domain Path: /languages/
 */

/*
 * Copyright (C) 2009, 2013  Aldo Latino  (email : aldolat@gmail.com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

define( 'PIS_VERSION', '1.15' );

/**
 * The core function
 *
 * @since 1.0
 */
function pis_posts_in_sidebar( $args ) {
	$defaults = array(
		'intro'             => '',
		'post_type'         => 'post', // post, page, media, or any custom post type
		'post_ids'          => '',   // Post/Pages IDs.
		'author'            => NULL,   // Author nicename, NOT name
		'cat'               => NULL,   // Category slugs, comma separated
		'tag'               => NULL,   // Tag slugs, comma separated
		'post_format'       => '',
		'number'            => get_option( 'posts_per_page' ),
		'orderby'           => 'date',
		'order'             => 'DESC',
		'cat_not_in'        => '',
		'tag_not_in'        => '',
		'offset_number'     => '',
		'post_status'       => 'publish',
		'post_meta_key'     => '',
		'post_meta_val'     => '',
		'ignore_sticky'     => false,
		'display_title'     => true,
		'link_on_title'     => true,
		'arrow'             => false,
		'display_image'     => false,
		'image_size'        => 'thumbnail',
		'image_align'       => 'no_change',
		'excerpt'           => 'excerpt', // can be "full_content", "rich_content", "content", "excerpt", "none"
		'exc_length'        => 20,        // In words
		'the_more'          => __( 'Read more&hellip;', 'pis' ),
		'exc_arrow'         => false,
		'display_author'    => false,
		'author_text'       => __( 'By', 'pis' ),
		'linkify_author'    => false,
		'display_date'      => false,
		'date_text'         => __( 'Published on', 'pis' ),
		'linkify_date'      => false,
		'comments'          => false,
		'comments_text'     => __( 'Comments:', 'pis' ),
		'utility_sep'       => '|',
		'categories'        => false,
		'categ_text'        => __( 'Category:', 'pis' ),
		'categ_sep'         => ',',
		'tags'              => false,
		'tags_text'         => __( 'Tags:', 'pis' ),
		'hashtag'           => '#',
		'tag_sep'           => '',
		'custom_field'      => false,
		'custom_field_txt'  => '',
		'meta'              => '',
		'custom_field_key'  => false,
		'custom_field_sep'  => ':',
		'archive_link'      => false,
		'link_to'           => 'category',
		'archive_text'      => __( 'Display all posts', 'pis' ),
		'nopost_text'       => __( 'No posts yet.', 'pis' ),
		'list_element'      => 'ul',
		'remove_bullets'    => false,
		'margin_unit'       => 'px',
		'intro_margin'      => NULL,
		'title_margin'      => NULL,
		'side_image_margin' => NULL,
		'bottom_image_margin' => NULL,
		'excerpt_margin'    => NULL,
		'utility_margin'    => NULL,
		'categories_margin' => NULL,
		'tags_margin'       => NULL,
		'archive_margin'    => NULL,
		'noposts_margin'    => NULL,
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );

	$author   == 'NULL' ? $author   = '' : $author   = $author;
	$cat      == 'NULL' ? $cat      = '' : $cat      = $cat;
	$tag      == 'NULL' ? $tag      = '' : $tag      = $tag;

	// $post__in accepts only an array
	if ( $posts_id ) $posts_id = explode( ',', $posts_id ); else $posts_id = NULL;

	// Build the array to get posts
	$params = array(
		'post_type'           => $post_type,
		'post__in'            => $posts_id,
		'author_name'         => $author, // Use nicenames.
		'category_name'       => $cat,
		'tag'                 => $tag,
		'post_format'         => $post_format,
		'posts_per_page'      => $number,
		'orderby'             => $orderby,
		'order'               => $order,
		'category__not_in'    => $cat_not_in,
		'tag__not_in'         => $tag_not_in,
		'offset'              => $offset_number,
		'post_status'         => $post_status,
		'meta_key'            => $post_meta_key,
		'meta_value'          => $post_meta_val,
		'ignore_sticky_posts' => $ignore_sticky
	);
	$linked_posts = new WP_Query( $params ); ?>

		<?php // If in a single post, get the ID of the post of the main loop ?>
		<?php if ( is_single() ) {
			global $post;
			$single_post_id = $post->ID;
		} ?>

		<?php /* The Loop */ ?>
		<?php if ( $linked_posts->have_posts() ) : ?>

			<?php if ( $intro ) { ?>
				<p <?php echo pis_paragraph( $intro_margin, $margin_unit, 'pis-intro', 'pis_intro_class' ); ?>>
					<?php echo pis_break_text( $intro ); ?>
				</p>
			<?php } ?>

			<?php if ( $remove_bullets && $list_element == 'ul' ) $bullets_style = ' style="list-style-type:none; margin-left:0; padding-left:0;"'; else $bullets_style = ''; ?>
			<<?php echo $list_element; ?> <?php pis_class( 'pis-ul', apply_filters( 'pis_ul_class', '' ) ); echo $bullets_style; ?>>

				<?php while( $linked_posts->have_posts() ) : $linked_posts->the_post(); ?>

					<?php // Assign the class 'current-post' if this is the post of the main loop ?>
					<?php if ( is_single() && $single_post_id == $linked_posts->post->ID ) {
						$postclass = 'current-post pis-li';
					} else {
						$postclass = 'pis-li';
					} ?>

					<li <?php pis_class( $postclass, apply_filters( 'pis_li_class', '' ) ); ?>>

						<?php /* The title */ ?>
						<?php if ( $display_title ) { ?>
							<p <?php echo pis_paragraph( $title_margin, $margin_unit, 'pis-title', 'pis_title_class' ); ?>>
								<?php if ( $link_on_title ) { ?>
									<?php $title_link = sprintf( __( 'Permalink to %s', 'pis' ), the_title_attribute( 'echo=0' ) ); ?>
									<a <?php pis_class( 'pis-title-link', apply_filters( 'pis_title_link_class', '' ) ); ?> href="<?php the_permalink(); ?>" title="<?php echo esc_attr( $title_link ); ?>" rel="bookmark">
								<?php } ?>
										<?php the_title(); ?>
										<?php if ( $arrow ) { ?>
											<?php echo pis_arrow(); ?>
										<?php } ?>
								<?php if ( $link_on_title ) { ?>
									</a>
								<?php } ?>
							</p>
						<?php } // Close Display title ?>

						<?php /* The post content */ ?>
						<?php if ( ! post_password_required() ) : ?>
							<?php if ( ( $display_image && has_post_thumbnail() ) || 'none' != $excerpt ) { ?>

								<p <?php echo pis_paragraph( $excerpt_margin, $margin_unit, 'pis-excerpt', 'pis_excerpt_class' ); ?>>

									<?php /* The thumbnail */ ?>
									<?php if ( $display_image ) {
										if ( has_post_thumbnail() ) { ?>
											<?php
											switch ( $image_align ) {
												case 'left' :
													$image_class = ' alignleft';
													if ( ! is_null( $side_image_margin ) || ! is_null( $bottom_image_margin ) ) {
														$image_style = ' style="display: inline; float: left; margin-right: ' . $side_image_margin . $margin_unit . '; margin-bottom: ' . $bottom_image_margin . $margin_unit . ';"';
														$image_style = str_replace( ' margin-right: px;', '', $image_style);
														$image_style = str_replace( ' margin-bottom: px;', '', $image_style);
													}
													break;
												case 'right':
													$image_class = ' alignright';
													if ( ! is_null( $side_image_margin ) || ! is_null( $bottom_image_margin ) ) {
														$image_style = ' style="display: inline; float: right; margin-left: ' . $side_image_margin . $margin_unit . '; margin-bottom: ' . $bottom_image_margin . $margin_unit . ';"';
														$image_style = str_replace( ' margin-left: px;', '', $image_style);
														$image_style = str_replace( ' margin-bottom: px;', '', $image_style);
													}
													break;
												case 'center':
													$image_class = ' aligncenter';
													if ( ! is_null( $bottom_image_margin ) )
														$image_style = ' style="margin-bottom: ' . $bottom_image_margin . $margin_unit . ';"';
													break;
												default:
													$image_class = '';
													break;
											} ?>
											<a <?php pis_class( 'pis-thumbnail-link', apply_filters( 'pis_thumbnail_link_class', '' ) ); ?> href="<?php the_permalink(); ?>" title="<?php echo esc_attr( $title_link ); ?>" rel="bookmark">
												<?php $image_html = get_the_post_thumbnail(
													$linked_posts->post->ID,
													$image_size,
													array(
														'class' => 'pis-thumbnail-img' . ' ' . apply_filters( 'pis_thumbnail_class', '' ) . $image_class,
													)
												);
												$image_html = str_replace( '<img', '<img' . $image_style, $image_html );
												echo $image_html;
												?></a>
										<?php } // Close if ( has_post_thumbnail )  */
									} // Close if ( $display_image ) ?>

									<?php /* The text */ ?>
									<?php /*
										"Full content" = the content of the post as displayed in the page.
										"Rich content" = the content with inline images, titles and more (shortcodes will be executed).
										"Content"      = the full text of the content, whitout any ornament (shortcodes will be stripped).
										"More excerpt" = the excerpt up to the point of the "more" tag (inserted by the user).
										"Excerpt"      = the excerpt as defined by the user or generated by WordPress.
									*/ ?>
									<?php switch ( $excerpt ) {

										case 'full_content':
											the_content();
											break;

										case 'rich_content':
											$content = $linked_posts->post->post_content;
											// Honor any paragraph break
											$content = pis_break_text( $content );
											echo apply_filters( 'pis_rich_content', $content );
											break;

										case 'content':
											// Remove shortcodes
											$content = strip_shortcodes( $linked_posts->post->post_content );
											// remove any HTML tag
											$content = wp_kses( $content, array() );
											// Honor any paragraph break
											$content = pis_break_text( $content );
											echo apply_filters( 'pis_content', $content );
											break;

										case 'more_excerpt':
											$excerpt_text = strip_shortcodes( $linked_posts->post->post_content );
											$testformore = strpos( $excerpt_text, '<!--more-->' );
											if ( $testformore ) {
												$excerpt_text = substr( $excerpt_text, 0, $testformore );
											} else {
												$excerpt_text = wp_trim_words( $excerpt_text, $exc_length, '&hellip;' );
											}
											echo apply_filters( 'pis_more_excerpt_text', $excerpt_text );

											/* The 'Read more' and the Arrow */
											if ( $the_more || $exc_arrow ) {
												if ( $exc_arrow ) {
													$the_arrow = pis_arrow();
												} else {
													$the_arrow = '';
												} ?>
												<span <?php pis_class( 'pis-more', apply_filters( 'pis_more_class', '' ) ); ?>>
													<a href="<?php echo the_permalink(); ?>" title="<?php esc_attr_e( 'Read the full post', 'pis' ); ?>" rel="bookmark">
														<?php echo $the_more . '&nbsp;' . $the_arrow; ?>
													</a>
												</span>
											<?php }

											break;

										case 'excerpt':
											// If we have a user-defined excerpt...
											if ( $linked_posts->post->post_excerpt ) {
												// Honor any paragraph break
												$user_excerpt = pis_break_text( $linked_posts->post->post_excerpt );
												echo apply_filters( 'pis_user_excerpt', $user_excerpt );
											} else {
											// ... else generate an excerpt
												$excerpt_text = strip_shortcodes( $linked_posts->post->post_content );
												$excerpt_text = wp_trim_words( $excerpt_text, $exc_length, '&hellip;' );
												echo apply_filters( 'pis_excerpt_text', $excerpt_text );
											}

											/* The 'Read more' and the Arrow */
											if ( $the_more || $exc_arrow ) {
												if ( $exc_arrow ) {
													$the_arrow = pis_arrow();
												} else {
													$the_arrow = '';
												} ?>
												<span <?php pis_class( 'pis-more', apply_filters( 'pis_more_class', '' ) ); ?>>
													<a href="<?php echo the_permalink(); ?>" title="<?php esc_attr_e( 'Read the full post', 'pis' ); ?>" rel="bookmark">
														<?php echo $the_more . '&nbsp;' . $the_arrow; ?>
													</a>
												</span>
											<?php }
									}
									// Close The text ?>

								</p>

							<?php }	// Close The content ?>
						<?php endif; // Close if post password required ?>

						<?php /* The author, the date and the comments */ ?>
						<?php if ( $display_author || $display_date || $comments ) { ?>
							<p <?php echo pis_paragraph( $utility_margin, $margin_unit, 'pis-utility', 'pis_utility_class' ); ?>>
						<?php } ?>

							<?php /* The author */ ?>
							<?php if ( $display_author ) { ?>
								<span <?php pis_class( 'pis-author', apply_filters( 'pis_author_class', '' ) ); ?>>
									<?php if ( $author_text ) echo $author_text . '&nbsp;'; ?><?php
									if ( $linkify_author ) { ?>
										<?php
										$author_title = sprintf( __( 'View all posts by %s', 'pis' ), get_the_author() );
										$author_link  = get_author_posts_url( get_the_author_meta( 'ID' ) );
										?>
										<a <?php pis_class( 'pis-author-link', apply_filters( 'pis_author_link_class', '' ) ); ?> href="<?php echo $author_link; ?>" title="<?php echo esc_attr( $author_title ); ?>" rel="author">
											<?php echo get_the_author(); ?></a>
									<?php } else {
										echo get_the_author();
									} ?>
								</span>
							<?php } ?>

							<?php /* The date */ ?>
							<?php if ( $display_date ) { ?>
								<?php if ( $display_author ) { ?>
									<span <?php pis_class( 'pis-separator', apply_filters( 'pis_separator_class', '' ) ); ?>>&nbsp;<?php echo $utility_sep; ?>&nbsp;</span>
								<?php } ?>
								<span <?php pis_class( 'pis-date', apply_filters( 'pis_date_class', '' ) ); ?>>
									<?php if ( $date_text ) echo $date_text . '&nbsp;'; ?><?php
									if ( $linkify_date ) { ?>
										<?php $date_title = sprintf( __( 'Permalink to %s', 'pis' ), the_title_attribute( 'echo=0' ) ); ?>
										<a <?php pis_class( 'pis-date-link', apply_filters( 'pis_date_link_class', '' ) ); ?> href="<?php the_permalink(); ?>" title="<?php echo esc_attr( $date_title ); ?>" rel="bookmark">
											<?php echo get_the_date(); ?></a>
									<?php } else {
										echo get_the_date();
									} ?>
								</span>

							<?php } ?>

							<?php /* The comments */ ?>
							<?php if ( ! post_password_required() ) : ?>
								<?php if ( $comments ) { ?>
									<?php if ( $display_author || $display_date ) { ?>
										<span <?php pis_class( 'pis-separator', apply_filters( 'pis_separator_class', '' ) ); ?>>&nbsp;<?php echo $utility_sep; ?>&nbsp;</span>
									<?php } ?>
									<span <?php pis_class( 'pis-comments', apply_filters( 'pis_comments_class', '' ) ); ?>>
										<?php if ( $comments_text ) echo $comments_text . '&nbsp;'; ?><?php
										comments_popup_link( '<span class="pis-reply">' . __( 'Leave a comment', 'pis' ) . '</span>', __( '1 Comment', 'pis' ), __( '% Comments', 'pis' ) ); ?>
									</span>
								<?php } ?>
							<?php endif; ?>

						<?php if ( $display_author || $display_date || $comments ) { ?>
							</p>
						<?php } ?>

						<?php /* The categories */ ?>
						<?php if ( $categories ) {
							$list_of_categories = get_the_category_list( $categ_sep . ' ', '', $linked_posts->post->ID );
							if ( $list_of_categories ) { ?>
								<p <?php echo pis_paragraph( $categories_margin, $margin_unit, 'pis-categories-links', 'pis_categories_class' ); ?>>
									<?php if ( $categ_text ) echo $categ_text . '&nbsp';
									echo apply_filters(  'pis_categories_list', $list_of_categories ); ?>
								</p>
							<?php }
						} ?>

						<?php /* The tags */ ?>
						<?php if ( $tags ) {
							$list_of_tags = get_the_term_list( $linked_posts->post->ID, 'post_tag', $hashtag, $tag_sep . ' ' . $hashtag, '' );
							if ( $list_of_tags ) { ?>
								<p <?php echo pis_paragraph( $tags_margin, $margin_unit, 'pis-tags-links', 'pis_tags_class' ); ?>>
									<?php if ( $tags_text ) echo $tags_text . '&nbsp;';
									echo apply_filters( 'pis_tags_list', $list_of_tags );
									?>
								</p>
							<?php }
						} ?>

						<?php /* The post meta */ ?>
						<?php if ( $custom_field ) {
							$the_custom_field = get_post_meta( $linked_posts->post->ID, $meta, false );
							if ( $the_custom_field ) {
								if ( $custom_field_txt )
									$cf_text = '<span class="pis-custom-field-text-before">' . $custom_field_txt . '</span>';
								else
									$cf_text = '';
								if ( $custom_field_key )
									$key = '<span class="pis-custom-field-key">' . $meta . '</span>' . '<span class="pis-custom-field-divider">' . $custom_field_sep . '</span> ';
								else
									$key = '';
								$cf_value = '<span class="pis-custom-field-value">' . $the_custom_field[0] . '</span>'; ?>
								<p <?php echo pis_paragraph( $custom_field_margin, $margin_unit, 'pis-custom-field', 'pis_custom_fields_class' ); ?>>
									<?php echo $cf_text . $key . $cf_value; ?>
								</p>
							<?php }
						} ?>

					</li>

				<?php endwhile; ?>

			</<?php echo $list_element; ?>>
			<!-- / ul#pis-ul -->

			<?php /* The link to the entire archive */ ?>
			<?php if ( $archive_link ) {

				$wp_post_type = array( 'post', 'page', 'media', 'any' );

				if ( $link_to == 'author' && isset( $author ) ) {
					$author_infos = get_user_by( 'slug', $author );
					if ( $author_infos ) {
						$term_link = get_author_posts_url( $author_infos->ID, $author );
						$title_text = sprintf( __( 'Display all posts by %s', 'pis' ), $author_infos->display_name );
					}
				} elseif ( $link_to == 'category' && isset( $cat ) ) {
					$term_identity = get_term_by( 'slug', $cat, 'category' );
					if ( $term_identity ) {
						$term_link = get_category_link( $term_identity->term_id );
						$title_text = sprintf( __( 'Display all posts archived as %s', 'pis' ), $term_identity->name );
					}
				} elseif ( $link_to == 'tag' && isset( $tag ) ) {
					$term_identity = get_term_by( 'slug', $tag, 'post_tag' );
					if ( $term_identity ) {
						$term_link = get_tag_link( $term_identity->term_id );
						$title_text = sprintf( __( 'Display all posts archived as %s', 'pis' ), $term_identity->name );
					}
				} elseif ( ! in_array( $post_type, $wp_post_type ) ) {
					$term_link = get_post_type_archive_link( $link_to );
					$post_type_object = get_post_type_object( $link_to );
					$title_text = sprintf( __( 'Display all posts archived as %s', 'pis' ), $post_type_object->labels->name );
				} elseif ( term_exists( $link_to, 'post_format' ) && $link_to == $post_format ) {
					$term_link = get_post_format_link( substr( $link_to, 12 ) );
					$term_object = get_term_by( 'slug', $link_to, 'post_format' );
					$title_text = sprintf( __( 'Display all posts with post format %s', 'pis' ), $term_object->name );
				}

				if ( isset( $term_link ) ) { ?>
					<p <?php echo pis_paragraph( $archive_margin, $margin_unit, 'pis-archive-link', 'pis_archive_class' ); ?>>
						<a <?php pis_class( 'pis-archive-link-class', apply_filters( 'pis_archive_link_class', '' ) ); ?> href="<?php echo $term_link; ?>" title="<?php echo esc_attr( $title_text ); ?>" rel="bookmark">
							<?php echo $archive_text; ?>
						</a>
					</p>
				<?php }
			} ?>

		<?php /* If we have no posts yet */ ?>
		<?php else : ?>

			<?php if ( $nopost_text ) { ?>
				<ul <?php pis_class( 'pis-ul', apply_filters( 'pis_ul_class', '' ) ); ?>>
					<li <?php pis_class( 'pis-li pis-noposts', apply_filters( 'pis_nopost_class', '' ) ); ?>>
						<p <?php echo pis_paragraph( $noposts_margin, $margin_unit, 'noposts', 'pis_noposts_class' ); ?>>
							<?php echo $nopost_text; ?>
						</p>
					</li>
				</ul>
			<?php } ?>

		<?php endif; ?>

		<?php /* Reset this custom query */ ?>
		<?php wp_reset_postdata(); ?>

		<?php echo '<!-- Generated by Posts in Sidebar v' . PIS_VERSION . ' -->'; ?>

<?php }


/**
 * Return the class for the HTML element
 *
 * @since 1.9
 *
 * @param string $default One or more classes, defined by plugin's developer, to add to the class list.
 * @param string|array $class One or more classes, defined by the user, to add to the class list.
 * @param boolean $echo If the function should echo or not the output.
 * @return string $output List of classes.
 */
function pis_class( $default = '', $class = '', $echo = true ) {

	// Define $classes as array
	$classes = array();

	// If $default is not empy, add the value ad an element of the array
	if( ! empty( $default ) )
		$classes[] = $default;

	// If $class is not empty, transform it into an array and add the elements to the array
	if ( ! empty( $class ) ) {
		if ( ! is_array( $class ) ) $class = preg_split( '#\s+#', $class );
		$classes = array_merge( $classes, $class );
	}

	// Escape evil chars in $classes
	$classes = array_map( 'esc_attr', $classes );

	// Remove null or empty or space-only-filled elements from the array
	foreach ( $classes as $key => $value ) {
		if ( is_null( $value ) || $value == '' || $value == ' ' ) {
			unset( $classes[ $key ] );
		}
	}

	// Convert the array into string
	$classes = implode( ' ', $classes );

	// Complete the final output
	$classes = 'class="' . $classes . '"';

	if ( true === $echo )
		echo apply_filters( 'pis_classes', $classes );
	else
		return apply_filters( 'pis_classes', $classes );
}


/**
 * Return the paragraph class with inline style
 *
 * @since 1.12
 *
 * @param string $margin The margin of the paragraph.
 * @param string $unit The unit measure to be used.
 * @param string $class The default class defined by the plugin's developer.
 * @param string $class_filter The name of the class filter.
 * @param boolean $class_echo If the pis_class() function should echo or not the output.
 * @return string $output The class and the inline style.
 * @uses pis_class()
 */
function pis_paragraph( $margin, $unit, $class, $class_filter ) {
	( ! is_null( $margin ) ) ? $style = ' style="margin-bottom: ' . $margin . $unit . ';"' : $style = '';
	$output = pis_class( $class, apply_filters( $class_filter, '' ) ) . $style;
	return $output;
}


/**
* Return the given text with paragraph breaks (HTML <br />).
*
* @since 1.12
* @param string $text The text to be checked.
* @return string $text The checked text with paragraph breaks.
*/
function pis_break_text( $text ) {
	// Convert cross-platform newlines into HTML '<br />'
	$text = str_replace( array( "\r\n", "\n", "\r" ), "<br />", $text );
	return $text;
}


function pis_meta() {
	global $wpdb;
	$limit = (int) apply_filters( 'pis_postmeta_limit', 30 );
	$keys = $wpdb->get_col( "
		SELECT meta_key
		FROM $wpdb->postmeta
		GROUP BY meta_key
		HAVING meta_key NOT LIKE '\_%'
		ORDER BY meta_key
		LIMIT $limit" );
	if ( $keys )
		natcasesort($keys);
	return $keys;
}


/**
 * Generate an HTML arrow.
 *
 * @since 1.15
 * @return string $output The HTML arrow.
 */
function pis_arrow() {
	$the_arrow = '&rarr;';
	if ( is_rtl() ) $the_arrow = '&larr;';

	$output = '&nbsp;<span ' . pis_class( 'pis-arrow', apply_filters( 'pis_arrow_class', '' ), false ) . '>' . $the_arrow . '</span>';

	return $output;
}


/**
 * Generate the output for the more and/or the HTML arrow.
 *
 * @since 1.15
 * @uses pis_arrow()
 */
function pis_more_arrow( $the_more, $exc_arrow ) {
	if ( $the_more || $exc_arrow ) {
		if ( $exc_arrow ) {
			$the_arrow = pis_arrow();
		} else {
			$the_arrow = '';
		} ?>
		<span <?php pis_class( 'pis-more', apply_filters( 'pis_more_class', '' ) ); ?>>
			<a href="<?php echo the_permalink(); ?>" title="<?php esc_attr_e( 'Read the full post', 'pis' ); ?>" rel="bookmark">
				<?php echo $the_more . '&nbsp;' . $the_arrow; ?>
			</a>
		</span>
	<?php }
}


/**
 * Include the widget
 *
 * @since 1.1
 */
include_once( plugin_dir_path( __FILE__ ) . 'posts-in-sidebar-widget.php' );


/**
 * Include the widget form functions
 *
 * @since 1.12
 */
include_once( plugin_dir_path( __FILE__ ) . 'widget-form-functions.php' );


/**
 * Add the custom styles to wp_head hook.
 *
 * @since 1.13
 */
function pis_add_styles_to_head() {
	// Get the options from the database.
	$custom_styles = (array) get_option( 'widget_pis_posts_in_sidebar' );

	// Define $styles as an array.
	$styles = array();

	// Get all the values of "custom_styles" key into $styles.
	foreach ( $custom_styles as $key => $value ) {
		$styles[] = $value['custom_styles'];
	}

	// Remove any empty elements from the array
	$styles = array_filter( $styles );

	// Make the array as string.
	$styles = implode( "\n", $styles );

	// Print the output if it's not empty.
	if ( $styles ) echo '<style type="text/css">' . $styles . '</style>';
}
add_action( 'wp_head', 'pis_add_styles_to_head' );


/**
 * Make plugin available for i18n
 *
 * Translations must be archived in the /languages/ directory
 * The name of each translation file must be:
 *
 * ITALIAN:
 * pis-it_IT.po
 * pis-it_IT.mo
 *
 * GERMAN:
 * pis-de_DE.po
 * pis-de_DE.po
 *
 * and so on.
 *
 * @since 0.1
 */
function pis_load_languages() {
	load_plugin_textdomain( 'pis', false, dirname( plugin_basename( __FILE__ ) ) . '/languages');
}
add_action( 'plugins_loaded', 'pis_load_languages' );

/***********************************************************************
 *                            CODE IS POETRY
 **********************************************************************/
