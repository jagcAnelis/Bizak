$(document).ready(function(){
	$('.anthemeblocks-homeslider').addClass('owl-carousel');
	
	$('.anthemeblocks-homeslider').each(function(i, val) {
		var anhbhl_id = '#'+$(this).attr('id');
		$(anhbhl_id).owlCarouselAnTB({
			items: $(anhbhl_id).data('items'),
			loop: $(anhbhl_id).data('loop'),
			nav: $(anhbhl_id).data('nav'),
			autoplay: $(anhbhl_id).data('autoplay'),
			navText: ['<i class="material-icons">&#xE314;</i>','<i class="material-icons">&#xE315;</i>'],
			autoplayTimeout: $(anhbhl_id).data('autoplaytimeout'),
			navContainer: anhbhl_id+' .owl-stage-outer',
			smartSpeed: $(anhbhl_id).data('smartspeed'),
			dotsClass: 'owl-dots container',
		});
		$(this).parent('.anthemeblocks_homeslider-block').addClass('initialized');
	});	
});