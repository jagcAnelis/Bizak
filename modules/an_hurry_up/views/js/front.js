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
    
    function progressLeft(bar, counter)
    {
        bar.css('width', 100 * +counter.text() / bar.attr('data-max') + '%');
    }
    $(document).ready(function () {
        let progressBar, progressCount;
        $('.an_hurry_up').each(function (index) {
            progressBar = $(this).find('.an_hurry_up-progress-fill');
            progressCount = $(this).find('.an_hurry_up-count');
            progressLeft(progressBar, progressCount);
        });
        /*
        progressCount.on('DOMSubtreeModified', function() {
            progressLeft(progressBar, progressCount);
        })
        */
    });
    $(document).ajaxSuccess(function () {
        /*
        let progressBar = $('.an_hurry_up-progress-fill'),
            progressCount = $('.an_hurry_up-count');
        progressLeft(progressBar, progressCount);
        */
        let progressBar, progressCount;
        $('.an_hurry_up').each(function (index) {
            progressBar = $(this).find('.an_hurry_up-progress-fill');
            progressCount = $(this).find('.an_hurry_up-count');
            progressLeft(progressBar, progressCount);
        });
    });

    prestashop.on('updatedProduct', function (event) {
        let id_product,id_product_attribute,progressBar, progressCount;
        id_product = $('input#product_page_product_id').val();
        if ($('#an_hu_ipa').length > 0) {
            id_product_attribute = $('input#an_hu_ipa').val();
        }
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url:$('input#an_hu_url').val(),
            data:{
                action : 'getProductQty',
                ajax : true,
                id_product : id_product,
                combination_id : id_product_attribute,
            },
            success: function(response){
                $('.an_hurry_up-count').innerHTML = response;
                $('.an_hurry_up').each(function (index) {
                    progressBar = $(this).find('.an_hurry_up-progress-fill');
                    progressCount = $(this).find('.an_hurry_up-count');
                    progressLeft(progressBar, progressCount);
                });
            }
        });
    });
})(jQuery, window);