<?php

namespace app\controllers\processolicitatorio;

use Yii;
use yii\web\Controller;
use app\models\services\DashboardService;
use app\models\FiltroDashboardForm;
use app\models\processolicitatorio\ProcessoLicitatorio;

class DashboardController extends Controller
{
    public function actionIndex()
    {
        $filtroModel = new FiltroDashboardForm();
        $filtroModel->load(Yii::$app->request->get());

        return $this->render('index', [
            'filtroModel'            => $filtroModel,
            'kpi'                    => DashboardService::getKpis($filtroModel),
            'modalidades'            => DashboardService::getDistribuicaoPorModalidade($filtroModel),
            'situacoes'              => DashboardService::getDistribuicaoPorSituacao($filtroModel),
            'topCompradores'         => DashboardService::getTopCompradores($filtroModel),
            'alertas'                => DashboardService::getAlertas($filtroModel),
            'distribuicaoMensal'     => DashboardService::getDistribuicaoMensal($filtroModel),
            'anosDisponiveis'        => DashboardService::getAnosDisponiveis(),
            'topUnidadesAtendidas'   => DashboardService::getTopUnidadesAtendidas($filtroModel),
            'maioresRequisicoes'     => DashboardService::getMaioresRequisicoes($filtroModel),
            'compradoresSituacao'    => DashboardService::getCompradoresSituacao($filtroModel),
        ]);
    }

    public function actionDetalhesUnidade($codigo, $ano = null, $mes = null)
    {
        $query = ProcessoLicitatorio::find()
            ->alias('p')
            ->with(['modalidadeValorlimite.modalidade', 'modalidadeValorlimite.ramo', 'recursos'])
            ->where(new \yii\db\Expression('FIND_IN_SET(:codigo, p.prolic_destino) > 0'), [':codigo' => $codigo]);

        if ($ano) {
            $query->andWhere(['YEAR(p.prolic_dataprocesso)' => $ano]);
        }

        if ($mes) {
            $query->andWhere(['MONTH(p.prolic_dataprocesso)' => $mes]);
        }

        $processos = $query->all();

        $model = new \app\models\processolicitatorio\ProcessoLicitatorio();
        $nome = $model->getUnidades($codigo);

        return $this->renderPartial('detalhes-unidade', [
            'codigo' => $codigo,
            'nome' => $nome,
            'processos' => $processos,
        ]);
    }

    public function actionDetalhesRequisicao($codigo)
    {
        $processos = ProcessoLicitatorio::find()
            ->where(['id' => $codigo])
            ->all();

        return $this->renderPartial('detalhes-processos', compact('processos'));
    }

    public function actionDetalhesComprador($id, $situacao, $ano = null, $mes = null)
    {
        $query = ProcessoLicitatorio::find()
            ->alias('p')
            ->joinWith(['comprador c', 'situacao s', 'modalidadeValorlimite.modalidade', 'modalidadeValorlimite.ramo', 'recursos'])
            ->where(['p.comprador_id' => $id])
            ->andWhere(['s.sit_descricao' => $situacao]);

        if ($ano) {
            $query->andWhere(new \yii\db\Expression('YEAR(p.prolic_dataprocesso) = :ano', [':ano' => $ano]))
                ->addParams([':ano' => $ano]);
        }

        if ($mes) {
            $query->andWhere(new \yii\db\Expression('MONTH(p.prolic_dataprocesso) = :mes', [':mes' => $mes]))
                ->addParams([':mes' => $mes]);
        }

        $processos = $query->all();

        $compradorNome = ($processos && $processos[0]->comprador) ? $processos[0]->comprador->comp_descricao : 'Comprador';

        return $this->renderPartial('detalhes-comprador', [
            'nome' => $compradorNome,
            'situacao' => $situacao,
            'processos' => $processos,
        ]);
    }
}
