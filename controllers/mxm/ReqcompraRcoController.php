<?php

namespace app\controllers\mxm;

use app\models\mxm\ItpedcompraIpc;
use Yii;
use app\models\mxm\ReqcompraRco;
use app\models\mxm\ReqcompraRcoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ReqcompraRcoController implements the CRUD actions for ReqcompraRco model.
 * Aqui focamos apenas em `index` e `view` por ser uma tabela grande do Oracle (leitura).
 */
class ReqcompraRcoController extends Controller
{
    public function actionIndex()
    {
        $path = Yii::getAlias('@runtime/cache/requisicoes.json');

        if (!file_exists($path)) {
            throw new \yii\web\NotFoundHttpException('Cache ainda não gerado.');
        }

        $requisicoes = json_decode(file_get_contents($path), true);

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $requisicoes,
            'pagination' => ['pageSize' => 50],
            'sort' => ['attributes' => ['RCO_NUMERO', 'RCO_DATA']],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => null,
        ]);
    }

    public function actionView($id)
    {
        $model = ReqcompraRco::findOne($id);

        $itens = ItpedcompraIpc::find()
            ->where([
                'IPC_REQUISIC' => $model->RCO_NUMERO,
                'IPC_CDEMPRESA' => $model->RCO_EMPRESA
            ])
            ->orderBy(['IPC_NUMITEM' => SORT_ASC])
            ->asArray()
            ->all();

        // Converte campos ISO para UTF-8 manualmente
        array_walk($itens, function (&$item) {
            foreach ($item as $key => $value) {
                if (is_string($value)) {
                    $item[$key] = mb_convert_encoding($value, 'UTF-8', 'ISO-8859-1');
                }
            }
        });

        return $this->render('view', [
            'model' => $model,
            'itens' => $itens,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = ReqcompraRco::findOne(['RCO_NUMERO' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Requisição não encontrada.');
    }
}
