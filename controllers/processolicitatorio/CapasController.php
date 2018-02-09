<?php

namespace app\controllers\processolicitatorio;

use Yii;
use app\models\processolicitatorio\ProcessoLicitatorio;
use app\models\processolicitatorio\Capas;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;


class CapasController extends Controller
{

    public function actionGerarRelatorio($id)
    {
    	$model = new Capas();

        if ($model->load(Yii::$app->request->post())) {

            if($model->cap_tipo == 0){
                  return $this->redirect(['capa-padrao', 'id' => $id]);
                }
            else if($model->cap_tipo == 1){
                  return $this->redirect(['capa-fecomercio', 'id' => $id]);
                }
            else if($model->cap_tipo == 2){
                  return $this->redirect(['capa-indice', 'id' => $id]);
                }
        }else{
            return $this->renderAjax('/processolicitatorio/processo-licitatorio/capas/gerar-relatorio', [
                'model' => $model,
                ]);
        }

    }

    public function actionCapaPadrao($id)
    {
       $this->layout = 'main-imprimir';
       $model = $this->findProcessoLicitatorio($id);

            return $this->render('/processolicitatorio/processo-licitatorio/capas/capa-padrao', [
              'model' => $model, 
              ]);
    }

    protected function findProcessoLicitatorio($id)
    {
        if (($model = ProcessoLicitatorio::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
