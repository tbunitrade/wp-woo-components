<?php

function enqueue_custom_styles_scripts() {
    // Стили и скрипты для продукта
    wp_enqueue_style( 'product-style', get_stylesheet_directory_uri() . '/components/product/product-style.css' );
    wp_enqueue_script( 'product-script', get_stylesheet_directory_uri() . '/components/product/product-script.js', array('jquery'), null, true );

    // Стили для Newsletter
    wp_enqueue_style( 'newsletter-style', get_stylesheet_directory_uri() . '/components/newsletter/newsletter-style.css' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_custom_styles_scripts' );

