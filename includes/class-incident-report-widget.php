<?php
use Elementor\Widget_Base;

/**
 * Class Incident_Report_Widget
 */
class Incident_Report_Widget extends Widget_Base {

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
    }

    /**
     * Render widget output on the frontend.
     */
    protected function render() {
        // Render widget output here.
    }
}
