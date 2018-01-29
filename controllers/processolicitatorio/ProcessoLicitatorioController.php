<?php

namespace app\controllers\processolicitatorio;

use Yii;
use app\models\base\Modalidade;
use app\models\base\Ano;
use app\models\base\Ramo;
use app\models\base\ModalidadeValorlimite;
use app\models\base\Unidades;
use app\models\base\Artigo;
use app\models\base\Centrocusto;
use app\models\base\Recursos;
use app\models\base\Comprador;
use app\models\processolicitatorio\ProcessoLicitatorio;
use app\models\processolicitatorio\ProcessoLicitatorioSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProcessoLicitatorioController implements the CRUD actions for ProcessoLicitatorio model.
 */
class ProcessoLicitatorioController extends Controller
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
     * Lists all ProcessoLicitatorio models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProcessoLicitatorioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProcessoLicitatorio model.
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
     * Creates a new ProcessoLicitatorio model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $session = Yii::$app->session;

        $model = new ProcessoLicitatorio();

        $modalidade  = Modalidade::find()->where(['mod_status' => 1])->orderBy('mod_descricao')->all();
        $ano         = Ano::find()->where(['an_status' => 1])->orderBy('an_ano')->all();
        $ramo        = Ramo::find()->where(['ram_status' => 1])->orderBy('ram_descricao')->all();
        $destinos    = Unidades::find()->where(['uni_codsituacao' => 1])->orderBy('uni_nomeabreviado')->all();
        $valorlimite = ModalidadeValorlimite::find()->where(['status' => 1])->all();
        $artigo      = Artigo::find()->where(['art_status' => 1])->orderBy('art_descricao')->all();
        $centrocusto = Centrocusto::find()->where(['cen_codsituacao' => 1])->orderBy('cen_codano')->all();
        $recurso     = Recursos::find()->where(['rec_status' => 1])->orderBy('rec_descricao')->all();
        $comprador   = Comprador::find()->where(['comp_status' => 1])->orderBy('comp_descricao')->all();

        $model->prolic_datacriacao    = date('Y-m-d');
        $model->prolic_usuariocriacao = $session['sess_nomeusuario'];

        if ($model->load(Yii::$app->request->post())) {

            $model->prolic_destino = implode(", ",$model->prolic_destino);
            $model->prolic_centrocusto = implode(", ",$model->prolic_centrocusto);

        //Sequencia do cód. da modalidade de acordo com o tipo
        $query_id = ProcessoLicitatorio::find()->innerJoinWith('modalidadeValorlimite')->innerJoinWith('modalidadeValorlimite.modalidade')->where(['modalidade.id'=>$model->modalidadeValorlimite->modalidade_id])->all();
                foreach ($query_id as $value) {
                    $incremento = $value['id'];
                    $incremento++;
                }
             $model->prolic_sequenciamodal = $incremento;
             $model->save();

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'modalidade' => $modalidade,
            'ano' => $ano,
            'ramo' => $ramo,
            'destinos' => $destinos,
            'valorlimite' => $valorlimite,
            'artigo' => $artigo,
            'centrocusto' => $centrocusto,
            'recurso' => $recurso,
            'comprador' => $comprador,
        ]);
    }

    /**
     * Updates an existing ProcessoLicitatorio model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ProcessoLicitatorio model.
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
     * Finds the ProcessoLicitatorio model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProcessoLicitatorio the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProcessoLicitatorio::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
