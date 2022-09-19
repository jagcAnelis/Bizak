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
class SmartblogDetailsModuleFrontController extends smartblogModuleFrontController
{
    public $ssl = true;
    public $_report = '';
    private $_postsObject;

    public function init()
    {
        parent::init();
    }

    public function initContent()
    {
        parent::initContent();
        $id_post = Tools::getValue('id_post');
        Hook::exec('actionsbsingle', array('id_post' => $id_post));
        $blogcomment = new Blogcomment();
        $SmartBlogPost = new SmartBlogPost();
        $BlogCategory = new BlogCategory();

        $id_lang = $this->context->language->id;
        $id_lang_defaut = Configuration::get('PS_LANG_DEFAULT');
        $post = $SmartBlogPost->getPost($id_post, $id_lang);
        $tags = $SmartBlogPost->getProductTags($id_post);
        $comment = $blogcomment->getComment($id_post);
        $countcomment = $blogcomment->getToltalComment($id_post);
        $id_cate = $post['id_category'];
        $title_category = $BlogCategory->getNameCategory($id_cate);
        if (file_exists(_PS_MODULE_DIR_.'smartblog/views/img/' . Tools::getValue('id_post') . '.jpg')) {
            $post_img =  Tools::getValue('id_post');
        } else {
            $post_img = 'no';
        }

        SmartBlogPost::postViewed($id_post);

        $this->context->smarty->assign(array(
            'link_rewrite_'=>SmartBlogPost::getPostSlugById($post['id_post'], $this->context->language->id),
            'layout_details'=>$this->context->shop->theme->getLayoutRelativePathForPage('module-smartblog-details'),
            'post'=>$post,
            'comments'=>$comment,
            'tags'=>$tags,
            'title_category'=>$title_category[0]['meta_title'],
            'cat_link_rewrite'=>$title_category[0]['link_rewrite'],
            'meta_title'=>$post['meta_title'],
            'post_active'=>$post['active'],
            'content'=>$post['content'],
            'id_post'=>$post['id_post'],
            'smartshowauthorstyle'=>Configuration::get('smartshowauthorstyle'),
            'smartshowauthor'=>Configuration::get('smartshowauthor'),
            'created'=>$post['created'],
            'firstname'=>$post['firstname'],
            'lastname'=>$post['lastname'],
            'smartcustomcss' => Configuration::get('smartcustomcss'),
            'smartshownoimg' => Configuration::get('smartshownoimg'),
            'comment_status'=>$post['comment_status'],
            'countcomment'=>$countcomment,
            'post_img'=>$post_img,
            '_report'=>$this->_report,
            'id_category'=>$post['id_category']
        ));
        $this->context->smarty->assign('HOOK_SMART_BLOG_POST_FOOTER', Hook::exec('displaySmartAfterPost'));
        $this->setTemplate('module:smartblog/views/templates/front/posts.tpl');
    }
    public function _posts()
    {
        $SmartBlogPost = new SmartBlogPost();

        if (Tools::isSubmit('addComment')) {
            $id_lang = $this->context->language->id;
            $id_post = Tools::getValue('id_post');
            $post = $SmartBlogPost->getPost($id_post, $id_lang);
            if ($post['comment_status'] == 1) {
                $blogcomment = new Blogcomment();
                $name = Tools::getValue('name');
                $comment = Tools::getValue('comment');
                $mail = Tools::getValue('mail');
                if (Tools::getValue('mail') == '') {
                    $website = '#';
                } else {
                    $website = Tools::getValue('website');
                }

                $id_parent_post = (int)Tools::getValue('id_parent_post');

                $comments = array();

                if (empty($name)) {
                    $this->_report .= '<p class="error">'.$this->module->l('Name is required').'</p>';
                } elseif (empty($comment)) {
                    $this->_report .= '<p class="error">'.$this->module->l('Comment is required').'</p>';
                } elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                    $this->_report .= '<p class="error">'.$this->module->l('E-mail is not valid').'</p>';
                } else {
                    $comments['name'] = $name;
                    $comments['mail'] = $mail;
                    $comments['comment'] = $comment;
                    $comments['website'] = $website;
                    if (!$id_parent_post = Tools::getvalue('comment_parent')) {
                        $id_parent_post = 0;
                    }
                    $value = Configuration::get('smartacceptcomment');
                    if (Configuration::get('smartacceptcomment') != '' && Configuration::get('smartacceptcomment') != null) {
                        $value = Configuration::get('smartacceptcomment');
                    } else {
                        $value = 0;
                    }
                    $bc = new Blogcomment();
                    $bc->id_post = (int)$id_post;
                    $bc->name = $name;
                    $bc->email = $mail;
                    $bc->content = $comment;
                    $bc->website = $website;
                    $bc->id_parent = (int)$id_parent_post;
                    $bc->active = (int)$value;
                    if ($bc->add()) {
                        $this->_report.='<p class="success">'.$this->module->l('Comment added !').'</p>';
                        Hook::exec('actionsbpostcomment', array('bc' => $bc));
                        $this->smartsendMail($name, $mail, $comment);
                    }
                }
            }
        }
    }

    private function smartsendMail($sname, $semailAddr, $scomment, $slink = null)
    {
        $name =  Tools::stripslashes($sname);
        $e_body ='You have Received a New Comment In Your Blog Post From '. $name . '. Comment: '.$scomment.' .Your Can reply Here : '.$slink.'';
        $emailAddr =  Tools::stripslashes($semailAddr);
        $comment =  Tools::stripslashes($scomment);
        $subject =  'New Comment Posted';
        $id_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        $to =  Configuration::get('PS_SHOP_EMAIL');
        $contactMessage = "$comment Name: $name IP: ".((version_compare(_PS_VERSION_, '1.3.0.0', '<'))?$_SERVER['REMOTE_ADDR']:Tools::getRemoteAddr());
        if (Mail::Send(
            $id_lang,
            'contact',
            $subject,
            array(
                '{message}' => nl2br($e_body),
                '{email}' =>  $emailAddr,
            ),
            $to,
            null,
            $emailAddr,
            $name
        )) {
            return true;
        }
    }
}
