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

class LgCommentsAccountreviewsModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    public $display_column_left = false;

    public function __construct()
    {
        parent::__construct();
        $this->context = Context::getContext();
    }

    /**
     * @see FrontController::initContent()
     */
    public function initContent()
    {
        parent::initContent();
        $this->assign();
    }

    private function getDateFormat()
    {
        $format = Db::getInstance()->getValue(
            'SELECT date_format_lite '.
            'FROM '._DB_PREFIX_.'lang '.
            'WHERE id_lang = '.(int)$this->context->language->id
        );
        return $format;
    }

    /**
     * Assign wishlist template
     */
    public function assign()
    {
        // TODO: Hace falta dar la posibilidad de comentar aun cunado el email no se ha mandado. Y a quien comente
        // TODO: excluirlo de la lista de envío (aunque se debe mostrar como que ha comentado en el listado para que
        // TODO: luego no haya confusiones de que hay pedidos que no se mandan
        $lgreviews = Db::getInstance()->ExecuteS(
            'SELECT *, o.id_order '.
            'FROM '._DB_PREFIX_.'orders o '.
            'LEFT JOIN '._DB_PREFIX_.'currency c ON o.id_currency = c.id_currency '.
            'LEFT JOIN '._DB_PREFIX_.'lgcomments_orders lo ON ('.
            '   lo.id_order = o.id_order AND o.id_customer = '.(int)$this->context->customer->id.
            ') '.
            'WHERE lo.`id_order` IS NOT NULL '.
            'ORDER BY o.id_order DESC'
        );

        foreach ($lgreviews as $k => $review) {
            $lgreviews[$k]['link'] = Context::getContext()->link->getModuleLink(
                'lgcomments',
                'account',
                array('id_order' => $review['id_order'], 'lghash' => $review['hash'], 'source' => 'account'),
                null,
                $this->context->language->id,
                $this->context->shop->id,
                false
            );
        }

        $this->context->smarty->assign(
            array(
                'lgreviews' => $lgreviews,
                'dateformat' => $this->getDateFormat(),
                'tpl_dir' => _PS_THEME_DIR_,
                'base_dir_ssl' => (
                    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off')
                        ? 'https://' . $this->context->shop->domain_ssl
                        : 'http://' . $this->context->shop->domain
                )
            )
        );

        if (version_compare(_PS_VERSION_, '1.7.0', '>=')) {
            $this->setTemplate('module:lgcomments/views/templates/front/account_reviews_17.tpl');
        } else {
            $this->setTemplate('account_reviews.tpl');
        }
    }

    /**
     * Asegura la carga del JS.
     */
    public function setMedia()
    {
        parent::setMedia();
        $this->addJquery();
    }

    /**
     * Carga los estilos de la pagina y le añade el estilo de la cuenta de usuario
     * @return array
     */
    public function getTemplateVarPage()
    {
        $page = parent::getTemplateVarPage();
        $page['body_classes']['page-customer-account'] = true;
        return $page;
    }
}
