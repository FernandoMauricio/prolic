<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\base\Comprador */

$this->title = 'Atualizar Comprador: ' . $model->comp_descricao . '';
$this->params['breadcrumbs'][] = ['label' => 'Compradores', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="comprador-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>