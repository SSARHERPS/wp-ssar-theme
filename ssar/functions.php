<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Set Localization (do not remove)
load_child_theme_textdomain( 'minimum', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'minimum' ) );

/* Child theme (do not remove)*/
define( 'CHILD_THEME_NAME', __( 'Minimum Pro Theme', 'minimum' ) );
define( 'CHILD_THEME_URL', 'http://my.studiopress.com/themes/minimum/' );
define( 'CHILD_THEME_VERSION', '3.0.1' );  

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Enqueue scripts
add_action( 'wp_enqueue_scripts', 'minimum_enqueue_scripts' );
function minimum_enqueue_scripts() {

	wp_enqueue_script( 'minimum-responsive-menu', get_bloginfo( 'stylesheet_directory' ) . '/js/responsive-menu.js', array( 'jquery' ), '1.0.0' );
	wp_enqueue_style( 'dashicons' );
	wp_enqueue_style( 'minimum-google-fonts', '//fonts.googleapis.com/css?family=Roboto:300,400|Roboto+Slab:300,400', array(), CHILD_THEME_VERSION );

}

//* Add new image sizes
add_image_size( 'home-featured', 336, 250, TRUE );
add_image_size( 'ssar-species-featured-img', 360, 360, TRUE );

//register new featured image for species names
function ssar_species_featured_image() {
	if( has_post_thumbnail() ) {
	$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large');
   echo '<a href="' . $large_image_url[0] . '" title="' . the_title_attribute('echo=0') . '" >';
   the_post_thumbnail( 'ssar-species-featured-img' );	
   echo '</a>';
	}
} 

//* Add support for structural wraps
add_theme_support( 'genesis-structural-wraps', array(
	'header',
	'site-tagline',
	'nav',
	'subnav',
	'home-featured',
	'site-inner',
	'footer-widgets',
	'footer'
) );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 2 );

//* Unregister layout settings
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

//* Unregister secondary sidebar 
unregister_sidebar( 'sidebar-alt' );


//* Remove site description
remove_action( 'genesis_site_description', 'genesis_seo_site_description' );

//* Reposition the primary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_nav' );
if (is_front_page) {
	add_action( 'genesis_after_header', 'genesis_do_nav', 15 );
	} else {
		add_action( 'genesis_after_header', 'genesis_do_nav' );
	}


//* Add the site tagline section
if (is_front_page) {
	add_action( 'genesis_after_header', 'minimum_site_tagline', 11);
	} else {
	add_action( 'genesis_after_header', 'minimum_site_tagline' );
}

function minimum_site_tagline() {

	printf( '<div %s>', genesis_attr( 'site-tagline' ) );
	genesis_structural_wrap( 'site-tagline' );

		printf( '<div %s>', genesis_attr( 'site-tagline-left' ) );
		printf( '<p %s>%s</p>', genesis_attr( 'site-description' ), esc_html( get_bloginfo( 'description' ) ) );
		echo '</div>';
	
		printf( '<div %s>', genesis_attr( 'site-tagline-right' ) );
		genesis_widget_area( 'site-tagline-right' );
		echo '</div>';

	genesis_structural_wrap( 'site-tagline', 'close' );
	echo '</div>';

}


//* Modify the size of the Gravatar in the author box
add_filter( 'genesis_author_box_gravatar_size', 'minimum_author_box_gravatar' );
function minimum_author_box_gravatar( $size ) {

	return 144;

}

//* Modify the size of the Gravatar in the entry comments
add_filter( 'genesis_comment_list_args', 'minimum_comments_gravatar' );
function minimum_comments_gravatar( $args ) {

	$args['avatar_size'] = 96;
	return $args;

}

//* Remove comment form allowed tags
add_filter( 'comment_form_defaults', 'minimum_remove_comment_form_allowed_tags' );
function minimum_remove_comment_form_allowed_tags( $defaults ) {
	
	$defaults['comment_notes_after'] = '';
	return $defaults;

}

//* Register widget areas

/*home slider courtesy of http://sridharkatakam.com/how-to-replace-background-image-in-minimum-pro-with-responsive-slider/ */
genesis_register_sidebar( array(
	'id'	=> 'home-slider',
	'name'	=> __( 'Home Slider', 'minimum' ),
	'description'	=> __( 'This is the home slider', 'minimum' ),
) );
genesis_register_sidebar( array(
	'id'          => 'site-tagline-right',
	'name'        => __( 'Site Tagline Right', 'minimum' ),
	'description' => __( 'This is the site tagline right section.', 'minimum' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-featured-1',
	'name'        => __( 'Home Featured 1', 'minimum' ),
	'description' => __( 'This is the home featured 1 section.', 'minimum' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-featured-2',
	'name'        => __( 'Home Featured 2', 'minimum' ),
	'description' => __( 'This is the home featured 2 section.', 'minimum' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-featured-3',
	'name'        => __( 'Home Featured 3', 'minimum' ),
	'description' => __( 'This is the home featured 3 section.', 'minimum' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-featured-left',
	'name'        => __( 'Home Featured Left', 'minimum' ),
	'description' => __( 'This is the home featured left, news section.', 'minimum' ),
) ); 
genesis_register_sidebar( array(
	'id'          => 'home-featured-right',
	'name'        => __( 'Home Featured Right', 'minimum' ),
	'description' => __( 'This is the home featured right section.', 'minimum' ),
) ); 
genesis_register_sidebar( array(
	'id'            => 'zenscientist-sidebar',
	'name'          => __( 'Zen Scientist Sidebar', 'minimum' ),
	'description'   => __( 'This is the Zen Scientist sidebar', 'minimum' ),
) );
genesis_register_sidebar( array(
	'id'            => 'species-index-sidebar',
	'name'          => __( 'Species Index Sidebar', 'minimum' ),
	'description'   => __( 'This is the custom Species Index sidebar', 'minimum' ),
) );


//* Hook after post widget area after post content
add_action( 'genesis_before_sidebar_widget_area', 'ssar_zensci_widget' );
	function ssar_zensci_widget() {
	//if ( is_page( 45 ) )
		genesis_widget_area( 'zenscientist-sidebar', array(
			'before' => '<div class="sidebar widget-area">',
			'after' => '</div>',
	) );
}


/*kudos to http://sridharkatakam.com/how-to-replace-background-image-in-minimum-pro-with-responsive-slider/*/
//* Add home slider between header and tagline
add_action( 'genesis_after_header', 'minimum_slider', 9 );

function minimum_slider() {
	if (is_home() || is_front_page()) {
		printf( '<div %s>', genesis_attr( 'home-slider' ) );
		genesis_widget_area( 'home-slider' );
		echo '</div>';
	}
}

/* kudos http://sridharkatakam.com/how-to-set-up-sticky-header-or-navigation-in-genesis/ */
add_action( 'wp_enqueue_scripts', 'ssarherps_enqueue_script' );
function ssarherps_enqueue_script() {
	wp_enqueue_script( 'sticky-nav', get_bloginfo( 'stylesheet_directory' ) . '/js/sticky-nav.js', array( 'jquery' ), '', true );
}

// Register responsive menu script
//kudos http://wpbacon.com/tutorials/genesis-responsive-menu-2-0/
add_action( 'wp_enqueue_scripts', 'ssarherps_enqueue_scripts' );
/**
 * Enqueue responsive javascript  * @author Ozzy Rodriguez
 */
function ssarherps_enqueue_scripts() {

	wp_enqueue_script( 'ssarherps-responsive-menu', get_stylesheet_directory_uri() . '/js/responsive-menu.js', array( 'jquery' ), '1.0.0', true ); 

}

/** ============= Code for child menu template ==================== **/
function sgi_list_childparent() {
	global $post;
	if ($post->post_parent) {
		//get the parent and see if it'a a top page (has no parent)
		$parent = get_page($post->post_parent);
		if ($parent->post_parent) {
			//if it's not a top page, then his parent should be
			$children = wp_list_pages('title_li=&child_of=' . $parent->post_parent . '&echo=0');
		} else {
			$children = wp_list_pages('title_li=&child_of=' . $post->post_parent . '&echo=0');
		}
	} else {
		$children = wp_list_pages('title_li=&child_of=' . $post->ID . '&echo=0');
	}
	if ($children) { ?>
		
	
		<?php
			if ($post->ancestors[1]) { 
				$ancestor_title = get_the_title($post->ancestors[1]);
				$ancestor_link = get_permalink($post->ancestors[1]);
			?>
		
			<h4><a style="text-decoration:none;" href="<?php echo $ancestor_link; ?>"><?php echo $ancestor_title; ?></a></h4>
			
			
			<?php } elseif ($post->post_parent) {
				$parent_title = get_the_title($post->post_parent);
				$parent_link = get_permalink($post->post_parent);
			?>
     	
			<h4><a style="text-decoration:none;" href="<?php echo $parent_link; ?>"><?php echo $parent_title; ?></a></h4>
			<?php } else { ?>
						  
			<h4><a style="text-decoration:none;" href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h4>
            
           
			<?php }
			
				
			
			echo '<ul id="child-page-links">'. $children .'</ul>'; ?>
	
			
	<?php
	}
}


//* Customize the post info function
add_filter( 'genesis_post_info', 'sp_post_info_filter' );
function sp_post_info_filter($post_info) {
if ( !is_page() ) {
	$post_info = 'Posted [post_date] by [post_author] [post_edit]';
	return $post_info;
}}

// Modify credits section
add_filter('genesis_footer_creds_text', 'ssar_footer_creds_text');
function ssar_footer_creds_text($creds) {
    $creds = '[footer_loginout]&nbsp;&nbsp;|&nbsp;&nbsp;SSAR [footer_copyright] | All Rights Reserved.&nbsp;&nbsp;|&nbsp;&nbsp;Website by <a href="http://trishroque.com/">Trish Roque Designs</a>'; 
    return $creds;
}

//modify breacrumb
add_filter( 'genesis_breadcrumb_args', 'child_breadcrumb_args' );
function child_breadcrumb_args( $args ) {
     $args['sep']  = ' &#8594; ';
    $args['labels'] = '';
	 $args['prefix']                  = '<div class="breadcrumb">';
    $args['suffix']                  = '</div>';
    $args['hierarchical_attachments'] = true; // Genesis 1.5 and later
    $args['hierarchical_categories']  = true; // Genesis 1.5 and later
    $args['display']                 = true;
    $args['labels']['prefix']        = '';
    $args['labels']['author']        = 'Archives for ';
    $args['labels']['category']      = ''; // Genesis 1.6 and later
    $args['labels']['tag']           = 'Archives for ';
    $args['labels']['date']          = 'Archives for ';
    $args['labels']['search']        = 'Search for ';
    $args['labels']['tax']           = '';
    $args['labels']['404']           = 'Not found: '; // Genesis 1.5 and later
	$args['labels']['post_type'] = '';  
	 return $args;
}

/*===================changes login look ================================== */
function ssar_custom_login_logo() {
echo '<style type="text/css">

body.login { height: 95%; border-top-style: 5px solid #681B03; background:#efefef ; }
html { height: 100%; }
#login { width: 360px; }
#login h1 a { background:url('.dirname(get_bloginfo('stylesheet_url')).'/images/ssarlogo-admin.png) no-repeat top center; height:126px; width:360px; margin: -30px auto 5px;  }
#backtoblog { display:none; }
</style>';
}

add_action('login_head', 'ssar_custom_login_logo');

//changes the url on logo on admin page
add_filter( 'login_headerurl', 'the_url' );
function the_url( $url ) {
    return get_bloginfo( 'siteurl' );
}



/* ====================== remove some fields in admin profile panel http://www.strangework.com/2010/03/30/how-to-remove-default-profile-fields-in-wordpress/ ============================= */
  
   // add_filter('user_contactmethods','hide_profile_fields',10,1);

    function hide_profile_fields( $contactmethods ) {
    unset($contactmethods['aim']);
    unset($contactmethods['jabber']);
    unset($contactmethods['yim']);
    return $contactmethods;
    }
 
 /*============ hide personal options from admin http://brassblogs.com/cms-platforms/wordpress/hiding-information-from-the-wordpress-admin======================== */
add_action('admin_head', 'hide_profile_info');
function hide_profile_info() {
	global $pagenow; // get what file we're on
	
	if(!current_user_can('edit_others_posts')) { // we want admins and editors to still see it
		switch($pagenow) {
		case 'profile.php':
		$output = "\n\n" . '<script type="text/javascript">' . "\n";
		$output .= 'jQuery(document).ready(function() {' . "\n";
		//$output .= 'jQuery("form#your-profile > h3:first").hide();' . "\n"; // hide "Personal Options" header
		$output .= 'jQuery("form#your-profile > h3").hide();' . "\n"; // hide all headers
		$output .= 'jQuery("form#your-profile > table:first").hide();' . "\n"; // hide "Personal Options" table
		$output .= 'jQuery("table.form-table:eq(1) tr:first").hide();' . "\n"; // hide "username"
		$output .= 'jQuery("table.form-table:eq(1) tr:eq(1)").hide();' . "\n"; // hide "firstname"
		$output .= 'jQuery("table.form-table:eq(1) tr:eq(2)").hide();' . "\n"; // hide "lastname"
		$output .= 'jQuery("table.form-table:eq(1) tr:eq(3)").hide();' . "\n"; // hide "nickname"
		$output .= 'jQuery("table.form-table:eq(1) tr:eq(4)").hide();' . "\n"; // hide "display name publicly as"
		$output .= 'jQuery("table.form-table:eq(1)+h3").hide();' . "\n"; // hide "Contact Info" header
		$output .= 'jQuery("table.form-table:eq(2)").hide();' . "\n"; // hide "Contact Info" table
		$output .= 'jQuery("table.form-table:eq(3) tr:eq(0)").hide();' . "\n"; // hide "Biographical Info"
		$output .= '});' . "\n";
		$output .= '</script>' . "\n\n";
		break;
		
		default:
		$output = '';
		}
	}
echo $output;
}

/* =====Custom post-type and metabox code are in the plugins/ssar-cpt-species.php ===== */
add_action( 'init', 'cmb_initialize_species_metaboxes', 9999 );
/**
 * Initialize the metabox class.
 */
function cmb_initialize_species_metaboxes() {
	
	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'lib/metabox/init.php';
}

/* ========= Custom taxonomy for CPTs - SPECIES =======*/
add_action( 'init', 'ssar_create_tax_taxa', 0 );
function ssar_create_tax_taxa() {
	register_taxonomy(
			'taxa',
			array( 'species-name' ), //CPT
			 array(
			 	'hierarchical' => true,
				'labels' =>  array(
					'name' => _x( 'Taxa', 'taxonomy general name' ),
					'singular_name' => _x( 'Taxon', 'taxonomy singular name' ),
					'search_items' => __( 'Search Taxa' ),
					'all_items' => __( 'All Taxa' ),
					'edit_item' => __( 'Edit Taxon' ),
					'update_item' => __( 'Update Taxon' ),
					'add_new_item' => __( 'Add New Taxon' ),
					'new_cause_name' => __( 'New Taxon' ),
					'menu_name' => __( 'Taxa' ),
				),
				//Control slugs used for this taxonomy
				'rewrite' => array(
					'slug' => 'taxa',
					'with_front' => false,
					'hierarchical' => true
				),
				'query_var' => 'taxa',
				'has_archive' => true
		 ) );
}


/* ================== add new fields in custom taxonomy 
kudos https://pippinsplugins.com/adding-custom-meta-fields-to-taxonomies/ */

// Add term page
function ssar_taxonomy_add_new_meta_field() {
	// this will add the custom meta field to the add new term page
	?>
<div class="form-field">
		<label for="term_meta[custom_term_meta]"><?php _e( 'Taxon common name', 'pippin' ); ?></label>
		<input type="text" name="term_meta[custom_term_meta]" id="term_meta[custom_term_meta]" value="">
		<p class="description"><?php _e( 'Enter the common name for this taxon','pippin' ); ?></p>
	</div>
 
<?php
}
add_action( 'taxa_add_form_fields', 'ssar_taxonomy_add_new_meta_field', 10, 2 );

// Edit term page
function ssar_taxonomy_edit_meta_field($term) {
 
	// put the term ID into a variable
	$t_id = $term->term_id;
 
	// retrieve the existing value(s) for this meta field. This returns an array
	$term_meta = get_option( "taxonomy_$t_id" ); ?>
	<tr class="form-field">
	<th scope="row" valign="top"><label for="term_meta[custom_term_meta]"><?php _e( 'Taxon common name', 'pippin' ); ?></label></th>
		<td>
			<input type="text" name="term_meta[custom_term_meta]" id="term_meta[custom_term_meta]" value="<?php echo esc_attr( $term_meta['custom_term_meta'] ) ? esc_attr( $term_meta['custom_term_meta'] ) : ''; ?>">
			<p class="description"><?php _e( 'Enter the common name for this taxon','pippin' ); ?></p>
		</td>
	</tr>
<?php
}
add_action( 'taxa_edit_form_fields', 'ssar_taxonomy_edit_meta_field', 10, 2 );

// Save extra taxonomy fields callback function.
function save_taxonomy_custom_meta( $term_id ) {
	if ( isset( $_POST['term_meta'] ) ) {
		$t_id = $term_id;
		$term_meta = get_option( "taxonomy_$t_id" );
		$cat_keys = array_keys( $_POST['term_meta'] );
		foreach ( $cat_keys as $key ) {
			if ( isset ( $_POST['term_meta'][$key] ) ) {
				$term_meta[$key] = $_POST['term_meta'][$key];
			}
		}
		// Save the option array.
		update_option( "taxonomy_$t_id", $term_meta );
	}
}  
add_action( 'edited_taxa', 'save_taxonomy_custom_meta', 10, 2 );  
add_action( 'create_taxa', 'save_taxonomy_custom_meta', 10, 2 );

/*custom taxonomy for CPT species-names by_alpha========================= */
add_action( 'init', 'create_tax_speciesalphabet', 0 ); 
function create_tax_speciesalphabet() {
	register_taxonomy( 'species_alphabet',
						'species-name',
						  array( 
						  	'hierarchical' => false,
							'label' => __('By Alphabet'), 
							'query_var' => 'by_alpha',
							'rewrite' => array( 'slug' => 'by_alpha' )
						 )
					 );
}


/**==============================================================================
Helper function to add first letter of species name for alpha sorting
 * Define default terms for custom taxonomies in WordPress 3.0.1
 *
 * @author    Michael Fields     http://wordpress.mfields.org/
 * @props     John P. Bloch      http://www.johnpbloch.com/
 *
 * @since     2010-09-13
 * @alter     2010-09-14
 *
 * @license   GPLv2
 */
function mfields_set_default_object_terms( $post_id, $post ) {
    if ( 'publish' === $post->post_status ) {
		global $post;
		$post = get_post( $post_ID ); // get post object
		$speciesname = get_post_meta($post->ID, '_cmb_species_species_name',true);
		$species_initial = ucwords(substr($speciesname,0,1));
		
		 $defaults = array(
            'species_alphabet' => array( $species_initial ),
          
            );
			
        $taxonomies = get_object_taxonomies( $post->post_type );
        foreach ( (array) $taxonomies as $taxonomy ) {
            $terms = wp_get_post_terms( $post_id, $taxonomy );
            if ( empty( $terms ) && array_key_exists( $taxonomy, $defaults ) ) {
                wp_set_object_terms( $post_id, $defaults[$taxonomy], $taxonomy );
            }
        }
    }
}
add_action( 'save_post', 'mfields_set_default_object_terms', 100, 2 );

/* ================== re-order admin menu =========
kudos http://code.tutsplus.com/articles/customizing-your-wordpress-admin--wp-24941 ========= */
function custom_menu_order($menu_ord) {
    if (!$menu_ord) return true;
     
    return array(
        'index.php', // Dashboard
        'separator1', // First separator
		 'edit.php?post_type=page', // Pages
		'edit.php', // Posts
		'edit.php?post_type=species-name', // CPT 
        'upload.php', // Media
        'link-manager.php', // Links
       
        'edit-comments.php', // Comments
        'separator2', // Second separator
		//'admin.php?page=genesis', //genesis
        'themes.php', // Appearance
        'plugins.php', // Plugins
        'users.php', // Users
        'tools.php', // Tools
	    'options-general.php', // Settings
        'separator-last', // Last separator
    );
}
add_filter('custom_menu_order', 'custom_menu_order'); // Activate custom_menu_order
add_filter('menu_order', 'custom_menu_order');



/* =============== SAVE THE TITLE IN CPT Species Name ===================== */
//predefined hook from WP codex 'title_save_pre'
add_filter('title_save_pre', 'save_new_title');
function save_new_title($species_title) {
	
	if ($_POST['post_type'] == 'species-name') :
	
		$species_title = ltrim($_POST['_cmb_species_species_name']);
	
	endif;
	return $species_title;
	
}

/* Alter the Genesis Search for so that we can change the destination page and our querystring parameter.
kudos http://www.rickrduncan.com/marketing/wordpress/genesis-google-cse ============ */
add_filter( 'genesis_search_form', 'b3m_search_form', 10, 4);
function b3m_search_form( $form, $search_text, $button_text, $label ) {
$onfocus = " onfocus=\"if (this.value == '$search_text') {this.value = '';}\"";
$onblur = " onblur=\"if (this.value == '') {this.value = '$search_text';}\"";
$form = '<form method="get" class="searchform search-form" action="' . home_url() . '/search" >' . $label . '
<input type="text" value="' . esc_attr( $search_text ) . '" name="q" class="s search-input"' . $onfocus . $onblur . ' />
<input type="submit" class="searchsubmit search-submit" value="' . esc_attr( $button_text ) . '" />
</form>';
return $form;
}
