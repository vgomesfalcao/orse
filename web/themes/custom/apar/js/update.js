jQuery(document).ready(function($) { 
   $('.apar-search-header form input[type=submit]').after('<i aria-hidden="true" class="fa fa-search"></i>');
   $('.apar-services-wh-bl .text-box:eq(0)').removeClass('white');
   $('.apar-services-wh-bl .text-box:eq(0)').addClass('section-secondary');
   $('.apar-services-wh-bl .section-secondary .icon-plain-msmall').addClass('text-white');
   $('.apar-services-wh-bl .section-secondary .icon-plain-msmall span').addClass('text-white');
   $('.apar-services-wh-bl .section-secondary .text-box-right .raleway').removeClass('title');
   $('.apar-services-wh-bl .section-secondary .text-box-right .raleway').addClass('text-white');
   $('.apar-services-wh-bl .section-secondary .text-box-right .padding-top-1').addClass('text-white');
   $('.apar-bl-futures .apar-bg-color .text-center').addClass('text-box');
   $('.apar-bl-futures .apar-bg-white .text-center').addClass('text-box more-height white');
   $('.apar-bl-futures .apar-bg-white .font-weight-6').removeClass('text-white');
   $('.apar-bl-futures .apar-bg-white p').removeClass('text-sm');
   $('.apar-bl-futures .apar-bg-white .btn-circle').addClass('primary');
   $('.pd-list-item input').addClass('btn btn-border light btn-small');
   $('.apar-page-sitemap .sitemap-menu').addClass('sitemap');
   var baseURL = drupalSettings.path.themeUrl;
   if($('#style-customizer #apr-color-pr').length){
      $('#style-customizer #apr-color-pr > li > a').click(function(){
         if($('#switch-colors').length){
            $('#switch-colors').attr('href',baseURL + '/css/color-primary/' + $(this).data('color') + '.css');
         }
      });
   }
   if($('#style-customizer #apr-color-sc').length){
      $('#style-customizer #apr-color-sc > li > a').click(function(){
         if($('#switch-colors-2').length){
            $('#switch-colors-2').attr('href',baseURL + '/css/color-secondary/' + $(this).data('color') + '.css');
         }
      });
   }
   if($('#style-customizer').length){
      $('#sc-layout-type-boxed').click(function(){
         $('body').attr("class",'apar-boxed');
      });
      $('#sc-layout-type-wide').click(function(){
         $('body').removeClass('apar-boxed');
      });
   }
});
