/**
* 2020 Anvanto
*
* NOTICE OF LICENSE
*
* This file is not open source! Each license that you purchased is only available for 1 wesite only.
* If you want to use this file on more websites (or projects), you need to purchase additional licenses. 
* You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
*
*  @author Anvanto <anvantoco@gmail.com>
*  @copyright  2020 Anvanto
*  @license    Valid for 1 website (or project) for each purchase of license
*  International Registered Trademark & Property of Anvanto
*/

var antheme_select = function () {
    jQuery('.custom-antheme-select').css({borderColor: '#cccccc'});
    jQuery('.custom-antheme-select input:checked').each(function () {
        jQuery(this).parents('.custom-antheme-select').css({borderColor: 'red'});
    });
};

jQuery(document).ready(function () {
    antheme_select();
    jQuery('.custom-antheme-select').on('click', antheme_select);
	
	
	$('.exSelect').attr('disabled', 'disabled');
	
	
	$('.exSelect-apply').on('click', function(){
		
		var exSelect = $(this).parents('.form-group').find('.exSelect');
		
		if ($(this).prop('checked')){
			exSelect.removeAttr('disabled');
		} else {
			exSelect.attr('disabled', 'disabled');
		}
	});
	
});














