{*
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 * @author    Línea Gráfica E.C.E. S.L.
 * @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 * @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *            https://www.lineagrafica.es/licenses/license_es.pdf
 *            https://www.lineagrafica.es/licenses/license_fr.pdf
 *}
<div id="lgcookieslaw_banner" class="lgcookieslaw_banner">
	{*<form method="post" action="">*}
		{if $buttons_position == 2 }
			{*<input name="aceptocookies" class="lgcookieslaw_btn" type="submit" value="{stripslashes($button1|escape:'quotes':'UTF-8')}" >*}
			<span id="lgcookieslaw_accept" class="lgcookieslaw_btn lgcookieslaw_btn_accept" onclick="closeinfo(true)">{stripslashes($button1|escape:'quotes':'UTF-8')}</span>
			<a id="lgcookieslaw_info" class="lgcookieslaw_btn" {if isset($cms_target) && $cms_target} target="_blank" {/if} href="{$cms_link|escape:'quotes':'UTF-8'}" >
				{stripslashes($button2|escape:'quotes':'UTF-8')}
			</a>
		{/if}
		<div class="lgcookieslaw_container">
			{if $buttons_position == 4}
				<div class="lgcookieslaw_button_container" style="padding:5px">
					{*<input name="aceptocookies" class="lgcookieslaw_btn" type="submit" value="{stripslashes($button1|escape:'quotes':'UTF-8')}" >*}
					<span id="lgcookieslaw_accept" class="lgcookieslaw_btn lgcookieslaw_btn_accept" onclick="closeinfo(true)">{stripslashes($button1|escape:'quotes':'UTF-8')}</span>
					<a id="lgcookieslaw_info" class="lgcookieslaw_btn" {if isset($cms_target) && $cms_target} target="_blank" {/if} href="{$cms_link|escape:'quotes':'UTF-8'}" >
						{stripslashes($button2|escape:'quotes':'UTF-8')}
					</a>
				</div>
			{/if}
			<div class="lgcookieslaw_message">{if version_compare($smarty.const._PS_VERSION_,'1.7.0','>=')}{$cookie_message nofilter}{* HTML CONTENT FROM TINYMCE *}{else}{stripslashes($cookie_message|escape:'quotes':'UTF-8')}{/if}</div>
			{if $buttons_position == 5}
				<div class="lgcookieslaw_button_container">
					<div>
						{*<input name="aceptocookies" class="lgcookieslaw_btn" type="submit" value="{stripslashes($button1|escape:'quotes':'UTF-8')}" >*}
						<span id="lgcookieslaw_accept" class="lgcookieslaw_btn lgcookieslaw_btn_accept" onclick="closeinfo(true)">{stripslashes($button1|escape:'quotes':'UTF-8')}</span>
					</div>
					<div>
						<a id="lgcookieslaw_info" class="lgcookieslaw_btn" {if isset($cms_target) && $cms_target} target="_blank" {/if} href="{$cms_link|escape:'quotes':'UTF-8'}" >
							{stripslashes($button2|escape:'quotes':'UTF-8')}
						</a>
					</div>
				</div>
			{/if}
		</div>
		{if $buttons_position == 3 }
			{*<input name="aceptocookies" class="lgcookieslaw_btn" type="submit" value="{stripslashes($button1|escape:'quotes':'UTF-8')}" >*}
			<span id="lgcookieslaw_accept" class="lgcookieslaw_btn lgcookieslaw_btn_accept" onclick="closeinfo(true)">{stripslashes($button1|escape:'quotes':'UTF-8')}</span>
			<a id="lgcookieslaw_info" class="lgcookieslaw_btn" {if isset($cms_target) && $cms_target} target="_blank" {/if} href="{$cms_link|escape:'quotes':'UTF-8'}" >
				{stripslashes($button2|escape:'quotes':'UTF-8')}
			</a>
		{/if}
		{if $show_close}
			<div id="lgcookieslaw_close" class="lgcookieslaw_btn-close">
				<img src="{$path_module|escape:'html':'UTF-8'}/views/img/close.png" alt="close" class="lgcookieslaw_close_banner_btn" onclick="closeinfo();">
			</div>
		{/if}
	{*</form>*}
</div>
