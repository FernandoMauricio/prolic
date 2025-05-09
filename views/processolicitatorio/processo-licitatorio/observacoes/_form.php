<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\processolicitatorio\Observacoes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="observacoes-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="mb-3">
        <?= $form->field($model, 'obs_descricao')->textarea([
            'rows' => 3,
            'maxlength' => true,
            'class' => 'form-control'
        ]) ?>
    </div>

    <div class="d-flex justify-content-end">
        <?= Html::submitButton('<i class="bi bi-check2-circle me-1"></i> Salvar', [
            'class' => 'btn btn-success shadow-sm'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>