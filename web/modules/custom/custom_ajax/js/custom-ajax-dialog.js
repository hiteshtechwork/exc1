alert("hello");

(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.customAjaxDialog = {
    attach: function (context, settings) {
      // Override the dialog's open function to prevent automatic opening.
      Drupal.dialog.prototype.open = function () {};

      // Bind a click event to the overlay to close the modal.
      $(".ui-widget-overlay")
        .once("custom-modal")
        .on("click", function () {
          $(this).siblings(".ui-dialog").dialog("close");
        });
    },
  };
})(jQuery, Drupal, drupalSettings);
