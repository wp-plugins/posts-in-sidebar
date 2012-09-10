<?php
/*
	Plugin Name: Posts in Sidebar
	Description:  Publish a list of posts in your sidebar
	Plugin URI: http://www.aldolat.it/wordpress/wordpress-plugins/posts-in-sidebar/
	Author: Aldo Latino
	Author URI: http://www.aldolat.it/
	Version: 1.0.1
	License: GPLv3 or later
*/

/*
	Copyright (C) 2009, 2012  Aldo Latino  (email : aldolat@gmail.com)

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
 * The core function
 * 
 */
 
function pis_posts_in_sidebar( $args ) {
	$defaults = array(
		'author'        => NULL,   // Author nicename, NOT name
		'cat'           => NULL,   // Category slugs, comma separated
		'tag'           => NULL,   // Tag slugs, comma separated
		'number'        => get_option( 'posts_per_page' ),
		'orderby'       => 'date',
		'order'         => 'DESC',
		'offset_number' => '',
		'post_status'   => 'publish',
		'post_meta_key' => '',
		'post_meta_val' => '',
		'ignore_sticky' => false,
		'display_title' => true,
		'link_on_title' => true,
		'display_date'  => false,
		'display_image' => false,
		'image_size'    => 'thumbnail',
		'excerpt'       => true,
		'arrow'         => false,
		'exc_length'    => 20,      // In words
		'exc_arrow'     => false,
		'comments'      => false,
		'tags'          => false,
		'tags_text'     => 'Tags:',
		'hashtag'       => '#',
		'tag_sep'       => '',
		'archive_link'  => false,
		'link_to'       => 'category',
		'archive_text'  => __( 'More posts &rarr;', 'pis' )
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );

	$author == 'NULL' ? $author = '' : $author = $author;
	$cat    == 'NULL' ? $cat = ''    : $cat = $cat;
	$tag    == 'NULL' ? $tag = ''    : $tag = $tag;

	// Build the array to get posts
	$params = array(
		'author_name'        => $author,
		'category_name'      => $cat,
		'tag'                => $tag,
		'posts_per_page'     => $number,
		'orderby'            => $orderby,
		'order'              => $order,
		'offset'             => $offset_number,
		'post_status'        => $post_status,
		'meta_key'           => $post_meta_key,
		'meta_value'         => $post_meta_val,
		'ignore_sticky_posts'=> $ignore_sticky
	);
	$linked_posts = new WP_Query( $params ); ?>

	<ul class="pis-ul">
		<?php /* The Loop */ ?>
		<?php if( $linked_posts->have_posts() ) : ?>

			<?php while( $linked_posts->have_posts() ) : $linked_posts->the_post(); ?>

				<li class="pis-li">

					<?php /* The title */ ?>
					<?php if( $display_title ) { ?>
						<p class="pis-title">
							<?php if( $link_on_title ) { ?>
								<a class="pis-title-link" href="<?php the_permalink(); ?>" title="<?php printf( __( 'Permalink to %s', 'pis' ), the_title_attribute( 'echo=0' ) ); ?>">
							<?php } ?>
									<?php the_title(); ?>
									<?php if( $arrow ) { ?>
										&nbsp;<span class="pis-arrow">&rarr;</span>
									<?php } ?>
							<?php if( $link_on_title ) { ?>
								</a>
							<?php } ?>
						</p>
					<?php } // Close Display title ?>

					<?php /* The content */ ?>
					<?php if( $display_image || $excerpt ) { ?>

						<p class="pis-excerpt">

							<?php /* The thumbnail */ ?>
							<?php if( $display_image ) {
								if( has_post_thumbnail() ) { ?>
									<a class="pis-thumbnail-link" href="<?php the_permalink(); ?>">
										<?php the_post_thumbnail(
											$image_size,
											array( 'class' => 'pis-thumbnail-img' )
										); ?>
									</a>
								<?php } // Close The thumbnail
							} ?>

							<?php /* The text */ ?>
							<?php if( $excerpt ) { ?>
								<?php
								// If we have a user-defined excerpt...
								if( $linked_posts->post->post_excerpt ) {
									$excerpt_text = strip_tags( $linked_posts->post->post_excerpt );
								} else {
								// ... else generate an excerpt

									/* Excerpt in words */
									$excerpt_text = wp_trim_words( strip_shortcodes( $linked_posts->post->post_content ), $exc_length, '&hellip;' );

									/* BONUS: Excerpt in characters */
									// $excerpt_text = substr( strip_tags( $linked_posts->post->post_content ), 0, $exc_length ) . '&hellip;';

								} ?>
								<?php echo $excerpt_text; ?>

								<?php /* The arrow */ ?>
								<?php if( $exc_arrow ) { ?>
									&nbsp;<span class="pis-arrow">
										<a href="<?php echo the_permalink(); ?>" title="<?php _e( 'Read the full post', 'pis' ); ?>">
											&rarr;
										</a>
									</span>
								<?php } ?>
							<?php } // Close The excerpt ?>

						</p>

					<?php } // Close The content ?>

					<?php /* The date and the comments */ ?>
					<?php if( $display_date || $comments ) { ?>
						<p class="pis-utility">
					<?php } ?>

						<?php /* The date */ ?>
						<?php if( $display_date ) { ?>

							<span class="pis-date">
								<a href="<?php the_permalink(); ?>">
									<?php echo get_the_date(); ?>
								</a>
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
						<?php if( $comments ) { ?>
							<?php if( $display_date ) { ?>
								<span class="pis-separator"> - </span>
							<?php } ?>
							<span class="pis-comments">
								<?php comments_popup_link( '<span class="pis-reply">' . __( 'Leave a comment', 'pis' ) . '</span>', __( '1 Comment', 'pis' ), __( '% Comments', 'pis' ) ); ?>
							</span>
						<?php } ?>

					<?php if( $display_date || $comments ) { ?>
						</p>
					<?php } ?>

					<?php /* The tags */ ?>
					<?php if( $tags ) {
						$list_of_tags = get_the_term_list( $linked_posts->post->ID, 'post_tag', $hashtag, $tag_sep . ' ' . $hashtag, '' );
						if( $list_of_tags ) { ?>
							<p class="pis-tags-links">
								<?php echo $tags_text; ?>&nbsp;<?php echo $list_of_tags; ?>
							</p>
						<?php }
					} ?>
					
				</li>

			<?php endwhile; ?>

		<?php /* If we have no posts yet */ ?>
		<?php else : ?>
			<li class="pis-li pis-noposts">
				<?php _e( 'No posts yet.', 'pis' ); ?>
			</li>

		<?php endif; ?>

	</ul>

	<?php /* The link to the entire archive */ ?>
	<?php if( $archive_link ) {

		if( $link_to == 'category' && isset( $cat ) ) {
			$term_identity = get_term_by( 'slug', $cat, 'category' );
			$term_link = get_category_link( $term_identity->term_id );
		} elseif( $link_to == 'tag' && isset( $tag ) ) {
			$term_identity = get_term_by( 'slug', $tag, 'post_tag' );
			$term_link = get_tag_link( $term_identity->term_id );
		} elseif( $link_to == 'author' && isset( $author ) ) {
			$author_infos = get_user_by( 'slug', $author );
			$term_link = get_author_posts_url( $author_infos->ID, $author );
		}

		if( $archive_text == '' ) $archive_text = __( 'More posts &rarr;', 'pis' ); ?>

		<?php if( $term_link ) { ?>
			<p class="archive-link">
				<a href="<?php echo $term_link; ?>">
					<?php echo $archive_text; ?>
				</a>
			</p>
		<?php }
	} ?>

	<?php /* Reset this custom query */ ?>
	<?php wp_reset_postdata();
}

add_action( 'widgets_init', 'pis_load_widgets' );

/**
 * Register the widget
 */

function pis_load_widgets() {
	register_widget( 'PIS_Posts_In_Sidebar' );
}

/**
 * Create the widget
 */
 
class PIS_Posts_In_Sidebar extends WP_Widget {

	function PIS_Posts_In_Sidebar() {
		/* Widget settings. */
		$widget_ops = array(
			'classname' => 'posts-in-sidebar',
			'description' => __( 'Display a list of posts in a widget', 'pis' )
		);

		/* Widget control settings. */
		$control_ops = array(
			'width' => 700,
			'id_base' => 'pis_posts_in_sidebar'
		);

		/* Create the widget. */
		$this->WP_Widget( 'pis_posts_in_sidebar', __( 'Posts in Sidebar', 'pis' ), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title;
		pis_posts_in_sidebar( array(
			'author'        => $instance['author'],
			'cat'           => $instance['cat'],
			'tag'           => $instance['tag'],
			'number'        => $instance['number'],
			'orderby'       => $instance['orderby'],
			'order'         => $instance['order'],
			'offset_number' => $instance['offset_number'],
			'post_status'   => $instance['post_status'],
			'post_meta_key' => $instance['post_meta_key'],
			'post_meta_val' => $instance['post_meta_val'],
			'ignore_sticky' => $instance['ignore_sticky'],
			'display_title' => $instance['display_title'],
			'link_on_title' => $instance['link_on_title'],
			'display_date'  => $instance['display_date'],
			'display_image' => $instance['display_image'],
			'image_size'    => $instance['image_size'],
			'excerpt'       => $instance['excerpt'],
			'arrow'         => $instance['arrow'],
			'exc_length'    => $instance['exc_length'],
			'exc_arrow'     => $instance['exc_arrow'],
			'comments'      => $instance['comments'],
			'tags'          => $instance['tags'],
			'tags_text'     => $instance['tags_text'],
			'hashtag'       => $instance['hashtag'],
			'tag_sep'       => $instance['tag_sep'],
			'archive_link'  => $instance['archive_link'],
			'link_to'       => $instance['link_to'],
			'archive_text'  => $instance['archive_text']
		));
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']         = strip_tags( $new_instance['title'] );
		$instance['author']        = $new_instance['author'];
		$instance['cat']           = $new_instance['cat'];
		$instance['tag']           = $new_instance['tag'];
		$instance['number']        = intval( strip_tags( $new_instance['number'] ) );
			if( $instance['number'] == 0 || ! is_numeric( $instance['number'] ) ) $instance['number'] = get_option( 'posts_per_page' );
		$instance['orderby']       = $new_instance['orderby'];
		$instance['order']         = $new_instance['order'];
		$instance['offset_number'] = absint( strip_tags( $new_instance['offset_number'] ) );
			if( $instance['offset_number'] == 0 || ! is_numeric( $instance['offset_number'] ) ) $instance['offset_number'] = '';
		$instance['post_status']   = $new_instance['post_status'];
		$instance['post_meta_key'] = strip_tags( $new_instance['post_meta_key'] );
		$instance['post_meta_val'] = strip_tags( $new_instance['post_meta_val'] );
		$instance['ignore_sticky'] = $new_instance['ignore_sticky'];
		$instance['display_title'] = $new_instance['display_title'];
		$instance['link_on_title'] = $new_instance['link_on_title'];
		$instance['display_date']  = $new_instance['display_date'];
		$instance['display_image'] = $new_instance['display_image'];
		$instance['image_size']    = $new_instance['image_size'];
		$instance['excerpt']       = $new_instance['excerpt'];
		$instance['exc_length']    = absint( strip_tags( $new_instance['exc_length'] ) );
			if( $instance['exc_length'] == '' || ! is_numeric( $instance['exc_length'] ) ) $instance['exc_length'] = 20;
		$instance['arrow']         = $new_instance['arrow'];
		$instance['exc_arrow']     = strip_tags( $new_instance['exc_arrow'] );
		$instance['comments']      = strip_tags( $new_instance['comments'] );
		$instance['tags']          = $new_instance['tags'];
		$instance['tags_text']     = strip_tags( $new_instance['tags_text'] );
		$instance['hashtag']       = strip_tags( $new_instance['hashtag'] );
		$instance['tag_sep']       = strip_tags( $new_instance['tag_sep'] );
		$instance['archive_link']  = $new_instance['archive_link'];
		$instance['link_to']       = $new_instance['link_to'];
		$instance['archive_text']  = strip_tags( $new_instance['archive_text'] );
		return $instance;
	}

	function form($instance) {
		$defaults = array(
			'title'         => __( 'Posts', 'pis' ),
			'author'        => '',
			'cat'           => '',
			'tag'           => '',
			'number'        => get_option( 'posts_per_page' ),
			'orderby'       => 'date',
			'order'         => 'DESC',
			'offset_number' => '',
			'post_status'   => 'publish',
			'post_meta_key' => '',
			'post_meta_val' => '',
			'ignore_sticky' => false,
			'display_title' => true,
			'link_on_title' => true,
			'display_date'  => false,
			'display_image' => false,
			'image_size'    => 'thumbnail',
			'excerpt'       => true,
			'arrow'         => false,
			'exc_length'    => 20,
			'exc_arrow'     => false,
			'comments'      => false,
			'tags'          => false,
			'tags_text'     => 'Tags:',
			'hashtag'       => '#',
			'tag_sep'       => '',
			'archive_link'  => false,
			'link_to'       => 'category',
			'archive_text'  => __( 'More posts &rarr;', 'pis' )
		);
		$instance      = wp_parse_args( (array) $instance, $defaults );
		$ignore_sticky = (bool) $instance['ignore_sticky'];
		$display_title = (bool) $instance['display_title'];
		$link_on_title = (bool) $instance['link_on_title'];
		$excerpt       = (bool) $instance['excerpt'];
		$display_date  = (bool) $instance['display_date'];
		$display_image = (bool) $instance['display_image'];
		$arrow         = (bool) $instance['arrow'];
		$exc_arrow     = (bool) $instance['exc_arrow'];
		$comments      = (bool) $instance['comments'];
		$tags          = (bool) $instance['tags'];
		$archive_link  = (bool) $instance['archive_link'];
		?>
		<div style="float: left; width: 31%; margin-left: 2%;">

			<h4><?php _e( 'The title of the widget', 'pis' ); ?></h4>
			
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>">
					<?php _e( 'Title', 'pis' ); ?>
				</label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
			</p>

			<hr />

			<h4><?php _e( 'Get these posts', 'pis' ); ?></h4>

			<p>
				<label for="<?php echo $this->get_field_id('author'); ?>">
					<?php _e( 'Author', 'pis' ); ?>
				</label>
				<select name="<?php echo $this->get_field_name('author'); ?>" >
					<?php $my_author = $instance['author']; ?>
					<option <?php selected( 'NULL', $my_author); ?> value="NULL">
						<?php _e( 'None', 'pis' ); ?>
					</option>
					<?php
						$authors = (array) get_users();
						foreach ( $authors as $author ) :
					?>
						<option <?php selected( $author->user_nicename, $my_author); ?> value="<?php echo $author->user_nicename; ?>">
							<?php echo $author->display_name; ?>
						</option>
					<?php endforeach; ?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('cat'); ?>">
					<?php _e( 'Category', 'pis' ); ?>
				</label>
				<select name="<?php echo $this->get_field_name('cat'); ?>" >
					<?php $my_category = $instance['cat']; ?>
					<option <?php selected( 'NULL', $my_category); ?> value="NULL">
						<?php _e( 'None', 'pis' ); ?>
					</option>
					<?php
						$my_cats = get_categories();
						foreach( $my_cats as $my_cat ) :
					?>
						<option <?php selected( $my_cat->slug, $my_category); ?> value="<?php echo $my_cat->slug; ?>">
							<?php echo $my_cat->cat_name; ?>
						</option>
					<?php endforeach; ?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('tag'); ?>">
					<?php _e( 'Tag', 'pis' ); ?>
				</label>
				<select name="<?php echo $this->get_field_name('tag'); ?>" >
					<?php $my_tagx = $instance['tag']; ?>
					<option <?php selected( 'NULL', $my_tagx); ?> value="NULL">
						<?php _e( 'None', 'pis' ); ?>
					</option>
					<?php
						$my_tags = get_tags();
						foreach( $my_tags as $my_tag ) :
					?>
						<option <?php selected( $my_tag->slug, $my_tagx); ?> value="<?php echo $my_tag->slug; ?>">
							<?php echo $my_tag->name; ?>
						</option>
					<?php endforeach; ?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('number'); ?>">
					<?php _e( 'How many posts to display', 'pis' ); ?>
				</label>
				<input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $instance['number']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('orderby'); ?>">
					<?php _e( 'Order by', 'pis' ); ?>
				</label>
				<select name="<?php echo $this->get_field_name('orderby'); ?>" >
					<option <?php selected( 'date', $instance['orderby']); ?> value="date">
						<?php _e( 'Date', 'pis' ); ?>
					</option>
					<option <?php selected( 'title', $instance['orderby']); ?> value="title">
						<?php _e( 'Title', 'pis' ); ?>
					</option>
					<option <?php selected( 'id', $instance['orderby']); ?> value="id">
						<?php _e( 'ID', 'pis' ); ?>
					</option>
					<option <?php selected( 'modified', $instance['orderby']); ?> value="modified">
						<?php _e( 'Modified', 'pis' ); ?>
					</option>
					<option <?php selected( 'rand', $instance['orderby']); ?> value="rand">
						<?php _e( 'Random', 'pis' ); ?>
					</option>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('order'); ?>">
					<?php _e( 'Order', 'pis' ); ?>
				</label>
				<select name="<?php echo $this->get_field_name('order'); ?>" >
					<option <?php selected( 'ASC', $instance['order']); ?> value="ASC">
						<?php _e( 'Ascending', 'pis' ); ?>
					</option>
					<option <?php selected( 'DESC', $instance['order']); ?> value="DESC">
						<?php _e( 'Descending', 'pis' ); ?>
					</option>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('offset_number'); ?>">
					<?php _e( 'Number of posts to skip', 'pis' ); ?>
				</label>
				<input class="widefat" id="<?php echo $this->get_field_id('offset_number'); ?>" name="<?php echo $this->get_field_name('offset_number'); ?>" type="text" value="<?php echo $instance['offset_number']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('post_status'); ?>">
					<?php _e( 'Post status', 'pis' ); ?>
				</label>
				<select name="<?php echo $this->get_field_name('post_status'); ?>" >
					<?php $statuses = get_post_stati( '', 'objects' );
						foreach( $statuses as $status ) { ?>
							<option <?php selected( $status->name, $instance['post_status']); ?> value="<?php echo $status->name; ?>">
								<?php _e( $status->label, 'pis' ); ?>
							</option>
						<?php }
					?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('post_meta_key'); ?>">
					<?php _e( 'Post meta key', 'pis' ); ?>
				</label>
				<input class="widefat" id="<?php echo $this->get_field_id('post_meta_key'); ?>" name="<?php echo $this->get_field_name('post_meta_key'); ?>" type="text" value="<?php echo $instance['post_meta_key']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('post_meta_val'); ?>">
					<?php _e( 'Post meta value', 'pis' ); ?>
				</label>
				<input class="widefat" id="<?php echo $this->get_field_id('post_meta_val'); ?>" name="<?php echo $this->get_field_name('post_meta_val'); ?>" type="text" value="<?php echo $instance['post_meta_val']; ?>" />
			</p>
			<p>
				<input class="checkbox" type="checkbox" <?php checked( $ignore_sticky ); ?> value="1" id="<?php echo $this->get_field_id( 'ignore_sticky' ); ?>" name="<?php echo $this->get_field_name( 'ignore_sticky' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'ignore_sticky' ); ?>">
					<?php _e( 'Ignore sticky posts', 'pis' ); ?>
				</label>
				<br /><em><?php _e( 'Sticky posts are automatically ignored if you set up an author or a taxonomy in this widget.', 'pis' ); ?></em>
			</p>

		</div>

		<div style="float: left; width: 31%; margin-left: 2%;">

			<h4><?php _e( 'The title of the post', 'pis' ); ?></h4>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $display_title ); ?> value="1" id="<?php echo $this->get_field_id( 'display_title' ); ?>" name="<?php echo $this->get_field_name( 'display_title' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'display_title' ); ?>">
					<?php _e( 'Display the title of the post', 'pis' ); ?>
				</label>
			</p>
			<p>
				<input class="checkbox" type="checkbox" <?php checked( $link_on_title ); ?> value="1" id="<?php echo $this->get_field_id( 'link_on_title' ); ?>" name="<?php echo $this->get_field_name( 'link_on_title' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'link_on_title' ); ?>">
					<?php _e( 'Link the title to the post', 'pis' ); ?>
				</label>
			</p>
			<p>
				<input class="checkbox" type="checkbox" <?php checked( $arrow ); ?> value="1" id="<?php echo $this->get_field_id( 'arrow' ); ?>" name="<?php echo $this->get_field_name( 'arrow' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'arrow' ); ?>">
					<?php _e( 'Show an arrow after the title', 'pis' ); ?>
				</label>
			</p>

			<hr />

			<h4><?php _e( 'The featured image of the post', 'pis' ); ?></h4>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $display_image ); ?> value="1" id="<?php echo $this->get_field_id( 'display_image' ); ?>" name="<?php echo $this->get_field_name( 'display_image' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'display_image' ); ?>">
					<?php _e( 'Display the featured image of the post', 'pis' ); ?>
				</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('image_size'); ?>">
					<?php _e( 'Size of the thumbnail', 'pis' ); ?>
				</label>
				<select name="<?php echo $this->get_field_name('image_size'); ?>" >
					<?php $my_size = $instance['image_size']; ?>
					<?php
						$sizes = (array) get_intermediate_image_sizes();
						foreach ( $sizes as $size ) :
					?>
						<option <?php selected( $size, $my_size); ?> value="<?php echo $size; ?>">
							<?php echo $size; ?>
						</option>
					<?php endforeach; ?>
				</select>
				<br /><em><?php printf( __(
					'Note that in order to use image sizes different from the WordPress standards, add them to your functions.php. See the %1$sCodex%2$s for further information.', 'pis'),
					'<a href="http://codex.wordpress.org/Function_Reference/add_image_size">', '</a>'
				); ?></em>
			</p>

			<hr />

			<h4><?php _e( 'The excerpt of the post', 'pis' ); ?></h4>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $excerpt ); ?> value="1" id="<?php echo $this->get_field_id( 'excerpt' ); ?>" name="<?php echo $this->get_field_name( 'excerpt' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'excerpt' ); ?>">
					<?php _e( 'Show an excerpt of the post', 'pis' ); ?>
				</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'exc_length' ); ?>">
					<?php _e( 'Length of the excerpt (in words)', 'pis' ); ?>
				</label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'exc_length' ); ?>" name="<?php echo $this->get_field_name( 'exc_length' ); ?>" type="text" value="<?php echo $instance['exc_length']; ?>" />
			</p>
			<p>
				<input class="checkbox" type="checkbox" <?php checked( $exc_arrow ); ?> value="1" id="<?php echo $this->get_field_id( 'exc_arrow' ); ?>" name="<?php echo $this->get_field_name( 'exc_arrow' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'exc_arrow' ); ?>">
					<?php _e( 'Show an arrow after the excerpt', 'pis' ); ?>
				</label>
			</p>

		</div>

		<div style="float: left; width: 31%; margin-left: 2%;">

			<h4><?php _e( 'The date and the comments of the post', 'pis' ); ?></h4>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $display_date ); ?> value="1" id="<?php echo $this->get_field_id( 'display_date' ); ?>" name="<?php echo $this->get_field_name( 'display_date' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'display_date' ); ?>">
					<?php _e( 'Display the date of the post', 'pis' ); ?>
				</label>
			</p>
			<p>
				<input class="checkbox" type="checkbox" <?php checked( $comments ); ?> value="1" id="<?php echo $this->get_field_id( 'comments' ); ?>" name="<?php echo $this->get_field_name( 'comments' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'comments' ); ?>">
					<?php _e( 'Display the number of comments', 'pis' ); ?>
				</label>
			</p>

			<hr />

			<h4><?php _e( 'The tags of the post', 'pis' ); ?></h4>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $tags ); ?> value="1" id="<?php echo $this->get_field_id( 'tags' ); ?>" name="<?php echo $this->get_field_name( 'tags' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'tags' ); ?>">
					<?php _e( 'Show the tags of the post', 'pis' ); ?>
				</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('tags_text'); ?>">
					<?php _e( 'Text before tags list', 'pis' ); ?>
				</label>
				<input class="widefat" id="<?php echo $this->get_field_id('tags_text'); ?>" name="<?php echo $this->get_field_name('tags_text'); ?>" type="text" value="<?php echo $instance['tags_text']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'hashtag' ); ?>">
					<?php _e( 'Use this hashtag', 'pis' ); ?>
				</label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'hashtag' ); ?>" name="<?php echo $this->get_field_name( 'hashtag' ); ?>" type="text" value="<?php echo $instance['hashtag']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'tag_sep' ); ?>">
					<?php _e( 'Use this separator between tags', 'pis' ); ?>
				</label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'tag_sep' ); ?>" name="<?php echo $this->get_field_name( 'tag_sep' ); ?>" type="text" value="<?php echo $instance['tag_sep']; ?>" />
				<br /><em><?php _e( 'A space will be added after the separator.', 'pis' ); ?></em>
			</p>

			<hr />

			<h4><?php _e( 'The link to the archive', 'pis' ); ?></h4>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $archive_link ); ?> value="1" id="<?php echo $this->get_field_id( 'archive_link' ); ?>" name="<?php echo $this->get_field_name( 'archive_link' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'archive_link' ); ?>">
					<?php _e( 'Show the link to the taxonomy archive', 'pis' ); ?>
				</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('link_to'); ?>">
					<?php _e( 'Link to', 'pis' ); ?>
				</label>
				<select name="<?php echo $this->get_field_name('link_to'); ?>" >
					<option <?php selected( 'author', $instance['link_to']); ?> value="author">
						<?php _e( 'Author Archive', 'pis' ); ?>
					</option>
					<option <?php selected( 'category', $instance['link_to']); ?> value="category">
						<?php _e( 'Category Archive', 'pis' ); ?>
					</option>
					<option <?php selected( 'tag', $instance['link_to']); ?> value="tag">
						<?php _e( 'Tag Archive', 'pis' ); ?>
					</option>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'archive_text' ); ?>">
					<?php _e( 'Use this text for archive link', 'pis' ); ?>
				</label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'archive_text' ); ?>" name="<?php echo $this->get_field_name( 'archive_text' ); ?>" type="text" value="<?php echo $instance['archive_text']; ?>" />
			</p>

		</div>

		<div class="clear"></div>
		
		<?php
	}

}

/**
 * Make plugin available for i18n
 * Translations must be archived in the /languages directory
 * The name of each translation file must be:
 * ITALIAN:
 * pis-it_IT.po
 * pis-it_IT.mo
 * GERMAN:
 * pis-de_DE.po
 * pis-de_DE.po
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