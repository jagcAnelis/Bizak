<?php

class AdminSeguimientoNotificacionesProductosController extends ModuleAdminController
{
	public function __construct()
    {
        $context = Context::getContext();
        Tools::redirectAdmin($context->link->getAdminLink('AdminModules')."&configure=productos_interesados");
        parent::__construct();
    }
}
