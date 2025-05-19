<?php

namespace app\controllers\mxm;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ArrayDataProvider;

class ReqcompraRcoController extends Controller
{
    private function carregarCache()
    {
        $caminho = Yii::getAlias('@runtime/cache/requisicoes-cache.json');
        if (!file_exists($caminho)) {
            throw new \Exception('Arquivo de cache de requisições não encontrado.');
        }

        $dados = json_decode(file_get_contents($caminho), true);

        if (!is_array($dados)) {
            throw new \Exception('Formato inválido no arquivo de cache.');
        }

        return $dados;
    }

    public function actionIndex()
    {
        $dados = json_decode(file_get_contents(Yii::getAlias('@runtime/cache/requisicoes-cache.json')), true);

        $query = array_filter($dados, function ($item) {
            $req = $item['requisicao'] ?? [];

            $busca = Yii::$app->request->get('q');

            if (!$busca) return true;

            return stripos($req['RCO_NUMERO'] ?? '', $busca) !== false
                || stripos($req['RCO_REQUISITANTE'] ?? '', $busca) !== false
                || stripos($req['RCO_OBS'] ?? '', $busca) !== false;
        });

        $provider = new \yii\data\ArrayDataProvider([
            'allModels' => $query,
            'pagination' => ['pageSize' => 20],
            'sort' => [
                'attributes' => [
                    'RCO_NUMERO' => [
                        'asc' => ['requisicao.RCO_NUMERO' => SORT_ASC],
                        'desc' => ['requisicao.RCO_NUMERO' => SORT_DESC],
                    ],
                    'RCO_DATA' => [
                        'asc' => ['requisicao.RCO_DATA' => SORT_ASC],
                        'desc' => ['requisicao.RCO_DATA' => SORT_DESC],
                    ],
                    'RCO_REQUISITANTE' => [
                        'asc' => ['requisicao.RCO_REQUISITANTE' => SORT_ASC],
                        'desc' => ['requisicao.RCO_REQUISITANTE' => SORT_DESC],
                    ],
                ]
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $provider,
            'searchTerm' => Yii::$app->request->get('q'),
        ]);
    }


    public function actionView($id)
    {
        $dados = $this->carregarCache();

        foreach ($dados as $registro) {
            if ($registro['requisicao']['RCO_NUMERO'] === $id) {
                return $this->render('view', [
                    'model' => $registro['requisicao'],
                    'itens' => $registro['itens'],
                ]);
            }
        }

        throw new NotFoundHttpException("Requisição {$id} não encontrada no cache.");
    }
}
