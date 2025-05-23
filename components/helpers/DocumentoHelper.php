<?php

namespace app\components\helpers;

use app\models\api\WebManagerService;
use Yii;

class DocumentoHelper
{
    /**
     * Formata CPF ou CNPJ. Também busca razão social via API para CNPJs.
     *
     * @param string $entrada Texto com CPF, CNPJ ou nome da empresa.
     * @param bool $consultarApi Se true, consulta a API no caso de CNPJ.
     * @return string Texto formatado ou original.
     */
    public static function formatarDocumento(string $entrada, bool $consultarApi = true): string
    {
        $entrada = trim($entrada);
        $docLimpo = preg_replace('/\D/', '', $entrada);

        // CNPJ
        if (strlen($docLimpo) === 14) {
            $cnpjFormatado = preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "$1.$2.$3/$4-$5", $docLimpo);

            if ($consultarApi) {
                try {
                    $dadosApi = WebManagerService::consultarFornecedor($docLimpo);
                    if ($dadosApi && isset($dadosApi['razaoSocial'])) {
                        return $cnpjFormatado . ' - ' . $dadosApi['razaoSocial'];
                    } else {
                        Yii::warning("CNPJ não encontrado na API: $docLimpo", __METHOD__);
                        self::registrarFlashUnico('warning', "CNPJ $cnpjFormatado não encontrado na base de fornecedores.");
                    }
                } catch (\Throwable $e) {
                    Yii::error("Erro ao consultar fornecedor: $docLimpo. " . $e->getMessage(), __METHOD__);
                    self::registrarFlashUnico('error', "Erro ao consultar fornecedor $cnpjFormatado.");
                }
            }

            return $cnpjFormatado;
        }

        // CPF
        if (strlen($docLimpo) === 11) {
            return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $docLimpo);
        }

        // Retorna o valor original
        return $entrada;
    }

    /**
     * Aplica a formatação a uma lista de empresas.
     *
     * @param array $empresas Lista de strings contendo CNPJs, CPFs ou nomes.
     * @return array Lista de empresas formatadas.
     */
    public static function formatarListaEmpresas(array $empresas): array
    {
        $atualizadas = [];
        $houveAtualizacao = false;

        foreach ($empresas as $empresa) {
            $formatado = self::formatarDocumento($empresa);

            if (trim($empresa) !== $formatado) {
                Yii::info("Empresa atualizada: '$empresa' => '$formatado'", __METHOD__);
                $houveAtualizacao = true;
            }

            $atualizadas[] = $formatado;
        }

        if ($houveAtualizacao) {
            self::registrarFlashUnico('info', 'Algumas empresas foram atualizadas automaticamente com base na API do MXM.');
        }

        return array_unique($atualizadas);
    }

    /**
     * Garante que uma mensagem de flash só seja adicionada uma vez por tipo.
     */
    private static function registrarFlashUnico(string $tipo, string $mensagem): void
    {
        $session = Yii::$app->session;
        $flashes = (array) $session->getFlash($tipo, []);

        if (!in_array($mensagem, (array) $flashes, true)) {
            $session->setFlash($tipo, array_merge((array) $flashes, [$mensagem]));
        }
    }
}
