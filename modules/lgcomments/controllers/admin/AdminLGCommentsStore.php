<?php
/**
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 * @author    Línea Gráfica E.C.E. S.L.
 * @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 * @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *            https://www.lineagrafica.es/licenses/license_es.pdf
 *            https://www.lineagrafica.es/licenses/license_fr.pdf
 */

require_once(
    _PS_MODULE_DIR_ . DIRECTORY_SEPARATOR . 'lgcomments' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR .
    'LGStoreComment.php'
);

class AdminLGCommentsStoreController extends ModuleAdminController
{
    protected $position_identifier = 'id_storecomment';

    public function __construct()
    {
        include_once(
            _PS_MODULE_DIR_.'lgcomments'.DIRECTORY_SEPARATOR.
            'classes'.DIRECTORY_SEPARATOR.'LGStoreComment.php'
        );
        $this->position_identifier = LGStoreComment::$definition['primary'];
        parent::__construct();
        $this->context = Context::getContext();
        $this->id_language = (int)$this->context->language->id;
        $this->bootstrap = true;
        $this->table = LGStoreComment::$definition['table'];
        $this->identifier = LGStoreComment::$definition['primary'];
        $this->lang = false;
        $this->className = 'LGStoreComment';
        $this->deleted = false;
        $this->_defaultOrderBy = 'position';
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'icon' => 'icon-trash',
                'confirm' => $this->l('Delete selected items?')
            )
        );
        $this->allow_export = true;
        $languages_array = array();
        $this->languages = Language::getLanguages(false, (int)$this->context->shop->id);
        foreach ($this->languages as $language) {
            $languages_array[$language['id_lang']] = $language['name'];
        }
        $this->fields_list = array(
            LGStoreComment::$definition['primary'] => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs',
                'width' => '80',
                // 'filter_key' => 'a!'.LGStoreComment::$definition['primary'],
            ),
            'date' => array(
                'title' => $this->l('Date'),
                'filter_key' => 'a!date',
                'width' => '120',
                'type' => 'date'
            ),
            'reference' => array(
                'title' => $this->l('Ref. Order'),
                'filter_key' => 'o!reference',
                'width' => '100'
            ),
            'nick' => array(
                'title' => $this->l('Nick'),
                'filter_key' => 'nick',
                'search' => true,
                'orderby' => false,
                'width' => '150',
                'tmpTableFilter' => true,
            ),
            'stars' => array(
                'title' => $this->l('Rating (/10)'),
                'align' => 'center',
                'width' => '80',
            ),
            'title' => array(
                'title' => $this->l('Title'),
                'filter_key' => 'a!title',
                'align' => 'center',
                'type' => 'text',
            ),
            'comment' => array(
                'title' => $this->l('Review'),
                'filter_key' => 'a!comment',
                'align' => 'center',
                'callback' => 'stripTags',
            ),
            'answer' => array(
                'title' => $this->l('Answer'),
                'filter_key' => 'a!answer',
                'align' => 'center',
                'callback' => 'stripTags',
            ),
            'active' => array(
                'title' => $this->l('Status'),
                'type' => 'bool',
                'active' => 'active',
                'width' => '80',
                'order_key' => 'active',
                'filter_key' => 'a!active',
                'align' => 'center',
            ),
            'position' => array(
                'title' => $this->l('Position'),
                'width' => '80',
                'filter_key' => 'a!position',
                'align' => 'center',
                'position' => 'position',
            ),
            'lang_name' => array(
                'title' => $this->l('Language'),
                'filter_key' => 'a!id_lang',
                'type' => 'select',
                'list' => $languages_array,
                'align' => 'center',
                'width' => '80',
            ),
        );
        $this->addRowAction('edit');
        $this->addRowAction('ViewPage');
        $this->addRowAction('delete');
        $this->_select .= 'l.name as lang_name, o.reference';
        $this->_join .= ' LEFT JOIN `'._DB_PREFIX_.'orders` o ON (a.`id_order` = o.`id_order`) ';
        $this->_join .= ' LEFT JOIN `'._DB_PREFIX_.'lang` l ON (a.`id_lang` = l.`id_lang`) ';
    }

    public function displayViewPageLink()
    {
        $link = new Link();
        $reviewpage = $link->getModuleLink('lgcomments', 'reviews');

        $this->context->smarty->assign(
            array(
                'reviewpage' => $reviewpage,
            )
        );

        $out = $this->context->smarty->fetch(
            _PS_MODULE_DIR_ . 'lgcomments'
            . DIRECTORY_SEPARATOR . 'views'
            . DIRECTORY_SEPARATOR . 'templates'
            . DIRECTORY_SEPARATOR . 'admin'
            . DIRECTORY_SEPARATOR . '_partials'
            . DIRECTORY_SEPARATOR . 'displayViewPageLink.tpl'
        );

        return $out;
    }

    public function getConfigFormValues()
    {
        $obj = $this->loadObject();
        if (!$obj) {
            return array();
        } else {
            $out = array();
            $out['active'] = $obj->active;
            $out['id_customer'] = $obj->id_customer;
            $out['date'] = $obj->date;
            $out['id_order'] = $obj->id_order;
            $out['position'] = $obj->position;
            $out['id_lang'] = $obj->id_lang; // LGStoreComment instance, id_lang is public
            $out['comment'] = $obj->comment;
            $out['stars'] = $obj->stars;
            $out['nick'] = $obj->nick;
            $out['title'] = $obj->title;
            $out['answer'] = $obj->answer;
            $this->tpl_form_vars = $out;
        }
        return $out;
    }

    private function getP()
    {
        $default_lang = $this->context->language->id;
        $lang         = Language::getIsoById($default_lang);
        $pl           = array('es','fr');
        if (!in_array($lang, $pl)) {
            $lang = 'en';
        }
        $this->context->controller->addCSS(_MODULE_DIR_.'lgcomments/views/css/publi/style.css');
        $base = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ?
            'https://'.$this->context->shop->domain_ssl :
            'http://'.$this->context->shop->domain);
        if (version_compare(_PS_VERSION_, '1.5.0', '>')) {
            $uri = $base.$this->context->shop->getBaseURI();
        } else {
            $uri = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ?
                    'https://'._PS_SHOP_DOMAIN_SSL_DOMAIN_:
                    'http://'._PS_SHOP_DOMAIN_).__PS_BASE_URI__;
        }
        $path = _PS_MODULE_DIR_.'lgcomments'
            .DIRECTORY_SEPARATOR.'views'
            .DIRECTORY_SEPARATOR.'publi'
            .DIRECTORY_SEPARATOR.$lang
            .DIRECTORY_SEPARATOR.'index.php';
        $object = Tools::file_get_contents($path);
        $object = str_replace('src="/modules/', 'src="'.$uri.'modules/', $object);

        return $object;
    }

    public function renderList()
    {
        return $this->getP().parent::renderList();
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);
        $this->addJquery();
        $this->addCSS(_PS_MODULE_DIR_.'lgcomments/views/css/publi/style.css');
    }

    public function initToolbar()
    {
        switch ($this->display) {
            case 'add':
            case 'edit':
                // Default save button - action dynamically handled in javascript
                $this->toolbar_btn['save'] = array(
                    'href' => '#',
                    'desc' => $this->l('Save')
                );
                $back = Tools::safeOutput(pSQL(Tools::getValue('back', '')));
                if (empty($back)) {
                    $back = self::$currentIndex.'&token='.$this->token;
                }
                if (!Validate::isCleanHtml($back)) {
                    die(Tools::displayError());
                }
                if (!$this->lite_display) {
                    $this->toolbar_btn['cancel'] = array(
                        'href' => $back,
                        'desc' => $this->l('Cancel')
                    );
                }
                break;
            case 'delete':
                Db::getInstance()->delete(
                    _DB_PREFIX_.LGStoreComment::$definition['table'],
                    LGStoreComment::$definition['primary'] . " = "
                    . (int)Tools::getValue(LGStoreComment::$definition['primary'])
                );
                break;
            case 'view':
                // Default cancel button - like old back link
                $back = Tools::safeOutput(pSQL(Tools::getValue('back', '')));
                if (empty($back)) {
                    $back = self::$currentIndex.'&token='.$this->token;
                }
                if (!Validate::isCleanHtml($back)) {
                    die(Tools::displayError());
                }
                if (!$this->lite_display) {
                    $this->toolbar_btn['back'] = array(
                        'href' => $back,
                        'desc' => $this->l('Back to the list')
                    );
                }
                break;
            case 'options':
                $this->toolbar_btn['save'] = array(
                    'href' => '#',
                    'desc' => $this->l('Save')
                );
                break;
            default:
                if ($this->allow_export) {
                    $this->toolbar_btn['export'] = array(
                        'href' => self::$currentIndex.'&amp;export'.$this->table.'&amp;token='.$this->token,
                        'desc' => $this->l('Export')
                    );
                }
        }
        unset($this->toolbar_btn['new']);
    }

    public function ajaxProcessUpdatePositions()
    {
        $way = (int)Tools::getValue('way');
        $id_storecomment = (int)Tools::getValue('id');
        $positions = Tools::getValue('lgcomments_storecomments'); // ESCAPED BELOW
        if (is_array($positions)) {
            foreach ($positions as $position => $value) {
                $pos = explode('_', $value);
                if (isset($pos[2]) && (int)$pos[2] === $id_storecomment) {
                    if (isset($position) && $this->updatePosition((int)$way, (int)$position, $id_storecomment)) {
                        echo 'ok position '.(int)$position.' for comment '.(int)$pos[1].'\r\n';
                    } else {
                        echo '{"hasError" : true, "errors" : "Can not update comment ';
                        echo ''.(int)$id_storecomment.' to position '.(int)$position.' "}';
                    }
                    break;
                }
            }
        }
    }
    
    public function updatePosition($way, $position, $id)
    {
        if (!$res = Db::getInstance()->executeS(
            'SELECT `' . LGStoreComment::$definition['primary'] . '`, `position`
            FROM `' . _DB_PREFIX_ . LGStoreComment::$definition['table'] . '`
            ORDER BY `position` ASC'
        )) {
            return false;
        }

        foreach ($res as $storecomments) {
            if ((int)$storecomments[LGStoreComment::$definition['primary']] == (int)$id) {
                $moved_storecomments = $storecomments;
            }
        }

        if (!isset($moved_storecomments) || !isset($position)) {
            return false;
        }
        //var_dump($moved_storecomments['position']);
        return (Db::getInstance()->execute(
            'UPDATE `'._DB_PREFIX_.LGStoreComment::$definition['table'].'`
            SET `position`= `position` '.($way ? '- 1' : '+ 1').'
            WHERE `position`
            '.($way
                ? '> '.(int)$moved_storecomments['position'].' AND `position` <= '.(int)$position
                : '< '.(int)$moved_storecomments['position'].' AND `position` >= '.(int)$position.'
            ')
        )
        && Db::getInstance()->execute('
            UPDATE `' . _DB_PREFIX_ . LGStoreComment::$definition['table'] . '`
            SET `position` = '.(int)$position.'
            WHERE `' . LGStoreComment::$definition['primary'] . '` = '
                . (int)$moved_storecomments[LGStoreComment::$definition['primary']]));
    }

    public function renderForm()
    {
        if (substr_count(_PS_VERSION_, '1.6') > 0) {
            $type = 'switch';
        } else {
            $type = 'radio';
        }

        $this->addjQueryPlugin(array(
            'date',
        ));
        $this->initToolbar();
        $path = _PS_MODULE_DIR_.$this->module->name.'/views/js/jquery-ui.js';
        $this->context->controller->addJS($path);
        $this->context->controller->addJqueryPlugin('ui.tooltip', null, true);
        $this->context->controller->addCSS(_PS_MODULE_DIR_.$this->module->name.'/views/css/back.css');
        $this->fields_form['input'] = array(
            array(
                'type' => $type,
                'label' => $this->l('Status'),
                'name' => 'active',
                'required' => false,
                'class' => 't',
                'bool' => 'true',
                'is_bool' => true,
                'values' => array(
                    array(
                        'id' => 'active_on',
                        'value' => 1,
                        'label' => $this->l('Enabled')
                    ),
                    array(
                        'id' => 'active_off',
                        'value' => 0,
                        'label' => $this->l('Disabled')
                    )
                ),
            ),
            array(
                'type' => 'text',
                'label' => $this->l('Position'),
                'name' => 'position',
                'col' => '1',
                'required' => true,
            ),
            array(
                'type' => 'select',
                'label' => $this->l('Rating'),
                'name' => 'stars',
                'required' => true,
                'col' => '1',
                'options' => array(
                    'query' => array(
                        array('key' => '0', 'name' => '0/10'),
                        array('key' => '1', 'name' => '1/10'),
                        array('key' => '2', 'name' => '2/10'),
                        array('key' => '3', 'name' => '3/10'),
                        array('key' => '4', 'name' => '4/10'),
                        array('key' => '5', 'name' => '5/10'),
                        array('key' => '6', 'name' => '6/10'),
                        array('key' => '7', 'name' => '7/10'),
                        array('key' => '8', 'name' => '8/10'),
                        array('key' => '9', 'name' => '9/10'),
                        array('key' => '10', 'name' => '10/10'),
                    ),
                    'id' => 'key',
                    'name' => 'name'
                )
            ),
            array(
                'type' => 'select',
                'label' => $this->l('Language'),
                'name' => 'id_lang',
                'required' => true,
                'options' => array(
                    'query' => Language::getLanguages(false, $this->context->shop->id),
                    'id' => 'id_lang',
                    'name' => 'name',
                ),
            ),
            array(
                'type' => 'date',
                'label' => $this->l('Date'),
                'name' => 'date',
                'size' => '12',
                'required' => true,
                'col' => '6',
            ),
            array(
                'type' => 'text',
                'label' => $this->l('Nick'),
                'name' => 'nick',
                'col' => '2',
                'required' => true,
            ),
            array(
                'type' => 'text',
                'label' => $this->l('Title'),
                'name' => 'title',
                'col' => '2',
                'required' => true,
            ),
            array(
                'type' => 'textarea',
                'autoload_rte' => 'true',
                'label' => $this->l('Review'),
                'name' => 'comment',
                'cols' => '30',
                'rows' => '5',
                'required' => true,
            ),
            array(
                'type' => 'textarea',
                'autoload_rte' => 'true',
                'label' => $this->l('Answer'),
                'name' => 'answer',
                'cols' => '30',
                'rows' => '3',
                'required' => false,
            ),
            array(
                'type' => 'checkbox',
                'name' => 'sendemail',
                'values' => array(
                    'query' => array(
                        array(
                            'id' => 'on',
                            'name' => $this->l('Send answer by email to the customer'),
                            'val' => '1'
                        ),
                    ),
                    'id' => 'id',
                    'name' => 'name'
                )
            ),
            array(
                'type' => 'hidden',
                'name' => 'id_customer',
            ),
            array(
                'type' => 'hidden',
                'name' => 'id_order',
            ),
        );
        $this->fields_form['submit'] = array(
            'title' => $this->l('Save'),
            'name' => 'submitAdd'.$this->table,
        );
        $this->getConfigFormValues();
        $params = array();
        $params['link'] = $this->context->link;
        $params['ssl'] = (int)Configuration::get('PS_SSL_ENABLED_EVERYWHERE');
        $this->context->smarty->assign($params);
        return $this->getP().parent::renderForm();
    }

    public function renderView()
    {
        $this->renderList();
    }

    public function postProcess()
    {
        parent::postProcess();
        if (Tools::getIsset('activelgcomments_storecomments')) {
            $this->processStatus();
        }
        $data = Db::getInstance()->getRow(
            'SELECT * '.
            'FROM '._DB_PREFIX_.'customer '.
            'WHERE id_customer = '.(int)Tools::getValue('id_customer')
        );
        $idlang = (int)Tools::getValue('id_lang');
        $link = new Link();
        $urlshop = $link->getModuleLink('lgcomments', 'reviews');
        $templateVars = array(
            '{object}' => Configuration::get('PS_SHOP_NAME'),
            '{firstname}' => $data['firstname'],
            '{lastname}' => $data['lastname'],
            '{stars}' => (int)Tools::getValue('stars'),
            '{title}' => pSQL(Tools::getValue('title')),
            '{comment}' => Tools::getValue('comment'),
            '{answer}' => Tools::getValue('answer'),
            '{storename}' => Configuration::get('PS_SHOP_NAME'),
            '{link}' => $urlshop
        );
        $langs = Db::getInstance()->ExecuteS('SELECT * FROM '._DB_PREFIX_.'lang');
        foreach ($langs as $lang) {
            if ($idlang == $lang['id_lang']) {
                $subject3 = Configuration::get('PS_LGCOMMENTS_SUBJECT3'.$lang['iso_code']);
            }
        }
        // Check if email template exists for current iso code. If not, use English template.
        $module_path = _PS_MODULE_DIR_.'lgcomments/mails/'.Language::getIsoById($idlang).'/';
        $template_path = _PS_THEME_DIR_.'modules/lgcomments/mails/'.Language::getIsoById($idlang).'/';
        if (is_dir($module_path) or is_dir($template_path)) {
            $langId = $idlang;
        } else {
            $langId = (int)Language::getIdByIso('en');
        }
        if ((int)Tools::getValue('sendemail_on') == 1) {
            return Mail::Send(
                (int)$langId,
                'send-answer',
                $subject3,
                $templateVars,
                $data['email'],
                null,
                null,
                Configuration::get('PS_SHOP_NAME'),
                null,
                null,
                _PS_MODULE_DIR_.'lgcomments/mails/'
            );
        }
    }

    public function processStatus()
    {
        if ($this->toggleStatus((int)Tools::getValue(LGStoreComment::$definition['primary'], 0))) {
            $matches = array();
            if (preg_match('/[\?|&]controller=([^&]*)/', (string)$_SERVER['HTTP_REFERER'], $matches) !== false
                &&
                Tools::strtolower($matches[1]) != Tools::strtolower(preg_replace('/controller/i', '', get_class($this)))
            ) {
                $this->redirect_after = preg_replace('/[\?|&]conf=([^&]*)/i', '', (string)$_SERVER['HTTP_REFERER']);
            } else {
                $this->redirect_after = self::$currentIndex.'&token='.$this->token;
            }
        } else {
            $this->errors[] = Tools::displayError('An error occurred while updating the status.');
        }
    }

    public function toggleStatus($id)
    {
        $params = array();
        $query = new DBQuery();
        $query->select('active');
        $query->from($this->table);
        $query->where($this->identifier.' = '.(int)$id);
        $status = (bool)DB::getInstance()->getValue($query);
        $status = !$status;
        $params['active'] = (int)$status;
        return DB::getInstance()->update($this->table, $params, $this->identifier.' = '.(int)$id);
    }

    public function l($string, $moduleclass = 'adminlgcommentsproducts', $addslashes = false, $htmlentities = true)
    {
        if (!$this->module instanceof Module) {
            $this->module = Module::getInstanceByName('lgcomments');
        }
        if (version_compare(_PS_VERSION_, '1.7.6', '<')) {
            return $this->module->l($string, $moduleclass, $addslashes, $htmlentities);
        } else {
            return $this->module->l($string);
        }
    }

    public function stripTags($string)
    {
        return strip_tags($string);
    }
}
