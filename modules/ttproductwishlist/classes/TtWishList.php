<?php
/**
*  @author    TemplateTrip
*  @copyright 2015-2017 TemplateTrip. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Ttwishlist extends ObjectModel
{
    /** @var integer Wishlist ID */
    public $id;

    /** @var integer Customer ID */
    public $id_customer;

    /** @var integer Token */
    public $token;

    /** @var integer Name */
    public $name;

    /** @var string Object creation date */
    public $date_add;

    /** @var string Object last modification date */
    public $date_upd;

    /** @var string Object last modification date */
    public $id_shop;

    /** @var string Object last modification date */
    public $id_shop_group;

    /** @var integer default */
    public $default;

    /**
    * @see ObjectModel::$definition
    */
    public static $definition = array(
        'table' => 'ttwishlist',
        'primary' => 'id_wishlist',
        'fields' => array(
            'id_customer' =>    array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'token' =>          array('type' => self::TYPE_STRING, 'validate' => 'isMessage', 'required' => true),
            'name' =>           array('type' => self::TYPE_STRING, 'validate' => 'isMessage', 'required' => true),
            'date_add' =>       array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'date_upd' =>       array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'id_shop' =>        array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_shop_group' =>  array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'default' =>        array('type' => self::TYPE_BOOL, 'validate' => 'isUnsignedId'),
        )
    );

    public function delete()
    {
        Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'ttwishlist_email` WHERE `id_wishlist` = '.(int)($this->id));
        Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'ttwishlist_product` WHERE `id_wishlist` = '.(int)($this->id));
        if ($this->default) {
            $result = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'ttwishlist` WHERE `id_customer` = '.(int)$this->id_customer.' AND `id_wishlist` != '.(int)$this->id.' LIMIT 1');
            foreach ($result as $res) {
                Db::getInstance()->update('ttwishlist', array('default' => '1'), 'id_wishlist = '.(int)$res['id_wishlist']);
            }
        }
        if (isset($this->context->cookie->id_wishlist)) {
            unset($this->context->cookie->id_wishlist);
        }
        return (parent::delete());
    }

    /**
    * Increment counter
    *
    * @return boolean succeed
    */
    public static function incCounter($id_wishlist)
    {
        if (!Validate::isUnsignedId($id_wishlist)) {
            die(Tools::displayError());
        }
        $result = Db::getInstance()->getRow('
            SELECT `counter`
            FROM `'._DB_PREFIX_.'ttwishlist`
            WHERE `id_wishlist` = '.(int)$id_wishlist);
        if ($result == false || !count($result) || empty($result) === true) {
            return (false);
        }

        return Db::getInstance()->execute('
            UPDATE `'._DB_PREFIX_.'ttwishlist` SET
            `counter` = '.(int)($result['counter'] + 1).'
            WHERE `id_wishlist` = '.(int)$id_wishlist);
    }


    public static function isExistsByNameForUser($name)
    {
        if (Shop::getContextShopID()) {
            $shop_restriction = 'AND id_shop = '.(int)Shop::getContextShopID();
        } elseif (Shop::getContextShopGroupID()) {
            $shop_restriction = 'AND id_shop_group = '.(int)Shop::getContextShopGroupID();
        } else {
            $shop_restriction = '';
        }

        $context = Context::getContext();
        return Db::getInstance()->getValue('
            SELECT COUNT(*) AS total
            FROM `'._DB_PREFIX_.'ttwishlist`
            WHERE `name` = \''.pSQL($name).'\'
            AND `id_customer` = '.(int)$context->customer->id.'
            '.$shop_restriction);
    }

    /**
    * Return true if wishlist exists else false
    *
    *  @return boolean exists
    */
    public static function exists($id_wishlist, $id_customer, $return = false)
    {
        if (!Validate::isUnsignedId($id_wishlist) || !Validate::isUnsignedId($id_customer)) {
            die(Tools::displayError());
        }
        $result = Db::getInstance()->getRow('
            SELECT `id_wishlist`, `name`, `token`
            FROM `'._DB_PREFIX_.'ttwishlist`
            WHERE `id_wishlist` = '.(int)($id_wishlist).'
            AND `id_customer` = '.(int)($id_customer).'
            AND `id_shop` = '.(int)Context::getContext()->shop->id);
        if (empty($result) === false && $result != false && sizeof($result)) {
            if ($return === false) {
                return (true);
            } else {
                return ($result);
            }
        }
        return (false);
    }

    /**
    * Get Customers having a wishlist
    *
    * @return array Results
    */
    public static function getCustomers()
    {
        $cache_id = 'WhishList::getCustomers';
        if (!Cache::isStored($cache_id)) {
            $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
                SELECT c.`id_customer`, c.`firstname`, c.`lastname`
                FROM `'._DB_PREFIX_.'ttwishlist` w
                INNER JOIN `'._DB_PREFIX_.'customer` c ON c.`id_customer` = w.`id_customer`
                ORDER BY c.`firstname` ASC');
            Cache::store($cache_id, $result);
        }
        return Cache::retrieve($cache_id);
    }

    /**
    * Get ID wishlist by Token
    *
    * @return array Results
    */
    public static function getByToken($token)
    {
        if (!Validate::isMessage($token)) {
            die(Tools::displayError());
        }
        return (Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
            SELECT w.`id_wishlist`, w.`name`, w.`id_customer`, c.`firstname`, c.`lastname`
            FROM `'._DB_PREFIX_.'ttwishlist` w
            INNER JOIN `'._DB_PREFIX_.'customer` c ON c.`id_customer` = w.`id_customer`
            WHERE `token` = \''.pSQL($token).'\''));
    }

    /**
    * Get Wishlists by Customer ID
    *
    * @return array Results
    */
    public static function getByIdCustomer($id_customer)
    {
        if (!Validate::isUnsignedId($id_customer)) {
            die(Tools::displayError());
        }
        if (Shop::getContextShopID()) {
            $shop_restriction = 'AND id_shop = '.(int)Shop::getContextShopID();
        } elseif (Shop::getContextShopGroupID()) {
            $shop_restriction = 'AND id_shop_group = '.(int)Shop::getContextShopGroupID();
        } else {
            $shop_restriction = '';
        }

        $cache_id = 'WhishList::getByIdCustomer_'.(int)$id_customer.'-'.(int)Shop::getContextShopID().'-'.(int)Shop::getContextShopGroupID();
        if (!Cache::isStored($cache_id)) {
            $result = Db::getInstance()->executeS('
                SELECT w.`id_wishlist`, w.`name`, w.`token`, w.`date_add`, w.`date_upd`, w.`counter`, w.`default`
                FROM `'._DB_PREFIX_.'ttwishlist` w
                WHERE `id_customer` = '.(int)($id_customer).'
                '.$shop_restriction.'
                ORDER BY w.`name` ASC');
            Cache::store($cache_id, $result);
        }
        return Cache::retrieve($cache_id);
    }

    /**
    * Get Wishlist products by Customer ID
    *
    * @return array Results
    */
    public static function getProductByIdCustomer($id_wishlist, $id_customer, $id_lang, $id_product = null, $quantity = false)
    {
        if (!Validate::isUnsignedId($id_customer) || !Validate::isUnsignedId($id_lang) || !Validate::isUnsignedId($id_wishlist)) {
            die(Tools::displayError());
        }
        $products = Db::getInstance()->executeS('
            SELECT wp.`id_product`, wp.`quantity`, p.`quantity` as product_quantity, pl.`name`, wp.`id_product_attribute`, wp.`priority`, pl.link_rewrite, cl.link_rewrite as category_rewrite
            FROM `'._DB_PREFIX_.'ttwishlist_product` wp
            LEFT JOIN `'._DB_PREFIX_.'product` p ON p.`id_product` = wp.`id_product`
            '.Shop::addSqlAssociation('product', 'p').'
            LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON pl.`id_product` = wp.`id_product`'.Shop::addSqlRestrictionOnLang('pl').'
            LEFT JOIN `'._DB_PREFIX_.'ttwishlist` w ON w.`id_wishlist` = wp.`id_wishlist`
            LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON cl.`id_category` = product_shop.`id_category_default` AND cl.id_lang='.(int)$id_lang.Shop::addSqlRestrictionOnLang('cl').'
            WHERE w.`id_customer` = '.(int)($id_customer).'
            AND pl.`id_lang` = '.(int)($id_lang).'
            AND wp.`id_wishlist` = '.(int)($id_wishlist).
            (empty($id_product) === false ? ' AND wp.`id_product` = '.(int)($id_product) : '').
            ($quantity == true ? ' AND wp.`quantity` != 0': '').'
            GROUP BY p.id_product, wp.id_product_attribute');
        if (empty($products) === true || !sizeof($products)) {
            return array();
        }
        for ($i = 0; $i < sizeof($products); ++$i) {
            if (isset($products[$i]['id_product_attribute']) && Validate::isUnsignedInt($products[$i]['id_product_attribute'])) {
                $result = Db::getInstance()->executeS('
                    SELECT al.`name` as attribute_name, pa.`quantity` as "attribute_quantity"
                    FROM `'._DB_PREFIX_.'product_attribute_combination` pac
                    LEFT JOIN `'._DB_PREFIX_.'attribute` a ON (a.`id_attribute` = pac.`id_attribute`)
                    LEFT JOIN `'._DB_PREFIX_.'attribute_group` ag ON (ag.`id_attribute_group` = a.`id_attribute_group`)
                    LEFT JOIN `'._DB_PREFIX_.'attribute_lang` al ON (a.`id_attribute` = al.`id_attribute` AND al.`id_lang` = '.(int)($id_lang).')
                    LEFT JOIN `'._DB_PREFIX_.'attribute_group_lang` agl ON (ag.`id_attribute_group` = agl.`id_attribute_group` AND agl.`id_lang` = '.(int)($id_lang).')
                    LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (pac.`id_product_attribute` = pa.`id_product_attribute`)
                    '.Shop::addSqlAssociation('product_attribute', 'pa').'
                    WHERE pac.`id_product_attribute` = '.(int)($products[$i]['id_product_attribute']));
                $products[$i]['attributes_small'] = '';
                if ($result) {
                    foreach ($result as $k => $row) {
                        $products[$i]['attributes_small'] .= $row['attribute_name'].', ';
                    }
                    $products[$i]['attributes_small'] = rtrim($products[$i]['attributes_small'], ', ');
                    if (isset($result[0])) {
                        $products[$i]['attribute_quantity'] = $result[0]['attribute_quantity'];
                    }
                }
            } else {
                $products[$i]['attribute_quantity'] = $products[$i]['product_quantity'];
            }
        }
        return ($products);
    }

    /**
    * Get Wishlists number products by Customer ID
    *
    * @return array Results
    */
    public static function getInfosByIdCustomer($id_customer)
    {
        if (Shop::getContextShopID()) {
            $shop_restriction = 'AND id_shop = '.(int)Shop::getContextShopID();
        } elseif (Shop::getContextShopGroupID()) {
            $shop_restriction = 'AND id_shop_group = '.(int)Shop::getContextShopGroupID();
        } else {
            $shop_restriction = '';
        }

        if (!Validate::isUnsignedId($id_customer)) {
            die(Tools::displayError());
        }
        return (Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT SUM(wp.`quantity`) AS nbProducts, wp.`id_wishlist`
            FROM `'._DB_PREFIX_.'ttwishlist_product` wp
            INNER JOIN `'._DB_PREFIX_.'ttwishlist` w ON (w.`id_wishlist` = wp.`id_wishlist`)
            WHERE w.`id_customer` = '.(int)($id_customer).'
            '.$shop_restriction.'
            GROUP BY w.`id_wishlist`
            ORDER BY w.`name` ASC'));
    }

    /**
    * Add product to ID wishlist
    *
    * @return boolean succeed
    */
    public static function addProduct($id_wishlist, $id_customer, $id_product, $id_product_attribute, $quantity)
    {
        if (!Validate::isUnsignedId($id_wishlist) || !Validate::isUnsignedId($id_customer) || !Validate::isUnsignedId($id_product) || !Validate::isUnsignedId($quantity)) {
            die(Tools::displayError());
        }
        $result = Db::getInstance()->getRow('
            SELECT wp.`quantity`
            FROM `'._DB_PREFIX_.'ttwishlist_product` wp
            JOIN `'._DB_PREFIX_.'ttwishlist` w ON (w.`id_wishlist` = wp.`id_wishlist`)
            WHERE wp.`id_wishlist` = '.(int)($id_wishlist).'
            AND w.`id_customer` = '.(int)($id_customer).'
            AND wp.`id_product` = '.(int)($id_product).'
            AND wp.`id_product_attribute` = '.(int)($id_product_attribute));
        if (empty($result) === false and sizeof($result)) {
            if (($result['quantity'] + $quantity) <= 0) {
                return (ttwishlist::removeProduct($id_wishlist, $id_customer, $id_product, $id_product_attribute));
            } else {
                return (Db::getInstance()->execute('
                    UPDATE `'._DB_PREFIX_.'ttwishlist_product` SET
                    `quantity` = '.(int)($quantity + $result['quantity']).'
                    WHERE `id_wishlist` = '.(int)($id_wishlist).'
                    AND `id_product` = '.(int)($id_product).'
                    AND `id_product_attribute` = '.(int)($id_product_attribute)));
            }
        } else {
            return (Db::getInstance()->execute('
                INSERT INTO `'._DB_PREFIX_.'ttwishlist_product` (`id_wishlist`, `id_product`, `id_product_attribute`, `quantity`, `priority`) VALUES(
                '.(int)($id_wishlist).',
                '.(int)($id_product).',
                '.(int)($id_product_attribute).',
                '.(int)($quantity).', 1)'));
        }
    }

    /**
    * Update product to wishlist
    *
    * @return boolean succeed
    */
    public static function updateProduct($id_wishlist, $id_product, $id_product_attribute, $priority, $quantity)
    {
        if (!Validate::isUnsignedId($id_wishlist) || !Validate::isUnsignedId($id_product) || !Validate::isUnsignedId($quantity) || $priority < 0 || $priority > 2) {
            die(Tools::displayError());
        }
        return (Db::getInstance()->execute('
            UPDATE `'._DB_PREFIX_.'ttwishlist_product` SET
            `priority` = '.(int)($priority).',
            `quantity` = '.(int)($quantity).'
            WHERE `id_wishlist` = '.(int)($id_wishlist).'
            AND `id_product` = '.(int)($id_product).'
            AND `id_product_attribute` = '.(int)($id_product_attribute)));
    }

    /**
    * Remove product from wishlist
    *
    * @return boolean succeed
    */
    public static function removeProduct($id_wishlist, $id_customer, $id_product, $id_product_attribute)
    {
        if (!Validate::isUnsignedId($id_wishlist) || !Validate::isUnsignedId($id_customer) || !Validate::isUnsignedId($id_product)) {
            die(Tools::displayError());
        }
        $result = Db::getInstance()->getRow('
            SELECT w.`id_wishlist`, wp.`id_wishlist_product`
            FROM `'._DB_PREFIX_.'ttwishlist` w
            LEFT JOIN `'._DB_PREFIX_.'ttwishlist_product` wp ON (wp.`id_wishlist` = w.`id_wishlist`)
            WHERE `id_customer` = '.(int)($id_customer).'
            AND w.`id_wishlist` = '.(int)($id_wishlist));
        if (empty($result) === true || $result === false || !sizeof($result) || $result['id_wishlist'] != $id_wishlist) {
            return (false);
        }

        return Db::getInstance()->execute('
            DELETE FROM `'._DB_PREFIX_.'ttwishlist_product`
            WHERE `id_wishlist` = '.(int)($id_wishlist).'
            AND `id_product` = '.(int)($id_product).'
            AND `id_product_attribute` = '.(int)($id_product_attribute));
    }

    /**
    * Add email to wishlist
    *
    * @return boolean succeed
    */
    public static function addEmail($id_wishlist, $email)
    {
        if (!Validate::isUnsignedId($id_wishlist) || empty($email) || !Validate::isEmail($email)) {
            return false;
        }
        return (Db::getInstance()->execute('
            INSERT INTO `'._DB_PREFIX_.'ttwishlist_email` (`id_wishlist`, `email`, `date_add`) VALUES(
            '.(int)($id_wishlist).',
            \''.pSQL($email).'\',
            \''.pSQL(date('Y-m-d H:i:s')).'\')'));
    }

    /**
    * Get email from wishlist
    *
    * @return Array results
    */
    public static function getEmail($id_wishlist, $id_customer)
    {
        if (!Validate::isUnsignedId($id_wishlist) || !Validate::isUnsignedId($id_customer)) {
            die(Tools::displayError());
        }
        return (Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT we.`email`, we.`date_add`
            FROM `'._DB_PREFIX_.'ttwishlist_email` we
            INNER JOIN `'._DB_PREFIX_.'ttwishlist` w ON w.`id_wishlist` = we.`id_wishlist`
            WHERE we.`id_wishlist` = '.(int)($id_wishlist).'
            AND w.`id_customer` = '.(int)($id_customer)));
    }

    /**
    * Return if there is a default already set
    *
    * @return boolean
    */
    public static function isDefault($id_customer)
    {
        return (Bool)Db::getInstance()->getValue('SELECT * FROM `'._DB_PREFIX_.'ttwishlist` WHERE `id_customer` = '.$id_customer.' AND `default` = 1');
    }

    public static function getDefault($id_customer)
    {
        return Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'ttwishlist` WHERE `id_customer` = '.$id_customer.' AND `default` = 1');
    }

    /**
    * Set current ttwishlist as default
    *
    * @return boolean
    */
    public function setDefault()
    {
        if ($default = $this->getDefault($this->id_customer)) {
            Db::getInstance()->update('ttwishlist', array('default' => '0'), 'id_wishlist = '.$default[0]['id_wishlist']);
        }

        return Db::getInstance()->update('ttwishlist', array('default' => '1'), 'id_wishlist = '.$this->id);
    }
}
