<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\processolicitatorio\Observacoes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="observacoes-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'obs_descricao')->textarea(['rows' => 3, 'maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
