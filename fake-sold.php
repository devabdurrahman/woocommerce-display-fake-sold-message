<?php
function spec_fake_sold_message($product_id) {
    $cache_key = 'spec_in_cart' . $product_id;
    $cached_data = get_transient($cache_key);

    if ($cached_data === false) {
        $sold_count = rand(0, 7);
		
        // 50% chance to show days + hours, otherwise just hours
        if (rand(0, 1) === 1) {
            $days = rand(1, 3); // Random days (1 to 3)
            $hours = rand(1, 23); // Random hours (1 to 23)
            $message = "{$sold_count} sold in last {$days} days";
        } else {
            $hours = rand(6, 12); // Only hours (6 to 12)
            $message = "{$sold_count} sold in last {$hours} hours";
        }
		
        set_transient($cache_key, $message, 10 * HOUR_IN_SECONDS);
    } else {
        $message = $cached_data;
    }

    return $message;
}


function spec_fake_in_cart_display($product_id) {
    $cache_key = 'fake_in_cart_displayy'. $product_id;
    $cached_data = get_transient($cache_key);

    if ($cached_data === false) {
		$cart_count = rand(1, 5);
		if($cart_count === 1){
			$message = "Hurry: Hurry! {$cart_count} person have this in their cart";
		}else{
			$message = "Hurry: Hurry! Over {$cart_count} people have this in their cart";
		}        
        set_transient($cache_key, $message, 10 * HOUR_IN_SECONDS);
    } else {
        $message = $cached_data;
    }

    return $message;
}

// Display fake messages on WooCommerce archive pages only for specific categories
add_action('woocommerce_after_shop_loop_item_title', function() {
    global $product;

    if ($product) {
        // Define the target ids
        $target_ids = array( 1692, 1689, 1686); 
		
		if ( in_array( $product->get_id(), $target_ids ) ) {
			$sold_message = spec_fake_sold_message($product->get_id());
			$runs_out_message = spec_fake_in_cart_display($product->get_id());
			echo '<div class="fake-message-container">
                    <p class="fake-message message-one">&#128293; ' . esc_html($sold_message) . '</p>
                    <p class="fake-message message-two">&#128293; ' . esc_html($runs_out_message) . '</p>
                  </div>';
		}
    }
}, 15);
?>
