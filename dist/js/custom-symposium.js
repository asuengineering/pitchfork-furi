/******/ (() => { // webpackBootstrap
/*!*****************************************!*\
  !*** ./src/scripts/custom-symposium.js ***!
  \*****************************************/
// Custom JS file for FURI Symposium

jQuery(document).ready(function ($) {
  if ($('body').hasClass('page-template-symposium')) {
    // // Sort the participants names prior to initializing fancy select box.
    // var participant_options = $('#filter-participant option');
    // participant_options.sort(function (a, b) {
    // 	if (a.text > b.text) return 1;
    // 	else if (a.text < b.text) return -1;
    // 	else return 0;
    // });

    // // Sort the project names as well.
    // var title_options = $('#filter-titles option');
    // title_options.sort(function (a, b) {
    // 	if (a.text > b.text) return 1;
    // 	else if (a.text < b.text) return -1;
    // 	else return 0;
    // });

    // $('#filter-participant').empty().append(participant_options);
    // $('#filter-titles').empty().append(title_options);

    var $grid = $('#symposium-grid').isotope({
      // options
      itemSelector: '.grid-item',
      layoutMode: 'fitRows'
    });
    var iso = $grid.data('isotope');
    var $filterCount = $('.filter-count');

    // Recalculate filter string on select box change.
    $('.filter-container select').on('change', function () {
      $filter = '';

      // grab value from the control that just changed.
      var $activeSelect = this.id;
      $value = $('#' + $activeSelect).val() || [];
      $filter = $value.join();

      // Did the control change result in a "clear selection?"
      if (!$filter) {
        // Enable all of the select boxes.
        $('.filter-container select').prop('disabled', false);
        // Enable the radio buttons, set to first option on each.
        $('input[name="researchThemeRadio"]').prop('disabled', false).filter('[value=""]').prop('checked', true);
        $('input[name="presentationTypeRadio"]').prop('disabled', false).filter('[value=""]').prop('checked', true);
      } else {
        // reset and disable all the selects except for the one that just changed.
        $('.filter-container select').not('#' + $activeSelect).val('').prop('disabled', true);
        // reset and disable the radio controls
        $('input[name="researchThemeRadio"]').prop('disabled', true).filter('[value=""]').prop('checked', true);
        $('input[name="presentationTypeRadio"]').prop('disabled', true).filter('[value=""]').prop('checked', true);
      }
      $('#symposium-grid').isotope({
        filter: $filter
      });
    });

    // Bind filter on select change. Combine results of all select boxes within .filter-container.
    $('.filter-container input').on('change', function () {
      $research = $('input[name="researchThemeRadio"]:checked').val();
      $presentation = $('input[name="presentationTypeRadio"]:checked').val();
      $filter = $research + $presentation;

      // Did the most recent change result in an empty filter?
      if (!$filter) {
        // Enable all of the select boxes.
        $('.filter-container select').prop('disabled', false);
      } else {
        // Disable and reset all of the select boxes.
        $('.filter-container select').val('').prop('disabled', true);
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
      $('.filter-container select').val('').prop('disabled', false);

      // Enable the radio buttons, set to first option on each.
      $('input[name="researchThemeRadio"]').prop('disabled', false).filter('[value=""]').prop('checked', true);
      $('input[name="presentationTypeRadio"]').prop('disabled', false).filter('[value=""]').prop('checked', true);
      $('#symposium-grid').isotope({
        filter: ''
      });
      updateFilterCount();
    });

    // Text for "Showing XX of YY items."
    function updateFilterCount() {
      if (iso.filteredItems.length == iso.items.length) {
        $filterCount.text(iso.items.length + ' projects');
      } else {
        $filterCount.text('Displaying ' + iso.filteredItems.length + ' of a total of ' + iso.items.length + ' projects.');
      }
    }

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