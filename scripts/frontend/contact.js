// CONTACT PAGE LOGIC
$(document).ready(function() {

  // Error handling on PHP email confirmation - will change url params to inform send-email.php
  var urlParams = new URLSearchParams(window.location.search);
  // Check for success/error messages in URL
  if (urlParams.has('success')) {
    showMessage('Success! Your message has been sent.', 'success');
  }
  else if (urlParams.has('error')) {
    var errorMsg = urlParams.get('error');
    showMessage(errorMsg || 'An error occurred. Please try again.', 'error');
  }

  // Function to display success/error messages - email confirmation
  function showMessage(message, type) {
    var messageDiv = $('<div></div>')
      .addClass('alert-message')
      .addClass(type === 'success' ? 'alert-success' : 'alert-error')
      .text(message);
    $('body').prepend(messageDiv);

    // Fade in the message
    messageDiv.fadeIn();

    // Auto-hide after 5 seconds
    setTimeout(function() {
      messageDiv.fadeOut(function() {
        $(this).remove();
        // Clean up URL
        window.history.replaceState({}, document.title, window.location.pathname);
      });
    }, 5000);

    // Close alert message when clicked
    messageDiv.on('click', function() {
      // Fade out the messageDiv
      $(this).fadeOut(function() {
        $(this).remove();
        window.history.replaceState({}, document.title, window.location.pathname);
      });
    });
  }

  // CSS effects for data filter divs - author selection:
  // Add hover effects to each contact div
  $('div[data-filter]').hover(
    function() {
      // Hover in - scale up
      $(this).css({
        'transform': 'scale(1.1)',
        'transition': 'transform 0.5s ease'
      });
    },
    function() {
      // Hover out - scale back to normal
      $(this).css({
        'transform': 'scale(1)',
        'transition': 'transform 0.5s ease'
      });
    }
  );

  // Data filtering for author selection:
  // Add event listeners to each contact div to filter author & open the corresponding modal
  $('div[data-filter]').each(function() {
    // Add on click event listeners to all three divs
    $(this).on('click', function() {
      // Assign the value of the data filter to a variable
      const filterType = $(this).data('filter');
      // Log the filterType to the console
      console.log('Clicked on:', filterType);
      // Capitalize the first letter of filterType for interpolation and display
      const authorName = filterType.charAt(0).toUpperCase() + filterType.slice(1); // This line is necessary to change filterType (all lowercase) to a full capitalized name for interpolating into the legend text
      // Update the legend text with the author's name
      $('#legend').text('Submit Contact Info for ' + authorName + ' Below ⬇');
      // Set the hidden author field value
      $('#author').val(filterType);
      // Show the modal
      $('#modal-backdrop').css('display', 'flex').hide().fadeIn();
    });
  });

  // Function to reset form and clear validation - contact form:
  function resetFormAndValidation() {
    // Select the form element
    var formElement = document.getElementById('contact_form');
    // Clear all form fields using a native constructor
    HTMLFormElement.prototype.reset.call(formElement);
    // Clear validation errors
    var validator = $('#contact_form').validate();
    // Reset the validator state
    validator.resetForm();
    // Remove valid/error classes
    $('#contact_form input, #contact_form textarea').removeClass('valid error');
  }

  // Close modal when clicking the close button
  $('#modal-close').on('click', function() {
    // Fade out the modal on click
    $('#modal-backdrop').fadeOut();
    // Call resetFormAndValidation
    resetFormAndValidation();
  });

  // Close modal when clicking outside the modal container (on the backdrop)
  $('#modal-backdrop').on('click', function(e) {
    // Check to see if click falls on the backdrop
    if (e.target === this) {
      $(this).fadeOut();
      resetFormAndValidation();
    }
  });

  // Clear all form fields when reset form is clicked
  $('#reset').on('click', function() {
    resetFormAndValidation();
  });

  // Hide modal backdrop on page load
  $('#modal-backdrop').hide();

  // jQuery Validation for contact form
  $('#contact_form').validate({
    rules: {
      full_name: {
        required: true,
        minlength: 2,
        maxlength: 100
      },
      phone: {
        required: true,
        phoneUS: true
      },
      email: {
        required: true,
        email: true
      },
      message: {
        required: true,
        minlength: 10,
        maxlength: 1000
      }
    },
    messages: {
      full_name: {
        required: "Please enter your full name",
        minlength: "Name must be at least 2 characters",
        maxlength: "Name cannot exceed 100 characters"
      },
      phone: {
        required: "Please enter your phone number",
        phoneUS: "Please enter a valid US phone number"
      },
      email: {
        required: "Please enter your email address",
        email: "Please enter a valid email address"
      },
      message: {
        required: "Please enter a message",
        minlength: "Message must be at least 10 characters",
        maxlength: "Message cannot exceed 1000 characters"
      }
    },
    errorElement: 'span',
    errorClass: 'error',
    validClass: 'valid',
    errorPlacement: function(error, element) {
      // Error will render as a sibling element
      error.insertAfter(element);
    },
    highlight: function(element) {
      $(element).addClass('error').removeClass('valid');
    },
    unhighlight: function(element) {
      $(element).removeClass('error').addClass('valid');
    },
    submitHandler: function(form) {
      // Form is valid, submit it
      form.submit();
    }
  });
});
