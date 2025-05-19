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
                return $this->render('view', [
                    'model' => $modelo,
                    'itens' => $modelo->itens,
                ]);
            }
        }

        throw new NotFoundHttpException("Requisição {$id} não encontrada no cache.");
    }
}
