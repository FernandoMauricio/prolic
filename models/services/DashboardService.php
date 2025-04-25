<?php

namespace app\models\services;

use app\models\processolicitatorio\ProcessoLicitatorio;
use app\models\FiltroDashboardForm;
use yii\db\Expression;

class DashboardService
{
    public static function getKpis(FiltroDashboardForm $filtro): array
    {
        $query = ProcessoLicitatorio::find();

        if ($filtro->ano) {
            $query->andWhere(['YEAR(prolic_dataprocesso)' => $filtro->ano]);
        }

        if ($filtro->mes) {
            $query->andWhere(['MONTH(prolic_dataprocesso)' => $filtro->mes]);
        }

        $total = $query->count();
        $valorEstimado = $query->sum('prolic_valorestimado');
        $valorEfetivo = $query->sum('prolic_valorefetivo');

        $ciclos = $query
            ->select(['dias' => new Expression('DATEDIFF(prolic_datahomologacao, prolic_dataprocesso)')])
            ->andWhere(['not', ['prolic_datahomologacao' => null]])
            ->andWhere(['not', ['prolic_dataprocesso' => null]])
            ->asArray()
            ->all();

        $dias = array_column($ciclos, 'dias');
        $mediaCiclo = $dias ? round(array_sum($dias) / count($dias), 2) : null;

        return [
            'total' => $total,
            'valor_estimado' => $valorEstimado,
            'valor_efetivo' => $valorEfetivo,
            'ciclo_medio' => $mediaCiclo,
        ];
    }

    public static function getAnosDisponiveis(): array
    {
        return ProcessoLicitatorio::find()
            ->select(['ano' => new Expression('YEAR(prolic_dataprocesso)')])
            ->distinct()
            ->orderBy(['ano' => SORT_DESC])
            ->column();
    }

    public static function getDistribuicaoPorModalidade(FiltroDashboardForm $filtro): array
    {
        $query = ProcessoLicitatorio::find()
            ->joinWith(['modalidadeValorlimite.modalidade mod']);

        if ($filtro->ano) {
            $query->andWhere(['YEAR(prolic_dataprocesso)' => $filtro->ano]);
        }

        if ($filtro->mes) {
            $query->andWhere(['MONTH(prolic_dataprocesso)' => $filtro->mes]);
        }

        return $query
            ->select(['name' => 'mod.mod_descricao', 'y' => new Expression('COUNT(*)')])
            ->groupBy('mod.id')
            ->asArray()
            ->all();
    }

    public static function getDistribuicaoPorSituacao(FiltroDashboardForm $filtro): array
    {
        $query = ProcessoLicitatorio::find()
            ->alias('p')
            ->joinWith(['situacao s'], false)
            ->select(['name' => 's.sit_descricao', 'y' => new Expression('COUNT(*)')])
            ->groupBy('s.id');

        if ($filtro->ano) {
            $query->andWhere(['YEAR(p.prolic_dataprocesso)' => $filtro->ano]);
        }

        if ($filtro->mes) {
            $query->andWhere(['MONTH(p.prolic_dataprocesso)' => $filtro->mes]);
        }

        return $query->asArray()->all();
    }


    public static function getTopCompradores(FiltroDashboardForm $filtro): array
    {
        $query = ProcessoLicitatorio::find()
            ->alias('p')
            ->joinWith(['comprador c'], false)
            ->select([
                'name' => new Expression('UPPER(c.comp_descricao)'),
                'y' => new Expression('COUNT(*)')
            ])
            ->groupBy(new Expression('UPPER(c.comp_descricao)'))
            ->orderBy(['y' => SORT_DESC])
            ->limit(5);

        if ($filtro->ano) {
            $query->andWhere(['YEAR(p.prolic_dataprocesso)' => $filtro->ano]);
        }

        if ($filtro->mes) {
            $query->andWhere(['MONTH(p.prolic_dataprocesso)' => $filtro->mes]);
        }

        $results = $query->asArray()->all();

        return $results;
    }

    public static function getAlertas(FiltroDashboardForm $filtro): array
    {
        $query = ProcessoLicitatorio::find()
            ->where(['situacao_id' => [1, 2, 5, 6]]) // Situações -> Elaboração, Licitação, Andamento, Homologação
            ->andWhere(['<', 'prolic_datacertame', new Expression('DATE_SUB(NOW(), INTERVAL 90 DAY)')])
            ->andWhere(['prolic_datahomologacao' => null]);

        if ($filtro->ano) {
            $query->andWhere(['YEAR(prolic_dataprocesso)' => $filtro->ano]);
        }

        if ($filtro->mes) {
            $query->andWhere(['MONTH(prolic_dataprocesso)' => $filtro->mes]);
        }

        return $query->limit(10)->all();
    }

    public static function getDistribuicaoMensal(FiltroDashboardForm $filtro): array
    {
        $queryProcessos = ProcessoLicitatorio::find()
            ->select(['mes' => new Expression('MONTH(prolic_datacertame)'), 'y' => new Expression('COUNT(*)')])
            ->groupBy(['mes'])
            ->orderBy(['mes' => SORT_ASC]);

        $queryAlertas = ProcessoLicitatorio::find()
            ->select(['mes' => new Expression('MONTH(prolic_datacertame)'), 'y' => new Expression('COUNT(*)')])
            ->where(['situacao_id' => [1, 2, 5, 6]]) // Situações -> Elaboração, Licitação, Andamento, Homologação
            ->andWhere(['<', 'prolic_datacertame', new Expression('DATE_SUB(NOW(), INTERVAL 90 DAY)')])
            ->andWhere(['prolic_datahomologacao' => null])
            ->groupBy(['mes'])
            ->orderBy(['mes' => SORT_ASC]);

        if ($filtro->ano) {
            $queryProcessos->andWhere(['YEAR(prolic_dataprocesso)' => $filtro->ano]);
            $queryAlertas->andWhere(['YEAR(prolic_dataprocesso)' => $filtro->ano]);
        }

        return [
            'processos' => $queryProcessos->asArray()->all(),
            'alertas' => $queryAlertas->asArray()->all()
        ];
    }
}
