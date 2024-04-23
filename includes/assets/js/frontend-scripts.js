jQuery(document).ready(function($) {
    // Function to validate form fields
    function validateForm() {
        var name = $('#name').val();
        var email = $('#email').val();
        var phone = $('#phone').val();
        var message = $('#message').val();
        var location = $('#location').val();
        
        // Simple validation, you can add more checks if needed
        if (name.trim() === '' || email.trim() === '' || message.trim() === '' || location.trim() === '') {
            alert('Please fill in all required fields.');
            return false;
        }
        return true;
    }

    // AJAX request to handle form submission
    $('#incident-report-form').on('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        // Validate form fields
        if (!validateForm()) {
            return; // Exit if validation fails
        }

        // AJAX call to submit form data
        var formData = new FormData(this);
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
    });

});
