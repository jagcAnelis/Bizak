<?php
/**
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 * @author    Línea Gráfica E.C.E. S.L.
 * @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 * @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *            https://www.lineagrafica.es/licenses/license_es.pdf
 *            https://www.lineagrafica.es/licenses/license_fr.pdf
 */

class LGStoreComment extends ObjectModel
{
    public $id_order;
    public $id_customer;
    public $id_lang;
    public $stars;
    public $nick;
    public $title;
    public $comment;
    public $answer;
    public $active;
    public $position;
    public $date;

    /**a
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table'     => 'lgcomments_storecomments',
        'primary'   => 'id_storecomment',
        'multilang' => false,
        'fields'    => array(
            'id_order'    => array('type' => self::TYPE_INT, 'required' => true),
            'id_customer' => array('type' => self::TYPE_INT, 'required' => true),
            'id_lang'     => array('type' => self::TYPE_INT, 'required' => true),
            'stars'       => array('type' => self::TYPE_INT, 'required' => true),
            'nick'        => array('type' => self::TYPE_STRING, 'size' => 255),
            'title'       => array('type' => self::TYPE_STRING, 'size' => 255),
            'comment'     => array('type' => self::TYPE_HTML),
            'answer'      => array('type' => self::TYPE_HTML),
            'active'      => array('type' => self::TYPE_INT, 'required' => true),
            'position'    => array('type' => self::TYPE_INT, 'required' => true),
            'date'        => array('type' => self::TYPE_DATE, 'validate' => 'isDate', 'required' => true),
        )
    );

    public static function install()
    {
        $sql = array(
            'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . self::$definition['table'] . '` (
                `' .  self::$definition['primary'] . '` INT(11) NOT NULL AUTO_INCREMENT,
                `id_order` INT(11) NOT NULL,
                `id_customer` INT(11) NOT NULL,
                `id_lang` INT(11) NOT NULL,
                `stars` INT(11) NOT NULL,
                `nick` VARCHAR(255) NOT NULL,
                `title` VARCHAR(255),
                `comment` TEXT,
                `answer` TEXT,
                `active` TINYINT(1) NOT NULL,
                `position` INT(11) NOT NULL,
                `date` DATETIME NOT NULL,
                PRIMARY KEY (`' .  self::$definition['primary'] . '`),
                KEY `date` (`date`,`id_customer`,`id_order`,`stars`,`id_lang`,`active`,`position`)
            ) ENGINE='.(defined('ENGINE_TYPE') ? ENGINE_TYPE : 'Innodb').' CHARSET=utf8',
        );

        return self::processQueries($sql);
    }

    public static function uninstall()
    {
        $sql = array(
            'DROP TABLE IF EXISTS `'._DB_PREFIX_.self::$definition['table'].'`',
        );

        return self::processQueries($sql);
    }

    public static function upgrade()
    {
        return true;
    }

    protected static function processQueries($queries)
    {
        $res = true;
        foreach ($queries as $query) {
            try {
                $res &= Db::getInstance()->execute($query);
            } catch (Exception $e) {
                Tools::dieObject($e->getMessage());
            }
        }

        return $res;
    }

    public static function getSumShopCommentsByLang($id_lang = null)
    {
        if (is_null($id_lang)) {
            $id_lang = (int)Context::getContext()->language->id;
        }
        $sumL = Db::getInstance()->getValue(
            'SELECT SUM(stars) AS totalcomentarios '.
            'FROM '._DB_PREFIX_.self::$definition['table'].' '.
            'WHERE active = 1'.
            ' AND id_lang = '.(int)$id_lang
        );
        return $sumL;
    }

    public static function getCountShopCommentsByLang($id_lang = null)
    {
        if (is_null($id_lang)) {
            $id_lang = (int)Context::getContext()->language->id;
        }
        $countL = Db::getInstance()->getValue(
            'SELECT COUNT('.self::$definition['primary'].') AS total '.
            'FROM '._DB_PREFIX_.self::$definition['table'].' '.
            'WHERE active = 1 '.
            'AND id_lang = '.(int)$id_lang.''
        );
        return $countL;
    }

    public static function getRandomShopCommentByLang($id_lang = null)
    {
        if (is_null($id_lang)) {
            $id_lang = (int)Context::getContext()->language->id;
        }
        $randomL = Db::getInstance()->executeS(
            'SELECT comment, stars '.
            'FROM '._DB_PREFIX_.self::$definition['table'].' '.
            'WHERE active = 1 '.
            'AND stars > 6 '.
            'AND id_lang = '.(int)$id_lang.' '.
            'ORDER BY RAND()'
        );
        return $randomL;
    }

    public static function getSumShopComments()
    {
        $sum = Db::getInstance()->getValue(
            'SELECT SUM(stars) AS totalcomentarios '.
            'FROM '._DB_PREFIX_.self::$definition['table'].' '.
            'WHERE active = 1'
        );
        return $sum;
    }

    public static function getCountShopComments()
    {
        $count = Db::getInstance()->getValue(
            'SELECT COUNT('.self::$definition['primary'].') AS total '.
            'FROM '._DB_PREFIX_.self::$definition['table'].' '.
            'WHERE active = 1'
        );
        return $count;
    }

    public static function getRandomShopComment()
    {
        $random = Db::getInstance()->executeS(
            'SELECT nick, comment, stars '.
            'FROM '._DB_PREFIX_.self::$definition['table'].' '.
            'WHERE active = 1 '.
            'AND stars > 6 '.
            'ORDER BY RAND()'
        );
        return $random;
    }

    /**
     * @param null $min
     * @param null $max
     * @param int $offset
     * @param null $limit
     * @param null $id_lang
     * @return array|false|mysqli_result|null|PDOStatement|resource
     */
    public static function getReviewsByRatings($min = null, $max = null, $offset = 0, $limit = null, $id_lang = null)
    {
        // CARLOS: Aunque parecza absurdo la columna position (innecesaria por cierto porque va a coincidir con
        //         la columna id_storecomment que es la key) está ordenada por orden de inserccion
        //         Por lo que para que salgan ordenados por fecha, hay que invertir los órdenes
        $sort_order = ((int)Configuration::get('PS_LGCOMMENTS_DISPLAY_ORDER') == 2)?'ASC':'DESC';
        $sql = 'SELECT st.*, '.
            (
                Configuration::get('PS_LGCOMMENTS_SCALE') == 20
                    ? '( st.stars * 2 ) '
                    : ( Configuration::get('PS_LGCOMMENTS_SCALE') == 5
                    ? ' ROUND( ( st.stars / 2 ), 1) '
                    : ' st.stars '
                )
            ).
            'AS rating '.
            'FROM `'._DB_PREFIX_.self::$definition['table'].'` st '.
            'WHERE st.active = 1 ';
        if (Configuration::get('PS_LGCOMMENTS_DISPLAY_LANGUAGE') == 1) {
            if (is_null($id_lang)) {
                $id_lang = (int)Context::getContext()->language->id;
            }
            $sql .= 'AND st.id_lang = '.(int)$id_lang;
        }
        if (!is_null($min)) {
            $sql .= ' AND st.stars >= '.(int)$min.' ';
        }
        if (!is_null($max)) {
            $sql .= ' AND st.stars < '.(int)$max.' ';
        }
        $sql .= 'ORDER BY st.position '.$sort_order.' ';
        $sql .= 'LIMIT '.((int)$offset * (int)$limit).','.(int)$limit;
//        if (!Tools::getIsset('ajax') && !Tools::getIsset('from-xhr')) {
//            Tools::dieObject($sql, false);
//        }
        $rates = Db::getInstance()->ExecuteS($sql);
//        if (!Tools::getIsset('ajax') && !Tools::getIsset('from-xhr')) {
//            Tools::dieObject($rates, false);
//        }
        return $rates;
    }

    /**
     * Get the total sum of all star values
     *
     * @param null $id_lang
     * @return false|null|string
     */
    public static function getSumStarsValues($id_lang = null)
    {
        $sql = 'SELECT SUM(stars) AS totalcomentarios '.
            'FROM '._DB_PREFIX_.self::$definition['table'].' '.
            'WHERE active = 1';

        if (Configuration::get('PS_LGCOMMENTS_DISPLAY_LANGUAGE') == 1) {
            if (is_null($id_lang)) {
                $id_lang = (int)Context::getContext()->language->id;
            }
            $sql .= ' AND id_lang = '.(int)$id_lang;
        }

        $sum = Db::getInstance()->getValue($sql);

        return $sum;
    }

    /**
     * @param $min
     * @param $max
     * @param null $id_lang
     * @return false|null|string
     */
    public static function getNumReviewsByRatings($min, $max, $id_lang = null)
    {
        $sql = 'SELECT COUNT('.self::$definition['primary'].') AS total '.
            'FROM '._DB_PREFIX_.self::$definition['table'].' '.
            'WHERE active = 1 '.
            '  AND stars >= '.(int)$min.' '.
            '  AND stars < '.(int)$max.'';

        if (Configuration::get('PS_LGCOMMENTS_DISPLAY_LANGUAGE') == 1) {
            if (is_null($id_lang)) {
                $id_lang = (int)Context::getContext()->language->id;
            }
            $sql .= '  AND id_lang = '.(int)$id_lang;
        }
        $count = Db::getInstance()->getValue($sql);
        return $count;
    }

    public static function getReviews(
        $id_lang = null,
        $p = null,
        $n = null,
        $get_total = false
    ) {
        if ($p < 1) {
            $p = 1;
        }
        /* Return only the number of products */

        if ($get_total) {
            return self::getNumReviewsByRatings('0', '11', (int)$id_lang);
        } else {
            return self::getReviewsByRatings(null, null, (int)$p - 1, (int)$n, 'ASC', (int)$id_lang);
        }
    }

    public static function getAllStoreComments()
    {
        $storeComments = Db::getInstance()->ExecuteS(
            'SELECT * ' .
            'FROM ' . _DB_PREFIX_ . self::$definition['table'] . ' ' .
            'ORDER BY ' . self::$definition['primary'] . ' ASC'
        );
        return $storeComments;
    }

    public static function getTotalComments()
    {
        self::getNumReviewsByRatings('0', '11');
    }

    public static function allowStoreComments()
    {
        return (Configuration::get('PS_LGCOMMENTS_OPINION_FORM') == 1
            || Configuration::get('PS_LGCOMMENTS_OPINION_FORM') == 2);
    }

    public static function getLastPosition()
    {
        $sql = 'SELECT MAX(`position`) FROM `' ._DB_PREFIX_.self::$definition['table'].'`';
        return (int)Db::getInstance()->getValue($sql) + 1;
    }

    /*************************************************************************************************************/
    /*                                                                                                           */
    /*                                                GDPR Methods                                               */
    /*                                                                                                           */
    /*************************************************************************************************************/

    public static function anonymize($id_customer)
    {
        $customer = new Customer($id_customer);
        if (Validate::isLoadedObject($customer)) {
            $sql = 'UPDATE `'._DB_PREFIX_.self::$definition['table'].'` '.
                'SET '.
                '   `id_customer` = 0, '.
                '   `nick` = "" '.
                'WHERE `id_customer` = '.$customer->id;
            return Db::getInstance()->execute($sql);
        } else {
            $module = Module::getInstanceByName('lgcomments');
            throw new Exception(
                $module->l(
                    'Customer does not exists.'
                )
            );
        }
    }

    public static function exportData($id_customer)
    {
        $customer = new Customer($id_customer);
        if (Validate::isLoadedObject($customer)) {
            $query = new DbQuery();
            $query->select('"Store Comment" AS type');
            $query->select('`'.self::$definition['primary'].'` AS id');
            $query->select('`nick`');
            $query->select('`title`');
            $query->select('`comment` AS message');
            $query->select('`answer`');
            $query->select('`date`');
            $query->from(self::$definition['table']);
            $query->where('`id_customer` = ' . (int)$customer->id);
            $res = Db::getInstance()->executeS($query);

            return $res;
        } else {
            $module = Module::getInstanceByName('lgcomments');
            throw new Exception(
                $module->l(
                    'Customer does not exists.'
                )
            );
        }
    }

    public static function validate()
    {
        $errors = array();

        if (!Tools::getValue('sendcomments') || !self::allowStoreComments()) {
            return $errors;
        }

        $module = Module::getInstanceByName('lgcomments');

        if (empty(Tools::getValue('lg_nick'))) {
            $errors['nick'] = $module->l(
                'Please insert a nickname'
            );
        }
        if (empty(Tools::getValue('score_store'))) {
            $errors['score'] = $module->l(
                'Please insert a score for your review'
            );
        }
        if (empty(Tools::getValue('title_store'))) {
            $errors['title'] = $module->l(
                'Please insert a title for your review'
            );
        }
        if (empty(Tools::getValue('comment_store'))) {
            $errors['comment'] = $module->l(
                'Please insert a comment for your review'
            );
        }
        return $errors;
    }
}
