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
 
jQuery(document).ready(function () {
	
	changeTypeView();
	changeAddToCart();
	
	$('input[name=an_pa_display_add_to_cart]').on('click', function(){
		if ($(this).val() == '1'){
			$('input[name=an_pa_display_quantity]').parents('.form-group').show();
		} else {
			$('input[name=an_pa_display_quantity]').parents('.form-group').hide();
		}
	});
	
	$('.an-pa-type-view input').on('click', function(){
		changeTypeView();
	});
	
	function changeAddToCart(){
		if ($('input[name=an_pa_display_add_to_cart]:checked').val() == '1'){
			$('input[name=an_pa_display_quantity]').parents('.form-group').show();
		} else {
			$('input[name=an_pa_display_quantity]').parents('.form-group').hide();
		}
	}
		
	function changeTypeView(){
		if ($('.an-pa-type-view input[name=an_pa_type_view]:checked').val() != 'select'){
			$('.form-group .an-pa-type-select').each(function(){
				$(this).parents('.form-group').hide();
			});
			$('input[name=an_pa_display_prices]').parents('.form-group').hide();
		} else {
			$('.form-group .an-pa-type-select').each(function(){
				$(this).parents('.form-group').show();
			});
			$('input[name=an_pa_display_prices]').parents('.form-group').show();		
		}
		
		if ($('.an-pa-type-view input[name=an_pa_type_view]:checked').val() != 'standart'){

			$('.form-group .an-pa-type-view-standart').each(function(){
				$(this).parents('.form-group').hide();
			});
		} else {
			$('.form-group .an-pa-type-view-standart').each(function(){
				$(this).parents('.form-group').show();
			});			
		}
		
		if ($('.an-pa-type-view input[name=an_pa_type_view]:checked').val() != 'select' && $('.an-pa-type-view input[name=an_pa_type_view]:checked').val() != 'standart'){

			$('input[name=an_pa_display_labels]').parents('.form-group').hide();
		} else {
			$('input[name=an_pa_display_labels]').parents('.form-group').show();			
		}		
	}

});