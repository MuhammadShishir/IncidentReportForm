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
// Get user's location using Geolocation API
function getUserLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else {
        document.getElementById("location").setAttribute("placeholder", "<?php _e('Geolocation not supported', 'incident-report-form-elementor'); ?>");
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
}
</script>
