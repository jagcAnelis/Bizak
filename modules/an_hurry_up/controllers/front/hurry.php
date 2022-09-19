<?php
/**
 * 2021 Anvanto
 *
 * NOTICE OF LICENSE
 *
 * This file is not open source! Each license that you purchased is only available for 1 wesite only.
 * If you want to use this file on more websites (or projects), you need to purchase additional licenses.
 * You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
 *
 *  @author Anvanto <anvantoco@gmail.com>
 *  @copyright  2021 Anvanto
 *  @license    Valid for 1 website (or project) for each purchase of license
 *  International Registered Trademark & Property of Anvanto
 */
 
/**
 * Class an_hurry_uphurryModuleFrontController
 */

class an_hurry_uphurryModuleFrontController extends ModuleFrontController
{
    /**
     * @var bool
     */
    public $ssl = true;

    /**
     * Init content
     */
    public function initContent()
    {
        $result = array();
        if (Tools::isSubmit('action')) {
            $actionName = Tools::getValue('action', '') . 'Action';
            if (method_exists($this, $actionName)) {
                $result = $this->$actionName();
            }
        }

        die(Tools::jsonEncode($result));
    }

    public function getProductQtyAction()
    {
        $id_product = Tools::getValue('id_product');
        $combination_id = Tools::getValue('combination_id');
        if (!$combination_id) {
            $combination_id = 0;
        }
        $quantity = StockAvailable::getQuantityAvailableByProduct($id_product, $combination_id);
        die(print_r($quantity,true));
    }
}
