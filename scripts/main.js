// Logic runs after document has loaded
$(document).ready(function() {
    // Log when document loads
    console.log( "Ready!" );
    // Check for success/error messages in URL
    var urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success')) {
      showMessage('Success! Your message has been sent.', 'success');
    } else if (urlParams.has('error')) {
      var errorMsg = urlParams.get('error');
      showMessage(errorMsg || 'An error occurred. Please try again.', 'error');
    }
    // Function to display success/error messages
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
      // Close on click
      messageDiv.on('click', function() {
        $(this).fadeOut(function() {
          $(this).remove();
          window.history.replaceState({}, document.title, window.location.pathname);
        });
      });
    }
    // Cache the button element
    var returnToTop = $("#returnTop");
  // Scroll event
  $(window).scroll(function() {
    if ($(this).scrollTop() > 20) { // If user scrolls down 20 pixels from the top of the page...
      returnToTop.fadeIn();  // Fade div in
    } 
    else {
      returnToTop.fadeOut(); // Fade div out if present
    }
  });
  // Click event to scroll to the top
  returnToTop.click(function() {
    $("html, body").animate({ scrollTop: 0 }, "medium"); // Scroll to top (other options: "fast" and "slow".)
  });

  // Contact page logic:
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
  // Add event listeners to each contact div to filter author & open the corresponding modal
  $('div[data-filter]').each(function() {
    // Add on click event listeners to all three divs
    $(this).on('click', function() {
      // Assign the value of the data filter to a variable
      const filterType = $(this).data('filter');
      // Log the filterType to the console
      console.log('Clicked on:', filterType);
      // Assign the value of filterType to an author name, interpolate into the form legend
      const authorName = filterType.charAt(0).toUpperCase() + filterType.slice(1);
      // Update the legend text with the author's name
      $('#legend').text('Submit Contact Info for ' + authorName + ' Below ⬇');
      // Set the hidden author field value
      $('#author').val(filterType);
      // Show the modal
      $('#modal-backdrop').css('display', 'flex').hide().fadeIn();
    });
  });

  // Function to reset form and clear validation
  function resetFormAndValidation() {
    var formElement = document.getElementById('contact_form');
    HTMLFormElement.prototype.reset.call(formElement);
    // Clear validation errors
    var validator = $('#contact_form').validate();
    validator.resetForm();
    // Remove valid/error classes
    $('#contact_form input, #contact_form textarea').removeClass('valid error');
  }

  // Close modal when clicking the close button
  $('#modal-close').on('click', function() {
    $('#modal-backdrop').fadeOut();
    resetFormAndValidation();
  });

  // Close modal when clicking outside the modal container (on the backdrop)
  $('#modal-backdrop').on('click', function(e) {
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