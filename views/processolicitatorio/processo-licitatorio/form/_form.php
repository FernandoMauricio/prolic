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
    <?= Html::activeHiddenInput($model, 'id') ?>

    <!-- Linha para o formulário e a requisição -->
    <div class="row g-3">
        <!-- Formulário à esquerda -->
        <div class="col-lg-6">
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
                            <?= $this->render('_info', compact('form', 'model', 'destinos', 'situacao')) ?>
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
                            <h6 class="mb-0"><i class="bi bi-calendar-check me-2"></i> Datas</h6>
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
        <!-- Coluna Direita: Requisições (Consulta em CACHE REQUISIÇÕES DE COMPRA) -->
        <div class="col-lg-6 position-relative">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-bold d-flex align-items-center"
                    title="Somente requisições que já possuem pedido de compra serão exibidas">
                    <i class="bi bi-file-earmark-text me-2 fs-5"></i>
                    Consulta de Requisições Vinculadas (D-1)
                </div>

                <div id="loading-requisicoes">
                    <?= \app\widgets\SkeletonLoader::widget([
                        'blocks' => 4,
                        'lines' => 3,
                    ]) ?>
                </div>

                <div class="card-body p-3 d-none" id="conteudo-requisicoes">
                    <div id="accordion-requisicoes-container">
                        <!-- Conteúdo será carregado via AJAX -->
                    </div>
                </div>

                <div class="card-footer small text-muted px-3 py-2">
                    <div class="d-flex justify-content-between flex-wrap">
                        <div>
                            <i class="bi bi-info-circle me-1"></i>
                            <strong>Essas requisições são exibidas a partir do cache local</strong>.
                        </div>
                        <div class="text-end ms-auto">
                            Alternar entre <em>resumo</em> e <em>detalhes</em>.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coluna Direita: Requisições -->
        <!-- <div class="col-lg-6 position-relative">
            <div id="requisicao-feedback" class="alert d-none position-absolute top-0 start-50 translate-middle-x mt-2 z-1051 shadow"></div>
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-bold d-flex align-items-center"
                    title="Somente requisições que já possuem pedido de compra serão exibidas">
                    <i class="bi bi-file-earmark-text me-2 fs-5"></i>
                    Integração MXM: Requisições e Pedidos
                </div>
                <div class="card-body position-relative p-0">
                    <div id="requisicao-spinner" class="spinner-border text-primary position-absolute top-50 start-50 translate-middle d-none" role="status"></div>

                    <div id="sem-requisicoes" class="text-muted text-center py-4 d-none">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        Nenhuma requisição carregada.
                    </div>
                    <div class="accordion" id="accordionPreview"></div>
                </div>
                <div class="card-footer small text-muted px-3 py-2">
                    <div class="d-flex justify-content-between flex-wrap">
                        <div>
                            <i class="bi bi-info-circle me-1"></i>
                            <strong>Apenas requisições que já possuem pedido de compra</strong> serão exibidas acima.
                        </div>
                        <div class="text-end ms-auto">
                            Clique em uma requisição para visualizar os detalhes.
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
    </div>

    <?php ActiveForm::end(); ?>

    <?php //$this->registerJsFile('@web/js/processolicitatorio.js', ['depends' => [\yii\web\JqueryAsset::className()]]); 
    ?>

</div>