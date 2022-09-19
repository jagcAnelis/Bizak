$(document).ready(function(){
	// open modal
    var wrap = $('#wrapper'),
    btn = $('.open-modal-btn'),
    modal = $('.sg-cover, .sg-modal, .an_sizeguide');

    btn.on('click', function(event) {
        $('html').addClass('sg-open');
        modal.fadeIn();
    });

    // close modal
    $('.sg-modal').click(function() {
        var select = $('.an_sizeguide');
        if ($(event.target).closest(select).length)
        return;
        modal.fadeOut(function () {
            $('html').removeClass('sg-open');
        });
        
    });
    $('.sg-btn-close').on('click', function(event) {
        modal.fadeOut(function () {
            $('html').removeClass('sg-open');
        });
    });
    
});