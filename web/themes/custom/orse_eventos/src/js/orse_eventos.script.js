import 'popper.js';
import 'bootstrap';

(function ($, Drupal) {

  'use strict';

  Drupal.behaviors.search = {
    attach: function (context) {
      $('.search-parent > a').once("search-form-show").on("click", function () {
        if ($(this).parent().hasClass('active')) {
          $(this).parent().removeClass("active");
          console.log("hiding");
        } else {
          $(this).parent().addClass("active");
          console.log("showing");
        }
      });
      $('.search-parent > .search-box > .search-box-inner > a').once("search-form-close").on("click", function () {
        if ($(this).parent().parent().parent().hasClass('active')) {
          $(this).parent().parent().parent().removeClass("active");
          console.log("hiding");
        } else {
          $(this).parent().parent().parent().addClass("active");
          console.log("showing");
        }
      });
      $('.form-item-data-evento .ui-datepicker-trigger').once('form-item-data-evento').each(function() {
        $(this).clone().appendTo('.form-item-data-evento label.form-required');
      });
      $('.form-item-data-evento>.ui-datepicker-trigger').remove();
    }
  };

})(jQuery, Drupal);
