<?php
/**
 * Plugin Name: Posts in Sidebar
 * Description:  Publish a list of posts in your sidebar
 * Plugin URI: http://dev.aldolat.it/projects/posts-in-sidebar/
 * Author: Aldo Latino
 * Author URI: http://www.aldolat.it/
 * Version: 1.3
 * License: GPLv3 or later
 * Text Domain: pis
 * Domain Path: /languages/
 */

/*
 * Copyright (C) 2009, 2012  Aldo Latino  (email : aldolat@gmail.com)
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
 *
 * @package PostsInSidebar
 * @version 1.3
 * @author Aldo Latino <aldolat@gmail.com>
 * @copyright Copyright (c) 2009-2012, Aldo Latino
 * @link http://www.aldolat.it/wordpress/wordpress-plugins/posts-in-sidebar/
 * @license http://www.gnu.org/licenses/gpl.html
 */

/**
 * The core function
 *
 * @since 1.0
 */
function pis_posts_in_sidebar( $args ) {
	$defaults = array(
		'author'        => NULL,   // Author nicename, NOT name
		'cat'           => NULL,   // Category slugs, comma separated
		'tag'           => NULL,   // Tag slugs, comma separated
		'number'        => get_option( 'posts_per_page' ),
		'orderby'       => 'date',
		'order'         => 'DESC',
		'cat_not_in'    => '',
		'tag_not_in'    => '',
		'offset_number' => '',
		'post_status'   => 'publish',
		'post_meta_key' => '',
		'post_meta_val' => '',
		'ignore_sticky' => false,
		'display_title' => true,
		'link_on_title' => true,
		'display_date'  => false,
		'linkify_date'  => true,
		'display_image' => false,
		'image_size'    => 'thumbnail',
		'excerpt'       => 'excerpt', // can be "excerpt" or "content"
		'arrow'         => false,
		'exc_length'    => 20,      // In words
		'exc_arrow'     => false,
		'comments'      => false,
		'categories'    => false,
		'categ_text'    => __( 'Category:', 'pis' ),
		'categ_sep'     => ',',
		'tags'          => false,
		'tags_text'     => __( 'Tags:', 'pis' ),
		'hashtag'       => '#',
		'tag_sep'       => '',
		'archive_link'  => false,
		'link_to'       => 'category',
		'archive_text'  => __( 'More posts &rarr;', 'pis' ),
		'nopost_text'   => __( 'No posts yet.', 'pis' ),
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );

	$author == 'NULL' ? $author = '' : $author = $author;
	$cat    == 'NULL' ? $cat = ''    : $cat = $cat;
	$tag    == 'NULL' ? $tag = ''    : $tag = $tag;

	// Build the array to get posts
	$params = array(
		'author_name'        => $author, // Use nicenames.
		'category_name'      => $cat,
		'tag'                => $tag,
		'posts_per_page'     => $number,
		'orderby'            => $orderby,
		'order'              => $order,
		'category__not_in'   => $cat_not_in,
		'tag__not_in'        => $tag_not_in,
		'offset'             => $offset_number,
		'post_status'        => $post_status,
		'meta_key'           => $post_meta_key,
		'meta_value'         => $post_meta_val,
		'ignore_sticky_posts'=> $ignore_sticky
	);
	$linked_posts = new WP_Query( $params ); ?>

	<ul class="pis-ul">
		<?php /* The Loop */ ?>
		<?php if ( $linked_posts->have_posts() ) : ?>

			<?php while( $linked_posts->have_posts() ) : $linked_posts->the_post(); ?>

				<li class="pis-li">

					<?php /* The title */ ?>
					<?php if ( $display_title ) { ?>
						<p class="pis-title">
							<?php if ( $link_on_title ) { ?>
								<a class="pis-title-link" href="<?php the_permalink(); ?>" title="<?php esc_attr_e( sprintf( __( 'Permalink to %s', 'pis' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">
							<?php } ?>
									<?php the_title(); ?>
									<?php if ( $arrow ) { ?>
										&nbsp;<span class="pis-arrow">&rarr;</span>
									<?php } ?>
							<?php if ( $link_on_title ) { ?>
								</a>
							<?php } ?>
						</p>
					<?php } // Close Display title ?>

					<?php /* The post content */ ?>
					<?php if ( ( $display_image && has_post_thumbnail() ) || 'none' != $excerpt ) { ?>

						<p class="pis-excerpt">

							<?php /* The thumbnail */ ?>
							<?php if ( $display_image ) {
								if ( has_post_thumbnail() ) { ?>
									<a class="pis-thumbnail-link" href="<?php the_permalink(); ?>" title="<?php esc_attr_e( sprintf( __( 'Permalink to %s', 'pis' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">
										<?php the_post_thumbnail(
											$image_size,
											array( 'class' => 'pis-thumbnail-img' )
										); ?>
									</a>
								<?php } // Close The thumbnail
							} ?>

							<?php /* The text */ ?>
							<?php if ( $excerpt == 'content' ) {
								echo strip_shortcodes( $linked_posts->post->post_content );
							} elseif ( $excerpt == 'excerpt' || $excerpt == '1' /* This condition takes care of the boolean value coming from version 1.1 */ ) {
								// If we have a user-defined excerpt...
								if ( $linked_posts->post->post_excerpt ) {
									$excerpt_text = strip_tags( $linked_posts->post->post_excerpt );
								} else {
								// ... else generate an excerpt

									/* Excerpt in words */
									$excerpt_text = wp_trim_words( strip_shortcodes( $linked_posts->post->post_content ), $exc_length, '&hellip;' );

									/* BONUS: Excerpt in characters */
									// $excerpt_text = substr( strip_tags( $linked_posts->post->post_content ), 0, $exc_length ) . '&hellip;';

								}
								echo $excerpt_text; ?>

								<?php /* The arrow */ ?>
								<?php if ( $exc_arrow ) { ?>
									&nbsp;<span class="pis-arrow">
										<a href="<?php echo the_permalink(); ?>" title="<?php esc_attr_e( 'Read the full post', 'pis' ); ?>" rel="bookmark">
											&rarr;
										</a>
									</span>
								<?php } ?>
							<?php } // Close The post content ?>

						</p>

					<?php }	// Close The content ?>

					<?php /* The date and the comments */ ?>
					<?php if ( $display_date || $comments ) { ?>
						<p class="pis-utility">
					<?php } ?>

						<?php /* The date */ ?>
						<?php if ( $display_date ) { ?>

							<span class="pis-date">
								<?php if ( $linkify_date ) { ?>
									<a class="pis-date-link" href="<?php the_permalink(); ?>" title="<?php esc_attr_e( sprintf( __( 'Permalink to %s', 'pis' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">
										<?php echo get_the_date(); ?>
									</a>
								<?php } else { 
									echo get_the_date();
								} ?>
							</span>

							<?php
							/*
							BONUS 1: The date as archived into the database
							<p class="pis-date">
								<a href="<?php the_permalink(); ?>">
									<?php echo $linked_posts->post->post_date; ?>
								</a>
							</p>

							BONUS 2: The date as archived into the database, and displayed into a localized form
							<p class="pis-date">
								<a href="<?php the_permalink(); ?>">
									<?php echo date_i18n( get_option( 'date_format' ), strtotime( $linked_posts->post->post_date ), false ); ?>
								</a>
							</p>
							*/ ?>

						<?php } ?>

						<?php /* The comments */ ?>
						<?php if ( $comments ) { ?>
							<?php if ( $display_date ) { ?>
								<span class="pis-separator"> - </span>
							<?php } ?>
							<span class="pis-comments">
								<?php comments_popup_link( '<span class="pis-reply">' . __( 'Leave a comment', 'pis' ) . '</span>', __( '1 Comment', 'pis' ), __( '% Comments', 'pis' ) ); ?>
							</span>
						<?php } ?>

					<?php if ( $display_date || $comments ) { ?>
						</p>
					<?php } ?>

					<?php /* The categories */ ?>
					<?php if ( $categories ) {
						$list_of_categories = get_the_category_list( $categ_sep . ' ', '', $linked_posts->post->ID );
						if ( $list_of_categories ) { ?>
							<p class="pis-categories-links">
								<?php if ( $categ_text ) $categ_text_out = $categ_text . '&nbsp;'; ?>
								<?php echo $categ_text_out; ?><?php echo $list_of_categories; ?>
							</p>
						<?php }
					} ?>

					<?php /* The tags */ ?>
					<?php if ( $tags ) {
						$list_of_tags = get_the_term_list( $linked_posts->post->ID, 'post_tag', $hashtag, $tag_sep . ' ' . $hashtag, '' );
						if ( $list_of_tags ) { ?>
							<p class="pis-tags-links">
								<?php if ( $tags_text ) $tags_text_out = $tags_text . '&nbsp;'; ?>
								<?php echo $tags_text_out; ?><?php echo $list_of_tags; ?>
							</p>
						<?php }
					} ?>

				</li>

			<?php endwhile; ?>

			<?php /* The link to the entire archive */ ?>
			<?php if ( $archive_link ) {

				if ( $link_to == 'category' && isset( $cat ) ) {
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
				} elseif ( $link_to == 'author' && isset( $author ) ) {
					$author_infos = get_user_by( 'slug', $author );
					if ( $author_infos ) {
						$term_link = get_author_posts_url( $author_infos->ID, $author );
						$title_text = sprintf( __( 'Display all posts by %s', 'pis' ), $author_infos->display_name );
					}
				} ?>

				<?php if ( $archive_text == '' )
					$archive_text = __( 'More posts &rarr;', 'pis' ); ?>

				<?php if ( isset( $term_link ) ) { ?>
					<p class="archive-link">
						<a href="<?php echo $term_link; ?>" title="<?php esc_attr_e( $title_text ); ?>" rel="bookmark">
							<?php echo $archive_text; ?>
						</a>
					</p>
				<?php }
			} ?>

		<?php /* If we have no posts yet */ ?>
		<?php else : ?>

			<li class="pis-li pis-noposts">
				<p class="noposts">
					<?php echo $nopost_text; ?>
				</p>
			</li>

		<?php endif; ?>

		<?php /* Reset this custom query */ ?>
		<?php wp_reset_postdata(); ?>

	</ul>

<?php }


/**
 * Include the widget
 *
 * @since 1.1
 */
include( 'posts-in-sidebar-widget.php' );


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