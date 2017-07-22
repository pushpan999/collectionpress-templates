<?php 

add_action( 'wp_enqueue_scripts', 'my_enqueue_assets' ); 

function my_enqueue_assets() { 

    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
    // enqueue child styles
	wp_enqueue_style('custom-theme', get_stylesheet_directory_uri() .'/custom.css', array('parent-style'));
    
} 

?>