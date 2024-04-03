<?php
/**
 * Plugin Name: Incident Report Form Elementor
 * Description: This plugin provides an Elementor widget for creating customizable incident report forms with various functionalities.
 * Version: 1.0.0
 * Author: MKS Entertainment & Technologies
 * Author URI: https://mkshishir.pages.dev
 * Text Domain: incident-report-form-elementor
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Include the main plugin class.
require_once(plugin_dir_path(__FILE__) . 'includes/class-incident-report-widget.php');

// Register the widget.
function register_incident_report_widget() {
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Incident_Report_Widget());
}
add_action('elementor/widgets/widgets_registered', 'register_incident_report_widget');

// Enqueue admin scripts and styles.
function incident_report_enqueue_admin_scripts() {
    wp_enqueue_style('incident-report-admin-styles', plugins_url('admin/css/admin-styles.css', __FILE__));
    wp_enqueue_script('incident-report-admin-scripts', plugins_url('admin/js/admin-scripts.js', __FILE__), array('jquery'), '1.0', true);
}
add_action('admin_enqueue_scripts', 'incident_report_enqueue_admin_scripts');

// Enqueue frontend scripts and styles.
function incident_report_enqueue_frontend_scripts() {
    wp_enqueue_style('incident-report-frontend-styles', plugins_url('assets/css/frontend-styles.css', __FILE__));
    wp_enqueue_script('incident-report-frontend-scripts', plugins_url('assets/js/frontend-scripts.js', __FILE__), array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'incident_report_enqueue_frontend_scripts');

// Load plugin text domain for translation.
function incident_report_load_textdomain() {
    load_plugin_textdomain('incident-report-form-elementor', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'incident_report_load_textdomain');
