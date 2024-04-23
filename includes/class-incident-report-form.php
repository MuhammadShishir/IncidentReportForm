<?php
/**
 * Class Incident_Report_Form
 *
 * Handles the functionality related to incident report forms.
 */
class Incident_Report_Form {
    
    /**
     * Constructor.
     */
    public function __construct() {
        // Add actions and filters.
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_shortcode('incident_report_form', array($this, 'render_form_shortcode'));
        add_action('admin_post_submit_incident_report', array($this, 'submit_report'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }

    /**
     * Enqueue scripts and styles.
     */
    public function enqueue_scripts() {
        // Enqueue scripts and styles here.
        wp_enqueue_script('incident-report-frontend-scripts', plugin_dir_url(__FILE__) . 'assets/js/frontend-scripts.js', array('jquery'), null, true);
        wp_enqueue_style('incident-report-frontend-styles', plugin_dir_url(__FILE__) . 'assets/css/frontend-styles.css', array(), null);
        wp_localize_script('incident-report-frontend-scripts', 'incident_report_ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
    }
    
    /**
     * Render the incident report form shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @return string Form HTML.
     */
    public function render_form_shortcode($atts) {
        // Handle shortcode attributes and render the form HTML.
        ob_start();
        include(plugin_dir_path(__FILE__) . '../templates/incident-report-form.php');
        return ob_get_clean();
    }

    /**
     * Process incident report form submission.
     */
    public function submit_report() {
        // Handle form submission here.
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $message = sanitize_textarea_field($_POST['message']);

        // Get user's location
        $latitude = isset($_POST['latitude']) ? floatval($_POST['latitude']) : '';
        $longitude = isset($_POST['longitude']) ? floatval($_POST['longitude']) : '';

        // Handle photo upload
        $uploaded_photo = $_FILES['photo'];
        $upload_overrides = array('test_form' => false);
        $movefile = wp_handle_upload($uploaded_photo, $upload_overrides);

        if ($movefile && !isset($movefile['error'])) {
            // File successfully uploaded, get the URL
            $photo_url = $movefile['url'];
        } else {
            // Error uploading file
            $photo_url = '';
        }

        // Get submitter's IP address
        $ip_address = $_SERVER['REMOTE_ADDR'];

        // Example: Insert form data into database
        global $wpdb;
        $table_name = $wpdb->prefix . 'incident_reports';
        $wpdb->insert($table_name, array(
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'message' => $message,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'photo_url' => $photo_url,
            'submitted_at' => current_time('mysql'),
            'ip_address' => $ip_address
        ));

        // Redirect to a "Thank You" page
        wp_redirect(home_url('/thank-you'));
        exit;
    }

    /**
     * Add admin menu for viewing incident reports.
     */
    public function add_admin_menu() {
        add_menu_page(
            'Incident Reports', // Page title
            'Incident Reports', // Menu title
            'manage_options', // Capability required
            'incident-reports', // Menu slug
            array($this, 'render_incident_reports_page'), // Callback function to render page content
            'dashicons-warning' // Icon
        );
    }

    /**
     * Render admin page content for viewing incident reports.
     */
    public function render_incident_reports_page() {
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
            <h1>Incident Reports</h1>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Message</th>
                        <th>Location</th>
                        <th>Photo URL</th>
                        <th>Submitted At</th>
                        <th>IP Address</th>
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
                            <td><a href="<?php echo esc_url($incident_report['photo_url']); ?>" target="_blank">View Photo</a></td>
                            <td><?php echo esc_html($incident_report['submitted_at']); ?></td>
                            <td><?php echo esc_html($incident_report['ip_address']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
}

// Initialize the class.
new Incident_Report_Form();
