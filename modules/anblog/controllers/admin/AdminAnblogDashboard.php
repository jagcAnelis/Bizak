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

require_once _PS_MODULE_DIR_.'anblog/loader.php';
require_once _PS_MODULE_DIR_.'anblog/classes/comment.php';

class AdminAnblogDashboardController extends ModuleAdminController
{

    public function __construct()
    {
        $this->bootstrap = true;
        $this->display = 'view';
        $this->addRowAction('view');
        parent::__construct();
    }

    public function initPageHeaderToolbar()
    {
        parent::initPageHeaderToolbar();

        $this->page_header_toolbar_title = $this->l('Dashboard');
        $this->page_header_toolbar_btn = array();
    }

    public function postProcess()
    {

        if (Tools::isSubmit('saveConfiguration')) {
            $keys = AnblogHelper::getConfigKey(false);
			
		
            $post = array();
            foreach ($keys as $key) {
                $post[$key] = Tools::getValue($key);
            }
            $post['social_code'] = str_replace('"', "'", $post['social_code']);
            $multi_lang_keys = AnblogHelper::getConfigKey(true);
            foreach ($multi_lang_keys as $multi_lang_key) {
                foreach (Language::getIDs(false) as $id_lang) {
                    $post[$multi_lang_key . '_' . (int)$id_lang] = Tools::getValue($multi_lang_key . '_' . (int)$id_lang);
                }
            }
            if (!$this->validateDashboard($post)) {
                return false;
            }
			
	
			
            AnblogConfig::updateConfigValue('cfg_global', serialize($post));
            Configuration::updateValue('link_rewrite', $post['link_rewrite']);
            $this->context->controller->confirmations[] = $this->l('Settings have been updated');
            Configuration::updateValue('ANBLOG_DASHBOARD_DEFAULTTAB', Tools::getValue('ANBLOG_DASHBOARD_DEFAULTTAB'));
        }
    }

    public function validateDashboard($post)
    {
        foreach (Language::getIDs(false) as $id_lang) {
            if (strpbrk($post['meta_keywords_' . $id_lang], '<>;=#{}')) {
                $this->context->controller->errors[] = 'Field meta keywords contains invalid characters';
                return false;
            }
        }
        return true;
    }


    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);
        $this->addJqueryUi('ui.widget');
        $this->addJqueryPlugin('tagify');
        if (file_exists(_PS_THEME_DIR_ . 'js/modules/anblog/views/assets/form.js')) {
            $this->context->controller->addJS(__PS_BASE_URI__ . 'modules/anblog/views/assets/admin/form.js');
        } else {
            $this->context->controller->addJS(__PS_BASE_URI__ . 'modules/anblog/views/js/admin/form.js');
        }
    }

    public function renderView()
    {
/*Configuration::deleteByName('PS_ROUTE_module-anblog-list');
Configuration::deleteByName('PS_ROUTE_module-anblog-blog');
Configuration::deleteByName('PS_ROUTE_module-anblog-category');*/




		$this->context->controller->errors = $this->module->checkIssetImageTypes();
		

		
		
		

        $link = $this->context->link;

        $code = '';
        if (sizeof(Language::getLanguages(true, true)) > 1) {
            $code =$this->context->language->iso_code .  '/';
        }
        $preview = array(
            'title' => $this->l('Open the blog'),
            'icon' => 'icon-eye',
            'target' => '_blank',
            'class' => '',
        );
        if (Configuration::get('PS_REWRITING_SETTINGS')) {
            $preview['href'] = $this->context->shop->getBaseURL(true) . $code . Configuration::get('link_rewrite', 'blog') . '.html';
        } else {
            $helper = AnblogHelper::getInstance();
            $preview['href'] = $helper->getFontBlogLink();
        }

        $onoff = array(
            array(
                'id' => 'indexation_on',
                'value' => 1,
                'label' => $this->l('Enabled')
            ),
            array(
                'id' => 'indexation_off',
                'value' => 0,
                'label' => $this->l('Disabled')
            )
        );

        $url_rss = Tools::htmlentitiesutf8('http://' . $_SERVER['HTTP_HOST'] . __PS_BASE_URI__) . 'modules/anblog/rss.php';

        $this->fields_form[0]['form'] = array(
            'tinymce' => true,
            'input' => array(

                // custom template
                array(
                    'type' => 'hidden',
                    'name' => 'ANBLOG_DASHBOARD_DEFAULTTAB',
                    'default' => '#fieldset_0',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Root Link Title'),
                    'name' => 'blog_link_title',
                    'required' => true,
                    'lang' => true,
                    'default' => 'Blog',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Category'),
                    'name' => 'category_rewrite',
                    'required' => true,
                    'lang' => true,
                    'default' => 'category',
                    'form_group_class' => 'url_use_id_sub url_use_id-0',
                    'desc' => 'Enter a hint word that is displayed in the URL of a category and makes the URL friendly',
                    'hint' => $this->l('Example http://domain/blog/category/name.html'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Post'),
                    'name' => 'detail_rewrite',
                    'required' => true,
                    'lang' => true,
                    'default' => 'detail',
                    'form_group_class' => 'url_use_id_sub url_use_id-0',
                    'desc' => 'Enter a hint word that is displayed in the URL of a post and makes the URL friendly',
                    'hint' => $this->l('Example http://domain/blog/post/name.html'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Root'),
                    'name' => 'link_rewrite',
                    'required' => true,
                    'desc' => $this->l('If necessary, change root of the blog'),
                    'default' => 'blog',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Meta Title'),
                    'name' => 'meta_title',
                    'lang' => true,
                    'cols' => 40,
                    'rows' => 10,
                    'default' => 'Blog',
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Meta description'),
                    'name' => 'meta_description',
                    'lang' => true,
                    'cols' => 40,
                    'rows' => 10,
                    'default' => '',
                    'desk' => $this->l('Display meta descrition on frontpage blog') . 'note: note &lt;&gt;;=#{}'
                ),
                array(
                    'type' => 'tags',
                    'label' => $this->l('Meta keywords'),
                    'name' => 'meta_keywords',
                    'default' => '',
                    'hint' => $this->l('Invalid characters:') . ' &lt;&gt;;=#{}',
                    'lang' => true,
                    'desc' => array(
                        $this->l('To add a keyword, enter the keyword and then press "Enter"')
                    )
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Enable RSS'),
                    'name' => 'indexation',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'default' => '',
                    'values' => $onoff,
                    'desc' => $url_rss
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('RSS Limit Items'),
                    'name' => 'rss_limit_item',
                    'default' => '20',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('RSS Title'),
                    'name' => 'rss_title_item',
                    'default' => 'RSS FEED',
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),

                'class' => 'btn btn-default pull-right'
            )
        );

        $this->fields_form[1]['form'] = array(
            'tinymce' => true,
            'default' => '',
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Category description'),
                    'name' => 'listing_show_categoryinfo',
                    'required' => false,
                    'class' => 't',
                    'desc' => $this->l('Display description of the category in the list of categories'),
                    'is_bool' => true,
                    'default' => '1',
                    'values' => $onoff,
                ),
                array(
                    'type' => 'number',
                    'label' => $this->l('Items limit'),
                    'name' => 'listing_limit_items',
                    'required' => false,
                    'class' => 't',
                    'default' => '6',
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Title'),
                    'name' => 'listing_show_title',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'default' => '1',
                    'values' => $onoff,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Description'),
                    'name' => 'listing_show_description',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'default' => '1',
                    'values' => $onoff,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('"Read more" button'),
                    'name' => 'listing_show_readmore',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'default' => '1',
                    'values' => $onoff,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Image'),
                    'name' => 'listing_show_image',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'default' => '1',
                    'values' => $onoff,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Author'),
                    'name' => 'listing_show_author',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'default' => '0',
                    'values' => $onoff,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Category'),
                    'name' => 'listing_show_category',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'default' => '0',
                    'values' => $onoff,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Date'),
                    'name' => 'listing_show_created',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'default' => '1',
                    'values' => $onoff,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Views'),
                    'name' => 'listing_show_hit',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'default' => '0',
                    'values' => $onoff,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Comments counter'),
                    'name' => 'listing_show_counter',
                    'required' => false,
                    'class' => 't',
                    'default' => '0',
                    'values' => $onoff,
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Posts type'),
                    'name' => 'item_posts_type',
                    'id' => 'item_posts_type',
                    'class' => 'item_posts_type',
                    'options' => array('query' => array(
                        array('id' => 'Type 1', 'name' => $this->l('type1')),
                        array('id' => 'Type 2', 'name' => $this->l('type2')),
                        array('id' => 'Type 3', 'name' => $this->l('type3')),
                    ),
                        'id' => 'id',
                        'name' => 'name'),
                    'default' => 'local'
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            )
        );

        $this->fields_form[2]['form'] = array(
            'tinymce' => true,
            'default' => '',
            'input' => array(

                array(
                    'type' => 'switch',
                    'label' => $this->l('Description'),
                    'name' => 'item_show_description',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'default' => '1',
                    'values' => $onoff,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Image'),
                    'name' => 'item_show_image',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'default' => '',
                    'values' => $onoff,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Author'),
                    'name' => 'item_show_author',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'default' => '1',
                    'values' => $onoff,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Category'),
                    'name' => 'item_show_category',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'default' => '1',
                    'values' => $onoff,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Date'),
                    'name' => 'item_show_created',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'default' => '1',
                    'values' => $onoff,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Views'),
                    'name' => 'item_show_hit',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'default' => '1',
                    'values' => $onoff,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Comments counter'),
                    'name' => 'item_show_counter',
                    'required' => false,
                    'class' => 't',
                    'default' => '1',
                    'values' => $onoff,
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Social Sharing CODE'),
                    'name' => 'social_code',
                    'required' => false,
                    'default' => '',
                    'desc' => 'If you want to replace default social sharing buttons, configure them on https://www.sharethis.com/ and paste their code into the field above'
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Comments list'),
                    'name' => 'item_show_listcomment',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'default' => '1',
                    'values' => $onoff,
                    'desc' => $this->l('Show/Hide the comments list'),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Comment form'),
                    'name' => 'item_show_formcomment',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'default' => '1',
                    'values' => $onoff,
                    'desc' => $this->l('This option is compatible only with local comments engine'),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Comments Engine'),
                    'name' => 'item_comment_engine',
                    'id' => 'item_comment_engine',
                    'class' => 'engine_select',
                    'options' => array('query' => array(
                        array('id' => 'local', 'name' => $this->l('Local')),
                        array('id' => 'facebook', 'name' => $this->l('Facebook')),
                        array('id' => 'diquis', 'name' => $this->l('Disqus')),
                    ),
                        'id' => 'id',
                        'name' => 'name'),
                    'default' => 'local'
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Enable reCAPTCHA  '),
                    'name' => 'google_captcha_status',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't local comment_item',
                    'default' => '1',
                    'values' => $onoff,
                    'desc' => html_entity_decode('&lt;a target=&#x22;_blank&#x22;  href=&quot;https://www.google.com/recaptcha/admin&quot;&gt;Register google reCAPTCHA &lt;/a&gt;')
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('reCAPTCHA site key'),
                    'name' => 'google_captcha_site_key',
                    'required' => false,
                    'class' => 't local comment_item',
                    'default' => '',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('reCAPTCHA secret key'),
                    'name' => 'google_captcha_secret_key',
                    'required' => false,
                    'default' => '',
                    'class' => 't local comment_item',
                ),
                array(
                    'type' => 'number',
                    'label' => $this->l('Comments limit'),
                    'name' => 'item_limit_comments',
                    'required' => false,
                    'class' => 't local comment_item',
                    'default' => '10',
                    'desc' => $this->l('This option is compatible only with local comments engine'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Disqus Account'),
                    'name' => 'item_diquis_account',
                    'required' => false,
                    'class' => 't diquis comment_item',
                    'default' => 'demo4antheme',
                    'desc' => html_entity_decode('Enter the name of your Disqus account (for example anvanto-com). You can copy the name from the address page in your account: for example, the URL is anvanto-com.disqus.com/admin, then copy the text before the first dot. If you have no Disqus account, &lt;a target=&quot;_blank&quot; href=&quot;https://disqus.com/admin/signup/&quot;&gt;sign up here&lt;/a&gt;')
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Facebook Application ID'),
                    'name' => 'item_facebook_appid',
                    'required' => false,
                    'class' => 't facebook comment_item',
                    'default' => '100858303516',
                    'desc' => html_entity_decode('&#x3C;a target=&#x22;_blank&#x22; href=&#x22;http://developers.facebook.com/docs/reference/plugins/comments/&#x22;&#x3E;' . $this->l('Register a comment box') . '&#x3C;/a&#x3E;' .  ' then enter your site URL into the Comments Plugin Code Generator and then press the "Get code" button. Copy the appId from the code and paste it into the field above.')
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Facebook Width'),
                    'name' => 'item_facebook_width',
                    'required' => false,
                    'class' => 't facebook comment_item',
                    'default' => '600'
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            )
        );


        $this->fields_form[3]['form'] = array(
            'tinymce' => true,
            'default' => '',
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Enable in blog'),
                    'name' => 'show_in_blog',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'default' => '0',
                    'values' => array(
                        array(
                            'id' => 'show_in_blog_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'show_in_blog_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Enable on post page'),
                    'name' => 'show_in_post',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'default' => '0',
                    'values' => array(
                        array(
                            'id' => 'show_in_post_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'show_in_post_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
                array(
                    'type' => 'number',
                    'label' => $this->l('Recent posts limit'),
                    'name' => 'limit_recent_blog',
                    'default' => '5',
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            )
        );
		
        $obj = new anblogcat();
        $obj->getTree();
        $menus = $obj->getDropdown(null, $obj->id_parent, false);
        array_shift($menus);		
		
		$itemHome['-'] = ['id'=>'', 'title' => '-', 'selected' => ''];
		$menus = array_merge($itemHome, $menus);		

        $this->fields_form[4]['form'] = array(
            'tinymce' => true,
            'default' => '',
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display Home'),
                    'name' => 'show_in_DisplayHome',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'default' => '0',
                    'values' => array(
                        array(
                            'id' => 'show_in_DisplayHome_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'show_in_DisplayHome_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),
				
				
				array(
					'type' => 'select',
					'label' => $this->l('Category'),
					'name' => 'categories_DisplayHome_blog',
					'options' => array('query' => $menus,
						'id' => 'id',
						'name' => 'title'),
					'default' => '',
				),				
				
                array(
                    'type' => 'number',
                    'label' => $this->l('Display Home posts limit'),
                    'name' => 'limit_DisplayHome_blog',
                    'default' => '6',
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            )
        );

        $data = AnblogConfig::getConfigValue('cfg_global');

        $obj = new stdClass();

        if ($data && $tmp = unserialize($data)) {
            foreach ($tmp as $key => $value) {
                // validate module
                $obj->{$key} = $value;
            }
        }

        $fields_value = $this->getConfigFieldsValues($obj);
        $helper = new HelperForm($this);

        $this->setHelperDisplay($helper);
        $helper->fields_value = $fields_value;
        $helper->tpl_vars = $this->tpl_form_vars;
        !is_null($this->base_tpl_form) ? $helper->base_tpl = $this->base_tpl_form : '';
        if ($this->tabAccess['view']) {
            $helper->tpl_vars['show_toolbar'] = false;
            $helper->tpl_vars['submit_action'] = 'saveConfiguration';
            if (Tools::getValue('back')) {
                $helper->tpl_vars['back'] = Tools::getValue('back');
            } else {
                $helper->tpl_vars['back'] = '';
            }
        }
        $form = $helper->generateForm($this->fields_form);
        $template = $this->createTemplate('panel.tpl');

        $comments = AnblogComment::getComments(null, 10, $this->context->language->id);
        $blogs = AnblogBlog::getListBlogs(null, $this->context->language->id, 0, 10, 'hits', 'DESC');
        $template->assign(
            array(
                'preview' => $preview,
                'showed' => 1,
                'comment_link' => $link->getAdminLink('AdminAnblogComments'),
                'blog_link' => $link->getAdminLink('AdminAnblogBlogs'),
                'blogs' => $blogs,
                'count_blogs' => AnblogBlog::countBlogs(null, $this->context->language->id),
                'count_cats' => Anblogcat::countCats(),
                'count_comments' => AnblogComment::countComments(),
                'latest_comments' => $comments,
                'globalform' => $form,
                'default_tab' => Configuration::get('ANBLOG_DASHBOARD_DEFAULTTAB')
            )
        );
        return $template->fetch();
    }

    /**
     * Asign value for each input of Data form
     */
    public function getConfigFieldsValues($obj)
    {
        $languages = Language::getLanguages(false);
        $fields_values = array();

        foreach ($this->fields_form as $k => $f) {
            foreach ($f['form']['input'] as $j => $input) {
                if (isset($input['lang'])) {
                    foreach ($languages as $lang) {
                        if (isset($obj->{trim($input['name']) . '_' . $lang['id_lang']})) {
                            $data = $obj->{trim($input['name']) . '_' . $lang['id_lang']};
                            $fields_values[$input['name']][$lang['id_lang']] = $data;
                        } else {
                            $fields_values[$input['name']][$lang['id_lang']] = Tools::getValue($input['name'] . '_' . $lang['id_lang'], $input['default']);
                        }
                    }
                } else {
                    if (isset($obj->{trim($input['name'])})) {
                        $data = $obj->{trim($input['name'])};
					

                        if ($input['name'] == 'image' && $data) {
                            $thumb = __PS_BASE_URI__ . 'modules/' . $this->name . '/views/img/' . $data;
                            $this->fields_form[$k]['form']['input'][$j]['thumb'] = $thumb;
                        }
                        if ($input['name'] == 'social_code') {
                            $fields_values[$input['name']] = html_entity_decode($data);
                        } else {
                            $fields_values[$input['name']] = $data;
                        }
                    } else {
                        // validate module
                        $fields_values[$input['name']] = Tools::getValue($input['name'], $input['default']);
                    }
                }
            }
        }

        $fields_values['ANBLOG_DASHBOARD_DEFAULTTAB'] = Tools::getValue('ANBLOG_DASHBOARD_DEFAULTTAB', Configuration::get('ANBLOG_DASHBOARD_DEFAULTTAB'));
        return $fields_values;
    }
}
