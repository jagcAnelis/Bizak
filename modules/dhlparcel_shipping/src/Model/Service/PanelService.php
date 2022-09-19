<?php

namespace DHLParcel\Shipping\Model\Service;

use DHLParcel\Shipping\Model\Core\Kernel;
use DHLParcel\Shipping\Model\Core\Settings;
use DHLParcel\Shipping\Model\Core\SingletonAbstract;
use DHLParcel\Shipping\Model\Data\Api\Response\Capability\Option;
use Configuration;
use Order;

class PanelService extends SingletonAbstract
{
    protected $implementedOptions = [
        // Delivery option
        Option::KEY_PS,
        Option::KEY_DOOR,
        Option::KEY_BP,
        Option::KEY_H, // (requires custom address selection)

        // Service option
        Option::KEY_EXP,
        Option::KEY_BOUW,
        Option::KEY_REFERENCE2,
        Option::KEY_EXW,
        Option::KEY_EA,
        Option::KEY_EVE,
        Option::KEY_RECAP,
        Option::KEY_INS,
        Option::KEY_REFERENCE,
        Option::KEY_HANDT,
        Option::KEY_NBB,
        Option::KEY_ADD_RETURN_LABEL,
        Option::KEY_SSN,
        //Option::KEY_PERS_NOTE, // (will be added later in the API, but for now it's not yet available)
        Option::KEY_SDD,
        Option::KEY_S,
        //Option::KEY_IS_BULKY,
        Option::KEY_AGE_CHECK,
    ];

    public function autoEnabledOptions($orderId)
    {
        $order = new Order((int)$orderId);

        $options = [];
        if (Configuration::get(Settings::AUTO_DEFAULT_REFERENCE) === Settings::AUTO_DEFAULT_REFERENCE_SOURCE_ORDER_ID) {
            $options[Option::KEY_REFERENCE] = $orderId;
        } elseif (Configuration::get(Settings::AUTO_DEFAULT_REFERENCE) === Settings::AUTO_DEFAULT_REFERENCE_SOURCE_REFERENCE) {
            $options[Option::KEY_REFERENCE] = $order->reference;
        } elseif (Configuration::get(Settings::AUTO_DEFAULT_REFERENCE) === Settings::AUTO_DEFAULT_REFERENCE_SOURCE_CUSTOM) {
            $options[Option::KEY_REFERENCE] = Configuration::get(Settings::AUTO_DEFAULT_REFERENCE_CUSTOM);
        }

        if (Configuration::get(Settings::AUTO_DEFAULT_REFERENCE2) === Settings::AUTO_DEFAULT_REFERENCE_SOURCE_ORDER_ID) {
            $options[Option::KEY_REFERENCE2] = $orderId;
        } elseif (Configuration::get(Settings::AUTO_DEFAULT_REFERENCE2) === Settings::AUTO_DEFAULT_REFERENCE_SOURCE_REFERENCE) {
            $options[Option::KEY_REFERENCE2] = $order->reference;
        } elseif (Configuration::get(Settings::AUTO_DEFAULT_REFERENCE2) === Settings::AUTO_DEFAULT_REFERENCE_SOURCE_CUSTOM) {
            $options[Option::KEY_REFERENCE2] = Configuration::get(Settings::AUTO_DEFAULT_REFERENCE2_CUSTOM);
        }

        if (Configuration::get(Settings::AUTO_DEFAULT_RETURN)) {
            $options[Option::KEY_ADD_RETURN_LABEL] = null;
        };
        return $options;
    }

    /**
     * @param Option[] $options
     * @return Option[]
     */
    public function parseDeliveryOptions($options, $selectedOptions = [], $optionsData = [])
    {
        return $this->parseOptions($options, $selectedOptions, $optionsData, Option::OPTION_TYPE_DELIVERY);
    }

    /**
     * @param Option[] $options
     * @return Option[]
     */
    public function parseServiceOptions($options, $selectedOptions = [], $optionsData = [])
    {
        return $this->parseOptions($options, $selectedOptions, $optionsData, Option::OPTION_TYPE_SERVICE);
    }

    /**
     * @param Option[] $options
     */
    protected function parseOptions($options, $selectedOptions, $optionsData, $optionType = Option::OPTION_TYPE_SERVICE)
    {
        if (!is_array($selectedOptions)) {
            $selectedOptions = [];
        }

        $possibleOptionTypes = [
            Option::OPTION_TYPE_SERVICE,
            Option::OPTION_TYPE_DELIVERY
        ];
        if (!in_array($optionType, $possibleOptionTypes)) {
            $optionType = Option::OPTION_TYPE_SERVICE;
        }

        $filterOptions = [];
        foreach ($options as $option) {
            if (!in_array($option->key, $this->implementedOptions)) {
                continue;
            }

            if (array_key_exists($option->key, $optionsData)) {
                $optionData = $optionsData[$option->key];
            } else {
                $optionData = null;
            }

            // Hard-coded overwrite: H is a delivery method, not a service option
            if ($option->key === Option::KEY_H) {
                $option->optionType = Option::OPTION_TYPE_DELIVERY;
            }

            if ($option->optionType === $optionType) {
                // Add additional information
                $option->imageUrl = '../modules/dhlparcel_shipping/assets/images/option/' . strtolower($option->key) . '.svg';
//                $option->description = // TODO insert description logic here, replace default text
                $option->exclusionData = $this->getExclusionData($option->exclusions);

                if (in_array($option->key, $selectedOptions)) {
                    $option->preselected = true;
                }

                // Update input template
                $option = $this->getInputTemplate($option, $optionData);

                // Sort DOOR to first if available
                if ($option->key === Option::KEY_DOOR) {
                    array_unshift($filterOptions, $option);
                } else {
                    $filterOptions[] = $option;
                }
            }
        }

        if ($optionType == Option::OPTION_TYPE_SERVICE) {
            $reordered = [];
            /** @var Option $filterOption */
            foreach ($filterOptions as $key => $filterOption) {
                if ($filterOption->key === Option::KEY_REFERENCE) {
                    $reordered[0] = $filterOption;
                    unset($filterOptions[$key]);
                }
                if ($filterOption->key === Option::KEY_REFERENCE2) {
                    $filterOption->description = 'Reference 2';
                    $reordered[1] = $filterOption;
                    unset($filterOptions[$key]);
                }
            }
            $filterOptions = array_merge($reordered, $filterOptions);
        }

        return $filterOptions;
    }

    /**
     * @param Option $option
     * @return Option
     */
    protected function getInputTemplate($option, $optionData)
    {
        switch ($option->key) {
            case Option::KEY_REFERENCE:
                $option->inputTemplate = Option::INPUT_TYPE_TEXT;
                $option->inputTemplateData = [
                    'placeholder' => 'Referencia',
                    'value'       => $optionData,
                ];
                break;
            case Option::KEY_REFERENCE2:
                $option->inputTemplate = Option::INPUT_TYPE_TEXT;
                $option->inputTemplateData = [
                    'placeholder' => 'Reference 2',
                    'value'       => $optionData,
                ];
                break;

            case Option::KEY_PS:
                $servicePoint = ServicePointService::instance()->get($optionData['value'], $optionData['country']);

                $option->inputTemplate = Option::INPUT_TEMPLATE_SERVICEPOINT;
                $option->inputTemplateData = [
                    'value'        => $optionData['value'],
                    'servicePoint' => $servicePoint
                ];
                break;

            case Option::KEY_INS:
                $option->inputTemplate = Option::INPUT_TEMPLATE_PRICE;
                $option->inputTemplateData = [];
                break;

            case Option::KEY_SSN:
                $option->inputTemplate = Option::INPUT_TEMPLATE_ADDRESS;
                $option->inputTemplateData = [];
                break;

            case Option::KEY_H:
                $option->inputTemplate = Option::INPUT_TEMPLATE_TERMINAL;
                $option->inputTemplateData = [];
                break;

            default:
                $option->inputTemplate = null;
                $option->inputTemplateData = [];
        }

        if (!empty($option->inputTemplate)) {
            $option->inputTemplate = Kernel::instance()->render(
                'backoffice/order/panel/input/' . $option->inputTemplate,
                $option->inputTemplateData
            );
        }

        return $option;
    }

    /**
     * @param Option[] $exclusions
     * @return string
     */
    protected function getExclusionData($exclusions)
    {
        if (empty($exclusions) || !is_array($exclusions)) {
            return json_encode([]);
        }

        $exclusionData = [];
        foreach($exclusions as $exclusion) {
            $exclusionData[] = $exclusion->key;
        }

        return json_encode($exclusionData);
    }
}
