<?php
 /* File name uses WP hierarchy naming convention: single-nameofcustomposttype.php (single-process-of-change.php). WP automatically knows to use this page instead of default template, which displays the content of the custom post type (CPT) and custom fields for Causes of Change (Drivers info pages).  */
 
 //force full width layout on effects story page
//add_filter('genesis_pre_get_option_site_layout', '__genesis_return_full_width_content');
remove_action('genesis_sidebar','genesis_do_sidebar'); 
add_action('genesis_sidebar','ssar_add_speciessidebar');
// Force layout to sidebar
add_filter('genesis_pre_get_option_site_layout', 'ssar_sidebar');
function ssar_sidebar($opt) {
    if ( is_page_template() )
    $opt = 'content-sidebar';
    return $opt;
}  

function ssar_add_speciessidebar(){
	genesis_widget_area( 'species-index-sidebar', array(
			'before' => '<div class="sidebar widget-area">',
			'after' => '</div>',
	) );
}

//removes Genesis breadcrumbs, adds taxonomy breadcrumbs
remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');

add_action( 'genesis_before_loop', 'be_taxonomy_breadcrumb' );
function be_taxonomy_breadcrumb() {
	/*kudos http://www.billerickson.net/wordpress-taxonomy-breadcrumbs/ */
	// Get the current term
	 echo '<div class="breadcrumb"><a href="'.get_bloginfo('url').'">Home</a> &#8594; '. '<a href="'.get_bloginfo('url').'/?post_type=species-name">North American Checklist</a>';
	$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
	 
	// Create a list of all the term's parents
	$parent = $term->parent;
	while ($parent):
		$parents[] = $parent;
		$new_parent = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ));
		$parent = $new_parent->parent;
	endwhile;
	if(!empty($parents)):
		$parents = array_reverse($parents);
	
	// For each parent, create a breadcrumb item
	foreach ($parents as $parent):
		$item = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ));
		$url = get_bloginfo('url').'/'.$item->taxonomy.'/'.$item->slug;
		echo ' &#8594; <a href="'.$url.'">'.$item->name.'</a>';
	endforeach;
	endif;
	 
	// Display the current term in the breadcrumb
	echo ' &#8594; '. $term->name.'</div>';
}



// Remove the post meta function
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );

add_action( 'genesis_before_loop' , 'ssar_get_custom_term_meta' );
function ssar_get_custom_term_meta() {
	$term = get_queried_object();
	
	// put the term ID into a variable
	$t_id = $term->term_id;
	$term_meta = get_option( "taxonomy_$t_id" );
	$term_name = $term->name;
	echo '<p style="font-weight: 400;">'. $term_name .' common name: '. $term_meta['custom_term_meta'] .'</p>';
}
//==========================alpha list links ========================


/*see http://wordpress.stackexchange.com/questions/41660/how-to-build-a-directory-with-indexes-in-wordpress/?codekitCB=404144737.558857 
http://pastebin.com/9VEiyYmN */

add_action('genesis_before_loop', 'ssar_alpha_links');
function ssar_alpha_links() {
	
		$taxonomy = 'taxa';
		$term = get_queried_object();
		
		echo '<p class="alphaindex" ><a href="'.get_bloginfo('url').'/?post_type=species-name">Alphabetical Species Index</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<strong>'.$term->name.'</strong> : ';

		ssar_alpha_create()	;
	echo '</p>';
	
}

function ssar_alpha_create(){
	   global $wp_query;
  	
	  $args = array (
           	'post_type' => 'species-name',
		    'meta_key' => '_cmb_species_species_name',  //custom field
   			'orderby' => 'meta_value',
			'order' => 'ASC',
          ); 
		  


  	query_posts(array_merge( $wp_query->query,$args )); 
	
	
				
	if ( have_posts() ) {
		 $in_this_row = 0;
		  while ( have_posts() ) {
               the_post();
			   $speciesname = genesis_get_custom_field('_cmb_species_species_name');
			  	$first_letter = strtoupper(substr($speciesname, 0, 1));
				
				  if ($first_letter != $curr_letter) {
                  if (++$post_count > 1) {
				  
                  echo '';
				  
				  				  
				}
				
				 start_new_letter($first_letter);
				  $curr_letter = $first_letter;
				
			   }
			    if (++$in_this_row > $posts_per_row) {
               
				 global $in_this_row;
   				 $in_this_row = 0;
				  
				
                  ++$in_this_row;  // Account for this first post
               }
			 //echo $curr_letter;
			  
		  }
	}
}
		  		
	
function start_new_letter($letter) {
			$taxonomy = 'taxa';
			$term = get_queried_object();
			
		 
			 echo '<a title="Link to taxa that start with '. $letter .'" href="'.get_bloginfo('url').'/?taxa='.strtolower($term->name) .'&amp;by_alpha='.$letter.'">'.$letter.'</a>&nbsp;&nbsp;&nbsp;'; 
				
			
}


/*======================main content custom fields======================= */
add_action( 'genesis_entry_content', 'ssar_cpt_species_fields' );
function ssar_cpt_species_fields() {
	
	echo '<div class="taxonomy-custemplate" >';
	//get taxa 
	/* see http://wordpress.stackexchange.com/questions/37285/custom-taxonomy-get-the-terms-listing-in-order-of-parent-child */
		$taxonomy = 'taxa';
		$terms = get_the_terms( $post->ID , $taxonomy );
		
		 // if terms is not array or its empty don't proceed
    if ( ! is_array( $terms ) || empty( $terms ) ) {
        return false;
    }

    foreach ( $terms as $term ) {
			$link = get_term_link( $term, $taxonomy );
        // if the term have a parent, set the child term as attribute in parent term
        if ( $term->parent != 0 )  {
            $terms[$term->parent]->child = $term;   
			$clink = get_term_link( $term, $taxonomy );
        } else {
            // record the parent term
            $parent = $term;
        }
	} 
		$order_name = $parent->name;
		$genus_name = $parent->child->name;
		
		//species name
		if( !genesis_get_custom_field( '_cmb_species_species_name' ) ) {
		echo '';
	} else {
		echo '<p style="font-size: 24px;"><em>' .ucwords($genus_name) .' '. strtolower( genesis_get_custom_field( '_cmb_species_species_name') ).'</em></p>';
	}
	
	
	echo '<div style="margin-left: 0;" class="one-third">';
	//image
	 ssar_species_featured_image();
	 echo '</div>';
	 
	 	echo '<div class="two-thirds">';
	
	//common name
		if( !genesis_get_custom_field( '_cmb_species_common_name' ) ) {
		echo '';
	} else {
		echo '<p style="font-size: 24px; font-weight: 400; margin-bottom: -5px;">Common Name: '. genesis_get_custom_field( '_cmb_species_common_name').'</p>';
	}
	
	//species author
		if( !genesis_get_custom_field( '_cmb_species_author' ) ) {
		echo '';
	} else {
		echo '<p>Author: '. genesis_get_custom_field( '_cmb_species_author');
	}
	
	//species year
	if( !genesis_get_custom_field( '_cmb_species_species_year' ) ) {
		echo '</p>';
	} else {
		echo ' ,'. genesis_get_custom_field( '_cmb_species_species_year').'</p>';
	}
	
	
	
	
	
		//taxonomy		
		 echo '<h3>Taxonomy</h3>
		<p>Order: <a href="'.$link.'">'.$order_name.'</a></p>
		<p> Genus: <a href="'.$clink.'">'.$genus_name.'</a></p>';
		
		
		
		
		
		//subspecie
		if( !genesis_get_custom_field( '_cmb_species_subspecies' ) ) {
		echo '';
	} else {
		echo '<p>Subspecies: '. genesis_get_custom_field( '_cmb_species_subspecies').'</p>';
	}
		
	 echo '</div>';
	 
	 echo '<div style="clear: both;"></div>';
		
	//notes
	
		
		if( !genesis_get_custom_field( '_cmb_species_notes' ) ) {
		echo '';
	} else {
	
		echo '<p style="margin-top: 20px;">Notes: '. genesis_get_custom_field( '_cmb_species_notes').'</p>';
		
	}
	
	echo '</div>';
}

 genesis();  
?>