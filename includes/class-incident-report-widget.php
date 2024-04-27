<?php
/**
 * Class Incident_Report_Widget
 */
class Incident_Report_Widget extends \Elementor\Widget_Base {

    /**
     * Get widget name.
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'incident-report-widget';
    }

    /**
     * Get widget title.
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Incident Report Form', 'incident-report-form-elementor');
    }

    /**
     * Get widget icon.
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-form-horizontal';
    }

    /**
     * Get widget categories.
     *
     * @return array Widget categories.
     */
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
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'widget_title',
            [
                'label' => __('Widget Title', 'incident-report-form-elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Incident Report Form', 'incident-report-form-elementor'),
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on the frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        // Render the widget title
        echo '<h2>' . esc_html($settings['widget_title']) . '</h2>';

        // Render the incident report form shortcode
        echo do_shortcode('[incident_report_form]');
    }
}

// Register the widget
function register_incident_report_widget() {
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Incident_Report_Widget());
}
add_action('elementor/widgets/widgets_registered', 'register_incident_report_widget');
