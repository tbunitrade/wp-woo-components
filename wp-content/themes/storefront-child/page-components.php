<?php
/*
Template Name: Custom Components
*/

get_header(); ?>

<div class="custom-components-page">
    <div class="product-section">
        <?php get_template_part( 'components/product/product-component' ); ?>
    </div>
    
    <div class="newsletter-section">
        <?php get_template_part( 'components/newsletter/newsletter-component' ); ?>
    </div>
</div>

<?php get_footer(); ?>
