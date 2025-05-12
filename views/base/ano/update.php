<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\base\Ano */

$this->title = 'Atualizar Ano: ' . $model->an_ano . '';
$this->params['breadcrumbs'][] = ['label' => 'Anos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="ano-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>