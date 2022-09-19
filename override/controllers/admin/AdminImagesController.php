<?php
/**
 * 2007-2020 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author     Anvanto (anvantoco@gmail.com)
 *  @copyright 2007-2020  http://anvanto.com
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class AdminImagesController extends AdminImagesControllerCore
{
    /*
    * module: anblog
    * date: 2022-08-30 13:45:19
    * version: 3.1.2
    */
    public function initContent()
    {
        if ($this->display != 'edit' && $this->display != 'add') {
            $module = Module::getInstanceByName('anblog');
            $module->regenerateThumbs();
        }
        parent::initContent();
    }
}
