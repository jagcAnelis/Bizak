/**
*  @author    TemplateTrip
*  @copyright 2015-2017 TemplateTrip. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

var wishlistProductsIds = [];
$(document).ready(function(){
    wishlistRefreshStatus();
    modelWishlist();
    $(document).on('change', 'select[name=wishlists]', function(){
        WishlistChangeDefault('wishlist_block_list', $(this).val());
    });


    $('.wishlist').each(function() {
        current = $(this);
        var htmlContent = $(this).children('.popover-content').html();
        $(this).children('.wishlist_button_list').popover({
            html: true,
            content: htmlContent
        });
    });
});

function modelWishlist() {
    var modelWishlist = '';
    modelWishlist += '<div id="ttwishlist-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'
    modelWishlist += '<div class="modal-dialog" role="document">'
    modelWishlist += '<div class="modal-content">'
    modelWishlist += '<div class="modal-header">'
    modelWishlist += '<button type="button" class="close" data-dismiss="modal" aria-label="Close">'
    modelWishlist += '<span aria-hidden="true">&times;</span>'
    modelWishlist += '</button>'
    modelWishlist += '<div class="modal-msg">'
    modelWishlist += '</div>'
    modelWishlist += '</div>'
    modelWishlist += '<div class="modal-footer">'
    modelWishlist += '</div>'
    modelWishlist += '</div>'
    modelWishlist += '</div>'
    modelWishlist += '</div>'

    $('body').append(modelWishlist);
}
function WishlistCart(id, action, id_product, id_product_attribute, quantity, id_wishlist)
{
    var content_add_wishlist = '<div class="compare-msg">' + added_to_wishlist + '</div>';
    var content_login_req = '<div class="compare-msg">' + loggin_required + '</div>';
    var content_wishlist_button = '<a class="btn btn-secondary" href="' + mywishlist_url + '">' + wishlistView + '</a>';
    var content_login_button = '<a class="btn btn-secondary" href="' + login_url + '">' + loginLabel + '</a>';
    $.ajax({
        type: 'GET',
        url: baseDir + 'modules/ttproductwishlist/cart.php?rand=' + new Date().getTime(),
        headers: { "cache-control": "no-cache" },
        async: true,
        cache: false,
        data: 'action=' + action + '&id_product=' + id_product + '&quantity=' + quantity + '&token=' + static_token + '&id_product_attribute=' + id_product_attribute + '&id_wishlist=' + id_wishlist,
        success: function(data)
        {
            if (action == 'add') {
                if (isLogged == true) {
                    wishlistProductsIdsAdd(id_product);
                    wishlistRefreshStatus();
                    $('.wishtlist_top .cart-wishlist-number').html(data);
                    $('#ttcompare-modal .modal-msg').html(content_add_wishlist);
                    $('#ttcompare-modal .modal-footer').html(content_wishlist_button);
                    $('#ttcompare-modal').modal('show');
                } else {
                    $('#ttcompare-modal .modal-msg').html(content_login_req);
                    $('#ttcompare-modal .modal-footer').html(content_login_button);
                    $('#ttcompare-modal').modal('show');
                }
            }
            if (action == 'delete') {
                wishlistProductsIdsRemove(id_product);
                wishlistRefreshStatus();
            }
            if($('#' + id).length != 0) {
                $('#' + id).slideUp('normal');
                document.getElementById(id).innerHTML = data;
                $('#' + id).slideDown('normal');
            }
        }
    });
}

/**
* Change customer default wishlist
*
* @return void
*/
function WishlistChangeDefault(id, id_wishlist)
{
    $.ajax({
        type: 'GET',
        url: baseDir + 'modules/ttproductwishlist/cart.php?rand=' + new Date().getTime(),
        headers: { "cache-control": "no-cache" },
        async: true,
        data: 'id_wishlist=' + id_wishlist + '&token=' + static_token,
        cache: false,
        success: function(data)
        {
            $('#' + id).slideUp('normal');
            document.getElementById(id).innerHTML = data;
            $('#' + id).slideDown('normal');
        }
    });
}

/**
* Show wishlist managment page
*
* @return void
*/
function WishlistManage(id, id_wishlist)
{
    $.ajax({
        type: 'GET',
        async: true,
        url: baseDir + 'modules/ttproductwishlist/managewishlist.php?rand=' + new Date().getTime(),
        headers: { "cache-control": "no-cache" },
        data: 'id_wishlist=' + id_wishlist + '&refresh=' + false,
        cache: false,
        success: function(data)
        {
            $('#' + id).hide();
            document.getElementById(id).innerHTML = data;
            $('#' + id).fadeIn('slow');

            $('.wishlist_change_button').each(function(index) {
                $(this).change(function () {
                    wishlistProductChange($('option:selected', this).attr('data-id-product'), $('option:selected', this).attr('data-id-product-attribute'), $('option:selected', this).attr('data-id-old-wishlist'), $('option:selected', this).attr('data-id-new-wishlist'));
                });
            });
        }
    });
}

/**
* Show wishlist product managment page
*
* @return void
*/
function WishlistProductManage(id, action, id_wishlist, id_product, id_product_attribute, quantity, priority)
{
    $.ajax({
        type: 'GET',
        async: true,
        url: baseDir + 'modules/ttproductwishlist/managewishlist.php?rand=' + new Date().getTime(),
        headers: { "cache-control": "no-cache" },
        data: 'action=' + action + '&id_wishlist=' + id_wishlist + '&id_product=' + id_product + '&id_product_attribute=' + id_product_attribute + '&quantity=' + quantity + '&priority=' + priority + '&refresh=' + true,
        cache: false,
        success: function(data)
        {
            if (action == 'delete') {
                $('#wlp_' + id_product + '_' + id_product_attribute).fadeOut('fast');
            } else if (action == 'update') {
                $('#wlp_' + id_product + '_' + id_product_attribute).fadeOut('fast');
                $('#wlp_' + id_product + '_' + id_product_attribute).fadeIn('fast');
            }
            nb_products = 0;
            $("[id^='quantity']").each(function(index, element){
                nb_products += parseInt(element.value);
            });
            $("#wishlist_"+id_wishlist).children('td').eq(1).html(nb_products);
        }
    });
}

/**
* Delete wishlist
*
* @return boolean succeed
*/
function WishlistDelete(id, id_wishlist, msg)
{
    var res = confirm(msg);
    if (res == false) {
        return (false);
    }

    if (typeof mywishlist_url == 'undefined') {
        return (false);
    }

    $.ajax({
        type: 'GET',
        async: true,
        dataType: "json",
        url: mywishlist_url,
        headers: { "cache-control": "no-cache" },
        cache: false,
        data: {
            rand: new Date().getTime(),
            deleted: 1,
            myajax: 1,
            id_wishlist: id_wishlist,
            action: 'deletelist'
        },
        success: function(data)
        {
            var mywishlist_siblings_count = $('#' + id).siblings().length;
            $('#' + id).fadeOut('slow').remove();
            $("#block-order-detail").html('');
            if (mywishlist_siblings_count == 0) {
                $("#block-history").remove();
            }

            if (data.id_default) {
                var tt_default = $("#wishlist_"+data.id_default+" > .wishlist_default");
                $("#wishlist_"+data.id_default+" > .wishlist_default > a").remove();
                tt_default.append('<p class="is_wish_list_default"><i class="fa fa-check-square"></i></p>');
            }
        }
    });
}

function WishlistDefault(id, id_wishlist)
{
    if (typeof mywishlist_url == 'undefined') {
        return (false);
    }

    $.ajax({
        type: 'GET',
        async: true,
        url: mywishlist_url,
        headers: { "cache-control": "no-cache" },
        cache: false,
        data: {
            rand:new Date().getTime(),
            'default': 1,
            id_wishlist:id_wishlist,
            myajax: 1,
            action: 'setdefault'
        },
        success: function (data)
        {
            var old_default_id = $(".is_wish_list_default").parents("tr").attr("id");
            var tt_check = $(".is_wish_list_default").parent();
            $(".is_wish_list_default").remove();
            tt_check.append('<a href="#" onclick="javascript:event.preventDefault();(WishlistDefault(\''+old_default_id+'\', \''+old_default_id.replace("wishlist_", "")+'\'));"><i class="fa fa-square"></i></a>');
            var tt_default = $("#"+id+" > .wishlist_default");
            $("#"+id+" > .wishlist_default > a").remove();
            tt_default.append('<p class="is_wish_list_default"><i class="fa fa-check-square"></i></p>');
        }
    });
}

/**
* Send wishlist by email
*
* @return void
*/
function WishlistSend(id, id_wishlist, id_email)
{

    var email_list={};
    $('#submitTtWishlist').addClass('loading').attr('disabled', 'true');
    for(var i=1; i<=10; i++) {
        if($('#' + id_email + i).val() && !$('#' + id_email + i).attr('disabled')) {
            email_list[id_email + i] = $('#' + id_email + i).val();
        }
    }
    
    $.ajax({
        type: 'POST',
        headers: {"cache-control": "no-cache"},
        url: baseDir + 'modules/ttproductwishlist/sendwishlist.php',
        async: true,
        cache: false,
        data: {
            token: static_token,
            id_wishlist: id_wishlist,
            emails: email_list
        },
        success: function (data)
        {
            try {
                var emailResponse = JSON.parse(data);
                $.each(email_list, function( key, value ) {
                    var field = $('#'+key).parent().siblings('.form-control-label')
                    if(emailResponse[key] == 1){
                        field.css('color','green');
                        $('#'+key).attr('disabled', true);
                    } else {
                        field.css('color','red');
                    }
                });
                $('#submitTtWishlist').removeClass('loading').removeAttr('disabled');
            }
            catch(err) {
                alert('TECHNICAL ERROR: Please try again later.');
                $('#submitTtWishlist').removeClass('loading').removeAttr('disabled');
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert('TECHNICAL ERROR: Please try again later.');
        }
    });
}

function wishlistProductsIdsAdd(id)
{
     if ($.inArray(parseInt(id),wishlistProductsIds) == -1) {
        wishlistProductsIds.push(parseInt(id))
    }
}

function wishlistProductsIdsRemove(id)
{
    wishlistProductsIds.splice($.inArray(parseInt(id),wishlistProductsIds), 1)
}

function wishlistRefreshStatus()
{
    $('.addToWishlist').each(function(){
        if ($.inArray(parseInt($(this).attr('data-rel')),wishlistProductsIds)!= -1) {
            $(this).addClass('checked');
        } else {
            $(this).removeClass('checked');
        }
    });
}

function wishlistProductChange(id_product, id_product_attribute, id_old_wishlist, id_new_wishlist)
{
    if (typeof mywishlist_url == 'undefined') {
        return (false);
    }

    var quantity = $('#quantity_' + id_product + '_' + id_product_attribute).val();

    $.ajax({
        type: 'GET',
        url: mywishlist_url,
        headers: { "cache-control": "no-cache" },
        async: true,
        cache: false,
        dataType: "json",
        data: {
            id_product:id_product,
            id_product_attribute:id_product_attribute,
            quantity: quantity,
            priority: $('#priority_' + id_product + '_' + id_product_attribute).val(),
            id_old_wishlist:id_old_wishlist,
            id_new_wishlist:id_new_wishlist,
            myajax: 1,
            action: 'productchangewishlist'
        },
        success: function (data)
        {
            if (data.success == true) {
                $('#wlp_' + id_product + '_' + id_product_attribute).fadeOut('slow');
                $('#wishlist_' + id_old_wishlist + ' td:nth-child(2)').text($('#wishlist_' + id_old_wishlist + ' td:nth-child(2)').text() - quantity);
                $('#wishlist_' + id_new_wishlist + ' td:nth-child(2)').text(+$('#wishlist_' + id_new_wishlist + ' td:nth-child(2)').text() + +quantity);
            } else {
                if (!!$.prototype.fancybox) {
                    $.fancybox.open([
                    {
                        type: 'inline',
                        autoScale: true,
                        minHeight: 30,
                        content: '<p class="fancybox-error">' + data.error + '</p>'
                    }
                    ], {
                        padding: 0
                    });
                }
            }
        }
    });
}
