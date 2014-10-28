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
add_action( 'genesis_before_loop', 'ssar_cp_species_breadcrumbs' );

//adds current taxonomy to breadcrumbs
 function ssar_cp_species_breadcrumbs() {
		//$taxonomy = 'species_alphabet';
		//$term = get_queried_object();
	echo '<div class="breadcrumb"><a href="'.get_bloginfo('url').'">Home</a> &#8594; ' . '<a href="'.get_bloginfo('url').'/?post_type=species-name">North American Checklist</a> &#8594; <em>' . get_the_title() .'</em></div>';
		
			}


// Remove the post meta function
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );



//main content custom fields
add_action( 'genesis_entry_content', 'ssar_cpt_species_fields' );
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