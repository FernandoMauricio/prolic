<?php

namespace app\controllers\mxm;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\cache\RequisicaoCache;
use yii\data\ArrayDataProvider;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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


    public function actionExportarItens($id)
    {
        foreach ($this->carregarTodasRequisicoes() as $modelo) {
            if ($modelo->getNumero() === $id) {
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // Cabeçalhos
                $sheet->fromArray([
                    'Item',
                    'Descrição',
                    'Especificação Técnica',
                    'UN',
                    'Qtd. Pedida',
                    'Qtd. Atendida',
                    'Preço',
                    'Desconto',
                    '% Desc.',
                    'Preço s/ Impostos',
                    'Entrega Prevista'
                ], null, 'A1');

                $linha = 2;
                foreach ($modelo->itens as $item) {
                    $sheet->fromArray([
                        $item['IPC_ITEM'],
                        $item['IPC_DESCRICAO'],
                        $item['IPC_TXESPTECNICAMAPA'],
                        $item['IPC_UNIDADE'],
                        $item['IPC_QTD'],
                        $item['IPC_QTDATEND'],
                        $item['IPC_PRECO'],
                        $item['IPC_VLDESCONTO'],
                        $item['IPC_PERCDESC'] . '%',
                        $item['IPC_PRECOSEMIMP'],
                        Yii::$app->formatter->asDate($item['IPC_DTPARAENT'], 'php:d/m/Y'),
                    ], null, 'A' . $linha++);
                }

                $filename = 'itens-requisicao-' . $modelo->getNumero() . '.xlsx';
                $writer = new Xlsx($spreadsheet);

                // Enviar para o navegador
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header("Content-Disposition: attachment; filename=\"$filename\"");
                $writer->save('php://output');
                exit;
            }
        }

        throw new NotFoundHttpException("Requisição {$id} não encontrada.");
    }

    public static function buscarAprovadoresSimples($NUMERO)
    {
        $sql = <<<SQL
            SELECT
                RAIR.RAIR_NUMITEM AS ITEM,
                TRIM(MXU.MXU_NOME) AS APROVADOR,
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
            WHERE RAIR.RAIR_NUMEROREQ = :NUMERO
            ORDER BY LAA.LAA_SQITEMAPROVACAO, LAA.LAA_DTAPROVACAO
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
                'status'          => $linha['STATUS'] ?? 'Desconhecido',
            ];
        }, $linhas);
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

                $aprovacoes = self::buscarAprovadoresSimples($modelo->getNumero());

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
                    'model' => $modelo,
                    'itens' => $modelo->itens,
                    'aprovacoes' => $aprovacoes,
                ]);
            }
        }

        throw new NotFoundHttpException("Requisição {$id} não encontrada no cache.");
    }
}
