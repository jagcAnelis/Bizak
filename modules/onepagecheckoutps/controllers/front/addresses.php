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
use OnePageCheckoutPS\Exception\AddressesException;
use OnePageCheckoutPS\Exception\OPCException;

class OnePageCheckoutPSAddressesModuleFrontController extends ModuleFrontControllerCore implements IFrontController
{
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

    private function save()
    {
        try {
            $requestParameters = Tools::getAllValues();

            $addresses = $this->get('onepagecheckoutps.core.addresses');
            $addresses->setParameters($requestParameters);
            $errors = $addresses->saveAddress();

            if (is_array($errors)) {
                return array(
                    'success' => false,
                    'messageError' => $this->l('Your information could not be updated, please check your details.', 'addresses'),
                    'errors' => $errors,
                );
            }

            return array(
                'success' => true,
                'addressId' => $errors,
                'stepAddressesRendered' => $addresses->render(),
            );
        } catch (OPCException $exception) {
            return array(
                'success' => false,
                'messageError' => $this->handleExceptionAjax($exception),
            );
        }
    }

    private function delete()
    {
        try {
            $requestParameters = Tools::getAllValues();

            $addresses = $this->get('onepagecheckoutps.core.addresses');
            $addresses->setParameters($requestParameters);
            $addresses->deleteAddress();
            $stepAddressesRendered = $addresses->render();

            return array(
                'success' => true,
                'stepAddressesRendered' => $stepAddressesRendered,
                'customerHaveAddresses' => $addresses->customerHaveAddresses(),
            );
        } catch (OPCException $exception) {
            return array(
                'success' => false,
                'messageError' => $this->handleExceptionAjax($exception),
            );
        }
    }

    private function list()
    {
        try {
            $requestParameters = Tools::getAllValues();

            $addresses = $this->get('onepagecheckoutps.core.addresses');
            $addresses->setParameters($requestParameters);
            $stepAddressesRendered = $addresses->render();

            return array(
                'success' => true,
                'stepAddressesRendered' => $stepAddressesRendered,
                'customerHaveAddresses' => $addresses->customerHaveAddresses(),
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
            case 'OnePageCheckoutPS\Exception\AddressesException':
                switch ($exception->getCode()) {
                    case AddressesException::ADDRESS_VATNUMBER_INVALID:
                        $messageLang = $this->l('Your VAT number is invalid', 'addresses');
                        break;
                    case AddressesException::ADDRESS_DNI_INVALID:
                        $messageLang = $this->l('Your DNI number is invalid', 'addresses');
                        break;
                }

                break;

            default:
                break;
        }

        return $exception->getMessageFormatted($messageLang);
    }
}
