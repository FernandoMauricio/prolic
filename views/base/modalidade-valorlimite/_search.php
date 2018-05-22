<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\base\ModalidadeValorlimiteSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="modalidade-valorlimite-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'modalidade_id') ?>

    <?= $form->field($model, 'ramo_id') ?>

    <?= $form->field($model, 'ano_id') ?>

    <?= $form->field($model, 'valor_limite') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
