<?php
class Elementor_YpfPlugins_Widget_Pricing_Table_Single_Product extends \Elementor\Widget_Base {

	public function get_name() {
		return 'ypfplugins_pricing_table_single_product';
	}

	public function get_title() {
		return esc_html__( 'YPF Product Single Step (Development)', 'ypf-plugins' );
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
				'label' => esc_html__( 'Pricing Table Settings', 'ypf-plugins' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
		    'pricing_table_card',
		    [
		        'label' => __('Table Type', 'ypf-plugins'),
		        'type' => \Elementor\Controls_Manager::SELECT,
		        'options' => [
		            'tab_content' => __('Tab Content', 'ypf-plugins'),
		            'single' => __('Single Use', 'ypf-plugins'),
		        ],
		        'default' => 'tab_content',
		    ]
		);

		$this->add_control(
		    'pricing_table_format_style',
		    [
		        'label' => __('Format Style', 'ypf-plugins'),
		        'type' => \Elementor\Controls_Manager::SELECT,
		        'options' => [
		            'style1' => __('Style 1', 'ypf-plugins'),
		        ],
		        'default' => 'style1',
		    ]
		);	  

        $repeater = new \Elementor\Repeater();

        // Step Name Field
	    $repeater->add_control(
	        'step_name', [
	            'label' => __('Header Name', 'ypf-plugins'),
	            'type' => \Elementor\Controls_Manager::TEXT,
	        ]
	    );

	    // Add a select control for products
       $repeater->add_control(
            'selected_product',
            [
                'label' => __('Select Product', 'ypf-plugins'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_woocommerce_products(),
                'default' => 'Select Product',
            ]
        );

	    $repeater->add_control(
	        'acf_group_field', [
	            'label' => __('Select ACF Group Field', 'ypf-plugins'),
	            'type' => \Elementor\Controls_Manager::SELECT,
	            'options' => $this->get_acf_group_field_options(), // You need to define this method
	        ]
	    );

	    $repeater->add_control(
			'tooltips_switch',
			[
				'label' => esc_html__( 'Show Tooltips Label', 'ypf-plugins' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'ypf-plugins' ),
				'label_off' => esc_html__( 'Hide', 'ypf-plugins' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

        // Add a select control for products
        $repeater->add_control(
            'selected_tooltips',
            [
                'label' => __('Select Tooltips', 'ypf-plugins'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_post_tooltips(),
                'default' => 'Select Tooltips',
                'condition' => [
	                'tooltips_switch' => 'yes',
	            ],
            ]
        );

	    $repeater->add_control(
			'step_button_switch',
			[
				'label' => esc_html__( 'Show Button', 'ypf-plugins' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'ypf-plugins' ),
				'label_off' => esc_html__( 'Hide', 'ypf-plugins' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$repeater->add_control(
			'step_button_title',
			[
				'label' => esc_html__( 'Button Text', 'ypf-plugins' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Button Text', 'ypf-plugins' ),
				'condition' => [
	                'step_button_switch' => 'yes',
	            ],
			]
		);

		$repeater->add_control(
			'step_button_link',
			[
				'label' => esc_html__( 'Button Link', 'ypf-plugins' ),
				'type' => \Elementor\Controls_Manager::URL,
				'options' => [ 'url', 'is_external', 'nofollow' ],
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => false,
					'custom_attributes' => '',
				],
				'label_block' => true,
				'condition' => [
	                'step_button_switch' => 'yes',
	            ],
			]
		);

	    $this->add_control(
	        'slide_items',
	        [
	            'label' => __('Slide Items', 'ypf-plugins'),
	            'type' => \Elementor\Controls_Manager::REPEATER,
	            'fields' => $repeater->get_controls(),
	            'title_field' => '{{{ step_name }}}',
	        ]
	    );

		$this->end_controls_section();


		$this->start_controls_section(
	        'side_title_style_section', // Unique name for the section
	        [
	            'label' => __('General Side Title', 'ypf-plugins'), // Section label
	            'tab' => \Elementor\Controls_Manager::TAB_STYLE, // The section tab
	        ]
	    );

	    // Side Title Background Control
	    $this->add_control(
	        'side_title_background', // Unique name for the control
	        [
	            'label' => __('Side Title Background', 'ypf-plugins'),
	            'type' => \Elementor\Controls_Manager::COLOR,
	            'selectors' => [
	                '{{WRAPPER}} .ypf_side_title_bg_elementor' => 'background-color: {{VALUE}}!important;', 
	            ],
	        ]
	    );

	    // Side Title  Text Color Control
	    $this->add_control(
	        'side_title_text_color', // Unique name for the control
	        [
	            'label' => __('Side Title Text Color', 'ypf-plugins'),
	            'type' => \Elementor\Controls_Manager::COLOR,
	            'selectors' => [
	                '{{WRAPPER}} .ypf_side_title_text_elementor' => 'color: {{VALUE}}!important;',
	            ],
	        ]
	    );

	    // Side Title Typography Control
	    $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'side_title_typography',
				'selector' => '{{WRAPPER}} .ypf_side_title_bg_elementor',
			]
		);

	    $this->end_controls_section();


	    $this->start_controls_section(
	        'step_title_style_section', // Unique name for the section
	        [
	            'label' => __('General Step Title', 'ypf-plugins'), // Section label
	            'tab' => \Elementor\Controls_Manager::TAB_STYLE, // The section tab
	        ]
	    );

	    // Step Title Background Control
	    $this->add_control(
	        'step_title_background', // Unique name for the control
	        [
	            'label' => __('Step Title Background', 'ypf-plugins'),
	            'type' => \Elementor\Controls_Manager::COLOR,
	            'selectors' => [
	                '{{WRAPPER}} .ypf_step_title_bg_elementor' => 'background-color: {{VALUE}}!important;', 
	            ],
	        ]
	    );

	    // Step Title  Text Color Control
	    $this->add_control(
	        'step_title_text_color', // Unique name for the control
	        [
	            'label' => __('Step Title Text Color', 'ypf-plugins'),
	            'type' => \Elementor\Controls_Manager::COLOR,
	            'selectors' => [
	                '{{WRAPPER}} .ypf_step_title_text_elementor' => 'color: {{VALUE}}!important;',
	            ],
	        ]
	    );

	    // Step Title Typography Control
	    $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'step_title_typography',
				'selector' => '{{WRAPPER}} .ypf_step_title_bg_elementor',
			]
		);

	    $this->end_controls_section();

	    $this->start_controls_section(
	        'content_style_section', // Unique name for the section
	        [
	            'label' => __('General Content', 'ypf-plugins'), // Section label
	            'tab' => \Elementor\Controls_Manager::TAB_STYLE, // The section tab
	        ]
	    );


	    // Content Background Control
	    $this->add_control(
	        'table_content_background', 
	        [
	            'label' => __('Table Content Background', 'ypf-plugins'),
	            'type' => \Elementor\Controls_Manager::COLOR,
	            'selectors' => [
	                '{{WRAPPER}} .ypf_table_content_bg_elementor' => 'background-color: {{VALUE}}!important;', 
	            ],
	        ]
	    );

	    // Content Text Color Control
	    $this->add_control(
	        'table_content_text_color', // Unique name for the control
	        [
	            'label' => __('Content Text Color', 'ypf-plugins'),
	            'type' => \Elementor\Controls_Manager::COLOR,
	            'selectors' => [
	                '{{WRAPPER}} .ypf_table_content_text_elementor' => 'color: {{VALUE}}!important;',
	            ],
	        ]
	    );

	    // Content Title Typography Control
	    $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'content_text_typography',
				'selector' => '{{WRAPPER}} .ypf_table_content_bg_elementor',
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
	        'table_additional_section', // Unique name for the section
	        [
	            'label' => __('Additional Settings', 'ypf-plugins'), // Section label
	            'tab' => \Elementor\Controls_Manager::TAB_STYLE, // The section tab
	        ]
	    );


	    // General Border Color Control
	    $this->add_control(
	        'general_border_color', // Unique name for the control
	        [
	            'label' => __('General Border Color', 'ypf-plugins'),
	            'type' => \Elementor\Controls_Manager::COLOR,
	            'selectors' => [
	                '{{WRAPPER}} .pt__table_general_border' => 'border-color: {{VALUE}}!important;', 
	            ],
	        ]
	    );

	    // Mobile Button Backgound Control
	    $this->add_control(
	        'mobile_button_background_color', // Unique name for the control
	        [
	            'label' => __('Mobile Button Backgound', 'ypf-plugins'),
	            'type' => \Elementor\Controls_Manager::COLOR,
	            'selectors' => [
	                '{{WRAPPER}} .pt__mobile_button_background__elementor' => 'background-color: {{VALUE}}!important;', 
	            ],
	        ]
	    );

	    // Mobile Button Arrow Color Control
	    $this->add_control(
	        'mobile_button_arrow_color', // Unique name for the control
	        [
	            'label' => __('Mobile Button Arrow Color', 'ypf-plugins'),
	            'type' => \Elementor\Controls_Manager::COLOR,
	            'selectors' => [
	                '{{WRAPPER}} .pt__mobile_button_arrow__elementor svg' => 'color: {{VALUE}}!important;',
	            ],
	        ]
	    );

	    // Mobile Button Arrow Color Control
	    $this->add_control(
	        'disable_mobile_button_background_color', // Unique name for the control
	        [
	            'label' => __('Disable Mobile Mobile Button', 'ypf-plugins'),
	            'type' => \Elementor\Controls_Manager::COLOR,
	            'selectors' => [
	                '{{WRAPPER}} .pt__mobile_button_background__elementor.swiper-button-disabled' => 'background-color: {{VALUE}}!important;', 
	            ],
	        ]
	    );
	    

	    $this->end_controls_section();

	    $this->start_controls_section(
	        'border_radius_section', // Unique name for the section
	        [
	            'label' => __('General Border Radius', 'ypf-plugins'), // Section label
	            'tab' => \Elementor\Controls_Manager::TAB_STYLE, // The section tab
	        ]
	    );

	    $this->add_control(
			'general_border_radius_left',
			[
				'label' => esc_html__( 'Border Radius Left', 'ypf-plugins' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .pricing__table .pt__title .pt__title__wrap' => 'border-top-left-radius: {{SIZE}}{{UNIT}}!important;border-bottom-left-radius: {{SIZE}}{{UNIT}}!important;',
				],
			]
		);

    
		$this->add_control(
			'general_border_radius_right',
			[
				'label' => esc_html__( 'Border Radius Right', 'ypf-plugins' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .pricing__table .pt__option .pt__option__item:last-child' => 'border-top-right-radius: {{SIZE}}{{UNIT}}!important;border-bottom-right-radius: {{SIZE}}{{UNIT}}!important;',
				],
			]
		);

		$this->end_controls_section();


	}

	protected function render() {		
	// Check if Elementor editor is active
    // Get the selected product ID from the widget settings
	$settings = $this->get_settings_for_display();
    // Check the value of 'pricing_table_card' control
    $swiperID = $settings['pricing_table_card'] === 'tab_content' ? 'pricingTableSlider' : 'pricingTableSliderSingle';

	// Check if a product ID is selected   
	if (!empty($settings['slide_items'])) {
    	// Fetch the product object
        echo '<div class="ypf-pricing-table-container ypf-tab-panel">';
            ?>
            <div class="pricing__table product-<?php echo $swiperID; ?>">

            <?php 
        	// Get the first repeater field for the title
    		$first_item = $settings['slide_items'][0] ?? null;
    		$tooltips_switch = $first_item['tooltips_switch'];
			$tooltips_post_elementor = $first_item['selected_tooltips'];
    		$tooltips_post_id_elementor = $tooltips_post_elementor ? $tooltips_post_elementor : '0';
    		$step_button_switch_first = $first_item['step_button_switch'];
    		if ($first_item && !empty($first_item['acf_group_field'])) { ?>
			  	<div class="pt__title tooltips-id-<?php echo $tooltips_post_id_elementor;  ?>">
			  		<div class="pt__title__wrap">
	                <?php display_acf_group_labels_and_tooltips_el($first_item['acf_group_field'], 'fx_challenge_tooltips', $first_item['selected_product'], $tooltips_switch , $tooltips_post_id_elementor); 
	                if ($step_button_switch_first == 'yes') {
		            	echo '<div class="pt__row heading-vertical pt__table_general_border"><div class="pt__row-heading-text"></div></div>'; 
		        	}
	                ?>
	           		</div>
	            </div>
	        <?php 
	         }
	        ?>

		  	<div class="pt__option">

		    <?php display_swiper_navigation_buttons_el('navBtnLeft', 'navBtnRight'); ?>

		    <?php
		    	if (!empty($settings['slide_items'])) {
		    		echo '<div class="pt__option__slider swiper" id="' . esc_attr($swiperID) . '">
			                 <div class="swiper-wrapper">';
			        foreach ($settings['slide_items'] as $item) {
			        	$step_button_switch = $item['step_button_switch'];
			        	$tooltips_switch_items = $item['tooltips_switch'];
						$tooltips_post_elementor_items = $item['selected_tooltips'];
			    		$tooltips_post_id_elementor_items = $tooltips_post_elementor_items ? $tooltips_post_elementor_items : '0';
			            echo '<div class="swiper-slide pt__option__item">
			                      <div class="pt__item">
			                          <div class="pt__item__wrap">';
							            display_acf_group_fields_single_el($item['acf_group_field'], $item['selected_product'], $item['acf_group_field'],'fx_challenge_tooltips', $tooltips_switch_items, $tooltips_post_id_elementor_items);
							            if ($step_button_switch == 'yes') {
							            echo '<div class="pt__row val val-btn-cta-checkout pt__table_general_border"><a href="'. $item['step_button_link']['url'] .'" class="btn btn-product product-'. $item['selected_product'] .' pt__table_btn_checkout">'. $item['step_button_title'] .'</a></div>';
							        	}			            
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

	private function get_post_tooltips() {
        $tooltip_posts = get_posts([
            'post_type' => 'tooltips-table',
            'posts_per_page' => -1, // Retrieve all posts
            'post_status' => 'publish', // Make sure to get only published posts
        ]);

        foreach ($tooltip_posts as $post) {
            $options[$post->ID] = $post->post_title;
        }

        return $options;
    }

}