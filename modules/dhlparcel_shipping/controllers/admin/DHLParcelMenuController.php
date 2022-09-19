<?php

class DHLParcelMenuController extends ModuleAdminController
{
    /** @var Module Instance of your module automatically set by ModuleAdminController */
    public $module;

    public function __construct()
    {
        Tools::redirectAdmin(Context::getContext()->link->getAdminLink('AdminModules', true, [], ['configure' => 'dhlparcel_shipping']));
    }
}
