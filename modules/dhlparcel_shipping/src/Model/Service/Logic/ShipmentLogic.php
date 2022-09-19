<?php

namespace DHLParcel\Shipping\Model\Service\Logic;

use DHLParcel\Shipping\Model\Api\Connector;
use DHLParcel\Shipping\Model\Core\SingletonAbstract;
use DHLParcel\Shipping\Model\UUID;
use DHLParcel\Shipping\Model\Data\Api\Request\Shipment as ShipmentRequest;
use DHLParcel\Shipping\Model\Data\Api\General\Addressee;
use DHLParcel\Shipping\Model\Data\Api\General\Addressee\Address;
use DHLParcel\Shipping\Model\Data\Api\Request\Shipment\Option;
use DHLParcel\Shipping\Model\Data\Api\Request\Shipment\Piece as PieceRequest;
use DHLParcel\Shipping\Model\Data\Api\Response\Shipment as ShipmentResponse;
use DHLParcel\Shipping\Model\Data\Api\Response\Capability\Option as CapabilityOption;
use Order;
use Address as PrestaShopAddress;
use Country;
use DHLParcel\Shipping\Model\Core\Settings;
use Configuration;
use Customer;

class ShipmentLogic extends SingletonAbstract
{
    /**
     * @param $orderId
     * @param array $options
     * @param array $pieces
     * @param bool $isBusiness
     * @return ShipmentRequest
     */
    public function getRequestData($orderId, $labelSizes, $options, $optionsData, $business)
    {
        $randomUUID = UUID::v4();
        $order = new Order($orderId);

        $receiverAddress = new PrestaShopAddress($order->id_address_delivery);
        $receiver = $this->getReceiverAddress($receiverAddress, $business);
        $shipper = $this->getShipperAddress();
        $accountId = Configuration::get(Settings::API_ACCOUNT_ID);
        $options = $this->getRequestOptions($options, $optionsData);
        $pieces = [];
        foreach($labelSizes as $labelSize) {
            $pieces = [];
            $piece = new PieceRequest();
            $piece->parcelType = $labelSize;
            $piece->quantity = 1;
            $pieces[] = $piece;
        }

        /** @var ShipmentRequest $shipmentRequest */
        $shipmentRequest = new ShipmentRequest();
        $shipmentRequest->shipmentId = $randomUUID;
        $shipmentRequest->orderReference = (string)$orderId;
        $shipmentRequest->receiver = $receiver;
        $shipmentRequest->shipper = $shipper;
        $shipmentRequest->accountId = $accountId;
        $shipmentRequest->options = $options;
        $shipmentRequest->pieces = $pieces;
        $shipmentRequest->application = 'Presta' . str_replace('.', '', _PS_VERSION_);

        return $shipmentRequest;
    }

    /**
     * @param ShipmentRequest $shipmentRequest
     * @return ShipmentRequest
     */
    public function getReturnRequestData($shipmentRequest)
    {
        // Check for alternative return address with settings
//        if ($this->helper->getConfigData('shipper/alternative_return_address')) {
//            $receiver = $this->getShipperAddress('return');
//        } elseif (!empty($shipmentRequest->onBehalfOf)) {
//            $receiver = $shipmentRequest->onBehalfOf;
//        } else {
            $receiver = $shipmentRequest->shipper;
//        }
//
//        // Check if there is an 'onBehalfOf' and unset it
//        if (!empty($shipmentRequest->onBehalfOf)) {
//            $shipmentRequest->onBehalfOf = null;
//        }
//
        $shipper = $shipmentRequest->receiver;
        $randomUUID = UUID::v4();
//
        $shipmentRequest->shipmentId = $randomUUID;
        $shipmentRequest->receiver = $receiver;
        $shipmentRequest->shipper = $shipper;
        $shipmentRequest->returnLabel = true;
//
        // Return labels are DOOR only with no other options
        $shipmentRequest->options = [
            new Option(['key' => CapabilityOption::KEY_DOOR]),
        ];

        return $shipmentRequest;
    }

    /**
     * @param ShipmentRequest $shipmentRequest
     * @return ShipmentResponse|null
     */
    public function sendRequest($shipmentRequest)
    {
        $response = Connector::instance()->post('shipments', $shipmentRequest->toArray(true));
        if (!$response) {
            return null;
        }

        /** @var ShipmentResponse $shipmentResponse */
        $shipmentResponse = new ShipmentResponse($response);
        $shipmentResponse = $this->updatePieces($shipmentResponse, $shipmentRequest);

        // Enrich pieces with postalCode
        $shipmentResponse = $this->tagPostalCode($shipmentResponse, $shipmentRequest->receiver->address->postalCode);
        return $shipmentResponse;
    }

    /**
     * @param ShipmentResponse $shipmentResponse
     * @param ShipmentRequest $shipmentRequest
     * @return ShipmentResponse
     */
    protected function updatePieces($shipmentResponse, $shipmentRequest)
    {
        $options = [];
        foreach($shipmentRequest->options as $option)
        {
            $options[] = $option->key;
        }
        $options = array_unique($options);

        if ($shipmentRequest->returnLabel) {
            $options = [CapabilityOption::KEY_ADD_RETURN_LABEL];
        }

        $pieces = [];
        foreach($shipmentResponse->pieces as $piece) {
            $piece->options = $options;
            $pieces[] = $piece;
        }
        $shipmentResponse->pieces = $pieces;

        return $shipmentResponse;
    }

    protected function tagPostalCode(ShipmentResponse $shipmentResponse, $postalCode)
    {
        if (!empty($shipmentResponse) && !empty($shipmentResponse->pieces)) {
            $updatedPieces = [];
            foreach ($shipmentResponse->pieces as $piece) {
                $piece->postalCode = strtoupper(trim($postalCode));
                $updatedPieces[] = $piece;
            }
            $shipmentResponse->pieces = $updatedPieces;
        }
        return $shipmentResponse;
    }

    /**
     * @param string $group
     * @return Addressee
     */
    protected function getShipperAddress()
    {
        /** @var Addressee $addressee */
        $addressee = new Addressee([
            'name'        => [
                'firstName'   => Configuration::get(Settings::SHIPPING_ADDRESS_FIRST_NAME),
                'lastName'    => Configuration::get(Settings::SHIPPING_ADDRESS_LAST_NAME),
                'companyName' => Configuration::get(Settings::SHIPPING_ADDRESS_COMPANY),
            ],
            'address'     => [
                'countryCode' => Configuration::get(Settings::SHIPPING_ADDRESS_COUNTRY),
                'postalCode'  => strtoupper(Configuration::get(Settings::SHIPPING_ADDRESS_POSTCODE)),
                'city'        => Configuration::get(Settings::SHIPPING_ADDRESS_CITY),
                'street'      => Configuration::get(Settings::SHIPPING_ADDRESS_STREET),
                'number'      => Configuration::get(Settings::SHIPPING_ADDRESS_STREET_NUMBER),
                'isBusiness'  => true,
                'addition'    => Configuration::get(Settings::SHIPPING_ADDRESS_STREET_ADDITION),
            ],
            'email'       => Configuration::get(Settings::SHIPPING_ADDRESS_EMAIL),
            'phoneNumber' => Configuration::get(Settings::SHIPPING_ADDRESS_PHONE),
        ]);

        return $addressee;
    }

    /**
     * @param PrestaShopAddress
     * @param bool $isBusiness
     * @return Addressee
     */
    protected function getReceiverAddress($address, $business)
    {
        $customer = new Customer($address->id_customer);

        /** @var Addressee $addressee */
        $addressee = new Addressee([
            'name'        => [
                'firstName'   => $address->firstname,
                'lastName'    => $address->lastname,
                'companyName' => $address->company ?: null,
            ],
            'address'     => [
                'countryCode' => Country::getIsoById($address->id_country),
                'postalCode'  => strtoupper($address->postcode),
                'city'        => $address->city,
                'street'      => '',
                'number'      => '',
                'isBusiness'  => $business,
                'addition'    => '',
            ],
            'email'       => $customer->email,
            'phoneNumber' => $address->phone,
        ]);

        $addressee->address = $this->updateAddressStreet($addressee->address, [$address->address1, $address->address2]);
        return $addressee;
    }

    /**
     * @param Address $address
     * @param array $street
     * @return Address
     */
    protected function updateAddressStreet(Address $address, array $street)
    {
        $fullStreet = implode(' ', $street);

        $data = $this->parseStreetData($fullStreet);
        $address->street = $data['street'];
        $address->number = $data['number'];
        $address->addition = $data['addition'];

        return $address;
    }

    /**
     * @param $raw
     * @return array [
     *      'street'   => (string) Parsed street $raw
     *      'number'   => (string) Parsed number from $raw
     *      'addition' => (string) Parsed additional street data from $raw
     * ]
     */
    protected function parseStreetData($raw)
    {
        $skipAdditionCheck = false;
        preg_match('/([^\d]*)\s*(.*)/i', trim($raw), $streetParts);
        $data = [
            'street' => isset($streetParts[1]) ? trim($streetParts[1]) : '',
            'number' => isset($streetParts[2]) ? trim($streetParts[2]) : '',
            'addition' => '',
        ];

        // Check if $street is empty
        if (strlen($data['street']) === 0) {
            // Try a reverse parse
            preg_match('/([\d]+[\w.-]*)\s*(.*)/i', trim($raw), $streetParts);
            $data['street'] = isset($streetParts[2]) ? trim($streetParts[2]) : '';
            $data['number'] = isset($streetParts[1]) ? trim($streetParts[1]) : '';
            $skipAdditionCheck = true;
        }

        // Check if $number has numbers
        if (preg_match("/\d/", $data['number']) !== 1) {
            $data['street'] = trim($raw);
            $data['number'] = '';
        } else if (!$skipAdditionCheck) {
            preg_match('/([\d]+)[ .-]*(.*)/i', $data['number'], $numberParts);
            $data['number'] = isset($numberParts[1]) ? trim($numberParts[1]) : '';
            $data['addition'] = isset($numberParts[2]) ? trim($numberParts[2]) : '';
        }

        return $data;
    }

    public function getRequestOptions($keys = [], $data = [])
    {
        $requestOptions = [];
        foreach ($keys as $key) {
            $option = new Option([
                'key' => $key,
            ]);
            if (array_key_exists($key, $data)) {
                $option->input = $data[$key];
            }

            $requestOptions[] = $option;
        }

        return $requestOptions;
    }
}
