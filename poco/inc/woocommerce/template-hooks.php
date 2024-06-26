<?php
/**
 * =================================================
 * Hook poco_page
 * =================================================
 */

/**
 * =================================================
 * Hook poco_single_post_top
 * =================================================
 */

/**
 * =================================================
 * Hook poco_single_post
 * =================================================
 */

/**
 * =================================================
 * Hook poco_single_post_bottom
 * =================================================
 */

/**
 * =================================================
 * Hook poco_loop_post
 * =================================================
 */

/**
 * =================================================
 * Hook poco_footer
 * =================================================
 */
add_action('poco_footer', 'poco_handheld_footer_bar', 25);

/**
 * =================================================
 * Hook poco_after_footer
 * =================================================
 */
add_action('poco_after_footer', 'poco_sticky_single_add_to_cart', 999);

/**
 * =================================================
 * Hook wp_footer
 * =================================================
 */

/**
 * =================================================
 * Hook wp_head
 * =================================================
 */

/**
 * =================================================
 * Hook poco_before_header
 * =================================================
 */

/**
 * =================================================
 * Hook poco_before_content
 * =================================================
 */

/**
 * =================================================
 * Hook poco_content_top
 * =================================================
 */
add_action('poco_content_top', 'poco_shop_messages', 10);

/**
 * =================================================
 * Hook poco_post_header_before
 * =================================================
 */

/**
 * =================================================
 * Hook poco_post_content_before
 * =================================================
 */

/**
 * =================================================
 * Hook poco_post_content_after
 * =================================================
 */

/**
 * =================================================
 * Hook poco_sidebar
 * =================================================
 */

/**
 * =================================================
 * Hook poco_loop_after
 * =================================================
 */

/**
 * =================================================
 * Hook poco_page_after
 * =================================================
 */

/**
 * =================================================
 * Hook poco_woocommerce_before_shop_loop_item
 * =================================================
 */

/**
 * =================================================
 * Hook poco_woocommerce_before_shop_loop_item_title
 * =================================================
 */
add_action('poco_woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
add_action('poco_woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
add_action('poco_woocommerce_before_shop_loop_item_title', 'poco_woocommerce_product_loop_wishlist_button', 10);

/**
 * =================================================
 * Hook poco_woocommerce_shop_loop_item_title
 * =================================================
 */
add_action('poco_woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);

/**
 * =================================================
 * Hook poco_woocommerce_after_shop_loop_item_title
 * =================================================
 */
add_action('poco_woocommerce_after_shop_loop_item_title', 'poco_woocommerce_get_product_description', 15);
add_action('poco_woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 20);
add_action('poco_woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 25);

/**
 * =================================================
 * Hook poco_woocommerce_after_shop_loop_item
 * =================================================
 */
