<?php

use DHLParcel\Shipping\Controller\BackOffice\AjaxController;

class DHLParcelAjaxController extends AjaxController
{
    public function run(){
        $action = Tools::getValue('action');
        if (!method_exists($this, $action.'Action')) {
            return null;
        }
        $this->{$action.'Action'}()->send();
    }
}
