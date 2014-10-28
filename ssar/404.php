<?php
//* Force full-width-content layout setting
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

/**
 * Handles display of 404 page not found errors.
 *
 * Custom 404 page not found error template. Exclude Hidden Pages From 404 Page Not Found Error Page.
 *
 * @category Genesis
 * @package  Templates
 * @author   Brad Dalton
 * @link     http://wpsites.net/web-design/add-content-404-page-genesis/
 */

/** Remove default loop **/
remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action( 'genesis_loop', 'genesis_404' );


/**
 * This function outputs a 404 "Not Found" error message
 *
 * @since 1.6
 */
function genesis_404() { ?>

	<div class="post hentry">

		<h1 class="entry-title"><?php _e( 'Uh oh, can\'t find what you\'re looking for?  (Error 404)', 'genesis' ); ?></h1>
		<div class="entry-content">
			<p><?php printf( __( 'The page you are looking for no longer exists. Perhaps you can return back to the site\'s <a href="%s">homepage</a> or, you can try finding it with a search or from the information below.', 'genesis' ), home_url() ). '</p>';

				echo '<p>' . get_search_form() . '</p>';  ?></p>

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

				<!--<h4><?php /* _e( 'Authors:', 'genesis' ); ?></h4>
				<ul>
					<?php wp_list_authors( 'exclude_admin=0&optioncount=1' ); */ ?>
				</ul> -->

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
