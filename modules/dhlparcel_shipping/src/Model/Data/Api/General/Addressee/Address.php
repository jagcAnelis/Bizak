<?php

namespace DHLParcel\Shipping\Model\Data\Api\General\Addressee;

use DHLParcel\Shipping\Model\Data\AbstractData;

class Address extends AbstractData
{
    public $countryCode;
    public $postalCode;
    public $city;
    public $street;
    public $number;
    public $isBusiness;
    public $addition;
}
