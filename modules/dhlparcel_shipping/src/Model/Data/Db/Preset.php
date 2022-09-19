<?php

namespace DHLParcel\Shipping\Model\Data\Db;

use DHLParcel\Shipping\Model\Data\AbstractData;

class Preset extends AbstractData
{
    public $id_preset;
    public $id_carrier;
    public $link;
    public $options;
    public $temp_link;
    public $temp_options;
}
