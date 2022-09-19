{*
 * 2016 Terranet
 *
 * NOTICE OF LICENSE
 *
 * @author    Terranet
 * @copyright 2016 Terranet
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*}

{extends file="helpers/form/form.tpl"}

{block name='after'}
    {if $submit_action == 'submitGshoppingfeedModule'}
        <div class="panel text-center rev_before">
            <span data-tab="gshoppingfeed_action" class="step-title tab">{l s='Step 2' mod='gshoppingfeed'}</span>
            <p class="help-block">
                {l s='Configuration and filter generation template' mod='gshoppingfeed'}
            </p>
        </div>
        {if (isset($fields.form.form.anchor) && $fields.form.form.anchor == 1)}
            <script type="text/javascript">
                openGSFConfigurationList({if (isset($fields.form.form.getLink) && $fields.form.form.getLink == 1)}1{else}0{/if});
            </script>
        {elseif (isset($fields.form.form.newForm) && $fields.form.form.newForm == 1)}
            <script type="text/javascript">
                openGSFConfigurationNewList();
            </script>
        {/if}
    {/if}
{/block}

{block name="input"}
    {if $input.type == 'custom_param'}
        <div class="row">
            <div class="col-md-6">
                <input type="text" id="added_custom_param" value="">
            </div>
            <div class="col-md-4">
                {if isset($input.features) && count($input.features)}
                    <select id="added_custom_param_feature">
                        {foreach from=$input.features item=feature}
                            <option value="{$feature['id_feature']|intval}">{$feature['name']|escape:'htmlall':'UTF-8'}</option>
                        {/foreach}
                    </select>
                {/if}
            </div>
            <div class="col-md-2">
                <span class="btn btn-default w100 add_new_custom_param">
                    {l s='add' mod='gshoppingfeed'}
                </span>
            </div>
            <input type="hidden" class="remove_tr_msg" value="{l s='These param will be deleted for good. Please confirm.' mod='gshoppingfeed'}">
        </div>
        <div class="row">
            <div class="col-md-12">
                <ul id="features_custom_selected">
                    {if isset($fields_value.features_custom_mod)
                    && is_array($fields_value.features_custom_mod)
                    && count($fields_value.features_custom_mod)}
                        {foreach from=$fields_value.features_custom_mod item=feature}
                            <li>
                                <input type="hidden" name="feature_custom_inheritage[]" value="{$feature['id_feature']|escape:'htmlall':'UTF-8'}">
                                <input type="hidden" name="feature_custom_inheritage_param[]" value="{$feature['unit']|urlencode|escape:'htmlall':'UTF-8'}">
                                <span class="feature_custom">
                                    < {$feature['unit']|escape:'htmlall':'UTF-8'} > {$feature['name']|escape:'htmlall':'UTF-8'} < /{$feature['unit']|escape:'htmlall':'UTF-8'} >
                                </span>
                                <span class="feature_removed"><i class="material-icons">delete</i></span>
                            </li>
                        {/foreach}
                    {/if}
                </ul>
            </div>
        </div>
    {elseif $input.type == 'textarea_clean'}

        <div class="row">
            <div class="col-lg-9">
                <textarea{if isset($input.readonly) && $input.readonly} readonly="readonly"{/if} name="{$input.name|escape:'htmlall':'UTF-8'}" id="{if isset($input.id)}{$input.id|escape:'htmlall':'UTF-8'}{else}{$input.name|escape:'htmlall':'UTF-8'}{/if}" {if isset($input.cols)}cols="{$input.cols|escape:'htmlall':'UTF-8'}"{/if} {if isset($input.rows)}rows="{$input.rows|escape:'htmlall':'UTF-8'}"{/if} class="{if isset($input.class)} {$input.class|escape:'htmlall':'UTF-8'}{/if}">{$fields_value[$input.name]|escape:'html':'UTF-8'}</textarea>
            </div>
            <div class="col-lg-2">
            </div>
        </div>

    {elseif $input.type == 'custom_attribute'}

        <div class="custom_attribute">
            <div class="row attribute-mod-container">
                <div class="col-lg-6">
                    <div style="width: 100%;" class="input-group input">
                        <input type="text" id="custom_attribute_name" placeholder="{l s='key-name' mod='gshoppingfeed'}" value="" class="custom_attribute_name input">
                        <p class="help-block"></p>
                    </div>
                </div>
                <div class="col-lg-5">
                    <select style="width: 100%;" class="custom_attribute_section">
                    {foreach $input.options.query AS $option}
                        {if $option == "-"}
                            <option value="">-</option>
                        {else}
                            <option value="{$option[$input.options.id]|escape:'htmlall':'UTF-8'}">{$option[$input.options.name]|escape:'htmlall':'UTF-8'}</option>
                        {/if}
                    {/foreach}
                    </select>
                </div>
                <div class="col-lg-1">
                    <button onclick="return false;" class="btn btn-default js-add-new-custom-atr">
                        {l s='Add' mod='gshoppingfeed'}
                    </button>
                </div>
            </div>
            {if isset($fields_value.custom_attribute)}
            {foreach from=$fields_value.custom_attribute item='customAttr'}
            <div class="row dec-row">
                <input type="hidden" name="custom_attr_key[]" value="{$customAttr['unit']|escape:'htmlall':'UTF-8'}">
                <input type="hidden" name="custom_attr_id[]" value="{$customAttr['id_attribute']|escape:'htmlall':'UTF-8'}">
                <div class="col-md-11">
                    <span class="example-row">
                    &lt;:g {$customAttr['unit']|escape:'htmlall':'UTF-8'}> {$customAttr['name']|escape:'htmlall':'UTF-8'} &lt;/:g {$customAttr['unit']|escape:'htmlall':'UTF-8'}>
                    </span>
                </div>
                <div class="col-lg-1">
                    <span class="js-remove-attr-line"><i class="material-icons">delete</i></span>
                </div>
            </div>
            {/foreach}
            {/if}
        </div>

    {elseif $input.type == 'switch_with_inp'}
        <div class="row">
            <div class="col-md-12">
                <div class="btn-group-switch-with-inp">
                    <span class="switch prestashop-switch fixed-width-lg">
                        {foreach $input.values as $value}
                            <input type="radio" name="{$input.name|escape:'htmlall':'UTF-8'}"{if $value.value == 1} id="{$input.name|escape:'htmlall':'UTF-8'}_on"{else} id="{$input.name|escape:'htmlall':'UTF-8'}_off"{/if} value="{$value.value|escape:'htmlall':'UTF-8'}"{if $fields_value[$input.name] == $value.value} checked="checked"{/if}{if (isset($input.disabled) && $input.disabled) or (isset($value.disabled) && $value.disabled)} disabled="disabled"{/if}/>
                        {strip}
                            <label {if $value.value == 1} for="{$input.name|escape:'htmlall':'UTF-8'}_on"{else} for="{$input.name|escape:'htmlall':'UTF-8'}_off"{/if}>
                            {if $value.value == 1}
                                {l s='Yes' d='Admin.Global' mod='gshoppingfeed'}
                            {else}
                                {l s='No' d='Admin.Global' mod='gshoppingfeed'}
                            {/if}
                        </label>
                        {/strip}
                        {/foreach}
                        <a class="slide-button btn"></a>
                    </span>
                    {$temporary_inp_field="`$input['name']`_inp"}
                    <input class="append-inp-with-with" value="{$fields_value[$temporary_inp_field]|escape:'htmlall':'UTF-8'}" name="{$input.name|escape:'htmlall':'UTF-8'}_inp" placeholder="{$input.placeholder|escape:'htmlall':'UTF-8'}" type="text">
                </div>
            </div>
        </div>
    {else}
        {$smarty.block.parent}
    {/if}
{/block}
