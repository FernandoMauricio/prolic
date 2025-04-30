<?php

namespace app\components;

use yii\base\Component;
use yii\httpclient\Client;

class ApiClient extends Component
{
    public $baseUrl;

    public function init()
    {
        parent::init();
        $this->baseUrl = getenv('API_BASE_URL');
    }

    public function post($endpoint, $data)
    {
        $client = new Client([
            'transport' => 'yii\httpclient\CurlTransport',
        ]);

        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($this->baseUrl . $endpoint)
            ->setFormat(Client::FORMAT_JSON)
            ->setData($data)
            ->addHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->send();

        return $response->isOk ? $response->data : [
            'error' => $response->content,
            'status' => $response->statusCode,
        ];
    }
}
