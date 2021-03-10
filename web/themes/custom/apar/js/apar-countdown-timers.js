$('#clock').countdown('2017/10/10').on('update.countdown', function(event) {
   var $this = $(this).html(event.strftime(''
     + '<p><span>%-w</span> <b>week%!w</b></p> '
     + '<p><span>%-d</span> <b>day%!d</b></p> '
     + '<p><span>%H</span> <b>hr </b></p>'
     + '<p><span>%M</span> <b>min</b></p> '
     + '<p><span>%S</span> <b>sec</b></p>'));
});
$('#clock2').countdown('2017/8/8').on('update.countdown', function(event) {
   var $this = $(this).html(event.strftime(''
     + '<p><span>%-w</span> <b>week%!w</b></p> '
     + '<p><span>%-d</span> <b>day%!d</b></p> '
     + '<p><span>%H</span> <b>hr </b></p>'
     + '<p><span>%M</span> <b>min</b></p> '
     + '<p><span>%S</span> <b>sec</b></p>'));
});
$('#clock3').countdown('2017/12/12').on('update.countdown', function(event) {
   var $this = $(this).html(event.strftime(''
     + '<p><span>%-w</span> <b>week%!w</b></p> '
     + '<p><span>%-d</span> <b>day%!d</b></p> '
     + '<p><span>%H</span> <b>hr </b></p>'
     + '<p><span>%M</span> <b>min</b></p> '
     + '<p><span>%S</span> <b>sec</b></p>'));
});
$('#clock4').countdown('2017/12/12').on('update.countdown', function(event) {
   var $this = $(this).html(event.strftime(''
     + '<p><span>%-w</span> <b>week%!w</b></p> '
     + '<p><span>%-d</span> <b>day%!d</b></p> '
     + '<p><span>%H</span> <b>hr </b></p>'
     + '<p><span>%M</span> <b>min</b></p> '
     + '<p><span>%S</span> <b>sec</b></p>'));
});
$('#clock5').countdown('2017/12/12').on('update.countdown', function(event) {
   var $this = $(this).html(event.strftime(''
     + '<p><span>%-w</span> <b>week%!w</b></p> '
     + '<p><span>%-d</span> <b>day%!d</b></p> '
     + '<p><span>%H</span> <b>hr </b></p>'
     + '<p><span>%M</span> <b>min</b></p> '
     + '<p><span>%S</span> <b>sec</b></p>'));
});
$('#clock6').countdown('2018/2/3').on('update.countdown', function(event) {
   var $this = $(this).html(event.strftime(''
     + '<p><span>%-w</span> <b>week%!w</b></p> '
     + '<p><span>%-d</span> <b>day%!d</b></p> '
     + '<p><span>%H</span> <b>hr </b></p>'
     + '<p><span>%M</span> <b>min</b></p> '
     + '<p><span>%S</span> <b>sec</b></p>'));
});
$('#clock7').countdown('2018/2/3').on('update.countdown', function(event) {
   var $this = $(this).html(event.strftime(''
     + '<p><span>%-w</span> <b>week%!w</b></p> '
     + '<p><span>%-d</span> <b>day%!d</b></p> '
     + '<p><span>%H</span> <b>hr </b></p>'
     + '<p><span>%M</span> <b>min</b></p> '
     + '<p><span>%S</span> <b>sec</b></p>'));
});
$('#clock8').countdown('2018/5/6').on('update.countdown', function(event) {
   var $this = $(this).html(event.strftime(''
     + '<p><span>%-w</span> <b>week%!w</b></p> '
     + '<p><span>%-d</span> <b>day%!d</b></p> '
     + '<p><span>%H</span> <b>hr </b></p>'
     + '<p><span>%M</span> <b>min</b></p> '
     + '<p><span>%S</span> <b>sec</b></p>'));
});

jQuery(document).ready(function( $ ) {
    $('.counter').counterUp({
        delay: 10,
        time: 5000
    });
});