<?php
use Elementor\Controls_Manager;

/**
 * Class Incident_Report_Widget
 */
class Incident_Report_Widget extends \Elementor\Widget_Base {
    // Widget constructor
    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);

        // Enqueue scripts and styles
        add_action('elementor/frontend/after_register_scripts', [$this, 'enqueue_incident_report_form_elementor_assets']);
    }

    // Enqueue scripts and styles
    public function enqueue_incident_report_form_elementor_assets() {
        // Enqueue your CSS and JS files here
        wp_enqueue_style('frontend-styles', plugin_dir_url(__FILE__) . 'includes/assets/css/style.css');
        wp_enqueue_script('frontend-script', plugin_dir_url(__FILE__) . 'includes/assets/js/script.js', ['jquery'], null, true);
    } 

   
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
                    '{{WRAPPER}} .zakat-overlay' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .zakat-overlay' => 'background-image: url({{URL}});',
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
                    '{{WRAPPER}} .zakat-overlay' => 'background-position: {{VALUE}};',
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
                    '{{WRAPPER}} .zakat-overlay' => 'background-size: {{VALUE}};',
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
                    '{{WRAPPER}} .zakat-overlay' => 'background-repeat: {{VALUE}};',
                ],
            ]
        );

        // Background Attachment control
        $this->add_control(
            'background_attachment',
            [
                'label' => __('Background Attachment', 'incident-report-form-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'scroll' => __('Scroll', 'incident-report-form-elementor'),
                    'fixed' => __('Fixed', 'incident-report-form-elementor'),
                    'local' => __('Local', 'incident-report-form-elementor'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .zakat-overlay' => 'background-attachment: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();
    }

/**
     * Render widget output on the frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
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
            <button type="submit"><?php _e('Submit', 'incident-report-form-elementor'); ?></button>
        </form>

        <!-- Thank You Message -->
        <div id="thank-you-message" style="display: none;">
            <?php _e('Thank you for your submission!', 'incident-report-form-elementor'); ?>
        </div>

        <script>
            jQuery(document).ready(function($) {
            // AJAX request to handle form submission
            $('#incident-report-form').on('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                // Get user's location using Geolocation API
                getUserLocation();
            });

            // Get user's location using Geolocation API
            function getUserLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        // Callback function to handle the user's location
                        showPosition(position);
                        // Submit the form after getting the user's location
                        submitForm();
                    }, showError);
                } else {
                    // If geolocation is not supported, proceed with form submission without location
                    submitForm();
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

            // Error handling for Geolocation API
            function showError(error) {
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        console.error("User denied the request for Geolocation.");
                        break;
                    case error.POSITION_UNAVAILABLE:
                        console.error("Location information is unavailable.");
                        break;
                    case error.TIMEOUT:
                        console.error("The request to get user location timed out.");
                        break;
                    case error.UNKNOWN_ERROR:
                        console.error("An unknown error occurred.");
                        break;
                }
                // If there's an error with geolocation, proceed with form submission without location
                submitForm();
            }

            // Function to submit the form
            function submitForm() {
                var formData = new FormData($('#incident-report-form')[0]);
                $.ajax({
                    type: 'POST',
                    url: incident_report_ajax_object.ajaxurl,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Display success message
                        $('#thank-you-message').show();
                        // Optionally, reset the form after successful submission
                        $('#incident-report-form')[0].reset();
                    },
                    error: function(xhr, status, error) {
                        // Display error message
                        console.error('AJAX Error:', xhr.responseText); // Log error response
                        alert('An error occurred while submitting the form. Please try again.');
                    }
                });
            }
        });

        </script>
        <?php
    }

}
