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
                        Allowed formats are: .gif, .jpg, .png
                    </p>
                </div>
            </div>
            <div class="form-group">
                {if isset($fields_value[$input.name]) && $fields_value[$input.name] != ''}
                    <div id="{$input.name}-images-thumbnails" class="col-lg-12">
                        <img src="{$uri}img/{$fields_value[$input.name]}" class="img-thumbnail" style="height: 150px; width: 150px;"/>
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
    {elseif $input.type == 'number' or $input.type == 'float'}
        <div class="{if isset($input.col)}col-lg-{$input.col}{else}col-lg-9{/if}">
			<div class="form-group">
				<div class="col-lg-9">
					<div class='input-group'>
						<input type="number"
							   id="{if isset($input.id)}{$input.id|intval}{else}{$input.name|escape:'htmlall':'UTF-8'}{/if}"
							   name="{$input.name|escape:'htmlall':'UTF-8'}"
							   class="form-control"
							   value="{$fields_value[$input.name]|escape:'html':'UTF-8'}"
								{if isset($input.size)} size="{$input.size|intval}"{/if}
								{if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
								{if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
								{if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
								{if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
								{if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
								{if isset($input.required) && $input.required} required="required" {/if}
								{if isset($input.max)} max="{$input.max|intval}"{/if}
								{if isset($input.min)} min="{$input.min|intval}"{/if}
								{if isset($input.placeholder) && $input.placeholder} placeholder="{$input.placeholder|escape:'htmlall':'UTF-8'}"{/if} />
						{if !empty($input.suffix)}
							<span class="input-group-addon">
							{$input.suffix|escape:'htmlall':'UTF-8'}
						</span>
						{/if}
					</div>
				</div>
			</div>
		</div>
		{elseif $input.type == 'html'}
			{if isset($input.html_content)}
				{if $input.html_content == 'hr'}
				<hr />
				{else}
				{$input.html_content}
				{/if}
			{else}
				{$input.name}
			{/if}
		{else}
        {$smarty.block.parent}
    {/if}
{/block}
