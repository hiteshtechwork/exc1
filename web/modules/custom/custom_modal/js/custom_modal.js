(function (Drupal, $) {
  Drupal.behaviors.customModal = {
    attach: function (context, settings) {
      $(".use-ajax")
        .once("custom-modal")
        .on("click", function (e) {
          e.preventDefault();
          var url = $(this).attr("href");
          Drupal.ajax({ url: url }).execute();
        });
    },
  };
})(Drupal, jQuery);
