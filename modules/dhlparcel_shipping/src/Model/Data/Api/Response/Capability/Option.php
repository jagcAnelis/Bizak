<?php

namespace DHLParcel\Shipping\Model\Data\Api\Response\Capability;

use DHLParcel\Shipping\Model\Data\AbstractData;

class Option extends AbstractData
{
    // Required delivery method option
    const KEY_PS = 'PS'; // Delivery to the specified DHL Parcelshop or DHL Parcelstation
    const KEY_DOOR = 'DOOR'; // Delivery to the address of the recipient
    const KEY_BP = 'BP'; // Mailbox delivery
    const KEY_H = 'H'; // Hold for collection (Terminal)

    // Additional delivery option
    const KEY_EXP = 'EXP'; // Expresser
    const KEY_BOUW = 'BOUW'; // Delivery to construction site
    const KEY_REFERENCE2 = 'REFERENCE2'; // Reference
    const KEY_EXW = 'EXW'; // Ex Works
    const KEY_EA = 'EA'; // Increased liability
    const KEY_EVE = 'EVE'; // Evening delivery
    const KEY_RECAP = 'RECAP'; // Recap
    const KEY_INS = 'INS'; // All risks insurance
    const KEY_REFERENCE = 'REFERENCE'; // Reference
    const KEY_HANDT = 'HANDT'; // Signature on delivery
    const KEY_NBB = 'NBB'; // No neighbour delivery
    const KEY_ADD_RETURN_LABEL = 'ADD_RETURN_LABEL'; // Print extra label for return shipment
    const KEY_SSN = 'SSN'; // Undisclosed sender
    const KEY_PERS_NOTE = 'PERS_NOTE'; // E-mail to receiver
    const KEY_SDD = 'SDD'; // Same-day delivery
    const KEY_S = 'S'; // Saturday delivery
    const KEY_IS_BULKY = 'IS_BULKY'; // Piece is bulky
    const KEY_AGE_CHECK = 'AGE_CHECK'; // Age check of recipient by courier

    const OPTION_TYPE_SERVICE = 'SERVICE_OPTION';
    const OPTION_TYPE_DELIVERY = 'DELIVERY_OPTION';

    const INPUT_TYPE_NUMBER = 'number';
    const INPUT_TYPE_TEXT = 'text';
    const INPUT_TYPE_ADDRESS = 'address';

    const INPUT_TEMPLATE_DOUBLE_TEXT = 'double-text';
    const INPUT_TEMPLATE_TEXT = 'text';
    const INPUT_TEMPLATE_SERVICEPOINT = 'servicepoint';
    const INPUT_TEMPLATE_TERMINAL = 'terminal';
    const INPUT_TEMPLATE_PRICE = 'price';
    const INPUT_TEMPLATE_ADDRESS = 'address';

    public $key;
    public $description;
    public $rank;
    public $code;
    public $inputType;
    public $inputMax;
    public $optionType;
    /** @var \DHLParcel\Shipping\Model\Data\Api\Response\Capability\Option[] */
    public $exclusions;

    /* Custom */
    public $imageUrl;
    public $exclusionData;
    public $preselected;
    public $inputTemplate;
    public $inputTemplateData;

    protected function getClassArrayMap()
    {
        return [
            'exclusions' => 'DHLParcel\Shipping\Model\Data\Api\Response\Capability\Option',
        ];
    }
}
