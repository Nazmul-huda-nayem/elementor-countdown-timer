<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Custom_Countdown_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'custom-countdown';
    }

    public function get_title() {
        return esc_html__('Custom Countdown', 'custom-elementor-countdown');
    }

    public function get_icon() {
        return 'eicon-countdown';
    }

    public function get_categories() {
        return ['general'];
    }

    public function get_script_depends() {
        return ['custom-countdown-js'];
    }

    public function get_style_depends() {
        return ['custom-countdown-css'];
    }

    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'custom-elementor-countdown'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'target_date',
            [
                'label' => esc_html__('Target Date', 'custom-elementor-countdown'),
                'type' => \Elementor\Controls_Manager::DATE_TIME,
                'default' => date('Y-m-d H:i', strtotime('+1 month')),
            ]
        );

        $this->add_control(
            'language',
            [
                'label' => esc_html__('Language', 'custom-elementor-countdown'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'english',
                'options' => [
                    'english' => esc_html__('English', 'custom-elementor-countdown'),
                    'bangla' => esc_html__('Bangla', 'custom-elementor-countdown'),
                ],
            ]
        );

        $this->add_control(
            'expired_text',
            [
                'label' => esc_html__('Expired Text', 'custom-elementor-countdown'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Time Expired!', 'custom-elementor-countdown'),
            ]
        );

        // Custom Labels Section
        $this->add_control(
            'labels_heading',
            [
                'label' => esc_html__('Custom Labels', 'custom-elementor-countdown'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'label_days',
            [
                'label' => esc_html__('Days Label', 'custom-elementor-countdown'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => esc_html__('Days / দিন', 'custom-elementor-countdown'),
            ]
        );

        $this->add_control(
            'label_hours',
            [
                'label' => esc_html__('Hours Label', 'custom-elementor-countdown'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => esc_html__('Hours / ঘন্টা', 'custom-elementor-countdown'),
            ]
        );

        $this->add_control(
            'label_minutes',
            [
                'label' => esc_html__('Minutes Label', 'custom-elementor-countdown'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => esc_html__('Minutes / মিনিট', 'custom-elementor-countdown'),
            ]
        );

        $this->add_control(
            'label_seconds',
            [
                'label' => esc_html__('Seconds Label', 'custom-elementor-countdown'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => esc_html__('Seconds / সেকেন্ড', 'custom-elementor-countdown'),
            ]
        );

        $this->add_control(
            'show_labels',
            [
                'label' => esc_html__('Show Labels', 'custom-elementor-countdown'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'custom-elementor-countdown'),
                'label_off' => esc_html__('Hide', 'custom-elementor-countdown'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Layout Section
        $this->start_controls_section(
            'layout_section',
            [
                'label' => esc_html__('Layout', 'custom-elementor-countdown'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'layout_direction',
            [
                'label' => esc_html__('Direction', 'custom-elementor-countdown'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'row' => [
                        'title' => esc_html__('Row', 'custom-elementor-countdown'),
                        'icon' => 'eicon-arrow-right',
                    ],
                    'column' => [
                        'title' => esc_html__('Column', 'custom-elementor-countdown'),
                        'icon' => 'eicon-arrow-down',
                    ],
                ],
                'default' => 'row',
                'selectors' => [
                    '{{WRAPPER}} .custom-countdown-wrapper' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Container
        $this->start_controls_section(
            'style_container',
            [
                'label' => esc_html__('Container', 'custom-elementor-countdown'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'container_gap',
            [
                'label' => esc_html__('Gap', 'custom-elementor-countdown'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .custom-countdown-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_alignment',
            [
                'label' => esc_html__('Alignment', 'custom-elementor-countdown'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Left', 'custom-elementor-countdown'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'custom-elementor-countdown'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Right', 'custom-elementor-countdown'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .custom-countdown-wrapper' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'container_background',
            [
                'label' => esc_html__('Background Color', 'custom-elementor-countdown'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .custom-countdown-wrapper' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => esc_html__('Padding', 'custom-elementor-countdown'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .custom-countdown-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'label' => esc_html__('Border', 'custom-elementor-countdown'),
                'selector' => '{{WRAPPER}} .custom-countdown-wrapper',
            ]
        );

        $this->add_responsive_control(
            'container_border_radius',
            [
                'label' => esc_html__('Border Radius', 'custom-elementor-countdown'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .custom-countdown-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Items
        $this->start_controls_section(
            'style_items',
            [
                'label' => esc_html__('Items', 'custom-elementor-countdown'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'item_background',
            [
                'label' => esc_html__('Background Color', 'custom-elementor-countdown'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f8f9fa',
                'selectors' => [
                    '{{WRAPPER}} .custom-countdown-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'label' => esc_html__('Border', 'custom-elementor-countdown'),
                'selector' => '{{WRAPPER}} .custom-countdown-item',
            ]
        );

        $this->add_responsive_control(
            'item_border_radius',
            [
                'label' => esc_html__('Border Radius', 'custom-elementor-countdown'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => 10,
                    'right' => 10,
                    'bottom' => 10,
                    'left' => 10,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .custom-countdown-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => esc_html__('Padding', 'custom-elementor-countdown'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 20,
                    'right' => 20,
                    'bottom' => 20,
                    'left' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .custom-countdown-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_min_width',
            [
                'label' => esc_html__('Min Width', 'custom-elementor-countdown'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => 80,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .custom-countdown-item' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_box_shadow',
                'label' => esc_html__('Box Shadow', 'custom-elementor-countdown'),
                'selector' => '{{WRAPPER}} .custom-countdown-item',
            ]
        );

        $this->end_controls_section();

        // Style Section - Numbers
        $this->start_controls_section(
            'style_numbers',
            [
                'label' => esc_html__('Numbers', 'custom-elementor-countdown'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'number_color',
            [
                'label' => esc_html__('Color', 'custom-elementor-countdown'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .custom-countdown-number' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'number_typography',
                'label' => esc_html__('Typography', 'custom-elementor-countdown'),
                'selector' => '{{WRAPPER}} .custom-countdown-number',
            ]
        );

        $this->add_responsive_control(
            'number_margin',
            [
                'label' => esc_html__('Margin', 'custom-elementor-countdown'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .custom-countdown-number' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'number_text_shadow',
                'label' => esc_html__('Text Shadow', 'custom-elementor-countdown'),
                'selector' => '{{WRAPPER}} .custom-countdown-number',
            ]
        );

        $this->end_controls_section();

        // Style Section - Labels
        $this->start_controls_section(
            'style_labels',
            [
                'label' => esc_html__('Labels', 'custom-elementor-countdown'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_labels' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => esc_html__('Color', 'custom-elementor-countdown'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .custom-countdown-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'label' => esc_html__('Typography', 'custom-elementor-countdown'),
                'selector' => '{{WRAPPER}} .custom-countdown-label',
            ]
        );

        $this->add_responsive_control(
            'label_margin',
            [
                'label' => esc_html__('Margin', 'custom-elementor-countdown'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .custom-countdown-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'label_text_transform',
            [
                'label' => esc_html__('Text Transform', 'custom-elementor-countdown'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'uppercase',
                'options' => [
                    'none' => esc_html__('None', 'custom-elementor-countdown'),
                    'uppercase' => esc_html__('Uppercase', 'custom-elementor-countdown'),
                    'lowercase' => esc_html__('Lowercase', 'custom-elementor-countdown'),
                    'capitalize' => esc_html__('Capitalize', 'custom-elementor-countdown'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .custom-countdown-label' => 'text-transform: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'label_letter_spacing',
            [
                'label' => esc_html__('Letter Spacing', 'custom-elementor-countdown'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => -2,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 1,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .custom-countdown-label' => 'letter-spacing: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Expired Message
        $this->start_controls_section(
            'style_expired',
            [
                'label' => esc_html__('Expired Message', 'custom-elementor-countdown'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'expired_color',
            [
                'label' => esc_html__('Color', 'custom-elementor-countdown'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ff0000',
                'selectors' => [
                    '{{WRAPPER}} .countdown-expired' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'expired_typography',
                'label' => esc_html__('Typography', 'custom-elementor-countdown'),
                'selector' => '{{WRAPPER}} .countdown-expired',
            ]
        );

        $this->add_responsive_control(
            'expired_text_align',
            [
                'label' => esc_html__('Alignment', 'custom-elementor-countdown'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'custom-elementor-countdown'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'custom-elementor-countdown'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'custom-elementor-countdown'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .countdown-expired' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $target_date = $settings['target_date'];
        $language = $settings['language'];
        $expired_text = $settings['expired_text'];
        $show_labels = $settings['show_labels'];
        
        // Get custom labels or use defaults
        $label_days = !empty($settings['label_days']) ? $settings['label_days'] : ($language === 'bangla' ? 'দিন' : 'Days');
        $label_hours = !empty($settings['label_hours']) ? $settings['label_hours'] : ($language === 'bangla' ? 'ঘন্টা' : 'Hours');
        $label_minutes = !empty($settings['label_minutes']) ? $settings['label_minutes'] : ($language === 'bangla' ? 'মিনিট' : 'Minutes');
        $label_seconds = !empty($settings['label_seconds']) ? $settings['label_seconds'] : ($language === 'bangla' ? 'সেকেন্ড' : 'Seconds');
        
        ?>
        <div class="custom-countdown-wrapper" 
             data-target-date="<?php echo esc_attr($target_date); ?>"
             data-language="<?php echo esc_attr($language); ?>"
             data-expired-text="<?php echo esc_attr($expired_text); ?>"
             data-show-labels="<?php echo esc_attr($show_labels); ?>"
             data-label-days="<?php echo esc_attr($label_days); ?>"
             data-label-hours="<?php echo esc_attr($label_hours); ?>"
             data-label-minutes="<?php echo esc_attr($label_minutes); ?>"
             data-label-seconds="<?php echo esc_attr($label_seconds); ?>">
            <!-- Countdown will be populated by JavaScript -->
        </div>
        <?php
    }

    protected function content_template() {
        ?>
        <#
        var targetDate = settings.target_date;
        var language = settings.language;
        var expiredText = settings.expired_text;
        var showLabels = settings.show_labels;
        
        // Get custom labels or use defaults
        var labelDays = settings.label_days || (language === 'bangla' ? 'দিন' : 'Days');
        var labelHours = settings.label_hours || (language === 'bangla' ? 'ঘন্টা' : 'Hours');
        var labelMinutes = settings.label_minutes || (language === 'bangla' ? 'মিনিট' : 'Minutes');
        var labelSeconds = settings.label_seconds || (language === 'bangla' ? 'সেকেন্ড' : 'Seconds');
        
        // Convert numbers to Bangla if needed
        function convertToBangla(number) {
            var banglaNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
            return number.toString().split('').map(function(digit) {
                return isNaN(digit) ? digit : banglaNumbers[parseInt(digit)];
            }).join('');
        }
        
        var displayDays = language === 'bangla' ? convertToBangla('30') : '30';
        var displayHours = language === 'bangla' ? convertToBangla('12') : '12';
        var displayMinutes = language === 'bangla' ? convertToBangla('45') : '45';
        var displaySeconds = language === 'bangla' ? convertToBangla('20') : '20';
        #>
        <div class="custom-countdown-wrapper" 
             data-target-date="{{{ targetDate }}}"
             data-language="{{{ language }}}"
             data-expired-text="{{{ expiredText }}}"
             data-show-labels="{{{ showLabels }}}"
             data-label-days="{{{ labelDays }}}"
             data-label-hours="{{{ labelHours }}}"
             data-label-minutes="{{{ labelMinutes }}}"
             data-label-seconds="{{{ labelSeconds }}}">
            <div class="custom-countdown-item">
                <span class="custom-countdown-number">{{{ displayDays }}}</span>
                <# if ( showLabels === 'yes' ) { #>
                    <span class="custom-countdown-label">{{{ labelDays }}}</span>
                <# } #>
            </div>
            <div class="custom-countdown-item">
                <span class="custom-countdown-number">{{{ displayHours }}}</span>
                <# if ( showLabels === 'yes' ) { #>
                    <span class="custom-countdown-label">{{{ labelHours }}}</span>
                <# } #>
            </div>
            <div class="custom-countdown-item">
                <span class="custom-countdown-number">{{{ displayMinutes }}}</span>
                <# if ( showLabels === 'yes' ) { #>
                    <span class="custom-countdown-label">{{{ labelMinutes }}}</span>
                <# } #>
            </div>
            <div class="custom-countdown-item">
                <span class="custom-countdown-number">{{{ displaySeconds }}}</span>
                <# if ( showLabels === 'yes' ) { #>
                    <span class="custom-countdown-label">{{{ labelSeconds }}}</span>
                <# } #>
            </div>
        </div>
        <?php
    }
}