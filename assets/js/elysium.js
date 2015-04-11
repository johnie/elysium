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

  $(function () {
    $.ajax({
      url: kruger.ajaxurl,
      method: 'post',
      cache: false,
      data: {
        'action': 'elysium_kruger',
        'personnr': '920408-2276',
        'nonce': kruger.nonce,
      },
      success: function(res) {
        console.log(res);
      }
    });
  });

})(window);
