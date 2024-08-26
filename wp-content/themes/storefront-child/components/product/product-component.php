<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<!---->
<!--<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">-->

<div class="wrap">

<?php
$sale = 30;
// Получаем данные продукта по ID
$product_id = 57; // ID тестового продукта
$product = wc_get_product( $product_id );


    if ( $product ) :
        $product_name = $product->get_name();
        $product_price = $product->get_price();
        $product_permalink = get_permalink( $product_id );
        $product_image = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'single-post-thumbnail' );
        $attachment_ids = $product->get_gallery_image_ids();
        foreach( $attachment_ids as $attachment_id ) {
            $image_link = wp_get_attachment_url( $attachment_id );
        }

        //var_dump('$image_link',$image_link);
        //$product_colors = $product->get_attribute( 'color' ); // Предполагаем, что атрибут цвета называется 'pa_color'
        //$product_colors_array = explode( ',', $product_colors );
    ?>

        <div class="product-component">
            <h2>Top product deals</h2>
            <div class="product">
                <?php if ($sale != 0): ?>
                    <label class="sale">
                        <em>-<?= $sale;?>%</em>
                    </label>
                <?php endif;?>
                <div class="imgContainer">
                    <img src="<?php echo esc_url($product_image[0]); ?>" alt="<?php echo esc_attr($product_name); ?>" class="product-image" data-hover-image="<?php echo esc_url($image_link); ?>">
                    <div class="add-to-cart">
                        <a target="_blank" href="?add-to-cart=<?php echo $product_id; ?>">Add to cart</a>
                    </div>
                </div>



                <div class="product-info">

                    <div class="price-container">
                        <div class="price">
                            <p class="regular-price"><span><?php echo get_woocommerce_currency_symbol(); ?></span><?php echo $product->regular_price; ?> </p>
                            <p class="sale-price"><span><?php echo get_woocommerce_currency_symbol(); ?></span><?php echo $product->sale_price; ?></p>
                        </div>
                        <div class="heart">
                            <div class="heartBlock"></div>
                        </div>

                    </div>

                    <div class="product-description">
                        <?php echo $product->description;?>
                    </div>

                    <div class="color-container">
                        <ul class="ul-colors">
                            <li class="blue active"></li>
                            <li class="red"></li>
                        </ul>
                    </div>

                    <!-- Варианты цвета -->
                    <div class="product-colors-v2" style="display: none">

                        <?php
                        var_dump('product_colors_array',$product_colors_array);
                        foreach ( $product_colors_array as $color ) : ?>

                            <span class="product-color" style="background-color: <?php echo esc_attr( $color ); ?>;"></span>
                        <?php endforeach; ?>
                    </div>

                </div>
                <!-- Кнопка "Добавить в корзину" -->
               

                <!-- Кнопка "Добавить в список желаемого" -->
                <?php if ( function_exists( 'yith_wcwl_add_to_wishlist' ) ) : ?>
                    <div class="wishlist-button">
                        <?php yith_wcwl_add_to_wishlist( array( 'product_id' => $product_id ) ); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    <?php endif; ?>
    <div class="category-link">
        <h2>Limited-time deals</h2>

        <a href=''>
            <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/deal-by-category.png">
        </a>
    </div>
</div>

<?php //var_dump('product', $product); ?>