$(document).ready(function(){
	$('.anthemeblocks-reviews').each(function(i, val) {
		var anhbhl_id = '#'+$(this).attr('id');
		$(anhbhl_id).owlCarouselAnTB({
			items: "1",
			loop: $(anhbhl_id).data('loop'),
			nav: $(anhbhl_id).data('nav'),
			dots: $(anhbhl_id).data('dots'),
			autoplay: $(anhbhl_id).data('autoplay'),
			navText: ['<i class="material-icons">&#xE314;</i>','<i class="material-icons">&#xE315;</i>'],
			autoplayTimeout: $(anhbhl_id).data('autoplaytimeout'),
			navContainer: anhbhl_id+' .owl-stage-outer',
				//	animateOut: 'slideOutDown',
		//    animateIn: 'rotateIn',
		});
	});	
});