// Logic runs after document has loaded
$(document).ready(function() {

  // MISC PAGE LOGIC:
  // Log message once document loads
  console.log( "Ready!" );

  // Hide and fade in body on reload - on each page
  $('body').hide().fadeIn(500);
  
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

  // MAIN NAVBAR HAMBURGER MENU:
  var navHamburger = $("#navHamburger");
  var navMenu = $("#navMenu");

  // Toggle main navigation menu
  navHamburger.click(function(e) {
    e.stopPropagation();
    $(this).toggleClass("active");
    navMenu.toggleClass("active");
  });

  // Handle dropdown in mobile menu
  $(".dropdown .dropButton").click(function(e) {
    e.preventDefault();
    e.stopPropagation();
    $(this).parent().toggleClass("active");
  });

  // Close main nav menu when clicking outside
  $(document).click(function(e) {
    if (!$(e.target).closest('#nav').length) {
      navHamburger.removeClass("active");
      navMenu.removeClass("active");
      $(".dropdown").removeClass("active");
    }
  });

  // Close main nav menu when clicking a link
  $("#nav a").click(function() {
    navHamburger.removeClass("active");
    navMenu.removeClass("active");
    $(".dropdown").removeClass("active");
  });

  // FOOTER HAMBURGER MENU:
  var footerHamburger = $("#footerHamburger");
  var footerNav = $("#footerNav");

  // Toggle footer navigation menu
  footerHamburger.click(function(e) {
    e.stopPropagation();
    $(this).toggleClass("active");
    footerNav.toggleClass("active");
  });

  // Close menu when clicking outside
  $(document).click(function(e) {
    if (!$(e.target).closest('.footer-nav, .footer-hamburger').length) {
      footerHamburger.removeClass("active");
      footerNav.removeClass("active");
    }
  });

  // Close menu when clicking a link
  $(".footer-nav-link").click(function() {
    footerHamburger.removeClass("active");
    footerNav.removeClass("active");
  });

  // DYNAMIC MODULE LOADING:
  // Detect current page and load appropriate page-specific module
  var currentPath = window.location.pathname;
  var pageName = currentPath.substring(currentPath.lastIndexOf('/') + 1).replace('.html', '');

  // Map of page names to their corresponding script files
  var pageModules = {
    'contact': '../scripts/frontend/contact.js',
    'about': '../scripts/frontend/about.js',
    'works': '../scripts/frontend/works.js'
  };

  // Load the appropriate module if it exists for this page
  if (pageModules[pageName]) {
    var script = document.createElement('script');
    script.src = pageModules[pageName];
    script.type = 'text/javascript';
    document.head.appendChild(script);
    console.log('Loaded module:', pageModules[pageName]);
  }
});