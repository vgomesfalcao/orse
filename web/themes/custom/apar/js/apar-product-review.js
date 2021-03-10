$(document).ready(function(){
  $('.product_preview_left .previews a').click(function(){
    var largeImage = $(this).attr('data-full');
    $('.selected').removeClass();
    $(this).addClass('selected');
    $('.full img').hide();
    $('.full img').attr('src', largeImage);
    $('.full img').fadeIn();


  }); // closing the listening on a click
  $('.full img').on('click', function(){
    var modalImage = $(this).attr('src');
    $.fancybox.open(modalImage);
  });
});