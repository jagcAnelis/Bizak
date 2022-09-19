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
    _PS_MODULE_DIR_ .
    DIRECTORY_SEPARATOR .
    'lgcomments' .
    DIRECTORY_SEPARATOR .
    'classes' .
    DIRECTORY_SEPARATOR .
    'LGStoreComment.php'
);
require_once(
    _PS_MODULE_DIR_ .
    DIRECTORY_SEPARATOR .
    'lgcomments' .
    DIRECTORY_SEPARATOR .
    'classes' .
    DIRECTORY_SEPARATOR .
    'LGProductComment.php'
);
require_once(
    _PS_MODULE_DIR_ .
    DIRECTORY_SEPARATOR .
    'lgcomments' .
    DIRECTORY_SEPARATOR .
    'classes' .
    DIRECTORY_SEPARATOR .
    'LGMailer.php'
);

require_once(
    _PS_MODULE_DIR_ .
    DIRECTORY_SEPARATOR .
    'lgcomments' .
    DIRECTORY_SEPARATOR .
    'classes' .
    DIRECTORY_SEPARATOR .
    'LGUtils.php'
);


class LGCommentsAccountModuleFrontController extends ModuleFrontController
{
    public $ssl = true;

    public function initContent()
    {
        parent::initContent();
        $id_order = (int)Tools::getValue('id_order');
        $lghash   = pSQL(Tools::getValue('lghash'));

        if ((empty($lghash) || !$this->hashExists($id_order, $lghash)) && Tools::getValue('source') == 'account') {
            $lghash = $this->generateHash();
            $this->saveCommentOrder($id_order, (int)$this->context->customer->id, $lghash);
        }

        if ($this->hashExists($id_order, $lghash)) {
            if (!$this->hasVoted($id_order, $lghash)) {
                $order_data       = $this->getOrderData($id_order);
                $shop_details     = '';
                $verify           = 1;
                $voted            = 0;
                $errors_store     = LGStoreComment::validate();
                $errors_products  = LGProductComment::validate($order_data['products']);
                $data             = empty($_POST) ? array() : $_POST;

                if ((int)Tools::getValue('sendcomments') && empty($errors_store) && empty($errors_products)) {

                    if (LGStoreComment::allowStoreComments()) {
                        $nick = preg_replace(
                            "/\r\n|\r|\n/",
                            '',
                            Tools::getValue('lg_nick', '')
                        );
                        $title = preg_replace(
                            "/\r\n|\r|\n/",
                            '<br>',
                            Tools::getValue('title_store', '')
                        );
                        $comment = preg_replace(
                            "/\r\n|\r|\n/",
                            '<br>',
                            Tools::getValue('comment_store', '')
                        );
                        $store_comment              = new LGStoreComment();
                        $store_comment->id_order    = (int)$id_order;
                        $store_comment->id_customer = (int)$order_data['id_customer'];
                        $store_comment->id_lang     = (int)$order_data['id_lang'];
                        $store_comment->stars       = (int)Tools::getValue('score_store');
                        $store_comment->nick        = pSQL(stripslashes($nick), true);
                        $store_comment->title       = pSQL(stripslashes($title), true);
                        $store_comment->comment     = pSQL(stripslashes($comment), true);
                        $store_comment->active      = LGUtils::commentNeedValidation();
                        $store_comment->position    = LGStoreComment::getLastPosition();
                        $store_comment->date        = date('Y-m-d H:i:s');
                        $store_comment->save();

                        $shop_details = $this->getItemDetails(
                            (int)Tools::getValue('score_store'),
                            pSQL(Tools::getValue('lg_nick', '')),
                            pSQL(Tools::getValue('title_store', '')),
                            pSQL(Tools::getValue('comment_store', ''))
                        );
                    }
                    if (LGProductComment::allowProductsComments()) {
                        $nick = preg_replace(
                            "/\r\n|\r|\n/",
                            '',
                            Tools::getValue('lg_nick')
                        );
                        foreach ($order_data['products'] as $product) {
                            $pcode = $product['id_order_detail'] . '_' . (int)$product['product_id'];
                            $title = preg_replace(
                                "/\r\n|\r|\n/",
                                '<br>',
                                Tools::getValue('product_title_' . $pcode, '')
                            );
                            $comment = preg_replace(
                                "/\r\n|\r|\n/",
                                '<br>',
                                Tools::getValue('product_comment_' . $pcode, '')
                            );
                            $product_comment                       = new LGProductComment();
                            $product_comment->id_product           = (int)$product['product_id'];
                            $product_comment->id_product_attribute = (int)$product['product_attribute_id'];
                            $product_comment->id_customer          = (int)$order_data['id_customer'];
                            $product_comment->id_lang              = $order_data['id_lang'];
                            $product_comment->stars                = (int)Tools::getValue('product_score_' . $pcode);
                            $product_comment->nick                 = pSQL(stripslashes($nick), true);
                            $product_comment->title                = pSQL(stripslashes($title), true);
                            $product_comment->comment              = pSQL(stripslashes($comment), true);
                            $product_comment->active               = LGUtils::commentNeedValidation();
                            $product_comment->position             = LGProductComment::getLastPosition();
                            $product_comment->date                 = date('Y-m-d H:i:s');
                            $product_comment->save();
                        }
                    }

                    $this->setOrderCommentAsVoted($id_order, $lghash);
                    $voted    = 1;
                    $customer = new Customer((int)$order_data['id_customer']);
                    $langs    = Language::getLanguages();
                    foreach ($langs as $lang) {
                        if ($order_data['id_lang'] == $lang['id_lang']) {
                            $subject2 = Configuration::get('PS_LGCOMMENTS_SUBJECT2' . $lang['iso_code']);
                        }
                    }
                    $templateVars = array(
                        '{firstname}' => $customer->firstname,
                        '{lastname}' => $customer->lastname,
                    );
                    // Check if email template exists for current iso code. If not, use English template.
                    $module_path = _PS_MODULE_DIR_ .
                        'lgcomments/mails/' . Language::getIsoById($order_data['id_lang']) . '/';
                    $template_path = _PS_THEME_DIR_ .
                        'modules/lgcomments/mails/' . Language::getIsoById($order_data['id_lang']) . '/';

                    if (is_dir($module_path) or is_dir($template_path)) {
                        $langId = $order_data['id_lang'];
                    } else {
                        $langId = (int)Language::getIdByIso('en');
                    }

                    Mail::Send(
                        (int)$langId,
                        'thank-you',
                        $subject2,
                        $templateVars,
                        $customer->email,
                        null,
                        null,
                        Configuration::get('PS_SHOP_NAME'),
                        null,
                        null,
                        dirname(__FILE__) . '/../../mails/'
                    );

                    $email_cron = Configuration::get('PS_LGCOMMENTS_EMAIL_CRON');
                    $email_alerts = Configuration::get('PS_LGCOMMENTS_EMAIL_ALERTS');
                    if ($email_cron and $email_alerts == 1) {
                        $templateVars = array(
                            '{id_order}'             => $id_order,
                            '{customer_firstname}'   => $customer->firstname,
                            '{customer_lastname}'    => $customer->lastname,
                            '{product_details_html}' => $this->getProuductsDetail($order_data['products'], 'html'),
                            '{product_details_text}' => $this->getProuductsDetail($order_data['products'], 'text'),
                            '{shop_details}'         => $shop_details,
                        );
                        // Check if email template exists for current iso code. If not, use English template.
                        $default        = Configuration::get('PS_LANG_DEFAULT');
                        $module_path2   = _PS_MODULE_DIR_ . 'lgcomments/mails/' . Language::getIsoById($default) . '/';
                        $template_path2 = _PS_THEME_DIR_ .
                            'modules/lgcomments/mails/' . Language::getIsoById($default) . '/';

                        if (is_dir($module_path2) or is_dir($template_path2)) {
                            $langId2 = $default;
                        } else {
                            $langId2 = (int)Language::getIdByIso('en');
                        }

                        Mail::Send(
                            (int)$langId2,
                            'new-review',
                            Configuration::get('PS_LGCOMMENTS_SUBJECT_NEWREVIEWS'),
                            $templateVars,
                            $email_cron,
                            null,
                            null,
                            Configuration::get('PS_SHOP_NAME'),
                            null,
                            null,
                            dirname(__FILE__) . '/../../mails/'
                        );
                    }
                }

                $form_action = $_SERVER['REQUEST_URI'];
                $this->context->smarty->assign(array(
                    'lgcomments_id_module' => $this->module->id,
                    'modules_dir'          => _MODULE_DIR_,
                    'verify'               => $verify,
                    'starstyle'            => Configuration::get('PS_LGCOMMENTS_STARDESIGN1'),
                    'starcolor'            => Configuration::get('PS_LGCOMMENTS_STARDESIGN2'),
                    'starsize'             => Configuration::get('PS_LGCOMMENTS_STARSIZE'),
                    'ratingscale'          => Configuration::get('PS_LGCOMMENTS_SCALE'),
                    'products'             => $order_data['products'],
                    'form_action'          => $form_action,
                    'voted'                => $voted,
                    'opinionform'          => (int)Configuration::get('PS_LGCOMMENTS_OPINION_FORM'),
                    'errors_store'         => $errors_store,
                    'errors_products'      => $errors_products,
                    'data'                 => $data,
                ));

                if (version_compare(_PS_VERSION_, '1.7.0', '>=')) {
                    $this->setTemplate('module:lgcomments/views/templates/front/review_form_17.tpl');
                } else {
                    $this->setTemplate('review_form.tpl');
                }
            } else {
                $verify = 2;
                $this->context->smarty->assign(array(
                    'verify' => $verify,
                ));

                if (version_compare(_PS_VERSION_, '1.7.0', '>=')) {
                    $this->setTemplate('module:lgcomments/views/templates/front/review_form_17.tpl');
                } else {
                    $this->setTemplate('review_form.tpl');
                }
            }
        } else {
            $verify = 0;
            $this->context->smarty->assign(array(
                'verify' => $verify,
            ));

            if (version_compare(_PS_VERSION_, '1.7.0', '>=')) {
                $this->setTemplate('module:lgcomments/views/templates/front/review_form_17.tpl');
            } else {
                $this->setTemplate('review_form.tpl');
            }
        }
    }

    protected function getProuductsDetail($products, $type = 'html')
    {
        if ($type == 'html') {
            $text_only_mode = false;
        } else {
            $text_only_mode = true;
        }

        foreach ($products as $index => $product) {
            $pcode                       = $product['id_order_detail'] . '_' . (int)$product['product_id'];
            $products[$index]['score']   = (int)Tools::getValue('product_score_' . $pcode);
            $products[$index]['lg_nick'] = Tools::getValue('lg_nick', '');
            $products[$index]['title']   = Tools::getValue('product_title_' . $pcode, '');
            $products[$index]['comment'] = Tools::getValue('product_comment_' . $pcode, '');
        }

        $this->context->smarty->assign(
            array(
                'text_only_mode' => $text_only_mode,
                'products'       => $products,
                //'score'   => $score,
                //'nick'    => $nick,
                //'title'   => $title,
                //'comment' => $comment
            )
        );

        return $this->context->smarty->fetch(
            _PS_MODULE_DIR_ .
            'lgcomments' .
            DIRECTORY_SEPARATOR .
            'views' .
            DIRECTORY_SEPARATOR .
            'templates' .
            DIRECTORY_SEPARATOR .
            'front' .
            DIRECTORY_SEPARATOR .
            'products_details.tpl'
        );
    }

    protected function getItemDetails($score, $nick, $title, $comment)
    {
        $this->context->smarty->assign(
            array(
                'score'   => $score,
                'nick'    => $nick,
                'title'   => $title,
                'comment' => $comment
            )
        );
        return $this->context->smarty->fetch(
            _PS_MODULE_DIR_ .
            'lgcomments' .
            DIRECTORY_SEPARATOR .
            'views' .
            DIRECTORY_SEPARATOR .
            'templates' .
            DIRECTORY_SEPARATOR .
            'front' .
            DIRECTORY_SEPARATOR .
            'item_details.tpl'
        );
    }

    public function setMedia()
    {
        if (version_compare(_PS_VERSION_, '1.7.0', '<')) {
            $this->addJquery();
        }
        parent::setMedia();
    }

    private function hashExists($id_order, $lghash)
    {
        $sql = 'SELECT `id_order`
                FROM `' . _DB_PREFIX_ . 'lgcomments_orders`
                WHERE `id_order` = ' . (int)$id_order . '
                AND `hash` = "' . pSQL($lghash) . '"';

        return Db::getInstance()->getValue($sql);
    }

    /**
     * Check if an order has been voted.
     *
     * @param $id_order
     * @param $lghash
     * @return false|null|string
     */
    private function hasVoted($id_order, $lghash)
    {
        $sql = 'SELECT `voted`
                FROM `' . _DB_PREFIX_ . 'lgcomments_orders`
                WHERE `id_order` = ' . (int)$id_order . '
                AND `hash` = "' . pSQL($lghash) . '"';

        return Db::getInstance()->getValue($sql);
    }

    /**
     * Get some order data.
     *
     * @param $id_order
     * @return array|false|mysqli_result|null|PDOStatement|resource
     */
    private function getOrderData($id_order)
    {
        $sql = 'SELECT o.`id_shop`, o.`id_customer`, o.`id_lang`
                FROM `' . _DB_PREFIX_ . 'orders` AS o
                WHERE o.`id_order` = ' . (int)$id_order;

        $order = Db::getInstance()->getRow($sql);

        $sql = 'SELECT *, i.id_image
                FROM `' . _DB_PREFIX_ . 'order_detail` AS od 
                INNER JOIN `' . _DB_PREFIX_ . 'product_lang` AS pl ON (od.`product_id` = pl.`id_product`) 
                INNER JOIN `' . _DB_PREFIX_ . 'image` AS i ON (od.`product_id` = i.`id_product` AND i.`cover` = 1) 
                WHERE od.`id_order` = ' . (int)$id_order . '
                AND pl.`id_lang` = ' . (int)$order['id_lang'] . '
                AND pl.`id_shop` = ' . (int)$order['id_shop'];

        $order['products'] = Db::getInstance()->executeS($sql);

        // Obtenemos la imagen de la combinacion del producto
        foreach ($order['products'] as &$product) {
            if ((int)$product['product_attribute_id'] > 0) {
                $sql = 'SELECT pai.`id_image` 
						FROM `'._DB_PREFIX_.'product_attribute_image` AS pai 
						LEFT JOIN `'._DB_PREFIX_.'image` AS i ON (pai.`id_image` = i.`id_image`)
						WHERE pai.`id_product_attribute` = ' . (int)$product['product_attribute_id'] . '
						ORDER BY i.`position` ASC';
                $id_image = (int)Db::getInstance()->getValue($sql);
            } elseif (!empty($product['id_image'])) {
                $id_image = $product['id_image'];
            }

            // Si no tiene una imagen asociada a la combinacion, se carga la imagen cover del producto
            if (!$id_image) {
                $sql = 'SELECT `id_image` 
                        FROM `'._DB_PREFIX_.'image`
                        WHERE `id_product` = ' . (int)$product['product_id'] . '
                        AND `cover` = 1';
                $id_image = (int)Db::getInstance()->getValue($sql);
            }
            $product['image'] = $this->context->link->getImageLink(
                $product['link_rewrite'],
                $id_image,
                LGUtils::getFormattedName('home')
            );
        }

        return $order;
    }

    /**
     * Mark an order comment as voted.
     *
     * @param $id_order
     * @param $lghash
     */
    public function setOrderCommentAsVoted($id_order, $lghash)
    {
        $sql = 'UPDATE `' . _DB_PREFIX_ . 'lgcomments_orders`
                SET `voted` = 1
                WHERE `id_order` = ' . (int)$id_order . '
                AND `hash` = "' . pSQL($lghash) . '"';

        Db::getInstance()->execute($sql);
    }

    protected function generateHash()
    {
        $number_range = array('min' => 48, 'max' => 57);
        $upper_range  = array('min' => 65, 'max' => 90);
        $lower_range  = array('min' => 97, 'max' => 122);
        $hash         = '';

        for ($i = 0; $i < 59; $i++) {
            switch (rand(0, 2)) {
                case 0:
                    $hash .= chr(rand($number_range['min'], $number_range['max']));
                    break;
                case 1:
                    $hash .= chr(rand($upper_range['min'], $upper_range['max']));
                    break;
                case 2:
                    $hash .= chr(rand($lower_range['min'], $lower_range['max']));
                    break;
            }
        }
        return $hash;
    }

    protected function saveCommentOrder($id_order, $id_customer, $hash, $date = null)
    {
        if (is_null($date)) {
            $date = 'NOW()';
        } else {
            $date = '"'.$date.'"';
        }

        $sql = 'REPLACE INTO `' . _DB_PREFIX_ . 'lgcomments_orders`
                VALUES (
                    ' . (int)$id_order . ',
                    ' . (int)$id_customer . ',
                    "' . pSQL($hash) . '",
                    0,
                    1,
                    NOW(),
                    0
                )';
        return Db::getInstance()->execute($sql);
    }
}
