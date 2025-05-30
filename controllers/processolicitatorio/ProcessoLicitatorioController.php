<?php

namespace app\controllers\processolicitatorio;

use app\components\helpers\DocumentoHelper;
use app\components\helpers\RbacHelper;
use app\controllers\mxm\ReqcompraRcoController;
use Yii;
use app\models\base\Ramo;
use app\models\base\ModalidadeValorlimite;
use app\models\base\Unidades;
use app\models\base\Artigo;
use app\models\base\Centrocusto;
use app\models\base\Recursos;
use app\models\base\Comprador;
use app\models\base\Situacao;
use app\models\base\Emailusuario;
use app\models\processolicitatorio\Observacoes;
use app\models\processolicitatorio\ProcessoLicitatorio;
use app\models\processolicitatorio\ProcessoLicitatorioSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use app\models\api\WebManagerService;
use yii\helpers\Url;
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
        $this->AccessAllow();
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'only' => ['create', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'matchCallback' => fn() => \app\components\helpers\RbacHelper::isAdmin(),
                    ],
                ],
                'denyCallback' => function () {
                    return Yii::$app->controller->redirect(['site/acesso-negado']);
                },
            ],
        ];
    }

    //Localiza os limites para a modalidade selecionada
    public function actionLimite()
    {
        $out = [];
        $selected = null;
        $req = Yii::$app->request;

        // os pais vêm normalmente em depdrop_parents
        $parents = $req->post('depdrop_parents', []);
        if (!empty($parents)) {
            $cat_id = $parents[0];
            // monta a lista de opções (id => nome)
            $out = ProcessoLicitatorio::getLimiteSubCat($cat_id);

            // depdrop_params[0] agora é o processo-id
            $params = $req->post('depdrop_params', []);
            if (!empty($params[0])) {
                $processoId = (int)$params[0];
                $modelProc  = ProcessoLicitatorio::findOne($processoId);
                if ($modelProc) {
                    // devolve o próprio valor salvo para pré-seleção
                    $selected = $modelProc->modalidade_valorlimite_id;
                }
            }
        }

        return $this->asJson([
            'output'   => $out,
            'selected' => $selected,
        ]);
    }


    // Localiza os dados dos Limites
    public function actionGetLimite($limiteId)
    {
        $getLimite = ModalidadeValorlimite::findOne($limiteId);

        if ($getLimite) {
            // Recalcula o valor_saldo baseado em outros valores
            $valorSaldo = $getLimite->valor_limite - $getLimite->valor_limite_apurado;

            // Se você tiver outros valores que afetam o saldo, ajuste aqui.
            // Por exemplo, adicionar o valor de um valor estimado:
            // $valorSaldo -= $getLimite->valor_estimado;

            // Converte o modelo em um array e depois em JSON
            return Json::encode([
                'valor_limite' => $getLimite->valor_limite,
                'valor_limite_apurado' => $getLimite->valor_limite_apurado,
                'valor_saldo' => $valorSaldo,  // Retorna o valor recalculado
            ]);
        } else {
            // Retorna um erro caso não encontre o limite
            return Json::encode(['error' => 'Limite não encontrado']);
        }
    }

    //Localiza a somatório dos Limites
    public function actionGetSumLimite($limiteId, $processo)
    {
        $getSumLimite = ProcessoLicitatorio::getSumLimite($limiteId, $processo);

        return \yii\helpers\Json::encode($getSumLimite);
    }

    public function actionObservacoes($id)
    {
        $session = Yii::$app->session;

        //VERIFICA SE O COLABORADOR FAZ PARTE DA EQUIPE DE COMPRAS (GMA)
        RbacHelper::ensureAdminAccess();

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
        $searchModel = new ProcessoLicitatorioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = ['defaultOrder' => ['id' => SORT_DESC]];

        return $this->render('consulta-processos-licitatorios', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all ProcessoLicitatorio models.
     * @return mixed
     */
    public function actionIndex()
    {
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
        $model = $this->findModel($id);
        $requisicoes = [];
        $naoEncontradas = [];

        foreach ($model->requisicoesCodmxm as $numero) {
            $requisicao = ReqcompraRcoController::carregarRequisicaoPorNumero($numero);

            if ($requisicao !== null) {
                $requisicoes[] = $requisicao;
            } else {
                $naoEncontradas[] = $numero;
            }
        }
        return $this->render('view', [
            'model' => $model,
            'requisicoes' => $requisicoes,
            'faltando' => $naoEncontradas,
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
        Yii::$app->response->format = Response::FORMAT_JSON;

        $meuId = Yii::$app->request->get('id');

        // Sanitiza o número (previne injeção acidental de ; ou espaços)
        $numeroSanitizado = trim($numeroRequisicao);

        if (!$numeroSanitizado) {
            return ['success' => false, 'mensagem' => 'Número de requisição inválido.'];
        }

        // Verifica se já está em uso por outro processo
        $query = ProcessoLicitatorio::find()
            ->andWhere(['IS NOT', 'prolic_codmxm', null])
            ->andWhere(['like', new \yii\db\Expression("CONCAT(';', prolic_codmxm, ';')"), ";{$numeroSanitizado};"]);

        if (!empty($meuId)) {
            $query->andWhere(['!=', 'id', (int)$meuId]);
        }

        if ($query->exists()) {
            return [
                'success' => false,
                'jaUtilizada' => true,
                'mensagem' => "A requisição {$numeroSanitizado} já está vinculada a outro processo.",
            ];
        }

        try {
            $dados = WebManagerService::consultarPedidoRequisicao($codigoEmpresa, $numeroSanitizado);
        } catch (\Throwable $e) {
            Yii::error("Erro ao consultar a API da MXM: " . $e->getMessage(), __METHOD__);
            $dados = null;
        }

        if (!empty($dados)) {
            $html = $this->renderAjax('form/_requisicao-preview', ['dados' => $dados]);
            header('Content-Type: application/json; charset=UTF-8');
            return ['success' => true, 'html' => $html, 'numeroRequisicao' => $numeroSanitizado, 'encontrada' => true];
        }

        $html = $this->renderAjax('form/_requisicao-preview', ['dados' => null, 'numero' => $numeroSanitizado]);
        header('Content-Type: application/json; charset=UTF-8');
        return ['success' => true, 'html' => $html, 'numeroRequisicao' => $numeroSanitizado, 'encontrada' => false];
    }

    public function actionBuscarRequisicaoOpcao($term = '')
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (strlen($term) < 5) {
            return ['results' => []];
        }

        try {
            $dados = WebManagerService::consultarPedidoRequisicao('02', $term);
        } catch (\Throwable $e) {
            Yii::error("Erro ao buscar requisição para autocomplete: " . $e->getMessage(), __METHOD__);
            return [
                'results' => [[
                    'id' => $term,
                    'text' => $term . ' - (erro ao consultar API)'
                ]]
            ];
        }

        if (!empty($dados)) {
            return [
                'results' => [[
                    'id' => $term,
                    'text' => $term . ' - ' . ($dados['requisitante'] ?? 'Desconhecido')
                ]]
            ];
        }

        return [
            'results' => [[
                'id' => $term,
                'text' => $term . ' - Requisição não encontrada'
            ]]
        ];
    }

    /**
     * Cria um novo ProcessoLicitatorio apenas com Modalidade e Ramo,
     * gera codprocesso e sequência, e então redireciona para editar tudo.
     */
    public function actionCreate()
    {
        $session = Yii::$app->session;

        $model = new ProcessoLicitatorio();
        $dadosAuxiliares = $this->carregarDadosAuxiliares();

        $model->prolic_datacriacao    = date('Y-m-d');
        $model->prolic_usuariocriacao = $session['sess_nomeusuario'];
        $model->situacao_id = 1; // Em elaboração
        $model->ano = date('Y'); // ano corrente
        $model->prolic_valorestimado = 0;
        $model->prolic_valorefetivo = 0;

        if ($model->load(Yii::$app->request->post())) {
            $ultimo = ProcessoLicitatorio::find()
                ->andWhere(['processo_licitatorio.ano' => date('Y')])
                ->max('prolic_codprocesso');
            $model->prolic_codprocesso = ((int)$ultimo) + 1;

            if (isset($model->modalidadeValorlimite->modalidade_id)) {
                $cont = ProcessoLicitatorio::find()
                    ->innerJoinWith(['modalidadeValorlimite', 'modalidadeValorlimite.modalidade'])
                    ->where([
                        'modalidade.id' => $model->modalidadeValorlimite->modalidade_id,
                        'processo_licitatorio.ano' => date('Y'),
                    ])
                    ->count('prolic_sequenciamodal');
                $model->prolic_sequenciamodal = $cont + 1;
            }

            $model->save(false);

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['redirect' => Url::to(['update', 'id' => $model->id])];
            }

            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->renderAjax('create', array_merge(
            ['model' => $model],
            $dadosAuxiliares
        ));
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

        $model = $this->findModel($id);

        // Se prolic_codmxm for uma string, converta para array
        if (is_string($model->prolic_codmxm)) {
            $model->prolic_codmxm = explode(';', $model->prolic_codmxm);
        }

        if (is_string($model->prolic_empresa)) {
            $model->prolic_empresa = explode(';', $model->prolic_empresa);
        }

        // Carregamento dos dados auxiliares da view
        $dadosAuxiliares = $this->carregarDadosAuxiliares();

        // Atualiza automaticamente as empresas via helper, apenas em requisições GET
        if (Yii::$app->request->isGet) {
            $model->prolic_empresa = DocumentoHelper::formatarListaEmpresas((array) $model->prolic_empresa);
        }

        // Pré-processamento dos campos múltiplos
        $model->prolic_dataatualizacao = date('Y-m-d');
        $model->prolic_usuarioatualizacao = $session['sess_nomeusuario'];
        $model->prolic_destino = array_map('trim', explode(',', $model->prolic_destino));
        $model->prolic_centrocusto = array_map('trim', explode(',', $model->prolic_centrocusto));
        $model->prolic_empresa = is_array($model->prolic_empresa) ? array_map('trim', $model->prolic_empresa) : (is_string($model->prolic_empresa) ? array_map('trim', explode(';', $model->prolic_empresa)) : []);
        $model->prolic_codmxm = is_array($model->prolic_codmxm) ? array_map('trim', $model->prolic_codmxm) : (is_string($model->prolic_codmxm) ? array_map('trim', explode(';', $model->prolic_codmxm)) : []);

        if ($model->load(Yii::$app->request->post())) {
            $this->ajustarSequenciaModalidade($model);

            // Ao salvar, converte os arrays de volta para strings
            $model->prolic_destino = implode(',', $model->prolic_destino);
            $model->prolic_centrocusto = implode(',', $model->prolic_centrocusto);
            $model->prolic_codmxm = implode(';', array_filter(array_map('trim', (array) $model->prolic_codmxm), fn($v) => $v !== ''));
            $model->prolic_codmxm = $this->formatarRequisicoesParaSalvar($model->prolic_codmxm);
            $model->prolic_empresa = $this->formatarEmpresasParaSalvar($model->prolic_empresa);

            /**
             * EXCEÇÃO DE VALIDAÇÃO POR ARTIGO
             * Se o artigo selecionado tiver tipo com "Situação", libera o valor
             */
            if ($model->artigo && stripos($model->artigo->art_tipo, 'Situação') !== false) {
                // Caso queira desconsiderar limite legal:
                $model->valor_limite = 999999999.99; // ou qualquer valor que represente "sem limite"
            }

            if ($model->validate()) {
                $model->save();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        if (is_string($model->prolic_codmxm)) {
            $model->prolic_codmxm = explode(';', $model->prolic_codmxm);
        }

        $model->prolic_codmxm = $model->getRequisicoesCodmxm();
        $requisicoes = [];
        foreach ((array) $model->getRequisicoesCodmxm() as $numero) {
            $requisicao = ReqcompraRcoController::carregarRequisicaoPorNumero(trim($numero));
            if ($requisicao !== null) {
                $requisicoes[] = $requisicao;
            }
        }

        return $this->render('update', array_merge(
            [
                'model' => $model,
                'requisicoes' => $requisicoes,
            ],
            $dadosAuxiliares
        ));
    }

    private function formatarRequisicoesParaSalvar($lista): string
    {
        $itens = is_array($lista) ? $lista : explode(';', (string) $lista);

        $filtrados = array_filter(array_map('trim', $itens), fn($v) => $v !== '');

        return ';' . implode(';', $filtrados) . ';';
    }

    private function carregarDadosAuxiliares($model = null)
    {
        $ramoId = $model ? $model->ramo_id : null;
        $artigoId = $model ? $model->artigo_id : null;
        $valorLimiteId = $model ? $model->modalidade_valorlimite_id : null;

        return [
            'ramo' => Ramo::find()
                ->where([
                    'or',
                    ['ram_status' => 1],
                    ['id' => $ramoId]
                ])
                ->orderBy(['ram_descricao' => SORT_DESC])
                ->all(),

            'destinos' => Unidades::find()
                ->where(['uni_codsituacao' => 1])
                ->orderBy(['uni_nomeabreviado' => SORT_DESC])
                ->all(),

            'valorlimite' => ModalidadeValorlimite::find()
                ->innerJoinWith('modalidade')
                ->where([
                    'or',
                    [
                        'and',
                        ['mod_status' => 1],
                        ['!=', 'tipo_modalidade', ''],
                        ['!=', 'homologacao_usuario', ''],
                    ],
                    ['modalidade_valorlimite.id' => $valorLimiteId]
                ])
                ->orderBy(['modalidade.mod_descricao' => SORT_DESC])
                ->all(),

            'artigo' => Artigo::find()
                ->select(['id', 'art_descricao', 'art_tipo'])
                ->where([
                    'or',
                    [
                        'and',
                        ['art_status' => 1],
                        ['!=', 'art_homologacaousuario', ''],
                    ],
                    ['id' => $artigoId]
                ])
                ->orderBy(['art_descricao' => SORT_DESC])
                ->all(),

            'centrocusto' => Centrocusto::find()
                ->where(['cen_codsituacao' => 1])
                ->orderBy(['cen_codano' => SORT_DESC])
                ->all(),

            'recurso' => Recursos::find()
                ->where(['rec_status' => 1])
                ->orderBy(['rec_descricao' => SORT_DESC])
                ->all(),

            'comprador' => Comprador::find()
                ->where(['comp_status' => 1])
                ->orderBy(['comp_descricao' => SORT_DESC])
                ->all(),

            'situacao' => Situacao::find()
                ->where(['sit_status' => 1])
                ->orderBy(['sit_descricao' => SORT_DESC])
                ->all(),
        ];
    }


    private function ajustarSequenciaModalidade($model)
    {
        $incremento = ProcessoLicitatorio::find()
            ->innerJoinWith('modalidadeValorlimite')
            ->innerJoinWith('modalidadeValorlimite.modalidade')
            ->where([
                'modalidade.id' => $model->modalidadeValorlimite->modalidade_id,
                'processo_licitatorio.ano' => date('Y'),
            ])->count();

        if ($model->modalidade != $_POST['ProcessoLicitatorio']['modalidade']) {
            $model->prolic_sequenciamodal = $incremento + 1;
        }
    }

    private function formatarEmpresasParaSalvar($documentos): string
    {
        $documentos = is_array($documentos) ? $documentos : (empty($documentos) ? [] : explode(';', $documentos));

        $formatados = [];

        foreach ($documentos as $documento) {
            $docLimpo = preg_replace('/\D/', '', $documento);
            if (!$docLimpo) {
                continue;
            }

            $dados = WebManagerService::consultarFornecedor($docLimpo);

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

        return implode(';', $formatados);
    }

    public function formatarEmpresas(array $documentos)
    {
        $resultado = [];
        foreach ($documentos as $doc) {
            $dados = WebManagerService::consultarFornecedor($doc);
            $docLimpo = preg_replace('/\D/', '', $doc);

            // Formatar CPF/CNPJ para o formato correto
            if (strlen($docLimpo) === 14) {
                $docFormatado = preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "$1.$2.$3/$4-$5", $docLimpo);
            } elseif (strlen($docLimpo) === 11) {
                $docFormatado = preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $docLimpo);
            } else {
                continue;
            }

            $razao = trim($dados['razaoSocial'] ?? '');
            if ($razao) {
                $resultado[$docLimpo] = [
                    'id' => $docLimpo,   // O CPF/CNPJ como id
                    'text' => "$docFormatado - $razao" // O texto completo com o CPF/CNPJ e a razão social
                ];
            }
        }
        return $resultado;
    }

    public function actionBuscarArtigoTipo($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $artigo = Artigo::findOne((int) $id);

        if ($artigo) {
            return [
                'success' => true,
                'descricao' => $artigo->art_descricao,
                'tipo' => $artigo->art_tipo,
            ];
        }

        return ['success' => false, 'mensagem' => 'Artigo não encontrado.'];
    }

    public function actionRequisicoesAjax($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        try {
            $model = $this->findModel($id);
            $requisicoes = [];
            $faltando = [];

            foreach ($model->getRequisicoesCodmxm() as $numero) {
                $req = \app\controllers\mxm\ReqcompraRcoController::carregarRequisicaoPorNumero($numero);
                if ($req !== null) {
                    $requisicoes[] = $req;
                } else {
                    $faltando[] = $numero;
                }
            }

            $html = $this->renderPartial('_accordion-requisicoes', [
                'requisicoes' => $requisicoes,
                'faltando' => $faltando,
            ]);

            return ['html' => $html];
        } catch (\Throwable $e) {
            Yii::error("Erro ao carregar requisições: " . $e->getMessage(), __METHOD__);
            return ['html' => '<div class="alert alert-danger">Erro ao carregar requisições vinculadas.</div>'];
        }
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
            !isset($session['sess_codusuario']) ||
            !isset($session['sess_codcolaborador']) ||
            !isset($session['sess_codunidade']) ||
            !isset($session['sess_nomeusuario']) ||
            !isset($session['sess_coddepartamento']) ||
            !isset($session['sess_codcargo']) ||
            !isset($session['sess_cargo']) ||
            !isset($session['sess_setor']) ||
            !isset($session['sess_unidade']) ||
            !isset($session['sess_responsavelsetor'])
        ) {
            return $this->redirect('https://portalsenac.am.senac.br');
        }
    }
}
