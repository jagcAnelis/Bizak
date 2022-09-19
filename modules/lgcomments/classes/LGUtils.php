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

require_once(
    _PS_MODULE_DIR_ . DIRECTORY_SEPARATOR . 'lgcomments' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR .
    'LGProductComment.php'
);

class LGUtils
{
    public static function getDateFormat()
    {
        $format = Db::getInstance()->getValue(
            'SELECT date_format_lite '.
            'FROM '._DB_PREFIX_.'lang '.
            'WHERE id_lang = '.(int)Context::getContext()->language->id
        );
        return $format;
    }

    public static function getStarsConfig($module)
    {
        return array(
            'designs' => array(
                array(
                    'key' => 'circle',
                    'name' => $module->l('Circle')
                ),
                array(
                    'key' => 'plain',
                    'name' => $module->l('Plain')
                ),
                array(
                    'key' => 'square',
                    'name' => $module->l('Square')
                ),
            ),
            'colours' => array(
                array(
                    'key' => 'yellow',
                    'name' => $module->l('Yellow')
                ),
                array(
                    'key' => 'orange',
                    'name' => $module->l('Orange')
                ),
                array(
                    'key' => 'red',
                    'name' => $module->l('Red')
                ),
                array(
                    'key' => 'pink',
                    'name' => $module->l('Pink')
                ),
                array(
                    'key' => 'purple',
                    'name' => $module->l('Purple')
                ),
                array(
                    'key' => 'greenlight',
                    'name' => $module->l('Greenlight')
                ),
                array(
                    'key' => 'bluelight',
                    'name' => $module->l('Bluelight')
                ),
                array(
                    'key' => 'bluedark',
                    'name' => $module->l('Bluedark')
                ),
                array(
                    'key' => 'grey',
                    'name' => $module->l('Grey')
                ),
                array(
                    'key' => 'black',
                    'name' => $module->l('Black')
                ),
            ),
            'sizes'  => array(80, 90, 100, 110, 120, 130, 140, 150, 160, 170, 180),
            'scales' => array(
                array(
                    'key' => '5',
                    'name' => $module->l('From 0 to 5 (ex: 4,5/5)')
                ),
                array(
                    'key' => '10',
                    'name' => $module->l('From 0 to 10 (ex: 9/10)')
                ),
                array(
                    'key' => '20',
                    'name' => $module->l('From 0 to 20 (ex: 18/20)')
                ),
            ),
        );
    }
    
    
    public static function getStoreWidgetConfig($module)
    {
        return array(
            'available_positions' => array(
                array(
                    'key' => 1,
                    'name' => $module->l('Top left')
                ),
                array(
                    'key' => 2,
                    'name' => $module->l('Middle left')
                ),
                array(
                    'key' => 3,
                    'name' => $module->l('Bottom left')
                ),
                array(
                    'key' => 4,
                    'name' => $module->l('Top right')
                ),
                array(
                    'key' => 5,
                    'name' => $module->l('Middle right')
                ),
                array(
                    'key' => 6,
                    'name' => $module->l('Bottom right')
                ),
            ),
            'available_places' => array(
                array(
                    'key' => 1,
                    'name' => $module->l('On the side of the screen')
                ),
                array(
                    'key' => 2,
                    'name' => $module->l('Inside a column or footer')
                ),
            ),
            'available_designs' => array(
                array(
                    'key' => 'bubble',
                    'name' => $module->l('Bubble')
                ),
                array(
                    'key' => 'customer',
                    'name' => $module->l('Customer')
                ),
                array(
                    'key' => 'horizontal',
                    'name' => $module->l('Horizontal')
                ),
                array(
                    'key' => 'letter',
                    'name' => $module->l('Letter')
                ),
                array(
                    'key' => 'pentagon',
                    'name' => $module->l('Pentagon')
                ),
                array(
                    'key' => 'shop',
                    'name' => $module->l('Shop')
                ),
                array(
                    'key' => 'vertical',
                    'name' => $module->l('Vertical')
                ),
            ),
            'available_colours' => array(
                array(
                    'key' => 'yellow',
                    'name' => $module->l('Yellow')
                ),
                array(
                    'key' => 'orange',
                    'name' => $module->l('Orange')
                ),
                array(
                    'key' => 'red',
                    'name' => $module->l('Red')
                ),
                array(
                    'key' => 'pink',
                    'name' => $module->l('Pink')
                ),
                array(
                    'key' => 'greenlight',
                    'name' => $module->l('Green light')
                ),
                array(
                    'key' => 'greendark',
                    'name' => $module->l('Green dark')
                ),
                array(
                    'key' => 'bluelight',
                    'name' => $module->l('Blue light')
                ),
                array(
                    'key' => 'bluedark',
                    'name' => $module->l('Blue dark')
                ),
                array(
                    'key' => 'purple',
                    'name' => $module->l('Purple')
                ),
                array(
                    'key' => 'brownlight',
                    'name' => $module->l('Brown light')
                ),
                array(
                    'key' => 'browndark',
                    'name' => $module->l('Brown dark')
                ),
                array(
                    'key' => 'beige',
                    'name' => $module->l('Beige')
                ),
                array(
                    'key' => 'greylight',
                    'name' => $module->l('Grey light')
                ),
                array(
                    'key' => 'greydark',
                    'name' => $module->l('Grey dark')
                ),
                array(
                    'key' => 'black',
                    'name' => $module->l('Black')
                ),
            ),
        );
    }
    
    public static function createDefaultConfig($values)
    {
        $created = Configuration::updateValue('PS_LGCOMMENTS_DISPLAY', '1');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_DISPLAY_TYPE', '1');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_DISPLAY_SIDE', '5');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_DISPLAY_LANGUAGE', '0');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_PER_PAGE', '20');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_TEXTCOLOR', '777777');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_TEXTCOLOR2', '777777');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_BACKCOLOR2', 'FBFBFB');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_DISPLAY_COMMENTS', '1');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_DISPLAY_DEFAULT', '3');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_DISPLAY_MORE', '10');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_DISPLAY_ZEROSTAR', '0');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_DISPLAY_LANGUAGE2', '0');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_DISPLAY_SNIPPETS', '0');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_DISPLAY_SNIPPETS2', '0');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_PRICE_RANGE', '$$');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_DISPLAY_ORDER', '2');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_DISPLAY_ORDER2', '2');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_DISPLAY_SLIDER', '1');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_OWLCAROUSEL_DISABLED', '0');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_SLIDER_BLOCKS', '4');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_SLIDER_TOTAL', '12');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_OPINION_FORM', '1');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_SCALE', '10');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_CATTOPMARGIN', '0');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_CATBOTMARGIN', '0');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_PRODTOPMARGIN', '0');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_PRODBOTMARGIN', '0');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_CROSS', '1');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_STORE_FILTER', '1');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_PRODUCT_FILTER', '1');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_PRODUCT_FILTER_NB', '3');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_STORE_FORM', '1');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_PRODUCT_FORM', '1');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_BGDESIGN1', 'vertical');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_BGDESIGN2', 'greylight');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_STARDESIGN1', 'plain');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_STARDESIGN2', 'yellow');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_STARSIZE', '120');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_STARS_TYPE', '1');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_CSS_CONF', serialize($values['extraright_css_config']));
        $created &= Configuration::updateValue('PS_LGCOMMENTS_BACKGROUND5', 'f6f6f6');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_BORDERSIZE5', '1');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_TAB_CONTENT', $values['tab_type']);
        $created &= Configuration::updateValue('PS_LGCOMMENTS_BORDERCOLOR5', '555555');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_RATECOLOR5', '555555');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_RATESIZE5', '22');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_RATEFAMILY5', 'arial');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_COMMENTCOLOR5', '555555');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_COMMENTSIZE5', '18');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_COMMENTFAMILY5', 'arial');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_COMMENTALIGN5', 'center');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_DATECOLOR5', '8C8C8C');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_DATESIZE5', '12');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_DATEFAMILY5', 'arial');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_DATEALIGN5', 'left');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_EMAIL_ALERTS', '1');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_SUBJECT_CRON', $values['subject_cron']);
        $created &= Configuration::updateValue('PS_LGCOMMENTS_SUBJECT_NEWREVIEWS', $values['subject_newreviews']);
        $created &= Configuration::updateValue('PS_LGCOMMENTS_DIAS', '7');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_DIAS2', '30');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_EMAIL_TWICE', '0');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_DAYS_AFTER', '10');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_VALIDATION', '1');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_BOXES', '1');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_TOP6', '70');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_LEFT6', '0');
        $created &= Configuration::updateValue('PS_LGCOMMENTS_STARST_POSITION', $values['prod_anchor_position']);
        $created &= Configuration::updateValue('PS_LGCOMMENTS_WIDGET_HOOK', 'displayFooter');
        $created &= Configuration::updateValue(
            'PS_LGCOMMENTS_EMAIL_CRON',
            Configuration::get('PS_SHOP_EMAIL')
        );

        // One status selected by default
        $created &= Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'lgcomments_status` VALUES (5)');

        // One group selected by default
        $created &= Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'lgcomments_customergroups` VALUES (3)');

        // All shops selected by default
        $shops = Db::getInstance()->executeS('SELECT `id_shop` FROM `'._DB_PREFIX_.'shop`');

        foreach ($shops as $shop) {
            $created &= Db::getInstance()->execute(
                'INSERT INTO `'._DB_PREFIX_.'lgcomments_multistore` VALUES ('.(int)$shop['id_shop'].')'
            );
        }
        return $created;
    }

    public static function createDefaultData()
    {
        $sql = 'SELECT `id_product`
                FROM `'._DB_PREFIX_.'product`
                WHERE `active` = 1';

        $id_product = Db::getInstance()->getValue($sql);

        // Product comment by default
        $created = Db::getInstance()->insert(
            LGProductComment::$definition['table'],
            array(
                'id_product'  => (int)$id_product,
                'id_customer' => 1,
                'id_lang'     => 1,
                'stars'       => 10,
                'nick'        => 'John Doe',
                'title'       => 'Product comment title',
                'comment'     => 'This is a default product comment.',
                'answer'      => 'Thanks for this default product comment!',
                'active'      => 1,
                'position'    => 1,
                'date'        => date('Y-m-d H:i:s')
            )
        );

        $created &= Db::getInstance()->insert(
            LGStoreComment::$definition['table'],
            array(
                'id_order'    => 1,
                'id_customer' => 1,
                'id_lang'     => 1,
                'stars'       => 10,
                'nick'        => 'John Doe',
                'title'       => 'Store comment title',
                'comment'     => 'This is a default store comment.',
                'answer'      => 'Thanks for this default store comment!',
                'active'      => 1,
                'position'    => 1,
                'date'        => date('Y-m-d H:i:s')
            )
        );

        return $created;
    }

    public static function createDefaultMetas()
    {
        $saved = true;

        if (substr_count(_PS_VERSION_, '1.6') > 0) {
            $saved &= Db::getInstance()->execute('
                INSERT INTO `'._DB_PREFIX_.'meta` (page, configurable)
                VALUES ("module-lgcomments-reviews", "1")');
            $id_meta = Db::getInstance()->getValue(
                'SELECT id_meta
                FROM '._DB_PREFIX_.'meta 
                WHERE page = "module-lgcomments-reviews"'
            );
            $shops = Db::getInstance()->executeS('SELECT `id_shop` FROM `'._DB_PREFIX_.'shop`');
            $themes = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'theme`');
            foreach ($themes as $theme) {
                $saved &= Db::getInstance()->execute('
                INSERT INTO `'._DB_PREFIX_.'theme_meta` (id_theme, id_meta, left_column, right_column)
                VALUES ("'.(int)$theme['id_theme'].'", "'.(int)$id_meta.'", "0", "0")');
            }
            foreach ($shops as $shop) {
                $languages = Language::getLanguages();
                foreach ($languages as $language) {
                    if ($language['iso_code'] == 'en') {
                        $saved &= Db::getInstance()->execute(
                            'INSERT INTO `'._DB_PREFIX_.'meta_lang` VALUES (
                                "'.(int)$id_meta.'",
                                "'.(int)$shop['id_shop'].'",
                                "'.(int)$language['id_lang'].'",
                                "Customer reviews about '.Configuration::get('PS_SHOP_NAME').'",
                                "Read all the reviews written by customers about our shop",
                                "reviews, comments, ratings, customers, shop",
                                "store-reviews"
                            )'
                        );
                        if (!Configuration::updateValue(
                            'PS_LGCOMMENTS_SUBJECT'.$language['iso_code'],
                            'We want to hear from you'
                        )
                        || !Configuration::updateValue(
                            'PS_LGCOMMENTS_SUBJECT2'.$language['iso_code'],
                            'Thank you for your review'
                        )
                        || !Configuration::updateValue(
                            'PS_LGCOMMENTS_SUBJECT3'.$language['iso_code'],
                            'You have received an answer to your review'
                        )) {
                            $saved &= false;
                        }
                    } elseif ($language['iso_code'] == 'es') {
                        $saved &= Db::getInstance()->execute(
                            'INSERT INTO `'._DB_PREFIX_.'meta_lang`
                            VALUES (
                                "'.(int)$id_meta.'",
                                "'.(int)$shop['id_shop'].'",
                                "'.(int)$language['id_lang'].'",
                                "Opiniones de clientes sobre '.Configuration::get('PS_SHOP_NAME').'",
                                "Descubre todas las opiniones de clientes sobre nuestra tienda",
                                "opiniones, comentarios, valoraciones, clientes, tienda",
                                "opiniones-tienda"
                            )'
                        );
                        if (!Configuration::updateValue(
                            'PS_LGCOMMENTS_SUBJECT'.$language['iso_code'],
                            'Tu opinión nos interesa'
                        )
                        || !Configuration::updateValue(
                            'PS_LGCOMMENTS_SUBJECT2'.$language['iso_code'],
                            'Muchas gracias por tu opinión'
                        )
                        || !Configuration::updateValue(
                            'PS_LGCOMMENTS_SUBJECT3'.$language['iso_code'],
                            'Has recibido una respuesta a tu comentario'
                        )) {
                            $saved &= false;
                        }
                    } elseif ($language['iso_code'] == 'fr') {
                        $saved &= Db::getInstance()->execute(
                            'INSERT INTO `'._DB_PREFIX_.'meta_lang`
                            VALUES (
                                "'.(int)$id_meta.'",
                                "'.(int)$shop['id_shop'].'",
                                "'.(int)$language['id_lang'].'",
                                "Avis clients sur '.Configuration::get('PS_SHOP_NAME').'",
                                "Découvrez tous les avis rédigés par les clients à propos de notre boutique",
                                "avis, commentaires, notes, clients, boutique",
                                "avis-boutique"
                            )'
                        );
                        if (!Configuration::updateValue(
                            'PS_LGCOMMENTS_SUBJECT'.$language['iso_code'],
                            'Votre avis nous intéresse'
                        )
                        || !Configuration::updateValue(
                            'PS_LGCOMMENTS_SUBJECT2'.$language['iso_code'],
                            'Merci beaucoup pour votre avis'
                        )
                        || !Configuration::updateValue(
                            'PS_LGCOMMENTS_SUBJECT3'.$language['iso_code'],
                            'Vous avez reçu une réponse à votre commentaire'
                        )) {
                            $saved &= false;
                        }
                    } elseif ($language['iso_code'] == 'it') {
                        $saved &= Db::getInstance()->execute(
                            'INSERT INTO `'._DB_PREFIX_.'meta_lang`
                            VALUES (
                                "'.(int)$id_meta.'",
                                "'.(int)$shop['id_shop'].'",
                                "'.(int)$language['id_lang'].'",
                                "Recensioni di clienti su '.Configuration::get('PS_SHOP_NAME').'",
                                "Leggi tutte le recensioni scritte dai clienti sul nostro negozio",
                                "recensioni, commenti, voti, clienti, negozio",
                                "recensioni-negozio"
                            )'
                        );
                        if (!Configuration::updateValue(
                            'PS_LGCOMMENTS_SUBJECT'.$language['iso_code'],
                            'La tua opinione conta'
                        )
                        || !Configuration::updateValue(
                            'PS_LGCOMMENTS_SUBJECT2'.$language['iso_code'],
                            'Grazie per la tua opinione'
                        )
                        || !Configuration::updateValue(
                            'PS_LGCOMMENTS_SUBJECT3'.$language['iso_code'],
                            'Hai ricevuto una risposta alla tua opinione'
                        )) {
                            $saved &= false;
                        }
                    } else {
                        $saved &= Db::getInstance()->execute(
                            'INSERT INTO `'._DB_PREFIX_.'meta_lang`
                            VALUES (
                                "'.(int)$id_meta.'",
                                "'.(int)$shop['id_shop'].'",
                                "'.(int)$language['id_lang'].'",
                                "Customer reviews about '.Configuration::get('PS_SHOP_NAME').'",
                                "Read all the reviews written by customers about our shop",
                                "reviews, comments, ratings, customers, shop",
                                "store-reviews"
                            )'
                        );
                        if (!Configuration::updateValue(
                            'PS_LGCOMMENTS_SUBJECT'.$language['iso_code'],
                            'We want to hear from you'
                        )
                        || !Configuration::updateValue(
                            'PS_LGCOMMENTS_SUBJECT2'.$language['iso_code'],
                            'Thank you for your review'
                        )
                        || !Configuration::updateValue(
                            'PS_LGCOMMENTS_SUBJECT3'.$language['iso_code'],
                            'You have received an answer to your review'
                        )) {
                            $saved &= false;
                        }
                    }
                }
            }
        }
        return $saved;
    }

    public static function saveRatingsConfig()
    {
        $saved = Configuration::updateValue(
            'PS_LGCOMMENTS_STARDESIGN1',
            pSQL(Tools::getValue('stardesign1', 'plain'))
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_STARDESIGN2',
            pSQL(Tools::getValue('bg_color', 'yellow'))
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_STARSIZE',
            (int)Tools::getValue('starsize', 120)
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_SCALE',
            (int)Tools::getValue('PS_LGCOMMENTS_SCALE', 5)
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_DISPLAY_ZEROSTAR',
            (int)Tools::getValue('PS_LGCOMMENTS_DISPLAY_ZEROSTAR', 0)
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_CATTOPMARGIN',
            (int)Tools::getValue('PS_LGCOMMENTS_CATTOPMARGIN', -10)
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_CATBOTMARGIN',
            (int)Tools::getValue('PS_LGCOMMENTS_CATBOTMARGIN', 10)
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_PRODTOPMARGIN',
            (int)Tools::getValue('PS_LGCOMMENTS_PRODTOPMARGIN', 5)
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_PRODBOTMARGIN',
            (int)Tools::getValue('PS_LGCOMMENTS_PRODBOTMARGIN', 5)
        );
        return $saved;
    }

    public static function saveStoreWidgetConfig()
    {
        $saved = Configuration::updateValue(
            'PS_LGCOMMENTS_DISPLAY',
            (int)Tools::getValue('PS_LGCOMMENTS_DISPLAY', 1)
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_DISPLAY_TYPE',
            (int)Tools::getValue('PS_LGCOMMENTS_DISPLAY_TYPE', 1)
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_DISPLAY_SIDE',
            (int)Tools::getValue('PS_LGCOMMENTS_DISPLAY_SIDE', 5)
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_BGDESIGN1',
            pSQL(Tools::getValue('bgdesign1', 'vertical'))
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_BGDESIGN2',
            pSQL(Tools::getValue('bg_color', 'greylight'))
        );
        $type = explode('-', pSQL(Tools::getValue('bgdesign1', 'bubble-yellow.png')));
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_CSS_CONF',
            serialize(self::getExtraRightCSSConfig($type[0]))
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_CROSS',
            (int)Tools::getValue('lgcomments_display_cross', 1)
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_TEXTCOLOR',
            pSQL(Tools::getValue('widget_text_color', '777777'))
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_WIDGET_HOOK',
            pSQL(Tools::getValue('PS_LGCOMMENTS_WIDGET_HOOK', 'displayFooter'))
        );
        return $saved;
    }

    public static function saveHomeSliderConfig()
    {
        $saved = Configuration::updateValue(
            'PS_LGCOMMENTS_DISPLAY_SLIDER',
            (int)Tools::getValue('PS_LGCOMMENTS_DISPLAY_SLIDER', 1)
        );
        $saved = Configuration::updateValue(
            'PS_LGCOMMENTS_OWLCAROUSEL_DISABLED',
            (int)Tools::getValue('PS_LGCOMMENTS_OWLCAROUSEL_DISABLED', 0)
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_SLIDER_BLOCKS',
            (int)Tools::getValue('PS_LGCOMMENTS_SLIDER_BLOCKS', 4)
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_SLIDER_TOTAL',
            (int)Tools::getValue('PS_LGCOMMENTS_SLIDER_TOTAL', 12)
        );
        return $saved;
    }

    public static function saveStorePageReviewConfig()
    {
        $saved = Configuration::updateValue(
            'PS_LGCOMMENTS_STORE_FILTER',
            (int)Tools::getValue('lgcomments_store_filter', 1)
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_TEXTCOLOR2',
            pSQL(Tools::getValue('PS_LGCOMMENTS_TEXTCOLOR2', '777777'))
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_BACKCOLOR2',
            pSQL(Tools::getValue('PS_LGCOMMENTS_BACKCOLOR2', 'FBFBFB'))
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_PER_PAGE',
            (int)Tools::getValue('PS_LGCOMMENTS_PER_PAGE', 20)
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_DISPLAY_ORDER',
            (int)Tools::getValue('PS_LGCOMMENTS_DISPLAY_ORDER', 1)
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_DISPLAY_LANGUAGE',
            (int)Tools::getValue('PS_LGCOMMENTS_DISPLAY_LANGUAGE', 0)
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_STORE_FORM',
            (int)Tools::getValue('PS_LGCOMMENTS_STORE_FORM', 1)
        );
        return $saved;
    }

    public static function saveProductReviewConfig()
    {
        $saved = Configuration::updateValue(
            'PS_LGCOMMENTS_DISPLAY_COMMENTS',
            (int)Tools::getValue('PS_LGCOMMENTS_DISPLAY_COMMENTS', 1)
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_TAB_CONTENT',
            (int)Tools::getValue('PS_LGCOMMENTS_TAB_CONTENT', ((version_compare(_PS_VERSION_, '1.7.0', '>='))?3:1))
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_PRODUCT_FILTER',
            (int)Tools::getValue('PS_LGCOMMENTS_PRODUCT_FILTER', 1)
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_PRODUCT_FILTER_NB',
            (int)Tools::getValue('PS_LGCOMMENTS_PRODUCT_FILTER_NB', 3)
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_DISPLAY_DEFAULT',
            (int)Tools::getValue('PS_LGCOMMENTS_DISPLAY_DEFAULT', 3)
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_DISPLAY_MORE',
            (int)Tools::getValue('PS_LGCOMMENTS_DISPLAY_MORE', 10)
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_DISPLAY_ORDER2',
            (int)Tools::getValue('PS_LGCOMMENTS_DISPLAY_ORDER2', 2)
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_DISPLAY_LANGUAGE2',
            (int)Tools::getValue('PS_LGCOMMENTS_DISPLAY_LANGUAGE2', 0)
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_PRODUCT_FORM',
            (int)Tools::getValue('PS_LGCOMMENTS_PRODUCT_FORM', 1)
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_STARST_POSITION',
            (int)Tools::getValue('PS_LGCOMMENTS_STARST_POSITION', 2)
        );
        return $saved;
    }

    public static function saveRichSnippetsConfig()
    {
        $saved = Configuration::updateValue(
            'PS_LGCOMMENTS_DISPLAY_SNIPPETS',
            (int)Tools::getValue('PS_LGCOMMENTS_DISPLAY_SNIPPETS', 0)
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_DISPLAY_SNIPPETS2',
            (int)Tools::getValue('PS_LGCOMMENTS_DISPLAY_SNIPPETS2', 0)
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_PRICE_RANGE',
            pSQL(Tools::getValue('PS_LGCOMMENTS_PRICE_RANGE', '€'))
        );
        return $saved;
    }

    public static function saveSendEmails()
    {
        // Email confirmation (Cron)
        $saved = Configuration::updateValue(
            'PS_LGCOMMENTS_EMAIL_ALERTS',
            (int)Tools::getValue('PS_LGCOMMENTS_EMAIL_ALERTS', 0)
        );

        $email_cron = pSQL(Tools::getValue('PS_LGCOMMENTS_EMAIL_CRON', ''));
        if (!empty($email_cron) && !Validate::isEmail($email_cron)) {
            $saved &= Configuration::updateValue('PS_LGCOMMENTS_EMAIL_CRON', '');
        } else {
            $saved &= Configuration::updateValue(
                'PS_LGCOMMENTS_EMAIL_CRON',
                pSQL(Tools::getValue('PS_LGCOMMENTS_EMAIL_CRON', ''))
            );
        }
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_SUBJECT_CRON',
            pSQL(Tools::getValue('subjectcron', ''))
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_SUBJECT_NEWREVIEWS',
            pSQL(Tools::getValue('subjectreviews', ''))
        );
        return $saved;
    }

    public static function saveConfigureEmails()
    {
        // Number of days
        $days = abs((int)Tools::getValue('lgcomments_dias', 30));
        if ($days == 0) {
            $days = 0; // Admitimos 0 como que desde el mismo día
        }
        $days2 = abs((int)Tools::getValue('lgcomments_dias2', 7));

        $saved = Configuration::updateValue('PS_LGCOMMENTS_DIAS', $days);
        $saved &= Configuration::updateValue('PS_LGCOMMENTS_DIAS2', $days2);

        // Order status
        $saved &= Db::getInstance()->execute('TRUNCATE TABLE ' . _DB_PREFIX_ . 'lgcomments_status');
        foreach (self::getOrdersStatus() as $estado) {
            if ((int)Tools::getValue('estado' . $estado['id_order_state'], 0) == 1) {
                $saved &= Db::getInstance()->execute(
                    'INSERT INTO ' . _DB_PREFIX_ . 'lgcomments_status ' .
                    'VALUES (' . (int)$estado['id_order_state'] . ')'
                );
            }
        }

        // Shops
        $saved &= Db::getInstance()->execute('TRUNCATE TABLE ' . _DB_PREFIX_ . 'lgcomments_multistore');
        foreach (self::getShops() as $shop) {
            if ((int)Tools::getValue('shop' . $shop['id_shop'], 0) == 1) {
                $saved &= Db::getInstance()->execute(
                    'INSERT INTO ' . _DB_PREFIX_ . 'lgcomments_multistore ' .
                    'VALUES (' . (int)$shop['id_shop'] . ')'
                );
            }
        }

        // Boxes checked
        $saved &= Configuration::updateValue('PS_LGCOMMENTS_BOXES', (int)Tools::getValue('PS_LGCOMMENTS_BOXES', 1));

        // Customer groups
        $customerGroups = self::getCustomerGroups();
        $saved &= Db::getInstance()->execute('TRUNCATE TABLE ' . _DB_PREFIX_ . 'lgcomments_customergroups');
        foreach ($customerGroups as $cGroup) {
            if ((int)Tools::getValue('group' . $cGroup['id_group'], 0) == 1) {
                $saved &= Db::getInstance()->execute(
                    'INSERT INTO ' . _DB_PREFIX_ . 'lgcomments_customergroups ' .
                    'VALUES (' . (int)$cGroup['id_group'] . ')'
                );
            }
        }

        // Email subject
        $langs = Language::getLanguages();
        foreach ($langs as $lang) {
            $saved &= Configuration::updateValue(
                'PS_LGCOMMENTS_SUBJECT' . $lang['iso_code'],
                pSQL(Tools::getValue('subject' . $lang['iso_code']))
            );
            $saved &= Configuration::updateValue(
                'PS_LGCOMMENTS_SUBJECT2' . $lang['iso_code'],
                pSQL(Tools::getValue('subject2' . $lang['iso_code']))
            );
            $saved &= Configuration::updateValue(
                'PS_LGCOMMENTS_SUBJECT3' . $lang['iso_code'],
                pSQL(Tools::getValue('subject3' . $lang['iso_code']))
            );
        }

        // Second email
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_EMAIL_TWICE',
            (int)Tools::getValue('lgcomments_email_twice', 0)
        );
        $saved &= Configuration::updateValue(
            'PS_LGCOMMENTS_DAYS_AFTER',
            (int)Tools::getValue('lgcomments_days_after', 10)
        );

        return $saved;
    }

    public static function getOrdersStatus()
    {
        $estados = Db::getInstance()->ExecuteS(
            'SELECT * ' .
            'FROM ' . _DB_PREFIX_ . 'order_state_lang osl ' .
            'INNER JOIN ' . _DB_PREFIX_ . 'order_state os ' .
            'ON osl.id_order_state = os.id_order_state ' .
            'WHERE osl.id_lang = ' . (int)Context::getContext()->language->id . ' ' .
            'ORDER BY osl.id_order_state ASC'
        );
        return $estados;
    }

    public static function getShops()
    {
        $shops = Db::getInstance()->ExecuteS(
            'SELECT * ' .
            'FROM ' . _DB_PREFIX_ . 'shop'
        );
        return $shops;
    }

    public static function getCustomerGroups()
    {
        $grupo = Db::getInstance()->ExecuteS(
            'SELECT * ' .
            'FROM ' . _DB_PREFIX_ . 'group_lang ' .
            'WHERE id_lang = ' . (int)Context::getContext()->language->id
        );
        return $grupo;
    }

    /* How to display the widget */
    public static function getExtraRightCSSConfig($type)
    {
        if (substr_count(_PS_VERSION_, '1.6') > 0
            && Configuration::get('PS_LGCOMMENTS_DISPLAY_TYPE') == 2
        ) {
            return self::getExtraRightCSSConfig16($type);
        } elseif (substr_count(_PS_VERSION_, '1.6') > 0
            && Configuration::get('PS_LGCOMMENTS_DISPLAY_TYPE') == 1
        ) {
            return self::getExtraRightCSSConfig15($type);
        } else {
            return self::getExtraRightCSSConfig15($type);
        }
    }

    /* Widget configuration for PS 1.6 column */
    public static function getExtraRightCSSConfig16($type)
    {
        $config = array();
        // Bubble widget (PS 1.6 column)
        $config['bubble'] = array(
            'title' => array(
                'top' => '45',
                'left' => '12',
                'width' => '130',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '20',
                'font-weight' => 'bold',
                'line-height' => '26',
                'rotate' => '0',
            ),
            'widget' => array(
                'width' => '270',
                'height' => '270',
            ),
            'rating' => array(
                'top' => '60',
                'left' => '150',
                'width' => '100',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '35',
                'font-weight' => 'normal',
            ),
            'review' => array(
                'top' => '165',
                'left' => '30',
                'width' => '200',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '17',
                'font-weight' => 'normal',
            ),
            'stars' => array(
                'top' => '115',
                'left' => '30',
                'width' => '200',
                'rotate' => '0',
            ),
            'see-more' => array(
                'top' => '220',
                'left' => '120',
                'width' => '120',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '20',
                'font-weight' => 'bold',
            ),
            'cross' => array(
                'top' => '20',
                'right' => '20',
            ),
        );
        // Customer widget (PS 1.6 column)
        $config['customer'] = array(
            'widget' => array(
                'width' => '270',
                'height' => '354',
            ),
            'title' => array(
                'top' => '30',
                'left' => '10',
                'width' => '240',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '21',
                'font-weight' => 'bold',
                'line-height' => '22',
                'rotate' => '0',
            ),
            'rating' => array(
                'top' => '100',
                'left' => '80',
                'width' => '100',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '32',
                'font-weight' => 'normal',
            ),
            'review' => array(
                'top' => '195',
                'left' => '88',
                'width' => '150',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '18',
                'font-weight' => 'normal',
            ),
            'stars' => array(
                'top' => '135',
                'left' => '45',
                'width' => '200',
                'rotate' => '0',
            ),
            'see-more' => array(
                'top' => '300',
                'left' => '120',
                'width' => '120',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '20',
                'font-weight' => 'bold',
            ),
            'cross' => array(
                'top' => '20',
                'right' => '20',
            ),
        );
        // Horizontal widget (PS 1.6 column)
        $config['horizontal'] = array(
            'widget' => array(
                'width' => '250',
                'height' => '100',
            ),
            'title' => array(
                'top' => '20',
                'left' => '12',
                'width' => '220',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '20',
                'font-weight' => 'bold',
                'line-height' => '20',
                'rotate' => '0',
            ),
            'rating' => array(
                'top' => '0',
                'left' => '0',
                'width' => '0',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '0',
                'font-weight' => 'normal',
            ),
            'review' => array(
                'top' => '0',
                'left' => '0',
                'width' => '0',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '0',
                'font-weight' => 'normal',
            ),
            'stars' => array(
                'top' => '45',
                'left' => '40',
                'width' => '170',
                'rotate' => '0',
            ),
            'see-more' => array(
                'top' => '0',
                'left' => '0',
                'width' => '0',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '0',
                'font-weight' => 'bold',
            ),
            'cross' => array(
                'top' => '15',
                'right' => '0',
            ),
        );
        // Letter widget (PS 1.6 column)
        $config['letter'] = array(
            'widget' => array(
                'width' => '270',
                'height' => '340',
            ),
            'title' => array(
                'top' => '45',
                'left' => '25',
                'width' => '140',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '24',
                'font-weight' => 'bold',
                'line-height' => '28',
                'rotate' => '0',
            ),
            'rating' => array(
                'top' => '45',
                'left' => '170',
                'width' => '90',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '29',
                'font-weight' => 'normal',
            ),
            'review' => array(
                'top' => '195',
                'left' => '40',
                'width' => '190',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '20',
                'font-weight' => 'normal',
            ),
            'stars' => array(
                'top' => '120',
                'left' => '35',
                'width' => '200',
                'rotate' => '0',
            ),
            'see-more' => array(
                'top' => '295',
                'left' => '120',
                'width' => '120',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '22',
                'font-weight' => 'bold',
            ),
            'cross' => array(
                'top' => '20',
                'right' => '20',
            ),
        );
        // Pentagon widget (PS 1.6 column)
        $config['pentagon'] = array(
            'widget' => array(
                'width' => '270',
                'height' => '297',
            ),
            'title' => array(
                'top' => '0',
                'left' => '0',
                'width' => '270',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '24',
                'font-weight' => 'bold',
                'line-height' => '24',
                'rotate' => '0',
            ),
            'rating' => array(
                'top' => '95',
                'left' => '85',
                'width' => '100',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '29',
                'font-weight' => 'normal',
            ),
            'review' => array(
                'top' => '190',
                'left' => '45',
                'width' => '175',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '17',
                'font-weight' => 'normal',
            ),
            'stars' => array(
                'top' => '145',
                'left' => '35',
                'width' => '200',
                'rotate' => '0',
            ),
            'see-more' => array(
                'top' => '255',
                'left' => '100',
                'width' => '120',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '22',
                'font-weight' => 'bold',
            ),
            'cross' => array(
                'top' => '20',
                'right' => '20',
            ),
        );
        // Shop widget (PS 1.6 column)
        $config['shop'] = array(
            'widget' => array(
                'width' => '270',
                'height' => '347',
            ),
            'title' => array(
                'top' => '32',
                'left' => '15',
                'width' => '240',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '21',
                'font-weight' => 'bold',
                'line-height' => '22',
                'rotate' => '0',
            ),
            'rating' => array(
                'top' => '95',
                'left' => '95',
                'width' => '80',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '26',
                'font-weight' => 'normal',
            ),
            'review' => array(
                'top' => '200',
                'left' => '42',
                'width' => '190',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '19',
                'font-weight' => 'normal',
            ),
            'stars' => array(
                'top' => '140',
                'left' => '35',
                'width' => '200',
                'rotate' => '0',
            ),
            'see-more' => array(
                'top' => '305',
                'left' => '130',
                'width' => '120',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '22',
                'font-weight' => 'bold',
            ),
            'cross' => array(
                'top' => '20',
                'right' => '20',
            ),
        );
        // Vertical widget (PS 1.6 column)
        $config['vertical'] = array(
            'widget' => array(
                'width' => '100',
                'height' => '250',
            ),
            'title' => array(
                'top' => '115',
                'left' => '-85',
                'width' => '230',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '20',
                'font-weight' => 'bold',
                'line-height' => '20',
                'rotate' => '1',
            ),
            'rating' => array(
                'top' => '0',
                'left' => '0',
                'width' => '0',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '0',
                'font-weight' => 'normal',
            ),
            'review' => array(
                'top' => '0',
                'left' => '0',
                'width' => '0',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '0',
                'font-weight' => 'normal',
            ),
            'stars' => array(
                'top' => '105',
                'left' => '-20',
                'width' => '170',
                'rotate' => '1',
            ),
            'see-more' => array(
                'top' => '0',
                'left' => '0',
                'width' => '0',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '0',
                'font-weight' => 'bold',
            ),
            'cross' => array(
                'top' => '20',
                'right' => '5',
            ),
        );

        return isset($config[$type]) ? $config[$type] : array();
    }

    /* Widget configuration for PS 1.6 side and PS 1.5 */
    public static function getExtraRightCSSConfig15($type)
    {
        $config = array();
        // Bubble widget (PS 1.6 side and PS 1.5)
        $config['bubble'] = array(
            'widget' => array(
                'width' => '200',
                'height' => '203',
            ),
            'title' => array(
                'top' => '30',
                'left' => '10',
                'width' => '95',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '15',
                'font-weight' => 'bold',
                'line-height' => '22',
                'rotate' => '0',
            ),
            'rating' => array(
                'top' => '45',
                'left' => '115',
                'width' => '60',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '25',
                'font-weight' => 'normal',
            ),
            'review' => array(
                'top' => '125',
                'left' => '10',
                'width' => '170',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '12',
                'font-weight' => 'normal',
            ),
            'stars' => array(
                'top' => '85',
                'left' => '20',
                'width' => '150',
                'rotate' => '0',
            ),
            'see-more' => array(
                'top' => '165',
                'left' => '60',
                'width' => '120',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '15',
                'font-weight' => 'bold',
            ),
            'cross' => array(
                'top' => '25',
                'right' => '15',
            ),
        );
        // Customer widget (PS 1.6 side and PS 1.5)
        $config['customer'] = array(
            'widget' => array(
                'width' => '200',
                'height' => '262',
            ),
            'title' => array(
                'top' => '23',
                'left' => '5',
                'width' => '180',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '16',
                'font-weight' => 'bold',
                'line-height' => '16',
                'rotate' => '0',
            ),
            'rating' => array(
                'top' => '70',
                'left' => '6',
                'width' => '180',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '24',
                'font-weight' => 'normal',
            ),
            'review' => array(
                'top' => '142',
                'left' => '63',
                'width' => '115',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '12',
                'font-weight' => 'normal',
            ),
            'stars' => array(
                'top' => '100',
                'left' => '32',
                'width' => '150',
                'rotate' => '0',
            ),
            'see-more' => array(
                'top' => '225',
                'left' => '70',
                'width' => '120',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '16',
                'font-weight' => 'bold',
            ),
            'cross' => array(
                'top' => '20',
                'right' => '10',
            ),
        );
        // Horizontal widget (PS 1.6 side and PS 1.5)
        $config['horizontal'] = array(
            'widget' => array(
                'width' => '200',
                'height' => '80',
            ),
            'title' => array(
                'top' => '15',
                'left' => '12',
                'width' => '180',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '16',
                'font-weight' => 'bold',
                'line-height' => '16',
                'rotate' => '0',
            ),
            'rating' => array(
                'top' => '0',
                'left' => '0',
                'width' => '0',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '0',
                'font-weight' => 'normal',
            ),
            'review' => array(
                'top' => '0',
                'left' => '0',
                'width' => '0',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '0',
                'font-weight' => 'normal',
            ),
            'stars' => array(
                'top' => '35',
                'left' => '25',
                'width' => '150',
                'rotate' => '0',
            ),
            'see-more' => array(
                'top' => '0',
                'left' => '0',
                'width' => '0',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '0',
                'font-weight' => 'bold',
            ),
            'cross' => array(
                'top' => '15',
                'right' => '0',
            ),
        );
        // Letter widget (PS 1.6 side and PS 1.5)
        $config['letter'] = array(
            'widget' => array(
                'width' => '200',
                'height' => '252',
            ),
            'title' => array(
                'top' => '35',
                'left' => '12',
                'width' => '110',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '18',
                'font-weight' => 'bold',
                'line-height' => '22',
                'rotate' => '0',
            ),
            'rating' => array(
                'top' => '32',
                'left' => '128',
                'width' => '60',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '21',
                'font-weight' => 'normal',
            ),
            'review' => array(
                'top' => '145',
                'left' => '30',
                'width' => '140',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '13',
                'font-weight' => 'normal',
            ),
            'stars' => array(
                'top' => '88',
                'left' => '20',
                'width' => '160',
                'rotate' => '0',
            ),
            'see-more' => array(
                'top' => '220',
                'left' => '70',
                'width' => '110',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '16',
                'font-weight' => 'bold',
            ),
            'cross' => array(
                'top' => '25',
                'right' => '5',
            ),
        );
        // Pentagon widget (PS 1.6 side and PS 1.5)
        $config['pentagon'] = array(
            'widget' => array(
                'width' => '200',
                'height' => '220',
            ),
            'title' => array(
                'top' => '3',
                'left' => '0',
                'width' => '200',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '18',
                'font-weight' => 'bold',
                'line-height' => '18',
                'rotate' => '0',
            ),
            'rating' => array(
                'top' => '68',
                'left' => '71',
                'width' => '60',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '21',
                'font-weight' => 'normal',
            ),
            'review' => array(
                'top' => '140',
                'left' => '22',
                'width' => '158',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '12',
                'font-weight' => 'normal',
            ),
            'stars' => array(
                'top' => '105',
                'left' => '20',
                'width' => '160',
                'rotate' => '0',
            ),
            'see-more' => array(
                'top' => '190',
                'left' => '50',
                'width' => '120',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '16',
                'font-weight' => 'bold',
            ),
            'cross' => array(
                'top' => '0',
                'right' => '5',
            ),
        );
        // Shop widget (PS 1.6 side and PS 1.5)
        $config['shop'] = array(
            'widget' => array(
                'width' => '200',
                'height' => '257',
            ),
            'title' => array(
                'top' => '22',
                'left' => '15',
                'width' => '170',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '15',
                'font-weight' => 'bold',
                'line-height' => '17',
                'rotate' => '0',
            ),
            'rating' => array(
                'top' => '70',
                'left' => '70',
                'width' => '60',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '19',
                'font-weight' => 'normal',
            ),
            'review' => array(
                'top' => '145',
                'left' => '30',
                'width' => '140',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '13',
                'font-weight' => 'normal',
            ),
            'stars' => array(
                'top' => '100',
                'left' => '20',
                'width' => '160',
                'rotate' => '0',
            ),
            'see-more' => array(
                'top' => '225',
                'left' => '70',
                'width' => '120',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '17',
                'font-weight' => 'bold',
            ),
            'cross' => array(
                'top' => '17',
                'right' => '5',
            ),
        );
        // Vertical widget (PS 1.6 side and PS 1.5)
        $config['vertical'] = array(
            'widget' => array(
                'width' => '80',
                'height' => '200',
            ),
            'title' => array(
                'top' => '92',
                'left' => '-65',
                'width' => '180',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '16',
                'font-weight' => 'bold',
                'line-height' => '16',
                'rotate' => '1',
            ),
            'rating' => array(
                'top' => '0',
                'left' => '0',
                'width' => '0',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '0',
                'font-weight' => 'normal',
            ),
            'review' => array(
                'top' => '0',
                'left' => '0',
                'width' => '0',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '0',
                'font-weight' => 'normal',
            ),
            'stars' => array(
                'top' => '85',
                'left' => '-20',
                'width' => '150',
                'rotate' => '1',
            ),
            'see-more' => array(
                'top' => '0',
                'left' => '0',
                'width' => '0',
                'text-align' => 'Center',
                'font-family' => 'Arial',
                'font-size' => '0',
                'font-weight' => 'bold',
            ),
            'cross' => array(
                'top' => '20',
                'right' => '5',
            ),
        );

        return isset($config[$type]) ? $config[$type] : array();
    }

    public static function commentNeedValidation()
    {
        return Configuration::get('PS_LGCOMMENTS_VALIDATION') == 0;
    }

    public static function addCSS($path, $id, $context = null, $force_old_method = false)
    {
        if (is_null($context)) {
            $context = Context::getContext();
        }
        if (version_compare(_PS_VERSION_, '1.7.0', '>') && !$force_old_method) {
            $context->controller->registerStylesheet(
                $id,
                $path,
                array(
                    'media' => 'all',
                    'priority' => 150,
                )
            );
        } else {
            Context::getContext()->controller->addCSS($path);
        }
    }

    public static function addJS($path, $id = null)
    {
        if (version_compare(_PS_VERSION_, '1.7.0', '>')) {
            Context::getContext()->controller->registerJavascript(
                $id,
                $path,
                array(
                    'position' => 'bottom',
                    'priority' => 150,
                )
            );
        } else {
            Context::getContext()->controller->addJS($path);
        }
    }

    public static function jsonEncode($raw)
    {
        if (version_compare(_PS_VERSION_, '1.7.0', '>')) {
            return json_encode($raw);  // Tools::jsonEncode() in PS 1.7 is deprecated
        } else {
            return Tools::jsonEncode($raw);
        }
    }

    public static function getMediaBasePath($module)
    {
        if (version_compare(_PS_VERSION_, '1.7.0', '>=')) {
            return 'modules/'.$module->name.'/';
        } else {
            return $module->getPathUri();
        }
    }

    public static function getFormattedName($size)
    {
        if (version_compare(_PS_VERSION_, '1.7.0', '>=')) {
            return ImageType::getFormattedName($size);
        } elseif (version_compare(_PS_VERSION_, '1.5.3', '>=')) {
            return ImageType::getFormatedName($size);
        } else {
            return $size;
        }
    }
}
