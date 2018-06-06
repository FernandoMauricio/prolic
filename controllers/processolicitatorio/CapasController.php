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
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $this->AccessAllow(); //Irá ser verificado se o usuário está logado no sistema

        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionGerarRelatorio($id)
    {
        //VERIFICA SE O COLABORADOR FAZ PARTE DA EQUIPE DE COMPRAS (GMA)
        $session = Yii::$app->session;
       if($session['sess_codunidade'] != 6){
            return $this->AccessoAdministrador();
        }
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
    }

    public function actionCapaPadrao($id)
    {
        //VERIFICA SE O COLABORADOR FAZ PARTE DA EQUIPE DE COMPRAS (GMA)
        $session = Yii::$app->session;
       if($session['sess_codunidade'] != 6){
            return $this->AccessoAdministrador();
        }

        $this->layout = 'main-imprimir';
        $model = $this->findProcessoLicitatorio($id);

        return $this->render('/processolicitatorio/processo-licitatorio/capas/capa-padrao', [
          'model' => $model, 
          ]);
      }
    }

    public function actionCapaFecomercio($id)
    {
        //VERIFICA SE O COLABORADOR FAZ PARTE DA EQUIPE DE COMPRAS (GMA)
        $session = Yii::$app->session;
       if($session['sess_codunidade'] != 6){
            return $this->AccessoAdministrador();
        }

        $this->layout = 'main-imprimir';
        $model = $this->findProcessoLicitatorio($id);

        return $this->render('/processolicitatorio/processo-licitatorio/capas/capa-fecomercio', [
          'model' => $model, 
          ]);
      }
    }

    public function actionCapaIndice($id)
    {
        //VERIFICA SE O COLABORADOR FAZ PARTE DA EQUIPE DE COMPRAS (GMA)
        $session = Yii::$app->session;
       if($session['sess_codunidade'] != 6){
            return $this->AccessoAdministrador();
        }

        $this->layout = 'main-imprimir-indice';
        $model = $this->findProcessoLicitatorio($id);

        return $this->render('/processolicitatorio/processo-licitatorio/capas/capa-indice', [
          'model' => $model, 
          ]);
      }
    }

    protected function findProcessoLicitatorio($id)
    {
        if (($model = ProcessoLicitatorio::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function AccessAllow()
    {
        $session = Yii::$app->session;
        if (!isset($session['sess_codusuario']) 
            && !isset($session['sess_codcolaborador']) 
            && !isset($session['sess_codunidade']) 
            && !isset($session['sess_nomeusuario']) 
            && !isset($session['sess_coddepartamento']) 
            && !isset($session['sess_codcargo']) 
            && !isset($session['sess_cargo']) 
            && !isset($session['sess_setor']) 
            && !isset($session['sess_unidade']) 
            && !isset($session['sess_responsavelsetor'])) 
        {
           return $this->redirect('http://portalsenac.am.senac.br');
        }
    }
    
    public function AccessoAdministrador()
    {
            $this->layout = 'main-acesso-negado';
            return $this->render('/site/acesso_negado');
    }
}
