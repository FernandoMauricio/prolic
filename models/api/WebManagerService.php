<?php

namespace app\models\api;

use yii\httpclient\Client;
use Yii;

class WebManagerService
{
    private static function buildAuthPayload(): array
    {
        return [
            'Username' => $_ENV['MXM_USERNAME'] ?? '',
            'Password' => $_ENV['MXM_PASSWORD'] ?? '',
            'EnvironmentName' => $_ENV['MXM_ENV'] ?? '',
        ];
    }

    public static function consultarEmpresaPorCnpj(string $cnpj): array
    {
        $documento = preg_replace('/\D/', '', $cnpj);
        $client = new Client([
            'transport' => 'yii\httpclient\CurlTransport',
        ]);

        $url = rtrim($_ENV['MXM_BASE_URL'], '/') . '/webmanager/api/InterfacedoFornecedor/ConsultaporCPFouCNPJ';

        try {
            $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl($url)
                ->setFormat(Client::FORMAT_JSON)
                ->setData([
                    'AutheticationToken' => self::buildAuthPayload(),
                    'Data' => [
                        'InterfacedoFornecedor' => [
                            ['CPFouCNPJ' => $documento]
                        ]
                    ]
                ])
                ->addHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->send();

            if (!$response->isOk || empty($response->data['Data']['InterfacedoFornecedor'][0])) {
                Yii::error("Erro ao consultar empresa CNPJ: {$cnpj}", __METHOD__);
                return [];
            }

            return self::formatEmpresa($response->data['Data']['InterfacedoFornecedor'][0]);
        } catch (\Throwable $e) {
            Yii::error("Erro na requisição de CNPJ: {$e->getMessage()}", __METHOD__);
            return [];
        }
    }

    private static function formatEmpresa(array $dados): array
    {
        return [
            'cnpj' => $dados['CPFouCNPJ'] ?? null,
            'razaoSocial' => $dados['Nome'] ?? null,
            'nomeFantasia' => $dados['NomeFantasia'] ?? null,
            'endereco' => [
                'logradouro' => $dados['Endereco'] ?? null,
                'bairro' => $dados['Bairro'] ?? null,
                'cidade' => $dados['Cidade'] ?? null,
                'uf' => $dados['UF'] ?? null,
                'cep' => $dados['CEP'] ?? null,
            ],
            'status' => $dados['Status'] ?? null,
        ];
    }
}
