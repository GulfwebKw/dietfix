<?php
/**
 * Post Grid
 *
 */
if (!function_exists('posts_grid_shortcode')) {

	function posts_grid_shortcode($atts, $content = null) {
		extract(shortcode_atts(array(
			'type'          => '',
			'columns'       => '3',
			'rows'          => '3',
			'order_by'      => 'date',
			'order'         => 'DESC',
			'thumb_width'   => '370',
			'thumb_height'  => '250',
			'meta'          => '',
			'excerpt_count' => '15',
			'link'          => 'yes',
			'link_text'     => theme_locals('read_more'),
			'custom_class'  => ''
		), $atts));


		$spans = $columns;
		$rand  = rand();

		// columns
		switch ($spans) {
			case '1':
				$spans = 'span12';
				break;
			case '2':
				$spans = 'span6';
				break;
			case '3':
				$spans = 'span4';
				break;
			case '4':
				$spans = 'span3';
				break;
			case '6':
				$spans = 'span2';
				break;
		}

		// check what order by method user selected
		switch ($order_by) {
			case 'date':
				$order_by = 'post_date';
				break;
			case 'title':
				$order_by = 'title';
				break;
			case 'popular':
				$order_by = 'comment_count';
				break;
			case 'random':
				$order_by = 'rand';
				break;
		}

		// check what order method user selected (DESC or ASC)
		switch ($order) {
			case 'DESC':
				$order = 'DESC';
				break;
			case 'ASC':
				$order = 'ASC';
				break;
		}

		// show link after posts?
		switch ($link) {
			case 'yes':
				$link = true;
				break;
			case 'no':
				$link = false;
				break;
		}

			global $post;
			global $my_string_limit_words;

			$numb = $columns * $rows;
							
			$args = array(
				'post_type'   => $type,
				'numberposts' => $numb,
				'orderby'     => $order_by,
				'order'       => $order
			);		

			$posts = get_posts($args);
			$i = 0;
			$count = 1;
			$output_end = '';
			if ($numb > count($posts)) {
				$output_end = '</ul>';
			}

			$output = '<ul class="posts-grid row-fluid unstyled '. $custom_class .'">';

			for ( $j=0; $j < count($posts); $j++ ) {
				$post_id        = $posts[$j]->ID;
				setup_postdata($posts[$j]);
				$excerpt        = get_the_excerpt();
				$attachment_url = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'full' );
				$url            = $attachment_url['0'];
				$image          = aq_resize($url, $thumb_width, $thumb_height, true);
				$mediaType      = get_post_meta($post_id, 'tz_portfolio_type', true);
				$prettyType     = 0;

				if ($count > $columns) {
					$count = 1;
					$output .= '<ul class="posts-grid row-fluid unstyled '. $custom_class .'">';
				}

				$output .= '<li class="'. $spans .'">';

					if($args[post_type] == "clients") {
						$output .= '<figure class="featured-thumbnail thumbnail"><a href="'.get_permalink($post_id).'" title="'.get_the_title($post_id).'">';
						$output .= '<img  src="'.$image.'" alt="'.get_the_title($post_id).'" />';
						$output .= '</a></figure>';

					} else {

						if(has_post_thumbnail($post_id) && $mediaType == 'Image') {
												
							$prettyType = 'prettyPhoto-'.$rand;									

							$output .= '<figure class="featured-thumbnail thumbnail">';
							$output .= '<a href="'.$url.'" title="'.get_the_title($post_id).'" rel="' .$prettyType.'">';
							$output .= '<img  src="'.$image.'" alt="'.get_the_title($post_id).'" />';
							$output .= '<span class="zoom-icon"></span></a></figure>';
						} elseif ($mediaType != 'Video' && $mediaType != 'Audio') {					

							$thumbid = 0;
							$thumbid = get_post_thumbnail_id($post_id);
											
							$images = get_children( array(
								'orderby' => 'menu_order',
								'order' => 'ASC',
								'post_type' => 'attachment',
								'post_parent' => $post_id,
								'post_mime_type' => 'image',
								'post_status' => null,
								'numberposts' => -1
							) ); 

							if ( $images ) {

								$k = 0;
								//looping through the images
								foreach ( $images as $attachment_id => $attachment ) {
									$prettyType = "prettyPhoto-".$rand ."[gallery".$i."]";								
									//if( $attachment->ID == $thumbid ) continue;

									$image_attributes = wp_get_attachment_image_src( $attachment_id, 'full' ); // returns an array
									$img = aq_resize( $image_attributes[0], $thumb_width, $thumb_height, true ); //resize & crop img
									$alt = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
									$image_title = $attachment->post_title;

									if ( $k == 0 ) {
										if (has_post_thumbnail($post_id)) {
											$output .= '<figure class="featured-thumbnail thumbnail">';
											if($args[post_type] == "clients") {	$output .= ''; } 
											else { $output .= '<a href="'.$image_attributes[0].'" title="'.get_the_title($post_id).'" rel="' .$prettyType.'">'; }
											$output .= '<img src="'.$image.'" alt="'.get_the_title($post_id).'" />';
										} else {
											$output .= '<figure class="featured-thumbnail thumbnail">';
											if($args[post_type] == "clients") {	$output .= ''; } 
											else { $output .= '<a href="'.$image_attributes[0].'" title="'.get_the_title($post_id).'" rel="' .$prettyType.'">'; }
											$output .= '<img  src="'.$img.'" alt="'.get_the_title($post_id).'" />';
										}
									} else {
										$output .= '<figure class="featured-thumbnail thumbnail" style="display:none;">';
										$output .= '<a href="'.$image_attributes[0].'" title="'.get_the_title($post_id).'" rel="' .$prettyType.'">';
										$output .= '<img  src="'.$img.'" alt="'.get_the_title($post_id).'" />';
									}
									$output .= '<span class="zoom-icon"></span>';

									if($args[post_type] == "clients") {	$output .= ''; } 
									else { $output .= '</a>'; }

									$output .= '</figure>';

									$k++;
								}					
							} elseif (has_post_thumbnail($post_id)) {
								$prettyType = 'prettyPhoto-'.$rand;
								$output .= '<figure class="featured-thumbnail thumbnail">';
								$output .= '<a href="'.$url.'" title="'.get_the_title($post_id).'" rel="' .$prettyType.'">';
								$output .= '<img  src="'.$image.'" alt="'.get_the_title($post_id).'" />';
								$output .= '<span class="zoom-icon"></span></a></figure>';
							}
						} else {

							// for Video and Audio post format - no lightbox
							$output .= '<figure class="featured-thumbnail thumbnail"><a href="'.get_permalink($post_id).'" title="'.get_the_title($post_id).'">';
							$output .= '<img  src="'.$image.'" alt="'.get_the_title($post_id).'" />';
							$output .= '</a></figure>';
						}

					}

					$output .= '<div class="clear"></div>';

					$output .= '<h5><a href="'.get_permalink($post_id).'" title="'.get_the_title($post_id).'">';
						$output .= get_the_title($post_id);
					$output .= '</a></h5>';

					if ($meta == 'yes') {
						// begin post meta
						$output .= '<div class="post_meta">';

							// post category
							$output .= '<span class="post_category">';
							if ($type!='' && $type!='post') {
								$terms = get_the_terms( $post_id, $type.'_category');
								if ( $terms && ! is_wp_error( $terms ) ) {
									$out = array();
									$output .= '<em>Posted in </em>';
									foreach ( $terms as $term )
										$out[] = '<a href="' .get_term_link($term->slug, $type.'_category') .'">'.$term->name.'</a>';
										$output .= join( ', ', $out );
								}
							} else {
								$categories = get_the_category();
								if($categories){
									$out = array();
									$output .= '<em>Posted in </em>';
									foreach($categories as $category)
										$out[] = '<a href="'.get_category_link($category->term_id ).'" title="'.$category->name.'">'.$category->cat_name.'</a> ';
										$output .= join( ', ', $out );
								}
							}
							$output .= '</span>';

							// post date
							$output .= '<span class="post_date">';
							$output .= '<time datetime="'.get_the_time('Y-m-d\TH:i:s', $post_id).'">' .get_the_time( get_option( 'date_format' ), $post_id ). '</time>';
							$output .= '</span>';

							// post author
							$output .= '<span class="post_author">';
							$output .= '<em>by </em>';
							$output .= '<a href="'.get_author_posts_url(get_the_author_meta( 'ID' )).'">'.get_the_author_meta('display_name').'</a>';
							$output .= '</span>';

							// post comment count
							$num = 0;						
							$queried_post = get_post($post_id);
							$cc = $queried_post->comment_count;
							if( $cc == $num || $cc > 1 ) : $cc = $cc.' Comments';
							else : $cc = $cc.' Comment';
							endif;
							$permalink = get_permalink($post_id);
							$output .= '<span class="post_comment">';
							$output .= '<a href="'. $permalink . '" class="comments_link">' . $cc . '</a>';
							$output .= '</span>';

						$output .= '</div>';
						// end post meta
					}

					if($excerpt_count >= 1){
						$output .= '<p class="excerpt">';
							$output .= my_string_limit_words($excerpt,$excerpt_count);
						$output .= '</p>';
					}
					if($link){
						$output .= '<a href="'.get_permalink($post_id).'" class="btn btn-primary" title="'.get_the_title($post_id).'">';
						$output .= $link_text;
						$output .= '</a>';
					}
					$output .= '</li>';
					if ($j == count($posts)-1) {
						$output .= $output_end;
					}
				if ($count % $columns == 0) {
					$output .= '</ul><!-- .posts-grid (end) -->';
				}
			$count++;
			$i++;		

		} // end for
		
		return $output;
	}	 
	add_shortcode('posts_grid', 'posts_grid_shortcode');	
}?>