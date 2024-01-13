<?php
function ypf_pricing_table_shortcode( $atts ) {
    // Extract shortcode attributes
    $atts = shortcode_atts( array(
        'productid' => '',
        'ypf-step-table' => '',
        'ypf-table-format' => 'tab', // 'tab' or 'single'
        'free_trial_btn' => '', // Default text for the Free Trial button
        'challenge_begins_btn' => '', // Default text for the Challenge Begins button
    ), $atts );

    $selected_product_id = $atts['productid'];
    $table_format = $atts['ypf-table-format'];
    $tooltips_post = get_option('ypf_select_post_tooltips');
    $tooltips_post_id = isset($tooltips_post) ? $tooltips_post : '1397';

    if ( ! empty( $selected_product_id ) ) {
        $product = wc_get_product( $selected_product_id );

        if ( $product ) {
            ob_start(); // Start output buffering
                echo '<div class="ypf-pricing-table-container ypf-tab-panel">';
                // Display the product information here
                // Split the 'ypf-table' attribute into an array and get the first item
                $tables = array_map('trim', explode(',', $atts['ypf-step-table']));
                $first_table = $tables[0] ?? ''; // Fallback to an empty string if no tables are set
                ?>
                <div class="pricing__table product-<?php echo $selected_product_id; ?>">
                <?php
                // Display titles from the first ypf-table
                if (!empty($first_table)) {
                    echo '<div class="pt__title">';
                    display_acf_group_labels_and_tooltips($first_table, 'fx_challenge_tooltips', $selected_product_id, $tooltips_post_id);
                    echo '</div>';
                }
                ?>

                <div class="pt__option">

                <?php display_swiper_navigation_buttons('navBtnLeft', 'navBtnRight'); ?>

                <?php
                // Determine the swiper container ID based on the 'ypf-table-format' attribute
                $swiper_container_id = $table_format === 'single' ? 'pricingTableSliderSingle' : 'pricingTableSlider';
                ?>

                <div class="pt__option__slider swiper" id="<?php echo esc_attr($swiper_container_id); ?>">
                    <div class="swiper-wrapper">

                        <?php 

                            // Split the 'ypf-table' attribute into an array
                            $step_tables = explode(',', $atts['ypf-step-table']);
                            foreach ($step_tables as $step_table) {
                                $step_table = trim($step_table); // Remove any whitespace
                                if (!empty($step_table)) {
                                    ?>
                                    <div class="swiper-slide pt__option__item">
                                        <div class="pt__item">
                                            <div class="pt__item__wrap">
                                                <?php
                                                    display_acf_group_fields($step_table, $selected_product_id, $step_table);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                            }
                        ?>

                    </div>
                </div>

                </div>
                </div>

                <?php
            echo '</div>'; // Close ypf-tab-panel

            // Fetch URLs from ACF fields
            $free_trial_url = get_field('free_trial_url', $selected_product_id);
            $challenge_begins_url = get_field('challenge_begins_url', $selected_product_id);

            ?>

            <div class="ypf-btn-wrap">
                <?php if (!empty($atts['free_trial_btn'])): ?>
                <a href="<?php echo esc_url($free_trial_url); ?>" class="btn ypf-button free-trial"><?php echo esc_html($atts['free_trial_btn']); ?> <i class="fa-solid fa-square-arrow-up-right"></i></a>
                <?php endif; ?>
                <?php if (!empty($atts['challenge_begins_btn'])): ?>
                <a href="<?php echo esc_url($challenge_begins_url); ?>" class="btn ypf-button challenge-begins"><?php echo esc_html($atts['challenge_begins_btn']); ?> <i class="fa-solid fa-square-arrow-up-right"></i></a>
                <?php endif; ?>
            </div>


            <?php
            return ob_get_clean(); // Return the buffered output
        } else {
            return '<p>Product not found.</p>';
        }
    } else {
        return '<p>Please specify a product ID.</p>';
    }
}
add_shortcode( 'ypf-pricing-table', 'ypf_pricing_table_shortcode' );

function display_acf_group_labels_and_tooltips($group_field_name, $tooltips_field_name, $product_id, $tooltips_post_id) {
    // Fetch group field values and object for the product
    $group_field_values = get_field($group_field_name, $product_id);
    $group_field_object = get_field_object($group_field_name, $product_id);

    // Fetch tooltips field values from the global tooltips post
    $tooltips_field_values = get_field($tooltips_field_name, $tooltips_post_id);

    if ($group_field_object) {
        echo '<div class="pt__title__wrap">';

        foreach ($group_field_object['sub_fields'] as $index => $sub_field) {
            $sub_field_label = $sub_field['label'];
            $sub_field_name = $sub_field['name'];
            $sub_field_tooltips_name = 'tooltips_' . $sub_field['name'];
            $sub_field_tooltip = isset($tooltips_field_values[$sub_field_tooltips_name]) ? $tooltips_field_values[$sub_field_tooltips_name] : '';

            $sub_field_tooltip_text = '';
            if (get_option('ypf_enable_tooltips')) {
                if (!empty($sub_field_tooltip)) { 
                    $sub_field_tooltip_text = '<span class="data-template" data-template="'. esc_html($sub_field_tooltips_name) . '"><i aria-hidden="true" class="fas fa-info-circle"></i></span>';
                }
            }
            echo '<div class="pt__row heading-vertical ' . esc_html($sub_field_name) . '"><div class="pt__row-heading-text">' . esc_html($sub_field_label) . $sub_field_tooltip_text . '</div></div>'; 
        }

        echo '<div style="display: none;">';
        if (get_option('ypf_enable_tooltips')) {
            foreach ($group_field_object['sub_fields'] as $index => $sub_field) {
                $sub_field_tooltips_name = 'tooltips_' . $sub_field['name'];
                $sub_field_tooltip = isset($tooltips_field_values[$sub_field_tooltips_name]) ? $tooltips_field_values[$sub_field_tooltips_name] : '';
                echo '<div id="'. esc_html($sub_field_tooltips_name) . '">' . esc_html($sub_field_tooltip) . '</div>';                   
            }
          }
          echo '</div>';

        echo '</div>'; // Close pt__title__wrap
    }
}


function display_acf_group_fields($group_field_name, $product_id, $css_class_prefix) {
    // Fetch the ACF group field for the current product
    $group_field_values = get_field($group_field_name, $product_id);
            
    // Get the field object for the group
    $group_field_object = get_field_object($group_field_name, $product_id);
            
    if ($group_field_values && $group_field_object) {
        foreach ($group_field_object['sub_fields'] as $sub_field) {
            // The label is in the field object
            $sub_field_label = $sub_field['label'];
            $sub_field_name = $sub_field['name'];
            // The value is in the values array
            $sub_field_value = !empty($group_field_values[$sub_field['name']]) ? $group_field_values[$sub_field['name']] : '-';
            echo '<div class="pt__row ' . esc_attr($css_class_prefix) . ' val val-' . esc_attr($sub_field_name) . '">' . $sub_field_value . '</div>';
        }
    }
}

function display_swiper_navigation_buttons($left_button_id, $right_button_id) {
    ?>
    <div class="pt__option__mobile__nav">
        <a id="<?php echo esc_attr($left_button_id); ?>" href="#" class="mobile__nav__btn">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.1538 11.9819H1.81972" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M11.9863 22.1535L1.82043 11.9865L11.9863 1.81946" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </a>
        <a id="<?php echo esc_attr($right_button_id); ?>" href="#" class="mobile__nav__btn">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1.81934 11.9819H22.1534" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M11.9863 22.1535L22.1522 11.9865L11.9863 1.81946" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </a>
    </div>
    <?php
}
