{*
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
*}

{*
<div class="an_panel an_panel_notification">
    <div class="an_panel_notification-icon">
        <svg
         xmlns="http://www.w3.org/2000/svg"
         xmlns:xlink="http://www.w3.org/1999/xlink"
         width="20px" height="20px">
        <path fill-rule="evenodd"  fill="rgb(46, 172, 174)"
         d="M10.000,0.000 C4.486,0.000 0.000,4.486 0.000,10.000 C0.000,15.514 4.486,20.000 10.000,20.000 C15.514,20.000 20.000,15.514 20.000,10.000 C20.000,4.486 15.514,0.000 10.000,0.000 ZM15.589,7.368 L9.198,13.709 C8.822,14.085 8.221,14.110 7.820,13.734 L4.436,10.652 C4.035,10.276 4.010,9.649 4.361,9.248 C4.737,8.847 5.363,8.822 5.764,9.198 L8.446,11.654 L14.160,5.940 C14.561,5.539 15.188,5.539 15.589,5.940 C15.990,6.341 15.990,6.967 15.589,7.368 Z"/>
        </svg>
    </div>
    <div class="an_panel_notification-content">
	<!--	<h2>Note</h2>  -->
        <p>AN  Theme Dashboard is dedicated to simplify the process of seeking for the modules installed with the theme. Here are all the modules that were installed with the theme or separately from Anvanto.</p>
    </div>
</div>
*}

{include file='./suggestions.tpl'}

<div class="an_panel an_panel_modules">
{*
    <div class="an_panel_block-header">
        <h3>Important</h3> 
    </div>
*}
    <div class="an_panel_modules-list an_panel-text-content">
		<h2>Important</h2>
		<ul>
			{if isset($an_dashboard.theme_doc) && $an_dashboard.theme_doc !=''}
			<li><a href="{$an_dashboard.theme_doc}" target="_blank">Theme Configuration Guide.</a><br />  Includes the scheme of theme elements (such as modules, blocks, pieces of text) along with the instructions how to manage and edit them.</li>
			{/if}
			{if isset($an_dashboard.translations_faq) && $an_dashboard.translations_faq !=''}
			<li><a href="{$an_dashboard.translations_faq}" target="_blank">Translations FAQ</a></li>
			{/if}
			<li><a href="https://youtube.com/playlist?list=PLPwkq-QuSPj2BPD4nEcB7NibIfGQhtju_" target="_blank">PrestaShop 1.7.x Video tutorials</a></li>
			<li>Where can I resize the logo? You can do this in the AN Logo module.</li>
		</ul>

	</div>
</div>

<div class="an_panel an_panel_modules">
    <div class="an_panel_block-header">
        <h3>Modules ({count($modules)})</h3> 
    </div>
    <div class="an_panel_modules-list">
		
		{foreach from=$modules item=module key=moduleFolder}
        <div class="an_panel_modules-item {if !Module::isEnabled({$moduleFolder})} an_panel_modules-disabled{/if}">
            <div class="an_panel_modules-item-logo">
				{if isset($module.logo) AND $module.logo != ''}
                <img src="{$module.logo}" />
				{/if}
            </div>
            <div class="an_panel_modules-item-content">

                <div class="an_panel_modules-item-title">
                    <h2>{if isset($module.name) AND $module.name != ''}{$module.name}{/if}</h2>
                    {if isset($module.url) AND $module.url != ''}<a class="an_panel-link" href="{$module.url}" target="_blank">Whatch on Addons</a>{/if}
                    <div class="an_panel_modules-disabled-flag label-tooltip" data-toggle="tooltip" data-placement="bottom" data-html="false" data-original-title="To enable the module open Modules -> Module Manager.">Disabled</div>
                </div>
				
				{if "`$smarty.current_dir`/short/`$moduleFolder`.tpl"|file_exists}
                <p class="an_panel_modules-item-text">{include file="./short/`$moduleFolder`.tpl"}</p>
				{/if}
				
				{if "`$smarty.current_dir`/big/`$moduleFolder`.tpl"|file_exists}
				<a class="an_panel_show-more an_panel-link" href="#modal_{$moduleFolder}">Show More</a>
				{/if}
				
				{if (isset($module.configure) AND $module.configure != '') OR
					(isset($module.video) AND $module.video !='') OR
					(isset($module.doc) AND $module.doc !='') 
				}
				<div class="an_panel_link-wrap">
					{if isset($module.configure) AND $module.configure != ''}
                    <a class="an_panel_btn" href="{$module.configure}">
                        <svg
                         xmlns="http://www.w3.org/2000/svg"
                         xmlns:xlink="http://www.w3.org/1999/xlink"
                         width="22px" height="21px">
                        <path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
                         d="M21.526,12.237 L20.936,12.353 C20.870,12.704 20.754,13.046 20.596,13.369 L21.028,13.810 C21.233,14.020 21.243,14.345 21.049,14.566 L20.421,15.283 C20.228,15.503 19.895,15.549 19.645,15.390 L19.109,15.048 C18.800,15.251 18.465,15.415 18.111,15.535 L18.090,16.164 C18.080,16.453 17.847,16.687 17.549,16.712 L16.579,16.792 C16.282,16.814 16.010,16.619 15.951,16.336 L15.817,15.695 C15.472,15.631 15.137,15.524 14.819,15.381 L14.332,15.820 C14.115,16.018 13.778,16.027 13.549,15.840 L12.806,15.235 C12.577,15.048 12.529,14.727 12.695,14.486 L13.065,13.947 C12.874,13.676 12.716,13.387 12.595,13.082 L11.932,13.061 C11.633,13.052 11.389,12.827 11.364,12.540 L11.281,11.605 C11.257,11.318 11.460,11.056 11.753,10.999 L12.367,10.879 C12.428,10.531 12.534,10.192 12.683,9.868 L12.253,9.427 C12.048,9.218 12.039,8.892 12.232,8.671 L12.860,7.950 C13.053,7.729 13.386,7.683 13.636,7.843 L14.127,8.157 C14.448,7.934 14.800,7.756 15.173,7.627 L15.191,7.071 C15.201,6.782 15.435,6.548 15.732,6.523 L16.702,6.443 C16.999,6.420 17.271,6.616 17.330,6.898 L17.445,7.445 C17.825,7.510 18.193,7.624 18.540,7.786 L18.951,7.415 C19.168,7.217 19.506,7.208 19.735,7.394 L20.478,8.000 C20.707,8.186 20.754,8.510 20.584,8.751 L20.261,9.220 C20.466,9.511 20.631,9.825 20.754,10.157 L21.346,10.176 C21.646,10.185 21.889,10.410 21.915,10.697 L21.998,11.632 C22.021,11.919 21.818,12.181 21.526,12.237 ZM16.501,9.743 C15.453,9.827 14.672,10.719 14.762,11.730 C14.849,12.740 15.774,13.494 16.822,13.407 C17.870,13.323 18.651,12.431 18.562,11.420 C18.474,10.410 17.549,9.657 16.501,9.743 ZM12.510,7.506 L11.670,7.599 C11.552,8.020 11.375,8.423 11.149,8.801 L11.694,9.468 C11.883,9.698 11.864,10.025 11.649,10.232 L10.653,11.193 C10.438,11.398 10.099,11.418 9.860,11.236 L9.159,10.701 C8.760,10.917 8.336,11.084 7.892,11.195 L7.788,12.062 C7.753,12.353 7.498,12.572 7.196,12.572 L5.787,12.572 C5.485,12.572 5.230,12.353 5.194,12.062 L5.086,11.163 C4.673,11.050 4.276,10.888 3.904,10.681 L3.179,11.234 C2.941,11.416 2.601,11.398 2.386,11.191 L1.390,10.230 C1.178,10.023 1.156,9.695 1.345,9.466 L1.923,8.762 C1.720,8.416 1.560,8.048 1.444,7.665 L0.529,7.563 C0.227,7.529 0.000,7.283 0.000,6.992 L0.000,5.633 C0.000,5.342 0.227,5.096 0.529,5.062 L1.397,4.966 C1.506,4.547 1.671,4.144 1.888,3.767 L1.345,3.104 C1.156,2.874 1.175,2.547 1.390,2.340 L2.386,1.379 C2.601,1.174 2.941,1.154 3.179,1.336 L3.828,1.832 C4.243,1.593 4.689,1.411 5.157,1.290 L5.251,0.510 C5.286,0.219 5.541,0.000 5.843,0.000 L7.252,0.000 C7.554,0.000 7.809,0.219 7.845,0.510 L7.939,1.290 C8.395,1.409 8.829,1.584 9.235,1.814 L9.860,1.336 C10.099,1.154 10.438,1.172 10.653,1.379 L11.647,2.337 C11.859,2.544 11.880,2.872 11.692,3.102 L11.182,3.723 C11.399,4.097 11.569,4.495 11.682,4.911 L12.510,5.005 C12.813,5.039 13.039,5.285 13.039,5.576 L13.039,6.935 C13.039,7.226 12.813,7.472 12.510,7.506 ZM6.549,3.990 C5.265,3.990 4.220,4.998 4.220,6.236 C4.220,7.474 5.265,8.482 6.549,8.482 C7.833,8.482 8.878,7.474 8.878,6.236 C8.878,4.998 7.833,3.990 6.549,3.990 ZM4.838,15.924 L5.251,15.884 C5.327,15.604 5.442,15.337 5.589,15.087 L5.336,14.773 C5.152,14.543 5.176,14.220 5.390,14.017 L5.820,13.612 C6.035,13.410 6.370,13.396 6.603,13.578 L6.898,13.810 C7.177,13.655 7.477,13.537 7.788,13.460 L7.835,13.105 C7.873,12.818 8.128,12.604 8.428,12.609 L9.025,12.615 C9.324,12.618 9.575,12.838 9.605,13.125 L9.643,13.482 C9.945,13.564 10.233,13.685 10.502,13.842 L10.788,13.630 C11.026,13.453 11.361,13.476 11.571,13.683 L11.989,14.095 C12.199,14.302 12.213,14.625 12.024,14.850 L11.786,15.132 C11.928,15.383 12.036,15.651 12.109,15.929 L12.492,15.977 C12.789,16.013 13.011,16.259 13.006,16.548 L12.999,17.124 C12.997,17.413 12.768,17.654 12.470,17.684 L12.079,17.722 C11.996,18.002 11.876,18.269 11.720,18.519 L11.972,18.835 C12.157,19.065 12.133,19.388 11.918,19.591 L11.491,19.994 C11.276,20.196 10.941,20.210 10.707,20.028 L10.377,19.771 C10.108,19.912 9.825,20.019 9.527,20.089 L9.473,20.504 C9.435,20.790 9.180,21.004 8.881,21.000 L8.284,20.993 C7.984,20.990 7.734,20.770 7.703,20.483 L7.656,20.046 C7.382,19.969 7.120,19.857 6.872,19.716 L6.521,19.978 C6.282,20.155 5.947,20.133 5.737,19.925 L5.319,19.514 C5.109,19.306 5.095,18.983 5.284,18.758 L5.567,18.421 C5.433,18.189 5.329,17.943 5.256,17.686 L4.817,17.631 C4.519,17.595 4.298,17.349 4.302,17.060 L4.309,16.484 C4.312,16.195 4.541,15.954 4.838,15.924 ZM8.654,18.269 C9.511,18.278 10.217,17.615 10.226,16.789 C10.235,15.963 9.549,15.283 8.692,15.274 C7.835,15.264 7.130,15.927 7.120,16.753 C7.111,17.579 7.797,18.260 8.654,18.269 Z"/>
                        </svg>
                        Configure
                    </a>
					{/if}
					{if isset($module.video) AND $module.video !='' }
                    <a class="an_panel_btn btn-yt" href="{$module.video}" target="_blank">
                        <svg
                         xmlns="http://www.w3.org/2000/svg"
                         xmlns:xlink="http://www.w3.org/1999/xlink"
                         width="22px" height="15px">
                        <path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
                         d="M21.547,2.347 C21.293,1.429 20.550,0.706 19.608,0.459 C17.886,0.000 11.000,0.000 11.000,0.000 C11.000,0.000 4.114,0.000 2.392,0.441 C1.468,0.688 0.707,1.429 0.453,2.347 C0.000,4.024 0.000,7.500 0.000,7.500 C0.000,7.500 0.000,10.994 0.453,12.653 C0.707,13.570 1.450,14.294 2.392,14.541 C4.132,15.000 11.000,15.000 11.000,15.000 C11.000,15.000 17.886,15.000 19.608,14.559 C20.550,14.312 21.293,13.588 21.547,12.670 C22.000,10.994 22.000,7.518 22.000,7.518 C22.000,7.518 22.018,4.024 21.547,2.347 ZM8.807,10.712 L8.807,4.288 L14.534,7.500 L8.807,10.712 Z"/>
                        </svg>
                        {l s='Video Guide' mod='an_theme_dashboard'}
                    </a>
					{/if}
					{if isset($module.doc) AND $module.doc !='' }
                    <a class="an_panel_btn btn-docs" href="{$module.doc}" target="_blank">
                        <svg
                         xmlns="http://www.w3.org/2000/svg"
                         xmlns:xlink="http://www.w3.org/1999/xlink"
                         width="16px" height="21px">
                        <path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
                         d="M13.744,21.000 L2.256,21.000 C1.012,21.000 0.000,19.988 0.000,18.744 L0.000,2.256 C0.000,1.012 1.012,0.000 2.256,0.000 L9.600,0.000 L9.600,4.717 C9.600,5.508 10.244,6.152 11.036,6.152 L16.000,6.152 L16.000,18.744 C16.000,19.988 14.988,21.000 13.744,21.000 ZM3.364,15.996 L6.347,15.996 C6.687,15.996 6.963,15.721 6.963,15.381 C6.963,15.041 6.687,14.766 6.347,14.766 L3.364,14.766 C3.024,14.766 2.749,15.041 2.749,15.381 C2.749,15.721 3.024,15.996 3.364,15.996 ZM12.390,8.203 L3.364,8.203 C3.024,8.203 2.749,8.479 2.749,8.818 C2.749,9.158 3.024,9.434 3.364,9.434 L12.390,9.434 C12.730,9.434 13.005,9.158 13.005,8.818 C13.005,8.479 12.730,8.203 12.390,8.203 ZM12.390,11.484 L3.364,11.484 C3.024,11.484 2.749,11.760 2.749,12.100 C2.749,12.439 3.024,12.715 3.364,12.715 L12.390,12.715 C12.730,12.715 13.005,12.439 13.005,12.100 C13.005,11.760 12.730,11.484 12.390,11.484 ZM10.831,4.717 L10.831,0.255 C11.014,0.351 11.185,0.472 11.338,0.617 L15.294,4.359 C15.468,4.523 15.613,4.714 15.726,4.922 L11.036,4.922 C10.923,4.922 10.831,4.830 10.831,4.717 Z"/>
                        </svg>
                        {l s='Documentation' mod='an_theme_dashboard'}
                    </a>
					{/if}
                </div>
				{/if}

            </div>
        </div>
		{/foreach}

    </div>
</div>

{foreach from=$modules item=module key=moduleFolder}
{if "`$smarty.current_dir`/big/`$moduleFolder`.tpl"|file_exists}
<div id="modal_{$moduleFolder}" class="mfp-hide an-popup-block">
	{include file="./big/`$moduleFolder`.tpl"}
    <a class="an_popup-modal-dismiss" href="#">
        <svg
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        width="21px" height="21px">
        <path fill-rule="evenodd"  opacity="0.102" fill="rgb(0, 0, 0)"
        d="M12.144,10.656 L20.348,2.452 C20.759,2.041 20.759,1.375 20.348,0.963 C19.937,0.552 19.270,0.552 18.859,0.963 L10.656,9.167 L2.452,0.963 C2.041,0.552 1.375,0.552 0.964,0.963 C0.552,1.375 0.552,2.041 0.964,2.452 L9.167,10.656 L0.964,18.859 C0.552,19.270 0.552,19.937 0.964,20.348 C1.375,20.759 2.041,20.759 2.452,20.348 L10.656,12.144 L18.859,20.348 C19.270,20.759 19.937,20.759 20.348,20.348 C20.759,19.937 20.759,19.270 20.348,18.859 L12.144,10.656 Z"/>
        </svg>
    </a>
</div>
{/if}
{/foreach}

{if "`$smarty.current_dir`/info-footer.tpl"|file_exists}
{include file='./info-footer.tpl'}
{/if}