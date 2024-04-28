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
if (!defined('ABSPATH')) exit;

use Elementor\Controls_Manager;

function create_incident_reports_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'incident_reports';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        email varchar(255) NOT NULL,
        phone varchar(20) NOT NULL,
        message text NOT NULL,
        location varchar(255) NOT NULL,
        latitude decimal(10,6) DEFAULT NULL,
        longitude decimal(10,6) DEFAULT NULL,
        image_url varchar(255) DEFAULT NULL, -- Separate column for image URL
        submitted_at datetime NOT NULL,
        ip_address varchar(100) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}
register_activation_hook( __FILE__, 'create_incident_reports_table' );

function incident_report_form_elementor_init() {

    class Incident_Report_Form_Elementor {
        
        public function __construct() {
            // Enqueue scripts and styles
            add_action('elementor/frontend/after_register_scripts', array($this, 'enqueue_incident_report_assets'));
        
            // Register the Elementor widget
            add_action('elementor/widgets/widgets_registered', array($this, 'register_incident_report_widget'));
        
            // Enqueue scripts and localize AJAX URL
            add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        
            add_action('wp_ajax_submit_incident_report', array($this, 'submit_report'));
            add_action('wp_ajax_nopriv_submit_incident_report', array($this, 'submit_report'));
            add_action('admin_menu', array($this, 'add_admin_menu'));
        }
        
        public function enqueue_scripts() {
            // Enqueue frontend scripts and styles
            wp_enqueue_script('incident-report-frontend-scripts', plugin_dir_url(__FILE__) . '/assets/js/frontend-scripts.js', array('jquery'), null, true);
            wp_enqueue_style('incident-report-frontend-styles', plugin_dir_url(__FILE__) . '/assets/css/frontend-styles.css', array(), null);
        
            // Localize script with AJAX URL and nonce
            wp_localize_script('incident-report-frontend-scripts', 'incident_report_ajax_object', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('submit_incident_report_nonce')
            ));
        } 
        
        public function enqueue_incident_report_assets() {
            // Enqueue your CSS and JS files here
            wp_enqueue_style('incident-report-form-elementor-style', plugin_dir_url(__FILE__) . '/assets/css/frontend-styles.css');
            wp_enqueue_script('incident-report-form-elementor-script', plugin_dir_url(__FILE__) . '/assets/js/frontend-scripts.js', array('jquery'), null, true);

        }

        // Register the Elementor widget
        public function register_incident_report_widget() {
            // Include your widget class file and register the widget
            require_once(plugin_dir_path(__FILE__) . 'includes/class-incident-report-widget.php');

            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Incident_Report_Widget());
        }
    
        public function submit_report() {
            // Check nonce and permissions
            if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'submit_incident_report_nonce')) {
                wp_send_json_error('Invalid nonce.');
            }

            $name = sanitize_text_field($_POST['name']);
            $email = sanitize_email($_POST['email']);
            $phone = sanitize_text_field($_POST['phone']);
            $message = sanitize_textarea_field($_POST['message']);
            $location = sanitize_text_field($_POST['location']);
            $latitude = isset($_POST['latitude']) ? floatval($_POST['latitude']) : '';
            $longitude = isset($_POST['longitude']) ? floatval($_POST['longitude']) : '';
            // Handle image upload
            $image_url = '';
            if (isset($_FILES['incident_image'])) {
                $upload_overrides = array('test_form' => false);
                $uploaded_image = wp_handle_upload($_FILES['incident_image'], $upload_overrides);
                
                if ($uploaded_image && !isset($uploaded_image['error'])) {
                    $image_url = $uploaded_image['url']; // Get the URL of the uploaded image
                } else {
                    // Handle upload error
                    $upload_error = isset($uploaded_image['error']) ? $uploaded_image['error'] : 'Image upload failed.';
                    wp_send_json_error($upload_error);
                }
            }

            global $wpdb;
            $table_name = $wpdb->prefix . 'incident_reports';

            $insert_result = $wpdb->insert($table_name, array(
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'message' => $message,
                'location' => $location,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'image_url' => $image_url, // Insert the image URL into the database
                'submitted_at' => current_time('mysql'),
                'ip_address' => $_SERVER['REMOTE_ADDR']
            ));

            if ($insert_result === false) {
                $error_message = $wpdb->last_error;
                error_log('Error inserting data into the database: ' . $error_message);
                wp_send_json_error('Error submitting report. Please try again later.');
            } else {
                // Return a success response
                wp_send_json_success('Report submitted successfully');
            }
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
        
            // Enqueue admin styles and scripts
            add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        }
        
        public function enqueue_admin_assets($hook) {
            // Enqueue admin styles
            wp_enqueue_style('incident-report-admin-styles', plugin_dir_url(__FILE__) . '/admin/css/admin-styles.css', array(), null);
        
            // Enqueue admin scripts
            wp_enqueue_script('incident-report-admin-scripts', plugin_dir_url(__FILE__) . '/admin/js/admin-scripts.js', array('jquery'), null, true);
        }
        

        public function render_incident_reports_page() {
            global $wpdb;
            $table_name = $wpdb->prefix . 'incident_reports';
            $incident_reports = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
            echo '<div class="wrap">';
            echo '<h1 style="color: pink;">Incident Reports</h1>';

                    // Add inline styles for the table
            echo '<style>';
            echo '.wp-list-table { width: 100%; border-collapse: collapse; }';
            echo '.wp-list-table th, .wp-list-table td { padding: 8px; border: 1px solid #ddd; }';
            echo '.wp-list-table th { background-color: yellow; }';
            echo '.wp-list-table td { background-color: lightblue; }';
            echo '</style>'; '<div class="wrap">';
            
            echo '<table class="wp-list-table widefat fixed striped">';
            echo '<thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Message</th><th>Location</th><th>Latitude</th><th>Longitude</th><th>Image URL</th><th>Submitted At</th><th>IP Address</th></tr></thead>';
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
                echo '<td>' . esc_html($incident_report['image_url']) . '</td>';
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

}

add_action('plugins_loaded', 'incident_report_form_elementor_init');



?>
