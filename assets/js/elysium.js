;(function (window) {

  'use strict';

  // Setup elysium object.
  window.Elysium = window.Elysium || {
    properties: {},
    views: {}
  };

  Elysium.views.Form = {
    submit: function () {
      var form     = $('#elysium');
      var submit   = $('#elysium_submit');
      
      form.on('submit', function (e) {
        e.preventDefault();

        $.ajax({
          url: '/bli-medlem',
          method: 'POST',
          data: $('#elysium').serialize(),
          success: function (data) {
            form.hide();
          }
        });

      });
    }
  }

  $('#elysium').validate({
    submitHandler: function(form) {
      form.submit();
    }
  });

})(window);
