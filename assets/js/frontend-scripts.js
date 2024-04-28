jQuery(document).ready(function($) {
    $('#incident-report-form').off('submit').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        // Fetch user's geolocation
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var latitude = position.coords.latitude;
                var longitude = position.coords.longitude;

                // Populate latitude and longitude fields
                $('.incident-report-form-elementor-latitude').val(latitude);
                $('.incident-report-form-elementor-longitude').val(longitude);

                submitReport(); // Proceed with form submission
            });
        } else {
            submitReport(); // Proceed with form submission without geolocation
        }
    });

    // Function to submit the report via AJAX
    function submitReport() {
        var formData = $('#incident-report-form').serialize(); // Serialize form data

        // Submit AJAX request
        $.ajax({
            url: incident_report_ajax_object.ajaxurl, // Use admin-ajax.php URL
            type: 'POST',
            data: formData + '&action=submit_incident_report&nonce=' + incident_report_ajax_object.nonce,
            success: function(response) {
                // Reset form after successful submission
                $('#incident-report-form')[0].reset();
                alert('Report submitted successfully');
            },
            error: function(xhr, status, error) {
                console.error(error);
                alert('Error submitting report. Please try again.');
            }
        });
    }
});
