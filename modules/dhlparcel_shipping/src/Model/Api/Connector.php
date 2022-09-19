<?php

namespace DHLParcel\Shipping\Model\Api;

use DHLParcel\Shipping\Model\Core\Settings;
use DHLParcel\Shipping\Model\Core\SingletonAbstract;
use GuzzleHttp\Client;
use Configuration;
use Cache;

class Connector extends SingletonAbstract
{
    const POST = 'post';
    const GET = 'get';

    const AUTH_API = 'authenticate/api-key';

    protected $accessToken;
    /** @var Client */
    protected $client;
    protected $failedAuthentication = false;
    protected $url = 'https://api-gw.dhlparcel.nl/';
    public $isError = false;
    public $errorCode = null;
    public $errorMessage = null;

    public function __construct()
    {
        $this->client = new Client();
        if (Configuration::get(Settings::ALT_API_ENABLED)) {
            $this->url = Configuration::get(Settings::ALT_API_URL);
        }
    }

    public function getAvailableMethods()
    {
        return [
            self::POST,
            self::GET
        ];
    }

    public function post($endpoint, $params = null)
    {
        $request = $this->request(self::POST, $endpoint, $params);

        if (!$request) {
            return false;
        }

        $data = json_decode($request->getBody()->getContents(), true);
        return $data;
    }

    public function get($endpoint, $params = null)
    {
        $cacheId = 'dhlparcel_'.$endpoint.'_'.crc32(json_encode($params));
        if (!empty(Cache::isStored($cacheId))) {
            return Cache::retrieve($cacheId);
        }

        $request = $this->request(self::GET, $endpoint, $params);
        if (!$request) {
            return false;
        }

        $data = json_decode($request->getBody()->getContents(), true);
        Cache::store($cacheId, $data);
        return $data;
    }

    public function request($method, $endpoint, $params = [], $isRetry = false)
    {
        // Assume there's always an error, until this method manages to return correctly and set the boolean to true.
        $this->isError = true;
        $this->errorCode = null;
        $this->errorMessage = null;
        $options = ['headers' => [
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json'
        ]];

        if ($endpoint != self::AUTH_API) {
            if (empty($this->accessToken)) {
                $this->authenticate();
            }
            $options['headers']['Authorization'] = 'Bearer ' . $this->accessToken;
        }

        if (!empty($params)) {
            if ($method == self::POST) {
                $options['json'] = $params;
            } else {
                $options['query'] = $params;
            }
        }
        try {
            /** @var \GuzzleHttp\Message\ResponseInterface $response */
            $response = $this->client->{$method}($this->url . $endpoint, $options);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            if ($e->getCode() === 401 && $endpoint !== self::AUTH_API && $isRetry === false && $this->failedAuthentication !== true) {
                // Try again after an auth
                $this->authenticate(true);
                $response = $this->request($method, $endpoint, $params, true);
            } else {
                $this->isError = true;
                $this->errorCode = $e->getCode();
                $this->errorMessage = $e->getResponse()->getBody()->getContents();

                return false;
            }
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            $this->isError = true;
            $this->errorCode = $e->getCode();
            $this->errorMessage = $e->getResponse()->getBody()->getContents();
            if (Configuration::get(Settings::DEBUG_ENABLED)) {
                throw $e;
            }

            return false;
        } catch (\Exception $e) {
            $this->isError = true;
            $this->errorCode = $e->getCode();
            $this->errorMessage = $e->getMessage();
            if (Configuration::get(Settings::DEBUG_ENABLED)) {
                throw $e;
            }

            return false;
        }

        if (!is_bool($response) && $response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            $this->isError = false;
            return $response;
        }
        return false;
    }

    protected function authenticate($refresh = false)
    {
        // Prevent endless authentication calls
        if ($this->failedAuthentication) {
            // Exit early
            return;
        }
        if (!empty($this->accessToken) && $refresh === false) {
            return;
        }

        $response = $this->post(self::AUTH_API, [
            'userId' => trim(Configuration::get(Settings::API_USER_ID)),
            'key'    => trim(Configuration::get(Settings::API_KEY)),
        ]);
        if (!empty($response['accessToken'])) {
            $this->accessToken = $response['accessToken'];
        }
        if (empty($response['accessToken']) && $refresh === true) {
            $this->failedAuthentication = true;
        }
    }

    /**
     * @param $apiUserId
     * @param $apiKey
     * @return array
     */
    public function testAuthenticate($apiUserId, $apiKey)
    {
        $response = $this->post(self::AUTH_API, [
            'userId' => trim($apiUserId),
            'key'    => trim($apiKey),
        ]);

        if (!isset($response['accessToken'])) {
            return [];
        }

        if (!isset($response['accountNumbers'])) {
            return [];
        }

        return $response['accountNumbers'];
    }
}
