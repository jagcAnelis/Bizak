$(document).ready(function(){
    if ($.cookie('an_notification')!='accepted') {
        $('.notification_cookie').show();
    }
	$('.notification_cookie-accept').on('click', function () {
        $.cookie('an_notification', 'accepted');
        $('.notification_cookie').hide();
    });
});