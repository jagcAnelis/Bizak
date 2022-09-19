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
use OnePageCheckoutPS\Exception\MyAccountException;
use OnePageCheckoutPS\Exception\OPCException;

class OnePageCheckoutPSMyAccountModuleFrontController extends ModuleFrontControllerCore implements IFrontController
{
    public function postProcess()
    {
        $noajax = Tools::getValue('noajax');
        if ($this->ajax || $noajax) {
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

    private function customerExists()
    {
        try {
            $requestParameters = Tools::getAllValues();

            $myAccount = $this->get('onepagecheckoutps.core.myaccount');
            $myAccount->setParameters($requestParameters);
            $myAccount->customerExists();

            return array(
                'success' => true,
            );
        } catch (OPCException $exception) {
            return array(
                'success' => false,
                'messageCode' => $exception->getCode(),
                'messageError' => $this->handleExceptionAjax($exception),
            );
        }
    }

    private function loginCustomer()
    {
        try {
            $email = Tools::getValue('email');
            $password = Tools::getValue('password');

            $myAccount = $this->get('onepagecheckoutps.core.myaccount');
            $myAccount->login($email, $password);

            return array(
                'success' => true,
            );
        } catch (OPCException $exception) {
            return array(
                'success' => false,
                'messageError' => $this->handleExceptionAjax($exception),
            );
        }
    }

    private function loginSocialCustomer()
    {
        try {
            $requestParameters = Tools::getAllValues();

            $myAccount = $this->get('onepagecheckoutps.core.myaccount');
            $myAccount->setParameters($requestParameters);
            $myAccount->loginSocial();

            $context = $this->module->getContextProvider();
            $link = $context->getLink();

            $redirectUrl = $link->getPageLink('my-account');
            if ($context->getCart()->nbProducts()) {
                if ($this->module->getConfigurationList('OPC_REDIRECT_DIRECTLY_TO_OPC')) {
                    $redirectUrl = $link->getPageLink(
                        'order',
                        true,
                        $context->getLanguage()->id,
                        array('checkout' => '1')
                    );
                } else {
                    $redirectUrl = $link->getPageLink('order');
                }
            } else {
                $customerAddreses = $context->getCustomer()->getAddresses($context->getLanguageId());
                if (empty($customerAddreses)) {
                    $redirectUrl = $link->getPageLink('addresses');
                }
            }

            $context->getSmarty()->assign('redirectUrl', $redirectUrl);

            die(
                $context->getSmarty()->fetch(
                    'module:onepagecheckoutps/views/templates/front/checkout/my_account/_partials/close_popup.tpl'
                )
            );
        } catch (OPCException $exception) {
            die($this->handleExceptionAjax($exception));
        }
    }

    private function saveCustomer()
    {
        try {
            $requestParameters = Tools::getAllValues();

            $myAccount = $this->get('onepagecheckoutps.core.myaccount');
            $myAccount->setParameters($requestParameters);
            $errors = $myAccount->saveCustomer();

            if (is_array($errors)) {
                return array(
                    'success' => false,
                    'messageError' => $this->l('Your information could not be updated, please check your details.', 'myaccount'),
                    'errors' => $errors,
                );
            }

            return array(
                'success' => true,
                'customerName' => $myAccount->getCustomerName(),
            );
        } catch (OPCException $exception) {
            return array(
                'success' => false,
                'messageError' => $this->handleExceptionAjax($exception),
            );
        }
    }

    private function convertGuestToCustomer()
    {
        try {
            $requestParameters = Tools::getAllValues();

            $myAccount = $this->get('onepagecheckoutps.core.myaccount');
            $myAccount->setParameters($requestParameters);
            $myAccount->convertGuestToCustomer();

            return array(
                'success' => true,
                'message' => $this->l('Your guest account has been successfully transformed into a customer account.', 'myaccount'),
            );
        } catch (OPCException $exception) {
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
            case 'OnePageCheckoutPS\Exception\MyAccountException':
                switch ($exception->getCode()) {
                    case MyAccountException::CUSTOMER_EMAIL_INVALID:
                        $messageLang = $this->l('The email %s format is invalid', 'myaccount');
                        break;
                    case MyAccountException::CUSTOMER_ACCESS_INCORRECT:
                        $messageLang = $this->l('The email or password is incorrect. Verify your information and try again.', 'myaccount');
                        break;
                    case MyAccountException::CUSTOMER_EMAIL_ALREADY_USED:
                        $messageLang = $this->l('An account was already registered with this email address.', 'myaccount');
                        break;
                }

                break;

            default:
                break;
        }

        return $exception->getMessageFormatted($messageLang);
    }
}
