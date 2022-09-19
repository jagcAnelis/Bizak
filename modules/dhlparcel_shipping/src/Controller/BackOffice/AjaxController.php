<?php

namespace DHLParcel\Shipping\Controller\BackOffice;

use DHLParcel\Shipping\Model\Core\TranslateTrait;
use DHLParcel\Shipping\Model\Core\Kernel;
use DHLParcel\Shipping\Model\Api\Connector;
use DHLParcel\Shipping\Model\Data\Preset;
use DHLParcel\Shipping\Model\Service\LabelService;
use DHLParcel\Shipping\Model\Service\PanelService;
use DHLParcel\Shipping\Model\Service\PanelNotificationService;
use DHLParcel\Shipping\Model\Service\PresetService;
use DHLParcel\Shipping\Model\Service\ServicePointService;
use DHLParcel\Shipping\Model\Service\SettingsService;
use DHLParcel\Shipping\Model\Service\ShipmentService;
use DHLParcel\Shipping\Model\Service\CapabilityService;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tools;
use Order;
use Address;
use Country;

class AjaxController extends FrameworkBundleAdminController
{
    use TranslateTrait;

    public function panelCreateAction()
    {
        $orderId = Tools::getValue('order_id');
        $business = boolval(Tools::getValue('business') == '1');
        $selectedOptions = Tools::getValue('selected_options');
        $selectedOptionsData = Tools::getValue('selected_options_data');
        $size = Tools::getValue('size');

        $shipmentService = ShipmentService::instance();

        if (!is_array($selectedOptionsData)) {
            $selectedOptionsData = [];
        }

        $success = $shipmentService->create($orderId, $size, $selectedOptions, $selectedOptionsData, $business);
        if ($success) {
            PanelNotificationService::instance()->addNotice($this->l('Label created.'));
        } else {
            PanelNotificationService::instance()->addError($this->l('Label could not be created.'));
            if ($errorMessage = $shipmentService->getError(ShipmentService::CREATE_ERROR)) {
                PanelNotificationService::instance()->addError($errorMessage);
            }
        }

        $view = [];
        $view['notifications'] = $this->renderTemplate('backoffice/order/panel/notifications', [
            'notifications' => [
                'notices'  => PanelNotificationService::instance()->getNotices(),
                'warnings' => PanelNotificationService::instance()->getWarnings(),
                'errors'   => PanelNotificationService::instance()->getErrors(),
            ],
        ]);

        $labels = LabelService::instance()->getAll($orderId);
        $view['labels'] = $this->renderTemplate('backoffice/order/panel/labels', [
            'labels' => $labels,
        ]);

        $response = new JsonResponse();
        $response->setData([
            'status'  => $success ? 'success' : 'error',
            'data'    => [
                'view' => $view,
                'extra' => $shipmentService->lastTrackingNumber ? [
                    'tracking_number' => $shipmentService->lastTrackingNumber
                ] : null,
            ],
            'message' => null,
        ]);

        return $response;
    }

    public function panelDeleteAction()
    {
        $labelId = Tools::getValue('label_id');
        $label = LabelService::instance()->load($labelId);

        if ($label) {
            $success = LabelService::instance()->delete($labelId);

            if ($success) {
                PanelNotificationService::instance()->addNotice($this->l('Label deleted.'));
            } else {
                PanelNotificationService::instance()->addError($this->l('Label could not be deleted.'));
            }
        } else {
            PanelNotificationService::instance()->addError($this->l('Label could not be found.'));
            $success = false;
        }

        $view = [];
        $view['notifications'] = $this->renderTemplate('backoffice/order/panel/notifications', [
            'notifications' => [
                'notices'  => PanelNotificationService::instance()->getNotices(),
                'warnings' => PanelNotificationService::instance()->getWarnings(),
                'errors'   => PanelNotificationService::instance()->getErrors(),
            ],
        ]);

        if ($label) {
            $labels = LabelService::instance()->getAll($label->id_order);
            $view['labels'] = $this->renderTemplate('backoffice/order/panel/labels', [
                'labels' => $labels,
            ]);
        } else {
            $view['labels'] = null;
        }

        $response = new JsonResponse();
        $response->setData([
            'status'  => $success ? 'success' : 'error',
            'data'    => [
                'view' => $view,
            ],
            'message' => null,
        ]);

        return $response;
    }

    public function panelAction()
    {
        $level = intval(Tools::getValue('level'));
        $orderId = Tools::getValue('order_id');
        $business = boolval(Tools::getValue('business') == '1');
        $selectedOptions = Tools::getValue('selected_options') ?: [];
        $selectedOptionsData = Tools::getValue('selected_options_data') ?: [];

        if ($level == 3) {
            $capabilityOptions = [];
        } else {
            $capabilityOptions = $selectedOptions;
        }

        $capabilities = CapabilityService::instance()->check(
            $this->getReceiverCountry($orderId),
            $this->getReceiverPostcode($orderId),
            $business,
            $capabilityOptions
        );

        $view = [];
        if ($level >= 2) {
            $options = CapabilityService::instance()->getOptions($capabilities);
            $deliveryOptions = PanelService::instance()->parseDeliveryOptions($options, $selectedOptions, $selectedOptionsData);
            $serviceOptions = PanelService::instance()->parseServiceOptions($options, $selectedOptions, $selectedOptionsData);

            $view['delivery_options'] = $this->renderTemplate('backoffice/order/panel/delivery-options', [
                'deliveryOptions' => $deliveryOptions,
            ]);

            $view['service_options'] = $this->renderTemplate('backoffice/order/panel/service-options', [
                'serviceOptions' => $serviceOptions,
            ]);
        } else {
            $view['delivery_options'] = null;
            $view['service_options'] = null;
        }

        if ($level >= 1) {
            $sizes = CapabilityService::instance()->getSizes($capabilities);
            $view['sizes'] = $this->renderTemplate('backoffice/order/panel/sizes', [
                'sizes' => $sizes,
            ]);
        } else {
            $view['sizes'] = null;
        }

        $response = new JsonResponse();
        $response->setData([
            'status'  => 'success',
            'data'    => [
                'view' => $view,
            ],
            'message' => null,
        ]);

        return $response;
    }

    public function panelServicePointAction()
    {
        $orderId = Tools::getValue('order_id');
        $search = Tools::getValue('search');

        $country = $this->getReceiverCountry($orderId);

        $servicePoints = ServicePointService::instance()->search($search, $country);

        $view = [];
        $view['list'] = $this->renderTemplate('backoffice/order/panel/input/servicepoint/list', [
            'servicePoints' => $servicePoints
        ]);

        $response = new JsonResponse();
        $response->setData([
            'status'  => 'success',
            'data'    => [
                'view' => $view,
            ],
            'message' => null,
        ]);

        return $response;
    }

    public function carrierAction()
    {
        $shippingCountry = SettingsService::instance()->shippingCountry();
        $capabilities = CapabilityService::instance()->check($shippingCountry);

        $options = CapabilityService::instance()->getOptions($capabilities);
        $presetOptions = PresetService::instance()->options();
        foreach ($options as $key => $option) {
            if (!in_array($key, $presetOptions)) {
                $options[$key] = null;
                unset($options[$key]);
            }
        }

        $carrierId = Tools::getValue('carrier_id');
        $preset = PresetService::instance()->load($carrierId);

        $deliveryOptions = PanelService::instance()->parseDeliveryOptions($options, $preset->options);
        $serviceOptions = PanelService::instance()->parseServiceOptions($options, $preset->options);

        $presetData = new Preset();

        $presetData->carrierId = Tools::getValue('carrier_id');
        $presetData->link = boolval($preset->link);
        $presetData->deliveryOptions = $deliveryOptions;
        $presetData->serviceOptions = $serviceOptions;

        $view = [];
        $view['form'] = $this->renderTemplate('backoffice/carrier/form', $presetData->toArray());

        $response = new JsonResponse();
        $response->setData([
            'status'  => !empty($view) ? 'success' : 'error',
            'data'    => [
                'view' => $view,
            ],
            'message' => null,
        ]);
        return $response;
    }

    public function carrierTemporarySaveAction()
    {
        $carrierId = Tools::getValue('carrier_id');
        $link = Tools::getValue('link');
        $selectedOptions = Tools::getValue('selected_options');

        $success = PresetService::instance()->update($carrierId, $link, $selectedOptions, true);

        $response = new JsonResponse();
        $response->setData([
            'status'  => $success ? 'success' : 'error',
            'data'    => $success,
            'message' => null,
        ]);
        return $response;
    }

    public function carrierResetAction()
    {
        PresetService::instance()->deleteCarriers();
        PresetService::instance()->addCarriers();

        $response = new JsonResponse();
        $response->setData([
            'status'  => 'success',
            'data'    => [],
            'message' => $this->l('DHL shipping methods are reset.'),
        ]);

        return $response;
    }

    public function authenticateAction()
    {
        $apiUserId = Tools::getValue('user_id');
        $apiKey = Tools::getValue('key');

        $accounts = Connector::instance()->testAuthenticate($apiUserId, $apiKey);

        $response = new JsonResponse();
        $response->setData([
            'status'  => !empty($accounts) ? 'success' : 'error',
            'data'    => [
                'accounts' => $accounts,
            ],
            'message' => !empty($accounts) ? $this->l('Connection successful') : $this->l('Authentication failed'),
        ]);

        return $response;
    }

    protected function getReceiverCountry($orderId)
    {
        $order = new Order($orderId);
        $receiverAddress = new Address($order->id_address_delivery);
        return strtoupper(Country::getIsoById($receiverAddress->id_country));
    }

    protected function getReceiverPostcode($orderId)
    {
        $order = new Order($orderId);
        $receiverAddress = new Address($order->id_address_delivery);
        return strtoupper($receiverAddress->postcode);
    }

    protected function renderTemplate($path, $data = [])
    {
        if ($this->container && $this->container->get('twig')) {
            if (version_compare(_PS_VERSION_, '1.7.5', '>=')) {
                $template = '@Modules/dhlparcel_shipping/views/templates/' . $path . '.twig';
            } elseif (version_compare(_PS_VERSION_, '1.7.0', '>=') && version_compare(_PS_VERSION_, '1.7.5', '<')) {
                $template = _PS_MODULE_DIR_ . 'dhlparcel_shipping/views/templates/' . $path . '.twig';
            } else {
                return false;
            }
            return $this->container->get('twig')->render($template, $data);
        } else {
            return Kernel::instance()->render($path, $data);
        }

    }
}
