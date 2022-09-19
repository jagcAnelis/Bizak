{if $an_staticblock->formdata}
<div class="visitors-block"
data-min-value="{$an_staticblock->formdata->additional_field_real_time_visitor_counter_minValue}"
data-max-value="{$an_staticblock->formdata->additional_field_real_time_visitor_counter_maxValue}"
data-stroke-value="{$an_staticblock->formdata->additional_field_real_time_visitor_counter_strokeValue}"
data-min-interval="{$an_staticblock->formdata->additional_field_real_time_visitor_counter_minInterval}"
data-max-interval="{$an_staticblock->formdata->additional_field_real_time_visitor_counter_maxInterval}"
>
    <div class="visitors-block-text">
        <span class="label">{l s='Real time:' mod='anthemeblocks'}</span>
        <span class="visitors-counter">{$an_staticblock->formdata->additional_field_real_time_visitor_counter_minValue}</span>
        <span class="label_visitors">{l s='Visitor right now' mod='anthemeblocks'}</span>
    </div>
</div>
{/if}