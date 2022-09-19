<?php

namespace DHLParcel\Shipping\Model\Core;

use ReflectionClass;

class Settings extends SingletonAbstract
{
    use TranslateTrait;

    const PREFIX = 'DHLPARCEL_SHIPPING_';

    const API_USER_ID = self::PREFIX . 'API_USER_ID';
    const API_KEY = self::PREFIX . 'API_KEY';
    const API_TEST_AUTHENTICATE_BUTTON = self::PREFIX . 'TEST_AUTHENTICATE_BUTTON';
    const API_ACCOUNT_ID = self::PREFIX . 'API_ACCOUNT_ID';

    const BUSINESS = self::PREFIX . 'BUSINESS';
    const AUTO_DEFAULT_REFERENCE = self::PREFIX . 'AUTO_DEFAULT_REFERENCE';
    const AUTO_DEFAULT_REFERENCE_CUSTOM = self::PREFIX . 'AUTO_DEFAULT_REFERENCE_CUSTOM';
    const AUTO_DEFAULT_REFERENCE2 = self::PREFIX . 'AUTO_DEFAULT_REFERENCE2';
    const AUTO_DEFAULT_REFERENCE2_CUSTOM = self::PREFIX . 'AUTO_DEFAULT_REFERENCE2_CUSTOM';
    const AUTO_DEFAULT_RETURN = self::PREFIX . 'AUTO_DEFAULT_RETURN';

    const AUTO_DEFAULT_REFERENCE_SOURCE_ORDER_ID = '1';
    const AUTO_DEFAULT_REFERENCE_SOURCE_REFERENCE = '2';
    const AUTO_DEFAULT_REFERENCE_SOURCE_CUSTOM = '3';

    const SHIPPING_METHOD_JUMP_BUTTON = self::PREFIX . 'SHIPPING_METHOD_JUMP_BUTTON';
    const SHIPPING_METHOD_PAYMENT_BUTTON = self::PREFIX . 'SHIPPING_METHOD_PAYMENT_BUTTON';
    const SHIPPING_METHOD_SERVICEPOINT_MAPS_KEY = self::PREFIX . 'SERVICEPOINT_MAPS_KEY';
    const SHIPPING_METHOD_RESET_BUTTON = self::PREFIX . 'SHIPPING_METHOD_RESET_BUTTON';

    const SHIPPING_ADDRESS_FIRST_NAME = self::PREFIX . 'SHIPPING_ADDRESS_FIRST_NAME';
    const SHIPPING_ADDRESS_LAST_NAME = self::PREFIX . 'SHIPPING_ADDRESS_LAST_NAME';
    const SHIPPING_ADDRESS_COMPANY = self::PREFIX . 'SHIPPING_ADDRESS_COMPANY';
    const SHIPPING_ADDRESS_POSTCODE = self::PREFIX . 'SHIPPING_ADDRESS_POSTCODE';
    const SHIPPING_ADDRESS_CITY = self::PREFIX . 'SHIPPING_ADDRESS_CITY';
    const SHIPPING_ADDRESS_STREET = self::PREFIX . 'SHIPPING_ADDRESS_STREET';
    const SHIPPING_ADDRESS_STREET_NUMBER = self::PREFIX . 'SHIPPING_ADDRESS_STREET_NUMBER';
    const SHIPPING_ADDRESS_STREET_ADDITION = self::PREFIX . 'SHIPPING_ADDRESS_STREET_ADDITION';
    const SHIPPING_ADDRESS_COUNTRY = self::PREFIX . 'SHIPPING_ADDRESS_COUNTRY';
    const SHIPPING_ADDRESS_EMAIL = self::PREFIX . 'SHIPPING_ADDRESS_EMAIL';
    const SHIPPING_ADDRESS_PHONE = self::PREFIX . 'SHIPPING_ADDRESS_PHONE';

    const DEBUG_ENABLED = self::PREFIX . 'DEBUG_ENABLED';
    const ALT_API_ENABLED = self::PREFIX . 'ALT_API_ENABLED';
    const ALT_API_URL = self::PREFIX . 'ALT_API_URL';

    public function allSettings()
    {
        $class = new ReflectionClass ( get_called_class() );
        $constants = $class->getConstants();
        unset($constants['PREFIX']);
        return array_values($constants);
    }

    public function getFormData()
    {
        return [
            'legend' => [
                'title' => $this->l('Settings'),
            ],
            'tabs'   => [
                'account' => $this->l('Account'),
                'label' => $this->l('Label'),
                'shipping_method' => $this->l('Shipping methods'),
                'delivery_times' => $this->l('Delivery Times'),
                'shipping_address' => $this->l('Shipping address'),
                'usability' => $this->l('Usability'),
                'developer' => $this->l('Developer'),
            ],
            'input'  => array_merge(
                $this->tab($this->accountFields(), 'account'),
                $this->tab($this->labelFields(), 'label'),
                $this->tab($this->shippingMethodFields(), 'shipping_method'),
                $this->tab($this->deliveryTimesFields(), 'delivery_times'),
                $this->tab($this->addressFields(), 'shipping_address'),
                $this->tab($this->usablityFields(), 'usability'),
                $this->tab($this->developerFields(), 'developer')
            ),
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right',
            ],
        ];
    }

    protected function accountFields()
    {
        return [
            [
                'type'     => 'text',
                'label'    => $this->l('API UserID'),
                'name'     => self::API_USER_ID,
                'size'     => 20,
                'required' => true,
            ],
            [
                'type'     => 'text',
                'label'    => $this->l('API Key'),
                'name'     => self::API_KEY,
                'size'     => 20,
                'required' => true,
            ],
            [
                'type'     => 'hidden',
                'name'     => self::API_TEST_AUTHENTICATE_BUTTON,
                'required' => false,

            ],
            [
                'type'     => 'text',
                'label'    => $this->l('AccountID'),
                'name'     => self::API_ACCOUNT_ID,
                'size'     => 20,
                'required' => true,
            ],
        ];
    }

    protected function labelFields()
    {
        return [
            [
                'type'     => 'select',
                'label'    => $this->l('Default receiver address'),
                'name'     => self::BUSINESS,
                'options'   => [
                    'query' => [
                        [
                            'id'   => 0,
                            'name' => $this->l('Private address'),
                        ], [
                            'id'   => 1,
                            'name' => $this->l('Business address'),
                        ]
                    ],
                    'id'    => 'id',
                    'name'  => 'name',
                ],
                'required' => true,
            ],
            [
                'type'     => 'select',
                'label'    => $this->l('Auto-enable: Label reference'),
                'name'     => self::AUTO_DEFAULT_REFERENCE,
                'options'  => [
                    'query' => [
                        [
                            'id'   => 0,
                            'name' => $this->l('No'),
                        ], [
                            'id'   => self::AUTO_DEFAULT_REFERENCE_SOURCE_ORDER_ID,
                            'name' => $this->l('Yes, Order ID'),
                        ], [
                            'id'   => self::AUTO_DEFAULT_REFERENCE_SOURCE_REFERENCE,
                            'name' => $this->l('Yes, Order Reference'),
                        ], [
                            'id'   => self::AUTO_DEFAULT_REFERENCE_SOURCE_CUSTOM,
                            'name' => $this->l('Yes, Custom text'),
                        ]
                    ],
                    'id'    => 'id',
                    'name'  => 'name',
                ],
                'required' => true,
                'class'    => 'dhlparcel_shipping_service_option_reference'
            ],
            [
                'type'     => 'text',
                'label'    => $this->l('Default text for reference'),
                'name'     => self::AUTO_DEFAULT_REFERENCE_CUSTOM,
                'required' => false,
                'desc'     => $this->l('max length is 15 characters for reference'),
                'size'     => 15
            ],
            [
                'type'     => 'select',
                'label'    => $this->l('Auto-enable: Label reference 2'),
                'name'     => self::AUTO_DEFAULT_REFERENCE2,
                'options'  => [
                    'query' => [
                        [
                            'id'   => 0,
                            'name' => $this->l('No'),
                        ], [
                            'id'   => self::AUTO_DEFAULT_REFERENCE_SOURCE_ORDER_ID,
                            'name' => $this->l('Yes, Order ID'),
                        ], [
                            'id'   => self::AUTO_DEFAULT_REFERENCE_SOURCE_REFERENCE,
                            'name' => $this->l('Yes, Order Reference'),
                        ], [
                            'id'   => self::AUTO_DEFAULT_REFERENCE_SOURCE_CUSTOM,
                            'name' => $this->l('Yes, Custom text'),
                        ]
                    ],
                    'id'    => 'id',
                    'name'  => 'name',
                ],
                'required' => true,
                'class'    => 'dhlparcel_shipping_service_option_reference'
            ],
            [
                'type'     => 'text',
                'label'    => $this->l('Default text for reference 2'),
                'desc'     => $this->l('max length is 70 characters for reference 2'),
                'name'     => self::AUTO_DEFAULT_REFERENCE2_CUSTOM,
                'required' => false,
                'size'     => 70
            ],
            [
                'type'     => 'select',
                'label'    => $this->l('Auto-enable: Return label'),
                'name'     => self::AUTO_DEFAULT_RETURN,
                'options'   => [
                    'query' => [
                        [
                            'id'   => 0,
                            'name' => $this->l('No'),
                        ], [
                            'id'   => 1,
                            'name' => $this->l('Yes'),
                        ]
                    ],
                    'id'    => 'id',
                    'name'  => 'name',
                ],
                'required' => true,
            ],
        ];
    }

    protected function shippingMethodFields()
    {
        return [
            [
                'type'     => 'hidden',
                'name'     => self::SHIPPING_METHOD_JUMP_BUTTON,
                'required' => false,

            ],
            [
                'type'     => 'hidden',
                'name'     => self::SHIPPING_METHOD_PAYMENT_BUTTON,
                'required' => false,

            ],
            [
                'type'     => 'text',
                'label'    => $this->l('Google Maps Javascript API Key'),
                'name'     => self::SHIPPING_METHOD_SERVICEPOINT_MAPS_KEY,
                'desc'     => sprintf(
                    $this->l('To show ServicePoint locations on Google Maps, please configure your credentials. No Google Maps Javascript API credentials yet? Follow the instructions %shere%s on how to get the API key.'),
                    '<a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">',
                    '</a>'),
                'size'     => 20,
                'required' => true,
            ],
            [
                'type'     => 'hidden',
                'name'     => self::SHIPPING_METHOD_RESET_BUTTON,
                'required' => false,
            ],
        ];
    }

    protected function deliveryTimesFields()
    {
        return [];
    }

    protected function addressFields()
    {
        return [
            [
                'type'     => 'text',
                'label'    => $this->l('First name'),
                'name'     => self::SHIPPING_ADDRESS_FIRST_NAME,
                'size'     => 20,
                'required' => true,
            ],
            [
                'type'     => 'text',
                'label'    => $this->l('Last name'),
                'name'     => self::SHIPPING_ADDRESS_LAST_NAME,
                'size'     => 20,
                'required' => true,
            ],
            [
                'type'     => 'text',
                'label'    => $this->l('Company'),
                'name'     => self::SHIPPING_ADDRESS_COMPANY,
                'size'     => 20,
                'required' => true,
            ],
            [
                'type'     => 'text',
                'label'    => $this->l('Postcode'),
                'name'     => self::SHIPPING_ADDRESS_POSTCODE,
                'size'     => 20,
                'required' => true,
            ],
            [
                'type'     => 'text',
                'label'    => $this->l('City'),
                'name'     => self::SHIPPING_ADDRESS_CITY,
                'size'     => 20,
                'required' => true,
            ],
            [
                'type'     => 'text',
                'label'    => $this->l('Street'),
                'name'     => self::SHIPPING_ADDRESS_STREET,
                'size'     => 20,
                'required' => true,
            ],
            [
                'type'     => 'text',
                'label'    => $this->l('Number'),
                'name'     => self::SHIPPING_ADDRESS_STREET_NUMBER,
                'size'     => 20,
                'required' => true,
            ],
            [
                'type'  => 'text',
                'label' => $this->l('Addition'),
                'name'  => self::SHIPPING_ADDRESS_STREET_ADDITION,
                'size'  => 20,
            ],
            [
                'type'     => 'select',
                'label'    => $this->l('Country'),
                'name'     => self::SHIPPING_ADDRESS_COUNTRY,
                'options'   => [
                    'query' => [
                        [
                            'id'   => 'NL',
                            'name' => $this->l('Netherlands'),
                        ], [
                            'id'   => 'BE',
                            'name' => $this->l('Belgium'),
                        ], [
                            'id'   => 'LU',
                            'name' => $this->l('Luxembourg'),
                        ], [
                            'id'   => 'ES',
                            'name' => $this->l('Spain'),
                        ]
                    ],
                    'id'    => 'id',
                    'name'  => 'name',
                ],
                'required' => true,
            ],
            [
                'type'     => 'text',
                'label'    => $this->l('Email'),
                'name'     => self::SHIPPING_ADDRESS_EMAIL,
                'size'     => 20,
                'required' => true,
            ],
            [
                'type'     => 'text',
                'label'    => $this->l('Phone'),
                'name'     => self::SHIPPING_ADDRESS_PHONE,
                'size'     => 20,
                'required' => true,
            ],
        ];
    }

    protected function usablityFields()
    {
        return [];
    }

    protected function developerFields()
    {
        return [
            [
                'type'     => 'select',
                'label'    => $this->l('Debug'),
                'name'     => self::DEBUG_ENABLED,
                'options'   => [
                    'query' => [
                        [
                            'id'   => 0,
                            'name' => $this->l('Disabled'),
                        ], [
                            'id'   => 1,
                            'name' => $this->l('Enabled'),
                        ]
                    ],
                    'id'    => 'id',
                    'name'  => 'name',
                ],
                'required' => true,
            ],
            [
                'type'     => 'select',
                'label'    => $this->l('Use alternative API'),
                'name'     => self::ALT_API_ENABLED,
                'options'   => [
                    'query' => [
                        [
                            'id'   => 0,
                            'name' => $this->l('Disabled'),
                        ], [
                            'id'   => 1,
                            'name' => $this->l('Enabled'),
                        ]
                    ],
                    'id'    => 'id',
                    'name'  => 'name',
                ],
                'required' => true,
            ],
            [
                'type'     => 'text',
                'label'    => $this->l('Alternative API url'),
                'name'     => self::ALT_API_URL,
                'size'     => 20,
                'required' => true,
            ],
        ];
    }

    protected function tab($array, $tab)
    {
        if (is_array($array) && !empty($array)) {
            foreach ($array as &$row) {
                $row['tab'] = $tab;
            }
        }
        return $array;
    }
}
