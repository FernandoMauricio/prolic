<?php

/* @var $this yii\web\View */
/* @var $model app\models\processolicitatorio\ProcessoLicitatorio */

use yii\web\JqueryAsset;

$this->title = 'Editar Processo #' . $model->id;
// $this->registerJsFile(
//     '@web/js/processolicitatorio.js',
//     ['depends' => [JqueryAsset::class]]
// );

$this->registerJsFile('@web/js/requisicoes-handler.js', [
    'depends' => [\yii\web\JqueryAsset::class],
]);
$this->registerJs('var processoId = ' . (int) $model->id . ';', \yii\web\View::POS_HEAD);


$this->title = 'Atualizar Processo Licitatório: ' . $model->id . '';
$this->params['breadcrumbs'][] = ['label' => 'Processo Licitatórios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Atualizar';

?>
<?= \app\widgets\Alert::widget() ?>

<div class="processo-licitatorio-update">

    <?= $this->render('form/_form', [
        'model' => $model,
        'ramo' => $ramo,
        'destinos' => $destinos,
        'valorlimite' => $valorlimite,
        'artigo' => $artigo,
        'centrocusto' => $centrocusto,
        'recurso' => $recurso,
        'comprador' => $comprador,
        'situacao' => $situacao,
        'requisicoes' => $requisicoes,
    ]) ?>

</div>