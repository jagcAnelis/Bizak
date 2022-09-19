<?php

namespace DHLParcel\Shipping\Model\Core;

trait TranslateTrait
{
    /** @var \Module */
    protected $module = null;

    protected function l($string)
    {
        if (!$this->module) {
            $this->module = \Module::getInstanceByName('dhlparcel_shipping');
        }
        return $this->module->l($string);
    }
}
