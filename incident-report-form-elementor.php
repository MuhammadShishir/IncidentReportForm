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

// Enqueue frontend scripts and styles.
function incident_report_enqueue_frontend_scripts() {
    wp_enqueue_script('incident-report-frontend-scripts', plugin_dir_url(__FILE__) . 'includes/assets/js/frontend-scripts.js', array('jquery'), null, true);
    wp_enqueue_style('incident-report-frontend-styles', plugin_dir_url(__FILE__) . 'includes/assets/css/frontend-styles.css', array(), null);
    wp_localize_script('incident-report-frontend-scripts', 'incident_report_ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'incident_report_enqueue_frontend_scripts');

// Load plugin text domain for translation.
function incident_report_load_textdomain() {
    load_plugin_textdomain('incident-report-form-elementor', false, dirname(plugin_basename(__FILE__)) . '/includes/languages/');
}
add_action('plugins_loaded', 'incident_report_load_textdomain');

// Include the main plugin class.
require_once(plugin_dir_path(__FILE__) . 'incident-report-form-elementor.php');

// Include the admin scripts and styles.
function incident_report_enqueue_admin_scripts() {
    wp_enqueue_script('incident-report-admin-scripts', plugin_dir_url(__FILE__) . 'admin/js/admin-scripts.js', array('jquery'), null, true);
    wp_enqueue_style('incident-report-admin-styles', plugin_dir_url(__FILE__) . 'admin/css/admin-styles.css', array(), null);
}
add_action('admin_enqueue_scripts', 'incident_report_enqueue_admin_scripts');

class Incident_Report_Form_Elementor {
    
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_shortcode('incident_report_form', array($this, 'render_form_shortcode'));
        add_action('admin_post_submit_incident_report', array($this, 'submit_report'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }

    public function enqueue_scripts() {
        wp_enqueue_script('incident-report-frontend-scripts', plugin_dir_url(__FILE__) . 'includes/assets/js/frontend-scripts.js', array('jquery'), null, true);
        wp_enqueue_style('incident-report-frontend-styles', plugin_dir_url(__FILE__) . 'includes/assets/css/frontend-styles.css', array(), null);
        wp_localize_script('incident-report-frontend-scripts', 'incident_report_ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
    }
    
    public function render_form_shortcode($atts) {
        ob_start();
        include(plugin_dir_path(__FILE__) . '../templates/incident-report-form.php');
        return ob_get_clean();
    }

    public function submit_report() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'incident_reports';

        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $message = sanitize_textarea_field($_POST['message']);
        $location = sanitize_text_field($_POST['location']);
        $latitude = isset($_POST['latitude']) ? floatval($_POST['latitude']) : '';
        $longitude = isset($_POST['longitude']) ? floatval($_POST['longitude']) : '';

        $wpdb->insert($table_name, array(
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'message' => $message,
            'location' => $location,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'submitted_at' => current_time('mysql'),
            'ip_address' => $_SERVER['REMOTE_ADDR']
        ));

        // Redirect after form submission
        wp_redirect(home_url('/thank-you'));
        exit;
    }

    public function add_admin_menu() {
        add_menu_page(
            'Incident Reports',
            'Incident Reports',
            'manage_options',
            'incident-reports',
            array($this, 'render_incident_reports_page'),
            'dashicons-warning'
        );
    }

    public function render_incident_reports_page() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'incident_reports';
        $incident_reports = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

        echo '<div class="wrap">';
        echo '<h1>Incident Reports</h1>';
        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Message</th><th>Location</th><th>Latitude</th><th>Longitude</th><th>Submitted At</th><th>IP Address</th></tr></thead>';
        echo '<tbody>';
        foreach ($incident_reports as $incident_report) {
            echo '<tr>';
            echo '<td>' . esc_html($incident_report['name']) . '</td>';
            echo '<td>' . esc_html($incident_report['email']) . '</td>';
            echo '<td>' . esc_html($incident_report['phone']) . '</td>';
            echo '<td>' . esc_html($incident_report['message']) . '</td>';
            echo '<td>' . esc_html($incident_report['location']) . '</td>';
            echo '<td>' . esc_html($incident_report['latitude']) . '</td>';
            echo '<td>' . esc_html($incident_report['longitude']) . '</td>';
            echo '<td>' . esc_html($incident_report['submitted_at']) . '</td>';
            echo '<td>' . esc_html($incident_report['ip_address']) . '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    }
}

new Incident_Report_Form_Elementor();
