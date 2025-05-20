<?php

namespace app\controllers\mxm;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\cache\RequisicaoCache;
use yii\data\ArrayDataProvider;

class ReqcompraRcoController extends Controller
{
    /**
     * Lê o cache e transforma em uma lista de modelos RequisicaoCache.
     * @return RequisicaoCache[]
     * @throws \Exception
     */
    private function carregarTodasRequisicoes(): array
    {
        $caminho = Yii::getAlias('@runtime/cache/requisicoes-cache.json');

        if (!file_exists($caminho)) {
            throw new \Exception('Arquivo de cache de requisições não encontrado.');
        }

        $dadosBrutos = json_decode(file_get_contents($caminho), true);

        if (!is_array($dadosBrutos)) {
            throw new \Exception('Formato inválido no arquivo de cache.');
        }

        return array_map(fn($row) => new RequisicaoCache($row), $dadosBrutos);
    }

    public static function buscarAprovadoresSimples($NUMERO)
    {
        $sql = <<<SQL
            SELECT
                RAIR.RAIR_NUMITEM AS ITEM,
                TRIM(MXU.MXU_NOME) AS APROVADOR,
                LAA.LAA_DTAPROVACAO AS DATA_APROVACAO,
                LAA.LAA_JUSTIFICATIVA AS JUSTIFICATIVA,
                LAA.LAA_SQITEMAPROVACAO AS ORDEM,
                CASE LAA.LAA_STATUS
                    WHEN 1 THEN 'Aprovado'
                    WHEN 2 THEN 'Cancelado'
                    WHEN 3 THEN 'Reprovado'
                    ELSE 'Pendente'
                END AS STATUS
            FROM RELAPROVIREQ_RAIR RAIR
            JOIN LINHAAPROVLOC_LAA LAA ON RAIR.RAIR_SQAPROVACAO = LAA.LAA_SQAPROVACAO
            LEFT JOIN MXS_USUARIO_MXU MXU ON LAA.LAA_APROVADOR = MXU.MXU_USUARIO
            WHERE RAIR.RAIR_NUMEROREQ = :NUMERO
            ORDER BY RAIR.RAIR_NUMITEM, LAA.LAA_SQITEMAPROVACAO
        SQL;

        $linhas = Yii::$app->db_oracle
            ->createCommand($sql, [':NUMERO' => $NUMERO])
            ->queryAll();

        return array_map(function ($linha) {
            return [
                'item'            => $linha['ITEM'] ?? null,
                'ordem'           => $linha['ORDEM'] ?? '-',
                'aprovador'       => $linha['APROVADOR'] ?? '(não atribuído)',
                'data_aprovacao'  => $linha['DATA_APROVACAO'] ?? null,
                'justificativa'   => $linha['JUSTIFICATIVA'] ?? '-',
                'status'          => $linha['STATUS'] ?? 'Desconhecido',
            ];
        }, $linhas);
    }

    public static function buscarAprovadoresPorItem($numero)
    {
        $sql = <<<SQL
            SELECT
                rair.RAIR_NUMITEM AS item,
                TRIM(mxu.MXU_NOME) AS aprovador,
                TRIM(mxu.MXU_EMAIL) AS email,
                laa.LAA_DTAPROVACAO AS data_aprovacao,
                laa.LAA_JUSTIFICATIVA AS justificativa,
                CASE laa.LAA_STATUS
                    WHEN 1 THEN 'Aprovado'
                    WHEN 3 THEN 'Reprovado'
                    WHEN 2 THEN 'Cancelado'
                    ELSE 'Pendente'
                END AS status
            FROM RELAPROVIREQ_RAIR rair
            JOIN LINHAAPROVLOC_LAA laa ON rair.RAIR_SQAPROVACAO = laa.LAA_SQAPROVACAO
            LEFT JOIN MXS_USUARIO_MXU mxu ON laa.LAA_APROVADOR = mxu.MXU_USUARIO
            WHERE TRIM(rair.RAIR_NUMEROREQ) = :numero
            ORDER BY rair.RAIR_NUMITEM, laa.LAA_SEQUENCIA
        SQL;

        $linhas = Yii::$app->db_oracle->createCommand($sql, [':numero' => $numero])->queryAll();

        $resultado = [];
        foreach ($linhas as $linha) {
            $itemIndex = (int)($linha['ITEM'] ?? 0); // ← Em caixa alta

            $resultado[$itemIndex][] = [
                'aprovador'      => trim($linha['APROVADOR'] ?? ''),
                'data_aprovacao' => $linha['DATA_APROVACAO'] ?? null,
                'justificativa'  => $linha['JUSTIFICATIVA'] ?? null,
                'status'         => $linha['STATUS'] ?? 'Pendente',
            ];
        }

        return $resultado;
    }

    /**
     * Lista filtrada de requisições.
     */
    public function actionIndex()
    {
        $termo = Yii::$app->request->get('q');
        $modelos = [];

        foreach ($this->carregarTodasRequisicoes() as $modelo) {
            if (
                !$termo ||
                stripos($modelo->getNumero(), $termo) !== false ||
                stripos($modelo->getRequisitante(), $termo) !== false
            ) {
                $modelos[] = $modelo;
            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $modelos,
            'pagination' => ['pageSize' => 20],
            'sort' => [
                'attributes' => [
                    'requisicao.RCO_NUMERO',
                    'requisicao.RCO_DATA',
                    'requisicao.RCO_REQUISITANTE'
                ]
            ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchTerm' => $termo,
        ]);
    }

    /**
     * Detalhes de uma requisição específica.
     */
    public function actionView($id)
    {
        foreach ($this->carregarTodasRequisicoes() as $modelo) {
            if ($modelo->getNumero() === $id) {

                $aprovadoresPorItem = self::buscarAprovadoresPorItem($modelo->getNumero());
                $aprovacoes = self::buscarAprovadoresSimples($modelo->getNumero());


                return $this->render('view', [
                    'model' => $modelo,
                    'itens' => $modelo->itens,
                    'aprovadoresPorItem' => $aprovadoresPorItem,
                    'aprovacoes' => $aprovacoes,
                ]);
            }
        }

        throw new NotFoundHttpException("Requisição {$id} não encontrada no cache.");
    }
}
