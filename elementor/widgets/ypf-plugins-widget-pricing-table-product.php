<?php
class Elementor_YpfPlugins_Widget_Pricing_Table_Per_Product extends \Elementor\Widget_Base {

	public function get_name() {
		return 'ypfplugins_pricing_table_product';
	}

	public function get_title() {
		return esc_html__( 'YPF Plugins Product Table', 'ypf-plugins' );
	}

	public function get_icon() {
		return 'eicon-table';
	}

	public function get_categories() {
		return [ 'ab-ypfplugins-category' ];
	}

	public function get_keywords() {
		return [ 'ypf plugins', 'ypf','pricing', 'table' ];
	}

	public function get_style_depends() {
        return ['ypf-plugins-css'];
    }

    public function get_script_depends() {
        return ['ypf-plugins-js'];
    }

	protected function register_controls() {

		// Content Tab Start

		$this->start_controls_section(
			'section_pricing_table',
			[
				'label' => esc_html__( 'Pricing Table Section', 'ypf-plugins' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

        // Add a select control for products
        $this->add_control(
            'selected_product',
            [
                'label' => __('Select Product', 'text-domain'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_woocommerce_products(),
                'default' => 'Select Product',
            ]
        );

        $repeater = new \Elementor\Repeater();

        // Step Name Field
	    $repeater->add_control(
	        'step_name', [
	            'label' => __('Step Name', 'plugin-name'),
	            'type' => \Elementor\Controls_Manager::TEXT,
	        ]
	    );

	    $repeater->add_control(
	        'acf_group_field', [
	            'label' => __('Select ACF Group Field', 'plugin-name'),
	            'type' => \Elementor\Controls_Manager::SELECT,
	            'options' => $this->get_acf_group_field_options(), // You need to define this method
	        ]
	    );

	    $this->add_control(
	        'slide_items',
	        [
	            'label' => __('Slide Items', 'plugin-name'),
	            'type' => \Elementor\Controls_Manager::REPEATER,
	            'fields' => $repeater->get_controls(),
	            'title_field' => '{{{ step_name }}}',
	        ]
	    );

		$this->end_controls_section();


	}

	protected function render() {		
	// Check if Elementor editor is active
    if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
        echo '<p>Elementor editor is active. Product details will be displayed on the frontend.</p>';
        // Get the selected product ID from the widget settings
	$settings = $this->get_settings_for_display();
	$selected_product_id = $settings['selected_product'];
	$tooltips_post = get_option('ypf_select_post_tooltips');
    $tooltips_post_id = isset($tooltips_post) ? $tooltips_post : '1397';

	// Check if a product ID is selected   
	if (!empty($selected_product_id) && !empty($settings['slide_items'])) {
    	// Fetch the product object
        $product = wc_get_product($selected_product_id);

        echo '<div class="ypf-pricing-table-container ypf-tab-panel">';
            ?>
            <div class="pricing__table product-<?php echo $selected_product_id; ?>">

            <?php 
        	// Get the first repeater field for the title
    		$first_item = $settings['slide_items'][0] ?? null;
    		if ($first_item && !empty($first_item['acf_group_field'])) { ?>
			  	<div class="pt__title">
	                <?php display_acf_group_labels_and_tooltips($first_item['acf_group_field'], 'fx_challenge_tooltips', $selected_product_id, $tooltips_post_id); ?>
	            </div>
	        <?php 
	         }
	        ?>

		  	<div class="pt__option">

		    <?php display_swiper_navigation_buttons('navBtnLeft', 'navBtnRight'); ?>

		    <?php
		    	if (!empty($settings['slide_items'])) {
			        echo '<div class="pt__option__slider swiper" id="pricingTableSlider">
			                 <div class="swiper-wrapper">';
			        foreach ($settings['slide_items'] as $item) {
			            echo '<div class="swiper-slide pt__option__item">
			                      <div class="pt__item">
			                          <div class="pt__item__wrap">';
			            
			            // Assuming $product_id is available in scope
			            $this->display_acf_group_fields($item['acf_group_field'], $selected_product_id, $item['acf_group_field']);
			            
			            echo '        </div>
			                      </div>
			                  </div>';
			        }

			        echo '    </div>
			              </div>';
			    }
		    ?>

			</div>
			</div>

            <?php
      	echo '</div>'; // Close ypf-tab-panel
		} else {
        echo '<p>Please select a product.</p>';
    	}
    }else {
	// Get the selected product ID from the widget settings
	$settings = $this->get_settings_for_display();
	$selected_product_id = $settings['selected_product'];
	$tooltips_post = get_option('ypf_select_post_tooltips');
    $tooltips_post_id = isset($tooltips_post) ? $tooltips_post : '1397';

	// Check if a product ID is selected   
	if (!empty($selected_product_id) && !empty($settings['slide_items'])) {
    	// Fetch the product object
        $product = wc_get_product($selected_product_id);

        echo '<div class="ypf-pricing-table-container ypf-tab-panel">';
            ?>
            <div class="pricing__table product-<?php echo $selected_product_id; ?>">

            <?php 
        	// Get the first repeater field for the title
    		$first_item = $settings['slide_items'][0] ?? null;
    		if ($first_item && !empty($first_item['acf_group_field'])) { ?>
			  	<div class="pt__title">
	                <?php display_acf_group_labels_and_tooltips($first_item['acf_group_field'], 'fx_challenge_tooltips', $selected_product_id, $tooltips_post_id); ?>
	            </div>
	        <?php 
	         }
	        ?>

		  	<div class="pt__option">

		    <?php display_swiper_navigation_buttons('navBtnLeft', 'navBtnRight'); ?>

		    <?php
		    	if (!empty($settings['slide_items'])) {
			        echo '<div class="pt__option__slider swiper" id="pricingTableSlider">
			                 <div class="swiper-wrapper">';
			        foreach ($settings['slide_items'] as $item) {
			            echo '<div class="swiper-slide pt__option__item">
			                      <div class="pt__item">
			                          <div class="pt__item__wrap">';
			            
			            // Assuming $product_id is available in scope
			            $this->display_acf_group_fields($item['acf_group_field'], $selected_product_id, $item['acf_group_field']);
			            
			            echo '        </div>
			                      </div>
			                  </div>';
			        }

			        echo '    </div>
			              </div>';
			    }
		    ?>

			</div>
			</div>

            <?php
      	echo '</div>'; // Close ypf-tab-panel
		} else {
        echo '<p>Please select a product.</p>';
    	}
	}
	}

    // Helper function to get WooCommerce products
    private function get_woocommerce_products() {
        $products = wc_get_products(array(
            'status' => 'publish',
            'limit' => -1,
        ));

        $product_options = [];
        foreach ($products as $product) {
            $product_options[$product->get_id()] = $product->get_name();
        }

        return $product_options;
    }

    private function display_acf_group_labels_and_tooltips($group_field_name, $tooltips_field_name, $product_id, $tooltips_post_id) {
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

	private function display_acf_group_fields($group_field_name, $product_id, $css_class_prefix) {
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

	private function display_swiper_navigation_buttons($left_button_id, $right_button_id) {
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

	private function get_acf_group_field_options() {
	    $group_fields = array();

	    // Check if ACF function exists
	    if (function_exists('acf_get_field_groups')) {
	        // Get all ACF field groups
	        $field_groups = acf_get_field_groups();

	        foreach ($field_groups as $group) {
	            // Get fields for each group
	            $fields = acf_get_fields($group['key']);

	            foreach ($fields as $field) {
	                // Check if the field type is a group
	                if ($field['type'] == 'group') {
	                    // Use field key or name as needed
	                    $group_fields[$field['key']] = $field['label'];
	                }
	            }
	        }
	    }

	    return $group_fields;
	}



}