<?php
// Подключаем шрифты Google (если необходимо)
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<!--
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
-->

<div class="wrap">
    <?php
    $sale = 30; // Пример значения скидки

    // Используем WooCommerce для получения списка продуктов
    $args = array(
        'limit' => 1, // Получить только один продукт
        'status' => 'publish', // Только опубликованные продукты
    );
    $products = wc_get_products($args);

    if (!empty($products)) :
        $product = $products[0]; // Получаем первый продукт из списка

        $product_id = $product->get_id();
        $product_name = $product->get_name();
        $product_price = $product->get_price();
        $product_permalink = get_permalink($product_id);
        $product_image = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'single-post-thumbnail');

        // Получаем первую картинку из галереи
        $attachment_ids = $product->get_gallery_image_ids();
        $image_link = '';
        if (!empty($attachment_ids)) {
            $image_link = wp_get_attachment_url($attachment_ids[0]);
        }

        // Получаем атрибуты цвета
        $product_colors = $product->get_attribute('pa_color'); // Предполагаем, что атрибут называется 'pa_color'
        $product_colors_array = explode(',', $product_colors);
        ?>

        <div class="product-component">
            <h2>Top product deals</h2>
            <div class="product">
                <?php if ($sale != 0): ?>
                    <label class="sale">
                        <em>-<?= esc_html($sale); ?>%</em>
                    </label>
                <?php endif; ?>
                <div class="imgContainer">
                    <img src="<?php echo esc_url($product_image[0]); ?>" alt="<?php echo esc_attr($product_name); ?>" class="product-image" data-hover-image="<?php echo esc_url($image_link); ?>">
                    <div class="add-to-cart">
                        <a target="_blank" href="<?php echo esc_url('?add-to-cart=' . $product_id); ?>">Add to cart</a>
                    </div>
                </div>

                <div class="product-info">
                    <div class="price-container">
                        <div class="price">
                            <p class="regular-price"><span><?php echo get_woocommerce_currency_symbol(); ?></span><?php echo esc_html($product->get_regular_price()); ?></p>
                            <p class="sale-price"><span><?php echo get_woocommerce_currency_symbol(); ?></span><?php echo esc_html($product->get_sale_price()); ?></p>
                        </div>
                        <div class="heart">
                            <div class="heartBlock"></div>
                        </div>
                    </div>

                    <div class="product-description">
                        <?php echo wp_kses_post($product->get_description()); ?>
                    </div>

                    <!-- Варианты цвета -->
                    <div class="product-colors">
                        <?php foreach ($product_colors_array as $color) : ?>
                            <span class="product-color" style="background-color: <?php echo esc_attr(trim($color)); ?>;"></span>
                        <?php endforeach; ?>
                    </div>

                    <div class="color-container">
                        <ul class="ul-colors">
                            <li class="blue active"></li>
                            <li class="red"></li>
                        </ul>
                    </div>
                </div>

                <!-- Кнопка "Добавить в список желаемого" -->
                <?php if (function_exists('yith_wcwl_add_to_wishlist')) : ?>
                    <div class="wishlist-button">
                        <?php yith_wcwl_add_to_wishlist(array('product_id' => $product_id)); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php else : ?>
        <p>No products found.</p>
    <?php endif; ?>

    <div class="category-link">
        <h2>Limited-time deals</h2>
        <a href=''>
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/deal-by-category.png" alt="Deal by Category">
        </a>
    </div>
</div>
