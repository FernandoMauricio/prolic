<?php

namespace app\controllers\base;

use Yii;
use app\models\base\Modalidade;
use app\models\base\Ramo;
use app\models\base\ModalidadeValorlimite;
use app\models\base\ModalidadeValorlimiteSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
     * Lists all ModalidadeValorlimite models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ModalidadeValorlimiteSearch();
        $params = Yii::$app->request->queryParams;

        $status = Yii::$app->request->get('status', 1);
        $anoFiltro = Yii::$app->request->get('ano', 'corrente');

        $params['ModalidadeValorlimiteSearch']['status'] = $status;

        if ($status == 1 && $anoFiltro === 'anteriores') {
            $params['ModalidadeValorlimiteSearch']['ano_menor_que'] = date('Y');
        } elseif ($status == 1 && $anoFiltro === 'corrente') {
            $params['ModalidadeValorlimiteSearch']['ano'] = date('Y');
        }

        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'status' => $status,
            'anoFiltro' => $anoFiltro,
        ]);
    }


    public function actionHomologar($id)
    {
        //VERIFICA SE O COLABORADOR FAZ PARTE DA EQUIPE DE COMPRAS (GMA)
        $session = Yii::$app->session;
        if ($session['sess_codunidade'] != 6) {
            return $this->render('/site/acesso-negado');
        } else {

            $model = $this->findModel($id);

            if ($model->status == 0) { //Cadastros Inativados
                Yii::$app->session->setFlash('danger', '<b>ERRO! </b>Não é possível homologar um cadastro inativo!</b>');
                return $this->redirect(['index']);
            } else {
                //Homologa o limite da modalidade
                $connection = Yii::$app->db;
                $connection->createCommand()
                    ->update('modalidade_valorlimite', ['homologacao_usuario' => $session['sess_nomeusuario'], 'homologacao_data' => date('Y-m-d')], ['id' => $model->id])
                    ->execute();

                Yii::$app->session->setFlash('success', '<b>SUCESSO!</b> Cadastro limite da Modalidade:<b> ' . $model->modalidade->mod_descricao . '</b> e Segmento: <b>' . $model->ramo->ram_descricao . '</b> foi HOMOLOGADO!</b>');
            }
            return $this->redirect(['index']);
        }
    }

    /**
     * Displays a single ModalidadeValorlimite model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        //VERIFICA SE O COLABORADOR FAZ PARTE DA EQUIPE DE COMPRAS (GMA)
        $session = Yii::$app->session;
        if ($session['sess_codunidade'] != 6) {
            return $this->render('/site/acesso-negado');
        } else {

            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    public function actionToggleStatus()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        if (!$id) {
            return ['success' => false, 'error' => 'ID não informado'];
        }

        $model = $this->findModel($id);
        $wasInactive = $model->status == 0;
        $model->status = $model->status ? 0 : 1;

        // Se estava inativo e agora será ativado, revoga homologação
        if ($wasInactive && $model->status == 1) {
            $model->homologacao_usuario = null;
            $model->homologacao_data = null;
        }

        if ($model->save(false)) {
            return ['success' => true, 'status' => $model->status];
        }

        return ['success' => false, 'error' => 'Erro ao salvar'];
    }

    /**
     * Creates a new ModalidadeValorlimite model.
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

            $model = new ModalidadeValorlimite();

            $modalidade = Modalidade::find()->where(['mod_status' => 1])->orderBy('mod_descricao')->all();
            $ramo = Ramo::find()->where(['ram_status' => 1])->orderBy('ram_descricao')->all();

            $model->ano = date('Y');
            $model->status = 1; //Ativo

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $tipo = $model->verificarTipoModalidade();
                Yii::$app->session->setFlash('success', "Modalidade criada: <strong>$tipo</strong>");
                return $this->redirect(['index']);
            }

            return $this->render('create', [
                'model' => $model,
                'modalidade' => $modalidade,
                'ramo' => $ramo,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $modalidade = Modalidade::find()
            ->where(['mod_status' => 1])
            ->orderBy('mod_descricao')
            ->all();

        // Buscar todos os ramos (ativos + o do model)
        $ramoList = Ramo::find()
            ->where([
                'or',
                ['ram_status' => 1],
                ['id' => $model->ramo_id],
            ])
            ->orderBy('ram_descricao')
            ->all();

        // Construir array com label "[Inativo]" se necessário
        $ramoData = [];
        foreach ($ramoList as $r) {
            $label = $r->ram_status ? $r->ram_descricao : '[Inativo] ' . $r->ram_descricao;
            $ramoData[$r->id] = $label;
        }

        if ($model->load(Yii::$app->request->post())) {
            // Se o registro está ativo, e houve alteração, revoga a homologação
            if ($model->status == 1) {
                $model->homologacao_usuario = null;
                $model->homologacao_data = null;
            }
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Valor limite atualizado com sucesso!');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'modalidade' => $modalidade,
            'ramo' => $ramoData,
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
