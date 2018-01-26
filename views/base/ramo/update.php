<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\base\Ramo */

$this->title = 'Update Ramo: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Ramos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ramo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
