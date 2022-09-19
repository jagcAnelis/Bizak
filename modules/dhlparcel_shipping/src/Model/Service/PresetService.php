<?php

namespace DHLParcel\Shipping\Model\Service;

use DHLParcel\Shipping\Model\Core\SingletonAbstract;
use DHLParcel\Shipping\Model\Core\TranslateTrait;
use DHLParcel\Shipping\Model\Data\Db\Preset;
use DHLParcel\Shipping\Model\Data\Api\Response\Capability\Option;
use Carrier;
use Context;
use Db;
use DbQuery;
use Group;
use Language;
use RangePrice;
use RangeWeight;
use Zone;

class PresetService extends SingletonAbstract
{
    use TranslateTrait;

    public function options()
    {
        return [
            // Delivery methods for preset settings for carriers
            Option::KEY_DOOR,
            Option::KEY_PS,
            Option::KEY_BP,
            Option::KEY_H,
            // Service methods for preset settings for carriers
            Option::KEY_NBB,
            Option::KEY_EVE,
            Option::KEY_S,
        ];
    }

    public function addCarriers()
    {
        $this->addCarrier(
            'DHL ServicePoint',
            'Delivered in 1-2 days',
            true,
            [Option::KEY_DOOR, Option::KEY_PS]
        );

        $this->addCarrier(
            'DHL Home Delivery',
            'Delivered in 1-2 days',
            true,
            [Option::KEY_DOOR]
        );

        $this->addCarrier(
            'DHL Evening Delivery',
            'Delivered in 1-2 days',
            true,
            [Option::KEY_DOOR, Option::KEY_EVE]
        );

        $this->addCarrier(
            'DHL No Neighbour Delivery',
            'Delivered in 1-2 days',
            true,
            [Option::KEY_DOOR, Option::KEY_NBB]
        );

        $this->addCarrier(
            'DHL Saturday Delivery',
            'Delivered in 1-2 days (also on Saturdays)',
            true,
            [Option::KEY_S]
        );
    }

    public function deleteCarriers()
    {
        $query = new DbQuery();
        $query->from('carrier');
        $query->where("external_module_name = '" . 'dhlparcel_shipping' . "' AND deleted = 0");
        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

        foreach($results as $result) {
            $carrier = new Carrier($result['id_carrier']);
            $carrier->delete();
        }
    }

    protected function addCarrier($name, $description, $link, $options)
    {
        $carrier = new Carrier();

        $carrier->name = $this->l($name);
        $carrier->is_module = true;
        $carrier->active = 0;
        $carrier->range_behavior = 1;
        $carrier->need_range = 1;
        $carrier->shipping_external = true;
        $carrier->range_behavior = 0;
        $carrier->external_module_name = 'dhlparcel_shipping';
        $carrier->shipping_method = 2;
        $carrier->url = 'https://clientesparcel.dhl.es/seguimientoenvios/integra/SeguimientoDocumentos.aspx?codigo=@&lang=sp';

        foreach (Language::getLanguages() as $lang) {
            $carrier->delay[$lang['id_lang']] = $this->l($description);
        }

        if ($carrier->add() === true) {
            @copy(
                _PS_MODULE_DIR_ . DIRECTORY_SEPARATOR . 'dhlparcel_shipping' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'dhlparcel_shipping_carrier.jpg',
                _PS_SHIP_IMG_DIR_ . '/' . (int)$carrier->id . '.jpg'
            );
            $this->update($carrier->id, $link, $options);

            $this->addZones($carrier);
            $this->addGroups($carrier);
            $this->addRanges($carrier);

            return $carrier;
        }

        return false;
    }

    protected function addGroups($carrier)
    {
        $groups_ids = array();
        $groups = Group::getGroups(Context::getContext()->language->id);
        foreach ($groups as $group)
            $groups_ids[] = $group['id_group'];

        $carrier->setGroups($groups_ids);
    }

    protected function addRanges($carrier)
    {
        $range_price = new RangePrice();
        $range_price->id_carrier = $carrier->id;
        $range_price->delimiter1 = '0';
        $range_price->delimiter2 = '10000';
        $range_price->add();

        $range_weight = new RangeWeight();
        $range_weight->id_carrier = $carrier->id;
        $range_weight->delimiter1 = '0';
        $range_weight->delimiter2 = '10000';
        $range_weight->add();
    }

    protected function addZones($carrier)
    {
        $zones = Zone::getZones();

        foreach ($zones as $zone)
            $carrier->addZone($zone['id_zone']);
    }

    public function load($carrierId)
    {
        $query = new DbQuery();
        $query->from('dhlparcel_shipping_presets');
        $query->where("id_carrier = '" . (int) $carrierId . "'");
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($query);

        if (!$result) {
            return null;
        }

        $preset = new Preset($result);
        $preset->options = json_decode($preset->options, true);
        $preset->temp_options = json_decode($preset->temp_options, true);

        return $preset;
    }

    /**
     * @param array $options
     * @return Preset[]
     */
    public function search($options)
    {
        $query = new DbQuery();
        $query->from('dhlparcel_shipping_presets');


        $where = null;
        foreach ($options as $option) {
            $where = isset($where) ? $where . ' AND ' : '';
            $where .= "options LIKE '%" . $option . "%'";
        }
        if ($where) {
            $query->where($where);
        }

        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

        $presets = [];
        foreach($results as $result)
        {
            $preset = new Preset($result);
            $preset->options = json_decode($preset->options, true);
            $preset->temp_options = json_decode($preset->temp_options, true);
            $presets[] = $preset;
        }

        return $presets;
    }

    public function update($carrierId, $link, $options, $temporary = false)
    {
        $preset = $this->load($carrierId);
        if ($preset) {
            $preset = $this->prepareDataSave($preset, $link, $options, $temporary);
            $success = Db::getInstance()->update('dhlparcel_shipping_presets', $preset->toArray(true), 'id_carrier = ' . $carrierId);
        } else {
            $preset = new Preset();
            $preset->id_carrier = $carrierId;
            $preset = $this->prepareDataSave($preset, $link, $options, $temporary);
            $success = Db::getInstance()->insert('dhlparcel_shipping_presets', $preset->toArray(true));
        }

        if (!$success) {
            return false;
        }

        return $preset;
    }

    /**
     * @param Preset $preset
     * @param mixed $link
     * @param array $options
     * @param boolean $temporary
     * @return Preset
     */
    protected function prepareDataSave($preset, $link, $options, $temporary)
    {
        if (!is_array($options)) {
            $options = [];
        }

        if ($temporary) {
            $preset->temp_link = $link ? 1 : 0;
            $preset->temp_options = json_encode($options);
            $preset->options = json_encode($preset->options);
        } else {
            $preset->link = $link ? 1 : 0;
            $preset->options = json_encode($options);
            $preset->temp_options = json_encode($preset->temp_options);
        }

        return $preset;
    }
}
