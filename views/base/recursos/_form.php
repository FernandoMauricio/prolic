<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\base\Recursos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="recursos-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'rec_descricao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rec_status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
