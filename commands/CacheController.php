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
        $this->stdout("Iniciando exportação de requisições individuais...\n");

        $basePath = Yii::getAlias('@runtime/cache/requisicoes');
        if (!is_dir($basePath)) {
            mkdir($basePath, 0777, true);
        }

        try {
            $requisicoes = Yii::$app->db_oracle->createCommand("
                SELECT * FROM REQCOMPRA_RCO
                WHERE RCO_TIPO IN ('RDSV', 'RDMC', 'RDBM')
                AND RCO_DATA >= ADD_MONTHS(SYSDATE, -36)
                ORDER BY RCO_DATA DESC
            ")->queryAll();

            $index = [];

            foreach ($requisicoes as $requisicao) {
                $numero = $requisicao['RCO_NUMERO'];
                $itens = Yii::$app->db_oracle->createCommand("
                    SELECT * FROM IREQCOMPRA_IRC
                    WHERE IRC_NUMERO = :numero
                    ORDER BY IRC_ITEM
                ", [':numero' => $numero])->queryAll();

                $data = [
                    'requisicao' => $this->convertEncodingRecursive($requisicao),
                    'itens' => $this->convertEncodingRecursive($itens),
                ];

                $arquivo = "$basePath/{$numero}.json";
                file_put_contents($arquivo, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

                $index[] = $numero;
                $this->stdout("Requisição $numero exportada...\n");
            }

            // Gera o índice simples de requisições
            file_put_contents("$basePath/requisicoes-index.json", json_encode($index, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            $this->stdout("Exportação concluída. Total: " . count($index) . " arquivos.\n");
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
