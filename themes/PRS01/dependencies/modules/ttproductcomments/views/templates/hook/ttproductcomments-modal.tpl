{*
*  @author    Templatetrip
*  @copyright 2015-2017 Templatetrip. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*}

<div id="ttcomment-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h2 class="modal-title">
                    {l s='Write a review' mod='ttproductcomments'}
                </h2>
            </div>
            <div class="modal-body">
                <div class="row">
                    {if isset($comment_product) && $comment_product}
                        <div class="product clearfix col-xs-12 col-sm-6">
                            <img src="{$productcomment_cover_image}" alt="{$comment_product->name|escape:'html':'UTF-8'}" />
                            <div class="product_desc">
                                <p class="product_name">
                                    <strong>{$comment_product->name}</strong>
                                </p>
                                {$comment_product->description_short|escape:'html':'UTF-8' nofilter}
                            </div>
                        </div>
                    {/if}
                    <div class="new_comment_form_content col-xs-12 col-sm-6">
                        <form id="id_new_comment_form" action="#">
                            {if $criterions|@count > 0}
                                <ul id="criterions_list">
                                    {foreach from=$criterions item='criterion'}
                                        <li>
                                            <label>{$criterion.name|escape:'html':'UTF-8'}:</label>
                                            <div class="star_content">
                                                <input class="star" type="radio" name="criterion[{$criterion.id_product_comment_criterion|round}]" value="1" />
                                                <input class="star" type="radio" name="criterion[{$criterion.id_product_comment_criterion|round}]" value="2" />
                                                <input class="star" type="radio" name="criterion[{$criterion.id_product_comment_criterion|round}]" value="3" />
                                                <input class="star" type="radio" name="criterion[{$criterion.id_product_comment_criterion|round}]" value="4" />
                                                <input class="star" type="radio" name="criterion[{$criterion.id_product_comment_criterion|round}]" value="5" checked="checked" />
                                            </div>
                                            <div class="clearfix"></div>
                                        </li>
                                    {/foreach}
                                </ul>
                            {/if}
                            <div class="form-group">
                                <label class="form-control-label" for="comment_title">
                                    {l s='Title:' mod='ttproductcomments'} <sup class="required">*</sup>
                                </label>
                                <input class="form-control" id="comment_title" name="title" type="text" value=""/>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label" for="content">
                                    {l s='Comment:' mod='ttproductcomments'} <sup class="required">*</sup>
                                </label>
                                <textarea class="form-control" id="content" name="content" type="text"></textarea>
                            </div>
                            {if $allow_guests == true && !$logged}
                                <div class="form-group">
                                    <label class="form-control-label" for="customer_name">
                                        {l s='Your name:' mod='ttproductcomments'} <sup class="required">*</sup>
                                    </label>
                                    <input class="form-control" id="commentCustomerName" name="customer_name" type="text" value=""/>
                                </div>
                            {/if}
                            <div class="form-group">
                                <label class="form-control-label">
                                    <sup class="required">*</sup> {l s='Required fields' mod='ttproductcomments'}
                                </label>
                                <input id="id_product_comment_send" name="id_product" type="hidden" value='{$id_product_comment_form}' />
                            </div>
                        </form>
                        <div id="new_comment_form_error" class="error" style="display: none; padding: 15px 0px">
                            <ul></ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{l s='Close' mod='ttproductcomments'}</button>
                <button id="submitNewMessage" name="submitMessage" type="submit" class="btn btn-primary">
                    <span>{l s='Submit' mod='ttproductcomments'}</span>
                </button>
            </div>
        </div>
    </div>
</div>
