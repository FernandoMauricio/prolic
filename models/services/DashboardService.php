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
                'name' => 'c.comp_descricao',
                'comprador_id' => 'c.id',
                'y' => new Expression('COUNT(*)')
            ])
            ->groupBy('c.id')
            ->orderBy(['y' => SORT_DESC])
            ->limit(5);

        if ($filtro->ano) {
            $query->andWhere(['YEAR(p.prolic_dataprocesso)' => $filtro->ano]);
        }

        if ($filtro->mes) {
            $query->andWhere(['MONTH(p.prolic_dataprocesso)' => $filtro->mes]);
        }

        return $query->asArray()->all();
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

    /**
     * Retorna o top 5 de unidades atendidas, filtrado por ano/mês se fornecido.
     */
    public static function getTopUnidadesAtendidas(FiltroDashboardForm $filtro): array
    {
        // 1. Buscar todas unidades no db_base
        $unidades = \Yii::$app->db_base->createCommand("
        SELECT uni_codunidade, uni_nomeabreviado FROM unidade_uni
    ")->queryAll();

        $top = [];

        // 2. Para cada unidade, contar processos no banco principal db
        foreach ($unidades as $unidade) {
            $query = ProcessoLicitatorio::find()
                ->where(new \yii\db\Expression('FIND_IN_SET(:codigo, prolic_destino) > 0'), [':codigo' => $unidade['uni_codunidade']]);

            if ($filtro->ano) {
                $query->andWhere(['YEAR(prolic_dataprocesso)' => $filtro->ano]);
            }
            if ($filtro->mes) {
                $query->andWhere(['MONTH(prolic_dataprocesso)' => $filtro->mes]);
            }

            $count = (int)$query->count();

            if ($count > 0) {
                $top[] = [
                    'codigo' => $unidade['uni_codunidade'],
                    'unidade' => $unidade['uni_nomeabreviado'],
                    'count' => $count
                ];
            }
        }

        // 3. Ordenar descrescente e pegar os 5 maiores
        usort($top, fn($a, $b) => $b['count'] <=> $a['count']);
        $top = array_slice($top, 0, 5);

        return $top;
    }

    /**
     * Retorna o top 10 de processos com maior valor estimado.
     */
    public static function getMaioresRequisicoes(FiltroDashboardForm $filtro): array
    {
        $query = ProcessoLicitatorio::find()
            ->select([
                'codigo' => 'processo_licitatorio.id',
                'numero_processo' => 'prolic_codmxm',
                'valor_estimado' => 'prolic_valorestimado'
            ])
            ->orderBy(['prolic_valorestimado' => SORT_DESC])
            ->limit(10);

        if ($filtro->ano) {
            $query->andWhere(['YEAR(prolic_dataprocesso)' => $filtro->ano]);
        }
        if ($filtro->mes) {
            $query->andWhere(['MONTH(prolic_dataprocesso)' => $filtro->mes]);
        }

        return $query->asArray()->all();
    }

    /**
     * Retorna o top 5 de compradores com a contagem de processos por situação (stacked).
     */
    public static function getCompradoresSituacao(FiltroDashboardForm $filtro): array
    {
        $topCompradores = self::getTopCompradores($filtro);

        $data = [];
        foreach ($topCompradores as $comprador) {
            $entry = [
                'comprador'      => $comprador['name'],
                'comprador_id'   => $comprador['comprador_id'],
                'Em Licitação'   => 0,
                'Concluido'      => 0,
                'Deserto'        => 0,
                'Em Andamento'   => 0,
                'Em Homologação' => 0,
                'Cancelado'      => 0,
            ];

            $rows = ProcessoLicitatorio::find()
                ->alias('p')
                ->joinWith(['situacao s'], false)
                ->select([
                    'situacao' => 's.sit_descricao',
                    'count' => new Expression('COUNT(*)')
                ])
                ->where(['p.comprador_id' => $comprador['comprador_id']])
                ->groupBy('s.sit_descricao');

            if ($filtro->ano) {
                $rows->andWhere(['YEAR(p.prolic_dataprocesso)' => $filtro->ano]);
            }

            if ($filtro->mes) {
                $rows->andWhere(['MONTH(p.prolic_dataprocesso)' => $filtro->mes]);
            }

            foreach ($rows->asArray()->all() as $row) {
                $entry[$row['situacao']] = (int)$row['count'];
            }

            $data[] = $entry;
        }

        return $data;
    }
}
