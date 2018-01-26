<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\base\ModalidadeValorlimite */

$this->title = 'Update Modalidade Valorlimite: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Modalidade Valorlimites', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="modalidade-valorlimite-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
