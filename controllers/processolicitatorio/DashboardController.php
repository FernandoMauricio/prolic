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

    public function actionDetalhesComprador($nome, $situacao)
    {
        $processos = ProcessoLicitatorio::find()
            ->alias('p')
            ->joinWith(['situacao s', 'comprador c'])
            ->where(['like', 'c.comp_descricao', $nome])
            ->andWhere(['like', 's.sit_descricao', $situacao])
            ->all();

        return $this->renderPartial('detalhes-processos', compact('processos'));
    }
}
