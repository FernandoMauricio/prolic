<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use kartik\depdrop\DepDrop;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\processolicitatorio\ProcessoLicitatorio */
/* @var array $valorlimite */
/* @var array $artigo */

?>

<?php $form = ActiveForm::begin([
    'id' => 'form-gerar-processo',
    'options' => ['class' => 'h-100', 'enctype' => 'multipart/form-data'],
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
            <!-- Modalidade e Segmento -->
            <div class="col-md-4">
                <?php
                echo $form->field($model, 'modalidade')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map($valorlimite, 'modalidade.id', 'modalidade.mod_descricao'),
                    'options' => [
                        'id' => 'modalidade-id',
                        'placeholder' => 'Selecione a Modalidade…',
                    ],
                    'pluginOptions' => [
                        'allowClear'               => true,
                        'minimumResultsForSearch'  => 0,
                        'dropdownParent'           => new JsExpression('$("#modal")'),
                    ],
                ]);
                ?>
            </div>
            <div class="col-md-8">
                <?php
                echo $form->field($model, 'modalidade_valorlimite_id')->widget(DepDrop::classname(), [
                    'type'            => DepDrop::TYPE_SELECT2,
                    'select2Options'  => [
                        'options'       => ['id' => 'valorlimite-id'],
                        'pluginOptions' => [
                            'allowClear'              => true,
                            'minimumResultsForSearch' => 0,
                            'dropdownParent'          => new JsExpression('$("#modal")'),
                        ],
                    ],
                    'pluginOptions'   => [
                        'depends'      => ['modalidade-id'],
                        'placeholder'  => 'Selecione o Segmento...',
                        'initialize'   => true,
                        'url'          => Url::to(['/processolicitatorio/processo-licitatorio/limite']),
                    ],
                ])->label('Segmento <span class="text-danger">*</span>');
                ?>
            </div>
            <div class="col-md-6">
                <?php
                echo $form->field($model, 'recursos_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map($recurso, 'id', 'rec_descricao'),
                    'options' => [
                        'placeholder' => 'Selecione o Recurso...',
                    ],
                    'pluginOptions' => [
                        'allowClear'               => true,
                        'minimumResultsForSearch'  => 0,
                        'dropdownParent'           => new JsExpression('$("#modal")'),
                    ],
                ]);
                ?>
            </div>
            <div class="col-md-6">
                <?php
                echo $form->field($model, 'comprador_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map($comprador, 'id', 'comp_descricao'),
                    'options' => [
                        'placeholder' => 'Selecione o Comprador...',
                    ],
                    'pluginOptions' => [
                        'allowClear'               => true,
                        'minimumResultsForSearch'  => 0,
                        'dropdownParent'           => new JsExpression('$("#modal")'),
                    ],
                ]);
                ?>
            </div>
            <!-- Artigo -->
            <div class="col-md-12">
                <?php
                echo $form->field($model, 'artigo_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map($artigo, 'id', 'art_descricao'),
                    'options' => ['placeholder' => 'Informe o Artigo…'],
                    'pluginOptions' => [
                        'allowClear'               => true,
                        'minimumResultsForSearch'  => 0,
                        'dropdownParent'           => new JsExpression('$("#modal")'),
                    ],
                ]);
                ?>
            </div>

        </div>
    </div>

    <div class="card-footer bg-light d-flex justify-content-end">
        <?= Html::a('<i class="bi bi-x-circle me-1"></i> Cancelar', ['index'], [
            'class' => 'btn btn-outline-secondary me-2',
        ]) ?>
        <?= Html::submitButton('<i class="bi bi-check-circle me-1"></i> Salvar e Continuar', [
            'class' => 'btn btn-primary',
            'name' => 'submit-button',
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>