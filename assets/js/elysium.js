;(function (window) {

  'use strict';

  // Setup elysium object.
  window.Elysium = window.Elysium || {
    properties: {},
    views: {}
  };

  Elysium.views.Form = {
    submit: function () {
      var form     = jQuery('#elysium');
      var submit   = jQuery('#elysium_submit');
      
      form.on('submit', function (e) {
        e.preventDefault();

        $.ajax({
          url: '/bli-medlem',
          method: 'POST',
          data: jQuery('#elysium').serialize(),
          success: function (data) {
            form.hide();
          }
        });

      });
    }
  }

  Elysium.views.Form.submit();

})(window);
