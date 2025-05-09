<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\base\Comprador */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="comprador-form card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-person-fill me-2"></i> <?= $model->isNewRecord ? 'Novo Comprador' : 'Editar Comprador' ?></h5>
    </div>

    <div class="card-body">
        <?php $form = ActiveForm::begin(); ?>

        <div class="row g-3">
            <div class="col-md-8">
                <?= $form->field($model, 'comp_descricao')->textInput([
                    'readonly' => !$model->isNewRecord,
                    'placeholder' => 'Informe o nome do comprador...',
                ]) ?>
            </div>

            <div class="col-md-4">
                <?= $form->field($model, 'comp_status')->radioList([
                    '1' => 'Ativo',
                    '0' => 'Inativo',
                ], ['class' => 'mt-2']) ?>
            </div>
        </div>
    </div>

    <div class="card-footer bg-light text-end">
        <?= Html::a('<i class="bi bi-arrow-left-circle me-1"></i> Cancelar', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
        <?= Html::submitButton('<i class="bi bi-check-circle-fill me-1"></i> Gravar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>