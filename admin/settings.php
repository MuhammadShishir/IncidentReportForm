<?php
// settings.php

// Ensure that this file is being accessed within the WordPress environment.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Check if user has permissions to access the settings page.
if ( ! current_user_can( 'manage_options' ) ) {
    return;
}

// Include necessary files.
require_once plugin_dir_path( __FILE__ ) . 'includes/class-incident-report-widget.php';

// Check if form is submitted.
if ( isset( $_POST['incident_report_form_submit'] ) && wp_verify_nonce( $_POST['incident_report_nonce'], 'incident_report_nonce' ) ) {
    // Process form data.
    // Add your form processing logic here.
}

// Render the settings page.
?>
<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    
    <form method="post" action="">
        <?php wp_nonce_field( 'incident_report_nonce', 'incident_report_nonce' ); ?>
        
        <h2>Form Settings</h2>
        <p>Form Builder Tool: This is where you can build your incident report form.</p>
        <!-- Example form builder tool -->
        <textarea name="incident_report_form_builder" rows="8" cols="50"><?php echo esc_textarea( get_option( 'incident_report_form_builder' ) ); ?></textarea>
        
        <h2>CSS Settings</h2>
        <p>CSS Settings: Customize the appearance of your incident report form using CSS.</p>
        <!-- Example CSS settings fields -->
        <label for="incident_report_css">Custom CSS:</label>
        <textarea name="incident_report_css" rows="4" cols="50"><?php echo esc_textarea( get_option( 'incident_report_css' ) ); ?></textarea>
        
        <h2>JS Settings</h2>
        <p>JS Settings: Customize the functionality of your incident report form using JavaScript.</p>
        <!-- Example JS settings fields -->
        <label for="incident_report_js">Custom JavaScript:</label>
        <textarea name="incident_report_js" rows="4" cols="50"><?php echo esc_textarea( get_option( 'incident_report_js' ) ); ?></textarea>
        
        <h2>About Us Page</h2>
        <p>About Us Page: Customize the content of your About Us page.</p>
        <!-- Example About Us page settings fields -->
        <label for="incident_report_about_us_content">About Us Content:</label>
        <textarea name="incident_report_about_us_content" rows="8" cols="50"><?php echo esc_textarea( get_option( 'incident_report_about_us_content' ) ); ?></textarea>
        
        <h2>Donation Form Page</h2>
        <p>Donation Form Page: Customize the content of your Donation Form page.</p>
        <!-- Example Donation Form page settings fields -->
        <label for="incident_report_donation_form_content">Donation Form Content:</label>
        <textarea name="incident_report_donation_form_content" rows="8" cols="50"><?php echo esc_textarea( get_option( 'incident_report_donation_form_content' ) ); ?></textarea>
        
        <?php submit_button( 'Save Settings', 'primary', 'incident_report_form_submit' ); ?>
    </form>
</div>
