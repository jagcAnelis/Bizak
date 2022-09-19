<?php

namespace DHLParcel\Shipping\Controller\BackOffice;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Tools;
use Context;

class MenuController extends FrameworkBundleAdminController
{
    public function linkAction()
    {
        Tools::redirectAdmin($this->getContext()->link->getAdminLink('AdminModules', true, [], ['configure' => 'dhlparcel_shipping']));
    }
}
