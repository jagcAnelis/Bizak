<?php

namespace DHLParcel\Shipping\Model\Service;

use DHLParcel\Shipping\Model\Core\Settings;
use DHLParcel\Shipping\Model\Core\SingletonAbstract;
use Configuration;

class SettingsService extends SingletonAbstract
{
    public function shippingCountry()
    {
        return Configuration::get(Settings::SHIPPING_ADDRESS_COUNTRY);
    }

    public function mapsKey()
    {
        return Configuration::get(Settings::SHIPPING_METHOD_SERVICEPOINT_MAPS_KEY);
    }
}
