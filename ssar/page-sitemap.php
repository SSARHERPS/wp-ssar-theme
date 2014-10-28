<?php
/*
Template Name: Sitemap Page
*/

//* Force full-width-content layout setting
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

/**
modified from http://wpsites.net/web-design/add-content-404-page-genesis/
 */

/** Remove default loop **/
remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action( 'genesis_loop', 'ssar_sitemap' );


/**
 * This function outputs a 404 "Not Found" error message
 *
 * @since 1.6
 */
function ssar_sitemap() { ?>

	<div class="post hentry">

		<h1 class="entry-title"><?php _e( 'SSAR Sitemap', 'genesis' ); ?></h1>
		<div class="entry-content">

			<div class="archive-page">

				<h4><?php _e( 'Pages:', 'genesis' ); ?></h4>
				<ul>
					<?php wp_list_pages('exclude=24,36&title_li='); ?>
				</ul>

				<h4><?php _e( 'Categories:', 'genesis' ); ?></h4>
				<ul>
					<?php wp_list_categories( 'sort_column=name&title_li=' ); ?>
				</ul>

			</div><!-- end .archive-page-->

			<div class="archive-page">

				<h4><?php _e( 'Authors:', 'genesis' ); ?></h4>
				<ul>
					<?php wp_list_authors( 'exclude_admin=0&optioncount=1' ); ?>
				</ul> 

				<h4><?php _e( 'Monthly:', 'genesis' ); ?></h4>
				<ul>
					<?php wp_get_archives( 'type=monthly' ); ?>
				</ul>

				<h4><?php _e( 'Recent Posts:', 'genesis' ); ?></h4>
				<ul>
					<?php wp_get_archives( 'type=postbypost&limit=100' ); ?>
				</ul>

			</div><!-- end .archive-page-->

		</div><!-- end .entry-content -->

	</div><!-- end .postclass -->

<?php
}

genesis();
