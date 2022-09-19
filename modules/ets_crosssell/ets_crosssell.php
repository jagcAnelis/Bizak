<?php
/**
 * 2007-2020 ETS-Soft
 *
 * NOTICE OF LICENSE
 *
 * This file is not open source! Each license that you purchased is only available for 1 wesite only.
 * If you want to use this file on more websites (or projects), you need to purchase additional licenses. 
 * You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
 * 
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please contact us for extra customization service at an affordable price
 *
 *  @author ETS-Soft <etssoft.jsc@gmail.com>
 *  @copyright  2007-2020 ETS-Soft
 *  @license    Valid for 1 website (or project) for each purchase of license
 *  International Registered Trademark & Property of ETS-Soft
 */

if(!defined('_PS_VERSION_'))
	exit;
if (!defined('_ETS_CROSSSELL_CACHE_DIR_')) 
    define('_ETS_CROSSSELL_CACHE_DIR_',_PS_CACHE_DIR_.'ets_crosssell_cache/');
class Ets_crosssell extends Module
{ 
    public $_config_types;
    public $_configs;
    public $_sidebars;
    public $is17 = false;
    public $_sort_options;
    public function __construct()
	{
        $this->name = 'ets_crosssell';
		$this->tab = 'front_office_features';
		$this->version = '2.0.6';
		$this->author = 'ETS-Soft';
		$this->need_instance = 0;
		$this->secure_key = Tools::encrypt($this->name);
		$this->bootstrap = true;
        if(version_compare(_PS_VERSION_, '1.7', '>='))
            $this->is17 = true; 
        $this->module_key = '0d2ff6d8b136b0e02a7c5c446415d6df';
		parent::__construct();
        $this->ps_versions_compliancy = array('min' => '1.6.0.0', 'max' => _PS_VERSION_);
        $this->displayName =$this->l('Cross Selling Pro - Upsell - Shopping cart and all pages');
        $this->description = $this->l('Automated product suggestions based on customer\'s interest to display on the shopping cart, product page, order page, etc. Cross-selling Pro (upsell) helps increase the visibility of all products and encourage customers to buy more!');
        $this->shortlink = 'https://mf.short-link.org/';
        if(Tools::getValue('configure')==$this->name && Tools::isSubmit('othermodules'))
        {
            $this->displayRecommendedModules();
        }
	}
    public function _defines()
    {
        $this->context->smarty->assign('link',$this->context->link);
        $this->_sort_options=array(
            array(
                'id_option' => 'cp.position asc',
                'name' => $this->l('Popularity')
            ),
            array(
                'id_option' => 'rand',
                'name' => $this->l('Random products')
            ),
            array(
                'id_option' => 'pl.name asc',
                'name' => $this->l('Product name: A-Z')
            ),
            array(
                'id_option' => 'pl.name desc',
                'name' => $this->l('Product name: Z-A')
            ),
            array(
                'id_option' => 'price asc',
                'name' => $this->l('Price: Lowest first')
            ),
            array(
                'id_option' => 'price desc',
                'name' => $this->l('Price: Highest first')
            ),
            array(
                'id_option' => 'p.id_product desc',
                'name' => $this->l('Newest items first')
            ),
        );
        $this->_config_types = array(
            'purchasedtogether' =>array(
                'title' => $this->l('Frequently purchased together'),
                'default'=>1,
                'desc' => $this->l('Display the products that were often purchased in the same cart as the product currently viewed'),
                'setting' => array(
                    array(
                        'name' => 'title',
                        'type' =>'text',
                        'label' => $this->l('Custom title'),
                        'validate'=>'isCleanHtml',
                        'lang'=>true,
                        'desc' => $this->l('Leave blank to use default title'),
                    ),
                    array(
                        'name'=>'display_sub_category',
                        'type'=>'switch',
                        'label' => $this->l('Display subcategories filter'),
                        'default'=>1,
                        'values' => array(
    						array(
    							'id' => 'active_on',
    							'value' => 1,
    							'label' => $this->l('Yes')
    						),
    						array(
    							'id' => 'active_off',
    							'value' => 0,
    							'label' => $this->l('No')
    						)
    					)
                    )
                )
            ),
            'recommendedproducts' =>array(
                'title' => $this->l('Recommended products'),
                'default' =>1,
                'desc' => $this->l('Suggestions to customers for products they may be interested in based on products they’ve already bought or viewed online'),
                'setting' => array(
                    array(
                        'name' => 'title',
                        'type' =>'text',
                        'label' => $this->l('Custom title'),
                        'validate'=>'isCleanHtml',
                        'lang'=>true,
                        'desc' => $this->l('Leave blank to use default title'),
                    ),
                    array(
                        'name'=>'display_sub_category',
                        'type'=>'switch',
                        'label' => $this->l('Display subcategories filter'),
                        'default'=>1,
                        'values' => array(
    						array(
    							'id' => 'active_on',
    							'value' => 1,
    							'label' => $this->l('Yes')
    						),
    						array(
    							'id' => 'active_off',
    							'value' => 0,
    							'label' => $this->l('No')
    						)
    					)
                        
                    )
                )
            ),
            'popularproducts' =>array(
                'title'=> $this->l('Popular products'),
                'default'=>1,
                'desc' => $this->l('Popular products of a product category'),
                'setting' => array(
                    array(
                        'name' => 'title',
                        'type' =>'text',
                        'label' => $this->l('Custom title'),
                        'validate'=>'isCleanHtml',
                        'lang'=>true,
                        'desc' => $this->l('Leave blank to use default title'),
                    ),
                    array(
                        'name'=>'id_category',
                        'type'=>'text',
                        'required' => true,
                        'validate' => 'isunsignedInt',  
                        'default' => Configuration::get('HOME_FEATURED_CAT'),
                        'label' => $this->l('Category whose products will be selected to display'),
                        'desc' => $this->l('Choose the category ID of the products that you would like to display on store front (default: 2 for "Home").'),
                    ),
                    array(
                        'name'=>'sort_by_default',
                        'type'=>'select',
                        'label'=> $this->l('Default product sort option'),
                        'options' => array(
                            'query' => $this->_sort_options,
                            'id' => 'id_option',
                            'name' => 'name'
                        ),
                        'default' => 'cp.position asc',
                    ),
                    array(
                        'name'=>'enable_sort_by',
                        'type'=>'switch',
                        'label' => $this->l('Display sort options'),
                        'values' => array(
    						array(
    							'id' => 'active_on',
    							'value' => 1,
    							'label' => $this->l('Yes')
    						),
    						array(
    							'id' => 'active_off',
    							'value' => 0,
    							'label' => $this->l('No')
    						)
    					)
                    ),
                    array(
                        'name'=>'display_sub_category',
                        'type'=>'switch',
                        'label' => $this->l('Display subcategories filter'),
                        'default'=>1,
                        'values' => array(
    						array(
    							'id' => 'active_on',
    							'value' => 1,
    							'label' => $this->l('Yes')
    						),
    						array(
    							'id' => 'active_off',
    							'value' => 0,
    							'label' => $this->l('No')
    						)
    					)
                    )
                )
            ),
            'mostviewedproducts' => array(
                'title' => $this->l('Most viewed products'),
                'default' => 1,
                'desc' => $this->l('Products which are viewed most by visitors/customers in your store'),
                'setting' => array(
                    array(
                        'name' => 'title',
                        'type' =>'text',
                        'label' => $this->l('Custom title'),
                        'validate'=>'isCleanHtml',
                        'lang'=>true,
                        'desc' => $this->l('Leave blank to use default title'),
                    ),
                    array(
                        'name'=>'display_sub_category',
                        'type'=>'switch',
                        'label' => $this->l('Display subcategories filter'),
                        'default'=>1,
                        'values' => array(
    						array(
    							'id' => 'active_on',
    							'value' => 1,
    							'label' => $this->l('Yes')
    						),
    						array(
    							'id' => 'active_off',
    							'value' => 0,
    							'label' => $this->l('No')
    						)
    					)
                    )
                )
            ),
            'trendingproducts' => array(
                'title' => $this->l('Trending products'),
                'default' => 1,
                'desc' => $this->l('Products which get most sales in a period of time are considered as trending'),
                'setting' => array(
                    array(
                        'name' => 'title',
                        'type' =>'text',
                        'label' => $this->l('Custom title'),
                        'validate'=>'isCleanHtml',
                        'lang'=>true,
                        'desc' => $this->l('Leave blank to use default title'),
                    ),
                    array(
                        'name' => 'day',
                        'type' =>'text',
                        'label' => $this->l('Most purchased in (days)'),
                        'required' => true,
                        'default' => 30,
                        'validate' => 'isunsignedInt',
                    ),
                    array(
                        'name'=>'display_sub_category',
                        'type'=>'switch',
                        'label' => $this->l('Display subcategories filter'),
                        'default'=>1,
                        'values' => array(
    						array(
    							'id' => 'active_on',
    							'value' => 1,
    							'label' => $this->l('Yes')
    						),
    						array(
    							'id' => 'active_off',
    							'value' => 0,
    							'label' => $this->l('No')
    						)
    					)
                    )
                )
            ),
            'topratedproducts' =>array(
                'title' => $this->l('Top rated products'),
                'default'=>1,
                'desc' => $this->l('Products with the highest rating by customers in your store'),
                'warning' => (Module::isInstalled('productcomments') && Module::isEnabled('productcomments')) || (Module::isInstalled('ets_productcomments') && Module::isEnabled('ets_productcomments')) ? false : $this->l('module is not installed on your site. This module is made by PrestaShop and it\'s free. Please install that module to display top rated products to customers'),
                'module_name' => $this->l('Product Comments'),
                'setting' => array(
                    array(
                        'name' => 'title',
                        'type' =>'text',
                        'label' => $this->l('Custom title'),
                        'validate'=>'isCleanHtml',
                        'lang'=>true,
                        'desc' => $this->l('Leave blank to use default title'),
                    ),
                    array(
                        'name'=>'display_sub_category',
                        'type'=>'switch',
                        'label' => $this->l('Display subcategories filter'),
                        'default'=>1,
                        'values' => array(
    						array(
    							'id' => 'active_on',
    							'value' => 1,
    							'label' => $this->l('Yes')
    						),
    						array(
    							'id' => 'active_off',
    							'value' => 0,
    							'label' => $this->l('No')
    						)
    					)
                        
                    )
                )
            ),
            'featuredproducts' =>array(
                'title' => $this->l('Featured products'),
                'default'=>1,
                'desc' => $this->l('Featured products of a category'),
                'setting' => array(
                    array(
                            'name' => 'title',
                            'type' =>'text',
                            'label' => $this->l('Custom title'),
                            'validate'=>'isCleanHtml',
                            'lang'=>true,
                            'desc' => $this->l('Leave blank to use default title'),
                    ),
                    array(
                        'name'=>'id_category',
                        'label' => $this->l('Category'),
                        'type' => 'categories',
                        'required' => true,
                        'validate' => 'isunsignedInt',
                        'default' => Configuration::get('HOME_FEATURED_CAT'),
                        'tree' => array(
                            'id'=>Configuration::get('PS_ROOT_CATEGORY'),
                        )
                    ),
                    array(
                        'name'=>'sort_by_default',
                        'type'=>'select',
                        'label'=> $this->l('Default product sort option'),
                        'options' => array(
                            'query' => $this->_sort_options,
                            'id' => 'id_option',
                            'name' => 'name'
                        ),
                        'default' => 'cp.position asc',
                    ),
                    array(
                        'name'=>'enable_sort_by',
                        'type'=>'switch',
                        'label' => $this->l('Display sort options'),
                        'values' => array(
    						array(
    							'id' => 'active_on',
    							'value' => 1,
    							'label' => $this->l('Yes')
    						),
    						array(
    							'id' => 'active_off',
    							'value' => 0,
    							'label' => $this->l('No')
    						)
    					)
                    ),
                    array(
                        'name'=>'display_sub_category',
                        'type'=>'switch',
                        'label' => $this->l('Display subcategories filter'),
                        'default'=>1,
                        'values' => array(
    						array(
    							'id' => 'active_on',
    							'value' => 1,
    							'label' => $this->l('Yes')
    						),
    						array(
    							'id' => 'active_off',
    							'value' => 0,
    							'label' => $this->l('No')
    						)
    					)
                    )
                )
            ),
            'youmightalsolike' =>array(
                'title' => $this->l('You might also like'),
                'default'=>1,
                'desc' => $this->l('Suggest products that are related to the product customers are viewing or the products which are put into their shopping cart'),
                'setting' => array(
                    array(
                        'name' => 'title',
                        'type' =>'text',
                        'label' => $this->l('Custom title'),
                        'validate'=>'isCleanHtml',
                        'lang'=>true,
                        'desc' => $this->l('Leave blank to use default title'),
                    ),
                    array(
                        'name'=>'display_sub_category',
                        'type'=>'switch',
                        'label' => $this->l('Display subcategories filter'),
                        'default'=>1,
                        'values' => array(
    						array(
    							'id' => 'active_on',
    							'value' => 1,
    							'label' => $this->l('Yes')
    						),
    						array(
    							'id' => 'active_off',
    							'value' => 0,
    							'label' => $this->l('No')
    						)
    					)
                        
                    )
                )
            ),
            'productinthesamecategories' =>array(
                'title' => $this->l('Products in the same category'),
                'default'=>1,
                'desc' => $this->l('Products which are in the same category with the ones customers currently viewing'),
                'setting' => array(
                    array(
                        'name' => 'title',
                        'type' =>'text',
                        'label' => $this->l('Custom title'),
                        'validate'=>'isCleanHtml',
                        'lang'=>true,
                        'desc' => $this->l('Leave blank to use default title'),
                    ),
                    array(
                        'name'=>'sort_by_default',
                        'type'=>'select',
                        'label'=> $this->l('Default product sort option'),
                        'options' => array(
                            'query' => $this->_sort_options,
                            'id' => 'id_option',
                            'name' => 'name'
                        ),
                        'default' => 'cp.position asc',
                    ),
                    array(
                        'name'=>'enable_sort_by',
                        'type'=>'switch',
                        'label' => $this->l('Display sort options'),
                        'values' => array(
    						array(
    							'id' => 'active_on',
    							'value' => 1,
    							'label' => $this->l('Yes')
    						),
    						array(
    							'id' => 'active_off',
    							'value' => 0,
    							'label' => $this->l('No')
    						)
    					)
                    ),
                    array(
                        'name'=>'display_sub_category',
                        'type'=>'switch',
                        'label' => $this->l('Display subcategories filter'),
                        'default'=>1,
                        'values' => array(
    						array(
    							'id' => 'active_on',
    							'value' => 1,
    							'label' => $this->l('Yes')
    						),
    						array(
    							'id' => 'active_off',
    							'value' => 0,
    							'label' => $this->l('No')
    						)
    					)
                    )
                )
            ),
            'viewedproducts' =>array(
                'title' => $this->l('Viewed products'),
                'default'=>1,
                'desc' => $this->l('Products which visitors/customers recently viewed'),
                'setting' => array(
                    array(
                        'name' => 'title',
                        'type' =>'text',
                        'label' => $this->l('Custom title'),
                        'validate'=>'isCleanHtml',
                        'lang'=>true,
                        'desc' => $this->l('Leave blank to use default title'),
                    ),
                    array(
                        'name'=>'sort_by_default',
                        'type'=>'select',
                        'label'=> $this->l('Default product sort option'),
                        'options' => array(
                            'query' => $this->_sort_options,
                            'id' => 'id_option',
                            'name' => 'name'
                        ),
                        'default' => 'cp.position asc',
                    ),
                    array(
                        'name'=>'enable_sort_by',
                        'type'=>'switch',
                        'label' => $this->l('Display sort options'),
                        'values' => array(
    						array(
    							'id' => 'active_on',
    							'value' => 1,
    							'label' => $this->l('Yes')
    						),
    						array(
    							'id' => 'active_off',
    							'value' => 0,
    							'label' => $this->l('No')
    						)
    					)
                    ),
                    array(
                        'name'=>'display_sub_category',
                        'type'=>'switch',
                        'label' => $this->l('Display subcategories filter'),
                        'default'=>1,
                        'values' => array(
    						array(
    							'id' => 'active_on',
    							'value' => 1,
    							'label' => $this->l('Yes')
    						),
    						array(
    							'id' => 'active_off',
    							'value' => 0,
    							'label' => $this->l('No')
    						)
    					)
                    )
                )
            ),
            'bestselling' =>array(
                'title'=>$this->l('Best selling'),
                'default'=>1,
                'desc' => $this->l('The top products based on sales'),
                'setting' => array(
                    array(
                        'name' => 'title',
                        'type' =>'text',
                        'label' => $this->l('Custom title'),
                        'validate'=>'isCleanHtml',
                        'lang'=>true,
                        'desc' => $this->l('Leave blank to use default title'),
                    ),
                    array(
                        'name'=>'display_sub_category',
                        'type'=>'switch',
                        'label' => $this->l('Display subcategories filter'),
                        'default'=>1,
                        'values' => array(
    						array(
    							'id' => 'active_on',
    							'value' => 1,
    							'label' => $this->l('Yes')
    						),
    						array(
    							'id' => 'active_off',
    							'value' => 0,
    							'label' => $this->l('No')
    						)
    					)
                    )
                )
            ),
            'newproducts' =>array(
                'title' => $this->l('New products'),
                'default'=>1,
                'desc' => $this->l('The newest products within your online store'),
                'setting' => array(
                    array(
                        'name' => 'title',
                        'type' =>'text',
                        'lang'=>true,
                        'validate'=>'isCleanHtml',
                        'label' => $this->l('Custom title'),
                        'desc' => $this->l('Leave blank to use default title'),
                    ),
                    array(
                        'name'=>'sort_by_default',
                        'type'=>'select',
                        'label'=> $this->l('Default product sort option'),
                        'options' => array(
                            'query' => $this->_sort_options,
                            'id' => 'id_option',
                            'name' => 'name'
                        ),
                        'default' => 'cp.position asc',
                    ),
                    array(
                        'name'=>'enable_sort_by',
                        'type'=>'switch',
                        'label' => $this->l('Display sort options'),
                        'values' => array(
    						array(
    							'id' => 'active_on',
    							'value' => 1,
    							'label' => $this->l('Yes')
    						),
    						array(
    							'id' => 'active_off',
    							'value' => 0,
    							'label' => $this->l('No')
    						)
    					)
                    ),
                    array(
                        'name'=>'display_sub_category',
                        'type'=>'switch',
                        'label' => $this->l('Display subcategories filter'),
                        'default'=>1,
                        'values' => array(
    						array(
    							'id' => 'active_on',
    							'value' => 1,
    							'label' => $this->l('Yes')
    						),
    						array(
    							'id' => 'active_off',
    							'value' => 0,
    							'label' => $this->l('No')
    						)
    					)
                    )
                )
            ),
            'specialproducts' =>array(
                'title' => $this->l('Special products'),
                'default'=>1,
                'desc' => $this->l('Products which are discounted on the current time'),
                'setting' => array(
                    array(
                        'name' => 'title',
                        'type' =>'text',
                        'lang'=>true,
                        'validate'=>'isCleanHtml',
                        'label' => $this->l('Custom title'),
                        'desc' => $this->l('Leave blank to use default title'),
                    ),
                    array(
                        'name'=>'sort_by_default',
                        'type'=>'select',
                        'label'=> $this->l('Default product sort option'),
                        'options' => array(
                            'query' => $this->_sort_options,
                            'id' => 'id_option',
                            'name' => 'name'
                        ),
                        'default' => 'cp.position asc',
                    ),
                    array(
                        'name'=>'enable_sort_by',
                        'type'=>'switch',
                        'label' => $this->l('Display sort options'),
                        'values' => array(
    						array(
    							'id' => 'active_on',
    							'value' => 1,
    							'label' => $this->l('Yes')
    						),
    						array(
    							'id' => 'active_off',
    							'value' => 0,
    							'label' => $this->l('No')
    						)
    					)
                    ),
                    array(
                        'name'=>'display_sub_category',
                        'type'=>'switch',
                        'label' => $this->l('Display subcategories filter'),
                        'default'=>1,
                        'values' => array(
    						array(
    							'id' => 'active_on',
    							'value' => 1,
    							'label' => $this->l('Yes')
    						),
    						array(
    							'id' => 'active_off',
    							'value' => 0,
    							'label' => $this->l('No')
    						)
    					)
                    )
                )
            ),
            'productinthesamemanufacture' =>array(
                'title'=> $this->l('Product in the same brand'),
                'default' =>1,
                'desc' => $this->l('Products which come from the same manufacturer'),
                'info' => Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'manufacturer m,'._DB_PREFIX_.'manufacturer_shop ms WHERE m.id_manufacturer= ms.id_manufacturer AND ms.id_shop="'.(int)$this->context->shop->id.'" AND m.active=1') ? false : $this->display(__FILE__,'brand.tpl'),
                'setting' => array(
                    array(
                        'name' => 'title',
                        'type' =>'text',
                        'label' => $this->l('Custom title'),
                        'validate'=>'isCleanHtml',
                        'lang'=>true,
                        'desc' => $this->l('Leave blank to use default title'),
                    ),
                    array(
                        'name'=>'sort_by_default',
                        'type'=>'select',
                        'label'=> $this->l('Default product sort option'),
                        'options' => array(
                            'query' => $this->_sort_options,
                            'id' => 'id_option',
                            'name' => 'name'
                        ),
                        'default' => 'cp.position asc',
                    ),
                    array(
                        'name'=>'enable_sort_by',
                        'type'=>'switch',
                        'label' => $this->l('Display sort options'),
                        'values' => array(
    						array(
    							'id' => 'active_on',
    							'value' => 1,
    							'label' => $this->l('Yes')
    						),
    						array(
    							'id' => 'active_off',
    							'value' => 0,
    							'label' => $this->l('No')
    						)
    					)
                    ),
                    array(
                        'name'=>'display_sub_category',
                        'type'=>'switch',
                        'label' => $this->l('Display subcategories filter'),
                        'default'=>1,
                        'values' => array(
    						array(
    							'id' => 'active_on',
    							'value' => 1,
    							'label' => $this->l('Yes')
    						),
    						array(
    							'id' => 'active_off',
    							'value' => 0,
    							'label' => $this->l('No')
    						)
    					)
                    )
                )
            ),
        );
        $id_root_category = Db::getInstance()->getValue('SELECT id_category FROM '._DB_PREFIX_.'category WHERE is_root_category=1');
        $sub_categories_default=array();
        $categories = Db::getInstance()->executeS('SELECT id_category FROM '._DB_PREFIX_.'category WHERE id_parent='.(int)$id_root_category);
        if($categories)
        {
            foreach($categories as $category)
                $sub_categories_default[]= $category['id_category'].',';
        }
        $this->_configs = array(
            'home_page' => array(
                'recommendedproducts' =>$this->l('Recommended products'), 
                'trendingproducts' => $this->l('Trending products'),
                'mostviewedproducts' => $this->l('Most viewed products'),
                'topratedproducts' =>$this->l('Top rated products'),
                'youmightalsolike' =>$this->l('You might also like'),
                'viewedproducts' =>$this->l('Viewed products'),
                'featuredproducts' =>$this->l('Featured products'),
                'popularproducts' =>$this->l('Popular products'),
                'bestselling' =>$this->l('Best selling'),
                'newproducts' =>$this->l('New products'),
                'specialproducts' =>$this->l('Special products'),
            ),
            'search_page' => array(
                'recommendedproducts' =>$this->l('Recommended products'),
                'trendingproducts' => $this->l('Trending products'),
                'mostviewedproducts' => $this->l('Most viewed products'),
                'topratedproducts' =>$this->l('Top rated products'),
                'youmightalsolike' =>$this->l('You might also like'),
                'viewedproducts' =>$this->l('Viewed products'),
                'featuredproducts' =>$this->l('Featured products'),
                'popularproducts' =>$this->l('Popular products'),
                'bestselling' =>$this->l('Best selling'),
                'newproducts' =>$this->l('New products'),
                'specialproducts' =>$this->l('Special products'),
            ),
            'category_page' => array(
                'recommendedproducts' =>$this->l('Recommended products'),
                'trendingproducts' => $this->l('Trending products'),
                'mostviewedproducts' => $this->l('Most viewed products'),
                'topratedproducts' =>$this->l('Top rated products'),
                'youmightalsolike' =>$this->l('You might also like'),
                'viewedproducts' =>$this->l('Viewed products'),
                'featuredproducts' =>$this->l('Featured products'),
                'popularproducts' =>$this->l('Popular products'),
                'bestselling' =>$this->l('Best selling'),
                'newproducts' =>$this->l('New products'),
                'specialproducts' =>$this->l('Special products'),
            ),
            'product_page' => array(
                'purchasedtogether' =>$this->l('Frequently purchased together'),
                'recommendedproducts' =>$this->l('Recommended products'),
                'trendingproducts' => $this->l('Trending products'),
                'productinthesamecategories' =>$this->l('Products from the same category'),
                'productinthesamemanufacture' =>$this->l('Products from the same brand'),
                'youmightalsolike' =>$this->l('You might also like'),
                'mostviewedproducts' => $this->l('Most viewed products'),
                'viewedproducts' =>$this->l('Viewed products'),
                'topratedproducts' =>$this->l('Top rated products'),
                'featuredproducts' =>$this->l('Featured products'),
                'popularproducts' =>$this->l('Popular products'),
                'bestselling' =>$this->l('Best selling'),
                'newproducts' =>$this->l('New products'),
                'specialproducts' =>$this->l('Special products'),
            ),
            'quick_view_page' => array(
                'purchasedtogether' =>$this->l('Frequently purchased together'),
                'recommendedproducts' =>$this->l('Recommended products'),
                'trendingproducts' => $this->l('Trending products'),
                'productinthesamecategories' =>$this->l('Products from the same category'),
                'productinthesamemanufacture' =>$this->l('Products from the same brand'),
                'youmightalsolike' =>$this->l('You might also like'),
                'mostviewedproducts' => $this->l('Most viewed products'),
                'viewedproducts' =>$this->l('Viewed products'),
                'topratedproducts' =>$this->l('Top rated products'),
                'featuredproducts' =>$this->l('Featured products'),
                'popularproducts' =>$this->l('Popular products'),
                'bestselling' =>$this->l('Best selling'),
                'newproducts' =>$this->l('New products'),
                'specialproducts' =>$this->l('Special products'),
            ),
            'added_popup_page' => array(
                'purchasedtogether' =>$this->l('Frequently purchased together'),
                'recommendedproducts' =>$this->l('Recommended products'),
                'trendingproducts' => $this->l('Trending products'),
                'productinthesamecategories' =>$this->l('Products from the same category'),
                'productinthesamemanufacture' =>$this->l('Products from the same brand'),
                'youmightalsolike' =>$this->l('You might also like'),
                'mostviewedproducts' => $this->l('Most viewed products'),
                'viewedproducts' =>$this->l('Viewed products'),
                'topratedproducts' =>$this->l('Top rated products'),
                'featuredproducts' =>$this->l('Featured products'),
                'popularproducts' =>$this->l('Popular products'),
                'bestselling' =>$this->l('Best selling'),
                'newproducts' =>$this->l('New products'),
                'specialproducts' =>$this->l('Special products'),
            ),
            'cart_page' => array(
                'recommendedproducts' =>$this->l('Recommended products'),
                'trendingproducts' => $this->l('Trending products'),
                'mostviewedproducts' => $this->l('Most viewed products'),
                'topratedproducts' =>$this->l('Top rated products'),
                'youmightalsolike' =>$this->l('You might also like'),
                'viewedproducts' =>$this->l('Viewed products'),
                'featuredproducts' =>$this->l('Featured products'),
                'popularproducts' =>$this->l('Popular products'),
                'bestselling' =>$this->l('Best selling'),
                'newproducts' =>$this->l('New products'),
                'specialproducts' =>$this->l('Special products'),
            ),
            'order_conf' => array(
                'recommendedproducts' =>$this->l('Recommended products'),
                'trendingproducts' => $this->l('Trending products'),
                'mostviewedproducts' => $this->l('Most viewed products'),
                'topratedproducts' =>$this->l('Top rated products'),
                'youmightalsolike' =>$this->l('You might also like'),
                'viewedproducts' =>$this->l('Viewed products'),
                'featuredproducts' =>$this->l('Featured products'),
                'popularproducts' =>$this->l('Popular products'),
                'bestselling' =>$this->l('Best selling'),
                'newproducts' =>$this->l('New products'),
                'specialproducts' =>$this->l('Special products'),
            ),
            'cms_page' => array(
                'recommendedproducts' =>$this->l('Recommended products'),
                'trendingproducts' => $this->l('Trending products'),
                'mostviewedproducts' => $this->l('Most viewed products'),
                'topratedproducts' =>$this->l('Top rated products'),
                'youmightalsolike' =>$this->l('You might also like'),
                'viewedproducts' =>$this->l('Viewed products'),
                'featuredproducts' =>$this->l('Featured products'),
                'popularproducts' =>$this->l('Popular products'),
                'bestselling' =>$this->l('Best selling'),
                'newproducts' =>$this->l('New products'),
                'specialproducts' =>$this->l('Special products'),
            ),
            'contact_page' => array(
                'recommendedproducts' =>$this->l('Recommended products'),
                'trendingproducts' => $this->l('Trending products'),
                'mostviewedproducts' => $this->l('Most viewed products'),
                'topratedproducts' =>$this->l('Top rated products'),
                'youmightalsolike' =>$this->l('You might also like'),
                'viewedproducts' =>$this->l('Viewed products'),
                'featuredproducts' =>$this->l('Featured products'),
                'popularproducts' =>$this->l('Popular products'),
                'bestselling' =>$this->l('Best selling'),
                'newproducts' =>$this->l('New products'),
                'specialproducts' =>$this->l('Special products'),
            ),
            'settings'=>array(
                array(
                    'name'=>'ETS_CS_CATEGORY_SUB',
                    'label' => $this->l('Sub categories to filter'),
                    'type' => 'categories',
                    'default' => $sub_categories_default,
                    'use_checkbox'=>true,
                    'tree' => array(
                        'id'=>Configuration::get('PS_ROOT_CATEGORY'),
                        'use_checkbox'=>true,
                        'selected_categories'=> explode(',',Configuration::get('ETS_CS_CATEGORY_SUB')),
                    )
                ),
                array(
                    'type' =>'switch',
                    'label' => $this->l('Enable cache'),
                    'name' => 'ETS_CS_ENABLE_CACHE',
                    'default'=>1,
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			)
                ),
                array(
                    'type' =>'text',
                    'label' => $this->l('Cache lifetime'),
                    'name' => 'ETS_CS_CACHE_LIFETIME',
                    'default'=>24,
                    'suffix' => $this->l('hour(s)'),
                    'col' => '2',
                    'validate' => 'isUnsignedFloat',
                ),
                array(
                    'type' =>'switch',
                    'label' => $this->l('Display "Out of stock" products'),
                    'name' => 'ETS_CS_OUT_OF_STOCK',
                    'default'=>1,
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			)
                )
            ),
        ); 
        $this->_sidebars = array(
            'home_page' => $this->l('Homepage'),
            'search_page' => $this->l('Search Page'),
            'category_page' => $this->l('Product category page'),
            'product_page' => $this->l('Product details page'),
            'quick_view_page' => $this->l('Product quick view popup'),
            'added_popup_page' => $this->l('Added product popup'),
            'cart_page' => $this->l('Shopping cart page'),
            'order_conf' => $this->l('Order confirmation page'),
            'cms_page' => $this->l('CMS page'),
            'contact_page' => $this->l('Contact page'),
            'settings' => $this->l('General settings'),
                        
        );
    }
    
    /**
	 * @see Module::install()
	 */
    public function install()
	{
	    return parent::install()&& $this->registerHook('displayLeftColumn')
        && $this->registerHook('displayBackOfficeHeader') 
        && $this->registerHook('displayHome') 
        && $this->registerHook('displaySearch')
        && $this->registerHook('displayHeader')
        && $this->registerHook('displayRightColumn')
        && $this->registerHook('displayContentWrapperBottom')
        && $this->registerHook('displayProductAdditionalInfo')
        && $this->registerHook('displayRightColumnProduct')
        && $this->registerHook('displayProductPopupAdded')
        && $this->registerHook('displayShoppingCartFooter')
        && $this->registerHook('actionProductAdd')
        && $this->registerHook('actionProductUpdate')
        && $this->registerHook('actionProductDelete')
        && $this->registerHook('actionOrderStatusPostUpdate')
        && $this->registerHook('actionValidateOrder')
        && $this->registerHook('displayOrderConfirmation')
        && $this->registerHook('displayOrderConfirmation2')
        && $this->registerHook('actionPageCacheAjax')
        && $this->registerHook('displayFooterProduct') && $this->_installDb() && $this->installDbDefault() && $this->_registerHooks();
    } 
    public function _installDb()
    {
        return Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_crosssell_product_viewed` ( 
            `id_ets_crosssell_product_viewed` INT(11) NOT NULL AUTO_INCREMENT ,
            `id_product` INT(11) NOT NULL , 
            `viewed` INT(11) NOT NULL , 
            PRIMARY KEY (`id_ets_crosssell_product_viewed`)) ENGINE = InnoDB;');
    }
    public function installDbDefault()
    {
        if(!$this->_sidebars)
            $this->_defines();
        foreach($this->_sidebars as $control=> $sidebar)
        {
            $this->_saveConfig($control,true);
            unset($sidebar);
        }
        
        Configuration::updateValue('ETS_CS_HOME_PAGE_LAYOUT','tab');
        Configuration::updateValue('ETS_CS_HOME_PAGE_MODE','grid');
        Configuration::updateValue('ETS_CS_HOME_PAGE_RECOMMENDEDPRODUCTS',1);
        Configuration::updateValue('ETS_CS_HOME_PAGE_TRENDINGPRODUCTS',1);
        Configuration::updateValue('ETS_CS_HOME_PAGE_MOSTVIEWEDPRODUCTS',1);
        Configuration::updateValue('ETS_CS_HOME_PAGE_TOPRATEDPRODUCTS',1);

        Configuration::updateValue('ETS_CS_SEARCH_PAGE_LAYOUT','list');
        Configuration::updateValue('ETS_CS_SEARCH_PAGE_MODE','grid');
        Configuration::updateValue('ETS_CS_SEARCH_PAGE_RECOMMENDEDPRODUCTS',1);
        Configuration::updateValue('ETS_CS_SEARCH_PAGE_TRENDINGPRODUCTS',0);
        Configuration::updateValue('ETS_CS_SEARCH_PAGE_MOSTVIEWEDPRODUCTS',0);
        Configuration::updateValue('ETS_CS_SEARCH_PAGE_TOPRATEDPRODUCTS',0);

        Configuration::updateValue('ETS_CS_CATEGORY_PAGE_LAYOUT','tab');
        Configuration::updateValue('ETS_CS_CATEGORY_PAGE_MODE','slide');
        Configuration::updateValue('ETS_CS_PRODUCT_PAGE_LAYOUT','list');
        Configuration::updateValue('ETS_CS_PRODUCT_PAGE_MODE','slide');
        Configuration::updateValue('ETS_CS_PRODUCT_PAGE_PURCHASEDTOGETHER',1);
        Configuration::updateValue('ETS_CS_PRODUCT_PAGE_PRODUCTINTHESAMECATEGORIES',1);
        Configuration::updateValue('ETS_CS_QUICK_VIEW_PAGE_LAYOUT','tab');
        Configuration::updateValue('ETS_CS_QUICK_VIEW_PAGE_MODE','slide');
        Configuration::updateValue('ETS_CS_QUICK_VIEW_PAGE_YOUMIGHTALSOLIKE',1);        
        Configuration::updateValue('ETS_CS_ADDED_POPUP_PAGE_LAYOUT','tab');
        Configuration::updateValue('ETS_CS_ADDED_POPUP_PAGE_MODE','slide');
        Configuration::updateValue('ETS_CS_ADDED_POPUP_PAGE_YOUMIGHTALSOLIKE',1);
        Configuration::updateValue('ETS_CS_CART_PAGE_LAYOUT','list');
        Configuration::updateValue('ETS_CS_CART_PAGE_MODE','slide');
        Configuration::updateValue('ETS_CS_CART_PAGE_YOUMIGHTALSOLIKE',1);
        Configuration::updateValue('ETS_CS_CART_PAGE_RECOMMENDEDPRODUCTS',1);
        Configuration::updateValue('ETS_CS_ORDER_CONF_LAYOUT','list');
        Configuration::updateValue('ETS_CS_ORDER_CONF_MODE','slide');
        Configuration::updateValue('ETS_CS_ORDER_CONF_YOUMIGHTALSOLIKE',1);
        Configuration::updateValue('ETS_CS_ORDER_CONF_RECOMMENDEDPRODUCTS',1);
        Configuration::updateValue('ETS_CS_CMS_PAGE_LAYOUT','list');
        Configuration::updateValue('ETS_CS_CMS_PAGE_MODE','grid');
        Configuration::updateValue('ETS_CS_CMS_PAGE_RECOMMENDEDPRODUCTS',1);
        Configuration::updateValue('ETS_CS_CONTACT_PAGE_LAYOUT','tab');
        Configuration::updateValue('ETS_CS_CONTACT_PAGE_MODE','grid');
        Configuration::updateValue('ETS_CS_CONTACT_PAGE_RECOMMENDEDPRODUCTS',1);
        Configuration::updateValue('ETS_CS_CONTACT_PAGE_TRENDINGPRODUCTS',1);        
        
        if($pages= array_keys($this->_sidebars))
        {
            foreach($pages as $page)
            {
                if($page!='settings')
                {
                    Configuration::deleteByName('ETS_CS_'.Tools::strtoupper($page).'_ROW_DESKTOP');
                    Configuration::deleteByName('ETS_CS_'.Tools::strtoupper($page).'_ROW_TABLET');
                    Configuration::deleteByName('ETS_CS_'.Tools::strtoupper($page).'_ROW_MOBILE');                
                   if($page=='category_page'|| $page=='contact_page') 
                    {
                        Configuration::updateValue('ETS_CS_'.Tools::strtoupper($page).'_ROW_DESKTOP',3);
                        Configuration::updateValue('ETS_CS_'.Tools::strtoupper($page).'_ROW_TABLET',2);
                        Configuration::updateValue('ETS_CS_'.Tools::strtoupper($page).'_ROW_MOBILE',1);
                    }elseif($page=='quick_view_page' || $page=='added_popup_page' ){
                        Configuration::updateValue('ETS_CS_'.Tools::strtoupper($page).'_ROW_DESKTOP',6);
                        Configuration::updateValue('ETS_CS_'.Tools::strtoupper($page).'_ROW_TABLET',4);
                        Configuration::updateValue('ETS_CS_'.Tools::strtoupper($page).'_ROW_MOBILE',2);
                    }
                    else
                    {
                        Configuration::updateValue('ETS_CS_'.Tools::strtoupper($page).'_ROW_DESKTOP',4);
                        Configuration::updateValue('ETS_CS_'.Tools::strtoupper($page).'_ROW_TABLET',3);
                        Configuration::updateValue('ETS_CS_'.Tools::strtoupper($page).'_ROW_MOBILE',1);
                    }
                    
                }
            }
        }
        return true;
    }   
    public function _registerHooks()
    {
        if(!$this->_config_types)
            $this->_defines();
        foreach($this->_config_types as $key=>$config_type)
        {
            $this->registerHook('display'.$key);
            unset($config_type);
        }
        return true;
    } 
    /**
	 * @see Module::uninstall()
	 */
	public function uninstall()
	{
        $this->clearCache();
        return parent::uninstall() && $this->uninstallDbDefault();
    }
    public function uninstallDbDefault()
    {
        if(!$this->_sidebars)
            $this->_defines();
        foreach($this->_sidebars as $control=> $sidebar)
        {
            $configs = $this->_configs[$control];
            if($configs)
            {
                foreach($configs as $key => $config)
                {
                    Configuration::deleteByName('ETS_CS_'.Tools::strtoupper($control).'_'.Tools::strtoupper($key));
                    if(isset($this->_config_types[$key]['setting']))
                    {
                        foreach($this->_config_types[$key]['setting'] as $setting)
                        {
                            Configuration::deleteByName('ETS_CS_'.Tools::strtoupper($control).'_'.Tools::strtoupper($key).'_'.Tools::strtoupper($setting['name']));
                        }
                    }
                    unset($config);
                }
             }
             Configuration::deleteByName('ETS_CS_'.Tools::strtoupper($control).'_LAYOUT');
             Configuration::deleteByName('ETS_CS_'.Tools::strtoupper($control).'_MODE');
             Configuration::deleteByName('ETS_CS_'.Tools::strtoupper($control).'_COUNT_PRODUCT');
             Configuration::deleteByName('ETS_CS_'.Tools::strtoupper($control).'_POSITIONS');
             Configuration::deleteByName('ETS_CS_'.Tools::strtoupper($control).'_ROW_DESKTOP');
             Configuration::deleteByName('ETS_CS_'.Tools::strtoupper($control).'_ROW_TABLET');
             Configuration::deleteByName('ETS_CS_'.Tools::strtoupper($control).'_ROW_MOBILE');
             unset($sidebar);
        }
        Configuration::deleteByName('ETS_CS_CATEGORY_SUB');
        Configuration::deleteByName('ETS_CS_ENABLE_CACHE');
        Configuration::deleteByName('ETS_CS_CACHE_LIFETIME');
        return true;
    }
    public function hookDisplayBackOfficeHeader()
    {
        if((Tools::getValue('controller')=='AdminModules' && Tools::getValue('configure')==$this->name))
        {
            $this->context->controller->addCSS($this->_path.'views/css/admin.css');
            $this->context->controller->addCSS($this->_path.'views/css/other.css');
        }
    }
    public function hookActionPageCacheAjax()
    {
        if(!Module::isInstalled('ets_homecategories') || !Module::isEnabled('ets_homecategories'))
        {
            $this->context->cookie->ets_homecat_order_seed = rand(1, 10000);
            $this->context->cookie->write();
        }
        $id_product = (int)Tools::getValue('id_product');
		$productsViewed = (isset($this->context->cookie->viewed) && !empty($this->context->cookie->viewed)) ? array_slice(array_reverse(explode(',', $this->context->cookie->viewed)), 0,Configuration::get('PRODUCTS_VIEWED_NBR')) : array();
        $productMostViewed = (isset($this->context->cookie->mostViewed) && !empty($this->context->cookie->mostViewed)) ? array_slice(array_reverse(explode(',', $this->context->cookie->mostViewed)), 0) : array();
        if(Tools::getValue('controller')=='product' && $id_product && (!in_array($id_product, $productsViewed) || !in_array($id_product,$productMostViewed)))
		{
			$product = new Product((int)$id_product);
			if ($product->checkAccess((int)$this->context->customer->id))
			{
			    if(!in_array($id_product, $productsViewed))
                {
                    if (isset($this->context->cookie->viewed) && !empty($this->context->cookie->viewed))
    					$this->context->cookie->viewed .= ','.(int)$id_product;
    				else
    					$this->context->cookie->viewed = (int)$id_product;
                    $this->context->cookie->write();
                } 
				if(!in_array($id_product,$productMostViewed))
                {
                    if(!Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'ets_crosssell_product_viewed WHERE id_product='.(int)$id_product))
                        Db::getInstance()->execute('INSERT INTO '._DB_PREFIX_.'ets_crosssell_product_viewed(id_product,viewed) VALUES('.(int)$id_product.',1)');
                    else
                        Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'ets_crosssell_product_viewed SET viewed=viewed+1 WHERE id_product='.(int)$id_product);
                    if (isset($this->context->cookie->mostViewed) && !empty($this->context->cookie->mostViewed))
        				$this->context->cookie->mostViewed .= ','.(int)$id_product;
        			else
        				$this->context->cookie->mostViewed = (int)$id_product;
                    $this->context->cookie->write(); 
                }
                
			}
		}
    }
    public function hookActionProductAdd()
    {
        $this->clearCache();
    }
    public function hookActionProductUpdate()
    {
        $this->clearCache();
    }
    public function hookActionProductDelete()
    {
        $this->clearCache();
    }
    public function hookActionOrderStatusPostUpdate($params)
    {
        $this->clearCache();
    }
    public function getContent()
	{
        if(!$this->_sidebars)
            $this->_defines();
        $control = Tools::getValue('control','home_page');
        if(!in_array($control,array('home_page','search_page','category_page','product_page','quick_view_page','added_popup_page','cart_page','order_conf','cms_page','contact_page','settings')))
            $control= 'home_page';
        $this->context->controller->addJqueryUI('ui.sortable');
        if(Tools::getValue('action')=='clearCache')
        {
            $this->clearCache();
            die(
                Tools::jsonEncode(
                    array(
                        'success' => $this->l('Clear cache successfully'),
                    )
                )
            );
        }
        if(Tools::getValue('action')=='updateBlock')
        {
            $field = Tools::getValue('field');
            $value_filed = Tools::getValue('value_filed');
            Configuration::updateValue($field,$value_filed);
            die(
                Tools::jsonEncode(
                    array(
                        'success' => $this->l('Updated successfully'),
                    )
                )
            );
        }
        if(Tools::getValue('action')=='updateFieldOrdering')
        {
            $field_positions= Tools::getValue('field_positions');
            Configuration::updateValue('ETS_CS_'.Tools::strtoupper($control).'_POSITIONS',implode(',',$field_positions));
            die(
                Tools::jsonEncode(
                    array(
                        'success' => $this->l('Updated successfully'),
                    )
                )
            );
        }
        if(Tools::isSubmit('saveConfig'))
        {
            if($this->_checkValidatePost($control))
            {
                $this->_saveConfig($control);
                if(Tools::isSubmit('ajax'))
                {
                    die(
                        Tools::jsonEncode(
                            array(
                                'success' => $this->l('Updated successfully'),
                            )
                        )
                    );
                }
                else
                    Tools::redirect($this->context->link->getAdminLink('AdminModules').'&configure='.$this->name.'&control='.$control.'&conf=4');
            }
            
        }
        $this->smarty->assign(array(
            'ets_crossell_sidebar' => $this->renderSidebar($control),
            'ets_crossell_body_html' => $this->renderAdminBodyHtml($control),
            'control' => $control,
            'ets_cs_module_dir' => $this->_path,
        ));
        return $this->display(__FILE__, 'admin.tpl');           
    }
    public function renderSidebar($control)
    {
        $intro = true;
        $localIps = array(
            '127.0.0.1',
            '::1'
        );
		$baseURL = Tools::strtolower(self::getBaseModLink());
		if(!Tools::isSubmit('intro') && (in_array(Tools::getRemoteAddr(), $localIps) || preg_match('/^.*(localhost|demo|test|dev|:\d+).*$/i', $baseURL)))
		    $intro = false;
        $this->context->smarty->assign(
            array(
                'sidebars' => $this->_sidebars,
                'control' => $control,
                'cs_link_module' => $this->context->link->getAdminLink('AdminModules').'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name,
                'other_modules_link' => isset($this->refs) ? $this->refs.$this->context->language->iso_code : $this->context->link->getAdminLink('AdminModules', true) . '&configure=' . $this->name.'&othermodules=1',
                'intro' => $intro,
                'refsLink' => isset($this->refs) ? $this->refs.$this->context->language->iso_code : false,
            )
        );
        return $this->display(__FILE__,'sidebar.tpl');
    }
    public static function getBaseModLink()
    {
        $context = Context::getContext();
        return (Configuration::get('PS_SSL_ENABLED_EVERYWHERE')?'https://':'http://').$context->shop->domain.$context->shop->getBaseURI();
    }
    public function renderAdminBodyHtml($control)
    {
        $languages = Language::getLanguages(false);
        $fields_form = array(
    		'form' => array(
    			'legend' => array(
    				'title' => ($control!='settings' ? $this->l('Product blocks').': ' : '').$this->_sidebars[$control],
    				'icon' => 'fa fa-list-ul'
    			),
    			'input' => array(),
                'submit' => array(
    				'title' => $this->l('Save'),
    			)
            ),
    	);
        $configs = $this->_configs[$control];
        $fields = array();
        if($control!='settings')
        {
            if($configs)
            {
                $first_field=true;
                foreach($configs as $key => $config){
                    $arg = array(
                        'type' =>'switch',
                        'label' => $config,
                        'first_field' => $first_field ? true : false,
                        'name' => 'ETS_CS_'.Tools::strtoupper($control).'_'.Tools::strtoupper($key),
                        'form_group_class' => 'ets-cs-form-group-field',
                        'values' => array(
    						array(
    							'id' => 'active_on',
    							'value' => 1,
    							'label' => $this->l('Yes')
    						),
    						array(
    							'id' => 'active_off',
    							'value' => 0,
    							'label' => $this->l('No')
    						)
    					)	
                    );
                    
                    $fields['ETS_CS_'.Tools::strtoupper($control).'_'.Tools::strtoupper($key)] = Configuration::get('ETS_CS_'.Tools::strtoupper($control).'_'.Tools::strtoupper($key));
                    $fields_form['form']['input'][] = $arg;
                    if(isset($this->_config_types[$key]['setting']) && $this->_config_types[$key]['setting'])
                    {
                        foreach($this->_config_types[$key]['setting'] as $index=> $setting)
                        {
                            $name = 'ETS_CS_'.Tools::strtoupper($control).'_'.Tools::strtoupper($key).'_'.Tools::strtoupper($setting['name']);
                            $arg = array(
                                'type' =>$setting['type'],
                                'label' => $setting['label'],
                                'begin_group' => $index==0 ? true:false,
                                'title_group' => $this->_config_types[$key]['title'],
                                'end_group' => $index==count($this->_config_types[$key]['setting'])-1 ? true:false,
                                'module_name' => isset($this->_config_types[$key]['module_name']) ? $this->_config_types[$key]['module_name']:false,
                                'warning' => isset($this->_config_types[$key]['warning']) ? $this->_config_types[$key]['warning']: false,
                                'info' => isset($this->_config_types[$key]['info']) ? $this->_config_types[$key]['info']: false,
                                'name' => $name,
                                'lang'=> isset($setting['lang']) ? $setting['lang']:false,
                                'desc' => isset($setting['desc']) ? $setting['desc'] :'',
                                'form_group_class' => (isset($setting['form_group_class'] ) ? $setting['form_group_class'].' ':'').$key,
                                'tree' => isset($setting['tree']) ? $setting['tree']:array(),
                                'required' => isset($setting['required']) ? $setting['required'] : false,
                                'values' => isset($setting['values']) ? $setting['values']:'',	
                                'options' => isset($setting['options']) ? $setting['options']:false,
                                
                            );
                            if(isset($setting['tree']))
                            {
                                $tree = $setting['tree'];
                                $tree['selected_categories'] = array(Configuration::get($name));
                                $arg['tree']= $tree;
                            }
                            if(isset($setting['lang'])  && $setting['lang'])
                            {
                                foreach($languages as $lang)
                                {
                                    $fields[$name][$lang['id_lang']] = Configuration::get($name,$lang['id_lang']);
                                }
                            }
                            else
                                $fields[$name] = Configuration::get($name);
                            $fields_form['form']['input'][] = $arg;
                        }
                    }
                    $first_field=false;
                }
            }
            $fields_form['form']['input'][] = array(
                'name' =>  'ETS_CS_'.Tools::strtoupper($control).'_LAYOUT',
                'type'=>'radio',
                'label' => $this->l('Product layout'),
                'global_field' => true,
                'values' => array(
                    array(
                        'id' => 'ETS_CS_'.Tools::strtoupper($control).'_LAYOUT_LIST',
                        'value'=>'list',
                        'label' => $this->l('List')
                    ),
                    array(
                        'id' => 'ETS_CS_'.Tools::strtoupper($control).'_LAYOUT_TAB',
                        'value'=>'tab',
                        'label' => $this->l('Tab')
                    ),
                ),
            );
            $fields_form['form']['input'][] = array(
                'name' =>  'ETS_CS_'.Tools::strtoupper($control).'_MODE',
                'type'=>'radio',
                'label' => $this->l('Product listing mode'),
                'values' => array(
                    array(
                        'id' => 'ETS_CS_'.Tools::strtoupper($control).'_MODE_GRID',
                        'value'=>'grid',
                        'label' => $this->l('Grid')
                    ),
                    array(
                        'id' => 'ETS_CS_'.Tools::strtoupper($control).'_MODE_SLIDE',
                        'value'=>'slide',
                        'label' => $this->l('Carousel slider')
                    ),
                ),
            );
            $fields_form['form']['input'][] = array(
                'name' =>  'ETS_CS_'.Tools::strtoupper($control).'_COUNT_PRODUCT',
                'type'=>'text',
                'required' => true,
                'label' => $this->l('Product count'),
                'desc' => $this->l('The number of products will be displayed per Ajax load'),
            );
            $fields_form['form']['input'][] = array(
                'name' =>  'ETS_CS_'.Tools::strtoupper($control).'_ROW_DESKTOP',
                'type'=>'select',
                'label' => $this->l('Number of displayed products per row on desktop'),
                'options' => array(
                    'query' => array(
                        array(
                            'id_option' =>1,
                            'name' =>1,
                        ),
                        array(
                            'id_option' =>2,
                            'name' =>2,
                        ),
                        array(
                            'id_option' =>3,
                            'name' =>3,
                        ),
                        array(
                            'id_option' =>4,
                            'name' =>4,
                        ),
                        array(
                            'id_option' =>5,
                            'name' =>5,
                        ),
                        array(
                            'id_option' =>6,
                            'name' =>6,
                        )
                    ),
                    'id' => 'id_option',
                    'name' => 'name'
                ),
            );
            $fields_form['form']['input'][] = array(
                'name' =>  'ETS_CS_'.Tools::strtoupper($control).'_ROW_TABLET',
                'type'=>'select',
                'label' => $this->l('Number of displayed products per row on tablet'),
                'options' => array(
                    'query' => array(
                        array(
                            'id_option' =>1,
                            'name' =>1,
                        ),
                        array(
                            'id_option' =>2,
                            'name' =>2,
                        ),
                        array(
                            'id_option' =>3,
                            'name' =>3,
                        ),
                        array(
                            'id_option' =>4,
                            'name' =>4,
                        ),
                        array(
                            'id_option' =>5,
                            'name' =>5,
                        ),
                        array(
                            'id_option' =>6,
                            'name' =>6,
                        )
                    ),
                    'id' => 'id_option',
                    'name' => 'name'
                ),
            );
            $fields_form['form']['input'][] = array(
                'name' =>  'ETS_CS_'.Tools::strtoupper($control).'_ROW_MOBILE',
                'type'=>'select',
                'label' => $this->l('Number of displayed products per row on mobile'),
                'options' => array(
                    'query' => array(
                        array(
                            'id_option' =>1,
                            'name' =>1,
                        ),
                        array(
                            'id_option' =>2,
                            'name' =>2,
                        ),
                        array(
                            'id_option' =>3,
                            'name' =>3,
                        ),
                        array(
                            'id_option' =>4,
                            'name' =>4,
                        ),
                        array(
                            'id_option' =>5,
                            'name' =>5,
                        ),
                        array(
                            'id_option' =>6,
                            'name' =>6,
                        )
                    ),
                    'id' => 'id_option',
                    'name' => 'name'
                ),
            );
            $fields['ETS_CS_'.Tools::strtoupper($control).'_ROW_DESKTOP'] = Configuration::get('ETS_CS_'.Tools::strtoupper($control).'_ROW_DESKTOP');
            $fields['ETS_CS_'.Tools::strtoupper($control).'_ROW_TABLET'] = Configuration::get('ETS_CS_'.Tools::strtoupper($control).'_ROW_TABLET');
            $fields['ETS_CS_'.Tools::strtoupper($control).'_ROW_MOBILE'] = Configuration::get('ETS_CS_'.Tools::strtoupper($control).'_ROW_MOBILE');
            $fields['ETS_CS_'.Tools::strtoupper($control).'_LAYOUT'] = Configuration::get('ETS_CS_'.Tools::strtoupper($control).'_LAYOUT');
            $fields['ETS_CS_'.Tools::strtoupper($control).'_MODE'] = Configuration::get('ETS_CS_'.Tools::strtoupper($control).'_MODE');
            $fields['ETS_CS_'.Tools::strtoupper($control).'_COUNT_PRODUCT'] = Configuration::get('ETS_CS_'.Tools::strtoupper($control).'_COUNT_PRODUCT');
        }
        else
        {
            foreach($configs as $config)
            {
                $fields_form['form']['input'][] = $config;
                $fields[$config['name']] = Configuration::get($config['name'],Tools::getValue($config['name']));
            }
        }
        $helper = new HelperForm();
    	$helper->show_toolbar = false;
    	$helper->table = $this->table;
    	$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
    	$helper->default_form_language = $lang->id;
    	$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
    	$this->fields_form = array();
    	$helper->module = $this;
    	$helper->identifier = $this->identifier;
    	$helper->submit_action = 'saveConfig';
    	$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name.'&control='.$control;
    	$helper->token = Tools::getAdminTokenLite('AdminModules');
    	$language = new Language((int)Configuration::get('PS_LANG_DEFAULT'));            
        $helper->override_folder = '/';
        $fields_position = Configuration::get('ETS_CS_'.Tools::strtoupper($control).'_POSITIONS') ? explode(',',Configuration::get('ETS_CS_'.Tools::strtoupper($control).'_POSITIONS')) :array_keys($this->_configs[$control]);
        $fields_postion_value = array();
        if($fields_position)
        {
            foreach($fields_position as &$field_position)
            {
                $fields_postion_value[] = 'ETS_CS_'.Tools::strtoupper($control).'_'.Tools::strtoupper($field_position);
            }
        }
        $helper->tpl_vars = array(
    		'base_url' => $this->context->shop->getBaseURL(),
    		'language' => array(
    			'id_lang' => $language->id,
    			'iso_code' => $language->iso_code
    		),
    		'fields_value' => $fields,
            'fields_position' => $fields_position,
            'fields_postion_value' =>$fields_postion_value,
            '_config_types' => $this->_config_types,
            'control' => Tools::strtoupper($control),
    		'languages' => $this->context->controller->getLanguages(),
    		'id_language' => $this->context->language->id,
            'isConfigForm' => true,
            'image_baseurl' => $this->_path.'views/img/',
            'page_title' => $this->_sidebars[$control],
            'tab' => $control,
        );
        return $helper->generateForm(array($fields_form));	
    }
    public function _saveConfig($control,$default=false)
    {
        $languages = Language::getLanguages(false);
        $configs = $this->_configs[$control];
        if($control!='settings')
        {
            if($configs)
            {
                foreach($configs as $key => $config)
                {
                    $name = 'ETS_CS_'.Tools::strtoupper($control).'_'.Tools::strtoupper($key);
                    if($default)
                    {
                        $value=0;
                    }else
                    {
                        $value = Tools::getValue($name);
                    }
                    Configuration::updateValue($name,$value);
                    if(isset($this->_config_types[$key]['setting']))
                    {
                        foreach($this->_config_types[$key]['setting'] as $setting)
                        {
                            $name = 'ETS_CS_'.Tools::strtoupper($control).'_'.Tools::strtoupper($key).'_'.Tools::strtoupper($setting['name']);
                            if(isset($setting['lang']) && $setting['lang'])
                            {
                                $valules = array();
                                foreach($languages as $lang)
                                {
                                    $valules[$lang['id_lang']] = trim(Tools::getValue($name.'_'.$lang['id_lang'])) ? trim(Tools::getValue($name.'_'.$lang['id_lang'])) : '';//trim(Tools::getValue($key.'_'.$id_lang_default))
                                }
                                Configuration::updateValue($name,$valules);
                            }
                            else
                            {
                                if($default)
                                {
                                    if(isset($setting['default']))
                                        $value= $setting['default'];
                                    else
                                        $value=0;
                                }
                                else
                                    $value = Tools::getValue($name);
                                Configuration::updateValue($name,$value);
                            }
                        }
                    }
                }
            }
            Configuration::updateValue('ETS_CS_'.Tools::strtoupper($control).'_LAYOUT',Tools::getValue('ETS_CS_'.Tools::strtoupper($control).'_LAYOUT','tab'));
            Configuration::updateValue('ETS_CS_'.Tools::strtoupper($control).'_MODE',Tools::getValue('ETS_CS_'.Tools::strtoupper($control).'_MODE','grid'));
            Configuration::updateValue('ETS_CS_'.Tools::strtoupper($control).'_COUNT_PRODUCT',Tools::getValue('ETS_CS_'.Tools::strtoupper($control).'_COUNT_PRODUCT',8));
            Configuration::updateValue('ETS_CS_'.Tools::strtoupper($control).'_ROW_DESKTOP',Tools::getValue('ETS_CS_'.Tools::strtoupper($control).'_ROW_DESKTOP'));
            Configuration::updateValue('ETS_CS_'.Tools::strtoupper($control).'_ROW_TABLET',Tools::getValue('ETS_CS_'.Tools::strtoupper($control).'_ROW_TABLET'));
            Configuration::updateValue('ETS_CS_'.Tools::strtoupper($control).'_ROW_MOBILE',Tools::getValue('ETS_CS_'.Tools::strtoupper($control).'_ROW_MOBILE'));
        }
        else
        {
            foreach($configs as $config)
            {
                if($config['type']!='categories')
                    Configuration::updateValue($config['name'],Tools::getValue($config['name'],(isset($config['default']) ? $config['default']:'') ));
                elseif(Tools::getValue($config['name'],(isset($config['default']) ? $config['default']:'')))
                    Configuration::updateValue($config['name'],implode(',',Tools::getValue($config['name'],(isset($config['default']) ? $config['default']:''))));
            }
        }
        $this->clearCache();
    }
    public function _checkValidatePost($control)
    {
        $errors = array();
        $languages = Language::getLanguages(false);
        $configs = $this->_configs[$control];
        if($configs)
        {
            foreach($configs as $key => $config)
            {
                if($control!='settings')
                {
                    if(!Tools::getValue('ETS_CS_'.Tools::strtoupper($control).'_COUNT_PRODUCT'))
                        $errors[] = $this->l('Product count is required');
                    elseif(!Validate::isUnsignedInt(Tools::getValue('ETS_CS_'.Tools::strtoupper($control).'_COUNT_PRODUCT')))
                        $errors[]= $this->l('Product count is not valid'); 
                    if(isset($this->_config_types[$key]['setting']) && Tools::getValue('ETS_CS_'.Tools::strtoupper($control).'_'.Tools::strtoupper($key)))
                    {
                        foreach($this->_config_types[$key]['setting'] as $setting)
                        {
                            $name = 'ETS_CS_'.Tools::strtoupper($control).'_'.Tools::strtoupper($key).'_'.Tools::strtoupper($setting['name']);
                            if((isset($setting['required']) && $setting['required']) ||  (isset($setting['validate']) && $setting['validate'] && method_exists('Validate',$setting['validate'])))
                            {
                                $validate = $setting['validate'];
                                if(isset($setting['lang']) && $setting['lang'])
                                { 
                                    foreach($languages as $lang)
                                    {
                                        if(isset($setting['required']) && $setting['required']  && !Tools::getValue($name.'_'.$lang['id_lang']))
                                            $errors[] = $setting['label'].' ('.$this->_config_types[$key]['title'].')'.' '.$this->l('is required ');
                                        elseif((isset($setting['validate']) && $setting['validate'] && method_exists('Validate',$setting['validate'])) && !Validate::$validate(trim(Tools::getValue($name.'_'.$lang['id_lang']))))
                                            $errors[] =  $setting['label'].' ('.$this->_config_types[$key]['title'].')'.' '.$this->l('is not valid in ').$lang['iso_code'];
                                    }
                                }
                                else
                                {
                                    if(isset($setting['required']) && $setting['required'] && !Tools::getValue($name))
                                        $errors[] = $setting['label'].' ('.$this->_config_types[$key]['title'].')'.' '. $this->l('is required');
                                    elseif(isset($setting['validate']) && $setting['validate'] && !Validate::$validate(trim(Tools::getValue($name))))
                                        $errors[] = $setting['label'].' ('.$this->_config_types[$key]['title'].')'.' '. $this->l('is not valid');
                                }
                                unset($validate);
                            }
                        }
                    }
                }
                else
                {
                    $validate = isset($config['validate']) ? $config['validate']:'';
                    $name = $config['name'];
                    if(isset($config['lang']) && $config['lang'])
                    { 
                        foreach($languages as $lang)
                        {
                            if(isset($config['required']) && $config['required']  && !Tools::getValue($name.'_'.$lang['id_lang']))
                                $errors[] = $config['label'].' '.$this->l('is required ');
                            elseif((isset($config['validate']) && $config['validate'] && method_exists('Validate',$config['validate'])) && !Validate::$validate(trim(Tools::getValue($name.'_'.$lang['id_lang']))))
                                $errors[] =  $config['label'].' '.$this->l('is not valid in ').$lang['iso_code'];
                        }
                    }
                    else
                    {
                        if(isset($config['required']) && $config['required'] && !Tools::getValue($name))
                            $errors[] = $config['label'].' '. $this->l('is required');
                        elseif($validate &&  !Validate::$validate(trim(Tools::getValue($name))))
                            $errors[] = $config['label'].' '. $this->l('is not valid');
                    }
                    unset($validate);
                }
            }
         }
         if(!$errors)
            return true;
         else
         {
            die(
                Tools::jsonEncode(
                array(
                    'errors' => $this->displayError($errors),
                )
            )
            );
         }       
    }
    public function hookDisplayHeader()
    {
        if(!$this->_configs)
            $this->_defines();
        if(!$this->is17 && Tools::getValue('controller')!='index' && Tools::getValue('controller')!='category')
            $this->context->controller->addCSS($this->_path . 'views/css/product_list16.css', 'all');
        if(Tools::isSubmit('getCrosssellContent'))
        {
            die(
                Tools::jsonEncode(
                    array(
                        'product_list' => Hook::exec('display'.Tools::getValue('tab'),array('name_page'=>Tools::getValue('page_name'),'id_product'=>Tools::getValue('id_product')),$this->id),
                    )
                )  
            );
        }
        if(Tools::isSubmit('sortProductsCrosssellContent'))
        {
            die(
                Tools::jsonEncode(
                    array(
                        'product_list' => Hook::exec('display'.Tools::getValue('tab'),array('name_page'=>Tools::getValue('page_name'),'id_product'=>Tools::getValue('id_product'),'order_by'=>Tools::getValue('sort_by')),$this->id),
                    )
                )  
            );
        }
        if(Tools::getValue('getProductPopupAdded'))
        {
            die(
                Tools::jsonEncode(
                    array(
                        'product_lists' => Hook::exec('displayProductPopupAdded',array('name_page'=>'added_popup_page','id_product'=>Tools::getValue('id_product')),$this->id),
                    )
                )  
            );
        }
        if(Tools::getValue('getProductExtraPage') && !$this->is17)
        {
            die(
                Tools::jsonEncode(
                    array(
                        'product_lists' => Hook::exec('displayContentWrapperBottom',array(),$this->id),
                    )
                )  
            );
        }
        $this->hookActionPageCacheAjax();
        $this->context->controller->addCSS($this->_path . 'views/css/slick.css', 'all');
        $this->context->controller->addCSS($this->_path . 'views/css/front.css', 'all');
        $this->context->controller->addJS($this->_path . 'views/js/slick.min.js');
        $this->context->controller->addJS($this->_path . 'views/js/front.js');
        if(!$this->is17)
            $this->context->controller->addCSS($this->_path . 'views/css/front16.css', 'all');
        $this->context->smarty->assign(
            array(
                'ets_crosssell_16' => !$this->is17,
            )
        );
        return $this->display(__FILE__,'header.tpl');
    }
    public function hookDisplayOrderConfirmation()
    {
        if(!$this->is17)
            return $this->_execHook('order_conf');
    }
    public function hookDisplayOrderConfirmation2()
    {
        if($this->is17)
            return $this->_execHook('order_conf');
    }
    public function hookDisplayHome()
    {
        return $this->_execHook('home_page');
    }
    public function hookDisplaySearch()
    {
        return $this->_execHook('search_page');
    }
    public function hookDisplayFooterProduct($params)
    {
        return $this->_execHook('product_page',array('id_product'=>$params['product']->id));
    }
    public function hookDisplayContentWrapperBottom()
    {
        if(Tools::getValue('controller')=='category')
        {
            return $this->_execHook('category_page');
        }   
        if(Tools::getValue('controller')=='contact')
        {
            return $this->_execHook('contact_page');
        }
        if(Tools::getValue('controller')=='cms')
            return $this->_execHook('cms_page');
    }
    public function hookDisplayProductAdditionalInfo()
    {
        if(Tools::getValue('action')=='quickview' && $this->is17)
        {
            return $this->_execHook('quick_view_page',array('id_product'=>Tools::getValue('id_product')));
        }
    }
    public function hookDisplayRightColumnProduct()
    {
        if(Tools::isSubmit('content_only') && !$this->is17)
            return $this->_execHook('quick_view_page',array('id_product'=>Tools::getValue('id_product')));
    }
    public function hookDisplayProductPopupAdded($params)
    {
        return $this->_execHook('added_popup_page',array('id_product'=>isset($params['id_product'])? $params['id_product']:0 ));
    }
    public function hookDisplayShoppingCartFooter()
    {
        return $this->_execHook('cart_page');
    }
    public function displayRecommendedModules()
    {
        $cacheDir = dirname(__file__) . '/../../cache/'.$this->name.'/';
        $cacheFile = $cacheDir.'module-list.xml';
        $cacheLifeTime = 24;
        $cacheTime = (int)Configuration::getGlobalValue('ETS_MOD_CACHE_'.$this->name);
        $profileLinks = array(
            'en' => 'https://addons.prestashop.com/en/207_ets-soft',
            'fr' => 'https://addons.prestashop.com/fr/207_ets-soft',
            'it' => 'https://addons.prestashop.com/it/207_ets-soft',
            'es' => 'https://addons.prestashop.com/es/207_ets-soft',
        );
        if(!is_dir($cacheDir))
        {
            @mkdir($cacheDir, 0755,true);
            if ( @file_exists(dirname(__file__).'/index.php')){
                @copy(dirname(__file__).'/index.php', $cacheDir.'index.php');
            }
        }
        if(!file_exists($cacheFile) || !$cacheTime || time()-$cacheTime > $cacheLifeTime * 60 * 60)
        {
            if(file_exists($cacheFile))
                @unlink($cacheFile);
            if($xml = self::file_get_contents($this->shortlink.'ml.xml'))
            {
                $xmlData = @simplexml_load_string($xml);
                if($xmlData && (!isset($xmlData->enable_cache) || (int)$xmlData->enable_cache))
                {
                    @file_put_contents($cacheFile,$xml);
                    Configuration::updateGlobalValue('ETS_MOD_CACHE_'.$this->name,time());
                }
            }
        }
        else
            $xml = Tools::file_get_contents($cacheFile);
        $modules = array();
        $categories = array();
        $categories[] = array('id'=>0,'title' => $this->l('All categories'));
        $enabled = true;
        $iso = Tools::strtolower($this->context->language->iso_code);
        $moduleName = $this->displayName;
        $contactUrl = '';
        if($xml && ($xmlData = @simplexml_load_string($xml)))
        {
            if(isset($xmlData->modules->item) && $xmlData->modules->item)
            {
                foreach($xmlData->modules->item as $arg)
                {
                    if($arg)
                    {
                        if(isset($arg->module_id) && (string)$arg->module_id==$this->name && isset($arg->{'title'.($iso=='en' ? '' : '_'.$iso)}) && (string)$arg->{'title'.($iso=='en' ? '' : '_'.$iso)})
                            $moduleName = (string)$arg->{'title'.($iso=='en' ? '' : '_'.$iso)};
                        if(isset($arg->module_id) && (string)$arg->module_id==$this->name && isset($arg->contact_url) && (string)$arg->contact_url)
                            $contactUrl = $iso!='en' ? str_replace('/en/','/'.$iso.'/',(string)$arg->contact_url) : (string)$arg->contact_url;
                        $temp = array();
                        foreach($arg as $key=>$val)
                        {
                            if($key=='price' || $key=='download')
                                $temp[$key] = (int)$val;
                            elseif($key=='rating')
                            {
                                $rating = (float)$val;
                                if($rating > 0)
                                {
                                    $ratingInt = (int)$rating;
                                    $ratingDec = $rating-$ratingInt;
                                    $startClass = $ratingDec >= 0.5 ? ceil($rating) : ($ratingDec > 0 ? $ratingInt.'5' : $ratingInt);
                                    $temp['ratingClass'] = 'mod-start-'.$startClass;
                                }
                                else
                                    $temp['ratingClass'] = '';
                            }
                            elseif($key=='rating_count')
                                $temp[$key] = (int)$val;
                            else
                                $temp[$key] = (string)strip_tags($val);
                        }
                        if($iso)
                        {
                            if(isset($temp['link_'.$iso]) && isset($temp['link_'.$iso]))
                                $temp['link'] = $temp['link_'.$iso];
                            if(isset($temp['title_'.$iso]) && isset($temp['title_'.$iso]))
                                $temp['title'] = $temp['title_'.$iso];
                            if(isset($temp['desc_'.$iso]) && isset($temp['desc_'.$iso]))
                                $temp['desc'] = $temp['desc_'.$iso];
                        }
                        $modules[] = $temp;
                    }
                }
            }
            if(isset($xmlData->categories->item) && $xmlData->categories->item)
            {
                foreach($xmlData->categories->item as $arg)
                {
                    if($arg)
                    {
                        $temp = array();
                        foreach($arg as $key=>$val)
                        {
                            $temp[$key] = (string)strip_tags($val);
                        }
                        if(isset($temp['title_'.$iso]) && $temp['title_'.$iso])
                                $temp['title'] = $temp['title_'.$iso];
                        $categories[] = $temp;
                    }
                }
            }
        }
        if(isset($xmlData->{'intro_'.$iso}))
            $intro = $xmlData->{'intro_'.$iso};
        else
            $intro = isset($xmlData->intro_en) ? $xmlData->intro_en : false;
        $this->smarty->assign(array(
            'modules' => $modules,
            'enabled' => $enabled,
            'module_name' => $moduleName,
            'categories' => $categories,
            'img_dir' => $this->_path . 'views/img/',
            'intro' => $intro,
            'shortlink' => $this->shortlink,
            'ets_profile_url' => isset($profileLinks[$iso]) ? $profileLinks[$iso] : $profileLinks['en'],
            'trans' => array(
                'txt_must_have' => $this->l('Must-Have'),
                'txt_downloads' => $this->l('Downloads!'),
                'txt_view_all' => $this->l('View all our modules'),
                'txt_fav' => $this->l('Prestashop\'s favourite'),
                'txt_elected' => $this->l('Elected by merchants'),
                'txt_superhero' => $this->l('Superhero Seller'),
                'txt_partner' => $this->l('Module Partner Creator'),
                'txt_contact' => $this->l('Contact us'),
                'txt_close' => $this->l('Close'),
            ),
            'contactUrl' => $contactUrl,
         ));
         echo $this->display(__FILE__, 'module-list.tpl');
         die;
    }
    public static function file_get_contents($url, $use_include_path = false, $stream_context = null, $curl_timeout = 60)
    {
        if ($stream_context == null && preg_match('/^https?:\/\//', $url)) {
            $stream_context = stream_context_create(array(
                "http" => array(
                    "timeout" => $curl_timeout,
                    "max_redirects" => 101,
                    "header" => 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36'
                ),
                "ssl"=>array(
                    "allow_self_signed"=>true,
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                ),
            ));
        }
        if (function_exists('curl_init')) {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => html_entity_decode($url),
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36',
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_TIMEOUT => $curl_timeout,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_FOLLOWLOCATION => true,
            ));
            $content = curl_exec($curl);
            curl_close($curl);
            return $content;
        } elseif (in_array(ini_get('allow_url_fopen'), array('On', 'on', '1')) || !preg_match('/^https?:\/\//', $url)) {
            return Tools::file_get_contents($url, $use_include_path, $stream_context);
        } else {
            return false;
        }
    }
    public function _execHook($control,$params=array())
    {
        if(!$this->_configs)
            $this->_defines();
        $layout = Configuration::get('ETS_CS_'.Tools::strtoupper($control).'_LAYOUT');
        $configs = $this->_configs[$control];
        $sc_configs = array();
        $fields_position = Configuration::get('ETS_CS_'.Tools::strtoupper($control).'_POSITIONS') ? explode(',',Configuration::get('ETS_CS_'.Tools::strtoupper($control).'_POSITIONS')) :array_keys($this->_configs[$control]);
        if($fields_position)
        {
            foreach($fields_position as $filed_position)
            {
                if(Configuration::get('ETS_CS_'.Tools::strtoupper($control).'_'.Tools::strtoupper($filed_position)) && ($filed_position!='viewedproducts' || Hook::exec('display'.$filed_position,array('name_page'=>$control,'id_product'=>isset($params['id_product']) ? $params['id_product']:0),$this->id)))
                {
                    $title = Configuration::get('ETS_CS_'.Tools::strtoupper($control).'_'.Tools::strtoupper($filed_position).'_TITLE',$this->context->language->id);
                    if($id_categories = Configuration::get('ETS_CS_CATEGORY_SUB'))
                    {
                          $id_categories = explode(',',$id_categories);
                          $sub_categories = Db::getInstance()->executeS('SELECT * FROM '._DB_PREFIX_.'category c
                          INNER JOIN '._DB_PREFIX_.'category_shop cs ON (c.id_category=cs.id_category AND cs.id_shop="'.(int)$this->context->shop->id.'")
                          LEFT JOIN '._DB_PREFIX_.'category_lang cl ON (c.id_category=cl.id_category)
                          WHERE c.active=1 AND cl.id_lang="'.(int)$this->context->language->id.'" AND c.id_category IN ('.implode(',',array_map('intval',$id_categories)).') GROUP BY c.id_category');
                    }
                    else
                        $sub_categories=array();
                    $sc_configs[] = array(
                        'tab_name' => $title ? $title : $configs[$filed_position],
                        'hook' => 'display'.$filed_position,
                        'tab' => $filed_position,
                        'sub_categories' => Configuration::get('ETS_CS_'.Tools::strtoupper($control).'_'.Tools::strtoupper($filed_position).'_DISPLAY_SUB_CATEGORY') ? $sub_categories : array()
                    );
                }
            }
        }
        $this->smarty->assign(
            array(
                'sc_configs' => $sc_configs,
                'name_page' => $control,
                'id_product' => isset($params['id_product']) ? $params['id_product']:0,
                'layout_mode' => Configuration::get('ETS_CS_'.Tools::strtoupper($control).'_MODE') ? Configuration::get('ETS_CS_'.Tools::strtoupper($control).'_MODE') :'list',
            )
        );   
        if($layout=='tab' && $control!= 'search_page') {
            return $this->display(__FILE__,'layout_tab.tpl');
        } elseif ($control === 'search_page') {
            return $this->display(__FILE__,'layout_search.tpl');
        }
        else {
            return $this->display(__FILE__,'layout_list.tpl');
        }
    }
    public function getProducts($id_category = false, $page = 0, $per_page = 12, $order_by = 'cp.position', $id_products = false,$not_id_products = false,$excludedOld = false,$includeSub=false,$id_manufacturer=0,$no_in_cart=false)
    {
        $page = (int)$page;
        if ($page <= 0)
            $page = 1;
        $per_page = (int)$per_page;
        if ($per_page <= 0)
            $per_page = 12;
        $active = true;
        $front = true;
        $nb_days_new_product = Configuration::get('PS_NB_DAYS_NEW_PRODUCT');
        $id_lang = (int)$this->context->language->id;
        if (!Validate::isUnsignedInt($nb_days_new_product)) {
            $nb_days_new_product = 20;
        }
        if ($order_by && !in_array($order_by, array('price asc', 'price desc', 'pl.name asc', 'pl.name desc', 'cp.position asc', 'p.id_product desc', 'rand')))
            $order_by = 'cp.position asc';
        if ($order_by == 'price asc') {
            $order_by = 'orderprice asc';
        } elseif ($order_by == 'price desc') {
            $order_by = 'orderprice desc';
        }
        $prev_version = version_compare(_PS_VERSION_, '1.6.1.0', '<');
        $sql = 'SELECT DISTINCT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) AS quantity' . ($prev_version? ' ,IFNULL(product_attribute_shop.id_product_attribute, 0)':' ,MAX(product_attribute_shop.id_product_attribute)') . ' id_product_attribute, pl.`description`, pl.`description_short`, pl.`available_now`,
    					pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, IFNULL(image_shop.`id_image`, i.`id_image`) id_image,
    					il.`legend` as legend, m.`name` AS manufacturer_name, cl.`name` AS category_default,
    					DATEDIFF(product_shop.`date_add`, DATE_SUB("' . date('Y-m-d') . ' 00:00:00",
    					INTERVAL ' . (int)$nb_days_new_product . ' DAY)) > 0 AS new, product_shop.price AS orderprice
                FROM `' . _DB_PREFIX_ . 'category_product` cp
                LEFT JOIN `'._DB_PREFIX_.'product` p ON p.`id_product` = cp.`id_product`
                '.Shop::addSqlAssociation('product', 'p').
                ' LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl ON (product_shop.`id_category_default` = cl.`id_category` AND cl.`id_lang` = ' . (int)$id_lang . Shop::addSqlRestrictionOnLang('cl') . ')'.
                (!$prev_version?
                    'LEFT JOIN '._DB_PREFIX_.'product_attribute pa ON (pa.id_product = p.id_product)'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.default_on=1').'':
                    'LEFT JOIN `'._DB_PREFIX_.'product_attribute_shop` product_attribute_shop ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop='.(int)$this->context->shop->id.')'
                ).
                (
                    Tools::getValue('id_ets_css_sub_category')?' LEFT JOIN '._DB_PREFIX_.'category_product cp2 ON (cp2.id_product=p.id_product)':''
                )
                .Product::sqlStock('p', 0, false, $this->context->shop).'
                LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = ' . (int)$id_lang . Shop::addSqlRestrictionOnLang('pl') . ')'.
                (!$prev_version?
                    'LEFT JOIN `' . _DB_PREFIX_ . 'image` i ON (i.`id_product` = p.`id_product`)'. Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover = 1') :
                    'LEFT JOIN `' . _DB_PREFIX_ . 'image_shop` image_shop ON (image_shop.`id_product` = p.`id_product` AND image_shop.id_shop=' . (int)$this->context->shop->id . ')'
                ).'
                LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')	
                LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
                '.($excludedOld && ($this->context->customer->isLogged() || isset($this->context->cookie->id_cart) && $this->context->cookie->id_cart) ?
                ((int)$this->context->customer->isLogged() ? '
                                LEFT JOIN (
                                    SELECT od.product_id as id_product
                                    FROM '._DB_PREFIX_.'order_detail od
                                    JOIN '._DB_PREFIX_.'orders o ON od.id_order=o.id_order
                                    WHERE o.id_customer='.(int)$this->context->customer->id.'
                                ) od2 on p.id_product=od2.id_product
                            ' : '').(isset($this->context->cookie->id_cart) && $this->context->cookie->id_cart ? '
                                LEFT JOIN '._DB_PREFIX_.'cart_product cap ON cap.id_product=p.id_product AND cap.id_cart='.(int)$this->context->cookie->id_cart
                    : '') : ''
                ).'
                WHERE product_shop.`id_shop` = ' . (int)$this->context->shop->id . '
                '.($id_category ? ' AND ' . (!$includeSub ? 'cp.`id_category` = ' . (int)$id_category : 'cp.`id_category` IN(' . implode(',', $this->getTreeIds((int)$id_category)) . ')') : '')
                .(Tools::getValue('id_ets_css_sub_category') ? ' AND cp2.id_category="'.(int)Tools::getValue('id_ets_css_sub_category').'"':'')
                . ($active ? ' AND product_shop.`active` = 1' : '')
                . ($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '')
                . ($id_products ? ' AND product_shop.`id_product` IN ('.(implode(',',$id_products)).')' : '')
                .($excludedOld ? (((int)$this->context->customer->isLogged() ? ' AND od2.id_product is NULL ' : '')
                .(isset($this->context->cookie->id_cart) && $this->context->cookie->id_cart ? ' AND cap.id_product is NULL ' : '')) : '')
                . ($not_id_products ? ' AND product_shop.`id_product` NOT IN ('.(implode(',',$not_id_products)).')' : '')
                .($id_manufacturer ? ' AND m.id_manufacturer ="'.(int)$id_manufacturer.'"':'')
                .($no_in_cart && $this->context->cart->id ? ' AND product_shop.id_product NOT IN (SELECT id_product FROM '._DB_PREFIX_.'cart_product WHERE id_cart="'.(int)$this->context->cart->id.'")':'')
                . ' GROUP BY p.id_product'
                . (!Configuration::get('ETS_CS_OUT_OF_STOCK')? ' HAVING quantity > 0 ' : '')
                . ( ($order_by) ? ($order_by != 'rand' ? ' ORDER BY ' . pSQL($order_by) : ' ORDER BY RAND(' . (int)$this->getRandomSeed(). ')') : '') . '
                LIMIT ' . (int)($page-1)*$per_page . ',' . (int)$per_page;
        $products = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql, true, true);
        if (!$products) {
            return array();
        }
        if ($order_by == 'orderprice asc') {
            Tools::orderbyPrice($products, 'asc');
        } elseif ($order_by == 'orderprice desc') {
            Tools::orderbyPrice($products, 'desc');
        }
        $products = Product::getProductsProperties($id_lang, $products);
        if ($this->is17) {
            $products = Ets_crosssell::productsForTemplate($products);
        }
        return $products;
    }
    public function getTreeIds($id_root)
    {
        $ids = array();
        $ids[] = $id_root;
        if ($children = $this->getChildren($id_root))
            foreach ($children as $child) {
                $ids = array_merge($ids, $this->getTreeIds($child['id_category']));
            }
        $ids[]=$id_root;
        return array_unique($ids);
    }
    public function getChildren($id_category=false)
    {
        $orderBy = 'cl.name asc';
        if(!$id_category)
            $id_category = Category::getRootCategory()->id;
        return Db::getInstance()->executeS("
            SELECT c.id_category, cl.name 
            FROM " . _DB_PREFIX_ . "category c
            LEFT JOIN " . _DB_PREFIX_ . "category_lang cl ON c.id_category=cl.id_category AND cl.id_lang=" . (int)$this->context->language->id . " AND cl.id_shop ='" . (int)$this->context->shop->id . "'
            LEFT JOIN " . _DB_PREFIX_ . "category_shop cs ON cs.id_category=c.id_category AND cs.id_shop=" . (int)$this->context->shop->id . "
            WHERE c.active=1 AND c.id_parent=" . (int)$id_category . "
            ORDER BY " . pSQL($orderBy) . "
        ");
    }
    public static function productsForTemplate($products, Context $context = null)
    {
        if (!$products || !is_array($products))
            return array();
        if (!$context)
            $context = Context::getContext();
        $assembler = new ProductAssembler($context);
        $presenterFactory = new ProductPresenterFactory($context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = new PrestaShop\PrestaShop\Core\Product\ProductListingPresenter(
            new PrestaShop\PrestaShop\Adapter\Image\ImageRetriever(
                $context->link
            ),
            $context->link,
            new PrestaShop\PrestaShop\Adapter\Product\PriceFormatter(),
            new PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever(),
            $context->getTranslator()
        );

        $products_for_template = array();

        foreach ($products as $rawProduct) {
            $products_for_template[] = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($rawProduct),
                $context->language
            );
        }
        return $products_for_template;
    }
    public function getRecommendProducts($perpage = 12){
        if (!(isset($this->context->cookie->viewed) && $this->context->cookie->viewed || $this->context->customer->isLogged() || isset($this->context->cookie->id_cart) && $this->context->cookie->id_cart)){
            $products = $this->getProducts(false,1,$perpage,'rand',false,false,false,false,0,true);
        }else{
            $sqlViewed = false;
            $sqlCart = false;
            if( isset($this->context->cookie->viewed) && $this->context->cookie->viewed &&
                ($viewedProductsIds = array_map('intval',explode(',',$this->context->cookie->viewed)))){
                $totalViewed = count($viewedProductsIds);
                $sqlViewed='(';
                for($ik = $totalViewed; $ik>($totalViewed > $perpage ? $totalViewed-$perpage : 0); $ik--)
                {
                    $sqlViewed .= ($ik==$totalViewed ? '' : ' UNION ').' SELECT '.$viewedProductsIds[$ik-1].' as id_product';
                }
                $sqlViewed.=') as viewed_products ';
                $sqlViewed = '
                    (SELECT p.id_product, p.id_category_default 
                    FROM `'._DB_PREFIX_.'product` p
                    JOIN '.$sqlViewed. ' ON viewed_products.id_product=p.id_product AND p.active=1)
                ';
            }
            if ($this->context->customer->isLogged() || isset($this->context->cookie->id_cart) && $this->context->cookie->id_cart) {
                $sqlCart = '
                    (SELECT p.id_product, p.id_category_default 
                    FROM '._DB_PREFIX_.'cart_product cp
                    JOIN '._DB_PREFIX_.'cart c ON cp.id_cart=c.id_cart AND '.($this->context->customer->isLogged() ?' c.id_customer='.(int)$this->context->customer->id : ' c.id_cart='.(int)$this->context->cookie->id_cart).' 
                    JOIN '._DB_PREFIX_.'product p ON (cp.id_product = p.id_product)
                    ORDER BY cp.date_add DESC 
                    LIMIT '.(int)$perpage.')
                ';
            }
            $sqlCoreProducts = $sqlViewed && $sqlCart ? $sqlViewed." UNION ".$sqlCart : ($sqlViewed ? $sqlViewed : $sqlCart);
            $sql = 'SELECT DISTINCT core_products.id_product as id1,cp.id_product as id2, ac.id_product_2 as id3 
                    FROM `'._DB_PREFIX_.'category_product` cp
                    RIGHT JOIN ('.$sqlCoreProducts.') core_products ON core_products.id_category_default=cp.id_category AND core_products.id_product!=cp.id_product
                    LEFT JOIN '._DB_PREFIX_.'accessory ac ON ( core_products.id_product = ac.id_product_1)                    
                    ORDER BY RAND() 
                    LIMIT '.(int)$perpage*2;
            $pids = array();
            $filterSql = "";
            if($products = Db::getInstance()->executeS($sql))
            {
                foreach ($products as $product)
                {
                    if($product['id1'] && !in_array($product['id1'],$pids))
                    {
                        $filterSql .= ($filterSql ? " UNION " : "")." SELECT ".$product['id1']." as id_product ";
                        $pids[] = $product['id1'];
                    }
                    if($product['id2'] && !in_array($product['id2'],$pids))
                    {
                        $filterSql .= ($filterSql ? " UNION " : "")." SELECT ".$product['id2']." as id_product ";
                        $pids[] = $product['id2'];
                    }
                    if($product['id3'] && !in_array($product['id3'],$pids))
                    {
                        $filterSql .= ($filterSql ? " UNION " : "")." SELECT ".$product['id3']." as id_product ";
                        $pids[] = $product['id3'];
                    }
                    if(!($this->context->customer->isLogged() || isset($this->context->cookie->id_cart) && $this->context->cookie->id_cart) && count($pids)>=$perpage)
                        break;
                }
                if($this->context->customer->isLogged() || isset($this->context->cookie->id_cart) && $this->context->cookie->id_cart)
                {
                    $pids = array();
                    $sql = 'SELECT pids.id_product
                            FROM ('.$filterSql.') pids
                            '.((int)$this->context->customer->isLogged() ? '
                                LEFT JOIN (
                                    SELECT od.product_id as id_product
                                    FROM '._DB_PREFIX_.'order_detail od
                                    JOIN '._DB_PREFIX_.'orders o ON od.id_order=o.id_order
                                    WHERE o.id_customer='.(int)$this->context->customer->id.'
                                ) od2 on pids.id_product=od2.id_product
                            ' : '').(isset($this->context->cookie->id_cart) && $this->context->cookie->id_cart ? '
                                LEFT JOIN '._DB_PREFIX_.'cart_product cp ON cp.id_product=pids.id_product AND cp.id_cart='.(int)$this->context->cookie->id_cart
                              : '').'
                            WHERE 1 '.((int)$this->context->customer->isLogged() ? ' AND od2.id_product is NULL' : '')
                            .(isset($this->context->cookie->id_cart) && $this->context->cookie->id_cart ? ' AND cp.id_product is NULL' : '');

                    if($products = Db::getInstance()->executeS($sql))
                    {
                        foreach ($products as $product)
                        {
                            if(!in_array($product['id_product'],$pids))
                            {
                                $pids[] = $product['id_product'];
                            }
                        }
                    }
                }
                $products = $this->getProducts(false,1,$perpage,'rand',$pids,false,$pids ? false : true,false,0,true);
            }
            else
                $products = array();
            if (($total_extends = count($products))< $perpage){
                $products_extends = $this->getProducts(false,1,$perpage-$total_extends,'rand',false,$pids,true,false,0,true);
                $products = array_merge($products,$products_extends);
            }
        }
        return $products;
    }
    public function getMostViewedProducts($perpage=12,$order_by ='pviewed.viewed DESC')
    {
        $active = true;
        $front = true;
        $nb_days_new_product = Configuration::get('PS_NB_DAYS_NEW_PRODUCT');
        $id_lang = (int)$this->context->language->id;
        if (!Validate::isUnsignedInt($nb_days_new_product)) {
            $nb_days_new_product = 20;
        }
        $prev_version = version_compare(_PS_VERSION_, '1.6.1.0', '<');
        $sql = 'SELECT DISTINCT p.*,pviewed.viewed as total_viewed, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) AS quantity' . ($prev_version? ' ,IFNULL(product_attribute_shop.id_product_attribute, 0)':' ,MAX(product_attribute_shop.id_product_attribute)') . ' id_product_attribute, pl.`description`, pl.`description_short`, pl.`available_now`,
    					pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, IFNULL(image_shop.`id_image`, i.`id_image`) id_image,
    					il.`legend` as legend, m.`name` AS manufacturer_name, cl.`name` AS category_default,
    					DATEDIFF(product_shop.`date_add`, DATE_SUB("' . date('Y-m-d') . ' 00:00:00",
    					INTERVAL ' . (int)$nb_days_new_product . ' DAY)) > 0 AS new, product_shop.price AS orderprice
                FROM `' . _DB_PREFIX_ . 'category_product` cp
                LEFT JOIN `'._DB_PREFIX_.'product` p ON p.`id_product` = cp.`id_product`
                LEFT JOIN `'._DB_PREFIX_.'ets_crosssell_product_viewed` pviewed ON (pviewed.id_product=p.id_product)
                '.Shop::addSqlAssociation('product', 'p').
                ' LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl ON (product_shop.`id_category_default` = cl.`id_category` AND cl.`id_lang` = ' . (int)$id_lang . Shop::addSqlRestrictionOnLang('cl') . ')'.
                (!$prev_version?
                    'LEFT JOIN '._DB_PREFIX_.'product_attribute pa ON (pa.id_product = p.id_product)'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.default_on=1').'':
                    'LEFT JOIN `'._DB_PREFIX_.'product_attribute_shop` product_attribute_shop ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop='.(int)$this->context->shop->id.')'
                )
                .Product::sqlStock('p', 0, false, $this->context->shop).'
                LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = ' . (int)$id_lang . Shop::addSqlRestrictionOnLang('pl') . ')'.
                (!$prev_version?
                    'LEFT JOIN `' . _DB_PREFIX_ . 'image` i ON (i.`id_product` = p.`id_product`)'. Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover = 1') :
                    'LEFT JOIN `' . _DB_PREFIX_ . 'image_shop` image_shop ON (image_shop.`id_product` = p.`id_product` AND image_shop.id_shop=' . (int)$this->context->shop->id . ')'
                ).'
                LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')	
                LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
                WHERE pviewed.viewed >0 && product_shop.`id_shop` = ' . (int)$this->context->shop->id
                . ($active ? ' AND product_shop.`active` = 1' : '')
                .(Tools::getValue('id_ets_css_sub_category') ? ' AND cp.id_category="'.(int)Tools::getValue('id_ets_css_sub_category').'"':'')
                . ($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '')
                . ' GROUP BY p.id_product'
                . (!Configuration::get('ETS_CS_OUT_OF_STOCK')? ' HAVING quantity > 0 ' : '')
                . ( ($order_by) ? ($order_by != 'rand' ? ' ORDER BY ' . pSQL($order_by) : ' ORDER BY RAND(' . (Configuration::get('ETS_HOMECAT_ENBLE_LOAD_MORE') || Configuration::get('ETS_HOMECAT_PRODUCTS_LAYOUT') == 'carousel' ? (int)$this->getRandomSeed() : '') . ')') : '') . '
                LIMIT 0,'.$perpage ;
        $products = Db::getInstance()->executeS($sql);
        if (!$products) {
            return array();
        }
        if ($order_by == 'orderprice asc') {
            Tools::orderbyPrice($products, 'asc');
        } elseif ($order_by == 'orderprice desc') {
            Tools::orderbyPrice($products, 'desc');
        }
        $products = Product::getProductsProperties($id_lang, $products);
        if ($this->is17) {
            $products = Ets_crosssell::productsForTemplate($products);
        }
        return $products;
    }
    public function getProductYouMightAlsoLike($id_product=0,$count_product=8,$order_by ='total_product DESC')
    {
        $active = true; 
        $front = true; 
        $nb_days_new_product = Configuration::get('PS_NB_DAYS_NEW_PRODUCT');
        $id_lang = (int)$this->context->language->id;
        if (!Validate::isUnsignedInt($nb_days_new_product)) {
            $nb_days_new_product = 20;
        }
        $id_cart = Tools::getValue('id_cart',$this->context->cart->id);
        $prev_version = version_compare(_PS_VERSION_, '1.6.1.0', '<');
        $sql = 'SELECT DISTINCT p.*,count(accessory.id_product_2) as total_product, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) AS quantity' . ($prev_version? ' ,IFNULL(product_attribute_shop.id_product_attribute, 0)':' ,MAX(product_attribute_shop.id_product_attribute)') . ' id_product_attribute, pl.`description`, pl.`description_short`, pl.`available_now`,
    					pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, IFNULL(image_shop.`id_image`, i.`id_image`) id_image,
    					il.`legend` as legend, m.`name` AS manufacturer_name, cl.`name` AS category_default,
    					DATEDIFF(product_shop.`date_add`, DATE_SUB("' . date('Y-m-d') . ' 00:00:00",
    					INTERVAL ' . (int)$nb_days_new_product . ' DAY)) > 0 AS new, product_shop.price AS orderprice
                FROM `' . _DB_PREFIX_ . 'category_product` cp
                LEFT JOIN `'._DB_PREFIX_.'product` p ON p.`id_product` = cp.`id_product`
                LEFT JOIN '._DB_PREFIX_.'accessory accessory ON (accessory.id_product_2=p.id_product '.($id_product ? ' AND accessory.id_product_1="'.(int)$id_product.'"':'').')
                '.(!$id_product ? ' LEFT JOIN '._DB_PREFIX_.'cart_product cart_product ON (cart_product.id_product= accessory.id_product_1)':'').'
                '.Shop::addSqlAssociation('product', 'p').
                ' LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl ON (product_shop.`id_category_default` = cl.`id_category` AND cl.`id_lang` = ' . (int)$id_lang . Shop::addSqlRestrictionOnLang('cl') . ')'.
                (!$prev_version?
                    'LEFT JOIN '._DB_PREFIX_.'product_attribute pa ON (pa.id_product = p.id_product)'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.default_on=1').'':
                    'LEFT JOIN `'._DB_PREFIX_.'product_attribute_shop` product_attribute_shop ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop='.(int)$this->context->shop->id.')'
                )
                .Product::sqlStock('p', 0, false, $this->context->shop).'
                LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = ' . (int)$id_lang . Shop::addSqlRestrictionOnLang('pl') . ')'.
                (!$prev_version?
                    'LEFT JOIN `' . _DB_PREFIX_ . 'image` i ON (i.`id_product` = p.`id_product`)'. Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover = 1') :
                    'LEFT JOIN `' . _DB_PREFIX_ . 'image_shop` image_shop ON (image_shop.`id_product` = p.`id_product` AND image_shop.id_shop=' . (int)$this->context->shop->id . ')'
                ).'
                LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')	
                LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
                WHERE product_shop.`id_shop` = ' . (int)$this->context->shop->id
                .(!Configuration::get('ETS_CS_OUT_OF_STOCK') ? ' AND stock.quantity>0':'')
                . ($active ? ' AND product_shop.`active` = 1' : '')
                . ($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '')
                .(!$id_product ? 'AND cart_product.id_cart="'.(int)$id_cart.'"':'')
                .(Tools::getValue('id_ets_css_sub_category') ? ' AND cp.id_category="'.(int)Tools::getValue('id_ets_css_sub_category').'"':'')
                . ' GROUP BY p.id_product HAVING total_product>0'
                . ( ($order_by) ? ($order_by != 'rand' ? ' ORDER BY ' . pSQL($order_by) : ' ORDER BY RAND(' . (Configuration::get('ETS_HOMECAT_ENBLE_LOAD_MORE') || Configuration::get('ETS_HOMECAT_PRODUCTS_LAYOUT') == 'carousel' ? (int)$this->getRandomSeed() : '') . ')') : '') . '
                LIMIT 0,'.$count_product ;
        $products = Db::getInstance()->executeS($sql);
        if (!$products) {
            return array();
        }
        if ($order_by == 'orderprice asc') {
            Tools::orderbyPrice($products, 'asc');
        } elseif ($order_by == 'orderprice desc') {
            Tools::orderbyPrice($products, 'desc');
        }
        $products = Product::getProductsProperties($id_lang, $products);
        if ($this->is17) {
            $products = Ets_crosssell::productsForTemplate($products);
        }
        return $products;
    }
    public function getProductPurchasedTogether($id_product=0,$count_product=8,$order_by ='total_cart DESC')
    {
        $active = true;
        $front = true; 
        $nb_days_new_product = Configuration::get('PS_NB_DAYS_NEW_PRODUCT');
        $id_lang = (int)$this->context->language->id;
        if (!Validate::isUnsignedInt($nb_days_new_product)) {
            $nb_days_new_product = 20;
        }
        if(!$id_product)
            $sql_cart = 'SELECT procart.id_product,count(procart.id_product) as total_cart FROM '._DB_PREFIX_.'cart_product procart 
            INNER JOIN (
            	select procart2.id_cart FROM '._DB_PREFIX_.'cart_product procart2 
                INNER JOIN '._DB_PREFIX_.'cart_product procart3 ON (procart2.id_product= procart3.id_product) 
                WHERE procart3.id_cart="'.(int)$this->context->cart->id.'" GROUP BY procart2.id_cart
            ) as procart4 ON (procart4.id_cart= procart.id_cart)
            LEFT JOIN '._DB_PREFIX_.'cart_product procart5 on (procart5.id_product = procart.id_product AND procart5.id_cart="'.(int)$this->context->cart->id.'")
            WHERE procart5.id_cart is null
            group by procart.id_product';
        else
            $sql_cart ='SELECT procart.id_product,count(procart.id_product) as total_cart FROM '._DB_PREFIX_.'cart_product procart
            INNER JOIN '._DB_PREFIX_.'cart_product procart2 on (procart2.id_cart= procart.id_cart)
            WHERE procart2.id_product="'.(int)$id_product.'" AND procart.id_product!="'.(int)$id_product.'"
            GROUP BY procart.id_product';
        $prev_version = version_compare(_PS_VERSION_, '1.6.1.0', '<');
        $sql = 'SELECT DISTINCT p.*,cart_product.total_cart, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) AS quantity' . ($prev_version? ' ,IFNULL(product_attribute_shop.id_product_attribute, 0)':' ,MAX(product_attribute_shop.id_product_attribute)') . ' id_product_attribute, pl.`description`, pl.`description_short`, pl.`available_now`,
    					pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, IFNULL(image_shop.`id_image`, i.`id_image`) id_image,
    					il.`legend` as legend, m.`name` AS manufacturer_name, cl.`name` AS category_default,
    					DATEDIFF(product_shop.`date_add`, DATE_SUB("' . date('Y-m-d') . ' 00:00:00",
    					INTERVAL ' . (int)$nb_days_new_product . ' DAY)) > 0 AS new, product_shop.price AS orderprice
                FROM `' . _DB_PREFIX_ . 'category_product` cp
                LEFT JOIN `'._DB_PREFIX_.'product` p ON p.`id_product` = cp.`id_product`
                LEFT JOIN (
                    '.$sql_cart.'
                ) as cart_product ON (cart_product.id_product= p.id_product)
                '.Shop::addSqlAssociation('product', 'p').
                ' LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl ON (product_shop.`id_category_default` = cl.`id_category` AND cl.`id_lang` = ' . (int)$id_lang . Shop::addSqlRestrictionOnLang('cl') . ')'.
                (!$prev_version?
                    'LEFT JOIN '._DB_PREFIX_.'product_attribute pa ON (pa.id_product = p.id_product)'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.default_on=1').'':
                    'LEFT JOIN `'._DB_PREFIX_.'product_attribute_shop` product_attribute_shop ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop='.(int)$this->context->shop->id.')'
                )
                .Product::sqlStock('p', 0, false, $this->context->shop).'
                LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = ' . (int)$id_lang . Shop::addSqlRestrictionOnLang('pl') . ')'.
                (!$prev_version?
                    'LEFT JOIN `' . _DB_PREFIX_ . 'image` i ON (i.`id_product` = p.`id_product`)'. Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover = 1') :
                    'LEFT JOIN `' . _DB_PREFIX_ . 'image_shop` image_shop ON (image_shop.`id_product` = p.`id_product` AND image_shop.id_shop=' . (int)$this->context->shop->id . ')'
                ).'
                LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')	
                LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
                WHERE total_cart>0 AND product_shop.`id_shop` = ' . (int)$this->context->shop->id
                . ($active ? ' AND product_shop.`active` = 1' : '')
                .(!Configuration::get('ETS_CS_OUT_OF_STOCK') ? ' AND stock.quantity>0':'')
                .($this->context->cart->id ? ' AND product_shop.id_product NOT IN (SELECT id_product FROM '._DB_PREFIX_.'cart_product WHERE id_cart="'.(int)$this->context->cart->id.'")':'')
                . ($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '')
				.(Tools::getValue('id_ets_css_sub_category') ? ' AND cp.id_category="'.(int)Tools::getValue('id_ets_css_sub_category').'"':'')
                . ' GROUP BY p.id_product'
                . ( ($order_by) ? ($order_by != 'rand' ? ' ORDER BY ' . pSQL($order_by) : ' ORDER BY RAND(' . (Configuration::get('ETS_HOMECAT_ENBLE_LOAD_MORE') || Configuration::get('ETS_HOMECAT_PRODUCTS_LAYOUT') == 'carousel' ? (int)$this->getRandomSeed() : '') . ')') : '') . '
                LIMIT 0,'.$count_product ;
        $products = Db::getInstance()->executeS($sql);
        if (!$products) {
            return array();
        }
        if ($order_by == 'orderprice asc') {
            Tools::orderbyPrice($products, 'asc');
        } elseif ($order_by == 'orderprice desc') {
            Tools::orderbyPrice($products, 'desc');
        }
        $products = Product::getProductsProperties($id_lang, $products);
        if ($this->is17) {
            $products = Ets_crosssell::productsForTemplate($products);
        }
        return $products;
    }
    public static function getBestSalesLight($idLang, $pageNumber = 0, $nbProducts = 10, Context $context = null)
    {
        if (!$context) {
            $context = Context::getContext();
        }
        if ($pageNumber < 0) {
            $pageNumber = 0;
        }
        if ($nbProducts < 1) {
            $nbProducts = 10;
        }

        // no group by needed : there's only one attribute with default_on=1 for a given id_product + shop
        // same for image with cover=1
        $sql = '
		SELECT
			p.id_product, IFNULL(product_attribute_shop.id_product_attribute,0) id_product_attribute, pl.`link_rewrite`, pl.`name`, pl.`description_short`, product_shop.`id_category_default`,
			image_shop.`id_image` id_image, il.`legend`,
			ps.`quantity` AS sales, p.`ean13`, p.`upc`, cl.`link_rewrite` AS category, p.show_price, p.available_for_order, IFNULL(stock.quantity, 0) as quantity, p.customizable,
			IFNULL(pa.minimal_quantity, p.minimal_quantity) as minimal_quantity, stock.out_of_stock,
			product_shop.`date_add` > "' . date('Y-m-d', strtotime('-' . (Configuration::get('PS_NB_DAYS_NEW_PRODUCT') ? (int) Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20) . ' DAY')) . '" as new,
			product_shop.`on_sale`, product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity
		FROM `' . _DB_PREFIX_ . 'product_sale` ps
		LEFT JOIN `' . _DB_PREFIX_ . 'product` p ON ps.`id_product` = p.`id_product`
		' . Shop::addSqlAssociation('product', 'p') . '
        '.(
                Tools::getValue('id_ets_css_sub_category')?' LEFT JOIN '._DB_PREFIX_.'category_product cp2 ON (cp2.id_product=p.id_product)':''
        ).'
		LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_shop` product_attribute_shop
			ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop=' . (int) $context->shop->id . ')
		LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute` pa ON (product_attribute_shop.id_product_attribute=pa.id_product_attribute)
		LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl
			ON p.`id_product` = pl.`id_product`
			AND pl.`id_lang` = ' . (int) $idLang . Shop::addSqlRestrictionOnLang('pl') . '
		LEFT JOIN `' . _DB_PREFIX_ . 'image_shop` image_shop
			ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop=' . (int) $context->shop->id . ')
		LEFT JOIN `' . _DB_PREFIX_ . 'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = ' . (int) $idLang . ')
		LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl
			ON cl.`id_category` = product_shop.`id_category_default`
			AND cl.`id_lang` = ' . (int) $idLang . Shop::addSqlRestrictionOnLang('cl') . Product::sqlStock('p', 0);

        $sql .= '
		WHERE product_shop.`active` = 1 
        '. (!Configuration::get('ETS_CS_OUT_OF_STOCK')? ' AND ps.quantity > 0 ' : '')
        .(Tools::getValue('id_ets_css_sub_category') ? ' AND cp2.id_category="'.(int)Tools::getValue('id_ets_css_sub_category').'"':'').'
		AND p.`visibility` != \'none\'';

        if (Group::isFeatureActive()) {
            $groups = FrontController::getCurrentCustomerGroups();
            $sql .= ' AND EXISTS(SELECT 1 FROM `' . _DB_PREFIX_ . 'category_product` cp
				JOIN `' . _DB_PREFIX_ . 'category_group` cg ON (cp.id_category = cg.id_category AND cg.`id_group` ' . (count($groups) ? 'IN (' . implode(',', $groups) . ')' : '=' . (int) Configuration::get('PS_UNIDENTIFIED_GROUP')) . ')
				WHERE cp.`id_product` = p.`id_product`)';
        }

        $sql .= '
		ORDER BY ps.quantity DESC
		LIMIT ' . (int) ($pageNumber * $nbProducts) . ', ' . (int) $nbProducts;

        if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
            return false;
        }
        return $result;
    }
    protected function getBestSellers($nProducts)
    {
        if (Configuration::get('PS_CATALOG_MODE')) {
            return false;
        }
		if (!($result = Ets_crosssell::getBestSalesLight((int)$this->context->language->id, 0, (int)$nProducts)))
			return  array();
        if($this->is17)
            return Ets_crosssell::productsForTemplate($result);                    
		return $result;
    }
    public function getTrendingProducts($nbProducts,$day)
    {
        $date = strtotime("-$day day", strtotime(date('Y-m-d')));
        $active = true;
        $front = true;
        $nb_days_new_product = Configuration::get('PS_NB_DAYS_NEW_PRODUCT');
        $id_lang = (int)$this->context->language->id;
        if (!Validate::isUnsignedInt($nb_days_new_product)) {
            $nb_days_new_product = 20;
        }
        $prev_version = version_compare(_PS_VERSION_, '1.6.1.0', '<');
        $order_by = ' total_sale DESC';
        $sql = 'SELECT DISTINCT p.*,count(DISTINCT od.id_order) as total_sale ,product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) AS quantity' . ($prev_version? ' ,IFNULL(product_attribute_shop.id_product_attribute, 0)':' ,MAX(product_attribute_shop.id_product_attribute)') . ' id_product_attribute, pl.`description`, pl.`description_short`, pl.`available_now`,
    					pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, IFNULL(image_shop.`id_image`, i.`id_image`) id_image,
    					il.`legend` as legend, m.`name` AS manufacturer_name, cl.`name` AS category_default,
    					DATEDIFF(product_shop.`date_add`, DATE_SUB("' . date('Y-m-d') . ' 00:00:00",
    					INTERVAL ' . (int)$nb_days_new_product . ' DAY)) > 0 AS new, product_shop.price AS orderprice
                FROM `' . _DB_PREFIX_ . 'category_product` cp
                INNER JOIN '._DB_PREFIX_.'order_detail od ON (od.product_id = cp.id_product)
                INNER JOIN '._DB_PREFIX_.'orders o ON (od.id_order=o.id_order)
                LEFT JOIN `'._DB_PREFIX_.'product` p ON p.`id_product` = cp.`id_product`
                '.Shop::addSqlAssociation('product', 'p').
                ' LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl ON (product_shop.`id_category_default` = cl.`id_category` AND cl.`id_lang` = ' . (int)$id_lang . Shop::addSqlRestrictionOnLang('cl') . ')'.
                (!$prev_version?
                    'LEFT JOIN '._DB_PREFIX_.'product_attribute pa ON (pa.id_product = p.id_product)'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.default_on=1').'':
                    'LEFT JOIN `'._DB_PREFIX_.'product_attribute_shop` product_attribute_shop ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop='.(int)$this->context->shop->id.')'
                ).
                (
                    Tools::getValue('id_ets_css_sub_category')?' LEFT JOIN '._DB_PREFIX_.'category_product cp2 ON (cp2.id_product=p.id_product)':''
                )
                .Product::sqlStock('p', 0, false, $this->context->shop).'
                LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = ' . (int)$id_lang . Shop::addSqlRestrictionOnLang('pl') . ')'.
                (!$prev_version?
                    'LEFT JOIN `' . _DB_PREFIX_ . 'image` i ON (i.`id_product` = p.`id_product`)'. Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover = 1') :
                    'LEFT JOIN `' . _DB_PREFIX_ . 'image_shop` image_shop ON (image_shop.`id_product` = p.`id_product` AND image_shop.id_shop=' . (int)$this->context->shop->id . ')'
                ).'
                LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')	
                LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
                WHERE o.date_add >="'.pSQL(date('Y-m-d', $date)).'" AND  product_shop.`id_shop` = ' . (int)$this->context->shop->id
                .(!Configuration::get('ETS_CS_OUT_OF_STOCK') ? ' AND stock.quantity>0':'')
                .($active ? ' AND product_shop.`active` = 1' : '')
                .(Tools::getValue('id_ets_css_sub_category') ? ' AND cp2.id_category="'.(int)Tools::getValue('id_ets_css_sub_category').'"':'')
                . ($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '')
                .($this->context->cart->id ? ' AND product_shop.id_product NOT IN (SELECT id_product FROM '._DB_PREFIX_.'cart_product WHERE id_cart="'.(int)$this->context->cart->id.'")':'')
                . ' GROUP BY p.id_product'
                . ( ($order_by) ? ($order_by != 'rand' ? ' ORDER BY ' . pSQL($order_by) : ' ORDER BY RAND(' . (Configuration::get('ETS_HOMECAT_ENBLE_LOAD_MORE') || Configuration::get('ETS_HOMECAT_PRODUCTS_LAYOUT') == 'carousel' ? (int)$this->getRandomSeed() : '') . ')') : '') . '
                LIMIT  0,' . (int)$nbProducts;
        $products = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        if (!$products) {
            return array();
        }
        if($this->is17)
            return self::productsForTemplate($products);
        else
            return Product::getProductsProperties($id_lang, $products);
    }
    public static function getListNewProducts($id_lang, $page_number = 0, $nb_products = 10, $count = false, $order_by = null, $order_way = null, Context $context = null)
    {
        $now = date('Y-m-d') . ' 00:00:00';
        if (!$context) {
            $context = Context::getContext();
        }

        $front = true;
        if (!in_array($context->controller->controller_type, array('front', 'modulefront'))) {
            $front = false;
        }

        if ($page_number < 1) {
            $page_number = 1;
        }
        if ($nb_products < 1) {
            $nb_products = 10;
        }
        if (empty($order_by) || $order_by == 'position') {
            $order_by = 'date_add';
        }
        if (empty($order_way)) {
            $order_way = 'DESC';
        }
        if ($order_by == 'id_product' || $order_by == 'price' || $order_by == 'date_add' || $order_by == 'date_upd') {
            $order_by_prefix = 'product_shop';
        } elseif ($order_by == 'name') {
            $order_by_prefix = 'pl';
        }
        if (!Validate::isOrderBy($order_by) || !Validate::isOrderWay($order_way)) {
            die(Tools::displayError());
        }

        $sql_groups = '';
        if (Group::isFeatureActive()) {
            $groups = FrontController::getCurrentCustomerGroups();
            $sql_groups = ' AND EXISTS(SELECT 1 FROM `' . _DB_PREFIX_ . 'category_product` cp
                JOIN `' . _DB_PREFIX_ . 'category_group` cg ON (cp.id_category = cg.id_category AND cg.`id_group` ' . (count($groups) ? 'IN (' . implode(',', $groups) . ')' : '= ' . (int) Configuration::get('PS_UNIDENTIFIED_GROUP')) . ')
                WHERE cp.`id_product` = p.`id_product`)';
        }

        if (strpos($order_by, '.') > 0) {
            $order_by = explode('.', $order_by);
            $order_by_prefix = $order_by[0];
            $order_by = $order_by[1];
        }

        $nb_days_new_product = (int) Configuration::get('PS_NB_DAYS_NEW_PRODUCT');

        if ($count) {
            $sql = 'SELECT COUNT(p.`id_product`) AS nb
                    FROM `' . _DB_PREFIX_ . 'product` p
                    ' . Shop::addSqlAssociation('product', 'p') . '
                    WHERE product_shop.`active` = 1
                    AND product_shop.`date_add` > "' . date('Y-m-d', strtotime('-' . $nb_days_new_product . ' DAY')) . '"
                    ' . ($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '') . '
                    ' . $sql_groups;

            return (int) Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
        }
        $sql = new DbQuery();
        $sql->select(
            'p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, pl.`description`, pl.`description_short`, pl.`link_rewrite`, pl.`meta_description`,
            pl.`meta_keywords`, pl.`meta_title`, pl.`name`, pl.`available_now`, pl.`available_later`, image_shop.`id_image` id_image, il.`legend`, m.`name` AS manufacturer_name,
            (DATEDIFF(product_shop.`date_add`,
                DATE_SUB(
                    "' . $now . '",
                    INTERVAL ' . $nb_days_new_product . ' DAY
                )
            ) > 0) as new'
        );

        $sql->from('product', 'p');
        $sql->join(Shop::addSqlAssociation('product', 'p'));
        $sql->leftJoin('category_product','cp','cp.id_product=p.id_product');
        $sql->leftJoin(
            'product_lang',
            'pl',
            '
            p.`id_product` = pl.`id_product`
            AND pl.`id_lang` = ' . (int) $id_lang . Shop::addSqlRestrictionOnLang('pl')
        );
        $sql->leftJoin('image_shop', 'image_shop', 'image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop=' . (int) $context->shop->id);
        $sql->leftJoin('image_lang', 'il', 'image_shop.`id_image` = il.`id_image` AND il.`id_lang` = ' . (int) $id_lang);
        $sql->leftJoin('manufacturer', 'm', 'm.`id_manufacturer` = p.`id_manufacturer`');

        $sql->where('product_shop.`active` = 1');
        if($id_category = Tools::getValue('id_ets_css_sub_category'))
            $sql->where('cp.`id_category` = '.(int)$id_category);
        if ($front) {
            $sql->where('product_shop.`visibility` IN ("both", "catalog")');
        }
        $sql->where('product_shop.`date_add` > "' . date('Y-m-d', strtotime('-' . $nb_days_new_product . ' DAY')) . '"');
        if(!Configuration::get('ETS_CS_OUT_OF_STOCK'))
            $sql->where('stock.quantity >0');
        if (Group::isFeatureActive()) {
            $groups = FrontController::getCurrentCustomerGroups();
            $sql->where('EXISTS(SELECT 1 FROM `' . _DB_PREFIX_ . 'category_product` cp
                JOIN `' . _DB_PREFIX_ . 'category_group` cg ON (cp.id_category = cg.id_category AND cg.`id_group` ' . (count($groups) ? 'IN (' . implode(',', $groups) . ')' : '=' . (int) Configuration::get('PS_UNIDENTIFIED_GROUP')) . ')
                WHERE cp.`id_product` = p.`id_product`)');
        }
        if($order_by=='rand')
        {
            $order_way='';
            $order_by = 'RAND()';
        }
        $sql->orderBy((isset($order_by_prefix) ? pSQL($order_by_prefix) . '.' : '') . pSQL($order_by) . ' ' . pSQL($order_way));
        $sql->limit($nb_products, (int) (($page_number - 1) * $nb_products));

        if (Combination::isFeatureActive()) {
            $sql->select('product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity, IFNULL(product_attribute_shop.id_product_attribute,0) id_product_attribute');
            $sql->leftJoin('product_attribute_shop', 'product_attribute_shop', 'p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop=' . (int) $context->shop->id);
        }
        $sql->join(Product::sqlStock('p', 0));
        $sql->groupBy('p.id_product');
        
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        if (!$result) {
            return false;
        }

        if ($order_by == 'price') {
            Tools::orderbyPrice($result, $order_way);
        }
        $products_ids = array();
        foreach ($result as $row) {
            $products_ids[] = $row['id_product'];
        }
        // Thus you can avoid one query per product, because there will be only one query for all the products of the cart
        Product::cacheFrontFeatures($products_ids, $id_lang);

        return Product::getProductsProperties((int) $id_lang, $result);
    }
    protected function getNewProducts($nbProducts=8,$order_sort = 'cp.position asc')
    {
        if (Configuration::get('PS_CATALOG_MODE')) {
            return false;
        }
        if($order_sort)
        {
            $order_sort = explode(' ',$order_sort);
            $order_by = $order_sort[0];
            if(isset($order_sort[1]))
                $order_way = $order_sort[1];
            else
                $order_way = null;
        }
        else
        {
            $order_way = null;
            $order_by = null;
        }
		$newProducts = Ets_crosssell::getListNewProducts((int) $this->context->language->id, 0, (int)$nbProducts,false,$order_by,$order_way);
        if($this->is17)
            return Ets_crosssell::productsForTemplate($newProducts);
		return $newProducts;
    }
    private function getSpecialProducts($nbProducts,$order_sort = 'cp.position asc')
    {
        if($order_sort)
        {
            $order_sort = explode(' ',$order_sort);
            $order_by = $order_sort[0];
            if(isset($order_sort[1]))
                $order_way = $order_sort[1];
            else
                $order_way = null;
        }
        else
        {
            $order_way = null;
            $order_by = null;
        }
        if($order_by=='rand')
        {
            $order_way = null;
            $order_by = null;
        }
        $products = Ets_crosssell::getPricesDrop(
            (int)Context::getContext()->language->id,
            0,
            (int)$nbProducts,false,$order_by,$order_way
        );
        if($this->is17)
        {
            return Ets_crosssell::productsForTemplate($products);
        }
        else
            return $products;
    }
     public static function getPricesDrop(
        $id_lang,
        $page_number = 0,
        $nb_products = 10,
        $count = false,
        $order_by = null,
        $order_way = null,
        $beginning = false,
        $ending = false,
        Context $context = null
    ) {
        if (!Validate::isBool($count)) {
            die(Tools::displayError());
        }

        if (!$context) {
            $context = Context::getContext();
        }
        if ($page_number < 1) {
            $page_number = 1;
        }
        if ($nb_products < 1) {
            $nb_products = 10;
        }
        if (empty($order_by) || $order_by == 'position') {
            $order_by = 'price';
        }
        if (empty($order_way)) {
            $order_way = 'DESC';
        }
        if ($order_by == 'id_product' || $order_by == 'price' || $order_by == 'date_add' || $order_by == 'date_upd') {
            $order_by_prefix = 'product_shop';
        } elseif ($order_by == 'name') {
            $order_by_prefix = 'pl';
        }
        if (!Validate::isOrderBy($order_by) || !Validate::isOrderWay($order_way)) {
            die(Tools::displayError());
        }
        $current_date = date('Y-m-d H:i:00');
        $ids_product = self::_getProductIdByDate((!$beginning ? $current_date : $beginning), (!$ending ? $current_date : $ending), $context);

        $tab_id_product = array();
        foreach ($ids_product as $product) {
            if (is_array($product)) {
                $tab_id_product[] = (int) $product['id_product'];
            } else {
                $tab_id_product[] = (int) $product;
            }
        }

        $front = true;
        if (!in_array($context->controller->controller_type, array('front', 'modulefront'))) {
            $front = false;
        }

        $sql_groups = '';
        if (Group::isFeatureActive()) {
            $groups = FrontController::getCurrentCustomerGroups();
            $sql_groups = ' AND EXISTS(SELECT 1 FROM `' . _DB_PREFIX_ . 'category_product` cp
                JOIN `' . _DB_PREFIX_ . 'category_group` cg ON (cp.id_category = cg.id_category AND cg.`id_group` ' . (count($groups) ? 'IN (' . implode(',', $groups) . ')' : '=' . (int) Configuration::get('PS_UNIDENTIFIED_GROUP')) . ')
                WHERE cp.`id_product` = p.`id_product`)';
        }

        if ($count) {
            return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
            SELECT COUNT(DISTINCT p.`id_product`)
            FROM `' . _DB_PREFIX_ . 'product` p
            ' . Shop::addSqlAssociation('product', 'p') . '
            WHERE product_shop.`active` = 1
            AND product_shop.`show_price` = 1
            ' . ($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '') . '
            ' . ((!$beginning && !$ending) ? 'AND p.`id_product` IN(' . ((is_array($tab_id_product) && count($tab_id_product)) ? implode(', ', $tab_id_product) : 0) . ')' : '') . '
            ' . $sql_groups);
        }

        if (strpos($order_by, '.') > 0) {
            $order_by = explode('.', $order_by);
            $order_by = pSQL($order_by[0]) . '.`' . pSQL($order_by[1]) . '`';
        }

        $sql = '
        SELECT
            p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, pl.`description`, pl.`description_short`, pl.`available_now`, pl.`available_later`,
            IFNULL(product_attribute_shop.id_product_attribute, 0) id_product_attribute,
            pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`,
            pl.`name`, image_shop.`id_image` id_image, il.`legend`, m.`name` AS manufacturer_name,
            DATEDIFF(
                p.`date_add`,
                DATE_SUB(
                    "' . date('Y-m-d') . ' 00:00:00",
                    INTERVAL ' . (Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20) . ' DAY
                )
            ) > 0 AS new
        FROM `' . _DB_PREFIX_ . 'product` p
        ' . Shop::addSqlAssociation('product', 'p') . '
        LEFT JOIN '._DB_PREFIX_.'category_product cp ON (cp.id_product=p.id_product)
        LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_shop` product_attribute_shop
            ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop=' . (int) $context->shop->id . ')
        ' . Product::sqlStock('p', 0, false, $context->shop) . '
        LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (
            p.`id_product` = pl.`id_product`
            AND pl.`id_lang` = ' . (int) $id_lang . Shop::addSqlRestrictionOnLang('pl') . '
        )
        LEFT JOIN `' . _DB_PREFIX_ . 'image_shop` image_shop
            ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop=' . (int) $context->shop->id . ')
        LEFT JOIN `' . _DB_PREFIX_ . 'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = ' . (int) $id_lang . ')
        LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
        WHERE product_shop.`active` = 1
        AND product_shop.`show_price` = 1'
        .(!Configuration::get('ETS_CS_OUT_OF_STOCK') ? ' AND stock.quantity>0':'')
        .(Tools::getValue('id_ets_css_sub_category') ? ' AND cp.id_category="'.(int)Tools::getValue('id_ets_css_sub_category').'"':'').'
        ' . ($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '') . '
        ' . ((!$beginning && !$ending) ? ' AND p.`id_product` IN (' . ((is_array($tab_id_product) && count($tab_id_product)) ? implode(', ', $tab_id_product) : 0) . ')' : '') . '
        ' . $sql_groups . '
        GROUP BY p.id_product
        ORDER BY ' . (isset($order_by_prefix) ? pSQL($order_by_prefix) . '.' : '') . pSQL($order_by) . ' ' . pSQL($order_way) . '
        LIMIT ' . (int) (($page_number - 1) * $nb_products) . ', ' . (int) $nb_products;
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        
        if (!$result) {
            return false;
        }

        if ($order_by == 'price') {
            Tools::orderbyPrice($result, $order_way);
        }

        return Product::getProductsProperties($id_lang, $result);
    }
    protected static function _getProductIdByDate($beginning, $ending, Context $context = null, $with_combination = false)
    {
        if (!$context) {
            $context = Context::getContext();
        }

        $id_address = $context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')};
        $ids = Address::getCountryAndState($id_address);
        $id_country = $ids['id_country'] ? (int) $ids['id_country'] : (int) Configuration::get('PS_COUNTRY_DEFAULT');

        return SpecificPrice::getProductIdByDate(
            $context->shop->id,
            $context->currency->id,
            $id_country,
            $context->customer->id_default_group,
            $beginning,
            $ending,
            0,
            $with_combination
        );
    }
    public function getTopRatedProducts($nbProduct,$order_by ='total_grade DESC')
    {
        if(Module::isInstalled('ets_productcomments') || Module::isInstalled('productcomments'))
        {
            $active = true;
            $front = true;
            $nb_days_new_product = Configuration::get('PS_NB_DAYS_NEW_PRODUCT');
            $id_lang = (int)$this->context->language->id;
            if (!Validate::isUnsignedInt($nb_days_new_product)) {
                $nb_days_new_product = 20;
            }
            $prev_version = version_compare(_PS_VERSION_, '1.6.1.0', '<');
            $sql = 'SELECT DISTINCT p.*,AVG(comment.grade) as total_grade, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) AS quantity' . ($prev_version? ' ,IFNULL(product_attribute_shop.id_product_attribute, 0)':' ,MAX(product_attribute_shop.id_product_attribute)') . ' id_product_attribute, pl.`description`, pl.`description_short`, pl.`available_now`,
        					pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, IFNULL(image_shop.`id_image`, i.`id_image`) id_image,
        					il.`legend` as legend, m.`name` AS manufacturer_name, cl.`name` AS category_default,
        					DATEDIFF(product_shop.`date_add`, DATE_SUB("' . date('Y-m-d') . ' 00:00:00",
        					INTERVAL ' . (int)$nb_days_new_product . ' DAY)) > 0 AS new, product_shop.price AS orderprice
                    FROM `' . _DB_PREFIX_ . 'category_product` cp
                    LEFT JOIN `'._DB_PREFIX_.'product` p ON p.`id_product` = cp.`id_product`
                    '.(Module::isInstalled('ets_productcomments') ? 'LEFT JOIN `'._DB_PREFIX_.'ets_pc_product_comment` comment ON (comment.id_product = p.id_product)':' LEFT JOIN `'._DB_PREFIX_.'product_comment` comment ON (comment.id_product = p.id_product)').'
                    '.Shop::addSqlAssociation('product', 'p').
                    ' LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl ON (product_shop.`id_category_default` = cl.`id_category` AND cl.`id_lang` = ' . (int)$id_lang . Shop::addSqlRestrictionOnLang('cl') . ')'.
                    (!$prev_version?
                        'LEFT JOIN '._DB_PREFIX_.'product_attribute pa ON (pa.id_product = p.id_product)'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.default_on=1').'':
                        'LEFT JOIN `'._DB_PREFIX_.'product_attribute_shop` product_attribute_shop ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop='.(int)$this->context->shop->id.')'
                    )
                    .Product::sqlStock('p', 0, false, $this->context->shop).'
                    LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = ' . (int)$id_lang . Shop::addSqlRestrictionOnLang('pl') . ')'.
                    (!$prev_version?
                        'LEFT JOIN `' . _DB_PREFIX_ . 'image` i ON (i.`id_product` = p.`id_product`)'. Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover = 1') :
                        'LEFT JOIN `' . _DB_PREFIX_ . 'image_shop` image_shop ON (image_shop.`id_product` = p.`id_product` AND image_shop.id_shop=' . (int)$this->context->shop->id . ')'
                    ).'
                    LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')	
                    LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
                    WHERE product_shop.`id_shop` = ' . (int)$this->context->shop->id
                    .(!Configuration::get('ETS_CS_OUT_OF_STOCK') ? ' AND stock.quantity>0':'')
                    . ($active ? ' AND product_shop.`active` = 1' : '')
                    .(Tools::getValue('id_ets_css_sub_category') ? ' AND cp.id_category="'.(int)Tools::getValue('id_ets_css_sub_category').'"':'')
                    . ($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '')
                    . ' GROUP BY p.id_product HAVING total_grade >0'
                    . ( ($order_by) ? ($order_by != 'rand' ? ' ORDER BY ' . pSQL($order_by) : ' ORDER BY RAND(' . (Configuration::get('ETS_HOMECAT_ENBLE_LOAD_MORE') || Configuration::get('ETS_HOMECAT_PRODUCTS_LAYOUT') == 'carousel' ? (int)$this->getRandomSeed() : '') . ')') : '') . '
                    LIMIT 0,'.$nbProduct ;
            $products = Db::getInstance()->executeS($sql);
            if (!$products) {
                return array();
            }
            $products = Product::getProductsProperties($id_lang, $products);
            if ($this->is17) {
                $products = Ets_crosssell::productsForTemplate($products);
            }
            return $products;
        }
        else
            return array();
    }
    public function createCache($html,$params)
    {
        if(!Configuration::get('ETS_CS_ENABLE_CACHE'))
            return false;
        if(!is_dir(_ETS_CROSSSELL_CACHE_DIR_))
        {
            @mkdir(_ETS_CROSSSELL_CACHE_DIR_,0777,true);
            if ( @file_exists(dirname(__file__).'/index.php')){
                @copy(dirname(__file__).'/index.php', _ETS_CROSSSELL_CACHE_DIR_.'index.php');
            }
        }

        $str = '';
        if($params)
        {
            foreach($params as $key=>$value)
            {
                if(!is_array($value))
                    $str .='&'.$key.'='.$value;
            }
        }
        $str .= '&id_lang='.$this->context->language->id;
        $str .= '&ets_currency='.($this->context->cookie->id_currency ? $this->context->cookie->id_currency : Configuration::get('PS_CURRENCY_DEFAULT'));
        $id_customer = (isset($this->context->customer->id)) ? (int)($this->context->customer->id) : 0;
        $id_group = null;
        if ($id_customer) {
            $id_group = Customer::getDefaultGroupId((int)$id_customer);
        }
        if (!$id_group) {
            $id_group = (int)Group::getCurrent()->id;
        } 
        $str .= '&ets_group='.(int)$id_group; 
        $id_country =isset($this->context->cookie->iso_code_country) && $this->context->cookie->iso_code_country && Validate::isLanguageIsoCode($this->context->cookie->iso_code_country) ?
                    (int) Country::getByIso(Tools::strtoupper($this->context->cookie->iso_code_country)) : (int) Tools::getCountry();
        $str .='&ets_country='.($id_country ? $id_country : (int)$this->context->country->id);
        if(isset($this->context->cookie->id_cart) && $this->context->cookie->id_cart)
            $str .='&hascart=1';
        $str .='&id_category='.(int)Tools::getValue('id_ets_css_sub_category');
        file_put_contents(_ETS_CROSSSELL_CACHE_DIR_.md5($str).'.'.time(),$html);    
    }
    public function clearCache()
    {
        if(is_dir(_ETS_CROSSSELL_CACHE_DIR_) && ($files = glob(_ETS_CROSSSELL_CACHE_DIR_.'*')))
        {
            foreach ($files as $filename) {
                if($filename!=_ETS_CROSSSELL_CACHE_DIR_.'index.php')
                    @unlink($filename);
                }
        }
        if((int)Configuration::get('ETS_SPEED_ENABLE_PAGE_CACHE') && Module::isInstalled('ets_superspeed') && Module::isEnabled('ets_superspeed') && class_exists('Ets_ss_class_cache'))
        {
            $cacheObjSuperSpeed = new Ets_ss_class_cache();
            if(method_exists($cacheObjSuperSpeed,'deleteCache'))
                $cacheObjSuperSpeed->deleteCache('index');
        }
        if((int)Configuration::get('ETS_SPEED_ENABLE_PAGE_CACHE') && Module::isInstalled('ets_pagecache') && Module::isEnabled('ets_pagecache') && class_exists('Ets_pagecache_class_cache'))
        {
            $cacheObjPageCache = new Ets_pagecache_class_cache();
            if(method_exists($cacheObjPageCache,'deleteCache'))
                $cacheObjPageCache->deleteCache('index');
        }
        return true;
    }
    public function getCache($params){
	    if(!Configuration::get('ETS_CS_ENABLE_CACHE'))
            return false;
        if ( !$params )
            return false;
        $str = '';
        if($params)
        {
            foreach($params as $key=>$value)
            {
                if(!is_array($value))
                    $str .='&'.$key.'='.$value;
            }
        }
        $str .= '&id_lang='.$this->context->language->id;
        $str .= '&ets_currency='.($this->context->cookie->id_currency ? $this->context->cookie->id_currency : Configuration::get('PS_CURRENCY_DEFAULT'));
        $id_customer = (isset($this->context->customer->id)) ? (int)($this->context->customer->id) : 0;
        $id_group = null;
        if ($id_customer) {
            $id_group = Customer::getDefaultGroupId((int)$id_customer);
        }
        if (!$id_group) {
            $id_group = (int)Group::getCurrent()->id;
        } 
        $str .= '&ets_group='.(int)$id_group; 
        $id_country =isset($this->context->cookie->iso_code_country) && $this->context->cookie->iso_code_country && Validate::isLanguageIsoCode($this->context->cookie->iso_code_country) ?
                    (int) Country::getByIso(Tools::strtoupper($this->context->cookie->iso_code_country)) : (int) Tools::getCountry();
        $str .='&ets_country='.($id_country ? $id_country : (int)$this->context->country->id);
        if(isset($this->context->cookie->id_cart) && $this->context->cookie->id_cart)
            $str .='&hascart=1';
        $str .='&id_category='.(int)Tools::getValue('id_ets_css_sub_category');
        $url_file = _ETS_CROSSSELL_CACHE_DIR_.md5($str);
        $cacheLifeTime = (float)Configuration::get('ETS_CS_CACHE_LIFETIME');
        if($files = @glob($url_file.'.*'))
            foreach ($files as $file) {
                if(file_exists($file)){
                    $file_extends = Tools::substr(strrchr($file, '.'), 1);
                    if ( is_numeric( $file_extends )){
                        if ( (time() - (int)$file_extends <= $cacheLifeTime*60*60) || !$cacheLifeTime){
                            return Tools::file_get_contents($file);
                        }else{
                            unlink($file);
                        }
                    }
                }
            }
        return false;
    }
    public function displayProductList($params)
    {
        if($id_categories = Configuration::get('ETS_CS_CATEGORY_SUB'))
        {
              $id_categories = explode(',',$id_categories);
              $sub_categories = Db::getInstance()->executeS('SELECT * FROM '._DB_PREFIX_.'category c
              INNER JOIN '._DB_PREFIX_.'category_shop cs ON (c.id_category=cs.id_category AND cs.id_shop="'.(int)$this->context->shop->id.'")
              LEFT JOIN '._DB_PREFIX_.'category_lang cl ON (c.id_category=cl.id_category)
              WHERE c.active=1 AND cl.id_lang="'.(int)$this->context->language->id.'" AND c.id_category IN ('.implode(',',array_map('intval',$id_categories)).') GROUP BY c.id_category');
        }
        else
            $sub_categories=array();
        $this->smarty->assign(array(
            'products' => $params['products'],
            'tab' => $params['tab'],
            'name_page' => $params['name_page'],
            'ets_per_row_desktop' => (int)Configuration::get('ETS_CS_'.Tools::strtoupper($params['name_page']).'_ROW_DESKTOP'),
            'ets_per_row_tablet' => (int)Configuration::get('ETS_CS_'.Tools::strtoupper($params['name_page']).'_ROW_TABLET'),
            'ets_per_row_mobile' => (int)Configuration::get('ETS_CS_'.Tools::strtoupper($params['name_page']).'_ROW_MOBILE'),
            'layout_mode' => $params['layout_mode'],
            'sub_categories' =>Configuration::get('ETS_CS_'.Tools::strtoupper($params['name_page']).'_'.Tools::strtoupper($params['tab']).'_DISPLAY_SUB_CATEGORY') ? $sub_categories:array(),
            'id_ets_css_sub_category' => Tools::getValue('id_ets_css_sub_category'),
            'id_product_page' => Tools::getValue('id_product'),
            'sort_by' => isset($params['sort_by']) && $params['sort_by'] ? $params['sort_by']:'',
            'sort_options' =>isset($params['sort_options']) && $params['sort_options'] ? $params['sort_options'] :false,
        ));
        if($this->is17)
        {
            $this->smarty->assign('page_name',Tools::getValue('controller'));
        }
        return  $this->display(__FILE__, 'product_list' . ($this->is17 ? '_17' : '') . '.tpl');
    }
    public function getRandomSeed()
    {
        if ((int)Tools::getValue('ets_homecat_order_seed') > 0 && (int)Tools::getValue('ets_homecat_order_seed') <= 10000)
            return (int)Tools::getValue('ets_homecat_order_seed');
        elseif ((int)$this->context->cookie->ets_homecat_order_seed > 0 && (int)$this->context->cookie->ets_homecat_order_seed <= 10000)
            return (int)$this->context->cookie->ets_homecat_order_seed;
        else
            return 1;
    }
    public function hookDisplayRecommendedProducts($params)
    {
        $name_page = $params['name_page'];
        if(isset($this->_configs[$name_page]))
        {
            $count_product= (int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_COUNT_PRODUCT') ? (int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_COUNT_PRODUCT') :8;
            $products = $this->getRecommendProducts($count_product);
            $params = array(
                'products' => $products,
                'tab' => 'recommendedproducts',
                'name_page' => $name_page, 
                'layout_mode' => Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') ? Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') :'list',
            );
            return $this->displayProductList($params);
        }
    }
    public function hookDisplayViewedProducts($params)
    {
        $name_page = $params['name_page'];
        if(isset($this->_configs[$name_page]))
        {
            $count_product= (int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_COUNT_PRODUCT') ? (int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_COUNT_PRODUCT') :8;
            $productsViewed = (isset($this->context->cookie->viewed) && !empty($this->context->cookie->viewed)) ? array_slice(array_reverse(explode(',', $this->context->cookie->viewed)), 0) : array();
            if($productsViewed || Tools::isSubmit('ajax'))
            {
                $enable_sort_by =(int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_VIEWEDPRODUCTS_ENABLE_SORT_BY');
                $sort_by_default = Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_VIEWEDPRODUCTS_SORT_BY_DEFAULT');
                $products = $this->getProducts(false,1,$count_product,isset($params['order_by']) && $params['order_by'] ? $params['order_by']: ($sort_by_default ? $sort_by_default :'cp.position') ,$productsViewed);
                $params = array(
                    'products' => $products,
                    'tab' => 'viewedproducts',
                    'name_page' => $name_page, 
                    'sort_by' => Tools::getValue('sort_by',$sort_by_default),
                    'sort_options' =>$enable_sort_by ? $this->_sort_options:false,
                    'layout_mode' => Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') ? Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') :'list',
                );
                return $this->displayProductList($params);
            }
            else
            {
                return false;
            }
        }
        
    }
    public function hookDisplayFeaturedProducts($params)
    {
        $name_page = $params['name_page'];
        $enable_sort_by =(int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_FEATUREDPRODUCTS_ENABLE_SORT_BY');
        $sort_by_default = Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_FEATUREDPRODUCTS_SORT_BY_DEFAULT');        
        $count_product= (int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_COUNT_PRODUCT') ? (int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_COUNT_PRODUCT') :8;
        $cacheparams = array(
            'tab' => 'featuredproducts',
            'name_page' => $name_page, 
            'sort_by' => Tools::getValue('sort_by',$sort_by_default),
            'sort_options' =>$enable_sort_by ? $this->_sort_options:false,
            'layout_mode' => Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') ? Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') :'list',
        );
        if($html = $this->getCache($cacheparams))
            return $html;
        if(isset($this->_configs[$name_page]))
        {
            if($id_category = Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_FEATUREDPRODUCTS_ID_CATEGORY'))
            {
                $products = $this->getProducts($id_category,1,$count_product,isset($params['order_by']) && $params['order_by'] ? $params['order_by']: ($sort_by_default? $sort_by_default: 'cp.position'));
                $params = array(
                    'products' => $products,
                    'tab' => 'featuredproducts',
                    'name_page' => $name_page, 
                    'sort_by' => Tools::getValue('sort_by',$sort_by_default),
                    'sort_options' =>$enable_sort_by ? $this->_sort_options:false,
                    'layout_mode' => Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') ? Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') :'list',
                );
                $html = $this->displayProductList($params);
                $this->createCache($html,$cacheparams);
                return $html;
            }
            else{
                $this->smarty->assign(
                    array(
                        'tab' => 'featuredproducts',
                        'name_page' => $name_page, 
                        'layout_mode' => Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') ? Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') :'list', 
                    )
                );
                $html = $this->display(__FILE__,'no_product.tpl');
                $this->createCache($html,$cacheparams);
                return $html;
            }
        }
    }
    public function hookDisplayPopularProducts($params)
    {
        $name_page = $params['name_page'];
        $count_product= (int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_COUNT_PRODUCT') ? (int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_COUNT_PRODUCT') :8;
        $id_category = (int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_POPULARPRODUCTS_ID_CATEGORY') ? (int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_POPULARPRODUCTS_ID_CATEGORY') :2;
        $enable_sort_by =(int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_POPULARPRODUCTS_ENABLE_SORT_BY');
        $sort_by_default = Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_POPULARPRODUCTS_SORT_BY_DEFAULT');
        $cacheparams = array(
            'tab' => 'popularproducts',
            'name_page' => $name_page, 
            'sort_by' => Tools::getValue('sort_by',$sort_by_default),
            'sort_options' => $enable_sort_by ? $this->_sort_options:false,
            'layout_mode' => Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') ? Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') :'list',
        );
        if($html = $this->getCache($cacheparams))
            return $html;
        
        if(isset($this->_configs[$name_page]))
        {
            $products = $this->getProducts($id_category,1,$count_product,isset($params['order_by']) && $params['order_by']? $params['order_by']: ($sort_by_default ? $sort_by_default :'cp.position'));
            $params = array(
            'products'=>$products,
            'tab' => 'popularproducts',
            'name_page' => $name_page, 
            'sort_by' => Tools::getValue('sort_by',$sort_by_default),
            'sort_options' => $enable_sort_by ? $this->_sort_options:false,
            'layout_mode' => Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') ? Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') :'list',
            );
            $html = $this->displayProductList($params);
            $this->createCache($html,$cacheparams);
            return $html;
        }
    }
    public function hookDisplayMostViewedProducts($params)
    {
        $name_page = $params['name_page'];
        if(isset($this->_configs[$name_page]))
        {
            $count_product= (int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_COUNT_PRODUCT') ? (int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_COUNT_PRODUCT') :8;
            $products = $this->getMostViewedProducts($count_product);
            $params = array(
                'products' => $products,
                'tab' => 'mostviewedproducts',
                'name_page' => $name_page, 
                'layout_mode' => Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') ? Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') :'list',
            );
            return $this->displayProductList($params);
        }
        
    }
    public function hookDisplayYouMightAlsoLike($params)
    {
        $name_page = $params['name_page'];
        $count_product= (int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_COUNT_PRODUCT') ? (int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_COUNT_PRODUCT') :8;
        if(Tools::getValue('id_product'))
            $id_product = Tools::getValue('id_product');
        else
            $id_product =0;
        if($id_product)
        {
            $cacheparams = array(
                'id_product' => $id_product,
                'tab' => 'youmightalsolike',
                'name_page' => $name_page, 
                'sort_by' => Tools::getValue('sort_by'),
                'sort_options' =>false,// $this->_sort_options,
                'layout_mode' => Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') ? Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') :'list',
            );
            if($html = $this->getCache($cacheparams))
                return $html;
        }
        if(isset($this->_configs[$name_page]))
        {
            $products = $this->getProductYouMightAlsoLike($id_product,$count_product,isset($params['order_by']) && $params['order_by']? $params['order_by']:'total_product desc');
            $params = array(
                'products' => $products,
                'tab' => 'youmightalsolike',
                'name_page' => $name_page, 
                'sort_by' => Tools::getValue('sort_by'),
                'sort_options' =>false,// $this->_sort_options,
                'layout_mode' => Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') ? Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') :'list',
            );
            $html = $this->displayProductList($params);
            if($id_product)
                $this->createCache($html,$cacheparams);
            return $html;
        }  
    }
    public function hookDisplayBestSelling($params)
    {
        $name_page = $params['name_page'];
        if(isset($this->_configs[$name_page]))
        {
            $count_product= (int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_COUNT_PRODUCT') ? (int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_COUNT_PRODUCT') :8;
            $products = $this->getBestSellers($count_product);
            $params = array(
                'products' => $products,
                'tab' => 'bestselling',
                'name_page' => $name_page, 
                'layout_mode' => Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') ? Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') :'list',
            );
            return $this->displayProductList($params);
        }
        
    }
    public function hookDisplayTrendingProducts($params)
    {
        $name_page = $params['name_page'];
        if(isset($this->_configs[$name_page]))
        {
            $count_product= (int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_COUNT_PRODUCT') ? (int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_COUNT_PRODUCT') :8;
            $day = (int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_TRENDINGPRODUCTS_DAY');
            $products = $this->getTrendingProducts($count_product,$day);
            $params = array(
                'products' => $products,
                'tab' => 'trendingproducts',
                'name_page' => $name_page, 
                'layout_mode' => Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') ? Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') :'list',
            );
            return $this->displayProductList($params);
        }
    }
    public function hookDisplayNewProducts($params)
    {
        $name_page = $params['name_page'];
        $count_product= (int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_COUNT_PRODUCT') ? (int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_COUNT_PRODUCT') :8;
        $enable_sort_by =(int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_NEWPRODUCTS_ENABLE_SORT_BY');
        $sort_by_default = Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_NEWPRODUCTS_SORT_BY_DEFAULT');        
        $cacheparams = array(
            'tab' => 'newproducts',
            'name_page' => $name_page, 
            'sort_by' => Tools::getValue('sort_by',$sort_by_default),
            'sort_options' => $enable_sort_by ? $this->_sort_options:false,
            'layout_mode' => Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') ? Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') :'list',
        );
        if($html = $this->getCache($cacheparams))
            return $html;                
        if(isset($this->_configs[$name_page]))
        {
            $products = $this->getNewProducts($count_product,isset($params['order_by']) && $params['order_by'] ? $params['order_by']: ($sort_by_default ? $sort_by_default : 'cp.position'));
            $params = array(
                'products' => $products,
                'tab' => 'newproducts',
                'name_page' => $name_page, 
                'sort_by' => Tools::getValue('sort_by',$sort_by_default),
                'sort_options' => $enable_sort_by ? $this->_sort_options:false,
                'layout_mode' => Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') ? Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') :'list',
            );
            $html = $this->displayProductList($params);
            $this->createCache($html,$cacheparams);
            return $html;
        }
    }
    public function hookDisplaySpecialProducts($params)
    {
        $name_page = $params['name_page'];
        $count_product= (int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_COUNT_PRODUCT') ? (int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_COUNT_PRODUCT') :8;
        $enable_sort_by =(int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_SPECIALPRODUCTS_ENABLE_SORT_BY');
        $sort_by_default = Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_SPECIALPRODUCTS_SORT_BY_DEFAULT');
        $cacheparams = array(
            'tab' => 'specialproducts',
            'name_page' => $name_page, 
            'sort_by' => Tools::getValue('sort_by',$sort_by_default),
            'sort_options' => $enable_sort_by ?  $this->_sort_options :false,
            'layout_mode' => Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') ? Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') :'list',
        );
        if($html = $this->getCache($cacheparams))
            return $html;
        if(isset($this->_configs[$name_page]))
        {
            $products = $this->getSpecialProducts($count_product,isset($params['order_by']) && $params['order_by'] ? $params['order_by']: ($sort_by_default ? $sort_by_default : 'cp.position'));
            $params = array(
                'products' =>$products,
                'tab' => 'specialproducts',
                'name_page' => $name_page, 
                'sort_by' => Tools::getValue('sort_by',$sort_by_default),
                'sort_options' => $enable_sort_by ?  $this->_sort_options :false,
                'layout_mode' => Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') ? Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') :'list',
            );
            $html = $this->displayProductList($params);
            $this->createCache($html,$cacheparams);
            return $html;
        }
    }
    public function hookDisplayTopratedProducts($params)
    {
        $name_page = $params['name_page'];
        if(isset($this->_configs[$name_page]))
        {
            $count_product= (int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_COUNT_PRODUCT') ? (int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_COUNT_PRODUCT') :8;
            $products = $this->getTopRatedProducts($count_product);
            $params = array(
                'products' => $products,
                'tab' => 'topratedproducts',
                'name_page' => $name_page, 
                'layout_mode' => Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') ? Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') :'list',
            );
            return $this->displayProductList($params);
        }
    }
    public function hookDisplayPurchasedTogether($params)
    {
        $name_page = $params['name_page'];
        if(isset($this->_configs[$name_page]))
        {
            $id_product= isset($params['id_product']) ? (int)$params['id_product'] :0;
            $count_product= (int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_COUNT_PRODUCT') ? (int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_COUNT_PRODUCT') :8;
            $products = $this->getProductPurchasedTogether($id_product,$count_product);
            $params = array(
                'products' => $products,
                'tab' => 'purchasedtogether',
                'name_page' => $name_page, 
                'layout_mode' => Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') ? Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') :'list',
            );
            return $this->displayProductList($params);
        }
    }
    public function hookDisplayProductInTheSameCategories($params)
    {
        $name_page = $params['name_page'];
        $count_product= (int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_COUNT_PRODUCT') ? (int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_COUNT_PRODUCT') :8;
        $enable_sort_by =(int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_PRODUCTINTHESAMECATEGORIES_ENABLE_SORT_BY');
        $sort_by_default = Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_PRODUCTINTHESAMECATEGORIES_SORT_BY_DEFAULT');
        $id_product= isset($params['id_product']) ? (int)$params['id_product'] :0;
        if(!$id_product)
            return false;
        $cacheparams = array(
            'id_product' => $id_product,
            'tab' => 'productinthesamecategories',
            'name_page' => $name_page, 
            'sort_by' => Tools::getValue('sort_by',$sort_by_default),
            'sort_options' => $enable_sort_by ? $this->_sort_options :false,
            'layout_mode' => Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') ? Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') :'list',
        );
        if($html = $this->getCache($cacheparams))
            return $html;
        if(isset($this->_configs[$name_page]))
        {
            if($id_product)
            {
                $product = new Product($id_product);
                $id_category = $product->id_category_default;
                $products = $this->getProducts($id_category,0,$count_product, isset($params['order_by']) ? $params['order_by']: ($sort_by_default ? $sort_by_default : 'cp.position'),false,array($id_product));
                $params = array(
                    'products' => $products,
                    'tab' => 'productinthesamecategories',
                    'name_page' => $name_page, 
                    'sort_by' => Tools::getValue('sort_by',$sort_by_default),
                    'sort_options' => $enable_sort_by ? $this->_sort_options :false,
                    'layout_mode' => Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') ? Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') :'list',
                );
                $html = $this->displayProductList($params);
                $this->createCache($html,$cacheparams);
                return $html;
            }
        }
    }
    public function hookDisplayProductInTheSameManufacture($params)
    {
        $name_page = $params['name_page'];
        $id_product= isset($params['id_product']) ? (int)$params['id_product'] :0;
        if(!$id_product)
            return false;
        $count_product= (int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_COUNT_PRODUCT') ? (int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_COUNT_PRODUCT') :8;
        $enable_sort_by =(int)Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_PRODUCTINTHESAMEMANUFACTURE_ENABLE_SORT_BY');
        $sort_by_default = Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_PRODUCTINTHESAMEMANUFACTURE_SORT_BY_DEFAULT');
        $cacheparams = array(
            'id_product' => $id_product,
            'tab' => 'productinthesamemanufacture',
            'name_page' => $name_page, 
            'sort_by' => Tools::getValue('sort_by',$sort_by_default),
            'sort_options' => $enable_sort_by ? $this->_sort_options:false,
            'layout_mode' => Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') ? Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') :'list',
        );
        if($html = $this->getCache($cacheparams))
            return $html;
        if(isset($this->_configs[$name_page]))
        {
            if($id_product)
            {
                $product = new Product($id_product);
                $id_manufacturer = $product->id_manufacturer;
                $products = $this->getProducts(0,0,$count_product,isset($params['order_by']) ? $params['order_by']:($sort_by_default ? $sort_by_default : 'cp.position'),false,array($id_product),false,false,$id_manufacturer);
                $params = array(
                    'products' => $products,
                    'tab' => 'productinthesamemanufacture',
                    'name_page' => $name_page, 
                    'sort_by' => Tools::getValue('sort_by',$sort_by_default),
                    'sort_options' => $enable_sort_by ? $this->_sort_options:false,
                    'layout_mode' => Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') ? Configuration::get('ETS_CS_'.Tools::strtoupper($name_page).'_MODE') :'list',
                );
                $html = $this->displayProductList($params);
                $this->createCache($html,$cacheparams);
                return $html;
            }
        }
    }
}
