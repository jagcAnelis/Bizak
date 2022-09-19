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
        $items = array();

        $item[] = array(
            'id_customer' => 111,
            'customer_email' => 'test@test.com',
            'product' => 'producto XXXXX',
            'attribute' => 'atributo YYYYY',
            'interesados' => '123'
        );

        $this->fields_list = array(
            'customer_email' => array(
                'title' => $this->l('Customer email'),
                'width' => 140,
                'type' => 'text',
            ),
            'product' => array(
                'title' => $this->l('Product'),
                'width' => 140,
                'type' => 'text',
            ),
            'attribute' => array(
                'title' => $this->l('Attribute'),
                'width' => 140,
                'type' => 'text',
            ),
            'interesados' => array(
                'title' => $this->l('Interesados'),
                'width' => 140,
                'type' => 'text',
                'class' => 'text-center'
            )
        );
        $helper = new HelperList();
         
        $helper->shopLinkType = '';
         
        $helper->simple_header = false;
         
        $helper->actions = array();
         
        $helper->table_id = 'id_customer';
        $helper->identifier = 'id_customer';
        $helper->show_toolbar = true;
        $helper->title = $this->l('Productos Interesados');
        $helper->table = _DB_PREFIX_.'mailalert_customer_oos';
        $helper->listTotal = count($items);
         
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        return $helper->generateList($items, $this->fields_list);
    }
}
