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

{extends file="helpers/form/form.tpl"}
{block name="field"}
    {if $input.type == 'number'}
	<div class="col-lg-{if isset($input.col)}{$input.col|intval}{else}9{/if}{if !isset($input.label)} col-lg-offset-3{/if}">
        <input type="number"
            id="{if isset($input.id)}{$input.id}{else}{$input.name}{/if}"
            name="{$input.name}"
            class="form-control {if isset($input.class)} {$input.class} {/if}"
            onkeyup="return (function (el, e) {
                if (e.keyCode == 8) return true;
                jQuery(el).val((parseInt(jQuery(el).val()) || 0));
                if (jQuery(el).val() < (parseInt(jQuery(el).attr('min')) || 0)) {
                    jQuery(el).val((parseInt(jQuery(el).attr('min')) || 0));
                } else if (jQuery(el).val() > (parseInt(jQuery(el).attr('max')) || 0)) {
                    jQuery(el).val((parseInt(jQuery(el).attr('max')) || 0));
                }
            })(this, event);"
            value="{$fields_value[$input.name]|escape:'html':'UTF-8'}"
            {if isset($input.size)} size="{$input.size}"{/if}
            {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
            {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
            {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
            {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
            {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
            {if isset($input.required) && $input.required} required="required" {/if}
            {if isset($input.max)} max="{$input.max|intval}"{/if}
            {if isset($input.min)} min="{$input.min|intval}"{/if}
            {if isset($input.placeholder) && $input.placeholder} placeholder="{$input.placeholder}"{/if} />
            {if isset($input.suffix)}
            <span class="input-group-addon">
                {$input.suffix}
            </span>
            {/if}
	</div>
    {elseif $input.type == 'file_image'}
        <div class="col-lg-9">
            <div class="form-group">
                <div class="col-lg-6">
                    <input id="{$input.name}" type="file" name="{$input.name}" class="hide" />
                    <div class="dummyfile input-group">
                        <span class="input-group-addon"><i class="icon-file"></i></span>
                        <input id="{$input.name}-name" type="text" class="disabled" name="filename" readonly />
                        <span class="input-group-btn">
                        <button id="{$input.name}-selectbutton" type="button" name="submitAddAttachments" class="btn btn-default">
                            <i class="icon-folder-open"></i> {l s='Choose a file' mod='anscrolltop'}
                        </button>
                    </span>
                    </div>
                    <p class="help-block">
                        {l s='Allowed formats are: .svg, .gif, .jpg, .png' mod='anscrolltop'}
                    </p>
                </div>
            </div>
            {if isset($input.desc) && !empty($input.desc)}
                <p class="help-block">
                    {$input.desc}
                </p>
            {/if}
        </div>
        <script type="text/javascript">
            $(document).ready(function(){
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
    {else}
        {$smarty.block.parent}
    {/if}
{/block}
