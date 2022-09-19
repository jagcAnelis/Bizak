{*
*  @author    TemplateTrip
*  @copyright 2015-2018 TemplateTrip. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*}
{if $comparator_max_item}
	<form method="post" action="{$compareUrl}" class="compare-form">
		<button type="submit" class="btn btn-primary button button-medium bt_compare bt_compare" disabled="disabled">
			<span>{l s='Compare' mod='ttcompare'} (<span class="total-compare-val">{count($compared_products)}</span>)</span>
		</button>
		<input type="hidden" name="compare_product_count" class="compare_product_count" value="{count($compared_products)}" />
	</form>
{/if}