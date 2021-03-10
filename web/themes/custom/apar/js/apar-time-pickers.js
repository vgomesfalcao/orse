$(function () {
    $('#datetimepicker4').datetimepicker();
    $('#datetimepicker3').datetimepicker();
    $('#datetimepicker1').datetimepicker();
	autoclose: true;
	$('#datetimepicker5').datetimepicker({
	    defaultDate: "11/1/2016",
	    disabledDates: [
	        moment("12/25/2016"),
	        new Date(2016, 11 - 1, 21),
	        "07/22/2016 00:53"
	    ]
	});
	$('#datetimepicker2').datetimepicker({
	    format: 'LT'
	});
	$('#datetimepicker9').datetimepicker({
	    viewMode: 'years'
	});
	$(function () {
	    $('#datetimepicker12').datetimepicker({
	        inline: true,
	    });
	});
	$(function () {
	    $('#datetimepicker13').datetimepicker({
	        inline: true,
	    });
	});
});
