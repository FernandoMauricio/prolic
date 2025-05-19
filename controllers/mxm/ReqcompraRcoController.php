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
        $searchModel = new ReqcompraRcoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
