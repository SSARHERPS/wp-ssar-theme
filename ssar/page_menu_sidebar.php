<?php
/*
Template Name: Menu Sidebar Page
*/

remove_action('genesis_sidebar','genesis_do_sidebar'); 
add_action('genesis_after_content','ssar_add_specialsidebar');

// Force layout to sidebar
add_filter('genesis_pre_get_option_site_layout', 'ssar_sidebar');
function ssar_sidebar($opt) {
    if ( is_page_template() )
    $opt = 'content-sidebar';
    return $opt;
}  

function ssar_add_specialsidebar(){
 ?>
	<div id="sidebar" class="sidebar widget-area">
    
      <?php  sgi_list_childparent(); ?>
      
       
      </div>
    <?php
}
 
 
 
genesis();

?> 