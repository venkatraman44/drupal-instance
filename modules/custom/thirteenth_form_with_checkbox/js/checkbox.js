
(function ($, Drupal) {
  Drupal.behaviors.myFormBehavior = {
    attach: function (context, settings) {
      var $noLastName = $('#no-last-name');
      var $lastName = $('#last-name-wrapper');
      if ($noLastName.is(':checked')) {
        $lastName.hide();
      }

      $noLastName.on('change', function () {
        if ($(this).is(':checked')) {
          $lastName.hide();
        } else {
          $lastName.show();
        }
      });
    }
  };
})(jQuery, Drupal);
