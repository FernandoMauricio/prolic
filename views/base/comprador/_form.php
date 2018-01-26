<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\base\Comprador */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="comprador-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'comp_descricao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comp_status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
