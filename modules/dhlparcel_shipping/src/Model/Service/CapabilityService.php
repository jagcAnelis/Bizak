<?php

namespace DHLParcel\Shipping\Model\Service;

use DHLParcel\Shipping\Model\Core\SingletonAbstract;
use DHLParcel\Shipping\Model\Api\Connector;
use DHLParcel\Shipping\Model\Data\Api\Request\CapabilityCheck;
use DHLParcel\Shipping\Model\Data\Api\Response\Capability;
use DHLParcel\Shipping\Model\Data\Api\Response\Capability\Option;
use DHLParcel\Shipping\Model\Data\Capability\Product;
use Configuration;
use DHLParcel\Shipping\Model\Core\Settings;


class CapabilityService extends SingletonAbstract
{
    public function check($toCountry = '', $toPostalCode = '', $toBusiness = null, $requestOptions = [])
    {
        $capabilityCheck = $this->createCapabilityCheck(
            $toCountry,
            $toPostalCode,
            $toBusiness,
            $requestOptions
        );

        $response = Connector::instance()->get('capabilities/business', $capabilityCheck->toArray(true));
        $capabilities = [];
        if ($response && is_array($response)) {
            foreach ($response as $entry) {
                $capabilities[] = new Capability($entry);
            }
        }

        return $capabilities;
    }

    /**
     * @param $capabilities
     * @return Option[]
     */
    public function getOptions($capabilities)
    {
        $allowedOptions = [];
        if (is_array($capabilities)) {
            foreach ($capabilities as $capability) {
                if (isset($capability->options) && is_array($capability->options)) {
                    foreach ($capability->options as $option) {
                        if (isset($option->key)) {
                            $allowedOptions[$option->key] = $option;
                        }
                    }
                }
            }
        }
        return $allowedOptions;
    }

    /**
     * @param $capabilities
     * @return Product[]
     */
    public function getSizes($capabilities)
    {
        $uniqueSizes = [];
        if (is_array($capabilities)) {
            foreach ($capabilities as $capability) {
                if (isset($capability->parcelType)) {
                    if (!array_key_exists($capability->parcelType->key, $uniqueSizes)) {
                        $uniqueSizes[$capability->parcelType->key] = $capability->parcelType;
                    }
                }
            }
        }
        usort($uniqueSizes, [$this, 'sortByWeight']);
        return $uniqueSizes;
    }

    protected function createCapabilityCheck($toCountry, $toPostalCode, $toBusiness, $requestOptions)
    {
        $accountNumber = Configuration::get(Settings::API_ACCOUNT_ID);
        $fromCountry = Configuration::get(Settings::SHIPPING_ADDRESS_COUNTRY);
        $fromPostalCode = Configuration::get(Settings::SHIPPING_ADDRESS_POSTCODE);

        /** @var \DHLParcel\Shipping\Model\Data\Api\Request\CapabilityCheck $capabilityCheck */
        $capabilityCheck = new CapabilityCheck();
        $capabilityCheck->fromCountry = $fromCountry;
        $capabilityCheck->fromPostalCode = strtoupper($fromPostalCode);
        $capabilityCheck->toCountry = $toCountry ?: $fromCountry;
        $capabilityCheck->accountNumber = $accountNumber;

        if ($toBusiness !== null) {
            $capabilityCheck->toBusiness = $toBusiness ? 'true' : 'false';
        }

        if ($toPostalCode) {
            $capabilityCheck->toPostalCode = strtoupper($toPostalCode);
        }

        if (is_array($requestOptions) && count($requestOptions)) {
            $capabilityCheck->option = implode(',', $requestOptions);
        }

        return $capabilityCheck;
    }

    protected function sortByWeight($one, $two)
    {
        return $one->maxWeightKg > $two->maxWeightKg;
    }
}
