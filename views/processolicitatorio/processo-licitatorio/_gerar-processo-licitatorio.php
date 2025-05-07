<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use kartik\depdrop\DepDrop;

/* @var $this yii\web\View */
/* @var $model app\models\processolicitatorio\ProcessoLicitatorio */
/* @var array $valorlimite */
/* @var array $artigo */

?>

<?php $form = ActiveForm::begin([
    'id' => 'form-gerar-processo',
    'options' => ['class' => 'h-100'],
]); ?>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-journal-plus me-2"></i>Dados do Processo Licitatório</h5>
    </div>
    <div class="card-body">
        <div class="alert alert-info shadow-sm" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i>
            <strong>Próxima etapa:</strong> Após salvar esta solicitação, você será direcionado para adicionar mais detalhes sobre o processo.
        </div>
        <div class="row g-3">
            <!-- Modalidade e Ramo -->
            <div class="col-md-6">
                <?php
                echo $form->field($model, 'modalidade')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map($valorlimite, 'modalidade.id', 'modalidade.mod_descricao'),
                    'options' => ['id' => 'modalidade-id', 'placeholder' => 'Selecione a Modalidade...'],
                    'pluginOptions' => ['allowClear' => true],
                ])->label('Modalidade <span class="text-danger">*</span>');
                ?>
            </div>
            <div class="col-md-6">
                <?php
                echo $form->field($model, 'modalidade_valorlimite_id')->widget(DepDrop::classname(), [
                    'type'          => DepDrop::TYPE_SELECT2,
                    'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                    'options'       => ['id' => 'valorlimite-id'],
                    'pluginOptions' => [
                        'depends'    => ['modalidade-id'],
                        'placeholder' => 'Selecione o Ramo...',
                        'initialize' => true,
                        'url'        => Url::to(['/processolicitatorio/processo-licitatorio/limite']),
                    ],
                ])->label('Ramo <span class="text-danger">*</span>');
                ?>
            </div>

            <!-- Artigo -->
            <div class="col-md-12">
                <?= $form->field($model, 'artigo_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map($artigo, 'id', 'art_descricao'),
                    'options' => ['placeholder' => 'Escolha o Artigo...'],
                    'pluginOptions' => ['allowClear' => true],
                ])->label('Artigo <span class="text-danger">*</span>'); ?>
            </div>

        </div>
    </div>

    <div class="card-footer text-end">
        <?= Html::submitButton('<i class="bi bi-save2 me-1"></i> Salvar e Continuar', [
            'class' => 'btn btn-success'
        ]) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>