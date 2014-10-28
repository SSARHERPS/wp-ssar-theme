<?php
 /* Archive of species-names  */
 
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
add_action( 'genesis_before_loop', 'ssar_cp_species_breadcrumbs' );

//adds current taxonomy to breadcrumbs
 function ssar_cp_species_breadcrumbs() {
		$taxonomy = 'species_alphabet';
		$term = get_queried_object();
	echo '<div class="breadcrumb"><a href="'.get_bloginfo('url').'">Home</a> &#8594; North American Checklist</div>';
		
			}

// Remove the post meta function
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action('genesis_before_loop', 'ssar_alpha_links');
function ssar_alpha_links() {

		echo '<p class="alphaindex"><a href="'.get_bloginfo('url').'/?post_type=species-name">Alphabetical Species Index</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;';

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
			
			
		 
			 echo '<a title="Link to species that start with '. $letter .'" href="?by_alpha='.strtolower($letter).'">'.$letter.'</a>&nbsp;&nbsp;&nbsp;'; 
				
			
}



//main content custom fields
//add_action( 'genesis_entry_content', 'ssar_cpt_species_fields' );
function ssar_cpt_species_fields() {
	
	
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

}

 genesis();  
?>