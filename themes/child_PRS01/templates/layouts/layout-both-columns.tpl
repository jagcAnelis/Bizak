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
<!doctype html>
<html lang="{$language.iso_code}">

  <head>
    {block name='head'}
      {include file='_partials/head.tpl'}
    {/block}
  </head>

  <body id="{$page.page_name}" class="{$page.body_classes|classnames}">


    {if $page.page_name == 'product'}
      {if ($product.id == '809') || ($product.id == '811') }
        {literal}
        <!--Fragmento de evento de Bizak-Hey_Duggee_Interactivo_Producto -->
        <script>
          gtag('event', 'conversion', {
            'allow_custom_scripts': true,
            'send_to': 'DC-5471920/corp/bizak0+standard'
          });
        </script>
        <noscript>
        <img src="https://ad.doubleclick.net/ddm/activity/src=5471920;type=corp;cat=bizak0;dc_lat=;dc_rdid=;tag_for_child_directed_treatment=;tfua=;npa=;gdpr=${GDPR};gdpr_consent=${GDPR_CONSENT_755};ord=1?" width="1" height="1" alt=""/>
        </noscript>
        <!-- Final del fragmento de evento -->
        {/literal}
      {/if}
    {elseif $page.page_name == 'order-confirmation'}
      {literal}
        <!--
        Fragmento de evento de Bizak_Compra -->
        <script>
          gtag('event', 'purchase', {
            'allow_custom_scripts': true,
            'value': '{/literal}{$textototal = $order.totals.total_including_tax.value|strip:""}{assign "find" array(',', ' ', '€')}{assign "repl" array('.', '', '')}{$textofinal = $textototal|replace:$find:$repl}{$textofinal}{literal}',
            'transaction_id': '{/literal}{$smarty.get.id_order}{literal}',
            'send_to': 'DC-5471920/sales/bizak0+transactions'
          });
        </script>
        <noscript>
        <img src="https://ad.doubleclick.net/ddm/activity/src=5471920;type=sales;cat=bizak0;qty=1;cost={/literal}{$textofinal}{literal};dc_lat=;dc_rdid=;tag_for_child_directed_treatment=;tfua=;npa=;gdpr=${GDPR};gdpr_consent=${GDPR_CONSENT_755};ord={/literal}{$smarty.get.id_order|str_pad:10:'0':$smarty.const.STR_PAD_LEFT}{literal}?" width="1" height="1" alt=""/>
        </noscript>
        <!-- Final del fragmento de evento: no lo quite -->

        <!-- Event snippet for Compra conversion page -->
        <script>
          gtag('event', 'conversion', {
              'send_to': 'AW-10873570697/ME3_CMK9-7QDEImL9sAo',
              'value': '{/literal}{$textototal = $order.totals.total_including_tax.value|strip:""}{assign "find" array(',', ' ', '€')}{assign "repl" array('.', '', '')}{$textofinal = $textototal|replace:$find:$repl}{$textofinal}{literal}',
              'currency': 'EUR',
              'transaction_id': ''
          });
        </script>
        <!-- Final de Event snippet for Compra conversion page -->
      {/literal}
    {/if}
    {block name='hook_after_body_opening_tag'}
      {hook h='displayAfterBodyOpeningTag'}
    {/block}

    <main>
      {block name='product_activation'}
        {include file='catalog/_partials/product-activation.tpl'}
      {/block}

      <header id="header">
        {block name='header'}
          {include file='_partials/header.tpl'}
        {/block}
      </header>
	 <div id="page" class="">
      {block name='notifications'}
        {include file='_partials/notifications.tpl'}
      {/block}

      <section id="wrapper">
        {hook h="displayWrapperTop"}
        <div class="container">
          {block name='breadcrumb'}
            {include file='_partials/breadcrumb.tpl'}
          {/block}

          {block name="left_column"}
            <div id="left-column" class="col-xs-12 col-sm-4 col-md-3">
              {if $page.page_name == 'product'}
                {hook h='displayLeftColumnProduct'}
              {else}
                {hook h="displayLeftColumn"}
              {/if}
            </div>
          {/block}

          {block name="content_wrapper"}
            <div id="content-wrapper" class="left-column right-column col-sm-4 col-md-6">
              {hook h="displayContentWrapperTop"}
              {block name="content"}
                <p>Hello world! This is HTML5 Boilerplate.</p>
              {/block}
              {hook h="displayContentWrapperBottom"}
            </div>
          {/block}

          {block name="right_column"}
            <div id="right-column" class="col-xs-12 col-sm-4 col-md-3">
              {if $page.page_name == 'product'}
                {hook h='displayRightColumnProduct'}
              {else}
                {hook h="displayRightColumn"}
              {/if}
            </div>
          {/block}
        </div>
        {hook h="displayWrapperBottom"}
      </section>

      <footer id="footer">
        {block name="footer"}
          {include file="_partials/footer.tpl"}
        {/block}
      </footer>
	</div>
    </main>

    {block name='javascript_bottom'}
      {include file="_partials/javascript.tpl" javascript=$javascript.bottom}
    {/block}

    {block name='hook_before_body_closing_tag'}
      {hook h='displayBeforeBodyClosingTag'}
    {/block}
  </body>

</html>
