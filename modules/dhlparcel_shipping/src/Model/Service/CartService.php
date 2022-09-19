<?php

namespace DHLParcel\Shipping\Model\Service;

use DHLParcel\Shipping\Model\Core\SingletonAbstract;
use DHLParcel\Shipping\Model\Data\Db\CartData;
use Db;
use DbQuery;

class CartService extends SingletonAbstract
{
    const SERVICEPOINT = 'servicepoint';

    public function getDataKey($cartId, $key)
    {
        $cartData = $this->load($cartId);
        if (!$cartData) {
            return null;
        }
        if (!$cartData->data || empty($cartData->data) || !is_array($cartData->data)) {
            return null;
        }
        if (!array_key_exists($key, $cartData->data)) {
            return null;
        }
        if (empty($cartData->data[$key])) {
            return null;
        }
        return $cartData->data[$key];
    }

    public function saveDataKey($cartId, $key, $keyData)
    {
        $cartData = $this->load($cartId);
        if (!$cartData) {
            $cartData = new CartData();
            $cartData->id_cart = $cartId;
        }
        if (!$cartData->data) {
            $cartData->data = [];
        }
        $cartData->data[$key] = $keyData;
        $this->update($cartId, $cartData->data);
    }

    /**
     * @param $cartId
     * @return CartData|null
     */
    protected function load($cartId)
    {
        $query = new DbQuery();
        $query->from('dhlparcel_shipping_cart_data');
        $query->where("id_cart = '" . (int) $cartId . "'");
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($query);
        if (!$result) {
            return null;
        }
        $cartData = new CartData($result);
        $cartData->data = json_decode($cartData->data, true);
        return $cartData;
    }

    /**
     * @param $cartId
     * @param $data
     * @return bool|CartData|null
     */
    protected function update($cartId, $data)
    {
        $cartData = $this->load($cartId);
        if ($cartData) {
            $cartData = $this->prepareDataSave($cartData, $data);
            $success = Db::getInstance()->update('dhlparcel_shipping_cart_data', $cartData->toArray(true), 'id_cart = ' . $cartId);
        } else {
            $cartData = new CartData();
            $cartData->id_cart = $cartId;
            $cartData = $this->prepareDataSave($cartData, $data);
            $success = Db::getInstance()->insert('dhlparcel_shipping_cart_data', $cartData->toArray(true));
        }

        if (!$success) {
            return false;
        }

        return $cartData;
    }

    /**
     * @param CartData $cartData
     * @param $data
     * @return CartData
     */
    protected function prepareDataSave($cartData, $data)
    {
        if (!is_array($data)) {
            $data = [];
        }
        $cartData->data = json_encode($data);
        return $cartData;
    }
}