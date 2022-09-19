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

require_once _PS_MODULE_DIR_.'anblog/loader.php';

class AnbloglistModuleFrontController extends ModuleFrontController
{
    public $php_self;
    protected $template_path = '';

    public function __construct()
    {
        parent::__construct();
        $this->context = Context::getContext();
        $this->template_path = _PS_MODULE_DIR_.'anblog/views/templates/front/';
        $code = '';
        if (sizeof(Language::getLanguages(true, true)) > 1) {
            $code =$this->context->language->iso_code .  '/';
        }
        $this->context->smarty->assign(
            'anblog_main_page',
            $this->context->shop->getBaseURL(true) . $code . Configuration::get('link_rewrite', 'blog') . '.html'
        );
    }

    /**
     * @see FrontController::initContent()
     */
    public function initContent()
    {
        $this->php_self = 'list';
        
        $config = AnblogConfig::getInstance();
        $authors = array();

        /* Load Css and JS File */
        AnblogHelper::loadMedia($this->context, $this);

        parent::initContent();

        $helper = AnblogHelper::getInstance();

        $limit_leading_blogs = (int)$config->get('listing_limit_items', 6);
        $author = Tools::getValue('author');
        $tag = trim(Tools::getValue('tag'));
        $n = (int)$limit_leading_blogs;
        $p = abs((int)(Tools::getValue('p', 1)));
        $this->template_path .= 'default/';

        $condition = array();
        
        if ($author) {
            $employee_obj = new Employee($author);
            if (isset($employee_obj) && $employee_obj->id != '') {
                $condition = array(
                'type' => 'author',
                'id_employee' => $author,
                'employee' => new Employee($author)
                );
            } else {
                $condition = array(
                'type' => 'author',
                'author_name' => $author,
                );
            }
            $r = $helper->getPaginationLink('module-anblog-list', 'list', array('author' => $author));
        }
        if ($tag) {
            $condition = array(
                'type' => 'tag',
                'tag' => urldecode($tag)
            );
            $r = $helper->getPaginationLink('module-anblog-list', 'list', array('tag' => $tag));
        }

        $blogs = AnblogBlog::getListBlogs(null, $this->context->language->id, $p, $n, 'date_add', 'DESC', $condition, true);
        $count = AnblogBlog::countBlogs(null, $this->context->language->id, $condition, true);

        $leading_blogs = array();

        if (count($blogs)) {
            $leading_blogs = array_slice($blogs, 0, $limit_leading_blogs);
        }
        foreach ($leading_blogs as $key => $blog) {
            $blog = AnblogHelper::buildBlog($helper, $blog, 'anblog_listing_leading_img', $config);
            if ($blog['id_employee']) {
                if (!isset($authors[$blog['id_employee']])) {
                    // validate module
                    $authors[$blog['id_employee']] = new Employee($blog['id_employee']);
                }

                if ($blog['author_name'] != '') {
                    $blog['author'] = $blog['author_name'];
                    $blog['author_link'] = $helper->getBlogAuthorLink($blog['author_name']);
                } else {
                    $blog['author'] = $authors[$blog['id_employee']]->firstname . ' ' . $authors[$blog['id_employee']]->lastname;
                    $blog['author_link'] = $helper->getBlogAuthorLink($authors[$blog['id_employee']]->id);
                }
            } else {
                $blog['author'] = '';
                $blog['author_link'] = '';
            }

            $leading_blogs[$key] = $blog;
        }

        $nb_blogs = $count;
        $range = 2; /* how many pages around page selected */
        if ($p > (($nb_blogs / $n) + 1)) {
            Tools::redirect(preg_replace('/[&?]p=\d+/', '', $_SERVER['REQUEST_URI']));
        }
        $pages_nb = ceil($nb_blogs / (int)($n));
        $start = (int)($p - $range);
        if ($start < 1) {
            $start = 1;
        }
        $stop = (int)($p + $range);
        if ($stop > $pages_nb) {
            $stop = (int)($pages_nb);
        }

        if (!isset($r)) {
            $r = $helper->getPaginationLink('module-anblog-list', 'list', array(), false, true);
        }

        $module_tpl = 'module:anblog/views/templates/front/default';

        /* breadcrumb */
        $this->context->smarty->assign(
            array(
                'getBlogLink'    => true,
                'blogLink'       => $helper->getFontBlogLink(),
                'blogTitle'      => htmlentities($config->get('blog_link_title_'.$this->context->language->id, 'Blog'), ENT_NOQUOTES, 'UTF-8'),
                'navigationPipe' => Configuration::get('PS_NAVIGATION_PIPE')
            )
        );
        $url_rss = '';
        $enbrss = (int)$config->get('indexation', 0);
        if ($enbrss == 1) {
            $url_rss = Tools::htmlentitiesutf8('http://'.$_SERVER['HTTP_HOST'].__PS_BASE_URI__).'modules/anblog/rss.php';
        }
        $this->context->smarty->assign(
            array(
            'leading_blogs' => $leading_blogs,
            'listing_column' => $config->get('listing_column', 3),
            'filter' => $condition,
            'module_tpl' => $module_tpl,
            //'module_tpl_listing' => $module_tpl_listing,
            'nb_items' => $count,
            'range' => $range,
            'start' => $start,
            'stop' => $stop,
            'pages_nb' => $pages_nb,
            'config' => $config,
            'p' => (int)$p,
            'n' => (int)$n,
            'meta_title' => $config->get('meta_title_'.Context::getContext()->language->id).' - '.Configuration::get('PS_SHOP_NAME'),
            'meta_keywords' => $config->get('meta_keywords_'.Context::getContext()->language->id),
            'meta_description' => $config->get('meta_description_'.Context::getContext()->language->id),
            'requestPage' => $r['requestUrl'],
            'requestNb' => $r,
            'controller' => 'latest',
            'url_rss' => $url_rss,
            'post_type' => Tools::getIsset('post_type') ? Tools::getValue('post_type') : AnblogConfig::getInstance()->get('item_posts_type'),
            'show_in_blog' => Tools::getIsset('show_in_blog') ? Tools::getValue('show_in_blog') : AnblogConfig::getInstance()->get('show_in_blog'),
            'show_in_post' => Tools::getIsset('show_in_post') ? Tools::getValue('show_in_post') : AnblogConfig::getInstance()->get('show_in_post'),
            )
        );
        $this->setTemplate('module:anblog/views/templates/front/blog.tpl');
    }
    
    //DONGND:: add meta title, meta description, meta keywords
    public function getTemplateVarPage()
    {
        $page = parent::getTemplateVarPage();
        $config = AnblogConfig::getInstance();

        $page['meta']['title'] = $config->get('meta_title_'.Context::getContext()->language->id).' - '.Configuration::get('PS_SHOP_NAME');
        $page['meta']['keywords'] = $config->get('meta_keywords_'.Context::getContext()->language->id);
        $page['meta']['description'] = $config->get('meta_description_'.Context::getContext()->language->id);

        return $page;
    }
    
    //DONGND:: add breadcrumb
    public function getBreadcrumbLinks()
    {
        $breadcrumb = parent::getBreadcrumbLinks();
        $link = AnblogHelper::getInstance()->getFontBlogLink();
        $config = AnblogConfig::getInstance();
        $breadcrumb['links'][] = array(
            'title' => $config->get('blog_link_title_'.$this->context->language->id, $this->l('Blog', 'list')),
            'url' => $link,
        );

        return $breadcrumb;
    }
    
    //DONGND:: get layout
    public function getLayout()
    {
        $entity = 'module-anblog-'.$this->php_self;
        
        $layout = $this->context->shop->theme->getLayoutRelativePathForPage($entity);
        
        if ($overridden_layout = Hook::exec(
            'overrideLayoutTemplate',
            array(
                'default_layout' => $layout,
                'entity' => $entity,
                'locale' => $this->context->language->locale,
                'controller' => $this,
            )
        )
        ) {
            return $overridden_layout;
        }

        if ((int) Tools::getValue('content_only')) {
            $layout = 'layouts/layout-content-only.tpl';
        }

        return $layout;
    }
}
