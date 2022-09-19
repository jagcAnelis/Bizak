/* global $ */
$(document).ready(function () {
    var $searchWidget = $('#search_widget');
    var $searchBox    = $searchWidget.find('input[type=text]');
    var searchURL     = $searchWidget.attr('data-search-controller-url');

    
    $.widget('prestashop.psBlockSearchAutocomplete', $.ui.autocomplete, {
        _renderItem: function (ul, product) {
            if ((product).hasOwnProperty("cover") && product.cover != null)  {
                if (product.has_discount) {
                    return $("<li>")
                    .append($("<a>")
                        .append($("<img src="+product.cover.bySize.slider_photo.url+">").addClass("product-image"))
                        .append($("<div class='product-desc'>")
                            
                            .append($("<span>").html(product.name).addClass("product"))
                            .append($("<div class='prices-block'>")
                                .append($("<span>").html(product.price).addClass("product-price"))
                                .append($("<span>").html(product.regular_price).addClass("regular-price"))
                            )
                        )
                    ).appendTo(ul)
                    ;
                } else {
                    return $("<li>")
                    .append($("<a>")
                        .append($("<img src="+product.cover.bySize.slider_photo.url+">").addClass("product-image"))
                        .append($("<div class='product-desc'>")
                            
                            .append($("<span>").html(product.name).addClass("product"))
                            .append($("<div class='prices-block'>")
                                .append($("<span>").html(product.price).addClass("product-price"))
                            )
                        )
                    ).appendTo(ul)
                    ;
                }
            } else {
                if (product.has_discount) {
                    return $("<li class='noimg-search'>")
                    .append($("<a>")
                        .append($("<div class='product-desc'>")
                            
                            .append($("<span>").html(product.name).addClass("product"))
                            .append($("<div class='prices-block'>")
                                .append($("<span>").html(product.price).addClass("product-price"))
                                .append($("<span>").html(product.regular_price).addClass("regular-price"))
                            )
                        )
                    ).appendTo(ul)
                    ;
                } else {
                    return $("<li class='noimg-search'>")
                    .append($("<a>")
                        .append($("<div class='product-desc'>")
                            
                            .append($("<span>").html(product.name).addClass("product"))
                            .append($("<div class='prices-block'>")
                                .append($("<span>").html(product.price).addClass("product-price"))
                            )
                        )
                    ).appendTo(ul)
                    ;
                }
            }
            
        }
        
    });
    jQuery.ui.autocomplete.prototype._resizeMenu = function () {
        var ul = this.menu.element;
        ul.outerWidth(this.element.outerWidth());
    }
    $searchBox.psBlockSearchAutocomplete({
        source: function (query, response) {
            $.post(searchURL, {
                s: query.term,
                resultsPerPage: 10
            }, null, 'json')
            .then(function (resp) {
                response(resp.products);
            })
            .fail(response);
        },
        select: function (event, ui) {
            var url = ui.item.url;
            window.location.href = url;
        },
    });
});
