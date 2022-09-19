<?php
/**
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * We are experts and professionals in PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This file is not open source! Each license that you purchased is only available for 1 wesite only.
 * If you want to use this file on more websites (or projects), you need to purchase additional licenses.
 * You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
 *
 * @author    PresTeamShop SAS (Registered Trademark) <info@presteamshop.com>
 * @copyright 2011-2022 PresTeamShop SAS, All rights reserved.
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 *
 * @category  PrestaShop
 * @category  Module
 */

use OnePageCheckoutPS\Controller\Front\IFrontController;
use OnePageCheckoutPS\Exception\OPCException;
use OnePageCheckoutPS\Exception\PaymentException;

class OnePageCheckoutPSPaymentModuleFrontController extends ModuleFrontControllerCore implements IFrontController
{
    private $checkoutProcess;

    public function postProcess()
    {
        if ($this->ajax) {
            $action = Tools::getValue('action');
            if (!empty($action) && method_exists($this, $action)) {
                $response = $this->{$action}();
                $dataType = Tools::getValue('dataType', 'json');
                if ($dataType === 'json') {
                    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
                    header('Cache-Control: post-check=0, pre-check=0', false);
                    header('Pragma: no-cache');
                    header('Content-Type: application/json');

                    $response = Tools::jsonEncode($response);
                }

                exit($response);
            }

            header('HTTP/1.0 403 Forbidden');
            exit();
        }

        Tools::redirect('index.php');
        exit();
    }

    public function getCheckoutProcess()
    {
        return $this->checkoutProcess;
    }

    private function getPaymentList()
    {
        $isSetOpcCustomerContext = false;

        try {
            $this->module->loginCustomerOPC($isSetOpcCustomerContext);

            $payment = $this->get('onepagecheckoutps.core.payment');
            $this->checkoutProcess = $payment->getCheckoutProcess();
            $stepPaymentRendered = $payment->render();

            if ($isSetOpcCustomerContext) {
                $this->module->logoutCustomerOPC();
            }

            return array(
                'success' => true,
                'stepPaymentRendered' => $stepPaymentRendered,
            );
        } catch (OPCException $exception) {
            if ($isSetOpcCustomerContext) {
                $this->module->logoutCustomerOPC();
            }

            return array(
                'success' => false,
                'messageError' => $this->handleExceptionAjax($exception),
            );
        }
    }

    public function handleExceptionAjax($exception)
    {
        $messageLang = '';
        $exceptionClass = get_class($exception);

        switch ($exceptionClass) {
            case 'OnePageCheckoutPS\Exception\PaymentException':
                switch ($exception->getCode()) {
                    case PaymentException::PAYMENT_PRODUCT_WIHTOUT_STOCK:
                        $messageLang = $this->l('An item (%s) in your cart is no longer available in this quantity. You cannot proceed with your order until the quantity is adjusted.', 'payment');
                        break;
                    case PaymentException::PAYMENT_NEED_ADDRESS:
                        $messageLang = $this->l('It is necessary to create an address to be able to show the different payment options.', 'payment');
                        break;
                    case PaymentException::PAYMENT_PRODUCT_NOT_AVAILABLE:
                        $messageLang = $this->l('This product (%s) is no longer available.', 'payment');
                        break;
                    case PaymentException::PAYMENT_NEED_SHIPPING:
                        $messageLang = $this->l('You must select a shipping method to view the available payment methods.', 'payment');
                        break;
                }

                break;
            default:
                break;
        }

        return $exception->getMessageFormatted($messageLang);
    }
}
