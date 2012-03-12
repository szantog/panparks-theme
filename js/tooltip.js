/**
 *  @file
 *  Set hidden values in donate forms
 */

(function ($) {

  Drupal.behaviors.panparks = {
    attach: function (context, settings) {
      $('.menu-766 a').bt({
        hoverIntentOpts: {
          interval: 0,
          timeout: 20000
        },

        showTip: function(box){
          $(box).fadeIn(500);
        },
        hideTip: function(box, callback){
          $(box).animate({opacity: 0}, 500, callback);
        },

        shrinkToFit: true,
        contentSelector: "$(this).attr('title')",
        cssStyles: {color: 'white', lineHeight: '14px', fontWeight: 'bold'},
        padding: '10px 20px',
        cornerRadius: 6,
        spikeLength: 12,
        spikeGirth: 8,
        strokeWidth: 1,
        strokeStyle: 'rgba(161, 161, 161, .6)',
        fill: 'rgba(240, 78, 42, .9)',
        positions: ['top'],
        trigger: 'none'
      });

      $("#buckaroo-payment-form .form-submit", context).click(function(c) {
        var date = Date.parse($("#birth_date_display").val());
        var old = Date.parse('t - 18 y');

        if (date > old) {
          Buckaroo.error('You can donate if you are over 18 years old.', $("#birth_date_display"))
          c.preventDefault();
        }
        $("#birth_date").val(date.toString("dd-MM-yyyy"));
        $("#birth_date_display").val(date.toString("dd-MM-yyyy"));
      });

      $(".menu-766 a", context).mouseenter(function () {
        $(this).btOn();
        //alert($(this).val());
     });

      $("#header-content", context).mouseout(function () {
        $(".menu-766 a").btOff();
        //alert($(this).val());
     });
     $("#is_opt_out").attr('checked', true);

      // Add required class to buckaroo birth date. It's rendered as required,
      //but CiviCRM doesn't add class to them.
     $("#birth_date_display", context).addClass('required');

    }
  };

}(jQuery));