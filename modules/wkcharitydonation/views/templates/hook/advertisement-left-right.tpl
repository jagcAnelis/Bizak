{**
* 2010-2021 Webkul.
*
* NOTICE OF LICENSE
*
* All right is reserved,
* Please go through LICENSE.txt file inside our module
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade this module to newer
* versions in the future. If you wish to customize this module for your
* needs please refer to CustomizationPolicy.txt file inside our module for more information.
*
* @author Webkul IN
* @copyright 2010-2021 Webkul IN
* @license LICENSE.txt
*}

{if isset($donationAds) && $donationAds}
    {foreach $donationAds as $donationAd}
        <style>
            .wk-adveritsement-lr{$donationAd.id_donation_info} .adv-title{
                color: {$donationAd.adv_title_color};
            }
            .wk-adveritsement-lr{$donationAd.id_donation_info} .adv-description{
                color: {$donationAd.adv_desc_color}
            }
            .wk-adveritsement-lr{$donationAd.id_donation_info} .adv-donate-btn{
                border : 1px solid {$donationAd.button_border_color};
                color: {$donationAd.button_text_color}
            }
            .wk-adveritsement-lr{$donationAd.id_donation_info} {
                background-image : url('{$donationAd.image_path}');
                background-position : center;
                background-repeat : no-repeat;
                background-size : cover;
                margin-bottom : 25px;
            }
        </style>
        <div class="wk-adveritsement-lr{$donationAd.id_donation_info}">
            <div class="ad-detail">
                <div class="adv-title">
                    {$donationAd.advertisement_title}
                </div>
                <div class="adv-description">
                    {$donationAd.advertisement_description nofilter}
                </div>
                {if ($donationAd.show_donate_button) && isset($donationAd.button_link)}
                <div id="adv-btn-side-div">
                    <a href="{$donationAd.button_link}" class="btn adv-donate-btn" id="adv-donate-btn-side">{if (isset($donationAd.donate_button_text))}{$donationAd.donate_button_text}{/if}</a>
                </div>
                {/if}
            </div>
        </div>
    {/foreach}
{/if}