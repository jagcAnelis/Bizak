<?php

class productos_interesados extends Module
{
    public function __construct()
    {
        $this->name = 'productos_interesados';
        $this->tab = 'administration';
        $this->version = '2.1.0';
        $this->author = 'Bizakshop';
        $this->need_instance = 0;
        
        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Productos interesados');
        $this->description = $this->l('Productos interesados');
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        if( !parent::install() )
            return false;
        return true;
    }

    public function uninstall()
    {
        if( !parent::uninstall() )
            return false;
        return true;
    }

    public function getContent()
    {
        /*$tab = new Tab;
        $tab->name = array($this->context->language->id => 'Productos interesados');
        $tab->class_name = 'AdminSeguimientoNotificacionesProductos';
        $tab->id_parent = 9;
        $tab->module = $this->name;
        $tab->icon = '';
        $tab->add();*/
        return $this->renderList();
    }

    public function renderList()
    {
        $helper = new HelperList();

        if( Tools::getIsset('submitResetps_mailalert_customer_oos') )
        {
            $this->context->cookie->ps_mailalert_customer_oosFilter_product = false;
            $this->context->cookie->ps_mailalert_customer_oosOrderby = false;
            $this->context->cookie->ps_mailalert_customer_oosOrderway = false;
            $this->context->cookie->write();
            $_POST['ps_mailalert_customer_oosFilter_product'] = false;
            $_POST['ps_mailalert_customer_oosOrderby'] = false;
            $_POST['ps_mailalert_customer_oosOrderway'] = false;
        }

        $busqueda = Tools::getValue('ps_mailalert_customer_oosFilter_product', $this->context->cookie->ps_mailalert_customer_oosFilter_product);
        $where_busqueda = '';
        if( !empty($busqueda) )
        {
            $where_busqueda = ' AND pl.name LIKE "%'.$busqueda.'%"';
            $this->context->cookie->ps_mailalert_customer_oosFilter_product = $busqueda;
            $this->context->cookie->write();
        }

        $orderby = Tools::getValue('ps_mailalert_customer_oosOrderby', $this->context->cookie->ps_mailalert_customer_oosOrderby);
        $orderway = Tools::getValue('ps_mailalert_customer_oosOrderway', $this->context->cookie->ps_mailalert_customer_oosOrderway);
        $order_sql = '';
        if( !empty($orderby) && !empty($orderway) )
        {
            $orderway = strtoupper($orderway);
            $order_sql = ' ORDER BY '.$orderby.' '.$orderway;
            $this->context->cookie->ps_mailalert_customer_oosOrderby = $orderby;
            $this->context->cookie->ps_mailalert_customer_oosOrderway = $orderway;
            $this->context->cookie->write();
            $helper->orderBy = $orderby;
            $helper->orderWay = $orderway;
        }

        $items = Db::getInstance()->executeS("SELECT COUNT(mco.customer_email) as interesados, mco.id_product, mco.id_product_attribute, pl.name as product, image_shop.id_image FROM "._DB_PREFIX_."mailalert_customer_oos mco LEFT JOIN "._DB_PREFIX_."product p ON mco.id_product = p.id_product LEFT JOIN "._DB_PREFIX_."product_lang pl ON p.id_product = pl.id_product LEFT JOIN "._DB_PREFIX_."image_shop image_shop ON (image_shop.id_product = p.id_product AND image_shop.cover = 1 AND image_shop.id_shop = ".$this->context->shop->id.") LEFT JOIN "._DB_PREFIX_."image i ON (i.id_image = image_shop.id_image) WHERE pl.id_lang = ".$this->context->language->id." ".$where_busqueda." GROUP BY id_product, id_product_attribute".$order_sql);

        $this->fields_list = array(
            'image' => array(
                'title' => $this->l('Image'),
                'align' => 'center',
                'image' => 'p',
                'orderby' => false,
                'filter' => false,
                'search' => false
            ),
            'product' => array(
                'title' => $this->l('Producto'),
                'type' => 'text',
            ),
            'interesados' => array(
                'title' => $this->l('Interesados'),
                'width' => 140,
                'type' => 'text',
                'align' => 'center',
                'search' => false
            )
        );

        $helper->shopLinkType = '';
        $helper->simple_header = false;
        $helper->actions = array();
        $helper->table_id = 'id_product';
        $helper->identifier = 'id_product';
        $helper->show_toolbar = true;
        $helper->title = $this->l('Productos Interesados');
        $helper->table = _DB_PREFIX_.'mailalert_customer_oos';
        $helper->listTotal = count($items);
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->imageType = 'jpg';

        $page = Tools::getValue('submitFilterps_mailalert_customer_oos', (int)1);
        $pagination = Tools::getValue('ps_mailalert_customer_oos_pagination', $helper->_default_pagination);
        $items = $this->getPaginatedItems($items, $page, $pagination);

        return $helper->generateList($items, $this->fields_list);
    }

    private function getPaginatedItems($items, $page, $pagination)
    {
        if( count($items) > $pagination )
            $items = array_slice( $items, $pagination * ($page - 1), $pagination );
        return $items;
    }
}
