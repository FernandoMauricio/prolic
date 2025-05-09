<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\base\Ramo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ramo-form card shadow-sm border-0">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-diagram-3-fill me-2"></i> <?= $model->isNewRecord ? 'Novo Segmento' : 'Atualizar Segmento' ?></h5>
    </div>

    <div class="card-body">
        <?php $form = ActiveForm::begin(); ?>

        <div class="mb-3">
            <?= $form->field($model, 'ram_descricao')->textInput([
                'readonly' => !$model->isNewRecord,
                'placeholder' => 'Informe a descrição do segmento...',
                'class' => 'form-control'
            ]) ?>
        </div>

        <div class="mb-3">
            <?= $form->field($model, 'ram_status')->radioList(
                ['1' => 'Ativo', '0' => 'Inativo'],
                ['class' => 'btn-group', 'data-toggle' => 'buttons']
            ) ?>
        </div>
    </div>

    <div class="card-footer bg-light text-end">
        <?= Html::a('<i class="bi bi-arrow-left-circle me-1"></i> Cancelar', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
        <?= Html::submitButton('<i class="bi bi-check-circle-fill me-1"></i> Gravar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>