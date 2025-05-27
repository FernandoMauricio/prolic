<?php

use kartik\select2\Select2;
use faryshta\widgets\JqueryTagsInput;
use yii\helpers\ArrayHelper;
?>
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-lg-6">
                <?= $form->field($model, 'prolic_centrocusto')->widget(Select2::class, [
                    'data' => ArrayHelper::map($centrocusto, 'cen_centrocustoreduzido', 'cen_centrocustoreduzido'),
                    'options' => [
                        'placeholder' => 'Informe os Centros de Custos...',
                        'multiple' => true
                    ],
                    'pluginOptions' => ['allowClear' => true],
                ]) ?>
            </div>
            <div class="col-lg-6">
                <?= $form->field($model, 'prolic_elementodespesa')->widget(JqueryTagsInput::class, [
                    'clientOptions' => [
                        'defaultText' => '',
                        'width' => '100%',
                        'height' => 'auto',
                        'delimiter' => ' / ',
                        'interactive' => true,
                    ],
                ]) ?>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-lg-12">
                <?= $form->field($model, 'prolic_responsavel_licitacao', [
                    'template' => '
                        <label class="form-label" for="prolic_responsavel_licitacao">
                            Responsável pela Licitação
                            <i class="bi bi-info-circle-fill text-muted ms-1"
                            data-bs-toggle="tooltip"
                            title="Opcional – preencha apenas se houver licitação."></i>
                        </label>
                        {input}{error}{hint}
                    ',
                ])->widget(Select2::class, [
                    'data' => [
                        'Comissão de Licitação de Obras' => 'Comissão de Licitação de Obras',
                        'Comissão Permanente de Licitação' => 'Comissão Permanente de Licitação',
                        'Laís Lopes' => 'Laís Lopes',
                        'Vinicius Fernandes' => 'Vinicius Fernandes',
                    ],
                    'options' => [
                        'placeholder' => 'Informe o responsável pela licitação...',
                    ],
                    'pluginOptions' => ['allowClear' => true],
                ]) ?>
            </div>
        </div>
    </div>
</div>