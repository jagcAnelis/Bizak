<?php

use DHLParcel\Shipping\Controller\FrontOffice\AjaxController;

class Dhlparcel_shippingLegacyModuleFrontController extends AjaxController
{
    public function initContent()
    {
        $this->ajax = true;
        parent::initContent();
    }

    public function displayAjax()
    {
        $action = Tools::getValue('action');
        if (!method_exists($this, $action.'Action')) {
            return null;
        }
        $this->{$action.'Action'}()->send();
    }
}
