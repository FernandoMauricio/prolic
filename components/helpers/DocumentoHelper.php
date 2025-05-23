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

        // Verifica se contém um CPF ou CNPJ válido no meio da string
        if (preg_match('/\b\d{11}\b/', $entrada, $matchCpf)) {
            $cpf = $matchCpf[0];
            $cpfFormatado = preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $cpf);

            if ($consultarApi) {
                try {
                    $dadosApi = WebManagerService::consultarFornecedor($cpf);
                    if ($dadosApi && isset($dadosApi['razaoSocial'])) {
                        return $cpfFormatado . ' - ' . $dadosApi['razaoSocial'];
                    } else {
                        Yii::warning("CPF não encontrado na API: $cpf", __METHOD__);
                        self::registrarFlashUnico('warning', "CPF $cpfFormatado não encontrado na base de fornecedores.");
                    }
                } catch (\Throwable $e) {
                    Yii::error("Erro ao consultar fornecedor (CPF): $cpf. " . $e->getMessage(), __METHOD__);
                    self::registrarFlashUnico('error', "Erro ao consultar fornecedor $cpfFormatado.");
                }
            }

            return $cpfFormatado;
        }

        if (preg_match('/\b\d{14}\b/', $entrada, $matchCnpj)) {
            $cnpj = $matchCnpj[0];
            $cnpjFormatado = preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "$1.$2.$3/$4-$5", $cnpj);

            if ($consultarApi) {
                try {
                    $dadosApi = WebManagerService::consultarFornecedor($cnpj);
                    if ($dadosApi && isset($dadosApi['razaoSocial'])) {
                        return $cnpjFormatado . ' - ' . $dadosApi['razaoSocial'];
                    } else {
                        Yii::warning("CNPJ não encontrado na API: $cnpj", __METHOD__);
                        self::registrarFlashUnico('warning', "CNPJ $cnpjFormatado não encontrado na base de fornecedores.");
                    }
                } catch (\Throwable $e) {
                    Yii::error("Erro ao consultar fornecedor (CNPJ): $cnpj. " . $e->getMessage(), __METHOD__);
                    self::registrarFlashUnico('error', "Erro ao consultar fornecedor $cnpjFormatado.");
                }
            }

            return $cnpjFormatado;
        }

        // Nenhum CPF ou CNPJ encontrado → gera aviso e retorna original
        self::registrarFlashUnico('warning', "A empresa \"$entrada\" não possui CPF ou CNPJ válido informado.");
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
        $houveAtualizacaoComApi = false;

        foreach ($empresas as $empresa) {
            $original = trim($empresa);
            $formatado = self::formatarDocumento($original);

            // Considera como atualização "com sucesso" se veio formatado + nome da API
            if ($formatado !== $original && strpos($formatado, ' - ') !== false) {
                $houveAtualizacaoComApi = true;
                Yii::info("Empresa atualizada: '$original' => '$formatado'", __METHOD__);
            }

            $atualizadas[] = $formatado;
        }

        // Apenas se teve pelo menos uma atualização bem-sucedida
        if ($houveAtualizacaoComApi) {
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
