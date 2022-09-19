{*
 * 2016 Terranet
 *
 * NOTICE OF LICENSE
 *
 * @author    Terranet
 * @copyright 2016 Terranet
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*}

{extends file="helpers/list/list_footer.tpl"}

{block name="endForm"}
    {if ($list_id == 'gshoppingfeed_taxonomy')}
        <div class="panel col-lg-12">
            <div class="panel-heading">
                {l s='Bulk updates:' mod='gshoppingfeed'}
            </div>
            <div class="row">
                <div class="col-md-12 bulk-taxonomy-upd-container">
                    <button class="load-bulk-taxonomy-js">{l s='View Google categories list' mod='gshoppingfeed'}</button>
                </div>
            </div>
        </div>
    {/if}
    </form>
{/block}
