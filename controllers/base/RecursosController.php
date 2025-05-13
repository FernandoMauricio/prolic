<?php

namespace app\controllers\base;

use Yii;
use app\models\base\Recursos;
use app\models\base\RecursosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RecursosController implements the CRUD actions for Recursos model.
 */
class RecursosController extends Controller
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

    /**
     * Lists all Recursos models.
     * @return mixed
     */
    public function actionIndex()
    {
        // VERIFICA SE O COLABORADOR FAZ PARTE DA EQUIPE DE COMPRAS (GMA)
        $session = Yii::$app->session;
        if ($session['sess_codunidade'] != 6) {
            return $this->render('/site/acesso-negado');
        }

        $params = Yii::$app->request->queryParams;

        // Redireciona para a aba "Ativos" se não houver status informado
        if (!isset($params['status'])) {
            return $this->redirect(['index', 'status' => 1]);
        }

        $searchModel = new \app\models\base\RecursosSearch();

        // Aplica o status ao filtro
        if (isset($params['status'])) {
            $params['RecursosSearch']['rec_status'] = $params['status'];
        }

        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionToggleStatus()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        if (!$id) {
            return ['success' => false, 'message' => 'ID ausente'];
        }

        $model = Recursos::findOne($id);
        if (!$model) {
            return ['success' => false, 'message' => 'Recurso não encontrado'];
        }

        $model->rec_status = $model->rec_status ? 0 : 1;

        if ($model->save(false)) {
            return ['success' => true, 'status' => $model->rec_status];
        }

        return ['success' => false, 'message' => 'Erro ao salvar'];
    }

    /**
     * Creates a new Recursos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        //VERIFICA SE O COLABORADOR FAZ PARTE DA EQUIPE DE COMPRAS (GMA)
        $session = Yii::$app->session;
        if ($session['sess_codunidade'] != 6) {
            return $this->render('/site/acesso-negado');
        } else {

            $model = new Recursos();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', '<b>SUCESSO! </b> Segmento cadastrado!</b>');
                return $this->redirect(['index']);
            }

            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Recursos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        //VERIFICA SE O COLABORADOR FAZ PARTE DA EQUIPE DE COMPRAS (GMA)
        $session = Yii::$app->session;
        if ($session['sess_codunidade'] != 6) {
            return $this->render('/site/acesso-negado');
        } else {

            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', '<b>SUCESSO! </b> Segmento cadastrado!</b>');
                return $this->redirect(['index']);
            }

            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Recursos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Recursos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Recursos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Recursos::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function AccessAllow()
    {
        $session = Yii::$app->session;
        if (
            !isset($session['sess_codusuario'])
            && !isset($session['sess_codcolaborador'])
            && !isset($session['sess_codunidade'])
            && !isset($session['sess_nomeusuario'])
            && !isset($session['sess_coddepartamento'])
            && !isset($session['sess_codcargo'])
            && !isset($session['sess_cargo'])
            && !isset($session['sess_setor'])
            && !isset($session['sess_unidade'])
            && !isset($session['sess_responsavelsetor'])
        ) {
            return $this->redirect('https://portalsenac.am.senac.br');
        }
    }
}
