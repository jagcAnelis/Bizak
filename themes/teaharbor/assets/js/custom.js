
$(document).ready(function () {
	if($('#product-details .product-out-of-stock').siblings().length == 0){
		$('.tabs li a[aria-controls="product-details"]').css('display','none');
		$('.tabs li a[aria-controls="product-details"]').parent().siblings().first().children("a").addClass("active");
	}

});

/* search open */
if (!(document.getElementById('search_not_found') || document.getElementById('pagenotfound') || document.getElementById('search_header_3')))

{   

 $(document).ready(function() {
        $("body").on("click", function(h) {
	            var target2 = $(h.target);
	            function close(elem) {
	                if (target2.hasClass('open-icon') || target2.closest(elem).length !== 0 || target2.hasClass('open-icon-path')) {
                        $("#header #search_widget").show();
                        $("#header .search-icon").hide();
					} else {
                        $("#header").find(elem).slideUp(0);
                        $("#header .search-icon").show();
	                }
	            }
	            close("#search_widget");
        });
 });
        
}



/* end search open */

function lazySizes () {
	let $catimg_height;
	let imgScaling = $('.thumbnail-container-image:first img').attr('data-height') / $('.thumbnail-container-image:first img').attr('data-width');
	$('.product-thumbnail').each(function() {
		$(this).css('height',$(this).parents('.thumbnail-container-image').width()*imgScaling);
	});
	$('.product-thumbnail img').each(function() {
		$catimg_height = $(this).parents('.thumbnail-container-image').width()*imgScaling;
		$('.thumbnail-container-image').each(function() {
			$(this).css('min-height',$catimg_height);
		});
	});
}
function lazyTabsSizes () {
	let imgScaling = $('.thumbnail-container-image:first img').attr('data-height') / $('.thumbnail-container-image:first img').attr('data-width');
	let $tabimg_height = $('.tab-pane.active').find('.thumbnail-container-image').width()*imgScaling;
	$('.tab-content .thumbnail-container-image').each(function() {
		$(this).css('min-height',$tabimg_height);
	});
	$('.tab-content .product-thumbnail').each(function() {
		$(this).css('height',$tabimg_height);
	});
}

function qtyButtons() {
	$(document).on('click', '.product-qty-container .quantity-button', function() {
		var min, max,
			input = $(this).siblings('input[type="text"]');
		if (input.attr('min')) {
			min = input.attr('min');
		}
		if (input.attr('max')) {
			max = input.attr('max')
		}
		var oldValue = parseFloat(input.val());

		if ($(this).hasClass('quantity-up')) {
			if (max && (oldValue >= max)) {
				var newVal = oldValue;
			} else {
				var newVal = oldValue + 1;
			}
			input.val(newVal);
		} else {
			if (oldValue <= min) {
				var newVal = oldValue;
			} else {
				var newVal = oldValue - 1;
			}
			input.val(newVal);
		}
		let qtyTimer;
		qtyTimer = setTimeout(function () {
			clearTimeout(qtyTimer);
			input.trigger("focusout");
		}, 500);
	})
}
$(document).ready(function () {
	$(".block_newsletter form").on("keypress", function (event) {
		var keyPressed = event.keyCode || event.which;
		if (keyPressed === 13) {
			if ($('.block_newsletter form .gdpr-newsletter input').prop('checked') == false) {
				event.preventDefault();
				return false;
			}
		}
	});
	qtyButtons();
	$('#js-product-slider').each(function (i, val) {
		var anhbhl_id = '#' + $(this).attr('id');
		$(anhbhl_id).owlCarouselAnTB({
			items: 4,
			loop: false,
			nav: true,
			dots: false,
			autoplay: false,
			navText: ['<i class="material-icons">&#xE314;</i>', '<i class="material-icons">&#xE315;</i>'],
			margin: 5,
			mouseDrag: false,
			responsiveClass:true,
			responsive:{
				0:{
					items:3
				},
				992:{
					items:3
				},
				1200:{
					items:4
				}
			}
		});
	});
	// open modal
	searchfilter_btn = $('#search_filter_toggler'),
		searchfilter_modal = $('.search_filters_mobile-cover, .search_filters_mobile-modal, #search_filters_wrapper');
	searchfilter_btn.off();
	$(document).on('click', '#search_filter_toggler', function(event) {
		$('html').addClass('search_filters_mobile-open');
		searchfilter_modal.fadeIn();
	});

	// close modal
	$(document).on('click', '.search_filters_mobile-modal', function() {
		var filterselect = $('#search_filters_wrapper');
		if ($(event.target).closest(filterselect).length)
			return;
		searchfilter_modal.fadeOut(function () {
			$('html').removeClass('search_filters_mobile-open');

		});
	});
	$(document).on('click', '.search_filters_mobile-btn-close', function(event) {
		searchfilter_modal.fadeOut(function () {
			$('html').removeClass('search_filters_mobile-open');
		});
	});
	if ($(window).width() < 767) {
		$('.search-widget input[type="text"]').attr('placeholder',$('.search-widget input[type="text"]').attr('mobile-placeholder'));
	}
	var openPhotoSwipe = function() {
		var pswpElement = document.querySelectorAll('.pswp')[0];
		let items = [];
		$('.js-thumb').each(function() {
			if ($(this).hasClass('selected')) {
				items.unshift(
					{
						src: $(this).attr('data-image-large-src'),
						w: $(this).attr('data-width'),
						h: $(this).attr('data-height'),
					}
				);
			} else {
				items.push(
					{
						src: $(this).attr('data-image-large-src'),
						w: $(this).attr('data-width'),
						h: $(this).attr('data-height'),
					}
				);
			}
		});
		var options = {
			history: false,
			focus: false,
			showAnimationDuration: 0,
			hideAnimationDuration: 0,
			closeOnScroll: false,
			closeOnVerticalDrag: false,
		};
		var gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
		gallery.init();
	};
	if (document.getElementById('zoom-in-slider')) {
		$('.page-content').on('click', '.product-cover', openPhotoSwipe);
	}
	
	if($('#product-details .product-out-of-stock').siblings().length == 0){
		$('.tabs li a[aria-controls="product-details"]').css('display','none');
		$('.tabs li a[aria-controls="product-details"]').parent().siblings().first().children("a").addClass("active");
	}
	$('a[data-toggle="tab"]').on('shown.bs.tab', function () {
		lazyTabsSizes();
		if ($('.slider_product-wrapper').length) {
			slider_reload($('.tab-content .an_slick-slider.slick-initialized'));
		}
	});
	$('#search_filter_controls .ok').on('click', function() {
		lazySizes();
		if ($('.slider_product-wrapper').length) {
			slider_reload($('.an_slick-slider'));
		}
	});
	if ($.cookie('an_collection_view')) {
		$('.collection-view-btn').removeClass('active');
		$('.collection-view-btn[data-xl = '+$.cookie('an_collection_view')+']').addClass('active');
	}
	$('.product-miniature').addClass('col-lg-'+$('.collection-view-btn.active').attr('data-xl'));

	$(document).on('click', '.collection-view-btn', function() {
		$.cookie('an_collection_view', $(this).attr('data-xl'));
		$('.collection-view-btn').removeClass('active');
		$(this).addClass('active');
		$('.product-miniature').removeClass('col-lg-12 col-lg-6 col-lg-4 col-lg-3');
		$('.product-miniature').addClass('col-lg-'+$('.collection-view-btn.active').attr('data-xl'));
		lazySizes();
		lazyTabsSizes();
		if ($('.slider_product-wrapper').length) {
			slider_reload($('.an_slick-slider'));
		}
		let view_cols = $('.collection-view-btn.active').attr('data-xl');
		switch (view_cols) {
			case '3':
				$('.product-miniature img').each(function(){
					if ($(this).attr('data-lazy-gif')) {
						$(this).attr('src',$(this).attr('data-lazy-gif'));
						$(this).removeClass('b-loaded b-initialized');
						$(this).attr('data-src',$(this).attr('data-catalog-small'));
					} else {
						$(this).attr('src',$(this).attr('data-catalog-small'));
					}
				});
				break;
			case '4':
				$('.product-miniature img').each(function(){
					if ($(this).attr('data-lazy-gif')) {
						$(this).attr('src',$(this).attr('data-lazy-gif'));
						$(this).removeClass('b-loaded b-initialized');
						$(this).attr('data-src',$(this).attr('data-catalog-medium'));
					} else {
						$(this).attr('src',$(this).attr('data-catalog-medium'));
					}
				});
				break;
			case '6':
				$('.product-miniature img').each(function(){
					if ($(this).attr('data-lazy-gif')) {
						$(this).attr('src',$(this).attr('data-lazy-gif'));
						$(this).removeClass('b-loaded b-initialized');
						$(this).attr('data-src',$(this).attr('data-catalog-large'));
					} else {
						$(this).attr('src',$(this).attr('data-catalog-large'));
					}
				});
				break;
			default:
				$('.product-miniature img').each(function(){
					if ($(this).attr('data-lazy-gif')) {
						$(this).attr('src',$(this).attr('data-lazy-gif'));
						$(this).removeClass('b-loaded b-initialized');
						$(this).attr('data-src',$(this).attr('data-catalog-medium'));
					} else {
						$(this).attr('src',$(this).attr('data-catalog-medium'));
					}
				});
		}
	});
	lazySizes();
	lazyTabsSizes();

	$(document).ajaxSuccess(function() {
		setTimeout(function () {
			if ($.cookie('an_collection_view')) {
				$('.collection-view-btn').removeClass('active');
				$('.collection-view-btn[data-xl = '+$.cookie('an_collection_view')+']').addClass('active');
			}
			$('.product-miniature').addClass('col-lg-'+$('.collection-view-btn.active').attr('data-xl'));
			//$('.collection-view-btn.active').trigger('click');
		}, 100);
		lazySizes();
		lazyTabsSizes();

	});
});
$( document ).ajaxStop(function() {
	// open modal
	searchfilter_btn = $('#search_filter_toggler'),
		searchfilter_modal = $('.search_filters_mobile-cover, .search_filters_mobile-modal, #search_filters_wrapper');
	searchfilter_btn.off();
	$(document).on('click', '#search_filter_toggler', function(event) {
		$('html').addClass('search_filters_mobile-open');
		searchfilter_modal.fadeIn();
	});

	// close modal
	$(document).on('click', '.search_filters_mobile-modal', function() {
		var filterselect = $('#search_filters_wrapper');
		if ($(event.target).closest(filterselect).length)
			return;
		searchfilter_modal.fadeOut(function () {
			$('html').removeClass('search_filters_mobile-open');

		});
	});
	$(document).on('click', '.search_filters_mobile-btn-close', function(event) {
		searchfilter_modal.fadeOut(function () {
			$('html').removeClass('search_filters_mobile-open');
		});
	});
	setTimeout(function () {
		$('#js-product-slider').each(function (i, val) {
			var anhbhl_id = '#' + $(this).attr('id');
			$(anhbhl_id).owlCarouselAnTB({
				items: 4,
				loop: false,
				nav: true,
				autoplay: false,
				navText: ['<i class="material-icons">&#xE314;</i>', '<i class="material-icons">&#xE315;</i>'],
				margin: 5,
				mouseDrag: false,
				responsiveClass:true,
				responsive:{
					0:{
						items:3
					},
					992:{
						items:3
					},
					1200:{
						items:4
					}
				}
			});
		});
	}, 500);
	if ($('.modal-body .product-images .thumb-container').length > 4) {
		$('.modal-body .product-images').addClass('product-images-scroll');
	}
	setTimeout(function () {
		if ($.cookie('an_collection_view')) {
			$('.collection-view-btn').removeClass('active');
			$('.collection-view-btn[data-xl = '+$.cookie('an_collection_view')+']').addClass('active');
		}
		$('.product-miniature').addClass('col-lg-'+$('.collection-view-btn.active').attr('data-xl'));
		//$('.collection-view-btn.active').trigger('click');
        lazySizes();
        lazyTabsSizes();
	}, 100);
	lazySizes();
	lazyTabsSizes();
});

$(window).on('resize', function(){
	if ($(window).width() < 767) {
		$('.search-widget input[type="text"]').attr('placeholder',$('.search-widget input[type="text"]').attr('mobile-placeholder'));
	} else {
		$('.search-widget input[type="text"]').attr('placeholder',$('.search-widget input[type="text"]').attr('desktop-placeholder'));
	}

	lazySizes();
	lazyTabsSizes();
});


/* nav hidden */
var nav = $('.header-nav'),
    conf = $('.site_configuration');
    conf.on('click', function () {
        nav.toggleClass('hidden-nav');
});

    $(document).click(function (e){
		if (!nav.is(e.target) && nav.has(e.target).length === 0 && !conf.is(e.target))
        {
			nav.removeClass('hidden-nav');
        }
    });
/* end nav hidden */