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
	var start_opacity = jQuery("#scrolltopbtn").css("opacity");

	if (jQuery(window).scrollTop() < 200) jQuery("#scrolltopbtn").css({opacity: 0});
	
	jQuery("#scrolltopbtn").css({display: 'inline-flex'});

	jQuery(window).scroll(function() {
	    if (jQuery(window).scrollTop() > 200) jQuery("#scrolltopbtn").css({opacity: start_opacity});
	    else jQuery("#scrolltopbtn").css({opacity: 0});
	});

	jQuery("#scrolltopbtn")
		.on("click", function () {
			jQuery("html, body").animate({ scrollTop: 0 }, "slow");
			return false;
		})
		.on("mouseenter", function() {
			jQuery("#scrolltopbtn").css({opacity: 1});
		})
		.on("mouseleave", function() {
		    if (jQuery(window).scrollTop() > 200) jQuery("#scrolltopbtn").css({opacity: start_opacity});
		    else jQuery("#scrolltopbtn").css({opacity: 0});
		});
});