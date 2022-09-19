<?php

namespace DHLParcel\Shipping\Model\Data\Api\General;

use DHLParcel\Shipping\Model\Data\AbstractData;

class Addressee extends AbstractData
{
    /** @var \DHLParcel\Shipping\Model\Data\Api\General\Addressee\Name */
    public $name;
    /** @var \DHLParcel\Shipping\Model\Data\Api\General\Addressee\Address */
    public $address;
    public $email;
    public $phoneNumber;

    protected function getClassMap()
    {
        return [
            'name'    => 'DHLParcel\Shipping\Model\Data\Api\General\Addressee\Name',
            'address' => 'DHLParcel\Shipping\Model\Data\Api\General\Addressee\Address',
        ];
    }
}
