<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\processolicitatorio\ProcessoLicitatorio */

$this->title = 'Create Processo Licitatorio';
$this->params['breadcrumbs'][] = ['label' => 'Processo Licitatorios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="processo-licitatorio-create">

    <h1><?= Html::encode($this->title) ?></h1>

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
        'empresa' => $empresa,
    ]) ?>

</div>
