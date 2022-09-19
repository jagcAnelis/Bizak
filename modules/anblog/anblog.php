<?php
/**
 * 2021 Anvanto
 *
 * NOTICE OF LICENSE
 *
 * This file is not open source! Each license that you purchased is only available for 1 wesite only.
 * If you want to use this file on more websites (or projects), you need to purchase additional licenses. 
 * You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
 *
 *  @author Anvanto <anvantoco@gmail.com>
 *  @copyright  2021 Anvanto
 *  @license    Valid for 1 website (or project) for each purchase of license
 *  International Registered Trademark & Property of Anvanto
 */

if (!defined('_PS_VERSION_')) {
    // module validation
    exit;
}

require_once _PS_MODULE_DIR_.'anblog/loader.php';

/**
 * Class anblog
 */
class anblog extends Module
{
    /**
     * @var string
     */
    public $base_config_url;

    /**
     * anblog constructor.
     */
    public function __construct()
    {
        $currentIndex = '';

        $this->name = 'anblog';
        $this->tab = 'front_office_features';
        $this->version = '3.1.2';
        $this->author = 'Anvanto';
        $this->module_key = 'c4eeba6dfce602f34ec16e1ccf830b1a';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->new174 = version_compare(_PS_VERSION_, '1.7.4.0', '>=') ?  true : false;
        $this->new = version_compare(_PS_VERSION_, '1.7.0.0', '>=') ?  true : false;

        $this->secure_key = Tools::encrypt($this->name);

        parent::__construct();

        $this->base_config_url = $currentIndex.'&configure='.$this->name.'&token='.Tools::getValue('token');
        $this->displayName = $this->l('AN Blog Management');
        $this->description = $this->l('Manage Blog Content');

        $code = '';
        if (sizeof(Language::getLanguages(true, true)) > 1) {
            $code =$this->context->language->iso_code .  '/';
        }
        $this->context->smarty->assign(
            'anblog_main_page',
            $this->context->shop->getBaseURL(true) . $code . Configuration::get('link_rewrite', 'blog') . '.html'
        );
		
		$this->imageTypes = [
			[
				'name' => 'anblog_default',
				'width' => 885,
				'height' => 620,
			],
			[
				'name' => 'anblog_thumb',
				'width' => 885,
				'height' => 620,
			],	
			[
				'name' => 'anblog_listing_leading_img',
				'width' => 405,
				'height' => 285,
			],
			[
				'name' => 'anblog_listing_secondary_img',
				'width' => 253,
				'height' => 177,
			],
			
		];
    }

    /**
     * Uninstall
     */
    private function uninstallModuleTab($class_sfx = '')
    {
        $tab_class = 'Admin'.Tools::ucfirst($this->name).Tools::ucfirst($class_sfx);

        $id_tab = Tab::getIdFromClassName($tab_class);
        if ($id_tab != 0) {
            $tab = new Tab($id_tab);
            $tab->delete();
            return true;
        }
        return false;
    }

    /**
     * Install Module Tabs
     */
    private function installModuleTab($title, $class_sfx = '', $parent = '')
    {
        $class = 'Admin'.Tools::ucfirst($this->name).Tools::ucfirst($class_sfx);
        @copy(_PS_MODULE_DIR_.$this->name.'/logo.gif', _PS_IMG_DIR_.'t/'.$class.'.gif');
        if ($parent == '') {
            // validate module
            $position = Tab::getCurrentTabId();
        } else {
            // validate module
            $position = Tab::getIdFromClassName($parent);
        }

        $tab1 = new Tab();
        $tab1->class_name = $class;
        $tab1->module = $this->name;
        $tab1->id_parent = (int)$position;
        $langs = Language::getLanguages(false);
        foreach ($langs as $l) {
            // validate module
            $tab1->name[$l['id_lang']] = $title;
        }
        $tab1->add(true, false);
    }

    /**
     * @see Module::install()
     */
    public function install()
    {
        /* Adds Module */
        if (parent::install()) {
            $this->registerANHook();
            $res = true;

            $res &= $this->installImageTypes();

            Configuration::updateValue('ANBLOG_CATEORY_MENU', 1);
            
            Configuration::updateValue('ANBLOG_DASHBOARD_DEFAULTTAB', '#fieldset_0');
            Configuration::updateValue('link_rewrite', 'blog');
			
			//	Delete old routers
			Configuration::deleteByName('PS_ROUTE_module-anblog-category');
			Configuration::deleteByName('PS_ROUTE_module-anblog-blog');
			Configuration::deleteByName('PS_ROUTE_module-anblog-list');
			
			
            /* Creates tables */
            $res &= $this->createTables();
            $res &= $this->installConfig();
            
            Configuration::updateValue('AP_INSTALLED_ANBLOG', '1');
            //DONGND: check thumb column, if not exist auto add
            if (Db::getInstance()->executeS(
                'SHOW TABLES LIKE \'%anblog_blog%\''
            )
                && count(
                    Db::getInstance()->executes(
                        'SELECT "thumb" FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = "'._DB_NAME_.'"
                         AND TABLE_NAME = "'._DB_PREFIX_.'anblog_blog" AND COLUMN_NAME = "thumb"'
                    )
                )<1
            ) {
                Db::getInstance()->execute(
                    'ALTER TABLE `'._DB_PREFIX_.'anblog_blog` ADD `thumb` varchar(255) DEFAULT NULL'
                );
            }

            $id_parent = Tab::getIdFromClassName('IMPROVE');
            
            $class = 'Admin'.Tools::ucfirst($this->name).'Management';
            $tab1 = new Tab();
            $tab1->class_name = $class;
            $tab1->module = $this->name;
            $tab1->id_parent = $id_parent;
            $langs = Language::getLanguages(false);

            foreach ($langs as $l) {
                // validate module
                $tab1->name[$l['id_lang']] = $this->l('AN Blog Management');
            }
            $tab1->add(true, false);
            
            // insert icon for tab
            if ($this->new) {
                Db::getInstance()->execute(
                    ' UPDATE `'._DB_PREFIX_.'tab` SET `icon` = "create" WHERE `id_tab` = "'.(int)$tab1->id.'"'
                );
            }

            $this->installModuleTab(
                'Posts',
                'blogs',
                'AdminAnblogManagement'
            );
            $this->installModuleTab(
                'Categories',
                'categories',
                'AdminAnblogManagement'
            );
            $this->installModuleTab(
                'Comments',
                'comments',
                'AdminAnblogManagement'
            );
            $this->installModuleTab(
                'Config',
                'dashboard',
                'AdminAnblogManagement'
            );

            return (bool)$res;
        }
        return false;
    }

    public function installImageTypes()
    {
        $res = true;

        $imageType = new ImageType();
        $imageType->products = 0;
        $imageType->categories = 0;
        $imageType->manufacturers = 0;
        $imageType->suppliers = 0;
        $imageType->stores = 0;		
		
		foreach ($this->imageTypes as $item){
			$imageType->name = $item['name'];
			$imageType->width = $item['width'];
			$imageType->height = $item['height'];
			$res &= $imageType->add();
		}
		
        return $res;
    }

    public function checkAndCreateImagesPresets()
    {

        if (!count(Db::getInstance()->executeS(
            'SELECT *
             FROM `'._DB_PREFIX_.'image_type`
             WHERE `name`LIKE \'anblog_%\''
        ))) {
            $this->installImageTypes();
        }
    }
    /**
     *
     */
    public function hookDisplayBackOfficeHeader()
    {
        //if (Dispatcher::getInstance()->getController() == 'AdminAnblogDashboard') {}
        if (file_exists(_PS_THEME_DIR_ . '/views/css/modules/anblog/assets/admin/blogmenu.css')) {
            $this->context->controller->addCss($this->_path . 'views/assets/admin/blogmenu.css');
        } else {
            $this->context->controller->addCss($this->_path . 'views/css/admin/blogmenu.css');
        }

        if (Dispatcher::getInstance()->getController() == 'AdminThemes') {
            $this->checkAndCreateImagesPresets();
        }
    }

    /**
     * @return string
     */
    public function getContent()
    {
        $redirect = $this->context->link->getAdminLink('AdminAnblogDashboard');
        Tools::redirectAdmin($redirect);
    }

    /**
     * @param $selected
     * @return string
     */
    public function getTreeForApPageBuilder($selected)
    {
        $cat = new Anblogcat();
        return $cat->getTreeForApPageBuilder($selected);
    }

    /**
     * @return bool
     */
    public function _prepareHook()
    {
        $helper = AnblogHelper::getInstance();

        $category = new Anblogcat(Tools::getValue('id_anblogcat'), $this->context->language->id);

        $tree = $category->getFrontEndTree((int)$category->id_anblogcat > 1 ? $category->id_anblogcat : 1, $helper);
        $this->smarty->assign('tree', $tree);
        if ($category->id_anblogcat) {
            // validate module
            $this->smarty->assign('currentCategory', $category);
        }

        return true;
    }

    /**
     *
     */
    public function hookDisplayHeader()
    {
        if (file_exists(_PS_THEME_DIR_.'/views/css/modules/anblog/assets/anblog.css')) {
            $this->context->controller->addCSS(($this->_path).'views/assets/anblog.css', 'all');
        } else {
            $this->context->controller->addCSS(($this->_path).'views/css/anblog.css', 'all');
        }

        //DONGND:: update language link
        if (Tools::getValue('module') == 'anblog') {
            $langs = Language::getLanguages(false);
            if (count($langs) > 1) {
                $config = AnblogConfig::getInstance();
                $array_list_rewrite = array();
                $array_category_rewrite = array();
                $array_config_category_rewrite = array();
                $array_blog_rewrite = array();
                $array_config_blog_rewrite = array();
                $config_url_use_id = !Configuration::get('PS_REWRITING_SETTINGS');
                $page_name = Dispatcher::getInstance()->getController();

                if ($page_name == 'blog') {
                    if ($config_url_use_id) {
                        $id_blog = Tools::getValue('id');
                    } else {
                        $id_shop = (int)Context::getContext()->shop->id;
                        $block_rewrite = pSQL(Tools::getValue('rewrite'));
                        $sql = 'SELECT bl.id_anblog_blog FROM '
                            ._DB_PREFIX_.'anblog_blog_lang bl INNER JOIN '
                            ._DB_PREFIX_.'anblog_blog_shop bs on bl.id_anblog_blog=bs.id_anblog_blog AND id_shop='
                            .$id_shop.' AND link_rewrite = "'.$block_rewrite.'"';
                        if ($row = Db::getInstance()->getRow($sql)) {
                            $id_blog = $row['id_anblog_blog'];
                        }
                    }
                }

                if ($page_name == 'category') {
                    if ($config_url_use_id) {
                        $id_category = Tools::getValue('id');
                    } else {
                        $id_shop = (int)Context::getContext()->shop->id;
                        $category_rewrite = pSQL(Tools::getValue('rewrite'));
                        $sql = 'SELECT cl.id_anblogcat FROM '
                            ._DB_PREFIX_.'anblogcat_lang cl INNER JOIN '
                            ._DB_PREFIX_.'anblogcat_shop cs  on cl.id_anblogcat=cs.id_anblogcat AND id_shop='
                            .$id_shop. ' INNER JOIN '
                            ._DB_PREFIX_.'anblogcat cc  on cl.id_anblogcat=cc.id_anblogcat
                             AND cl.id_anblogcat != cc.id_parent AND link_rewrite = "'.$category_rewrite.'"';
                        if ($row = Db::getInstance()->getRow($sql)) {
                            $id_category = $row['id_anblogcat'];
                        }
                    }
                    $blog_category_obj = new Anblogcat($id_category);
                }

                foreach ($langs as $lang) {
                    $array_list_rewrite[$lang['iso_code']] = $config->get('link_rewrite_'.$lang['id_lang'], 'blog');

                    if (isset($id_blog)) {
                        $blog_obj = new Anblogblog($id_blog);
                        $array_blog_rewrite[$lang['iso_code']] = $blog_obj->link_rewrite[$lang['id_lang']];
                        if ($config_url_use_id) {
                            $array_config_blog_rewrite[$lang['iso_code']]
                                = $config->get('detail_rewrite_'.$lang['id_lang'], 'detail');
                        }
                    }

                    if (isset($id_category)) {
                        $array_category_rewrite[$lang['iso_code']] = $blog_category_obj->link_rewrite[$lang['id_lang']];
                        if ($config_url_use_id) {
                            $array_config_category_rewrite[$lang['iso_code']]
                                = $config->get('category_rewrite_'.$lang['id_lang'], 'category');
                        }
                    }
                };

                Media::addJsDef(
                    array(
                        'array_list_rewrite' => $array_list_rewrite,
                        'array_category_rewrite' => $array_category_rewrite,
                        'array_blog_rewrite' => $array_blog_rewrite,
                        'array_config_category_rewrite' => $array_config_category_rewrite,
                        'array_config_blog_rewrite' => $array_config_blog_rewrite,
                        'config_url_use_id' => (int)!!$config_url_use_id
                    )
                );
            }
        }
    }

    public function hookDisplayLeftColumn($params)
    {
        $this->context->smarty->assign(array(
            'an_left_category' => $this->leftCategoryBlog(),
            'an_left_tag' => $this->lefTagBlog(),
            'an_left_recent' => $this->leftRecentBlog(),
        ));
        return $this->display(__FILE__, 'views/templates/hook/left_column_main.tpl');
    }


    /**
     * @return string
     */
    public function leftCategoryBlog()
    {
        $html = '';


        if (/*Configuration::get('ANBLOG_CATEORY_MENU') && */$this->_prepareHook()) {
            $html .= $this->display(__FILE__, 'views/templates/hook/categories_menu.tpl');
        }
        return $html;
    }

    /**
     * @return string
     */
    public function leftRecentBlog()
    {
        $html = '';

        $config = AnblogConfig::getInstance();
        $helper = AnblogHelper::getInstance();
        $authors = array();

        $limit = (int)$config->get('limit_recent_blog', 5);
        $leading_blogs = AnblogBlog::getListBlogs(
            null,
            $this->context->language->id,
            1,
            $limit,
            'date_add',
            'DESC',
            array(),
            true
        );
        foreach ($leading_blogs as $key => $blog) {
            $blog = AnblogHelper::buildBlog($helper, $blog, 'anblog_listing_secondary_img', $config);
            if ($blog['id_employee']) {
                if (!isset($authors[$blog['id_employee']])) {
                    // validate module
                    $authors[$blog['id_employee']] = new Employee($blog['id_employee']);
                }

                if ($blog['author_name'] != '') {
                    $blog['author'] = $blog['author_name'];
                    $blog['author_link'] = $helper->getBlogAuthorLink($blog['author_name']);
                } else {
                    $blog['author'] = $authors[$blog['id_employee']]->firstname.' '.$authors[$blog['id_employee']]->lastname;
                    $blog['author_link'] = $helper->getBlogAuthorLink($authors[$blog['id_employee']]->id);
                }
            } else {
                $blog['author'] = '';
                $blog['author_link'] = '';
            }

            $leading_blogs[$key] = $blog;
        }

        $this->smarty->assign('leading_blogs', $leading_blogs);
        $html .= $this->display(__FILE__, 'views/templates/hook/left_recent.tpl');

        return $html;
    }

    /**
     * @return string
     */
    public function lefTagBlog()
    {

        $html = '';
        $helper = AnblogHelper::getInstance();

        $leading_blogs = AnblogBlog::getListBlogs(
            null,
            $this->context->language->id,
            1,
            100000,
            'date_add',
            'DESC',
            array(),
            true
        );

        $tags_temp = array();
        foreach ($leading_blogs as $value) {
            $tags_temp = array_merge($tags_temp, explode(",", $value['tags']));
        }

        $tags_temp = array_unique($tags_temp);
        $tags = array();
        foreach ($tags_temp as $tag_temp) {
            $tags[] = array(
                'name' => $tag_temp,
                'link' => $helper->getBlogTagLink($tag_temp)
            );
        }

        $this->smarty->assign('anblogtags', $tags);
        $html .= $this->display(__FILE__, 'views/templates/hook/left_anblogtags.tpl');

        return $html;
    }

    /**
     * @param null $name
     * @return string
     */
    protected function getCacheId($name = null)
    {
        $name = ($name ? $name.'|' : '').implode('-', Customer::getGroupsStatic($this->context->customer->id));
        return parent::getCacheId($name);
    }

    /**
     * @param $params
     * @return string
     */
    public function hookdisplayRightcolumn($params)
    {
        return $this->hookdisplayLeftColumn($params);
    }

    /**
     * @see Module::uninstall()
     */
    public function uninstall()
    {
        if (parent::uninstall()) {
            $res = true;

            foreach (ImageType::getImagesTypes() as $type) {
                if ($type['name'] == 'anblog_default'
                    || $type['name'] == 'anblog_thumb'
                    || $type['name'] == 'anblog_listing_leading_img'
                    || $type['name'] == 'anblog_listing_secondary_img'
                ) {
                    $imageType = new ImageType($type['id_image_type']);
                    $res &= $imageType->delete();
                }
            }

            $this->uninstallModuleTab('management');
            $this->uninstallModuleTab('dashboard');
            $this->uninstallModuleTab('categories');
            $this->uninstallModuleTab('blogs');
            $this->uninstallModuleTab('comments');
            $this->uninstallModuleTab('module');
            
            $res &= $this->deleteTables();
            $this->deleteConfiguration();

            return (bool)$res;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function deleteTables()
    {
        return Db::getInstance()->execute(
            '
            DROP TABLE IF EXISTS
            `'._DB_PREFIX_.'anblogcat`,
            `'._DB_PREFIX_.'anblogcat_lang`,
            `'._DB_PREFIX_.'anblogcat_shop`,
            `'._DB_PREFIX_.'anblog_comment`,
            `'._DB_PREFIX_.'anblog_blog`,
            `'._DB_PREFIX_.'anblog_blog_lang`,
            `'._DB_PREFIX_.'anblog_hooks`,
            `'._DB_PREFIX_.'anblog_blog_categories`,
            `'._DB_PREFIX_.'anblog_blog_shop`'
        );
    }

    /**
     * @return bool
     */
    public function deleteConfiguration()
    {
        Configuration::deleteByName('ANBLOG_CATEORY_MENU');
        Configuration::deleteByName('ANBLOG_DASHBOARD_DEFAULTTAB');
        Configuration::deleteByName('ANBLOG_CFG_GLOBAL');
        return true;
    }

    /**
     * Creates tables
     */
    protected function createTables()
    {
        if ($this->_installDataSample()) {
            return true;
        }
        $res = 1;
        include_once dirname(__FILE__).'/install/install.php';
        return $res;
    }

    /**
     * @return bool
     */
    private function _installDataSample()
    {
        if (!file_exists(_PS_MODULE_DIR_.'appagebuilder/libs/ANDataSample.php')) {
            return false;
        }
        include_once _PS_MODULE_DIR_.'appagebuilder/libs/ANDataSample.php';

        $sample = new Datasample(1);
        return $sample->processImport($this->name);
    }

    /**
     * @return int
     */
    protected function installSample()
    {
        $res = 1;
        include_once dirname(__FILE__).'/install/sample.php';
        return $res;
    }

    /**
     * @return int
     */
    protected function installConfig()
    {
        $res = 1;
        include_once dirname(__FILE__).'/install/config.php';
        return $res;
    }

    /**
     * Hook ModuleRoutes
     */
    public function hookModuleRoutes($route = '', $detail = array())
    {
        if ($this->context->controller instanceof AdminController && !Tools::getIsset('controller')) {
            return false;
        }//TODO check this

        $config = AnblogConfig::getInstance();
        $routes = array();

        $routes['module-anblog-list'] = array(
            'controller' => 'list',
            'rule' => _AN_BLOG_REWRITE_ROUTE_.'.html',
            'keywords' => array(
            ),
            'params' => array(
                'fc' => 'module',
                'module' => 'anblog'
            )
        );
        
        if (Configuration::get('PS_REWRITING_SETTINGS')) {
            // URL HAVE ID
            $routes['module-anblog-blog'] = array(
                'controller' => 'blog',
                'rule' => _AN_BLOG_REWRITE_ROUTE_.'/post/{rewrite}/',
                'keywords' => array(
                    'id' => array('regexp' => '[0-9]+', 'param' => 'id'),
                    'rewrite' => array('regexp' => '[_a-zA-Z0-9-\pL]*', 'param' => 'rewrite'),
                ),
                'params' => array(
                    'fc' => 'module',
                    'module' => 'anblog',
                    
                )
            );

            $routes['module-anblog-category'] = array(
                'controller' => 'category',
                'rule' => _AN_BLOG_REWRITE_ROUTE_.'/{rewrite}/',
                'keywords' => array(
                    'id' => array('regexp' => '[0-9]+', 'param' => 'id'),
                    'rewrite' => array('regexp' => '[_a-zA-Z0-9-\pL]*', 'param' => 'rewrite'),
                ),
                'params' => array(
                    'fc' => 'module',
                    'module' => 'anblog',
                )
            );
        } else {
            // REMOVE ID FROM URL
            $category_rewrite = 'category_rewrite'.'_'.Context::getContext()->language->id;
            $category_rewrite = $config->get($category_rewrite, 'category');
            $detail_rewrite = 'detail_rewrite'.'_'.Context::getContext()->language->id;
            $detail_rewrite = $config->get($detail_rewrite, 'detail');

            $routes['module-anblog-blog'] = array(
                'controller' => 'blog',
                'rule' => _AN_BLOG_REWRITE_ROUTE_.'/'.$detail_rewrite.'/{rewrite}-b{id}.html',
                'keywords' => array(
                    'id' => array('regexp' => '[0-9]+', 'param' => 'id'),
                    'rewrite' => array('regexp' => '[_a-zA-Z0-9-\pL]*', 'param' => 'rewrite'),
                ),
                'params' => array(
                    'fc' => 'module',
                    'module' => 'anblog',
                )
            );

            $routes['module-anblog-category'] = array(
                'controller' => 'category',
                'rule' => _AN_BLOG_REWRITE_ROUTE_.'/'.$category_rewrite.'/{rewrite}-c{id}.html',
                'keywords' => array(
                    'id' => array('regexp' => '[0-9]+', 'param' => 'id'),
                    'rewrite' => array('regexp' => '[_a-zA-Z0-9-\pL]*', 'param' => 'rewrite'),
                ),
                'params' => array(
                    'fc' => 'module',
                    'module' => 'anblog',
                )
            );
        }
        return $routes;
    }

    /**
     * Get lastest blog for ApPageBuilder module
     *
     * @param  type $params
     * @return type
     */
    public function getBlogsFont($params)
    {
        $config = AnblogConfig::getInstance();
        $id_categories = '';
        if (isset($params['chk_cat'])) {
            // validate module
            $id_categories = $params['chk_cat'];
        }
        $order_by = isset($params['order_by']) ? $params['order_by'] : 'id_anblog_blog';
        $order_way = isset($params['order_way']) ? $params['order_way'] : 'DESC';
        $helper = AnblogHelper::getInstance();
        $limit = (int)$params['nb_blogs'];
        $blogs = AnblogBlog::getListBlogsForApPageBuilder(
            $id_categories,
            $this->context->language->id,
            $limit,
            $order_by,
            $order_way,
            array(),
            true
        );
        $authors = array();
        foreach ($blogs as $key => &$blog) {
            $blog = AnblogHelper::buildBlog($helper, $blog, 'anblog_listing_leading_img', $config);
            if ($blog['id_employee']) {
                if (!isset($authors[$blog['id_employee']])) {
                    $authors[$blog['id_employee']] = new Employee($blog['id_employee']);
                }
                $blog['author'] = $authors[$blog['id_employee']]->firstname.' '.$authors[$blog['id_employee']]->lastname;
                $blog['author_link'] = $helper->getBlogAuthorLink($authors[$blog['id_employee']]->id);
            } else {
                $blog['author'] = '';
                $blog['author_link'] = '';
            }
            unset($key); // validate module
        }
        return $blogs;
    }

    /**
     * Run only one when install/change Theme_of_AN
     */
    public function hookActionAdminBefore($params)
    {
        $this->unregisterHook('actionAdminBefore');
        if (isset($params) && isset($params['controller']) && isset($params['controller']->theme_manager)) {
            // Validate : call hook from theme_manager
        } else {
            // Other module call this hook -> duplicate data
            return;
        }

        // FIX : update Prestashop by 1-Click module -> NOT NEED RESTORE DATABASE
        $ap_version = Configuration::get('AP_CURRENT_VERSION');
        if ($ap_version != false) {
            $ps_version = Configuration::get('PS_VERSION_DB');
            $versionCompare =  version_compare($ap_version, $ps_version);
            if ($versionCompare != 0) {
                // Just update Prestashop
                Configuration::updateValue('AP_CURRENT_VERSION', $ps_version);
                return;
            }
        }

        // WHENE INSTALL THEME, INSERT HOOK FROM DATASAMPLE IN THEME
        $hook_from_theme = false;
        if (file_exists(_PS_MODULE_DIR_.'appagebuilder/libs/ANDataSample.php')) {
            include_once _PS_MODULE_DIR_.'appagebuilder/libs/ANDataSample.php';
            $sample = new Datasample();
            if ($sample->processHook($this->name)) {
                $hook_from_theme = true;
            };
        }

        // INSERT HOOK FROM MODULE_DATASAMPLE
        if ($hook_from_theme == false) {
            $this->registerANHook();
        }

        // WHEN INSTALL MODULE, NOT NEED RESTORE DATABASE IN THEME
        $install_module = (int)Configuration::get('AP_INSTALLED_ANBLOG', 0);
        if ($install_module) {
            Configuration::updateValue('AP_INSTALLED_ANBLOG', '0');// next : allow restore sample
            return;
        }

        // INSERT DATABASE FROM THEME_DATASAMPLE
        if (file_exists(_PS_MODULE_DIR_.'appagebuilder/libs/ANDataSample.php')) {
            include_once _PS_MODULE_DIR_.'appagebuilder/libs/ANDataSample.php';
            $sample = new Datasample();
            $sample->processImport($this->name);
        }
    }

    /**
     * Common method
     * Resgister all hook for module
     */
    public function registerANHook()
    {
        $res = true;
        $res &= $this->registerHook('header');
        $res &= $this->registerHook('moduleRoutes');
        $res &= $this->registerHook('displayBackOfficeHeader');
        $res &= $this->registerHook('displayHome');
		$res &= $this->registerHook('displayHomeAfter');
		$res &= $this->registerHook('displayBlogWidget');
        // Multishop create new shop
        $res &= $this->registerHook('actionAdminShopControllerSaveAfter');
        return $res;
    }

    /**
     *
     */
    public function correctModule()
    {
        //DONGND:: check thumb column, if not exist auto add
        if (Db::getInstance()->executeS('SHOW TABLES LIKE \'%anblog_blog%\'')
            && count(
                Db::getInstance()->executes(
                    'SELECT "thumb" FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = "'._DB_NAME_.'"
                    AND TABLE_NAME = "'._DB_PREFIX_.'anblog_blog" AND COLUMN_NAME = "thumb"'
                )
            )<1
        ) {
            Db::getInstance()->execute(
                'ALTER TABLE `'._DB_PREFIX_.'anblog_blog` ADD `thumb` varchar(255) DEFAULT NULL'
            );
        }

        //DONGND:: check author name column, if not exist auto add
        if (Db::getInstance()->executeS('SHOW TABLES LIKE \'%anblog_blog%\'')
            && count(
                Db::getInstance()->executes(
                    'SELECT "author_name" FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = "'._DB_NAME_.'"
                     AND TABLE_NAME = "'._DB_PREFIX_.'anblog_blog" AND COLUMN_NAME = "author_name"'
                )
            )<1
        ) {
            Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'anblog_blog` ADD `author_name` varchar(255) DEFAULT NULL');
        }
    }

    /**
     * @Action Create new shop, choose theme then auto restore datasample.
     */
    public function hookActionAdminShopControllerSaveAfter($param)
    {
        if (Tools::getIsset('controller') !== false && Tools::getValue('controller') == 'AdminShop'
            && Tools::getIsset('submitAddshop') !== false && Tools::getValue('submitAddshop')
            && Tools::getIsset('theme_name') !== false && Tools::getValue('theme_name')
        ) {
            $shop = $param['return'];
            
            if (file_exists(_PS_MODULE_DIR_.'appagebuilder/libs/ANDataSample.php')) {
                include_once _PS_MODULE_DIR_.'appagebuilder/libs/ANDataSample.php';
                $sample = new Datasample();
                AnblogHelper::$id_shop = $shop->id;
                $sample->_id_shop = $shop->id;
                $sample->processImport('anblog');
            }
        }
    }

    public function regenerateThumbs()
    {
        $query = '
		SELECT  c.id_anblog_blog, c.image
		FROM  '._DB_PREFIX_.'anblog_blog c';
        $data = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

        if (count($data)) {
            foreach ($data as $post) {
                $anblogImage = new AnblogImage($post);
                $anblogImage->delete(true);
            }
        }
    }

    public function hookDisplayHome($params)
    {
        return $this->renderBlog();
    }
	
    public function hookDisplayHomeAfter($params)
    {
        return $this->renderBlog();
    }

    public function hookDisplayBlogWidget($params)
    {
        return $this->renderBlog();
    }	

    /**
     * @param array $params
     * @return string
    */
    public function renderBlog($hookName = 'DisplayHome')
    {
        $config = AnblogConfig::getInstance();
        if (!$config->get('show_in_' . $hookName, 0)) {
            return '';
        }
        $helper = AnblogHelper::getInstance();
        $authors = array();
        $articles = array();
        $postCount = (int)$config->get('limit_' . $hookName . '_blog', 6);
		
		$homeCat = (int)$config->get('categories_DisplayHome_blog');
		if (!$homeCat){
			$homeCat = null;
		}		
		
        if ($postCount > 0) {
            $articles = AnblogBlog::getListBlogs(
                $homeCat,
                $this->context->language->id,
                1,
                $postCount,
                'date_add',
                'DESC',
                array(),
                true
            );
        }
        foreach ($articles as $key => $article) {
            $article = AnblogHelper::buildBlog($helper, $article, 'anblog_listing_leading_img', $config);
            if ($article['id_employee']) {
                if (!isset($authors[$article['id_employee']])) {
                    $authors[$article['id_employee']] = new Employee($article['id_employee']);
                }
                if ($article['author_name'] != '') {
                    $article['author'] = $article['author_name'];
                    $article['author_link'] = $helper->getBlogAuthorLink($article['author_name']);
                } else {
                    $article['author'] = $authors[$article['id_employee']]->firstname.' '.$authors[$article['id_employee']]->lastname;
                    $article['author_link'] = $helper->getBlogAuthorLink($authors[$article['id_employee']]->id);
                }
            } else {
                $article['author'] = '';
                $article['author_link'] = '';
            }
            $articles[$key] = $article;
        }
        $this->smarty->assign(
            array(
                'articles' => $articles,
                'config' => $config,
                'title'  => $config->get('hook_header_' . $this->context->language->id, ''),
                'columnCount' => $config->get('hook_column', 3)
            )
        );
        if (version_compare(_PS_VERSION_, '1.7.4', '>=')) {
            return $this->display(__FILE__, 'views/templates/hook/universal174.tpl');
        } else {
            return $this->display(__FILE__, 'views/templates/hook/universal.tpl');
        }
    }

    public function translateFrontBlog()
    {
        return array(
            'thanks' => $this->l('Thanks for your comment, it will be published soon!'),
            'error' => $this->l('An error occurred while sending the comment. Please recorrect data in fields!'),
            'recapcha' => $this->l('Please submit reCAPTCHA'),
            'blog' => $this->l('Blog'),
        );
    }
	
	public function checkIssetImageTypes()
	{
		$existingImageTypes = Db::getInstance()->executeS('
                SELECT *
                FROM `'._DB_PREFIX_.'image_type`
                WHERE `name`LIKE \'anblog_%\'');

		$existingImageTypesNames = [];
		foreach ($existingImageTypes as $item){
			$existingImageTypesNames[] = $item['name'];
		}
		
		$errorImageTypes = [];
 		foreach ($this->imageTypes as $id => $item){
			if (!in_array($item['name'], $existingImageTypesNames)){
				$errorImageTypes[] = 'Image type "' . $item['name'] . '" does not exist.';
			}
		}
		
		return $errorImageTypes;
	}
	
}
