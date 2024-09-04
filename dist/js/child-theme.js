/******/ (() => { // webpackBootstrap
/*!************************************!*\
  !*** ./src/scripts/child-theme.js ***!
  \************************************/
// Start your project's JS here.
// If you are rolling some jQuery you'll want to use a doc ready statement like the following.

jQuery(document).ready(function ($) {
  // Logic for maniuplating content within single-participant.php
  if ($('body').hasClass('single-participant')) {
    $('.project-icon').detach().appendTo('#project-icon-column');
  }

  // Adding click tracking for modal window, part of the QR code feature.
  // Code adapted from the story present in unity.web.asu.edu
  $('.openModalButton').click(function () {
    $(this).next().addClass('open');
  });
  $('.closeModalButton').click(function () {
    $(this).closest('.uds-modal').removeClass('open');
  });

  // Add tooltips to all pages if there's one present.
  $('[data-toggle="tooltip"]').tooltip();

  // Simple countup feature for the pre-footer area.
  $('.counter').each(function () {
    var $this = $(this),
      countTo = $this.attr('data-count');
    $({
      countNum: $this.text()
    }).animate({
      countNum: countTo
    }, {
      duration: 2000,
      easing: 'linear',
      step: function () {
        $this.text(Math.floor(this.countNum));
      },
      complete: function () {
        $this.text(this.countNum);
        //alert('finished');
      }
    });
  });
});
/******/ })()
;
//# sourceMappingURL=child-theme.js.map