/******/ (() => { // webpackBootstrap
/*!*****************************************!*\
  !*** ./src/scripts/custom-symposium.js ***!
  \*****************************************/
// Custom JS file for FURI Symposium

jQuery(document).ready(function ($) {
  if ($('body').hasClass('page-template-symposium')) {
    // Relocate the filtering grid on mobile
    var $sidebarContent = $('#filter-wrap');
    var $desktopContainer = $('#filter-sidebar');
    var $offcanvasContainer = $('#filter-offcanvas-body');
    var inOffcanvas = false;
    function moveSidebarContent() {
      var isMobile = window.innerWidth < 992;
      if (isMobile && !inOffcanvas) {
        $sidebarContent.appendTo($offcanvasContainer);
        inOffcanvas = true;
      } else if (!isMobile && inOffcanvas) {
        $sidebarContent.appendTo($desktopContainer);
        inOffcanvas = false;
      }
    }

    // Initial move on page load
    moveSidebarContent();

    // Move again on window resize
    $(window).on('resize', function () {
      moveSidebarContent();
    });

    // Initalize isotope.
    var $grid = $('#symposium-grid').isotope({
      // options
      itemSelector: '.grid-item',
      layoutMode: 'fitRows'
    });
    var iso = $grid.data('isotope');
    var $filterCount = $('.filter-count');
    var qsRegex;

    // Debounce function, specifically for search field filtering.
    // Prevents filtering from happening every millisecond.
    function debounce(fn, threshold) {
      var timeout;
      threshold = threshold || 100;
      return function debounced() {
        clearTimeout(timeout);
        var args = arguments;
        var _this = this;
        function delayed() {
          fn.apply(_this, args);
        }
        timeout = setTimeout(delayed, threshold);
      };
    }

    // Search field filtering. Triggered on keyup from search box
    // Example: https://codepen.io/desandro/pen/gOrVQa
    var $quicksearch = $('.quicksearch').keyup(debounce(function () {
      qsRegex = new RegExp($quicksearch.val(), 'gi');
      $('#symposium-grid').isotope({
        filter: function () {
          return qsRegex ? $(this).text().match(qsRegex) : true;
        }
      });

      // If the search val() is not empty, reset and disable all the other controls.
      // Otherwise, trigger the click event on the reset button to reset everything.
      if ($quicksearch.val()) {
        $('.filter-group select').val('').prop('disabled', true);
        $('input[name="researchThemeRadio"]').prop('disabled', true);
        $('input[name="presentationTypeRadio"]').prop('disabled', true);

        // disable the show filters button as well for mobile
        $('button#show-offcanvas').prop('disabled', true);
      } else {
        $('#filter-reset').click();
      }
      updateFilterCount();
    }, 200));

    // Recalculate filter string on select box change.
    $('.filter-group select').on('change', function () {
      console.log('Select listener.');
      $filter = '';

      // grab value from the control that just changed.
      var $activeSelect = this.id;
      $value = $('#' + $activeSelect).val() || [];
      $filter = $value;

      // reset and disable all the selects except for the one that just changed.
      $('.filter-group select').not('#' + $activeSelect).val('').prop('disabled', true);

      // disable the radio controls
      $('input[name="researchThemeRadio"]').prop('disabled', true);
      $('input[name="presentationTypeRadio"]').prop('disabled', true);

      // disable and reset the search filter
      $('input#keyword-filter').val('').prop("disabled", true);
      $('input#keyword-filter').attr("placeholder", "-- reset filters to enable --");
      $('#symposium-grid').isotope({
        filter: $filter
      });
      updateFilterCount();
    });

    // Bind filter on select change. Combine results of all select boxes within .filter-group.
    $('.filter-group input').on('change', function () {
      $research = $('input[name="researchThemeRadio"]:checked').val();
      $presentation = $('input[name="presentationTypeRadio"]:checked').val();
      $filter = $research + $presentation;

      // Did the most recent change result in an empty filter?
      if (!$filter) {
        // Enable all of the select boxes.
        $('.filter-group select').prop('disabled', false);

        // enable and reset the search filter
        $('input#keyword-filter').val('').prop("disabled", false);
        $('input#keyword-filter').attr("placeholder", "Type a keyword");
      } else {
        // Disable and reset all of the select boxes.
        $('.filter-group select').val('').prop('disabled', true);

        // disable and reset the search filter
        $('input#keyword-filter').val('').prop("disabled", true);
        $('input#keyword-filter').attr("placeholder", "-- reset filters to enable --");
      }
      $('#symposium-grid').isotope({
        filter: $filter
      });
      updateFilterCount();
    });

    // Shuffle. Resorts actively displayed collection of cards.
    $('#filter-shuffle').on('click', function () {
      $('#symposium-grid').isotope('shuffle');
    });

    // Reset all filters.
    $('#filter-reset').on('click', function () {
      // Enable and reset all of the select boxes.
      $('.filter-group select').val('').prop('disabled', false);

      // Enable the radio buttons, set to first option on each.
      $('input[name="researchThemeRadio"]').prop('disabled', false).filter('[value=""]').prop('checked', true);
      $('input[name="presentationTypeRadio"]').prop('disabled', false).filter('[value=""]').prop('checked', true);

      // Enable the search filter field
      $('input#keyword-filter').val('').prop("disabled", false);
      $('input#keyword-filter').attr("placeholder", "Type a keyword");

      // enable the show filters button, occasionally disabled on mobile.
      $('button#show-offcanvas').prop('disabled', false);
      $('#symposium-grid').isotope({
        filter: ''
      });
      updateFilterCount();
    });

    // Text for "Showing XX of YY items."
    function updateFilterCount() {
      if (iso.filteredItems.length == iso.items.length) {
        $filterCount.text('Displaying ' + iso.items.length + ' projects.');
      } else {
        $filterCount.text('Displaying ' + iso.filteredItems.length + ' of a total of ' + iso.items.length + ' projects.');
      }
    }

    // Initial set of project count
    updateFilterCount();

    // Event handler for the button click inside .card-ranking elements
    $(".card-ranking button.btn-expand").on("click", function () {
      // Toggle the "active" class for the .info-layer element within the same .card-ranking
      $(this).closest(".card-ranking").find(".info-layer").toggleClass("active");
    });
  }
});
/******/ })()
;
//# sourceMappingURL=custom-symposium.js.map