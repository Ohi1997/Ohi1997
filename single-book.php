<?php

get_header();

$show_default_title = get_post_meta(get_the_ID(), '_et_pb_show_title', true);

$is_page_builder_used = et_pb_is_pagebuilder_used(get_the_ID());

?>

<div id="main-content">
	<?php
	if (et_builder_is_product_tour_enabled()) :
		// load fullwidth page in Product Tour mode
		while (have_posts()) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class('et_pb_post'); ?>>
				<div class="entry-content">
					<?php
					the_content();
					?>
				</div>

			</article>

		<?php endwhile;
	else :
		?>
		<div class="container">
			<div id="content-area" class="clearfix">
				<div id="left-area">
					<?php while (have_posts()) : the_post(); ?>
						<?php
						/**
						 * Fires before the title and post meta on single posts.
						 *
						 * @since 3.18.8
						 */
						do_action('et_before_post');
						?>
						<article id="post-<?php the_ID(); ?>" <?php post_class('et_pb_post'); ?>>
							<?php if (('off' !== $show_default_title && $is_page_builder_used) || !$is_page_builder_used) { ?>
								<h1 class="entry-title"><?php the_title(); ?></h1>
								<hr class="heading-section-break">
								<div class="et_post_meta_wrapper book-content">
									<div class="book-content-left">
										<?php
										if (!post_password_required()) :

											et_divi_post_meta();

											$thumb = '';

											$width = (int) apply_filters('et_pb_index_blog_image_width', 355);

											$height = (int) apply_filters('et_pb_index_blog_image_height', 150);
											$classtext = 'et_featured_image';
											$titletext = get_the_title();
											$alttext = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true);
											$thumbnail = get_thumbnail($width, $height, $classtext, $alttext, $titletext, false, 'Blogimage');
											$thumb = $thumbnail["thumb"];

											$post_format = et_pb_post_format();

											if ('video' === $post_format && false !== ($first_video = et_get_first_video())) {
												printf(
													'<div class="et_main_video_container">
												%1$s
												</div>',
													et_core_esc_previously($first_video)
												);
											} else if (!in_array($post_format, array('gallery', 'link', 'quote')) && 'on' === et_get_option('divi_thumbnails', 'on') && '' !== $thumb) {
												print_thumbnail($thumb, $thumbnail["use_timthumb"], $alttext, $width, $height);
											} else if ('gallery' === $post_format) {
												et_pb_gallery_images();
											}
										?>

										<?php
											$text_color_class = et_divi_get_post_text_color();

											$inline_style = et_divi_get_post_bg_inline_style();

											switch ($post_format) {
												case 'audio':
													$audio_player = et_pb_get_audio_player();

													if ($audio_player) {
														printf(
															'<div class="et_audio_content%1$s"%2$s>
														%3$s
														</div>',
															esc_attr($text_color_class),
															et_core_esc_previously($inline_style),
															et_core_esc_previously($audio_player)
														);
													}

													break;
												case 'quote':
													printf(
														'<div class="et_quote_content%2$s"%3$s>
														%1$s
														</div>',
														et_core_esc_previously(et_get_blockquote_in_content()),
														esc_attr($text_color_class),
														et_core_esc_previously($inline_style)
													);

													break;
												case 'link':
													printf(
														'<div class="et_link_content%3$s"%4$s>
															<a href="%1$s" class="et_link_main_url">%2$s</a>
															</div>',
														esc_url(et_get_link_url()),
														esc_html(et_get_link_url()),
														esc_attr($text_color_class),
														et_core_esc_previously($inline_style)
													);

													break;
											}

										endif;
										?>
									</div>
									<div class="book-content-right">
										<div class="book-details">
											<h2 class="headline headline--medium">Book Details</h2>
											<hr class="heading-section-break">

											<?php if (!empty(get_field('book_edition'))) : ?>
												<p class="book-details-dash"><b>Book Edition:</b> <?php echo get_field('book_edition'); ?></p>
											<?php endif; ?>

											<?php if (!empty(get_field('isbn'))) : ?>
												<p class="book-details-dot"><b>ISBN:</b> <?php echo get_field('isbn'); ?></p>
											<?php endif; ?>

											<?php if (!empty(get_field('language'))) : ?>
												<p class="book-details-dash"><b>Language:</b> <?php echo get_field('language'); ?></p>
											<?php endif; ?>

											<?php if (!empty(get_field('paperback'))) : ?>
												<p class="book-details-dot"><b>PaperBack:</b> <?php echo get_field('paperback'); ?> pages</p>
											<?php endif; ?>

											<?php if (!empty(get_field('publisher'))) : ?>
												<p class="book-details-dash"><b>Publisher:</b> <?php echo get_field('publisher'); ?></p>
											<?php endif; ?>

											<?php if (!empty(get_field('publication_date'))) : ?>
												<p class="book-details-dot"><b>Publish Date:</b> <?php echo get_field('publication_date'); ?></p>
											<?php endif; ?>
										</div>
									</div>

								</div>
							<?php  } ?>

							<div class="entry-content">

								<?php
								do_action('et_before_content');

								the_content();

								wp_link_pages(array('before' => '<div class="page-links">' . esc_html__('Pages:', 'Divi'), 'after' => '</div>'));
								?>
							</div>
							<div class="et_post_meta_wrapper">
								<?php
								if (et_get_option('divi_468_enable') === 'on') {
									echo '<div class="et-single-post-ad">';
									if (et_get_option('divi_468_adsense') !== '') echo et_core_intentionally_unescaped(et_core_fix_unclosed_html_tags(et_get_option('divi_468_adsense')), 'html');
									else { ?>
										<a href="<?php echo esc_url(strval(et_get_option('divi_468_url'))); ?>"><img src="<?php echo esc_attr(et_get_option('divi_468_image')); ?>" alt="468" class="foursixeight" /></a>
								<?php 	}
									echo '</div>';
								}

								/**
								 * Fires after the post content on single posts.
								 *
								 * @since 3.18.8
								 */
								do_action('et_after_post');

								if ((comments_open() || get_comments_number()) && 'on' === et_get_option('divi_show_postcomments', 'on')) {
									comments_template('', true);
								}
								?>
							</div>
						</article>

					<?php endwhile; ?>


					<div class="related-authors">
						<?php
						echo '<h2 class="headline headline--medium">Book Author(s)</h2>';
						$relatedBookAuthors = get_field('related_authors');

						if (!empty($relatedBookAuthors)) {
							echo '<ul class="author-grid">';
							foreach ($relatedBookAuthors as $author) { ?>
								<li class="list-author">
									<a class="list-anchor" href="<?php echo get_the_permalink($author); ?>">
										<img class="author-image" src="<?php echo get_the_post_thumbnail_url($author); ?>" alt="">
										<span class="author-name"><?php echo get_the_title($author); ?></span>
									</a>
								</li>
						<?php }
							echo '</ul>';
						} else {
							echo '<p>No authors found for this book.</p>';
						}
						?>
					</div>



				</div>

				<?php get_sidebar(); ?>
			</div>
		</div>
	<?php endif; ?>

</div>

<?php

get_footer();
