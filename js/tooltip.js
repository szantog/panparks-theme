/**
 *  @file
 *  Set hidden values in donate forms
 */

(function ($) {

  Drupal.behaviors.panparks_donate = {
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
        cornerRadius: 8,
        spikeLength: 15,
        spikeGirth: 8,
        strokeWidth: 1,
        strokeStyle: 'rgba(161, 161, 161, .6)',
        fill: 'rgba(240, 78, 42, .9)',
        positions: ['bottom'],
        trigger: 'none'
      });

      $(".menu-766 a", context).mouseenter(function () {
        $(this).btOn();
        //alert($(this).val());
     });

      $("#header-content", context).mouseout(function () {
        $(".menu-766 a").btOff();
        //alert($(this).val());
     });
    }
  };

}(jQuery));