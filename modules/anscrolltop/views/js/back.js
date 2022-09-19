/**
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
*/

$(window).ready(function () {
	
  $('img.an_scrolltop-svg').each(function () {
    var imgObject = $(this);
    var imgID = imgObject.attr('id');
    var imgClass = 'an_scrolltop-svg';
    var imgURL = imgObject.attr('src');
	var svgColor = imgObject.attr('data-color');
	var imgWidth = imgObject.attr('data-width');

    $.ajax({
      url: imgURL,
      type: 'GET',
      success: function(data){
        if ($.isXMLDoc(data)) {
          // Get the SVG tag, ignore the rest
          var svg = $(data).find('svg');
          // Add replaced image's ID to the new SVG
          svg = typeof imgID !== 'undefined' ? svg.attr('id', imgID) : svg;
		  //
		  svg.attr({
			  width: imgWidth,
			  height: imgWidth,
		  });

          // Add replaced image's classes to the new SVG
          svg = typeof imgClass !== 'undefined' ? svg.attr('class', imgClass + ' replaced-svg') : svg.attr('class', ' replaced-svg');
          svg.removeClass('invisible');
          // Add URL in data
          svg = svg.attr('data-img-url', imgURL);
          // Remove any invalid XML tags as per http://validator.w3.org
          svg = svg.removeAttr('xmlns:a');
          // Set color defined in backoffice
		  svg.find('path:not([fill])').css('fill', svgColor);
		  svg.css('color', svgColor);
		  //
          // Replace image with new SVG
          imgObject.replaceWith(svg);
        }
        imgObject.removeClass('invisible');
      }
    });
  });
});

jQuery(document).ready(function () {
	var fields = {
		SVG_COLOR: 		'svg_color',
		SVG_WIDTH: 	'SVG_WIDTH',
		BORDER_WIDTH: 	'BORDER_WIDTH',
		BORDER_RADIUS: 	'BORDER_RADIUS',
		BORDER_COLOR: 	'BORDER_COLOR',
		BUTTON_WIDTH: 	'BUTTON_WIDTH',
		BUTTON_BG: 		'BUTTON_BG',
		BUTTON_HEIGHT: 	'BUTTON_HEIGHT',
		OPACITY: 		'OPACITY',
		BUTTON_MARGIN_X:'BUTTON_MARGIN_X',
		BUTTON_MARGIN_Y:'BUTTON_MARGIN_Y',
	};

	var num_validator = [
		[fields.SVG_WIDTH, 0, 150, "svgWidth"],
		[fields.BORDER_WIDTH, 0, 100, "borderWidth"],
		[fields.BORDER_RADIUS, 0, 100, "borderRadius"],
		[fields.BUTTON_WIDTH, 1, 150, "width"],
		[fields.BUTTON_HEIGHT, 1, 150, "height"],
		[fields.BUTTON_HEIGHT, 1, 150, "lineHeight"],
		[fields.OPACITY, 0, 100, "opacity"],
		[fields.BUTTON_MARGIN_X, 0, 500],
		[fields.BUTTON_MARGIN_Y, 0, 500],
	];

	var configuration = [];
	configuration[fields.SVG_WIDTH] 		= parseInt(jQuery('#'+fields.SVG_WIDTH).val()) || 0;
	configuration[fields.BORDER_WIDTH] 		= parseInt(jQuery('#'+fields.BORDER_WIDTH).val()) || 0;
	configuration[fields.BORDER_RADIUS] 	= parseInt(jQuery('#'+fields.BORDER_RADIUS).val()) || 0;
	configuration[fields.BUTTON_WIDTH] 		= parseInt(jQuery('#'+fields.BUTTON_WIDTH).val()) || 0;
	configuration[fields.BUTTON_HEIGHT] 	= parseInt(jQuery('#'+fields.BUTTON_HEIGHT).val()) || 0;
	configuration[fields.OPACITY] 			= parseInt(jQuery('#'+fields.OPACITY).val()) || 0;
	configuration[fields.BUTTON_MARGIN_X] 	= parseInt(jQuery('#'+fields.BUTTON_MARGIN_X).val()) || 0;
	configuration[fields.BUTTON_MARGIN_Y] 	= parseInt(jQuery('#'+fields.BUTTON_MARGIN_Y).val()) || 0;

	var scrolltopbtn = jQuery("#scrolltopbtn");
	scrolltopbtn.css('display', 'inline-flex');

	for (var i = 0; i < num_validator.length; i++) {
		(function(i) {
			jQuery('#'+num_validator[i][0]).on("keyup", function (event) {
				var value = parseInt(jQuery(this).val()) || 0;
				if (jQuery(this).val() == '') return true;
				return value > num_validator[i][2] || value < num_validator[i][1] || validate(event) ?
					jQuery(this).val(configuration[num_validator[i][0]]) && false :
					configuration_set(num_validator[i][0], value) && (num_validator[i][3] !== undefined ? scrolltopbtn.css(num_validator[i][3], num_validator[i][3] == 'lineHeight' ? value+'px' : value) : true);
			});
		})(i);
	}

	setInterval(function() {
		scrolltopbtn.css({
			borderColor: jQuery("input[name='"+fields.BORDER_COLOR+"']").val(),
			backgroundColor: jQuery("input[name='"+fields.BUTTON_BG+"']").val(),
		});

		jQuery('svg.an_scrolltop-svg').css('color', jQuery("input[name='"+fields.SVG_COLOR+"']").val());
		jQuery('svg.an_scrolltop-svg').find('path:not([fill])').css('fill', jQuery("input[name='"+fields.SVG_COLOR+"']").val());
		
	}, 1000);
	
	jQuery( "input[name='SVG_WIDTH']" ).change(function() {
	  jQuery('svg.an_scrolltop-svg').attr({
		  width: $(this).val(),
		  height: $(this).val(),
	  });
	});
	
	jQuery( "input[name='SVG_WIDTH']" ).on("keyup", function (event) {
	  jQuery('svg.an_scrolltop-svg').attr({
		  width: $(this).val(),
		  height: $(this).val(),
	  });
	});


	var configuration_set = function(field, value) {
		return Boolean(configuration[field] = value);
	};

	var validate = function(event) {
		return event.which != 8 && isNaN(String.fromCharCode(event.which));
	}
});