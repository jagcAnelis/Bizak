<?php

namespace DHLParcel\Shipping\Model\Service;

use DHLParcel\Shipping\Model\Core\SingletonAbstract;
use DHLParcel\Shipping\Model\Api\Connector;
use DHLParcel\Shipping\Model\Core\TranslateTrait;
use DHLParcel\Shipping\Model\Data\Api\Request\Shipment;
use DHLParcel\Shipping\Model\Service\Logic\ShipmentLogic;
use DHLParcel\Shipping\Model\Data\Api\Response\Capability\Option;

class ShipmentService extends SingletonAbstract
{
    use TranslateTrait;

    const FILE_PREFIX = 'DHLPPS_';
    const CREATE_ERROR = 'create';

    protected $errors = [];
    public $lastTrackingNumber = null;

    public function create($orderId, $labelSize, $options = [], $optionsData = [], $business = false)
    {
        $this->clearError(self::CREATE_ERROR);

        $returnEnabled = in_array(Option::KEY_ADD_RETURN_LABEL, $options);
        if ($returnEnabled) {
            $options = array_diff($options, [Option::KEY_ADD_RETURN_LABEL]);
        }

        $shipmentRequest = ShipmentLogic::instance()->getRequestData($orderId, [$labelSize], $options, $optionsData, $business);
        if (!$this->validateRequest($shipmentRequest)) {
            return false;
        }

        $shipmentResponse = ShipmentLogic::instance()->sendRequest($shipmentRequest);
        if (!$shipmentResponse) {
            // Attempt to retrieve last API error
            $connector = Connector::instance();
            if (isset($connector->errorCode) && isset($connector->errorMessage)) {
                $this->setError(self::CREATE_ERROR, ucfirst(sprintf($this->l('The API responded with [%1$s]: %2$s'), $connector->errorCode, $connector->errorMessage)));
            } else {
                $this->setError(self::CREATE_ERROR, ucfirst($this->l('An unexpected API error occured')));
            }
            return false;
        }

        $labels = LabelService::instance()->save($orderId, $shipmentResponse->pieces);
        if (!$labels) {
            return false;
        }

        // Update shipment tracking
        $label = reset($labels);
        $this->updateTracking($orderId, $label->tracker_code);

        if ($returnEnabled) {
            $returnShipmentRequest = ShipmentLogic::instance()->getReturnRequestData($shipmentRequest);
            $this->validateRequest($shipmentRequest);
            $returnShipmentResponse = ShipmentLogic::instance()->sendRequest($returnShipmentRequest);
            if (!$shipmentResponse) {
                //TODO code to handle revert
                // Attempt to retrieve last API error
                $connector = Connector::instance();
                if (isset($connector->errorCode) && $connector->errorCode == 502) {
                    $this->setError(self::CREATE_ERROR, ucfirst($this->l('Connection error, please try again later')));
                } elseif (isset($connector->errorCode) && isset($connector->errorMessage)) {
                    $this->setError(self::CREATE_ERROR, ucfirst(sprintf($this->l('The API responded with [%1$s]: %2$s'), $connector->errorCode, $connector->errorMessage)));
                } else {
                    $this->setError(self::CREATE_ERROR, ucfirst($this->l('An unexpected API error occured')));
                }
                return false;
            }

            $returnLabels = LabelService::instance()->save($orderId, $returnShipmentResponse->pieces);
            if (!$returnLabels) {
                //TODO code to handle revert
                return false;
            }

            $labels = array_merge($labels, $returnLabels);
        }

        return true;
    }

    protected function updateTracking($orderId, $tracking) {
        $this->lastTrackingNumber = null;

        $order = new \Order($orderId);
        if (!$order) {
            return false;
        }

        $order_carrier = new \OrderCarrier((int) $order->getIdOrderCarrier());
        if (!$order_carrier) {
            return false;
        }

        // Skip setting tracking if carrier isn't from DHL (to prevent issues with wrongful tracking links)
        $carrier = new \Carrier($order_carrier->id_carrier);
        if (!$carrier || $carrier->external_module_name !== 'dhlparcel_shipping') {
            return false;
        }

        // Legacy support (pre 1.6)
        $order->shipping_number = $tracking;
        $order->update();

        $order_carrier->tracking_number = pSQL($tracking);
        if (!$order_carrier->update()) {
            return false;
        }

        $this->lastTrackingNumber = $tracking;

        return true;
    }

    /**
     * @param Shipment $shipmentRequest
     * @return bool
     */
    protected function validateRequest($shipmentRequest)
    {
        // Cancel request if no street and housenumber are set
        if (empty($shipmentRequest->shipper->address->street)) {
            $this->setError(self::CREATE_ERROR, ucfirst(sprintf($this->l('Shipper %s field is required.'), $this->l('street'))));
            return false;
        }

        if (empty($shipmentRequest->shipper->address->number)) {
            $this->setError(self::CREATE_ERROR, ucfirst(sprintf($this->l('Shipper %s field is required.'), $this->l('house number'))));
            return false;
        }

        if (empty($shipmentRequest->receiver->address->street)) {
            $this->setError(self::CREATE_ERROR, ucfirst(sprintf($this->l('Receiver %s field is required.'), $this->l('street'))));
            return false;
        }

        if (empty($shipmentRequest->receiver->address->number)) {
            $this->setError(self::CREATE_ERROR, ucfirst(sprintf($this->l('Receiver %s field is required.'), $this->l('house number'))));
            return false;
        }

        return true;
    }

    protected function clearError($key)
    {
        if (array_key_exists($key, $this->errors)) {
            $this->errors[$key] = null;
            unset($this->errors[$key]);
        }
    }

    protected function setError($key, $value)
    {
        $this->errors[$key] = $value;
    }

    public function getError($key)
    {
        if (!array_key_exists($key, $this->errors)) {
            return null;
        }
        return $this->errors[$key];
    }
}