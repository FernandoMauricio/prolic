<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\base\Artigo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="artigo-form card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i> <?= $model->isNewRecord ? 'Novo Artigo' : 'Atualizar Artigo' ?></h5>
    </div>

    <div class="card-body">
        <?php $form = ActiveForm::begin(); ?>

        <div class="row g-3">
            <div class="col-md-8">
                <?= $form->field($model, 'art_descricao')->textInput([
                    'readonly' => !$model->isNewRecord,
                    'placeholder' => 'Informe a descrição do artigo...',
                ]) ?>
            </div>

            <div class="col-md-4">
                <?= $form->field($model, 'art_tipo')->widget(Select2::class, [
                    'data' => ['Valor' => 'Valor', 'Situação' => 'Situação'],
                    'options' => ['placeholder' => 'Selecione o tipo...'],
                    'pluginOptions' => ['allowClear' => true],
                ]) ?>
            </div>

            <div class="col-md-12">
                <?= $form->field($model, 'art_status')->radioList(
                    ['1' => 'Ativo', '0' => 'Inativo'],
                    ['class' => 'd-flex gap-4']
                ) ?>
            </div>
        </div>
    </div>

    <div class="card-footer bg-light text-end">
        <?= Html::a('<i class="bi bi-arrow-left-circle me-1"></i> Cancelar', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
        <?= Html::submitButton('<i class="bi bi-check-circle-fill me-1"></i> Gravar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>