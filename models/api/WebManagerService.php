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

    public static function consultarFornecedor(string $cpfOuCnpj): array
    {
        $documento = preg_replace('/\D/', '', $cpfOuCnpj);

        if (strlen($documento) !== 11 && strlen($documento) !== 14) {
            Yii::warning("CPF ou CNPJ inválido informado: $cpfOuCnpj", __METHOD__);
            return [];
        }

        $url = rtrim($_ENV['MXM_BASE_URL'], '/') . '/webmanager/api/InterfacedoFornecedor/ConsultaporCPFouCNPJ';

        try {
            $client = new Client([
                'transport' => 'yii\httpclient\CurlTransport',
            ]);

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
                Yii::error("Falha ao consultar fornecedor: $documento", __METHOD__);
                return [];
            }

            return self::formatFornecedor($response->data['Data']['InterfacedoFornecedor'][0]);
        } catch (\Throwable $e) {
            Yii::error("Erro na requisição do fornecedor ($documento): " . $e->getMessage(), __METHOD__);
            return [];
        }
    }

    private static function formatFornecedor(array $dados): array
    {
        return [
            'documento' => $dados['CPFouCNPJ'] ?? null,
            'razaoSocial' => $dados['Nome'] ?? null,
            'nomeFantasia' => $dados['NomeFantasia'] ?? null,
            'status' => $dados['Status'] ?? null,
            'endereco' => [
                'logradouro' => $dados['Endereco'] ?? null,
                'bairro' => $dados['Bairro'] ?? null,
                'cidade' => $dados['Cidade'] ?? null,
                'uf' => $dados['UF'] ?? null,
                'cep' => $dados['CEP'] ?? null,
            ],
            'banco' => $dados['ContaCorrenteFornecedor'][0]['Banco'] ?? null,
            'agencia' => $dados['ContaCorrenteFornecedor'][0]['CodigoAgencia'] ?? null,
            'conta' => $dados['ContaCorrenteFornecedor'][0]['CodigoContaNoBanco'] ?? null,
        ];
    }

    public static function consultarStatusRequisicao(string $CodigoRequisicao): ?string
    {
        $client = new \yii\httpclient\Client([
            'transport' => 'yii\httpclient\CurlTransport',
        ]);

        $url = rtrim($_ENV['MXM_BASE_URL'], '/') . '/webmanager/api/InterfaceDaRequisicaoDeCompra/ObterStatusRequisicao';

        try {
            $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl($url)
                ->setFormat(\yii\httpclient\Client::FORMAT_JSON)
                ->setData([
                    'AutheticationToken' => self::buildAuthPayload(),
                    'Data' => [
                        'CodigoRequisicao' => $CodigoRequisicao
                    ]
                ])
                ->addHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->send();

            if ($response->isOk && !empty($response->data['Data']['StatusRequisicao'])) {
                return $response->data['Data']['StatusRequisicao'];
            }

            Yii::warning("Status da requisição {$CodigoRequisicao} não retornado corretamente", __METHOD__);
        } catch (\Throwable $e) {
            Yii::error("Erro na API de status da requisição: " . $e->getMessage(), __METHOD__);
        }

        return null;
    }

    public static function consultarPedidoRequisicao(string $codigoEmpresa, string $numeroRequisicao): array
    {
        $client = new Client([
            'transport' => 'yii\httpclient\CurlTransport',
        ]);

        $url = rtrim($_ENV['MXM_BASE_URL'], '/') . '/webmanager/api/InterfaceDaRequisicaoDeCompra/ConsultarPedidoDeRequisicaoCompras';

        try {
            $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl($url)
                ->setFormat(Client::FORMAT_JSON)
                ->setData([
                    'AutheticationToken' => self::buildAuthPayload(),
                    'Data' => [
                        'CodigoEmpresa' => $codigoEmpresa,
                        'NumeroRequisicao' => $numeroRequisicao,
                    ]
                ])
                ->addHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->send();

            if (!$response->isOk || empty($response->data['Data'][0])) {
                Yii::error("Erro ao consultar requisição {$numeroRequisicao} da empresa {$codigoEmpresa}", __METHOD__);
                return [];
            }

            return self::formatarPedidoRequisicao($response->data['Data'][0]);
        } catch (\Throwable $e) {
            Yii::error("Erro na requisição de pedido: " . $e->getMessage(), __METHOD__);
            return [];
        }
    }

    private static function formatarPedidoRequisicao(array $dados): array
    {
        return [
            'empresa' => $dados['CodigoEmpresa'] ?? '',
            'numero' => $dados['NumeroPedidoCompras'] ?? '',
            'data' => $dados['DataPedido'] ?? '',
            'requisitante' => $dados['CodigoRequisitante'] ?? '',
            'condicaoPagamento' => $dados['CodigoCondicaoPagamento'] ?? '',
            'valorTotal' => $dados['ValorTotalPedido'] ?? '',
            'status' => $dados['DescricaoStatusPedido'] ?? '',
            'itens' => $dados['ItensPedido'] ?? [],
        ];
    }
}
