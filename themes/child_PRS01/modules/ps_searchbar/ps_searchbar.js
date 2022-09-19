/* global $ */
$(document).ready(function () {
    var $searchWidgetMobile = $('#top_section_mobile');
    var $searchWidgetDesktop = $('#top_section_desktop');
    var $searchBoxMobile    = $searchWidgetMobile.find('input[type=text]');
    var $searchBoxDesktop    = $searchWidgetDesktop.find('input[type=text]');
    var searchURL     = $('#search_widget').attr('data-search-controller-url');

    $.widget('prestashop.psBlockSearchAutocomplete', $.ui.autocomplete, {
        _renderItem: function (ul, product) {
            if (product.discount_amount !== null) {
                return $("<li>")
                    .append($("<img>").attr("src",product.cover.bySize.home_default.url).addClass("product-img"))
                    .append($("<a>")
                        .append($("<span>").html(product.category_name).addClass("category"))
                        .append($("<span>").html(' > ').addClass("separator"))
                        .append($("<span>").html(product.name).addClass("product"))
                        .append($("<span>").html(product.price).addClass("price"))
                        .append($("<span>").html(product.regular_price).addClass("customPrice"))
                    ).appendTo(ul)
                    ;
            } else {
                return $("<li>")
                    .append($("<img>").attr("src",product.cover.bySize.home_default.url).addClass("product-img"))
                    .append($("<a>")
                        .append($("<span>").html(product.category_name).addClass("category"))
                        .append($("<span>").html(' > ').addClass("separator"))
                        .append($("<span>").html(product.name).addClass("product"))
                        .append($("<span>").html(product.price).addClass("price"))
                    ).appendTo(ul)
                    ;
            }



        }
    });

    let searchParams = {
        source: function (query, response) {
            $.get(searchURL, {
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
    };
    $searchBoxMobile.psBlockSearchAutocomplete(searchParams);
    $searchBoxDesktop.psBlockSearchAutocomplete(searchParams);
});
