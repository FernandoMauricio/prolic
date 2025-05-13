<?php

namespace app\controllers\base;

use Yii;
use app\models\base\Artigo;
use app\models\base\ArtigoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ArtigoController implements the CRUD actions for Artigo model.
 */
class ArtigoController extends Controller
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
     * Lists all Artigo models.
     * @return mixed
     */
    public function actionIndex()
    {
        // VERIFICA SE O COLABORADOR FAZ PARTE DA EQUIPE DE COMPRAS (GMA)
        $session = Yii::$app->session;
        if ($session['sess_codunidade'] != 6) {
            return $this->render('/site/acesso-negado');
        } else {
            $params = Yii::$app->request->queryParams;

            // Se não tiver status na URL, redireciona para a aba "Ativos"
            if (!isset($params['status'])) {
                return $this->redirect(['index', 'status' => 1]);
            }

            $searchModel = new ArtigoSearch();

            // Força o filtro de status no search model
            if (isset($params['status'])) {
                $params['ArtigoSearch']['art_status'] = $params['status'];
            }

            $dataProvider = $searchModel->search($params);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    public function actionToggleStatus()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        if (!$id) {
            return ['success' => false, 'message' => 'ID ausente'];
        }

        $model = Artigo::findOne($id);
        if (!$model) {
            return ['success' => false, 'message' => 'Artigo não encontrado'];
        }

        $model->art_status = $model->art_status ? 0 : 1;

        if ($model->save(false)) {
            return ['success' => true, 'status' => $model->art_status];
        }

        return ['success' => false, 'message' => 'Erro ao salvar'];
    }

    public function actionHomologar($id)
    {
        //VERIFICA SE O COLABORADOR FAZ PARTE DA EQUIPE DE COMPRAS (GMA)
        $session = Yii::$app->session;
        if ($session['sess_codunidade'] != 6) {
            return $this->render('/site/acesso-negado');
        } else {

            $session = Yii::$app->session;
            $model = $this->findModel($id);

            if ($model->art_status == 0) { //Cadastros Inativados
                Yii::$app->session->setFlash('danger', '<b>ERRO! </b>Não é possível homologar um cadastro inativo!</b>');
                return $this->redirect(['index']);
            } else {
                //Homologa o limite da modalidade
                $connection = Yii::$app->db;
                $connection->createCommand()
                    ->update('artigo', ['art_homologacaousuario' => $session['sess_nomeusuario'], 'art_homologacaodata' => date('Y-m-d')], ['id' => $model->id])
                    ->execute();

                Yii::$app->session->setFlash('success', '<b>SUCESSO!</b> Artigo:<b> ' . $model->art_descricao . '</b> foi HOMOLOGADO!</b>');
            }
            return $this->redirect(['index']);
        }
    }

    /**
     * Creates a new Artigo model.
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

            $model = new Artigo();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', '<b>SUCESSO! </b> Artigo cadastrado!</b>');
                return $this->redirect(['index']);
            }

            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Artigo model.
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
                Yii::$app->session->setFlash('success', '<b>SUCESSO! </b> Artigo atuaizado!</b>');
                return $this->redirect(['index']);
            }

            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Artigo model.
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
     * Finds the Artigo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Artigo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Artigo::findOne($id)) !== null) {
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
