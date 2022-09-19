<?php

namespace DHLParcel\Shipping\Model\Service;

use DHLParcel\Shipping\Model\Core\SingletonAbstract;
use DHLParcel\Shipping\Model\Data\Db\OrderData;
use Db;
use DbQuery;

class OrderService extends SingletonAbstract
{
    const SERVICEPOINT = 'servicepoint';

    public function getDataKey($orderId, $key)
    {
        $orderData = $this->load($orderId);
        if (!$orderData) {
            return null;
        }
        if (!$orderData->data || empty($orderData->data) || !is_array($orderData->data)) {
            return null;
        }
        if (!array_key_exists($key, $orderData->data)) {
            return null;
        }
        return $orderData->data[$key];
    }

    public function saveDataKey($orderId, $key, $keyData)
    {
        $orderData = $this->load($orderId);
        if (!$orderData) {
            $orderData = new OrderData();
            $orderData->id_order = $orderId;
        }
        if (!$orderData->data) {
            $orderData->data = [];
        }
        $orderData->data[$key] = $keyData;
        $this->update($orderId, $orderData->data);
    }

    /**
     * @param $orderId
     * @return OrderData|null
     */
    protected function load($orderId)
    {
        $query = new DbQuery();
        $query->from('dhlparcel_shipping_order_data');
        $query->where("id_order = '" . (int) $orderId . "'");
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($query);
        if (!$result) {
            return null;
        }
        $orderData = new OrderData($result);
        $orderData->data = json_decode($orderData->data, true);
        return $orderData;
    }

    /**
     * @param $orderId
     * @param $data
     * @return bool|OrderData|null
     */
    protected function update($orderId, $data)
    {
        $orderData = $this->load($orderId);
        if ($orderData) {
            $orderData = $this->prepareDataSave($orderData, $data);
            $success = Db::getInstance()->update('dhlparcel_shipping_order_data', $orderData->toArray(true), 'id_order = ' . $orderId);
        } else {
            $orderData = new OrderData();
            $orderData->id_order = $orderId;
            $orderData = $this->prepareDataSave($orderData, $data);
            $success = Db::getInstance()->insert('dhlparcel_shipping_order_data', $orderData->toArray(true));
        }

        if (!$success) {
            return false;
        }

        return $orderData;
    }

    /**
     * @param OrderData $orderData
     * @param $data
     * @return OrderData
     */
    protected function prepareDataSave($orderData, $data)
    {
        if (!is_array($data)) {
            $data = [];
        }
        $orderData->data = json_encode($data);
        return $orderData;
    }
}
