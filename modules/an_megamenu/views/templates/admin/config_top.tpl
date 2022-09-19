{*
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
*}

<link href="https://fonts.googleapis.com/css?family=Ubuntu:400,700&display=swap" rel="stylesheet">
<style>
.an_panel {
    border-radius: 5px;
    margin: 0 4px 39px;
    font-family: 'Ubuntu', sans-serif;
}
.an_panel-link {
    text-decoration: underline!important;
}

.an_panel_info {
    font-family: 'Ubuntu', sans-serif;
    display: flex;
    padding: 0 5px;
    margin-bottom: 19px;
}
.an_panel_info-item {
    background: #fff;
    box-shadow: 0px 1px 1px 0px rgba(0, 0, 0, 0.1);
    border-radius: 2px;
    padding: 18px 36px 18px 18px;
    max-width: 330px;
    width: 100%;
    margin-right: 20px;
    margin-bottom: 20px;
}
.an_panel_info-item:last-child {
    margin-right: 0;
}
.an_panel_info-item-contact {
    border-left: 3px solid #21a6cb;
}
.an_panel_info-item-rate {
    border-left: 3px solid #fed500;
}
.an_panel_info-item-docs {
    border-left: 3px solid #e56b93;
}
.an_panel_info-item h2 {
    font-size: 18px;
    font-family: 'Ubuntu', sans-serif;
	font-weight: bold;
    margin: 0 0 7px;
}
.an_panel_info-item p {
    font-size: 14px;
    line-height: 24px;
    margin: 0;
}
.an_panel_info .grade {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    margin-top: 13px;
}


@media (max-width: 1366px) {
    .an_panel_info-item {
        max-width: 50%;
    }
}
@media (max-width: 767px) {
    .an_panel_info-item {
        max-width: 100%;
        margin-right: 0;
    }
    .an_panel_info {
        flex-direction: column;
    }
}
@media (max-width: 480px) {
    .an_panel_info-item {
        margin-right: 0;
    }
    .an_panel_modules-item {
        flex-direction: column;
        padding: 20px 0;
        position: relative;
    }
    .an_panel_modules-item-title {
        position: static;
    }
    .an_panel_modules-disabled-flag {
        top: 20px;
    }
}
</style>


{$contact_us = 'http://bit.ly/2OT7uaZ'}


<div class="an_panel_info">
    <div class="an_panel_info-item an_panel_info-item-rate">
        <h2>Main</h2>
		<p>Open the <a href="{$configure}" class="an_panel-link">Main Menu</a> of the module.</p>
		<p>Open the <a href="{$sitemap}" class="an_panel-link" target="_blank">SiteMap</a> of the shop.</p>
    </div>
	{*
    <div class="an_panel_info-item an_panel_info-item-contact">
        <h2>SiteMap</h2>
        <p><a class="an_panel-link" href="{$sitemap}" target="_blank">Contact us</a> on any question or problem with the module</p>
    </div>		
	*}
    <div class="an_panel_info-item an_panel_info-item-contact">
        <h2>Contact Us</h2>
        <p><a class="an_panel-link" href="{$contact_us}" target="_blank">Contact us</a> on any question or problem with the module</p>
    </div>
    <div class="an_panel_info-item an_panel_info-item-docs">
        <h2>Documentation</h2>
        <p>If you need help or any question / problem watch our <a  class="an_panel-link" href="{$modulePath}/doc/readme_en.pdf" target="_blank">documentation</a> or <a  class="an_panel-link" href="https://www.youtube.com/watch?v=8r-aiiKarDo" target="_blank">video guide</a>.</p>
    </div>
</div>