{*
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 * @author    Línea Gráfica E.C.E. S.L.
 * @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 * @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *            https://www.lineagrafica.es/licenses/license_es.pdf
 *            https://www.lineagrafica.es/licenses/license_fr.pdf
 *}
{literal}
<style>
	/* CSS for versions >= 1.5 and < 1.6 */
	.lgcookieslaw_banner {
        display:{/literal}{if $hidden}none{else}table{/if};{literal}
		width:100%;
		position:fixed;
		left:0;
		repeat-x scroll left top;
		background: {/literal}{$bgcolor|escape:'html':'UTF-8'}{literal};
		border-color: {/literal}{$bgcolor|escape:'html':'UTF-8'}{literal};
		border-left: 1px solid {/literal}{$bgcolor|escape:'html':'UTF-8'}{literal};
		border-radius: 3px 3px 3px 3px;
		border-right: 1px solid {/literal}{$bgcolor|escape:'html':'UTF-8'}{literal};
		color: {/literal}{$fontcolor|escape:'html':'UTF-8'}{literal} !important;
		z-index: 99999;
		border-style: solid;
		border-width: 1px;
		margin: 0;
		outline: medium none;
		text-align: center;
		vertical-align: middle;
		text-shadow: 0 0 0 0;
		-webkit-box-shadow: 0px 1px 5px 0px {/literal}{$shadowcolor|escape:'html':'UTF-8'}{literal};
		-moz-box-shadow:    0px 1px 5px 0px {/literal}{$shadowcolor|escape:'html':'UTF-8'}{literal};
		box-shadow:         0px 1px 5px 0px {/literal}{$shadowcolor|escape:'html':'UTF-8'}{literal};
		font-size: 12px;
	{/literal}
		{$position|escape:'htmlall':'UTF-8'};
		{$opacity|escape:'htmlall':'UTF-8'};
	{literal}
	}

	.lgcookieslaw_banner > form
	{
		position:relative;
	}

	.lgcookieslaw_banner span.lgcookieslaw_btn
	{
		border-color: {/literal}{$btn1_bgcolor|escape:'html':'UTF-8'}{literal} !important;
		background: {/literal}{$btn1_bgcolor|escape:'html':'UTF-8'}{literal} !important;
		color: {/literal}{$btn1_fontcolor|escape:'html':'UTF-8'}{literal} !important;
		text-align: center;
		margin: 0;
		padding: 0 5px;
		display: inline-block;
		border: 0;
		font-weight: bold;
		height: 26px;
		line-height: 26px;
		width: auto;
		font-size: 12px;
		cursor: pointer;
	}

	.lgcookieslaw_banner span:hover.lgcookieslaw_btn
	{
		moz-opacity:0.85;
		opacity: 0.85;
		filter: alpha(opacity=85);
	}

	.lgcookieslaw_banner a.lgcookieslaw_btn
	{
		border-color: {/literal}{$btn2_bgcolor|escape:'html':'UTF-8'}{literal};
		background: {/literal}{$btn2_bgcolor|escape:'html':'UTF-8'}{literal};
		color: {/literal}{$btn2_fontcolor|escape:'html':'UTF-8'}{literal} !important;
		margin: 0;
		padding: 0 5px;
		display: inline-block;
		border: 0;
		font-weight: bold;
		height: 26px;
		line-height: 26px;
		width: auto;
		font-size: 12px;
	}

	@media (max-width: 768px) {
		.lgcookieslaw_banner span.lgcookieslaw_btn,
		.lgcookieslaw_banner a.lgcookieslaw_btn {
			height: auto;
		}
	}

	.lgcookieslaw_banner > form a:hover.lgcookieslaw_btn
	{
		moz-opacity:0.85;
		opacity: 0.85;
		filter: alpha(opacity=85);
	}

	.lgcookieslaw_close_banner_btn
	{
		cursor:pointer;
		height:21px;
		max-width:21px;
		width:21px;
	}

	.lgcookieslaw_container {
		display:table;
		margin: 0 auto;
	}

	.lgcookieslaw_button_container {
		display:table-cell;
		padding: 5px 0;
		vertical-align: middle;
	}

	.lgcookieslaw_button_container div{
		display:table-cell;
		padding: 0px 5px 0px 0px;
		vertical-align: middle;
	}

	.lgcookieslaw_message {
		display:table-cell;
		font-size: 12px;
		padding: 5px 20px 5px 5px;
		vertical-align: middle;
	}

	.lgcookieslaw_btn-close {
		position:absolute;
		right:5px;
		top:5px;
	}
</style>
{/literal}
{include file='./cookieslaw_header_code.tpl'}