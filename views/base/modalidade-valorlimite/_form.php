<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\base\ModalidadeValorlimite */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="modalidade-valorlimite-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'modalidade_id')->textInput() ?>

    <?= $form->field($model, 'ramo_id')->textInput() ?>

    <?= $form->field($model, 'ano_id')->textInput() ?>

    <?= $form->field($model, 'valor_limite')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
