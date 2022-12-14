<?php
/**
 * 2020 Anvanto
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author Anvanto (anvantoco@gmail.com)
 *  @copyright  2020 anvanto.com

 *  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_')) {
    // module validation
    exit;
}

class AnblogHelper
{
    public $bloglink = null;
    public $ssl;

    public static function getInstance()
    {
        static $instance = null;
        if (!$instance) {
            // validate module
            $instance = new AnblogHelper();
        }

        return $instance;
    }

    public function __construct()
    {
        if (Configuration::get('PS_SSL_ENABLED') && Configuration::get('PS_SSL_ENABLED_EVERYWHERE')) {
            $this->ssl = true;
        }

        $protocol_link = (Configuration::get('PS_SSL_ENABLED') || Tools::usingSecureMode()) ? 'https://' : 'http://';
        $use_ssl = ((isset($this->ssl) && $this->ssl && Configuration::get('PS_SSL_ENABLED')) || Tools::usingSecureMode()) ? true : false;
        $protocol_content = ($use_ssl) ? 'https://' : 'http://';
        $this->bloglink = new AnblogLink($protocol_link, $protocol_content);
    }

    public static function loadMedia($context, $obj)
    {
        if (file_exists(_PS_THEME_DIR_.'css/modules/anblog/views/assets/anblog.css')) {
            $context->controller->addCss($obj->module->getPathUri().'views/assets/anblog.css');
        } else {
            $context->controller->addCss($obj->module->getPathUri().'views/css/anblog.css');
        }

        if (file_exists(_PS_THEME_DIR_.'js/modules/anblog/views/assets/anblog.js')) {
            $context->controller->addJs($obj->module->getPathUri().'views/assets/anblog.js');
        } else {
            $context->controller->addJs($obj->module->getPathUri() . 'views/js/anblog.js');
        }
        $anblogConfig = AnblogConfig::getInstance();
        if ($anblogConfig->params['item_comment_engine'] == 'local' && $anblogConfig->params['google_captcha_site_key'] && $anblogConfig->params['google_captcha_secret_key'] && $anblogConfig->params['google_captcha_status']) {
            $context->controller->registerJavascript('recaptcha', 'https://www.google.com/recaptcha/api.js', array('server' => 'remote', 'position' => 'bottom', 'priority' => 20));
        }
    }

    public function getLinkObject()
    {
        return $this->bloglink;
    }

    public function getModuleLink($route_id, $controller, array $params = array(), $ssl = null, $id_lang = null, $id_shop = null)
    {
        return $this->getLinkObject()->getLink($route_id, $controller, $params, $ssl, $id_lang, $id_shop);
    }

    public function getFontBlogLink()
    {
        return $this->getModuleLink('module-anblog-list', 'list', array());
    }

    public function getPaginationLink($route_id, $controller, array $params = array(), $nb = false, $sort = false, $pagination = false, $array = true)
    {
        return $this->getLinkObject()->getANPaginationLink('anblog', $route_id, $controller, $params, $nb, $sort, $pagination, $array);
    }

    public function getBlogLink($blog, $params1 = array())
    {
        $params = array(
            'id' => $blog['id_anblog_blog'],
            'rewrite' => $blog['link_rewrite'],
        );

        $params = array_merge($params, $params1);
        return $this->getModuleLink('module-anblog-blog', 'blog', $params);
    }

    public function getTagLink($tag)
    {
        $params = array(
            'tag' => $tag,
        );

        return $this->getModuleLink('blog_user_filter_rule', 'blog', $params);
    }

    public function getBlogCatLink($cparams)
    {
        $params = array(
            'id' => '',
            'rewrite' => ''
        );
        $params = array_merge($params, $cparams);
        return $this->getModuleLink('module-anblog-category', 'category', $params);
    }

    public function getBlogTagLink($tag, $cparams = array())
    {
        $params = array(
            'tag' => urlencode($tag),
        );
        $params = array_merge($params, $cparams);
        return $this->getModuleLink('module-anblog-list', 'list', $params);
    }

    public function getBlogAuthorLink($author, $cparams = array())
    {
        $params = array(
            'author' => $author,
        );
        $params = array_merge($params, $cparams);
        return $this->getModuleLink('module-anblog-list', 'list', $params);
    }

    public static function getTemplates()
    {
        $theme = _THEME_NAME_;
        $path = _PS_MODULE_DIR_.'anblog';
        $tpath = _PS_ALL_THEMES_DIR_.$theme.'modules/anblog/front';

        $output = array();

        $templates = glob($path.'/views/templates/front/*', GLOB_ONLYDIR);

        $ttemplates = glob($tpath, GLOB_ONLYDIR);
        if ($templates) {
            foreach ($templates as $t) {
                // validate module
                $output[basename($t)] = array('type' => 'module', 'template' => basename($t));
            }
        }
        if ($ttemplates) {
            foreach ($ttemplates as $t) {
                // validate module
                $output[basename($t)] = array('type' => 'module', 'template' => basename($t));
            }
        }

        return $output;
    }

    public static function buildBlog($helper, $blog, $image_type, $config)
    {
        // module validation


        $url = _PS_BASE_URL_;
        if (Tools::usingSecureMode()) {
            // validate module
            $url = _PS_BASE_URL_SSL_;
        }

        $id_shop = (int)Context::getContext()->shop->id;
        $blog['preview_url'] = '';

        $imgObj = new AnblogImage($blog);
        $blog['thumb_url'] = '';
        $blog['image_url'] = $imgObj->mainurl;
        if (array_key_exists($image_type, $imgObj->thumbsurls)) {
            $blog['preview_url'] = $imgObj->thumbsurls[$image_type];
            $blog['thumb_url'] = $imgObj->thumbsurls[$image_type];
        }
        
        $params = array(
            'rewrite' => $blog['category_link_rewrite'],
            'id' => $blog['id_anblogcat']
        );
        if ($config->get('item_comment_engine', 'local') == 'local') {
            // validate module
            $blog['comment_count'] = AnblogComment::countComments($blog['id_anblog_blog'], true, true);
        }
        $blog['category_link'] = $helper->getBlogCatLink($params);
        $blog['link'] = $helper->getBlogLink($blog);
        return $blog;
    }

    public static function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != '.' && $object != '..') {
                    if (filetype($dir.'/'.$object) == 'dir') {
                        self::rrmdir($dir.'/'.$object);
                    } else {
                        unlink($dir.'/'.$object);
                    }
                }
            }
            $objects = scandir($dir);
            reset($objects);
            rmdir($dir);
        }
    }

    public static function getConfigKey($multi_lang = false)
    {
        if ($multi_lang == false) {
            return array(
                'saveConfiguration',
                'indexation',
                'rss_limit_item',
                'rss_title_item',
                'listing_show_categoryinfo',
                'listing_limit_items',
                'listing_show_title',
                'listing_show_description',
                'listing_show_readmore',
                'listing_show_image',
                'listing_show_author',
                'listing_show_category',
                'listing_show_created',
                'listing_show_hit',
                'listing_show_counter',
                'item_show_description',
                'item_show_image',
                'item_show_author',
                'item_show_category',
                'item_show_created',
                'item_show_hit',
                'item_show_counter',
                'social_code',
                'google_captcha_status',
                'google_captcha_site_key',
                'google_captcha_secret_key',
                'item_show_listcomment',
                'item_show_formcomment',
                'item_comment_engine',
                'item_limit_comments',
                'item_diquis_account',
                'item_facebook_appid',
                'item_facebook_width',
                'show_popular_blog',
                'limit_popular_blog',
                'show_recent_blog',
                'limit_recent_blog',
                'limit_DisplayHome_blog',
                'show_all_tags',
                'link_rewrite',
                'show_in_blog',
                'show_in_post',
                'categories_DisplayHome_blog',
                'show_in_DisplayHome',
                'item_posts_type',
            );
        } else {
            return array(
                'blog_link_title',
                'category_rewrite',
                'detail_rewrite',
                'meta_title',
                'meta_description',
                'meta_keywords',
            );
        }
    }

    /**
     * @return day in month
     * 1st, 2nd, 3rd, 4th, ...
     */
    public function ordinal($number)
    {
        $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
        if ((($number % 100) >= 11) && (($number % 100) <= 13)) {
            return $number.'th';
        } else {
            return $number.$ends[$number % 10];
        }
    }

    /**
     * @return day in month
     * st, nd, rd, th, ...
     */
    public function string_ordinal($number)
    {
        $number = (int) $number;
        $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
        if ((($number % 100) >= 11) && (($number % 100) <= 13)) {
            return 'th';
        } else {
            return $ends[$number % 10];
        }
    }
    
    public static function genKey()
    {
        return md5(time().rand());
    }
    
    static $id_shop;
    /**
     * FIX Install multi theme
     * AnblogHelper::getIDShop();
     */
    public static function getIDShop()
    {
        if ((int)self::$id_shop) {
            $id_shop = (int)self::$id_shop;
        } else {
            $id_shop = (int)Context::getContext()->shop->id;
        }
        return $id_shop;
    }
}
