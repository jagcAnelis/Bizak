/**
 * 2007-2020 ETS-Soft
 *
 * NOTICE OF LICENSE
 *
 * This file is not open source! Each license that you purchased is only available for 1 wesite only.
 * If you want to use this file on more websites (or projects), you need to purchase additional licenses. 
 * You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
 * 
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please contact us for extra customization service at an affordable price
 *
 *  @author ETS-Soft <etssoft.jsc@gmail.com>
 *  @copyright  2007-2020 ETS-Soft
 *  @license    Valid for 1 website (or project) for each purchase of license
 *  International Registered Trademark & Property of ETS-Soft
 */

$(document).ready(function(){
   $(document).on('click','.field-positions .setting',function(){
        $('.ets-cs-form-group-field').removeClass('active');
        $('.ets-cs-form-group-field.'+$(this).data('setting')).addClass('active');
   }); 
   $(document).on('click','.close-setting-field',function(){
        $('.ets-cs-form-group-field').removeClass('active');
   });
   $(document).on('click','.field-positions .ets_sc_field',function(){
        var field = $(this).data('field');
        if($(this).is(':checked'))
        {
            $(this).parent().addClass('active');
            if($('#'+field+'_on').length)
            {
                $('#'+field+'_on').click();
            }
            var value_filed=1;
        }
        else
        {
            $(this).parent().removeClass('active');
            if($('#'+field+'_off').length)
            {
                $('#'+field+'_off').click();
            }
             var value_filed=0;
        }
        $.ajax({
            url: '',
            data: 'action=updateBlock&field='+field+'&value_filed='+value_filed,
            type: 'post',
            dataType: 'json',
            async: true,
			cache: false,
            success: function(json){
                if(json.success)
                {
                    $.growl.notice({ message: json.success });
                }
                if(json.errors)
                    $.growl.error({message:json.errors});
            },
            error: function(xhr, status, error)
            {
                
            }
        }); 
   });
   if($('#field-positions').length)
   {
        var $myfield = $("#field-positions");
    	$myfield.sortable({
    		opacity: 0.6,
            handle: ".position_number",
            cursor: 'move',
    		update: function() {
    			var order = $(this).sortable("serialize") + "&action=updateFieldOrdering";	
                var $this=  $(this);					
                $.ajax({
        			type: 'POST',
        			headers: { "cache-control": "no-cache" },
        			url: '',
        			async: true,
        			cache: false,
        			dataType : "json",
        			data:order,
        			success: function(json)
        			{
                        if(json.success)
                        {
                            $.growl.notice({ message: json.success });
                            var i=1;
                            $('.field-positions li').each(function(){
                                $(this).find('.position_number').html('<span>'+i+'</span>');
                                i++;
                            });
                        }
                        if(json.errors)
                        {
                            $.growl.error({message:json.errors});
                            $myfield.sortable("cancel");
                        }
                    }
        		});
    		},
        	stop: function( event, ui ) {
       		}
    	});
    }
    $(document).on('click','.module_form_submit_btn_filed',function(){
        $('#module_form_submit_btn').click();
    });
    $(document).on('click','button[name="saveConfig"]',function(e){
        e.preventDefault();
        if(!$('#module_form').hasClass('loading'))
        {
            $('#module_form').addClass('loading');
            var formData = new FormData($(this).parents('form').get(0));
            formData.append(name, 1);
            formData.append('ajax', 1);
            var url_ajax= $('#module_form').attr('action');
            $('.bootstrap .module_error').remove();
            $.ajax({
                url: url_ajax,
                data: formData,
                type: 'post',
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(json){
                    $('#module_form').removeClass('loading');
                    if(json.success)
                    {
                        $.growl.notice({ message: json.success });
                        if($('.ets-cs-form-group-field.active').length)
                            $('.ets-cs-form-group-field.active').removeClass('active');
                    }
                    else if(json.errors)
                    {  
                        if($('.ets-cs-form-group-field.active').length)
                            $('.ets-cs-form-group-field.active .popup_footer').before(json.errors);
                        else
                            $('#module_form .form-wrapper').append(json.errors);
                    }
                },
                error: function(xhr, status, error)
                {     
                    $('#module_form').removeClass('loading');
                }
            });
        }
    });
    $(document).mouseup(function (e)
    {
        if($('.ets-cs-form-group-field.active').length)
        {
            if (!$('.ets-cs-form-group-field.active .ets-cs-form-group-field-wapper').is(e.target)&& $('.ets-cs-form-group-field.active .ets-cs-form-group-field-wapper').has(e.target).length === 0)
            {
                $('.ets-cs-form-group-field.active').removeClass('active');
            }
        }
    });
    $(document).keyup(function(e) { 
        if(e.keyCode == 27) {
            if($('.ets-cs-form-group-field.active').length)
            {
                $('.ets-cs-form-group-field.active').removeClass('active');
            }
        }
    });
    $(document).on('click','.ets_cs_clear_cache',function(e){
        e.preventDefault();
        if(!$(this).hasClass('loading'))
        {
            $(this).addClass('loading');
            var $this= $(this);
            $.ajax({
                url: '',
                data: 'action=clearCache',
                type: 'post',
                dataType: 'json',
                async: true,
    			cache: false,
                success: function(json){
                    if(json.success)
                    {
                        $.growl.notice({ message: json.success });
                    }
                    if(json.errors)
                        $.growl.error({message:json.errors});
                    $this.removeClass('loading');
                },
                error: function(xhr, status, error)
                {
                    $this.removeClass('loading');
                }
            }); 
        }
        
    });
});