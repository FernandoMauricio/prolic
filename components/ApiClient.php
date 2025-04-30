<?php

namespace app\components;

use yii\base\Component;
use yii\httpclient\Client;

class ApiClient extends Component
{
    public $baseUrl;

    public function post($endpoint, $data)
    {
        if (!$this->baseUrl) {
            throw new \RuntimeException('baseUrl não foi definida corretamente.');
        }

        $client = new Client([
            'transport' => 'yii\httpclient\CurlTransport',
        ]);

        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl(rtrim($this->baseUrl, '/') . '/' . ltrim($endpoint, '/'))
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
