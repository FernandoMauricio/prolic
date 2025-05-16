<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\base\ModalidadeValorlimite $model */

$this->title = 'Atualizar Valor Limite';
$this->params['breadcrumbs'][] = ['label' => 'Parâmetros do Sistema', 'url' => ['/site/parametros']];
$this->params['breadcrumbs'][] = ['label' => 'Valores por Modalidade', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="modalidade-valorlimite-update">

    <h1 class="fs-3 fw-bold text-primary mb-4">
        <i class="bi bi-pencil-fill me-2"></i> <?= Html::encode($this->title) ?>
    </h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modalidade' => $modalidade,
        'ramo' => $ramo,
    ]) ?>

</div>