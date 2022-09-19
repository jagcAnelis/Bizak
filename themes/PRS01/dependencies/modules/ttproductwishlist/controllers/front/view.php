<?php
/**
*  @author    TemplateTrip
*  @copyright 2015-2017 TemplateTrip. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

class TtProductWishListViewModuleFrontController extends ModuleFrontController
{
    public $php_self;

    public function __construct()
    {
        parent::__construct();
        $this->context = Context::getContext();
        include_once($this->module->getLocalPath().'classes/TtWishList.php');
        include_once($this->module->getLocalPath().'classes/WishListProductAttribute.php');
        include_once($this->module->getLocalPath().'ttproductwishlist.php');
    }

    public function initContent()
    {
        $this->php_self = 'view';
        parent::initContent();
        if (!Configuration::get('TTWISHLIST_ENABLE')) {
            return Tools::redirect('index.php?controller=404');
        }
        $token = Tools::getValue('token');

        $module = new TtProductWishList();

        if ($token) {
            $wishlist = TtWishList::getByToken($token);

            $products = TtWishList::getProductByIdCustomer((int)$wishlist['id_wishlist'], (int)$wishlist['id_customer'], $this->context->language->id, null, true);

            $nb_products = count($products);
            $priority_names = array(0=> $module->l('High'), 1=>$module->l('Medium'), 2=>$module->l('Low'));
            $listProducts = array();

            for ($i = 0; $i < $nb_products; ++$i) {
                $curProduct = new Product((int)$products[$i]['id_product'], true, $this->context->language->id);
                $product_object = new WishListProductAttribute();
                $listProductsArray = array();
                $listProductsArray['wishlistInfo'] = $products[$i];
                $listProductsArray['curProduct'] = $product_object->getTemplateVarProducts($products[$i]['id_product']);
                $listProducts[] = $listProductsArray;
            }
            
            TtWishList::incCounter((int)$wishlist['id_wishlist']);
            $ajax = Configuration::get('PS_BLOCK_CART_AJAX');

            $wishlists = TtWishList::getByIdCustomer((int)$wishlist['id_customer']);

            foreach ($wishlists as $key => $item) {
                if ($item['id_wishlist'] == $wishlist['id_wishlist']) {
                    unset($wishlists[$key]);
                    break;
                }
            }

            $this->context->smarty->assign(
                array(
                    'current_wishlist' => $wishlist,
                    'token' => $token,
                    'ajax' => ((isset($ajax) && (int)$ajax == 1) ? '1' : '0'),
                    'wishlists' => $wishlists,
                    'products' => $listProducts
                )
            );
        }
        $this->setTemplate('module:ttproductwishlist/views/templates/front/view.tpl');
    }

    public function getTemplateVarPage()
    {
        $page = parent::getTemplateVarPage();
        $page['meta']['title'] = $this->l('View Wishlist', 'view').' - '.Configuration::get('PS_SHOP_NAME');
        $page['meta']['keywords'] = $this->l('view-wishlist', 'view');
        $page['meta']['description'] = $this->l('view Wishlist', 'view');
        return $page;
    }
    
    public function getBreadcrumbLinks()
    {
        $breadcrumb = parent::getBreadcrumbLinks();
        $breadcrumb['links'][] = array(
            'title' => $this->l('Your Account', 'view'),
            'url' => $this->context->link->getPageLink('my-account', true),
        );
        
        $breadcrumb['links'][] = array(
            'title' => $this->l('Your Wishlist', 'mywishlist'),
            'url' => $this->context->link->getModuleLink('ttproductwishlist', 'mywishlist'),
        );

        return $breadcrumb;
    }
    
    public function getLayout()
    {
        $entity = 'module-ttproductwishlist-'.$this->php_self;
        
        $layout = $this->context->shop->theme->getLayoutRelativePathForPage($entity);
        
        if ($overridden_layout = Hook::exec(
            'overrideLayoutTemplate',
            array(
                'default_layout' => $layout,
                'entity' => $entity,
                'locale' => $this->context->language->locale,
                'controller' => $this,
            )
        )) {
            return $overridden_layout;
        }

        if ((int) Tools::getValue('content_only')) {
            $layout = 'layouts/layout-content-only.tpl';
        }

        return $layout;
    }
}
