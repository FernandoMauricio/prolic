<?php

namespace app\controllers\mxm;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ArrayDataProvider;
use yii\web\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use app\models\cache\RequisicaoCache;
use app\models\api\WebManagerService;

class ReqcompraRcoController extends Controller
{
    public static function carregarRequisicaoPorNumero(string $numero): ?RequisicaoCache
    {
        $caminho = Yii::getAlias("@runtime/cache/_requisicoes/{$numero}.json");
        if (!file_exists($caminho)) {
            return null;
        }

        $json = json_decode(file_get_contents($caminho), true);
        if (!is_array($json)) {
            return null;
        }

        return new RequisicaoCache($json);
    }

    public function actionView($id)
    {
        $model = self::carregarRequisicaoPorNumero($id);

        if (!$model) {
            throw new NotFoundHttpException("Requisição {$id} não encontrada no cache.");
        }

        $aprovacoes = self::buscarAprovadoresSimples($id);

        $aprovacoesUnicas = [];
        $chaves = [];
        foreach ($aprovacoes as $aprov) {
            $chave = ($aprov['ordem'] ?? '-') . '|' . ($aprov['aprovador'] ?? '');
            if (!in_array($chave, $chaves)) {
                $chaves[] = $chave;
                $aprovacoesUnicas[] = $aprov;
            }
        }

        return $this->render('view', [
            'model' => $model,
            'itens' => $model->itens,
            'aprovacoes' => $aprovacoesUnicas,
        ]);
    }

    public function actionExportarItens($id)
    {
        $model = self::carregarRequisicaoPorNumero($id);

        if (!$model) {
            throw new NotFoundHttpException("Requisição {$id} não encontrada no cache.");
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray(['Item', 'Descrição', 'UN', 'Qtd. Pedida', 'Valor'], null, 'A1');

        $linha = 2;
        foreach ($model->itens as $item) {
            $sheet->fromArray([
                $item['IRC_ITEM'],
                $item['IRC_DESCRICAO'],
                $item['IRC_UNIDADE'],
                $item['IRC_QTDPEDIDA'],
                $item['IRC_VALOR'],
            ], null, 'A' . $linha++);
        }

        $filename = 'itens-requisicao-' . $model->getNumero() . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save('php://output');
        exit;
    }

    public function actionIndex()
    {
        $termo = Yii::$app->request->get('q');
        $modelos = [];

        $indexPath = Yii::getAlias('@runtime/cache/_requisicoes/requisicoes-index.json');
        $numeros = file_exists($indexPath) ? json_decode(file_get_contents($indexPath), true) : [];

        foreach ($numeros as $numero) {
            $model = self::carregarRequisicaoPorNumero($numero);
            if ($model) {
                if (
                    !$termo ||
                    stripos($model->getNumero(), $termo) !== false ||
                    stripos($model->getRequisitante(), $termo) !== false
                ) {
                    $modelos[] = $model;
                }
            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $modelos,
            'pagination' => ['pageSize' => 10],
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

    public function actionPainel()
    {
        $modelos = [];

        $indexPath = Yii::getAlias('@runtime/cache/_requisicoes/requisicoes-index.json');
        $numeros = file_exists($indexPath) ? json_decode(file_get_contents($indexPath), true) : [];

        foreach ($numeros as $numero) {
            $model = self::carregarRequisicaoPorNumero($numero);
            if ($model) {
                $modelos[] = $model;
            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $modelos,
            'pagination' => ['pageSize' => 20],
        ]);

        return $this->render('painel', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionStatusRequisicaoAjax($numero)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            $status = WebManagerService::consultarStatusRequisicao($numero) ?? 'Indisponível';

            return [
                'statusHtml' => $this->renderPartial('@app/views/partials/_badge-status.php', ['status' => $status])
            ];
        } catch (\Throwable $e) {
            Yii::error("Erro ao consultar status da requisição {$numero}: " . $e->getMessage(), __METHOD__);
            return [
                'statusHtml' => '<span class="badge bg-danger px-2 py-1">Erro</span>'
            ];
        }
    }

    public static function buscarAprovadoresSimples($numero)
    {
        $sql = <<<SQL
            SELECT
                RAIR.RAIR_NUMITEM AS ITEM,
                CASE
                    WHEN MXU.MXU_NOME IS NOT NULL THEN MXU.MXU_NOME
                    WHEN LAA.LAA_SQITEMAPROVACAO = 1 THEN ESF.ESF_DESCRICAO || '(aguardando designação de comprador)'
                    ELSE ESF.ESF_DESCRICAO
                END AS APROVADOR,
                LAA.LAA_DTAPROVACAO AS DATA_APROVACAO,
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
            LEFT JOIN ESTRFUNC_ESF ESF ON ESF.ESF_CDEMPRESA = '02' AND ESF.ESF_CODIGO = LAA.LAA_ESTRFUNC
            WHERE RAIR.RAIR_NUMEROREQ = :NUMERO
            ORDER BY LAA.LAA_SQITEMAPROVACAO, LAA.LAA_DTAPROVACAO
        SQL;

        $linhas = Yii::$app->db_oracle
            ->createCommand($sql, [':NUMERO' => $numero])
            ->queryAll();

        return array_map(function ($linha) {
            return [
                'item' => $linha['ITEM'] ?? null,
                'ordem' => $linha['ORDEM'] ?? '-',
                'aprovador' => isset($linha['APROVADOR'])
                    ? preg_replace(
                        '/aguardando designa.{1,5}o de comprador/ui',
                        'aguardando designação de comprador',
                        $linha['APROVADOR']
                    )
                    : '(não atribuído)',
                'data_aprovacao' => $linha['DATA_APROVACAO'] ?? null,
                'status' => $linha['STATUS'] ?? 'Desconhecido',
            ];
        }, $linhas);
    }
}
