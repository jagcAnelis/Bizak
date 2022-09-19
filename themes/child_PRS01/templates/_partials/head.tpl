{**
 * 2007-2018 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2018 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
{block name='head_charset'}
  <meta charset="utf-8">
{/block}
{block name='head_ie_compatibility'}
  <meta http-equiv="x-ua-compatible" content="ie=edge">
{/block}

{block name='head_seo'}
  <title>{block name='head_seo_title'}{$page.meta.title}{/block}</title>
  <meta name="description" content="{block name='head_seo_description'}{$page.meta.description}{/block}">
  <meta name="keywords" content="{block name='head_seo_keywords'}{$page.meta.keywords}{/block}">
  {if $page.meta.robots !== 'index'}
    <meta name="robots" content="{$page.meta.robots}">
  {/if}

  {*if $page.canonical}
    <link rel="canonical" href="{$page.canonical}">
  {/if*}
  {*<!–- canonical -–>*}
  {if $page.page_name == 'manufacturer' && $smarty.get.id_manufacturer > 0}
       
    {if $urls.current_url == 'https://bizakshop.com/manufacturer/otras-marcas?page=2'}
      <link rel="canonical" href="{$urls.current_url}" />
    {else}
      <link rel="canonical" href="{$link->getManufacturerLink($smarty.get.id_manufacturer, null, $id_lang)}" />
    {/if}
    
  {elseif $page.page_name == 'manufacturer' && !isset($smarty.get.id_manufacturer)}
    <link rel="canonical" href="{$link->getPageLink('manufacturer', 'true', $id_lang)}" />    
  {elseif $page.page_name == 'authentication'}
    <link rel="canonical" href="https://bizakshop.com/iniciar-sesion" /> 
  {elseif $page.page_name == 'index'}
    <link rel="canonical" href="https://bizakshop.com/">
  {elseif $page.canonical}
    <link rel="canonical" href="{$page.canonical}">
  {/if}
  {*<!–- /canonical -–>*}

  
  {block name='head_hreflang'}
      {foreach from=$urls.alternative_langs item=pageUrl key=code}
            <link rel="alternate" href="{$pageUrl}" hreflang="{$code}">
      {/foreach}
  {/block}
{/block}

{block name='head_viewport'}
  <meta name="viewport" content="width=device-width, initial-scale=1">
{/block}

<!-- TemplateTrip theme google font-->
    <link href="https://fonts.googleapis.com/css?family=Oswald" type="text/css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Lilita+One:400" rel="stylesheet"> 
<!-- TemplateTrip theme google font-->


{block name='head_icons'}
  <link rel="icon" type="image/vnd.microsoft.icon" href="{$shop.favicon}?{$shop.favicon_update_time}">
  <link rel="shortcut icon" type="image/x-icon" href="{$shop.favicon}?{$shop.favicon_update_time}">
{/block}

{block name='stylesheets'}
  {include file="_partials/stylesheets.tpl" stylesheets=$stylesheets}
{/block}

{block name='javascript_head'}
  {include file="_partials/javascript.tpl" javascript=$javascript.head vars=$js_custom_vars}
{/block}

{block name='hook_header'}
  {$HOOK_HEADER nofilter}
{/block}

{literal}
<!-- Global site tag 1 (gtag.js) - Google Marketing Platform -->
<script async src="https://www.googletagmanager.com/gtag/js?id=DC-5471920"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'DC-5471920');
</script>
<!-- Final Global site tag 1 -->

<!-- Global site tag 2 (gtag.js) - Google Ads: 964330295 -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-964330295"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-964330295');
</script>
<!-- Final Global site tag 2 -->

<!-- Global site tag (gtag.js) - Google Ads: 10873570697 --> 

<script async src="https://www.googletagmanager.com/gtag/js?id=AW-10873570697"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date()); 

  gtag('config', 'AW-10873570697');
</script>
<!-- Final Global site tag (gtag.js) - Google Ads: 10873570697 --> 
{/literal}

{literal}
<meta name="google-site-verification" content="t9pa1oZLDQIOIJJfsiEthyVgv0lg3mNSsP0kRLwNs3I" />
{/literal}

{literal}
<!-- Meta Pixel Code --> <script> !function(f,b,e,v,n,t,s) {if(f.fbq)return;n=f.fbq=function(){n.callMethod? n.callMethod.apply(n,arguments):n.queue.push(arguments)}; if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0'; n.queue=[];t=b.createElement(e);t.async=!0; t.src=v;s=b.getElementsByTagName(e)[0]; s.parentNode.insertBefore(t,s)}(window, document,'script', 'https://connect.facebook.net/en_US/fbevents.js'); fbq('init', '511419547357000'); fbq('track', 'PageView'); </script> <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=511419547357000&ev=PageView&noscript=1" /></noscript> <!-- End Meta Pixel Code -->
{/literal}

{block name='hook_extra'}{/block}
