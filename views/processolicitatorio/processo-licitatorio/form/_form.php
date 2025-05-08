<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\processolicitatorio\ProcessoLicitatorio $model */
/** @var yii\bootstrap5\ActiveForm $form */

$this->registerCssFile('@web/css/requisicao-preview.css', [
    'depends' => [yii\bootstrap5\BootstrapAsset::class],
]);
?>

<div class="processo-licitatorio-form">
    <h1 class="mb-4"><i class="bi bi-file-earmark-check me-2"></i> Cadastro de Processo Licitatório</h1>
    <?php $form = ActiveForm::begin([
        'id'                 => 'processolicitatorio-form',
        'enableClientValidation' => true,
        'validateOnChange'   => true,
        'validateOnBlur'     => true,
        'validateOnType'     => true,
    ]); ?>

    <?= $form->errorSummary($model, ['class' => 'alert alert-danger']) ?>

    <!-- Linha para o formulário e a requisição -->
    <div class="row g-3">
        <!-- Formulário à esquerda -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i> Processo Licitatório</h5>
                </div>
                <div class="card-body">
                    <!-- Seção 'Informações' -->
                    <div class="mb-4 border">
                        <div class="card-header bg-soft-custom text-white border-bottom">
                            <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i> Informações</h6>
                        </div>
                        <div class="card-body">
                            <?= $this->render('_info', compact('form', 'model', 'ano', 'destinos', 'situacao')) ?>
                        </div>
                    </div>

                    <!-- Seção 'Modalidade' -->
                    <div class="mb-4 border">
                        <div class="card-header bg-soft-custom text-white border-bottom">
                            <h6 class="mb-0"><i class="bi bi-bar-chart-steps me-2"></i> Modalidade, Segmento, Recursos e Artigo</h6>
                        </div>
                        <div class="card-body">
                            <?= $this->render('_modalidade', compact('form', 'model', 'valorlimite', 'artigo', 'recurso', 'comprador')) ?>
                        </div>
                    </div>

                    <!-- Seção 'Valores Financeiros' -->
                    <div class="mb-4 border">
                        <div class="card-header bg-soft-custom text-white border-bottom">
                            <h6 class="mb-0"><i class="bi bi-currency-dollar me-2"></i> Valores Financeiros</h6>
                        </div>
                        <div class="card-body">
                            <?= $this->render('_valores', compact('form', 'model')) ?>
                        </div>
                    </div>

                    <!-- Seção 'Informações Complementares' -->
                    <div class="mb-4 border">
                        <div class="card-header bg-soft-custom text-white border-bottom">
                            <h6 class="mb-0"><i class="bi bi-diagram-3 me-2"></i> Informações Complementares</h6>
                        </div>
                        <div class="card-body">
                            <?= $this->render('_complementares', compact('form', 'model', 'centrocusto')) ?>
                        </div>
                    </div>

                    <!-- Seção 'Datas' -->
                    <div class="mb-4 border">
                        <div class="card-header bg-soft-custom text-white border-bottom">
                            <h6 class="mb-0"><i class="bi bi-calendar-check me-2"></i> Datas e Situação</h6>
                        </div>
                        <div class="card-body">
                            <?= $this->render('_datas', compact('form', 'model')) ?>
                        </div>
                    </div>

                    <!-- Seção 'Motivo do Processo' -->
                    <div class="mb-4 border">
                        <div class="card-header bg-soft-custom text-white border-bottom">
                            <h6 class="mb-0"><i class="bi bi-card-text me-2"></i> Motivo do Processo</h6>
                        </div>
                        <div class="card-body">
                            <?= $form->field($model, 'prolic_motivo')->textarea(['rows' => 3]) ?>
                        </div>
                    </div>

                    <!-- Seção 'Empresa(s) Participante(s)' -->
                    <div class="mb-4 border">
                        <div class="card-header bg-soft-custom text-white border-bottom">
                            <h6 class="mb-0"><i class="bi bi-buildings me-2"></i> Empresa(s) Participante(s)</h6>
                        </div>
                        <div class="card-body">
                            <?= $this->render('_empresas', compact('form', 'model')) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end mt-4">
                <?= Html::submitButton('<i class="bi bi-check-circle me-1"></i> Salvar Processo', [
                    'class' => 'btn btn-success btn-lg shadow-sm px-4'
                ]) ?>
            </div>
        </div>

        <!-- Container para preview à direita -->
        <div class="col-lg-4">
            <div id="requisicao-preview" class="mt-2">
                <div id="requisicao-feedback" class="requisicao-feedback mt-3 d-none"></div>

                <div class="accordion" id="accordionPreview">
                </div>
            </div>
        </div>

    </div>

    <?php ActiveForm::end(); ?>

    <?php $this->registerJsFile('@web/js/processolicitatorio.js', ['depends' => [\yii\web\JqueryAsset::className()]]); ?>

</div>