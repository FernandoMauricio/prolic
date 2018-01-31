<?php

namespace app\controllers\base;

use Yii;
use app\models\base\Modalidade;
use app\models\base\Ano;
use app\models\base\Ramo;
use app\models\base\ModalidadeValorlimite;
use app\models\base\ModalidadeValorlimiteSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * ModalidadeValorlimiteController implements the CRUD actions for ModalidadeValorlimite model.
 */
class ModalidadeValorlimiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
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
     * Lists all ModalidadeValorlimite models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ModalidadeValorlimiteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionHomologar($id)
    {
        $session = Yii::$app->session;
        $model = $this->findModel($id);

        if($model->status == 0) { //Cadastros Inativados
            Yii::$app->session->setFlash('danger', '<b>ERRO! </b>Não é possível homologar um cadastro inativo!</b>');
            return $this->redirect(['index']);
            }else{
            //Homologa o limite da modalidade
            $connection = Yii::$app->db;
            $connection->createCommand()
            ->update('modalidade_valorlimite', ['homologacao_usuario' => $session['sess_nomeusuario'], 'homologacao_data' => date('Y-m-d')], ['id' => $model->id])
            ->execute();

            Yii::$app->session->setFlash('success', '<b>SUCESSO!</b> Cadastro limite da Modalidade:<b> '.$model->modalidade->mod_descricao.'</b> e Ramo: <b>'.$model->ramo->ram_descricao .'</b> foi HOMOLOGADO!</b>');
        }
        return $this->redirect(['index']);
    }

    /**
     * Displays a single ModalidadeValorlimite model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ModalidadeValorlimite model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ModalidadeValorlimite();

        $modalidade = Modalidade::find()->where(['mod_status' => 1])->orderBy('mod_descricao')->all();
        $ano = Ano::find()->where(['an_status' => 1])->orderBy('an_ano')->all();
        $ramo = Ramo::find()->where(['ram_status' => 1])->orderBy('ram_descricao')->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'modalidade' => $modalidade,
            'ano' => $ano,
            'ramo' => $ramo,
        ]);
    }

    /**
     * Updates an existing ModalidadeValorlimite model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $modalidade = Modalidade::find()->where(['mod_status' => 1])->orderBy('mod_descricao')->all();
        $ano = Ano::find()->where(['an_status' => 1])->orderBy('an_ano')->all();
        $ramo = Ramo::find()->where(['ram_status' => 1])->orderBy('ram_descricao')->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->homologacao_usuario = NULL;
            $model->homologacao_data    = NULL;
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'modalidade' => $modalidade,
            'ano' => $ano,
            'ramo' => $ramo,
        ]);
    }

    /**
     * Deletes an existing ModalidadeValorlimite model.
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
     * Finds the ModalidadeValorlimite model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ModalidadeValorlimite the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ModalidadeValorlimite::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
