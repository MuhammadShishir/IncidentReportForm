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
        // Define widget controls here.
        // You can add controls for form fields such as text fields, email fields, etc.
    }

    /**
     * Render widget output on the frontend.
     */
    protected function render() {
        // Render the incident report form shortcode
        echo do_shortcode('[incident_report_form]');
    }
}
