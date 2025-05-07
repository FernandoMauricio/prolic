<?php

/* @var $this yii\web\View */
/* @var $model app\models\processolicitatorio\ProcessoLicitatorio */

use yii\web\JqueryAsset;

$this->title = 'Editar Processo #' . $model->id;
$this->registerJsFile(
    '@web/js/processolicitatorio.js',
    ['depends' => [JqueryAsset::class]]
);

$this->title = 'Atualizar Processo Licitatório: ' . $model->id . '';
$this->params['breadcrumbs'][] = ['label' => 'Listagem de Processo Licitatórios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Atualizar';
?>

<div class="processo-licitatorio-update">

    <?= $this->render('_form', [
        'model' => $model,
        'ano' => $ano,
        'ramo' => $ramo,
        'destinos' => $destinos,
        'valorlimite' => $valorlimite,
        'artigo' => $artigo,
        'centrocusto' => $centrocusto,
        'recurso' => $recurso,
        'comprador' => $comprador,
        'situacao' => $situacao,
    ]) ?>

</div>