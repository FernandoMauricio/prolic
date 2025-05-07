<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var app\models\processolicitatorio\ProcessoLicitatorio $model */
/* @var array $valorlimite */
?>
<div class="processo-licitatorio-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_gerar-processo-licitatorio', [
        'model'       => $model,
        'valorlimite' => $valorlimite,
        'artigo'      => $artigo,
        'recurso'     => $recurso,
        'comprador'   => $comprador,
    ]) ?>
</div>