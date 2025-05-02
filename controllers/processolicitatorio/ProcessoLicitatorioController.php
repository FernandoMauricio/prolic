<?php

namespace app\controllers\processolicitatorio;

use Yii;
use app\models\base\Ano;
use app\models\base\Ramo;
use app\models\base\ModalidadeValorlimite;
use app\models\base\Unidades;
use app\models\base\Artigo;
use app\models\base\Centrocusto;
use app\models\base\Recursos;
use app\models\base\Comprador;
use app\models\base\Situacao;
use app\models\base\Empresa;
use app\models\base\Emailusuario;
use app\models\processolicitatorio\Observacoes;
use app\models\processolicitatorio\ProcessoLicitatorio;
use app\models\processolicitatorio\ProcessoLicitatorioSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use app\models\api\WebManagerService;
use yii\web\Response;

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

    //Localiza os limites para a modalidade selecionada
    public function actionLimite()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $cat_id = $parents[0];
                $param1 = null;
                $param2 = null;
                if (!empty($_POST['depdrop_params'])) {
                    $params = $_POST['depdrop_params'];
                    $param1 = $params[0]; // get the value of input-type-1
                    $param2 = $params[1]; // get the value of input-type-2
                }

                $out = ProcessoLicitatorio::getLimiteSubCat($cat_id, $param1, $param2);

                $selected = ProcessoLicitatorio::getSumLimite($cat_id, $param1);
                // the getDefaultSubCat function will query the database
                // and return the default sub cat for the cat_id

                echo Json::encode(['output' => $out, 'selected' => $selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }

    //Localiza os dados dos Limites
    public function actionGetLimite($limiteId)
    {
        $getLimite = ModalidadeValorlimite::findOne($limiteId);
        echo Json::encode($getLimite);
    }

    //Localiza a somatório dos Limites
    public function actionGetSumLimite($limiteId, $processo)
    {
        $getSumLimite = ProcessoLicitatorio::getSumLimite($limiteId, $processo);
        echo Json::encode($getSumLimite);
    }

    public function actionObservacoes($id)
    {
        $session = Yii::$app->session;

        //VERIFICA SE O COLABORADOR FAZ PARTE DA EQUIPE DE COMPRAS (GMA)
        $session = Yii::$app->session;
        if ($session['sess_codunidade'] != 6) {
            return $this->AccessoAdministrador();
        }

        $model = new Observacoes();
        $processolicitatorio = $this->findModel($id);

        $model->processo_licitatorio_id = $processolicitatorio->id;
        $model->obs_datacriacao         = date('Y-m-d');
        $model->obs_usuariocriacao      = $session['sess_nomeusuario'];

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('info', '<b>SUCESSO! </b> Observação inserida!</b>');
            return $this->redirect(['view', 'id' => $processolicitatorio->id]);
        }
        return $this->renderAjax('observacoes/create', [
            'model' => $model,
            'processolicitatorio' => $processolicitatorio,
        ]);
    }

    public function actionConsultaProcessosLicitatorios()
    {
        $session = Yii::$app->session;
        if ($session['sess_responsavelsetor'] == 0) { //Verifica se o colaborador é gerente
            return $this->AccessoAdministrador();
        }
        $this->layout = 'main-full';
        $searchModel = new ProcessoLicitatorioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = ['defaultOrder' => ['id' => SORT_DESC]];

        return $this->render('consulta-processos-licitatorios', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionViewGerencia($id)
    {
        $session = Yii::$app->session;
        if ($session['sess_responsavelsetor'] == 0) { //Verifica se o colaborador é gerente
            return $this->AccessoAdministrador();
        }
        $model = $this->findModel($id);

        return $this->render('view-gerencia', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Lists all ProcessoLicitatorio models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->layout = 'main-full';

        $searchModel = new ProcessoLicitatorioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = ['defaultOrder' => ['id' => SORT_DESC]];

        if (Yii::$app->request->post('hasEditable')) {
            // instantiate your ProcessoLicitatorio model for saving
            $processoLicitatorio = Yii::$app->request->post('editableKey');
            $model = ProcessoLicitatorio::findOne($processoLicitatorio);

            // store a default json response as desired by editable
            $out = Json::encode(['output' => '', 'message' => '']);

            $posted = current($_POST['ProcessoLicitatorio']);
            $post = ['ProcessoLicitatorio' => $posted];

            // load model like any single model validation
            if ($model->load($post)) {
                // can save model or do something before saving model
                $model->save(false);
                $output = '';
                $out = Json::encode(['output' => $output, 'message' => '']);
            }
            // return ajax json encoded response and exit
            echo $out;

            //ENVIANDO EMAIL PARA O GERENTE INFORMANDO SOBRE O PROCESSO
            $sql_email = "SELECT emus_email FROM emailusuario_emus, colaborador_col, responsavelambiente_ream WHERE ream_codunidade IN('$model->prolic_destino') AND ream_codcolaborador = col_codcolaborador AND col_codusuario = emus_codusuario";

            $email_solicitacao = Emailusuario::findBySql($sql_email)->all();
            foreach ($email_solicitacao as $email) {
                Yii::$app->mailer->compose()
                    ->setFrom(['no-reply@am.senac.br' => 'Gerência de Material'])
                    ->setTo($email['emus_email'])
                    ->setSubject('Processo Licitatório ' . $model->id . ' - ' . $model->situacao->sit_descricao)
                    ->setTextBody('Processo Licitatório: ' . $model->id . ' está com a situação ' . $model->situacao->sit_descricao . ' ')
                    ->setHtmlBody('
                       <p> Prezado(a) Gerente,</p>
                       <p> Existe um Processo Licitatório de <b>código: ' . $model->id . '</b> com a situação ' . $model->situacao->sit_descricao . '.</p>
                       <p> Por favor, não responda este e-mail. Acesse https://portalsenac.am.senac.br para analisar o Processo Licitatório.</p>
                       <p> Atenciosamente, <br> Gerência de Material - Senac AM.</p>
                       ')
                    ->send();
            }
            Yii::$app->session->setFlash('info', '<b>SUCESSO!</b> Processo Licitatório alterado para <b>' . $model->situacao->sit_descricao . '!</b>');
            return $this->redirect(['index']);
        }

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
        //VERIFICA SE O COLABORADOR FAZ PARTE DA EQUIPE DE COMPRAS (GMA)
        $session = Yii::$app->session;
        if ($session['sess_codunidade'] != 6) {
            return $this->AccessoAdministrador();
        }
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionView2($id)
    {
        //VERIFICA SE O COLABORADOR FAZ PARTE DA EQUIPE DE COMPRAS (GMA)
        $session = Yii::$app->session;
        if ($session['sess_codunidade'] != 6) {
            return $this->AccessoAdministrador();
        }
        $model = $this->findModel($id);

        return $this->render('view2', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ProcessoLicitatorio model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionGerarProcessoLicitatorio()
    {
        //VERIFICA SE O COLABORADOR FAZ PARTE DA EQUIPE DE COMPRAS (GMA)
        $session = Yii::$app->session;
        if ($session['sess_codunidade'] != 6) {
            return $this->AccessoAdministrador();
        }

        $model = new ProcessoLicitatorio();

        $ano         = Ano::find()->where(['an_status' => 1])->orderBy('an_ano')->all();
        $ramo        = Ramo::find()->where(['ram_status' => 1])->orderBy('ram_descricao')->all();
        $destinos    = Unidades::find()->where(['uni_codsituacao' => 1])->orderBy('uni_nomeabreviado')->all();
        //$valorlimite = Modalidade::find()->where(['mod_status' => 1])->all();
        $valorlimite = ModalidadeValorlimite::find()->innerJoinWith('modalidade')->where(['mod_status' => 1])->andWhere(['!=', 'homologacao_usuario', ''])->all();
        $artigo      = Artigo::find()->select(['id, CONCAT("(",art_tipo,")", " - ", art_descricao) AS art_descricao'])->andWhere(['!=', 'art_homologacaousuario', ''])->orderBy('art_descricao')->all();
        $centrocusto = Centrocusto::find()->where(['cen_codsituacao' => 1])->orderBy('cen_codano')->all();
        $recurso     = Recursos::find()->where(['rec_status' => 1])->orderBy('rec_descricao')->all();
        $comprador   = Comprador::find()->where(['comp_status' => 1])->orderBy('comp_descricao')->all();
        $situacao    = Situacao::find()->where(['sit_status' => 1])->orderBy('sit_descricao')->all();
        $empresa     = Empresa::find()->where(['emp_status' => 1])->orderBy('emp_descricao')->all();

        $model->prolic_datacriacao    = date('Y-m-d');
        $model->prolic_usuariocriacao = $session['sess_nomeusuario'];

        if ($model->load(Yii::$app->request->post())) {

            //Somatória dos valores
            // $model->prolic_valorefetivo = $model->prolic_valorestimado + $model->prolic_valoraditivo;

            //Junta todos destinos, centros de custos e empresas em uma linha
            is_array($model->prolic_destino) ? $model->prolic_destino = implode(', ', $model->prolic_destino) : null;
            is_array($model->prolic_centrocusto) ? $model->prolic_centrocusto = implode(', ', $model->prolic_centrocusto) : null;
            is_array($model->prolic_empresa) ? $model->prolic_empresa = implode(', ', $model->prolic_empresa) : null;

            //Número do Processo
            $numeroProcesso = ProcessoLicitatorio::find()->innerJoinWith('ano')->where(['ano.an_ano' => date('Y')])->max('prolic_codprocesso');
            $model->prolic_codprocesso = $numeroProcesso + 1;

            //Sequencia do cód. da modalidade de acordo com o tipo
            if (isset($model->modalidadeValorlimite->modalidade_id)) {
                $incremento = ProcessoLicitatorio::find()->innerJoinWith('modalidadeValorlimite')->innerJoinWith('modalidadeValorlimite.modalidade')->innerJoinWith('ano')->where(['modalidade.id' => $model->modalidadeValorlimite->modalidade_id, 'ano.an_ano' => date('Y')])->count('prolic_sequenciamodal');
                $model->prolic_sequenciamodal = $incremento + 1;
            }
            if ($model->validate()) {
                $model->save();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->renderAjax('gerar-processo-licitatorio', [
            'model' => $model,
            'ano' => $ano,
            'ramo' => $ramo,
            'destinos' => $destinos,
            'valorlimite' => $valorlimite,
            'artigo' => $artigo,
            'centrocusto' => $centrocusto,
            'recurso' => $recurso,
            'comprador' => $comprador,
            'situacao' => $situacao,
            'empresa' => $empresa,
        ]);
    }

    public function actionBuscarFornecedor($q)
    {
        $dados = WebManagerService::consultarFornecedor($q);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $documento = trim($dados['documento'] ?? $q);
        $razao = trim($dados['razaoSocial'] ?? 'Fornecedor não encontrado');

        if (strlen($documento) === 11) {
            $documentoFormatado = preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $documento);
        } elseif (strlen($documento) === 14) {
            $documentoFormatado = preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "$1.$2.$3/$4-$5", $documento);
        } else {
            $documentoFormatado = $documento;
        }

        return [[
            'id' => $documento,
            'text' => $documentoFormatado . ' - ' . $razao,
        ]];
    }

    public function actionBuscarRequisicao($codigoEmpresa, $numeroRequisicao)
    {
        $dados = WebManagerService::consultarPedidoRequisicao($codigoEmpresa, $numeroRequisicao);

        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!empty($dados)) {
            $html = $this->renderPartial('_requisicao-preview', ['dados' => $dados]);
            return ['success' => true, 'html' => $html, 'numeroRequisicao' => $numeroRequisicao];
        }

        return ['success' => false];
    }

    public function actionBuscarRequisicaoOpcao($term = '')
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (strlen($term) < 5) {
            return ['results' => []];
        }

        $dados = WebManagerService::consultarPedidoRequisicao('02', $term);

        if (!empty($dados)) {
            return [
                'results' => [[
                    'id' => $term,
                    'text' => $term . ' - ' . ($dados['requisitante'] ?? 'Desconhecido')
                ]]
            ];
        }

        return ['results' => []];
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
        $session = Yii::$app->session;
        if ($session['sess_codunidade'] != 6) {
            return $this->AccessoAdministrador();
        }

        $model = $this->findModel($id);

        // Carregamento dos dados auxiliares da view
        $dadosAuxiliares = $this->carregarDadosAuxiliares();

        // Pré-processamento dos campos múltiplos
        $model->prolic_dataatualizacao = date('Y-m-d');
        $model->prolic_usuarioatualizacao = $session['sess_nomeusuario'];
        $model->prolic_destino = array_map('trim', explode(',', $model->prolic_destino));
        $model->prolic_centrocusto = array_map('trim', explode(',', $model->prolic_centrocusto));
        $model->prolic_empresa = array_map('trim', explode(',', $model->prolic_empresa));
        $model->prolic_codmxm = array_map('trim', explode(',', $model->prolic_codmxm));

        if ($model->load(Yii::$app->request->post())) {
            $this->ajustarSequenciaModalidade($model);

            $model->prolic_destino = implode(',', $model->prolic_destino);
            $model->prolic_centrocusto = implode(',', $model->prolic_centrocusto);
            $model->prolic_empresa = $this->formatarEmpresasParaSalvar($model->prolic_empresa);
            $model->prolic_codmxm = implode(';', $model->prolic_codmxm);

            if ($model->validate()) {
                $model->save();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $empresasFormatadas = $this->formatarEmpresas($model->prolic_empresa);

        return $this->render('update', array_merge(
            [
                'model' => $model,
                'empresasFormatadas' => $empresasFormatadas,
            ],
            $dadosAuxiliares
        ));
    }

    private function carregarDadosAuxiliares()
    {
        return [
            'ano' => Ano::find()->where(['an_status' => 1])->orderBy('an_ano')->all(),
            'ramo' => Ramo::find()->where(['ram_status' => 1])->orderBy('ram_descricao')->all(),
            'destinos' => Unidades::find()->where(['uni_codsituacao' => 1])->orderBy('uni_nomeabreviado')->all(),
            'valorlimite' => ModalidadeValorlimite::find()->innerJoinWith('modalidade')->where(['mod_status' => 1])->andWhere(['!=', 'homologacao_usuario', ''])->all(),
            'artigo' => Artigo::find()->select(['id, CONCAT("(",art_tipo,")", " - ", art_descricao) AS art_descricao'])->andWhere(['!=', 'art_homologacaousuario', ''])->orderBy('art_descricao')->all(),
            'centrocusto' => Centrocusto::find()->where(['cen_codsituacao' => 1])->orderBy('cen_codano')->all(),
            'recurso' => Recursos::find()->where(['rec_status' => 1])->orderBy('rec_descricao')->all(),
            'comprador' => Comprador::find()->where(['comp_status' => 1])->orderBy('comp_descricao')->all(),
            'situacao' => Situacao::find()->where(['sit_status' => 1])->orderBy('sit_descricao')->all(),
        ];
    }

    private function ajustarSequenciaModalidade($model)
    {
        $incremento = ProcessoLicitatorio::find()
            ->innerJoinWith('modalidadeValorlimite')
            ->innerJoinWith('modalidadeValorlimite.modalidade')
            ->innerJoinWith('ano')
            ->where([
                'modalidade.id' => $model->modalidadeValorlimite->modalidade_id,
                'ano.an_ano' => date('Y')
            ])->count();

        if ($model->modalidade != $_POST['ProcessoLicitatorio']['modalidade']) {
            $model->prolic_sequenciamodal = $incremento + 1;
        }
    }

    private function formatarEmpresasParaSalvar(array $documentos): string
    {
        $formatados = [];

        foreach ($documentos as $documento) {
            $dados = WebManagerService::consultarFornecedor($documento);
            $docLimpo = preg_replace('/\D/', '', $documento);

            if (strlen($docLimpo) === 14) {
                $docFormatado = preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "$1.$2.$3/$4-$5", $docLimpo);
            } elseif (strlen($docLimpo) === 11) {
                $docFormatado = preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $docLimpo);
            } else {
                continue;
            }

            $razao = trim($dados['razaoSocial'] ?? '');
            if (!$razao) {
                continue;
            }

            $formatados[] = "$docFormatado - $razao";
        }

        return implode(', ', $formatados);
    }

    private function formatarEmpresas(array $documentos)
    {
        $resultado = [];
        foreach ($documentos as $doc) {
            $dados = WebManagerService::consultarFornecedor($doc);
            $docLimpo = preg_replace('/\D/', '', $doc);

            if (strlen($docLimpo) === 14) {
                $docFormatado = preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "$1.$2.$3/$4-$5", $docLimpo);
            } elseif (strlen($docLimpo) === 11) {
                $docFormatado = preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $docLimpo);
            } else {
                continue;
            }

            $razao = trim($dados['razaoSocial'] ?? '');
            if ($razao) {
                $resultado[$docLimpo] = $docFormatado . ' - ' . $razao;
            }
        }
        return $resultado;
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

    public function AccessoAdministrador()
    {
        return $this->render('/site/acesso-negado');
    }
}
