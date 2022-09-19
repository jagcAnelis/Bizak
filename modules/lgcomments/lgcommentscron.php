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

/* db conex */
include(dirname(__FILE__).'/../../config/config.inc.php');
require(dirname(__FILE__).'/../../init.php');
require_once(dirname(__FILE__).'/lgcomments.php');
$secureKeyGet = Tools::getValue('secureKey');
$secureKey = md5(_COOKIE_KEY_.Configuration::get('PS_SHOP_NAME'));
$module = new LGComments();
$context = Context::getContext();
if (!empty($secureKey) && $secureKey === $secureKeyGet) {
    $dias = (int)Configuration::get('PS_LGCOMMENTS_DIAS');
    $dias2 = (int)Configuration::get('PS_LGCOMMENTS_DIAS2');
    if (Configuration::get('PS_LGCOMMENTS_BOXES') == 2) {
        $boxes_checked = 'AND c.newsletter = 1 ';
    } elseif (Configuration::get('PS_LGCOMMENTS_BOXES') == 3) {
        $boxes_checked = 'AND c.optin = 1 ';
    } elseif (Configuration::get('PS_LGCOMMENTS_BOXES') == 4) {
        $boxes_checked = 'AND c.newsletter = 1 AND c.optin = 1 ';
    } else {
        $boxes_checked = '';
    }
    if ($dias2 < 1) {
        $orders = Db::getInstance()->ExecuteS(
            'SELECT DISTINCT o.id_order, o.id_customer, o.id_cart, lo.date_email, lo.sent, lo.date_email2 '.
            'FROM '._DB_PREFIX_.'orders o '.
            'INNER JOIN '._DB_PREFIX_.'lgcomments_status ek ON o.current_state = ek.id_order_status '.
            'LEFT JOIN '._DB_PREFIX_.'lgcomments_orders lo ON o.id_order = lo.id_order '.
            'RIGHT JOIN '._DB_PREFIX_.'customer_group cg ON o.id_customer = cg.id_customer '.
            'RIGHT JOIN '._DB_PREFIX_.'customer c ON o.id_customer = c.id_customer '.
            'INNER JOIN '._DB_PREFIX_.'lgcomments_customergroups lcg ON cg.id_group = lcg.id_customer_group '.
            'RIGHT JOIN '._DB_PREFIX_.'lgcomments_multistore lm ON o.id_shop = lm.id_shop '.
            'WHERE o.date_add >= DATE_SUB(NOW(),INTERVAL '.(int)$dias.' DAY) '.
            $boxes_checked.
            'ORDER BY o.id_order DESC'
        );
    } else {
        $orders = Db::getInstance()->ExecuteS(
            'SELECT DISTINCT o.id_order, o.id_customer, o.id_cart, lo.date_email, lo.sent, lo.date_email2 '.
            'FROM '._DB_PREFIX_.'orders o '.
            'INNER JOIN '._DB_PREFIX_.'lgcomments_status ek ON o.current_state = ek.id_order_status '.
            'LEFT JOIN '._DB_PREFIX_.'lgcomments_orders lo ON o.id_order = lo.id_order '.
            'RIGHT JOIN '._DB_PREFIX_.'customer_group cg ON o.id_customer = cg.id_customer '.
            'RIGHT JOIN '._DB_PREFIX_.'customer c ON o.id_customer = c.id_customer '.
            'INNER JOIN '._DB_PREFIX_.'lgcomments_customergroups lcg ON cg.id_group = lcg.id_customer_group '.
            'RIGHT JOIN '._DB_PREFIX_.'lgcomments_multistore lm ON o.id_shop = lm.id_shop '.
            'WHERE o.date_add >= DATE_SUB(NOW(),INTERVAL '.(int)$dias.' DAY) '.
            'AND o.date_add <= DATE_SUB(NOW(),INTERVAL '.(int)$dias2.' DAY) '.
            $boxes_checked.
            'ORDER BY o.id_order DESC'
        );
    }
    $enviados = 0;
    $pedidos = '<br>';
    if (count($orders) > 0) {
        foreach ($orders as $order) {
            $preproductos = Db::getInstance()->ExecuteS(
                'SELECT id_product, quantity '.
                'FROM '._DB_PREFIX_.'cart_product '.
                'WHERE id_cart = '.(int)$order['id_cart']
            );
            $productos = '';
            foreach ($preproductos as $preproducto) {
                $nombre = Db::getInstance()->getValue(
                    'SELECT name '.
                    'FROM '._DB_PREFIX_.'product_lang '.
                    'WHERE id_product = '.(int)$preproducto['id_product'].
                    ' AND id_lang = '.(int)$context->language->id
                );
                $productos .= 'product:'.$preproducto['id_product'].'|'.$nombre.'<br>'."\n";
            }
            $datoscliente = Db::getInstance()->ExecuteS(
                'SELECT firstname, lastname, email '.
                'FROM '._DB_PREFIX_.'customer '.
                'WHERE id_customer = '.(int)$order['id_customer']
            );
            foreach ($datoscliente as $datocliente) {
                $range = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $lghash = '';
                for ($i = 0; $i < 59; $i++) {
                    $lghash .= $range{rand(0, 35)};
                }
                $languageId = Db::getInstance()->getValue(
                    'SELECT id_lang '.
                    'FROM '._DB_PREFIX_.'orders '.
                    'WHERE id_order = '.(int)$order['id_order']
                );
                $shopId = Db::getInstance()->getValue(
                    'SELECT id_shop '.
                    'FROM '._DB_PREFIX_.'orders '.
                    'WHERE id_order = '.(int)$order['id_order']
                );
                $link = Context::getContext()->link->getModuleLink(
                    'lgcomments',
                    'account',
                    array('id_order' => $order['id_order'], 'lghash' => $lghash),
                    null,
                    $languageId,
                    $shopId,
                    false
                );
                /* Email generation */
                $templateVars = array(
                    '{firstname}' => $datocliente['firstname'],
                    '{lastname}' => $datocliente['lastname'],
                    '{storename}' => Configuration::get('PS_SHOP_NAME'),
                    '{email}' => $datocliente['email'],
                    '{id_order}' => $order['id_order'],
                    '{link}' => $link,
                    '{productos}' => $productos
                );
                $check = Db::getInstance()->getRow(
                    'SELECT * '.
                    'FROM '._DB_PREFIX_.'lgcomments_orders '.
                    'WHERE id_order = '.(int)$order['id_order']
                );
                $daysafter = (int)Configuration::get('PS_LGCOMMENTS_DAYS_AFTER');
                $check2 = Db::getInstance()->getRow(
                    'SELECT * '.
                    'FROM '._DB_PREFIX_.'lgcomments_orders '.
                    'WHERE id_order = '.(int)$order['id_order'].
                    ' AND voted < 1'.
                    ' AND sent < 2'.
                    ' AND date_email <= DATE_SUB(NOW(),INTERVAL '.$daysafter.' DAY)'
                );
                $sendtwice = Configuration::get('PS_LGCOMMENTS_EMAIL_TWICE');
                $langs = Db::getInstance()->ExecuteS('SELECT * FROM '._DB_PREFIX_.'lang');
                foreach ($langs as $lang) {
                    if ($languageId == $lang['id_lang']) {
                        $subject = Configuration::get('PS_LGCOMMENTS_SUBJECT'.$lang['iso_code']);
                    }
                }
                // Check if email template exists for current iso code. If not, use English template.
                $module_path = _PS_MODULE_DIR_.'lgcomments/mails/'.Language::getIsoById($languageId).'/';
                $template_path = _PS_THEME_DIR_.'modules/lgcomments/mails/'.Language::getIsoById($languageId).'/';
                if (is_dir($module_path) or is_dir($template_path)) {
                    $langId = $languageId;
                } else {
                    $langId = (int)Language::getIdByIso('en');
                }
                if (!$check) {
                    if (Mail::Send(
                        $langId,
                        'opinion-request',
                        $subject,
                        $templateVars,
                        $datocliente['email'],
                        null,
                        null,
                        Configuration::get('PS_SHOP_NAME'),
                        null,
                        null,
                        dirname(__FILE__).'/mails/'
                    )) {
                        Db::getInstance()->Execute(
                            'INSERT INTO '._DB_PREFIX_.'lgcomments_orders '.
                            'VALUES (
                                '.(int)$order['id_order'].',
                                '.(int)$order['id_customer'].',
                                NOW(),
                                \''.pSQL($lghash).'\',
                                0,
                                1,
                                0
                            )'
                        );
                        $enviados++;
                        $pedidos .= $order['id_order'].'<br>';
                        echo '<span style="font-family:arial; font-weight:bold; font-size:14px;">';
                        echo $module->l('Order', 'lgcommentscron').' #'.$order['id_order'].': ';
                        echo $module->l('Email sent (first time)', 'lgcommentscron');
                        echo '</span><br>';
                    } else {
                        echo '<span style="font-family:arial; font-size:14px;">';
                        echo $module->l('Order', 'lgcommentscron').' #'.$order['id_order'].': ';
                        echo $module->l('Email not sent: problem with your email configuration', 'lgcommentscron');
                        echo '</span><br>';
                    }
                } elseif ($sendtwice and $check2) {
                    $getHash = Db::getInstance()->getValue(
                        'SELECT hash '.
                        'FROM '._DB_PREFIX_.'lgcomments_orders '.
                        'WHERE id_order = '.$order['id_order']
                    );
                    $getLink = Context::getContext()->link->getModuleLink(
                        'lgcomments',
                        'account',
                        array('id_order' => $order['id_order'], 'lghash' => $getHash),
                        null,
                        $languageId,
                        $shopId,
                        false
                    );
                    $templateVars2 = array(
                        '{firstname}' => $datocliente['firstname'],
                        '{lastname}' => $datocliente['lastname'],
                        '{storename}' => Configuration::get('PS_SHOP_NAME'),
                        '{email}' => $datocliente['email'],
                        '{id_order}' => $order['id_order'],
                        '{link}' => $getLink,
                        '{productos}' => $productos
                    );
                    if (Mail::Send(
                        $langId,
                        'opinion-request',
                        $subject,
                        $templateVars2,
                        $datocliente['email'],
                        null,
                        null,
                        Configuration::get('PS_SHOP_NAME'),
                        null,
                        null,
                        dirname(__FILE__).'/mails/'
                    )) {
                        Db::getInstance()->Execute(
                            'UPDATE '._DB_PREFIX_.'lgcomments_orders '.
                            'SET sent = "2", date_email2 = NOW() '.
                            'WHERE id_order = '.(int)$order['id_order'].''
                        );
                        $enviados++;
                        $pedidos .= $order['id_order'].'<br>';
                        echo '<span style="font-family:arial; font-weight:bold; font-size:14px;">';
                        echo $module->l('Order', 'lgcommentscron').' #'.$order['id_order'].': ';
                        echo $module->l('Email sent (second time)', 'lgcommentscron');
                        echo '</span><br>';
                    } else {
                        echo '<span style="font-family:arial; font-size:14px;">';
                        echo $module->l('Order', 'lgcommentscron').' #'.$order['id_order'].': ';
                        echo $module->l('Email not sent: problem with your email configuration', 'lgcommentscron');
                        echo '</span><br>';
                    }
                } else {
                    echo '<span style="font-family:arial; font-size:14px;">';
                    echo $module->l('Order', 'lgcommentscron').' #'.$order['id_order'];
                    echo ': '.$module->l('Email already sent', 'lgcommentscron').'';
                    echo ' - '.date("d/m/Y H:i", strtotime($order['date_email'])).'';
                    if ($order['date_email2'] != '0000-00-00 00:00:00') {
                        echo ' - '.date("d/m/Y H:i", strtotime($order['date_email2'])).'';
                    }
                    echo '</span><br>';
                }
            }
        }
        /* email confirmación cron */
        $email_cron = Configuration::get('PS_LGCOMMENTS_EMAIL_CRON');
        $email_alerts = Configuration::get('PS_LGCOMMENTS_EMAIL_ALERTS');
        if ($enviados and $email_cron and $email_alerts == 1) {
            $templateVars = array(
                '{pedidos}' => $pedidos
            );
            // Check if email template exists for current iso code. If not, use English template.
            $default = Configuration::get('PS_LANG_DEFAULT');
            $module_path2 = _PS_MODULE_DIR_.'lgcomments/mails/'.Language::getIsoById($default).'/';
            $template_path2 = _PS_THEME_DIR_.'modules/lgcomments/mails/'.Language::getIsoById($default).'/';
            if (is_dir($module_path2) or is_dir($template_path2)) {
                $langId2 = $default;
            } else {
                $langId2 = (int)Language::getIdByIso('en');
            }
            Mail::Send(
                (int)$langId2,
                'cron-confirmation',
                Configuration::get('PS_LGCOMMENTS_SUBJECT_CRON'),
                $templateVars,
                $email_cron,
                null,
                null,
                Configuration::get('PS_SHOP_NAME'),
                null,
                null,
                dirname(__FILE__).'/mails/'
            );
        }
    } else {
        echo '<span style="font-family:arial; color:red; font-weight:bold; font-size:14px;">';
        echo $module->l('No email sent:', 'lgcommentscron');
        echo '&nbsp;';
        echo $module->l('you don\'t have any order that corresponds to the selected criteria.', 'lgcommentscron');
        echo '&nbsp;';
        echo $module->l('Please modify your settings and expand your range of selection.', 'lgcommentscron');
        echo '</span>';
    }
}
