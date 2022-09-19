/**
*  @author    Templatetrip
*  @copyright 2015-2017 Templatetrip. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

$(document).ready(function(){
	$('input.star').rating();
	$('.auto-submit-star').rating();
	$('.comparison_infos a').each(function(){
		var id_product_comment = parseInt($(this).data('id-product-comment'));
		if (id_product_comment) {
			$(this).click(function(e){
				e.preventDefault();
			});
			var htmlContent = $('#comments_' + id_product_comment).html();
			$(this).popover({
				placement : 'bottom', //placement of the popover. also can use top, bottom, left or right
				html: true, //needed to show html of course
				content : htmlContent  //this is the content of the html box. add the image here or anything you want really.
			});
		}
	});
});