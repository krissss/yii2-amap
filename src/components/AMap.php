<?php

namespace kriss\amap\components;

use kriss\amap\exceptions\ApiClientException;
use kriss\amap\exceptions\ApiResultException;
use kriss\amap\exceptions\ApiServerException;
use Yii;
use yii\base\BaseObject;
use yii\helpers\Json;
use yii\httpclient\Client;
use yii\httpclient\CurlTransport;
use yii\httpclient\Exception;

/**
 * @link https://developer.amap.com/api/webservice/guide/api/georegeo
 */
class AMap extends BaseObject
{
    /**
     * @var string
     */
    public $baseUrl = 'https://restapi.amap.com/v3';
    /**
     * @link https://developer.amap.com/?ref=http%3A%2F%2Flbs.amap.com%2Fdev%2Fkey
     * @var string
     */
    public $key;
    /**
     * @link https://github.com/yiisoft/yii2-httpclient/blob/master/docs/guide/README.md
     * @var array
     */
    public $clientOptions = [];
    /**
     * @var string
     */
    public $logCategory;

    /**
     * @var Client
     */
    protected $client;

    public function init()
    {
        parent::init();

        $this->client = new Client(array_merge([
            'baseUrl' => $this->baseUrl,
            'transport' => CurlTransport::class,
        ], $this->clientOptions));
    }

    /**
     * @param string $url
     * @param array $params
     * @return array
     * @throws ApiClientException
     * @throws ApiResultException
     * @throws ApiServerException
     */
    public function api($url, $params = [])
    {
        $this->logger(['request', $url, $params]);

        try {
            $response = $this->client->createRequest()
                ->setMethod('GET')
                ->setUrl($url)
                ->setData(array_merge([
                    'key' => $this->key,
                    'output' => 'JSON',
                ], $params))
                ->send();
        } catch (Exception $e) {
            throw new ApiClientException($e->getMessage());
        }
        $this->logger(['response', $url, $response->content]);
        if (!$response->isOk) {
            $this->logger(['response error', $url, $response->getStatusCode()], 'error');
            throw new ApiServerException($response->content, $response->getStatusCode());
        }
        $data = Json::decode($response->content);
        if ($data['status'] == 0) {
            $this->logger(['response result error', $url, $response->content], 'error');
            throw new ApiResultException($data['info'], isset($data['infocode']) ? $data['infocode'] : 0);
        }

        return $data;
    }

    /**
     * @param array|string $msg
     * @param string $type
     */
    protected function logger($msg, $type = 'info')
    {
        if (!$this->logCategory) {
            return;
        }
        if (is_array($msg)) {
            $msg = Json::encode($msg);
        }
        Yii::$type($msg, $this->logCategory);
    }
}