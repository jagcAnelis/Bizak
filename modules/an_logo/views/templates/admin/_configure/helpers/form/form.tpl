{*
* 2007-2018 PrestaShop
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2018 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{extends file="helpers/form/form.tpl"}
{block name="field"}
    {if $input.type == 'file_image'}
        <div class="col-lg-9">
            <div class="form-group">
                <div class="col-lg-6">
                    <input id="{$input.name}" type="file" name="{$input.name}" class="hide" />
                    <div class="dummyfile input-group">
                        <span class="input-group-addon"><i class="icon-file"></i></span>
                        <input id="{$input.name}-name" type="text" class="disabled" name="filename" readonly />
                        <span class="input-group-btn">
                        <button id="{$input.name}-selectbutton" type="button" name="submitAddAttachments" class="btn btn-default">
                            <i class="icon-folder-open"></i> {l s='Choose a file' d='Modules.Banner.Shop'}
                        </button>
                        <button id="{$input.name}-deletebutton" type="button" onclick="" name="submitDeleteAttachments" class="btn btn-danger">
                            <i class="icon-remove-sign"></i> Delete
                        </button>
                    </span>
                    </div>
                    <p class="help-block">
                        Allowed formats are: .svg, .gif, .jpg, .png
                    </p>
                </div>
            </div>
            <div class="form-group">
                {if isset($fields_value[$input.name]) && $fields_value[$input.name] != ''}
                    <div id="{$input.name}-images-thumbnails" class="col-lg-12">
                        <img src="{$uri}img/{$fields_value[$input.name]}" class="img-thumbnail" style="max-height: 150px; max-width: 150px;"/>
                    </div>
                {/if}
            </div>
            {if isset($input.desc) && !empty($input.desc)}
                <p class="help-block">
                    {$input.desc}
                </p>
            {/if}
        </div>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#{$input.name}-deletebutton').click(function(e) {
                    $('#{$input.name}-name').val('delete');
                    this.form.submit();
                });
                $('#{$input.name}-selectbutton').click(function(e) {
                    $('#{$input.name}').trigger('click');
                });

                $('#{$input.name}-name').click(function(e) {
                    $('#{$input.name}').trigger('click');
                });

                $('#{$input.name}-name').on('dragenter', function(e) {
                    e.stopPropagation();
                    e.preventDefault();
                });

                $('#{$input.name}-name').on('dragover', function(e) {
                    e.stopPropagation();
                    e.preventDefault();
                });

                $('#{$input.name}-name').on('drop', function(e) {
                    e.preventDefault();
                    var files = e.originalEvent.dataTransfer.files;
                    $('#{$input.name}')[0].files = files;
                    $(this).val(files[0].name);
                });

                $('#{$input.name}').change(function(e) {
                    if ($(this)[0].files !== undefined)
                    {
                        var files = $(this)[0].files;
                        var name  = '';

                        $.each(files, function(index, value) {
                            name += value.name+', ';
                        });

                        $('#{$input.name}-name').val(name.slice(0, -2));
                    }
                    else // Internet Explorer 9 Compatibility
                    {
                        var name = $(this).val().split(/[\\/]/);
                        $('#{$input.name}-name').val(name[name.length-1]);
                    }
                });

                if (typeof {$input.name}_max_files !== 'undefined')
                {
                    $('#{$input.name}').closest('form').on('submit', function(e) {
                        if ($('#{$input.name}')[0].files.length > {$input.name}_max_files) {
                            e.preventDefault();
                        }
                    });
                }
            });
        </script>
	{elseif $input.type == 'codecssjs'}
        <div class="col-lg-9">
            <div class="form-group">
                <div {if  isset($input.classCol)}class="{$input.classCol|escape:'htmlall':'UTF-8'}"{/if}>
				{if $input.name == 'an_logo_codeCss'}
				<p><strong>{l s='Please, clean the cache of your browser, if you have changed this field.' mod='an_logo'}</strong></p>
				{/if}
					<textarea
							id="{if isset($input.id)}{$input.id|intval}{else}{$input.name|escape:'htmlall':'UTF-8'}{/if}"
							name="{$input.name|escape:'htmlall':'UTF-8'}"
							class="form-control"
							style="height: {$input.height|escape:'htmlall':'UTF-8'}"
							{if  isset($input.rows)}rows="{$input.rows|escape:'htmlall':'UTF-8'}"{/if}
							>{$fields_value[$input.name]|escape:'html':'UTF-8'}</textarea>		
				</div>
			</div>
			{block name="description"}
				{if isset($input.desc) && !empty($input.desc)}
					<p class="help-block">
						{if is_array($input.desc)}
							{foreach $input.desc as $p}
								{if is_array($p)}
									<span id="{$p.id}">{$p.text}</span><br />
								{else}
									{$p}<br />
								{/if}
							{/foreach}
						{else}
							{$input.desc}
						{/if}
					</p>
				{/if}
			{/block}
		</div>	
		{if $input.name == 'svg_textarea'}
		<script type="text/javascript">
			$(document).ready(function(){
				hideshow();
				$('input[type=radio][name=an_logo_view_type]').change(function() {
					hideshow();
				});
			});
			function hideshow() {
				$('textarea#svg_textarea').parents('.form-group').hide();
				$('input#an_logo_img-name').parents('.form-group').hide();
				if($("input[name='an_logo_view_type']:checked").val() == 'svg_text') {
					$('textarea#svg_textarea').parents('.form-group').show();
				} else if($("input[name='an_logo_view_type']:checked").val() == 'svg') {
					$('input#an_logo_img-name').parents('.form-group').show();
				}
			}
		</script>
		{/if}
    {else}
        {$smarty.block.parent}
    {/if}
{/block}
