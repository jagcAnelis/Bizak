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
    var num_validator = [
        ['an_modal_cookie_opacity', 0, 100],
        ['an_modal_cookie_width', 0, 10000]
    ];
    for (var i = 0; i < num_validator.length; i++) {
        (function (i) {
            jQuery('#' + num_validator[i][0]).on("keyup", function (event) {
                var value = parseInt(jQuery(this).val()) || 0;
                if (jQuery(this).val() == '') {
                    return true;
                }
                if(value < num_validator[i][2] && value > num_validator[i][1]) {
                    this.value = value
                } else {
                    if(value > num_validator[i][2]) {
                        this.value = num_validator[i][2];
                    } else {
                        this.value = num_validator[i][1];
                    }
                }

            });
        })(i);
    }
});