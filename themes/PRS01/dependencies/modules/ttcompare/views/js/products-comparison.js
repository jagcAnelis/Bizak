/**
*  @author    TemplateTrip
*  @copyright 2015-2017 TemplateTrip. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
$(document).ready(function(){
	$(document).on('click', '.add_to_compare', function(e){
		e.preventDefault();
		if (typeof addToCompare != 'undefined')
			addToCompare(parseInt($(this).data('id-product')));
	});

	modelCompare();
	reloadProductComparison();
	compareButtonsStatusRefresh();
	totalCompareButtons();
});

function modelCompare() {
	var modelCompare = '';
		modelCompare += '<div id="ttcompare-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'
			modelCompare += '<div class="modal-dialog" role="document">'
				modelCompare += '<div class="modal-content">'
					modelCompare += '<div class="modal-header">'
						modelCompare += '<button type="button" class="close" data-dismiss="modal" aria-label="Close">'
							modelCompare += '<span aria-hidden="true">&times;</span>'
						modelCompare += '</button>'
						modelCompare += '<div class="modal-msg">'
						modelCompare += '</div>'
					modelCompare += '</div>'
					modelCompare += '<div class="modal-footer">'
					modelCompare += '</div>'
				modelCompare += '</div>'
			modelCompare += '</div>'
		modelCompare += '</div>'

	$('body').append(modelCompare);
}

function addToCompare(productId)
{
	var totalValueNow = parseInt($('.bt_compare').next('.compare_product_count').val());
	var action, totalVal;
	var content_max_product = '<div class="compare-msg">' + max_item + '</div>';
	var content_add_product = '<div class="compare-msg">' + compareAdd + '</div>';
	var content_remove_product = '<div class="compare-msg">' + compareRemove + '</div>';
	var content_compare_product = '<a class="btn btn-secondary" href="' + compareUrl + '">' + compareView + '</a>';
	if ($.inArray(parseInt(productId),comparedProductsIds) === -1)
		action = 'add';
	else
		action = 'remove';

	$.ajax({
		url: compareUrl + '?ajax=1&action=' + action + '&id_product=' + productId,
		async: true,
		cache: false,
		success: function(data) {
			if (action === 'add' && comparedProductsIds.length < comparator_max_item) {
				comparedProductsIds.push(parseInt(productId)),
				compareButtonsStatusRefresh(),
				totalVal = totalValueNow +1,
				$('.bt_compare').next('.compare_product_count').val(totalVal),
				totalValue(totalVal);
				$('#ttcompare-modal .modal-msg').html(content_add_product);
				$('#ttcompare-modal .modal-footer').html(content_compare_product);
				$('#ttcompare-modal').modal('show');
			} else if (action === 'remove') {
				comparedProductsIds.splice($.inArray(parseInt(productId), comparedProductsIds), 1),
				compareButtonsStatusRefresh(),
				totalVal = totalValueNow -1,
				$('.bt_compare').next('.compare_product_count').val(totalVal),
				totalValue(totalVal);
				$('#ttcompare-modal .modal-msg').html(content_remove_product);
				$('#ttcompare-modal .modal-footer').html(content_compare_product);
				$('#ttcompare-modal').modal('show');
			} else {
				$('#ttcompare-modal .modal-msg').html(content_max_product);
				$('#ttcompare-modal .modal-footer').html(content_compare_product);
				$('#ttcompare-modal').modal('show');
			}
			totalCompareButtons();
		},
		error: function(){},
		beforeSend: function(){
            $(".add_to_compare[data-id-product='"+productId+"']").addClass('adding');
        },
        complete: function(){
            $(".add_to_compare[data-id-product='"+productId+"']").removeClass('adding');
        }
	});
}

function reloadProductComparison()
{
	$(document).on('click', 'a.cmp_remove', function(e){
		e.preventDefault();
		var idProduct = parseInt($(this).data('id-product'));
		$.ajax({
			url: compareUrl + '?ajax=1&action=remove&id_product=' + idProduct,
			async: false,
			cache: false
		});
		$('td.product-' + idProduct).fadeOut(600);
	});
};

function compareButtonsStatusRefresh()
{
	$('.add_to_compare').each(function() {
		if ($.inArray(parseInt($(this).data('id-product')), comparedProductsIds) !== -1)
			$(this).addClass('checked');
		else
			$(this).removeClass('checked');
	});
}

function totalCompareButtons()
{
	var totalProductsToCompare = parseInt($('.bt_compare .total-compare-val').html());
	if (typeof totalProductsToCompare !== "number" || totalProductsToCompare === 0)
		$('.bt_compare').attr("disabled",true);
	else
		$('.bt_compare').attr("disabled",false);
}

function totalValue(value)
{
	$('.bt_compare').find('.total-compare-val').html(value);
}

function get(name)
{
	var regexS = "[\\?&]" + name + "=([^&#]*)";
	var regex = new RegExp(regexS);
	var results = regex.exec(window.location.search);

	if (results == null)
		return "";
	else
		return results[1];
}
