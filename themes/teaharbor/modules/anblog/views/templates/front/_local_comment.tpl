{*
* 2018 Anvanto
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
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
*  @author Anvanto (anvantoco@gmail.com)
*  @copyright  2018 anvanto.com

*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div id="blog-localengine">
	{if $comments}
		{if $config->get('item_show_listcomment','1') == 1}
			<h4 class="comment-list-title">{l s='Comments' d='Shop.Theme.Global'}</h4>

			<div class="comments clearfix">
			    <div class="comments-list">
                    {foreach from=$comments item=comment name=comment} {$default=''}
                    <div class="comment-item" id="comment{$comment.id_anblog_comment|escape:'html':'UTF-8'}">
                        <div class="comment-wrap">
                             <div class="comment-content">
                                {$comment.comment|nl2br nofilter}{* HTML form , no escape necessary *}
                            </div>
                            <div class="comment-meta">
                                <span class="comment-infor">
                                    <span class="comment-created">
                                        <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="14px" height="14px">
                                        <path fill-rule="evenodd"  fill="rgb(198, 198, 198)"
                                        d="M7.123,0.500 C8.917,0.500 10.541,1.230 11.721,2.403 C12.895,3.583 13.625,5.206 13.625,7.000 C13.625,8.794 12.895,10.417 11.721,11.597 C10.541,12.771 8.917,13.500 7.123,13.500 C5.328,13.500 3.704,12.771 2.530,11.597 C1.350,10.417 0.625,8.794 0.625,7.000 C0.625,5.206 1.350,3.583 2.529,2.403 C3.704,1.230 5.328,0.500 7.123,0.500 L7.123,0.500 ZM10.437,6.645 C10.631,6.645 10.786,6.805 10.786,7.000 C10.786,7.195 10.631,7.355 10.437,7.355 L7.133,7.355 L7.123,7.355 C6.993,7.355 6.878,7.280 6.818,7.175 L6.813,7.170 L6.813,7.170 L6.808,7.160 L6.808,7.160 L6.808,7.155 L6.808,7.155 L6.803,7.145 L6.803,7.145 L6.798,7.135 L6.798,7.135 L6.798,7.130 L6.798,7.130 L6.793,7.119 L6.793,7.119 L6.788,7.115 L6.788,7.115 L6.788,7.104 L6.788,7.104 L6.783,7.095 L6.783,7.095 L6.783,7.089 L6.783,7.089 L6.777,7.079 L6.777,7.079 L6.777,7.069 L6.777,7.069 L6.777,7.064 L6.777,7.059 L6.772,7.054 L6.772,7.054 L6.772,7.044 L6.772,7.044 L6.772,7.034 L6.772,7.034 L6.772,7.025 L6.772,7.025 L6.772,7.020 L6.772,7.020 L6.772,7.010 L6.772,7.010 L6.772,7.000 L6.772,7.000 L6.772,2.768 C6.772,2.573 6.927,2.419 7.122,2.419 C7.317,2.419 7.477,2.573 7.477,2.768 L7.477,6.645 L10.437,6.645 ZM11.221,2.903 C10.171,1.854 8.722,1.210 7.123,1.210 C5.523,1.210 4.074,1.854 3.024,2.903 C1.980,3.952 1.330,5.401 1.330,7.000 C1.330,8.599 1.980,10.047 3.024,11.097 C4.074,12.146 5.523,12.791 7.123,12.791 C8.722,12.791 10.171,12.146 11.221,11.097 C12.270,10.048 12.920,8.599 12.920,7.000 C12.920,5.401 12.271,3.952 11.221,2.903 L11.221,2.903 Z"/>
                                        </svg>
                                      <span>{l s='Created On' d='Shop.Theme.Global'}{strtotime($comment.date_add)|date_format:"%A, %e/%m/%Y"|escape:'html':'UTF-8'}</span>
                                    </span>
                                    <span class="comment-postedby"><i class="material-icons">person</i><span>{l s='Posted By' d='Shop.Theme.Global'}: {$comment.user|escape:'html':'UTF-8'}</span></span>
                                </span>


                            </div>


                        </div>
                    </div>
                    {/foreach}
                </div>
				{if $blog_count_comment}
				<div class="top-pagination-content clearfix bottom-line">
					{include file="module:anblog/views/templates/front/_pagination.tpl"}
				</div>
				{/if}
			</div>
		{/if}
	{/if}	
		{if $config->get('item_show_formcomment','1') == 1}
			<h4>{l s='Leave a comment' d='Shop.Theme.Global'}</h4>
			<form class="form-horizontal clearfix" method="post" id="comment-form" action="{$blog_link|escape:'html':'UTF-8'}" onsubmit="return false;">
				<div class="row">
					<div class="form-group col-md-4">
						<input type="text" name="user" placeholder="{l s='Name' d='Shop.Theme.Global'}" id="inputFullName" class="form-control">
					</div>

					<div class="form-group col-md-4">
						<input type="text" name="email"  placeholder="{l  s='Email' d='Shop.Theme.Global'}" id="inputEmail" class="form-control">
					</div>

					<div class="form-group col-md-12">
						<textarea type="text" name="comment" rows="8"  placeholder="{l  s='Your comment' d='Shop.Theme.Global'}" id="inputComment" class="form-control"></textarea>
						<div class="blog-comment-note">{l  s='Please note, comments must be approved before they are published' d='Shop.Theme.Global'}</div>
					</div>
					{if $config->get('google_captcha_status')}
						<div class="form-group col-md-12">
							<div class="ipts-captcha">
								<div class="g-recaptcha" data-sitekey="{$config->get('google_captcha_site_key')}"></div>
							</div>
						</div>
					{/if}
					
					<input type="hidden" name="id_anblog_blog" value="{$id_anblog_blog|intval}">
					<div class="form-group col-md-12">
						<button class="btn btn-primary btn-submit-comment-wrapper" name="submitcomment" type="submit">
							<span class="btn-submit-comment">{l s='Post comment' d='Shop.Theme.Global'}</span>
							<span class="anblog-cssload-container cssload-speeding-wheel"></span>
						</button>
					</div>
				</div>
			</form>
		{/if}
</div>