<?php
/**
*  @author    TemplateTrip
*  @copyright 2015-2017 TemplateTrip. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

$useSSL = true;

require_once(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');
require_once(dirname(__FILE__).'/classes/TtWishList.php');
require_once(dirname(__FILE__).'/classes/WishListProductAttribute.php');
require_once(dirname(__FILE__).'/ttproductwishlist.php');
$context = Context::getContext();
if ($context->customer->isLogged()) {
    $action = Tools::getValue('action');
    $id_wishlist = (int)Tools::getValue('id_wishlist');
    $id_product = (int)Tools::getValue('id_product');
    $id_product_attribute = (int)Tools::getValue('id_product_attribute');
    $quantity = (int)Tools::getValue('quantity');
    $priority = Tools::getValue('priority');
    $wishlist = new TtWishList((int)($id_wishlist));
    $refresh = ((Tools::getValue('refresh') == 'true') ? 1 : 0);
    if (empty($id_wishlist) === false) {
        if (!strcmp($action, 'update')) {
            TtWishList::updateProduct($id_wishlist, $id_product, $id_product_attribute, $priority, $quantity);
        } else {
            if (!strcmp($action, 'delete')) {
                TtWishList::removeProduct($id_wishlist, (int)$context->customer->id, $id_product, $id_product_attribute);
            }

            $products = TtWishList::getProductByIdCustomer($id_wishlist, $context->customer->id, $context->language->id);

            $nb_products = count($products);
            $listProducts = array();

            for ($i = 0; $i < $nb_products; ++$i) {
                $curProduct = new Product((int)$products[$i]['id_product'], true, $context->language->id);
                $product_object = new WishListProductAttribute();
                $listProductsArray = array();
                $listProductsArray['wishlistInfo'] = $products[$i];
                $listProductsArray['curProduct'] = $product_object->getTemplateVarProducts($products[$i]['id_product']);
                $listProducts[] = $listProductsArray;
            }

            $context->smarty->assign(array(
                'products' => $listProducts,
                'id_wishlist' => $id_wishlist,
                'refresh' => $refresh,
                'token_wish' => $wishlist->token,
                'wishlists' => TtWishList::getByIdCustomer($context->cookie->id_customer)
            ));

            /* Instance of module class for translations */
            $module = new TtProductWishList();
            $context->smarty->assign('link', $context->link);
            if (Tools::file_exists_cache(_PS_THEME_DIR_.'modules/ttproductwishlist/views/templates/front/managewishlist.tpl')) {
                $context->smarty->display(_PS_THEME_DIR_.'modules/ttproductwishlist/views/templates/front/managewishlist.tpl');
            } elseif (Tools::file_exists_cache(dirname(__FILE__).'/views/templates/front/managewishlist.tpl')) {
                $context->smarty->display(dirname(__FILE__).'/views/templates/front/managewishlist.tpl');
            } else {
                echo $module->l('No template found', 'managewishlist');
            }
        }
    }
}
