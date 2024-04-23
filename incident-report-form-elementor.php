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

// Register the widget after Elementor is loaded.
function register_incident_report_widget() {
    // Check if Elementor plugin is active
    if (defined('ELEMENTOR_PATH') && class_exists('\Elementor\Widget_Base')) {
        // Include the widget class file
        require_once(plugin_dir_path(__FILE__) . 'includes/class-incident-report-widget.php');

        // Register the widget
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Incident_Report_Widget());
    }
}
add_action('elementor/widgets/widgets_registered', 'register_incident_report_widget');

// Include the main plugin class.
require_once(plugin_dir_path(__FILE__) . 'includes/class-incident-report-form.php');

// Enqueue frontend scripts and styles.
function incident_report_enqueue_frontend_scripts() {
    // Enqueue frontend scripts and styles here.
    wp_enqueue_script('incident-report-frontend-scripts', plugin_dir_url(__FILE__) . 'assets/js/frontend-scripts.js', array('jquery'), null, true);
    wp_enqueue_style('incident-report-frontend-styles', plugin_dir_url(__FILE__) . 'assets/css/frontend-styles.css', array(), null);
    wp_localize_script('incident-report-frontend-scripts', 'incident_report_ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'incident_report_enqueue_frontend_scripts');

// Load plugin text domain for translation.
function incident_report_load_textdomain() {
    load_plugin_textdomain('incident-report-form-elementor', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'incident_report_load_textdomain');

// Define shortcode handler function
function render_incident_report_form_shortcode() {
    ob_start();
    ?>
    <!-- Incident Report Form -->
    <form id="incident-report-form" class="incident-report-form" method="post" enctype="multipart/form-data">
        <!-- Name -->
        <label for="name"><?php _e('Name:', 'incident-report-form-elementor'); ?></label>
        <input type="text" id="name" name="name" required>
        
        <!-- Email -->
        <label for="email"><?php _e('Email:', 'incident-report-form-elementor'); ?></label>
        <input type="email" id="email" name="email">
        
        <!-- Phone -->
        <label for="phone"><?php _e('Phone:', 'incident-report-form-elementor'); ?></label>
        <input type="tel" id="phone" name="phone">
        
        <!-- Message -->
        <label for="message"><?php _e('Message:', 'incident-report-form-elementor'); ?></label>
        <textarea id="message" name="message" required></textarea>
        
        <!-- Location -->
        <label for="location"><?php _e('Location:', 'incident-report-form-elementor'); ?></label>
        <input type="text" id="location" name="location" required>
        
        <!-- Photo -->
        <label for="photo"><?php _e('Photo:', 'incident-report-form-elementor'); ?></label>
        <input type="file" id="photo" name="photo">

        <!-- Other form fields -->
        <input type="hidden" id="latitude" name="latitude">
        <input type="hidden" id="longitude" name="longitude">

        <!-- Submit Button -->
        <button type="submit" onclick="getUserLocation()"><?php _e('Submit', 'incident-report-form-elementor'); ?></button>
    </form>

    <div id="thank-you-message" style="display: none;">
        <?php _e('Thank you for your submission!', 'incident-report-form-elementor'); ?>
    </div>

    <script>
    // Get user's location using Geolocation API
    function getUserLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            alert("<?php _e('Geolocation is not supported by this browser.', 'incident-report-form-elementor'); ?>");
        }
    }

    // Callback function to handle the user's location
    function showPosition(position) {
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;

        // Set latitude and longitude values in hidden fields
        document.getElementById("latitude").value = latitude;
        document.getElementById("longitude").value = longitude;

        // Show the "Thank You" message
        document.getElementById("thank-you-message").style.display = "block";
    }
    </script>
    <?php
    return ob_get_clean();
}


// Register shortcode
add_shortcode('incident_report_form', 'render_incident_report_form_shortcode');

// Save submitted data to the database
function save_incident_report_data() {
    if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['message']) && isset($_POST['location']) && isset($_POST['latitude']) && isset($_POST['longitude'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'incident_reports';
        
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $message = sanitize_textarea_field($_POST['message']);
        $location = sanitize_text_field($_POST['location']);
        $latitude = sanitize_text_field($_POST['latitude']);
        $longitude = sanitize_text_field($_POST['longitude']);
        
        // Handle file upload and store in media library
        $photo_id = 0; // Initialize variable to store attachment ID
        
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/media.php';
            require_once ABSPATH . 'wp-admin/includes/image.php';

            $upload_overrides = array('test_form' => false); // Set upload overrides

            $file = wp_handle_upload($_FILES['photo'], $upload_overrides); // Upload file

            if (!empty($file['error'])) {
                // Handle upload error
                wp_die('Error uploading file: ' . $file['error']);
            }

            // Get attachment ID
            $photo_id = $file['id'];
        }
        
        // Insert data into database
        $wpdb->insert(
            $table_name,
            array(
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'message' => $message,
                'location' => $location,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'photo_id' => $photo_id, // Store attachment ID in database
                'submitted_at' => current_time('mysql'),
                'ip_address' => $_SERVER['REMOTE_ADDR']
            )
        );
    }
}

add_action('init', 'save_incident_report_data');

// AJAX handler for form submission
function handle_incident_report_form_submission() {
    // Handle form submission and return response
    save_incident_report_data(); // Save submitted data to the database
    
    // Return JSON response
    wp_send_json_success(array('message' => __('Form submitted successfully.', 'incident-report-form-elementor')));
}
add_action('wp_ajax_submit_incident_report', 'handle_incident_report_form_submission');
add_action('wp_ajax_nopriv_submit_incident_report', 'handle_incident_report_form_submission');

// Add admin menu to display submitted data
function incident_report_admin_menu() {
    add_menu_page(
        __('Incident Reports', 'incident-report-form-elementor'), // Page title
        __('Incident Reports', 'incident-report-form-elementor'), // Menu title
        'manage_options', // Capability required
        'incident-reports', // Menu slug
        'render_incident_reports_page', // Callback function to render page content
        'dashicons-analytics' // Icon
    );
}
add_action('admin_menu', 'incident_report_admin_menu');

// Render admin page content to display submitted data
function render_incident_reports_page() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

 // Fetch submitted data from the database
global $wpdb;
$table_name = $wpdb->prefix . 'incident_reports';
$incident_reports = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

// Display data in a table
?>
    <div class="wrap">
        <h1><?php _e('Incident Reports', 'incident-report-form-elementor'); ?></h1>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e('Name', 'incident-report-form-elementor'); ?></th>
                    <th><?php _e('Email', 'incident-report-form-elementor'); ?></th>
                    <th><?php _e('Phone', 'incident-report-form-elementor'); ?></th>
                    <th><?php _e('Message', 'incident-report-form-elementor'); ?></th>
                    <th><?php _e('Location', 'incident-report-form-elementor'); ?></th>
                    <th><?php _e('Latitude', 'incident-report-form-elementor'); ?></th>
                    <th><?php _e('Longitude', 'incident-report-form-elementor'); ?></th>
                    <th><?php _e('Submitted At', 'incident-report-form-elementor'); ?></th>
                    <th><?php _e('IP Address', 'incident-report-form-elementor'); ?></th>
                    <th><?php _e('Photo', 'incident-report-form-elementor'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($incident_reports as $incident_report) : ?>
                    <tr>
                        <td><?php echo esc_html($incident_report['name']); ?></td>
                        <td><?php echo esc_html($incident_report['email']); ?></td>
                        <td><?php echo esc_html($incident_report['phone']); ?></td>
                        <td><?php echo esc_html($incident_report['message']); ?></td>
                        <td><?php echo esc_html($incident_report['location']); ?></td>
                        <td><?php echo esc_html($incident_report['latitude']); ?></td>
                        <td><?php echo esc_html($incident_report['longitude']); ?></td>
                        <td><?php echo esc_html($incident_report['submitted_at']); ?></td>
                        <td><?php echo esc_html($incident_report['ip_address']); ?></td>
                        <td><img src="<?php echo esc_url($incident_report['photo']); ?>" alt="<?php echo esc_attr__('Incident Photo', 'incident-report-form-elementor'); ?>" style="max-width: 100px;"></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php
}
