<?php
class Ps_EmailAlertsActionsModuleFrontControllerOverride extends Ps_EmailAlertsActionsModuleFrontController
{
    /**
     * Add a favorite product.
     */
    public function processAdd()
    {
        $context = Context::getContext();

        if ($context->customer->isLogged()) {
            $id_customer = (int) $context->customer->id;
            $customer = new Customer($id_customer);
            $customer_email = (string) $customer->email;
        } elseif (Validate::isEmail((string) Tools::getValue('customer_email'))) {
            $customer_email = (string) Tools::getValue('customer_email');
            $customer = $context->customer->getByEmail($customer_email);
            $id_customer = (isset($customer->id) && ($customer->id != null)) ? (int) $customer->id : null;
        } else {
            exit(json_encode(
                [
                    'error' => true,
                    'message' => $this->trans('Your e-mail address is invalid', [], 'Modules.Mailalerts.Shop'),
                ]
            ));
        }

        $id_product = (int) Tools::getValue('id_product');
        $id_product_attribute = (int) Tools::getValue('id_product_attribute');
        $id_shop = (int) $context->shop->id;
        $id_lang = (int) $context->language->id;
        $product = new Product($id_product, false, $id_lang, $id_shop, $context);

        $mail_alert = MailAlert::customerHasNotification($id_customer, $id_product, $id_product_attribute, $id_shop, null, $customer_email);

        if ($mail_alert) {
            exit(json_encode(
                [
                    'error' => true,
                    'message' => $this->trans('You already have an alert for this product', [], 'Modules.Mailalerts.Shop'),
                ]
            ));
        } elseif (!Validate::isLoadedObject($product)) {
            exit(json_encode(
                [
                    'error' => true,
                    'message' => $this->trans('Your e-mail address is invalid', [], 'Modules.Mailalerts.Shop'),
                ]
            ));
        }

        $mail_alert = new MailAlert();

        $mail_alert->id_customer = (int) $id_customer;
        $mail_alert->customer_email = (string) $customer_email;
        $mail_alert->id_product = (int) $id_product;
        $mail_alert->id_product_attribute = (int) $id_product_attribute;
        $mail_alert->id_shop = (int) $id_shop;
        $mail_alert->id_lang = (int) $id_lang;

        if ($mail_alert->add() !== false) {
            $merchant_mails = str_replace(',', "\n", (string) Configuration::get('MA_MERCHANT_MAILS'));
            $merchant_mails = explode("\n", $merchant_mails);
            foreach ($merchant_mails as $merchant_mail) {
                Mail::Send(
                    $id_lang,
                    'newsubalert',
                    'Nueva notificacion disponibilidad',
                    array(
                        '{productname}' => Product::getProductName($id_product, $id_product_attribute, $id_lang)
                    ),
                    $merchant_mail,
                    null,
                    (string) $configuration['PS_SHOP_EMAIL'],
                    (string) $configuration['PS_SHOP_NAME'],
                    null,
                    null,
                    _PS_MAIL_DIR_,
                    false,
                    $id_shop
                );
            }
            exit(json_encode(
                [
                    'error' => false,
                    'message' => $this->trans('Request notification registered', [], 'Modules.Mailalerts.Shop'),
                ]
            ));
        }

        exit(json_encode(
            [
                'error' => true,
                'message' => $this->trans('Your e-mail address is invalid', [], 'Modules.Mailalerts.Shop'),
            ]
        ));
    }
}
