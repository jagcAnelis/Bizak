<?php

namespace DHLParcel\Shipping\Controller\FrontOffice;

use DHLParcel\Shipping\Model\Core\Kernel;
use DHLParcel\Shipping\Model\Data\Db\CartData\ServicePoint;
use DHLParcel\Shipping\Model\Service\CartService;
use DHLParcel\Shipping\Model\Service\ServicePointService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Address;
use Country;
use ModuleFrontController;
use Tools;

class AjaxController extends ModuleFrontController
{
    public function servicePointAction()
    {
        $success = true;

        $cartId = $this->context->cart->id;
        $servicePointCartData = CartService::instance()->getDataKey($cartId, CartService::SERVICEPOINT);
        $address = new Address($this->context->cart->id_address_delivery);

        $data = [];
        if ($servicePointCartData) {
            $servicePointData = new ServicePoint($servicePointCartData);

            // Check if still in sync
            if (strtoupper($servicePointData->postcode) == strtoupper($address->postcode)
                && strtoupper($servicePointData->countryCode) == strtoupper(Country::getIsoById($address->id_country))
            ) {
                $servicePoint = ServicePointService::instance()->get($servicePointData->servicePointId, $servicePointData->countryCode);
                if ($servicePoint) {
                    $data['servicePoint'] = $servicePoint->toArray();
                }
            }
        }

        if (empty($data)) {
            // Try to select a default on empty
            $servicePoints = ServicePointService::instance()->search(
                strtoupper($address->postcode),
                strtoupper(Country::getIsoById($address->id_country)),
                1
            );

            if (empty($servicePoints)) {
                CartService::instance()->saveDataKey($cartId, CartService::SERVICEPOINT, []);
            } else {
                $servicePointResponse = reset($servicePoints);
                $servicePoint = new ServicePoint([
                    'servicePointId' => $servicePointResponse->id,
                    'postcode' => strtoupper($address->postcode),
                    'countryCode' => strtoupper($servicePointResponse->address->countryCode),
                ]);

                CartService::instance()->saveDataKey($cartId, CartService::SERVICEPOINT, $servicePoint->toArray());
                $data['servicePoint'] = $servicePointResponse->toArray();
            }
        }

        $view = [];
        $view['servicepoint'] = Kernel::instance()->render('frontoffice/checkout/servicepoint', $data);

        $response = new JsonResponse();
        $response->setData([
            'status' => $success ? 'success' : 'error',
            'data' => [
                'view' => $view,
            ],
            'message' => null,
        ]);
        return $response;
    }

    public function servicePointSyncAction()
    {
        $cartId = $this->context->cart->id;
        $servicePointId = Tools::getValue('servicepoint_id');
        $postcode = Tools::getValue('postcode');
        $countryCode = Tools::getValue('country_code');

        $servicePoint = new ServicePoint([
            'servicePointId' => $servicePointId,
            'postcode' => strtoupper($postcode),
            'countryCode' => strtoupper($countryCode),
        ]);

        CartService::instance()->saveDataKey($cartId, CartService::SERVICEPOINT, $servicePoint->toArray());

        $response = new JsonResponse();
        $response->setData([
            'status' => 'success',
            'data' => null,
            'message' => null,
        ]);
        return $response;
    }
}
