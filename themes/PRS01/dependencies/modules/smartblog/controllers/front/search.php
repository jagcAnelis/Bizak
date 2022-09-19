<?php
/**
* 2007-2018 PrestaShop
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

include_once(dirname(__FILE__).'/../../classes/controllers/FrontController.php');
class SmartblogsearchModuleFrontController extends smartblogModuleFrontController
{
    public $ssl = true;

    public function init()
    {
        parent::init();
    }

    public function initContent()
    {

        parent::initContent();
        $rer = SmartBlogPost::tagsPost('asd');

        $blogcomment = new Blogcomment();
        $result = '';
        $keyword = Tools::getValue('smartsearch');
        Hook::exec('actionsbsearch', array('keyword' => Tools::getValue('smartsearch')));
        $id_lang = (int)$this->context->language->id;
        $title_category = '';
        $posts_per_page = Configuration::get('smartpostperpage');
        $limit_start = 0;
        $limit = $posts_per_page;

        if ((boolean)Tools::getValue('page')) {
            $c = (int)Tools::getValue('page');
            $limit_start = $posts_per_page * ($c - 1);
        }

        $keyword = Tools::getValue('smartsearch');
        $id_lang = (int)$this->context->language->id;
        $result = SmartBlogPost::smartBlogSearchPost($keyword, $id_lang, $limit_start, $limit);

        $total = SmartBlogPost::smartBlogSearchPostCount($keyword, $id_lang);
        $totalpages = ceil($total/$posts_per_page);
        $i = 0;
        $to = array();
        if (!empty($result)) {
            foreach ($result as $item) {
                $to[$i] = $blogcomment->getToltalComment($item['id_post']);
                $i++;
            }
            $j = 0;
            foreach ($to as $item) {
                if ($item == '') {
                    $result[$j]['totalcomment'] = 0;
                } else {
                    $result[$j]['totalcomment'] = $item;
                }
                $j++;
            }
        }

        $this->context->smarty->assign(array(
            'layout_search'=>$this->context->shop->theme->getLayoutRelativePathForPage('module-smartblog-search'),
            'postcategory'=>$result,
            'title_category'=>$title_category,
            'smartshowauthorstyle'=>Configuration::get('smartshowauthorstyle'),
            'limit'=>isset($limit) ? $limit : 0,
            'limit_start'=>isset($limit_start) ? $limit_start : 0,
            'c'=>isset($c) ? $c : 1,
            'total'=>$total,
            'smartshowviewed' => Configuration::get('smartshowviewed'),
            'smartcustomcss' => Configuration::get('smartcustomcss'),
            'smartshownoimg' => Configuration::get('smartshownoimg'),
            'smartshowauthor'=>Configuration::get('smartshowauthor'),
            'smartblogliststyle' => Configuration::get('smartblogliststyle'),
            'post_per_page'=>$posts_per_page,
            'smartsearch'=>Tools::getValue('smartsearch'),
            'pagenums' => $totalpages - 1,
            'totalpages' =>$totalpages
        ));
        $this->setTemplate('module:smartblog/views/templates/front/searchresult.tpl');
    }
}
