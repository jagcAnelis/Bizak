<?php

namespace DHLParcel\Shipping\Model\Service;

use DHLParcel\Shipping\Model\Api\Connector;
use DHLParcel\Shipping\Model\Core\SingletonAbstract;
use DHLParcel\Shipping\Model\Data\Api\Response\Capability\Option;
use DHLParcel\Shipping\Model\Data\Api\Response\Shipment\Piece as PieceResponse;
use DHLParcel\Shipping\Model\Data\Api\Response\Label as LabelResponse;
use DHLParcel\Shipping\Model\Data\Db\Label;
use DHLParcel\Shipping\Model\Data\Db\Label\Action;
use Address;
use Country;
use Db;
use DbQuery;
use Order;
use Tools;

class LabelService extends SingletonAbstract
{
    const FILE_PREFIX = 'DHLPPS_';

    /**
     * @param int $orderId
     * @param PieceResponse[] $pieceResponses
     * @param bool $isReturn
     */
    public function save($orderId, $pieceResponses)
    {
        if (empty($pieceResponses)) {
            return [];
        }

        $labels = [];
        foreach ($pieceResponses as $pieceResponse) {
            $response = Connector::instance()->get(sprintf('labels/%s', $pieceResponse->labelId));
            if (!$response) {
                continue;
            }

            $labelResponse = new LabelResponse($response);

            $label = new Label();
            $label->id_order = $orderId;
            $label->label_uuid = $pieceResponse->labelId;
            $label->size = strtoupper($pieceResponse->parcelType);
            $label->options = json_encode($pieceResponse->options);
            $label->file = $this->createPDFFile($orderId, $labelResponse->pdf);
            $label->url = Tools::getHttpHost(true) . __PS_BASE_URI__ . 'upload/' . $label->file;
            $label->tracker_code = $labelResponse->trackerCode;
            $label->is_return = 0;

            $success = Db::getInstance()->insert('dhlparcel_shipping_labels', $label->toArray(true));
            if ($success) {
                $labels[] = $label;
            }
        }

        return $labels;
    }

    /**
     * @param $orderId
     * @return Label[]
     */
    public function getAll($orderId)
    {
        $query = new DbQuery();
        $query->from('dhlparcel_shipping_labels');
        $query->where('id_order = ' . (int) $orderId);
        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

        $labels = [];
        foreach($results as $result)
        {
            $label = new Label($result);
            $label->options = json_decode($label->options, true);
            // TODO TEMP
            $label->services = $this->getServices($label->options);
            $label->actions = $this->getActions($label);
            $label->trackerLink = $this->getTrackerLink($label);
            $labels[] = $label;
        }

        return $labels;
    }

    public function load($labelId)
    {
        $query = new DbQuery();
        $query->from('dhlparcel_shipping_labels');
        $query->where('id_label = ' . (int) $labelId);
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($query);

        if (!$result) {
            return null;
        }

        $label = new Label($result);
        $label->options = json_decode($label->options, true);
        // TODO TEMP
        $label->services = $this->getServices($label->options);
        $label->actions = $this->getActions($label);

        return $label;
    }

    public function delete($labelId)
    {
        if (!is_numeric($labelId)) {
            return false;
        }

        // TODO also delete file

        return Db::getInstance()->delete('dhlparcel_shipping_labels', 'id_label = ' . $labelId, 1);
    }

    protected function getServices($options)
    {
        $descriptions = null;
        foreach($options as $option)
        {
            // TODO TEMP
            if ($option == Option::KEY_DOOR) {
                $descriptions[] = 'Entrega a la dirección del destinatario.';
            } else if ($option == Option::KEY_PS) {
                $descriptions[] = 'DHL ServicePoint';
            } else if ($option == Option::KEY_ADD_RETURN_LABEL) {
                $descriptions[] = 'Etiqueta de devolución';
            } else {
                $descriptions[] = ucfirst(strtolower($option));
            }
        }
        $description = implode(', ', $descriptions);
        // TODO TEMP
        //$description = 'Referentie, Retourlabel, Niet bij de buren bezorgen, Handtekening bij ontvangst, Extra Zeker, Avondlevering. Referentie, Retourlabel, Niet bij de buren bezorgen, Handtekening bij ontvangst, Extra Zeker, Avondlevering';
        return $description;
    }

    /**
     * @param Label $label
     * @return Action[] array
     */
    protected function getActions($label)
    {
        $actions = [];

        // Download
        $action = new Action();
        $action->icon = 'download';
        $action->type = 'download';
        $action->link = $label->url;
        $actions[] = $action;

        // Print
//        $action = new Action();
//        $action->icon = 'print';
//        $action->type = 'print';
//        $action->link = $label->url;
//        $actions[] = $action;

        // Tracking
        $action = new Action();
        $action->icon = 'external-link';
        $action->type = 'tracker';
        $action->link = $this->getTrackerLink($label);
        $actions[] = $action;

        // Delete
        $action = new Action();
        $action->icon = 'trash';
        $action->type = 'delete';
        $action->link = $label->url;
        $actions[] = $action;

        return $actions;
    }

    /**
     * @param Label $label
     */
    protected function getTrackerLink($label)
    {
        $url = 'https://www.dhlparcel.es/es/particulares/atencion-al-cliente/recibir-un-envio/donde-esta-mi-envio.html';

        $order = new Order($label->id_order);
        $address = new Address($order->id_address_delivery);
        $postcode = $address->postcode;
        $landcode = strtoupper(Country::getIsoById($address->id_country));
        $trackerCode = $label->tracker_code;

        return $url .
            '?tt=' . $trackerCode .
            '&pc=' . $postcode .
            '&lc=' . $landcode;
    }

    protected function createPDFFile($orderId, $base64pdf)
    {
        $pdf = base64_decode($base64pdf);
        $uploadPath = _PS_UPLOAD_DIR_;
        $fileName = self::FILE_PREFIX . $orderId . '_' . str_shuffle((string)time() . rand(1000, 9999)) . '.pdf';
        $fullPath = $uploadPath.$fileName;

        // TODO, handle errors
        //$file_save_status = file_put_contents($path, $pdf);
        file_put_contents($fullPath, $pdf);

        return $fileName;
    }


}
