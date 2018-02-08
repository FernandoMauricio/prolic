<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\processolicitatorio\ObservacoesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="observacoes-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'obs_descricao') ?>

    <?= $form->field($model, 'obs_usuariocriacao') ?>

    <?= $form->field($model, 'obs_datacriacao') ?>

    <?= $form->field($model, 'processo_licitatorio_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
