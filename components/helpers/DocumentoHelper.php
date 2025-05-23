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
                $dadosApi = WebManagerService::consultarFornecedor($docLimpo);
                if ($dadosApi && isset($dadosApi['razaoSocial'])) {
                    return $cnpjFormatado . ' - ' . $dadosApi['razaoSocial'];
                }
            }

            return $cnpjFormatado;
        }

        // CPF
        if (strlen($docLimpo) === 11) {
            return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $docLimpo);
        }

        // Retorna o valor original se não for CNPJ nem CPF
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
            Yii::$app->session->setFlash('empresaAtualizadaViaApi', true);
        }

        return array_unique($atualizadas);
    }
}
