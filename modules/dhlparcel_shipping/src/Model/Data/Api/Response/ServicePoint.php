<?php

namespace DHLParcel\Shipping\Model\Data\Api\Response;

use DHLParcel\Shipping\Model\Data\AbstractData;
use DHLParcel\Shipping\Model\Data\Api\General\Addressee\Address;
use DHLParcel\Shipping\Model\Data\Api\Response\ServicePoint\GeoLocation;
use DHLParcel\Shipping\Model\Data\Api\Response\ServicePoint\OpeningTime;

class ServicePoint extends AbstractData
{
    public $id;
    public $name;
    public $keyword;
    /** @var Address $address */
    public $address;
    /** @var GeoLocation $geoLocation */
    public $geoLocation;
    public $distance;
    /** @var OpeningTime[] $openingTimes */
    public $openingTimes;
    public $shopType;
    public $country;

    protected function getClassMap()
    {
        return [
            'address'     => 'DHLParcel\Shipping\Model\Data\Api\General\Addressee\Address',
            'geoLocation' => 'DHLParcel\Shipping\Model\Data\Api\Response\ServicePoint\GeoLocation',
        ];
    }

    protected function getClassArrayMap()
    {
        return [
            'openingTimes' => 'DHLParcel\Shipping\Model\Data\Api\Response\ServicePoint\OpeningTime',
        ];
    }
}
