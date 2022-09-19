/**
 * 2021 Anvanto
 *
 * NOTICE OF LICENSE
 *
 * This file is not open source! Each license that you purchased is only available for 1 wesite only.
 * If you want to use this file on more websites (or projects), you need to purchase additional licenses. 
 * You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
 *
 *  @author Anvanto <anvantoco@gmail.com>
 *  @copyright  2021 Anvanto
 *  @license    Valid for 1 website (or project) for each purchase of license
 *  International Registered Trademark & Property of Anvanto
 */
 
(function ($, window, undefined) {
	'use strict';
	
	resizeProduct();
	
	$(document).ajaxSuccess(function() {
		resizeProduct();
	});
	
	function resizeProduct(){
		$('.js-product-miniature').each( function(){
			var marginBottom = $(this).find('.an_productattributes').height()+40+'px'
			$(this).find('.thumbnail-container').css('margin-bottom', marginBottom);		
		});
	}

	$(document).on('change','.an_productattributes [data-product-attribute], .an_productattributes-select select', function() {
		
		var self = this;

		getData($(this).closest('.an_productattributesForm').serialize(), function(data){ 			
			isAvailableForOrder($(self).closest('.an_productattributesForm').find('.js-an_productattributes-add-to-cart'), data);
			setMaxQty($(self).closest('.an_productattributesForm').find('.an_productattributes-qty'), data);
			setMinQty($(self).closest('.an_productattributesForm').find('.an_productattributes-qty'), data);			
			setPrices($(self).closest(an_productattributes.config.product_price_and_shipping), $(self).closest(an_productattributes.config.product_price_and_shipping).find(an_productattributes.config.price), data);
 			setImages($(self).closest(an_productattributes.config.thumbnail_container).find('img'), data);
			setVariants(self, data);
		}, $(self).closest(an_productattributes.config.thumbnail_container).find('img').closest('a'));
		
	});

	$(document).on('click','.an_productattributes-dropdown-menu li', function() {
		
		var self = this;			
				
		if (attributeGroups){
			generateInputs($(this).closest('.an_productattributesForm'), parseInt($(this).closest(an_productattributes.config.product_miniature).attr('data-id-product')), $(this).data('value'));
		}		
				
		getData($(this).closest('.an_productattributesForm').serialize(), function(data){ 
			isAvailableForOrder($(self).closest('.an_productattributesForm').find('.js-an_productattributes-add-to-cart'), data);
			setMaxQty($(self).closest('.an_productattributesForm').find('.an_productattributes-qty'), data);
			setMinQty($(self).closest('.an_productattributesForm').find('.an_productattributes-qty'), data);
			setPrices($(self).closest(an_productattributes.config.product_price_and_shipping), $(self).closest(an_productattributes.config.product_price_and_shipping).find(an_productattributes.config.price), data);
 			setImages($(self).closest(an_productattributes.config.thumbnail_container).find('img'), data);
		}, $(self).closest(an_productattributes.config.thumbnail_container).find('img').closest('a'));
	}); 
 	
	$(document).on('input','.an_productattributes-qty', function() {
		changeButInput(this);
	});

	function changeButInput(self){
		var val = parseInt($(self).val());
		var max = parseInt($(self).attr('data-max'));
		var addToCart = $(self).closest('.an_productattributesForm').find('.js-an_productattributes-add-to-cart');
		var addToCartStatus = parseInt(addToCart.attr('data-status'));

		if (max && val > max){
			addToCart.attr('disabled', 'disabled');
		} else if (addToCartStatus){
			addToCart.removeAttr('disabled');
		} else {
			addToCart.attr('disabled', 'disabled');
		}
	}

	function getData(dataUrl, callback, aContainer){
		
		aContainer.append(an_productattributes.loader);		
		
		$.ajax({
			type: "POST",
			url: an_productattributes.controller,
			data: dataUrl + '&action=getProductAttributes',
			dataType: 'json',
		}).done(function(data){
			callback(data);
		}).always(function() {
			aContainer.find('.js-anpa-loader').remove();
		});
	}

	function generateInputs(an_productattributesForm, productId, attrebuteID){
		$('.an_productattributes-hiddeninputs').remove();
		
		$.each(attributeGroups[productId][attrebuteID], function(index, value) {
			an_productattributesForm.append("<input name='group[" + value['id_attribute_group'] + "]' value='" + value['id_attribute'] + "' type='hidden' class='an_productattributes-hiddeninputs' />");
		});
	}
		
	function isAvailableForOrder(addToCart, data){
		if (!data.availableForOrder){
			addToCart.attr('disabled', 'disabled');
		} else {
			addToCart.removeAttr('disabled');
		}
		addToCart.attr('data-status', data.availableForOrder);
	}

	function setVariants(self, data){
		if (data.variants){
			$(self).closest('.js-an_productattributes-standart').html(data.variants);
		}
	}
		
	 function setMaxQty(qty, data){
		if (data.order_out_of_stock){
			qty.attr('data-max', data.quantity);
		} else {
			qty.removeAttr('data-max');
		}
	}
		
	function setMinQty(qty, data){
		if (data.minimal_quantity){
			qty.attr('min', data.minimal_quantity).val(data.minimal_quantity);
		}
	}
		
	function setPrices(priceContainer, price, data){
		priceContainer.find(an_productattributes.config.regular_price).remove();
		if (data.prices.has_discount && data.prices.regular_price){
			priceContainer.prepend('<span class="regular-price">'+data.prices.regular_price+'</span>');
		}
		
		price.html(data.prices.price);
	}	

	function setImages(img, data){
		if (data.images){
			img.attr('src', data.images.home[data.cover_id]);
		}
	}

})(jQuery, window);

$(document).ready(function () {
	
	selectFilling();
	
	$(document).ajaxSuccess(function() {
		selectFilling();
	});

		
	$(document).on('click','.an_productattributes-dropdown-toggler', function() {
		$(this).parents('.an_productattributes-dropdown').toggleClass('open');
	});
	
	$(document).on('click','.an_productattributes-dropdown-menu', function() {
		$(this).parents('.an_productattributes-dropdown').toggleClass('open');
	});

	$(document).on('click','.js-an_productattributes-product-selectbox li', function() {
		$(this).parents('.js-an_productattributes-product-selectbox').find('.js-an_productattributes-filter-option').text($(this).children('.js-an_productattributes-text').text());
		$(this).parents('.js-an_productattributes-select').find('option').removeAttr('selected');
		$(this).parents('.js-an_productattributes-select').find('option').eq($(this).index()).attr('selected','');
	});
	
	$(document).on('mouseleave', an_productattributes.config.product_miniature, function() {
		$('.an_productattributes-dropdown').removeClass('open');
		
	});

	function selectFilling(){
		$('.js-an_productattributes-product-selectbox li.selected').each(function() {
			let item = $(this).parents('.js-an_productattributes-product-selectbox').find('.js-an_productattributes-filter-option');
			if (!item.hasClass('selected')) {
				item.text($(this).children('.js-an_productattributes-text').text());
				item.addClass('selected');
			}
		});
	}
});