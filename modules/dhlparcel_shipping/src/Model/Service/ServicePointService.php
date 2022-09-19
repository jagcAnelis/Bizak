<?php

namespace DHLParcel\Shipping\Model\Service;

use DHLParcel\Shipping\Model\Api\Connector;
use DHLParcel\Shipping\Model\Core\SingletonAbstract;
use DHLParcel\Shipping\Model\Data\Api\Response\ServicePoint;

class ServicePointService extends SingletonAbstract
{

    protected $connector;
    protected $servicePointResponseFactory;

    /**
     * @param $search
     * @param $country
     * @param int $limit
     * @return ServicePoint[]
     */
    public function search($search, $country, $limit = 13)
    {
        if (trim(strtoupper($country)) === 'DE') {
            $typeRestrictions = ['packStation'];
        } else {
            $typeRestrictions = [];
        }

        $response = Connector::instance()->get('parcel-shop-locations/' . $country, [
            'limit'   => $limit + count($typeRestrictions)*7,
            'fuzzy' => strtoupper($search),
        ]);

        if (!$response || !is_array($response)) {
            return [];
        }

        $found = 0;
        $servicePoints = [];
        foreach ($response as $responseData) {
            $servicePoint = new ServicePoint($responseData);

            if (!in_array($servicePoint->shopType, $typeRestrictions)) {
                $servicePoint->country = $country;
                $servicePoints[] = $servicePoint;
                if (++$found >= $limit) {
                    break;
                }
            }
        }

        if (!$found) {
            return null;
        }

        return $servicePoints;
    }

    /**
     * @param $id
     * @param $country
     * @return ServicePoint|null
     */
    public function get($id, $country)
    {
        if (!$id) {
            return null;
        }

        $postNumber = null;
        if (($position = strpos($id, "|")) !== false) {
            $postNumber = substr($id, $position + 1);
        }

        // Remove any additional fields
        $id = strstr($id, '|', true) ?: $id;

        $response = Connector::instance()->get(sprintf('parcel-shop-locations/%s/%s', $country, $id));
        if (!$response) {
            return null;
        }

        $response['country'] = $country;
        $servicePoint = new ServicePoint($response);

        if ($servicePoint->shopType === 'packStation') {
            if (empty($servicePoint->name)) {
                $servicePoint->name = $servicePoint->keyword;
            }
            if (!empty($postNumber)) {
                $servicePoint->name = $servicePoint->name . ' ' . $postNumber;
                $servicePoint->id = $servicePoint->id . '|' . $postNumber;
            }
        }

        return $servicePoint;
    }
}
