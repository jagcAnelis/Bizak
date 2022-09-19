<?php

namespace DHLParcel\Shipping\Model\Data\Db;

use DHLParcel\Shipping\Model\Data\AbstractData;

class Label extends AbstractData
{
    public $id_label;
    public $id_order;
    public $label_uuid;
    public $size;
    public $options;
    public $file;
    public $url;
    public $tracker_code;
    public $is_return;

    /* Additional custom information not from DB */
    public $services;
    /** @var \DHLParcel\Shipping\Model\Data\Db\Label\Action[] */
    public $actions;
    public $trackerLink;

    protected function getClassArrayMap()
    {
        return [
            'actions' => 'DHLParcel\Shipping\Model\Data\Db\Label\Action',
        ];
    }
}
