<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\mxm\ReqcompraRco;
use yii\helpers\Json;

class CacheController extends Controller
{
    public function actionRequisicoes()
    {
        $this->stdout("Iniciando exportação de requisições...\n");

        try {
            $requisicoes = Yii::$app->db_oracle->createCommand("
            SELECT * FROM REQCOMPRA_RCO
            WHERE RCO_TIPO IN ('RDSV', 'RDMC')
            AND RCO_DATA >= ADD_MONTHS(SYSDATE, -36)
            ORDER BY RCO_DATA DESC
            ")->queryAll();

            $dados = [];

            foreach ($requisicoes as $requisicao) {
                $itens = Yii::$app->db_oracle->createCommand("
                    SELECT * FROM ITPEDCOMPRA_IPC
                    WHERE IPC_REQUISIC = :numero
                    AND IPC_CDEMPRESA = :empresa
                    ORDER BY IPC_NUMITEM
                ", [
                    ':numero' => $requisicao['RCO_NUMERO'],
                    ':empresa' => $requisicao['RCO_EMPRESA'],
                ])->queryAll();

                $dados[] = [
                    'requisicao' => $this->convertEncodingRecursive($requisicao),
                    'itens' => $this->convertEncodingRecursive($itens),
                ];
            }

            $caminho = Yii::getAlias('@runtime/cache/requisicoes-cache.json');
            file_put_contents($caminho, json_encode($dados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            $this->stdout("Exportação finalizada com sucesso! Cache salvo em:\n$caminho\n");
        } catch (\Throwable $e) {
            $this->stderr("Erro ao gerar cache: " . $e->getMessage() . "\n");
        }
    }

    private function convertEncodingRecursive($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'convertEncodingRecursive'], $data);
        } elseif (is_string($data)) {
            return mb_convert_encoding($data, 'UTF-8', 'ISO-8859-1');
        }
        return $data;
    }
}
