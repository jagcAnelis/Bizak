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

class LGCommentsReviewsModuleFrontController extends ModuleFrontController
{
    protected $lgcomments_stars = array();
    protected $lgcomments_total_reviews_sum = 0;
    protected $lgcomments_total_reviews_num = 0;

    public function initContent()
    {
        parent::initContent();

        // Esto es para la paginación
        $this->n = (int)Tools::getValue('n', (int)Configuration::get('PS_LGCOMMENTS_PER_PAGE'));
        if (version_compare(_PS_VERSION_, '1.7.0', '>=')) {
            $this->p = (int)Tools::getValue('page', 1);
        } else {
            $this->p = (int)Tools::getValue('p', 1);
        }

        if (version_compare(_PS_VERSION_, '1.7.0', '>=')) {
            if (Tools::getIsset('from-xhr')) {
                $this->setTemplate('module:lgcomments/views/templates/front/store_reviews_17_content.tpl');
            } else {
                $this->setTemplate('module:lgcomments/views/templates/front/store_reviews_17.tpl');
            }
        } else {
            $this->setTemplate('store_reviews.tpl');
        }

        // Variables especififcas para 1.7
        if (version_compare(_PS_VERSION_, '1.7.0', '>=')) {
            $template_var_urls = $this->getTemplateVarUrls();
            $this->context->smarty->assign(array(
                'my_base_url' => $template_var_urls['base_url'],
                'modules_dir' => _MODULE_DIR_ . 'lgcomments/',
                'tpl_dir'     => _PS_THEME_DIR_ . $this->module->name,
                'logged'      => $this->context->customer->isLogged(),
                'breadcrumb'  => $this->getBreadcrumb(),
                'lang_iso'    => $this->context->language->iso_code,
                'cookie'      => $this->context->cookie,
                'logo_url'    => $this->context->link->getMediaLink(_PS_IMG_.Configuration::get('PS_LOGO')),
            ));
        }

        $this->context->smarty->assign('current_theme_dir', _THEME_DIR_);

        // General configuration
        $this->setConfigurationTemplateVars();

        // Summary
        $this->getSummary();
        $this->setSummaryTemplateVars();

        // Reviews
        $this->assignCommentsList();

        // CARLOS: Si recargamos con el parámetro sendReview es que hemos llegado mandando el formulario y recargando
        //         la página, no por Ajax
        if (pSQL(Tools::getValue('action')) == 'sendReview' && !Tools::getIsset('ajax')) {
            $this->sendReview();
        }

        // En 1.7 parece que falla el método normal del displayAjax
        if (version_compare(_PS_VERSION_, '1.7.0', '>=') &&
            Tools::getIsset('from-xhr')
        ) {
            // ajax on 1.7
            $this->displayAjaxGetReviews();
        }
    }

    private function checkIfAlreadyReviewed()
    {
        $check = Db::getInstance()->getValue(
            'SELECT COUNT(' . LGStoreComment::$definition['primary'] . ') ' .
            'FROM ' . _DB_PREFIX_ . LGStoreComment::$definition['table'] . ' ' .
            'WHERE id_customer = ' . (int)$this->context->customer->id
        );
        return $check;
    }

    private function getMaxPosition()
    {
        $maxposition = Db::getInstance()->getValue(
            'SELECT MAX(`position`) ' .
            'FROM ' ._DB_PREFIX_.LGStoreComment::$definition['table']
        );
        return $maxposition;
    }

    private function getCustomerEmail($id)
    {
        $customerEmail = Db::getInstance()->getValue(
            'SELECT email ' .
            'FROM ' . _DB_PREFIX_ . 'customer ' .
            'WHERE id_customer = ' . (int)$id
        );
        return $customerEmail;
    }

    private function getFirstname($id)
    {
        $firstname = Db::getInstance()->getValue(
            'SELECT firstname ' .
            'FROM ' . _DB_PREFIX_ . 'customer ' .
            'WHERE id_customer = ' . (int)$id
        );
        return $firstname;
    }

    private function getLastname($id)
    {
        $lastname = Db::getInstance()->getValue(
            'SELECT lastname ' .
            'FROM ' . _DB_PREFIX_ . 'customer ' .
            'WHERE id_customer = ' . (int)$id
        );
        return $lastname;
    }

    private function getProductName($product_id, $lang_id)
    {
        $productName = Db::getInstance()->getValue(
            'SELECT name ' .
            'FROM ' . _DB_PREFIX_ . 'product_lang ' .
            'WHERE id_product = ' . (int)$product_id . ' ' .
            'AND id_lang = ' . (int)$lang_id
        );
        return $productName;
    }

    private function getDateFormat()
    {
        $format = Db::getInstance()->getValue(
            'SELECT date_format_lite ' .
            'FROM ' . _DB_PREFIX_ . 'lang ' .
            'WHERE id_lang = ' . (int)$this->context->language->id
        );
        return $format;
    }

    /* Assign list of products template vars */
    public function assignCommentsList()
    {
        $this->nbProducts = $this->lgcomments_total_reviews_num;
        $this->pagination((int)$this->nbProducts); // Pagination must be call after "getProducts"
        switch (pSQL(Tools::getValue('star', 'all'))) {
            case 'zero':
                $min = 0;
                $max = 2;
                break;
            case 'one':
                $min = 2;
                $max = 4;
                break;
            case 'two':
                $min = 4;
                $max = 6;
                break;
            case 'three':
                $min = 6;
                $max = 8;
                break;
            case 'four':
                $min = 8;
                $max = 10;
                break;
            case 'five':
                $min = 10;
                $max = 11;
                break;
            case 'all':
            default:
                $min = 0;
                $max = 11;
                break;
        }

        $reviews = LGStoreComment::getReviewsByRatings($min, $max, $this->p - 1, $this->n);

        $this->context->smarty->assign(array(
            'nb_products' => $this->nbProducts,

            // CARLOS: ESTOS REVIEWS ERAN MACHACADOS HAY QUE VER SI SON LOS BUENOS
            'reviews' => $reviews,
        ));
    }

    public function setConfigurationTemplateVars()
    {
        $lgcomments_shop_address = AddressFormat::generateAddress($this->context->shop->getAddress());
        $shop_url = $this->context->shop->getBaseURL(true, true);

        $link = new Link();
        $this->context->smarty->assign(array(
            // CARLOS: ESTOS SON SEGUROS
            'lgcomments_logo_url' =>  $link->getMediaLink(_PS_IMG_.Configuration::get('PS_LOGO')),
            'lgcomments_content_dir'       => _MODULE_DIR_ . $this->module->name,
            'price_range'       => Configuration::get('PS_LGCOMMENTS_PRICE_RANGE'),
            'lgcomments_shop_url' => $shop_url,
            'lgcomments_shop_address' => $lgcomments_shop_address,

            'starstyle' => Configuration::get('PS_LGCOMMENTS_STARDESIGN1'),
            'starcolor' => Configuration::get('PS_LGCOMMENTS_STARDESIGN2'),
            'star_link' => $link->getModuleLink('lgcomments', 'reviews'),
            'starsize' => Configuration::get('PS_LGCOMMENTS_STARSIZE'),
            'ratingscale' => Configuration::get('PS_LGCOMMENTS_SCALE'),
            'shop_name' => Configuration::get('PS_SHOP_NAME'),
            'displaysnippets' => Configuration::get('PS_LGCOMMENTS_DISPLAY_SNIPPETS'),
            'storetextcolor' => Configuration::get('PS_LGCOMMENTS_TEXTCOLOR2'),
            'storebackcolor' => Configuration::get('PS_LGCOMMENTS_BACKCOLOR2'),
            'storefilter' => Configuration::get('PS_LGCOMMENTS_STORE_FILTER'),
            'storeform' => Configuration::get('PS_LGCOMMENTS_STORE_FORM'),
            'alreadyreviewed' => $this->checkIfAlreadyReviewed(),
            'id_customer' => (int)$this->context->customer->id,
            'is_shop_comment' => true,
            'id_product' => 0, // Necesario, la plantilla se que ja si no
            'authentication_url' => Context::getContext()->link->getPageLink('authentication'),
            'worstrating'       => 0
//            'worstrating'       => Configuration::get('PS_LGCOMMENTS_SCALE') == 20
//                ? 2
//                : ( Configuration::get('PS_LGCOMMENTS_SCALE') == 5
//                    ? 0.5
//                    : 1
//                ),
        ));

        // CARLOS: Estas son para anailzar si son necesarias o no tras la refactorizacion, las que sean necesarias hay
        //         que pasarlas al de arriba
        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            $ps16 = true;
        } else {
            $ps16 = false;
        }
        $this->context->smarty->assign(array(
            'ps16' => $ps16,
        ));
    }

    public function getSummary()
    {
        $this->lgcomments_stars = array(
            'allstars'   => LGStoreComment::getNumReviewsByRatings('0', '11'),
            'zerostar'   => LGStoreComment::getNumReviewsByRatings('0', '2'),
            'onestar'    => LGStoreComment::getNumReviewsByRatings('2', '4'),
            'twostars'   => LGStoreComment::getNumReviewsByRatings('4', '6'),
            'threestars' => LGStoreComment::getNumReviewsByRatings('6', '8'),
            'fourstars'  => LGStoreComment::getNumReviewsByRatings('8', '10'),
            'fivestars'  => LGStoreComment::getNumReviewsByRatings('10', '11'),
        );
        $this->lgcomments_total_reviews_sum = LGStoreComment::getSumStarsValues();
        $this->lgcomments_total_reviews_num = $this->lgcomments_stars['allstars'];
    }

    public function setSummaryTemplateVars()
    {
        $mediacomentarios  = 0;
        $mediacomentarios2 = 0;
        if ($this->lgcomments_total_reviews_num > 0) {
            $mediacomentarios  = $this->lgcomments_total_reviews_sum / $this->lgcomments_total_reviews_num;
            $mediacomentarios2 = $this->lgcomments_total_reviews_sum / $this->lgcomments_total_reviews_num;
        }
        $this->context->smarty->assign(array(
            'stars'             => $this->lgcomments_stars,
            'numerocomentarios' => $this->lgcomments_total_reviews_num,
            'mediacomentarios'  => $mediacomentarios,
            'mediacomentarios2' => @round($mediacomentarios2, 1),
        ));
    }

    public function pagination($total_products = null)
    {
        $reviews_per_page          = Configuration::get('PS_LGCOMMENTS_PER_PAGE');
        $default_products_per_page = max(1, (int)Configuration::get('PS_LGCOMMENTS_PER_PAGE'));

        $nArray = array(
            $default_products_per_page,
            $default_products_per_page * 2,
            $default_products_per_page * 5
        );

        if ((int)Tools::getValue('n') && (int)$total_products > 0) {
            $nArray[] = $total_products;
        }

        // Retrieve the current number of products per page
        // (either the default, the GET parameter or the one in the cookie)
        $this->n = $default_products_per_page;
        if ((int)Tools::getValue('n') && in_array((int)Tools::getValue('n'), $nArray)) {
            $this->n = (int)Tools::getValue('n');
            if (isset($this->context->cookie->nb_item_per_page)
                &&
                in_array($this->context->cookie->nb_item_per_page, $nArray)
            ) {
                $this->n = (int)$this->context->cookie->nb_item_per_page;
            }
        }

        // If the parameter is not correct then redirect
        // (do not merge with the previous line, the redirect
        // is required in order to avoid duplicate content)
        if (!is_numeric($this->p) || $this->p < 1) {
            Tools::redirect(
                $this->context->link->getPaginationLink(
                    false,
                    false,
                    $this->n,
                    false,
                    1,
                    false
                )
            );
        }

        // Remove the page parameter in order to get a clean URL for the pagination template
        $current_url = preg_replace(
            '/(\?)?(&amp;)?p=\d+/',
            '$1',
            Tools::htmlentitiesUTF8($_SERVER['REQUEST_URI'])
        );

        if ($this->n != $default_products_per_page) {
            $this->context->cookie->nb_item_per_page = $this->n;
        }

        $pages_nb = ceil($total_products / (int)$this->n);
        if ($this->p > $pages_nb && $total_products != 0) {
            Tools::redirect(
                $this->context->link->getPaginationLink(
                    false,
                    false,
                    $this->n,
                    false,
                    $pages_nb,
                    false
                )
            );
        }

        $range = 2; /* how many pages around page selected */
        $start = (int)($this->p - $range);
        if ($start < 1) {
            $start = 1;
        }
        $stop = (int)($this->p + $range);
        if ($stop > $pages_nb) {
            $stop = (int)$pages_nb;
        }

        if (version_compare(_PS_VERSION_, '1.7.0', '>=')) {
            $totalItems = $total_products;
            $number_of_pages = (int)($total_products / $reviews_per_page);
            if (($total_products % $reviews_per_page) > 0) {
                $number_of_pages++;
            }
            $itemsShownFrom = (($this->p - 1) * $reviews_per_page) + 1;
            $itemsShownTo = (($this->p - 1) * $reviews_per_page) + $this->n;
            $pagination = new \PrestaShop\PrestaShop\Core\Product\Search\Pagination();
            $pagination->setPagesCount($number_of_pages);
            $pagination->setPage($this->p);

            $pagination_array = array(
                'should_be_displayed' => count($pagination->buildLinks()) > 3,
                'total_items'         => $totalItems,
                'p'                   => $this->p, // solo para debug
                'reviews_per_page'    => $reviews_per_page, // solo para debug
                'items_shown_from'    => $itemsShownFrom,
                'items_shown_to'      => ($itemsShownTo <= $totalItems) ? $itemsShownTo : $totalItems,
                'pages'               => array_map(
                    'LGCommentsReviewsModuleFrontController::updatePagesLink',
                    $pagination->buildLinks()
                ),
            );
            $this->context->smarty->assign('pagination', $pagination_array);
        }

        $this->context->smarty->assign(array(
            'nb_products'       => $total_products,
            'products_per_page' => $this->n,
            'pages_nb'          => $pages_nb,
            'p'                 => $this->p,
            'n'                 => $this->n,
            'nArray'            => $nArray,
            'range'             => $range,
            'start'             => $start,
            'stop'              => $stop,
            'current_url'       => $current_url,
            'reviewsbypage'     => $reviews_per_page,
            'dateformat'        => $this->getDateFormat(),
            'is_https' => (array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS'] == "on" ? 'true' : 'false'),
        ));
    }

    private function updatePagesLink($link)
    {
        $link['url'] = $this->updateQueryString(
            array(
                'page' => $link['page'],
            )
        );
        return $link;
    }

    public function sendReview($ajax = false)
    {
        $id_lang = (int)Language::getIdByIso(pSQL(Tools::getValue('lg_iso', false)));
        $saved   = false;

        $customer = Context::getContext()->customer;

        if (Validate::isLoadedObject($customer) && (int)(Tools::getValue('lg_id_customer', 0)) == $customer->id) {
            if ((int)Tools::getValue('send_store_review')) {
                if ($saved = $this->saveStoreReview($id_lang) !== false) {
                    $this->sendStoreMails($id_lang);
                }
            } elseif ((int)Tools::getValue('send_product_review')) {
                if (($saved = $this->saveProductReview($id_lang)) !== false) {
                    $this->sendProductMails($id_lang);
                }
            }
        }

        if ($ajax) {
            echo LGUtils::jsonEncode(
                array(
                    'has_error' => !$saved
                )
            );
            die();
        } else {
            return $saved;
        }
    }

    private function saveStoreReview($id_lang)
    {
        $maxposition       = (int)$this->getMaxPosition();
        $commentvalidation = Configuration::get('PS_LGCOMMENTS_VALIDATION');

        $result = DB::getInstance()->insert(
            LGStoreComment::$definition['table'],
            array(
                'id_order'    => 0,
                'id_customer' => pSQL(Tools::getValue('lg_id_customer', '')),
                'id_lang'     => (int)$id_lang,
                'stars'       => pSQL(Tools::getValue('lg_score', '')),
                'nick'        => pSQL(Tools::getValue('lg_nick', '')),
                'title'       => pSQL(Tools::getValue('lg_title', '')),
                'comment'     => pSQL(Tools::getValue('lg_comment', '')),
                'answer'      => '',
                'active'      => !$commentvalidation,
                'position'    => ($maxposition + 1),
                'date'        => date('Y-m-d H:i:s', strtotime('now')),
            )
        );

        return $result;
    }

    private function sendStoreMails($id_lang)
    {
        $id_customer   = (int)Tools::getValue('lg_id_customer');
        $iso_language  = Language::getIsoById((int)$id_lang);
        $customerEmail = $this->getCustomerEmail($id_customer);
        $firstname     = $this->getFirstname($id_customer);

        $langs = Language::getLanguages();
        foreach ($langs as $lang) {
            if ($id_lang == $lang['id_lang']) {
                $subject2 = Configuration::get('PS_LGCOMMENTS_SUBJECT2' . $lang['iso_code']);
            }
        }

        $templateVars = array(
            '{firstname}' => $firstname,
        );

        // Check if email template exists for current iso code. If not, use English template.
        $module_path   = _PS_MODULE_DIR_ . 'lgcomments/mails/' . $iso_language . '/';
        $template_path = _PS_THEME_DIR_ . 'modules/lgcomments/mails/' . $iso_language . '/';
        if (is_dir($module_path) or is_dir($template_path)) {
            $langId = (int)$id_lang;
        } else {
            $langId = (int)Language::getIdByIso('en');
        }

        @Mail::Send(
            (int)$langId,
            'thank-you',
            $subject2,
            $templateVars,
            $customerEmail,
            null,
            null,
            Configuration::get('PS_SHOP_NAME'),
            null,
            null,
            dirname(__FILE__) . '/../../mails/'
        );

        $email_cron    = Configuration::get('PS_LGCOMMENTS_EMAIL_CRON');
        $email_alerts  = Configuration::get('PS_LGCOMMENTS_EMAIL_ALERTS');
        if ($email_cron and $email_alerts == 1) {
            $templateVars = array(
                '{customer_firstname}' => $this->getFirstname($id_customer),
                '{customer_lastname}'  => $this->getLastname($id_customer),
                '{score_store}'        => (int)Tools::getValue('lg_score'),
                '{title_store}'        => pSQL(Tools::getValue('title_store')),
                '{comment_store}'      => pSQL(Tools::getValue('comment_store')),
            );
            // Check if email template exists for current iso code. If not, use English template.
            $default        = Configuration::get('PS_LANG_DEFAULT');
            $module_path2   = _PS_MODULE_DIR_ . 'lgcomments/mails/' . $iso_language . '/';
            $template_path2 = _PS_THEME_DIR_ . 'modules/lgcomments/mails/' . $iso_language . '/';
            if (is_dir($module_path2) or is_dir($template_path2)) {
                $langId2 = $default;
            } else {
                $langId2 = (int)Language::getIdByIso('en');
            }
            @Mail::Send(
                (int)$langId2,
                'new-review-store',
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

    private function saveProductReview($id_lang)
    {
        $maxposition       = (int)$this->getMaxPosition();
        $commentvalidation = Configuration::get('PS_LGCOMMENTS_VALIDATION');

        $data = array(
            'id_product'  => pSQL(Tools::getValue('lg_id_product', 0)),
            'id_customer' => pSQL(Tools::getValue('lg_id_customer', 0)),
            'id_lang'     => (int)$id_lang,
            'stars'       => pSQL(Tools::getValue('lg_score', 0)),
            'nick'        => pSQL(Tools::getValue('lg_nick', '')),
            'title'       => pSQL(Tools::getValue('lg_title', '')),
            'comment'     => pSQL(Tools::getValue('lg_comment', '')),
            'answer'      => '',
            'active'      => !$commentvalidation,
            'position'    => ($maxposition + 1),
            'date'        => date('Y-m-d H:i:s', strtotime('now')),
        );

        return DB::getInstance()->insert(
            'lgcomments_productcomments',
            $data
        );
    }

    private function sendProductMails($id_lang)
    {
        $id_customer   = (int)Tools::getValue('lg_id_customer');
        $customerEmail = $this->getCustomerEmail($id_customer);
        $langs         = Language::getLanguages();
        $iso_language  = Language::getIsoById((int)$id_lang);
        foreach ($langs as $lang) {
            if ($id_lang == $lang['id_lang']) {
                $subject2 = Configuration::get('PS_LGCOMMENTS_SUBJECT2' . $lang['iso_code']);
            }
        }
        $firstname = $this->getFirstname($id_customer);
        $templateVars = array(
            '{firstname}' => $firstname,
        );
        // Check if email template exists for current iso code. If not, use English template.
        $module_path   = _PS_MODULE_DIR_ . 'lgcomments/mails/' . $iso_language . '/';
        $template_path = _PS_THEME_DIR_ . 'modules/lgcomments/mails/' . $iso_language . '/';
        if (is_dir($module_path) or is_dir($template_path)) {
            $langId = (int)$id_lang;
        } else {
            $langId = (int)Language::getIdByIso('en');
        }
        @Mail::Send(
            (int)$langId,
            'thank-you',
            $subject2,
            $templateVars,
            $customerEmail,
            null,
            null,
            Configuration::get('PS_SHOP_NAME'),
            null,
            null,
            dirname(__FILE__) . '/../../mails/'
        );

        $email_cron       = Configuration::get('PS_LGCOMMENTS_EMAIL_CRON');
        $email_alerts     = Configuration::get('PS_LGCOMMENTS_EMAIL_ALERTS');
        $product_name     = $this->getProductName((int)Tools::getValue('id_product', 0), $id_lang);
        if ($email_cron and $email_alerts == 1) {
            $templateVars = array(
                '{customer_firstname}' => $this->getFirstname($id_customer),
                '{customer_lastname}'  => $this->getLastname($id_customer),
                '{product_name}'       => $product_name,
                '{lg_score}'           => (int)Tools::getValue('lg_score'),
                '{lg_title}'           => pSQL(Tools::getValue('lg_title')),
                '{lg_comment}'         => pSQL(Tools::getValue('lg_comment')),
            );
            // Check if email template exists for current iso code. If not, use English template.
            $default        = Configuration::get('PS_LANG_DEFAULT');
            $module_path2   = _PS_MODULE_DIR_ . 'lgcomments/mails/' . $iso_language . '/';
            $template_path2 = _PS_THEME_DIR_ . 'modules/lgcomments/mails/' . $iso_language . '/';
            if (is_dir($module_path2) or is_dir($template_path2)) {
                $langId2 = $default;
            } else {
                $langId2 = (int)Language::getIdByIso('en');
            }
            @Mail::Send(
                (int)$langId2,
                'new-review-product',
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

    public function setMedia()
    {
        parent::setMedia();
        $this->addJqueryPlugin('fancybox');
        if (version_compare(_PS_VERSION_, '1.6.0', '>=')) {
            $this->addCSS(_PS_MODULE_DIR_ . 'lgcomments/views/css/store_reviews.css');
            $this->addJS(_PS_MODULE_DIR_ . 'lgcomments/views/js/store_reviews_17.js');
        }
    }

    public function getBreadcrumbLinks()
    {
        $breadcrumb = parent::getBreadcrumbLinks();
        $breadcrumb['links'][] = array(
            'title' => $this->module->l('Customer reviews', 'reviews'),
            'url'   => $this->context->link->getModuleLink($this->module->name, 'reviews'),
        );
        return $breadcrumb;
    }

    public function displayAjaxGetReviews()
    {
        $response = array(
            'html' => $this->context->smarty->fetch($this->template)
        );
        if (version_compare(_PS_VERSION_, '1.7.0', '>=')) {
            die(json_encode($response)); // Tools::jsonEncode decrecated for Prestashop 1.7
        } else {
            die(Tools::jsonEncode($response));
        }
    }

    public function displayAjaxSendReview()
    {
        $this->sendReview(true);
    }
}
