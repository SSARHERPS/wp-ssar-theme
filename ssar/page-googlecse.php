<?php
/*
 * Template Name: Google CSE
 * 
 * This file adds the Google SERP template to our Genesis Child Theme.
 * 
 * @author     Rick R. Duncan - B3Marketing, LLC
 * @link       http://www.rickrduncan.com
 * @license    http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 *
 */

//* Force full-width-content layout setting
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

//* Add Noindex tag to the page. There is no SEO value in having this page be indexed.
//* Hyperlinks contained within the page are followed.
add_action( 'genesis_meta', 'b3m_noindex_page' );
function b3m_noindex_page() {
	echo '<meta name="robots" content="noindex, follow">';
}


//* Insert Google CSE code into <head> section of webpage
add_action( 'genesis_meta', 'b3m_google_cse_meta', 15 );
function b3m_google_cse_meta() { ?>

	<script>
  		(function() {
    	
    		var cx = '012000668923028459847:1d4zwb-rfi0';
    		var gcse = document.createElement('script');
    
    		gcse.type = 'text/javascript';
    		gcse.async = true;
    		gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') + '//www.google.com/cse/cse.js?cx=' + cx;
    
    		var s = document.getElementsByTagName('script')[0];
    		
    		s.parentNode.insertBefore(gcse, s);
  		})();
</script>
<?php 
}


//* Add custom body class of "google-cse"
add_filter( 'body_class', 'b3m_add_body_class' );
function b3m_add_body_class( $classes ) {
   
   $classes[] = 'google-cse';
   return $classes;
   
}


//* Remove standard Genesis loop and insert our custom page content
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'b3m_custom_content' );
function b3m_custom_content() { ?>
	
	<article class="entry" itemtype="http://schema.org/SearchResultsPage" itemscope="itemscope">
		
		<header class="entry-header">
			<h1 class="entry-title" itemprop="headline">
    			<?php echo get_the_title(); ?>
    		</h1>
    	</header>
    	
    	<div class="entry-content" itemprop="text">
    		<?php echo get_the_content(); ?>
    		<gcse:searchresults-only linkTarget='_self'></gcse:searchresults-only>
    	</div> 
    </article> 
		
<?php }

genesis();