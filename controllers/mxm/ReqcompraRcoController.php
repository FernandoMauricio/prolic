<?php

namespace app\controllers\mxm;

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
        return $this->render('view', [
            'model' => $this->findModel($id),
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
