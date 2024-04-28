<?php
use Elementor\Controls_Manager;

/**
 * Class Incident_Report_Widget
 */
class Incident_Report_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'incident-report-widget';
    }
    
    public function get_title() {
        return __('Incident Report Form', 'incident-report-form-elementor');
    }
    
    public function get_icon() {
        return 'eicon-form-horizontal';
    }

    public function get_categories() {
        return ['general'];
    }

    /**
     * Register widget controls.
     */
    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'incident-report-form-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'label',
            [
                'label' => __('Label', 'incident-report-form-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Incident Report Form', 'incident-report-form-elementor'),
                'placeholder' => __('Enter your money amount:', 'incident-report-form-elementor'),
            ]
        );

        $this->end_controls_section();
        
        // Style section for form
        $this->start_controls_section(
            'form_style_section',
            [
                'label' => __('Form Style', 'incident-report-form-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Form label color control
        $this->add_control(
            'form_label_color',
            [
                'label' => __('Label Color', 'incident-report-form-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .incident-report-form-elementor-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Form label font size control
        $this->add_control(
            'form_label_font_size',
            [
                'label' => __('Label Font Size', 'incident-report-form-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .incident-report-form-elementor-label' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Input color control
        $this->add_control(
            'input_color',
            [
                'label' => __('Input Color', 'incident-report-form-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .incident-report-form-elementor-input' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Input background color control
        $this->add_control(
            'input_background_color',
            [
                'label' => __('Input Background Color', 'incident-report-form-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .incident-report-form-elementor-input' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Submit button color control
        $this->add_control(
            'button_color',
            [
                'label' => __('Button Color', 'incident-report-form-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .incident-report-form-elementor-submit' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Submit button background color control
        $this->add_control(
            'button_background_color',
            [
                'label' => __('Button Background Color', 'incident-report-form-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .incident-report-form-elementor-submit' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style section for overlay
        $this->start_controls_section(
            'overlay_style_section',
            [
                'label' => __('Background Style', 'incident-report-form-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Overlay color control
        $this->add_control(
            'overlay_color',
            [
                'label' => __('Background Overlay Color', 'incident-report-form-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .incident-report-form-elementor-overlay' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Background Image control
        $this->add_control(
            'background_image',
            [
                'label' => __('Background Image', 'incident-report-form-elementor'),
                'type' => Controls_Manager::MEDIA,
                'selectors' => [
                    '{{WRAPPER}} .incident-report-form-elementor-overlay' => 'background-image: url({{URL}});',
                ],
            ]
        );
        // Background Position control
        $this->add_control(
            'background_position',
            [
                'label' => __('Background Position', 'incident-report-form-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'top left' => __('Top Left', 'incident-report-form-elementor'),
                    'top center' => __('Top Center', 'incident-report-form-elementor'),
                    'top right' => __('Top Right', 'incident-report-form-elementor'),
                    'center left' => __('Center Left', 'incident-report-form-elementor'),
                    'center center' => __('Center Center', 'incident-report-form-elementor'),
                    'center right' => __('Center Right', 'incident-report-form-elementor'),
                    'bottom left' => __('Bottom Left', 'incident-report-form-elementor'),
                    'bottom center' => __('Bottom Center', 'incident-report-form-elementor'),
                    'bottom right' => __('Bottom Right', 'incident-report-form-elementor'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .incident-report-form-elementor-overlay' => 'background-position: {{VALUE}};',
                ],
            ]
        );

        // Background Size control
        $this->add_control(
            'background_size',
            [
                'label' => __('Background Size', 'incident-report-form-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'auto' => __('Auto', 'incident-report-form-elementor'),
                    'cover' => __('Cover', 'incident-report-form-elementor'),
                    'contain' => __('Contain', 'incident-report-form-elementor'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .incident-report-form-elementor-overlay' => 'background-size: {{VALUE}};',
                ],
            ]
        );

        // Background Repeat control
        $this->add_control(
            'background_repeat',
            [
                'label' => __('Background Repeat', 'incident-report-form-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'repeat' => __('Repeat', 'incident-report-form-elementor'),
                    'no-repeat' => __('No Repeat', 'incident-report-form-elementor'),
                    'repeat-x' => __('Repeat Horizontally', 'incident-report-form-elementor'),
                    'repeat-y' => __('Repeat Vertically', 'incident-report-form-elementor'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .incident-report-form-elementor-overlay' => 'background-repeat: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        ?>
        <div class="incident-report-form-elementor">
            <div class="incident-report-form-elementor-overlay"></div>
            <form id="incident-report-form" action="#" method="post" enctype="multipart/form-data">

                <div class="incident-report-form-elementor-field">
                    <label class="incident-report-form-elementor-label"><?php echo esc_html($settings['label']); ?></label>
                    <input type="text" name="name" class="incident-report-form-elementor-input" placeholder="<?php esc_attr_e('Your name', 'incident-report-form-elementor'); ?>" required>
                </div>
                <div class="incident-report-form-elementor-field">
                    <input type="email" name="email" class="incident-report-form-elementor-input" placeholder="<?php esc_attr_e('Your email', 'incident-report-form-elementor'); ?>" >
                </div>
                <div class="incident-report-form-elementor-field">
                    <input type="tel" name="phone" class="incident-report-form-elementor-input" placeholder="<?php esc_attr_e('Your phone number', 'incident-report-form-elementor'); ?>">
                </div>
                <div class="incident-report-form-elementor-field">
                    <textarea name="message" class="incident-report-form-elementor-input" placeholder="<?php esc_attr_e('Incident description', 'incident-report-form-elementor'); ?>" ></textarea>
                </div>
                <div class="incident-report-form-elementor-field">
                    <input type="text" name="location" class="incident-report-form-elementor-input" placeholder="<?php esc_attr_e('Incident location', 'incident-report-form-elementor'); ?>">
                </div>
                <div class="incident-report-form-elementor-field">
                <label for="incident-image"><?php esc_html_e('Upload Image', 'incident-report-form-elementor'); ?></label>
                <input type="file" id="incident-image" name="incident_image" accept="image/*">
                
                </div>
                <input type="hidden" name="latitude" class="incident-report-form-elementor-latitude">
                <input type="hidden" name="longitude" class="incident-report-form-elementor-longitude">
                <input type="hidden" name="nonce" class="incident-report-form-elementor-nonce" value="<?php echo esc_attr(wp_create_nonce('submit_incident_report_nonce')); ?>">

                <div class="incident-report-form-elementor-field">
                <button type="submit"><?php esc_html_e('Submit', 'incident-report-form-elementor'); ?></button>
                </div>
            </form>
        </div>
        <?php
    }
}
