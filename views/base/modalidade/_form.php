<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\base\Modalidade */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="modalidade-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'mod_descricao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mod_status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
