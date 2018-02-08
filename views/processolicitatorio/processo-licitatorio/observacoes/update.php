<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\processolicitatorio\Observacoes */

$this->title = 'Update Observacoes: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Observacoes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="observacoes-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
