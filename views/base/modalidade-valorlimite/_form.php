<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\money\MaskMoney;

/* @var $this yii\web\View */
/* @var $model app\models\base\ModalidadeValorlimite */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="modalidade-valorlimite-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="card shadow-sm mb-4 border-primary">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-cash-coin me-2"></i> Cadastro de Valor Limite</h5>
        </div>
        <div class="card-body">

            <div class="row g-3">
                <div class="col-md-4">
                    <?= $form->field($model, 'modalidade_id')->widget(Select2::class, [
                        'data' => ArrayHelper::map($modalidade, 'id', 'mod_descricao'),
                        'options' => ['placeholder' => 'Selecione a Modalidade...'],
                        'pluginOptions' => ['allowClear' => true],
                        'disabled' => !$model->isNewRecord,
                    ]) ?>
                </div>

                <div class="col-md-5">
                    <?= $form->field($model, 'ramo_id')->widget(Select2::class, [
                        'data' => ArrayHelper::map($ramo, 'id', 'ram_descricao'),
                        'options' => ['placeholder' => 'Selecione o Segmento...'],
                        'pluginOptions' => ['allowClear' => true],
                        'disabled' => !$model->isNewRecord,
                    ]) ?>
                </div>

                <div class="col-md-3">
                    <?= $form->field($model, 'ano_id')->widget(Select2::class, [
                        'data' => ArrayHelper::map($ano, 'id', 'an_ano'),
                        'options' => ['placeholder' => 'Selecione o Ano...'],
                        'pluginOptions' => ['allowClear' => true],
                    ]) ?>
                </div>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-4">
                    <?= $form->field($model, 'valor_limite')->widget(MaskMoney::class, [
                        'pluginOptions' => [
                            'prefix' => 'R$ ',
                            'allowNegative' => false,
                            'thousands' => '.',
                            'decimal' => ','
                        ]
                    ]) ?>
                </div>

                <div class="col-md-4">
                    <?= $form->field($model, 'status')->radioList([
                        '1' => 'Ativo',
                        '0' => 'Inativo'
                    ], ['class' => 'd-flex gap-4']) ?>
                </div>
            </div>
        </div>
        <div class="card-footer bg-light text-end">
            <?= Html::a('<i class="bi bi-arrow-left-circle me-1"></i> Cancelar', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
            <?= Html::submitButton('<i class="bi bi-check-circle-fill me-1"></i> Gravar', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>