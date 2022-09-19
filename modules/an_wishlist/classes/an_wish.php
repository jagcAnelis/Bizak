<?php
/**
 * 2020 Anvanto
 *
 * NOTICE OF LICENSE
 *
 * This file is not open source! Each license that you purchased is only available for 1 wesite only.
 * If you want to use this file on more websites (or projects), you need to purchase additional licenses. 
 * You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
 *
 *  @author Anvanto <anvantoco@gmail.com>
 *  @copyright  2020 Anvanto
 *  @license    Valid for 1 website (or project) for each purchase of license
 *  International Registered Trademark & Property of ETS-Soft
 */

class an_wish extends ObjectModel
{
    /**
     * @var int
     */
    public $id_wishlist;
    /**
     * @var int
     */
    public $id;
    /**
     * @var int
     */
    public $id_shop;

    public $id_customer;
    public $is_guest;

    /** @var string Object creation date */
    public $date_add;

    /**
     * @var array
     */
    public static $definition = array(
        'table' => 'an_wishlist',
        'primary' => 'id_wishlist',
        'multilang' => false,
        'multilang_shop' => false,
        'fields' => array(
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_customer' => array('type' =>self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true ),
            'is_guest' => array('type' =>self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true ),
        ),
    );
    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        parent::__construct($id, $id_lang, $id_shop);
        if (Shop::isFeatureActive()) {
            $this->id_shop = Context::getContext()->shop->id;
        } else {
            $this->id_shop = 1;
        }
    }



	public static function findWishlistByCustomer($idCustomer, $is_guest = 0){
		
        if (!$idCustomer) {
            return false;
        }

        return Db::getInstance()->getValue('
            SELECT `id_wishlist`
            FROM `' . _DB_PREFIX_ . 'an_wishlist`
            WHERE `id_customer` = ' . (int)$idCustomer . '
            AND `is_guest` = ' . (int)$is_guest . '
            AND `id_shop` = ' . (int) Context::getContext()->shop->id);
	}

























}
