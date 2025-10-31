$( document ).ready(function() {




    console.log( "Ready!" );

    // Cache the button element
  var returnToTop = $("#returnTop");

  // Scroll event
  $(window).scroll(function() {
    if ($(this).scrollTop() > 20) { // If user scrolls down 20 pixels from the top of the page...
      returnToTop.fadeIn();  // Fade div in
    } else {
      returnToTop.fadeOut(); // Fade div out if present
    }
  });

  // Click event to scroll to the top
  returnToTop.click(function() {
    $("html, body").animate({ scrollTop: 0 }, "medium"); // Scroll to top (other options: "fast" and "slow".)
  });



























  

























  


























});