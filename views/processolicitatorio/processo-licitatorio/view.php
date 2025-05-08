<?php
// views/processolicitatorio/view.php

use yii\helpers\Html;
use yii\bootstrap5\Modal;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $model app\models\processolicitatorio\ProcessoLicitatorio */

$this->title = $model->prolic_sequenciamodal . '/' . $model->ano->an_ano;
$this->params['breadcrumbs'][] = ['label' => 'Processos Licitatórios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Estilos e scripts
$this->registerCssFile('@web/css/requisicao-preview.css', ['depends' => [\yii\bootstrap5\BootstrapAsset::class]]);
$this->registerJsFile('@web/js/processolicitatorio.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$cods = is_array($model->prolic_codmxm)
    ? $model->prolic_codmxm
    : (strlen($model->prolic_codmxm) ? explode(';', $model->prolic_codmxm) : []);
$this->registerJs('var requisicoesSalvas = ' . json_encode($cods) . ';', View::POS_HEAD);
?>

<div class="processo-licitatorio-view container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fs-3 fw-bold text-primary d-flex align-items-center gap-2 mb-0">
            <i class="bi bi-file-earmark-text fs-2"></i>
            Acompanhamento de <span class="text-dark"><?= Html::encode($this->title) ?></span>
        </h1>
        <div class="btn-group">
            <?= Html::a('← Voltar', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
            <?= Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::button('📝 Obs', ['value' => Url::to(['observacoes', 'id' => $model->id]), 'class' => 'btn btn-success', 'id' => 'modalButton']) ?>
            <?= Html::button('🖨 Capa', ['value' => Url::to(['/capas/gerar-relatorio', 'id' => $model->id]), 'class' => 'btn btn-warning', 'id' => 'modalButton2']) ?>
        </div>
    </div>

    <!-- Modais -->
    <?php Modal::begin(['title' => '<h5>Observação - Processo ' . $model->id . '</h5>', 'id' => 'modal', 'size' => 'modal-lg']); ?>
    <div id="modalContent"></div>
    <?php Modal::end(); ?>
    <?php Modal::begin(['title' => '<h5>Capa - Processo ' . $model->id . '</h5>', 'id' => 'modal2', 'size' => 'modal-lg']); ?>
    <div id="modalContent2"></div>
    <?php Modal::end(); ?>

    <div class="row g-4">
        <!-- Lado esquerdo: Dados Principais, Itens e Financeiro -->
        <div class="col-lg-8">
            <!-- Detalhes do Processo -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light fw-bold">Detalhes do Processo</div>
                <div class="card-body">
                    <div class="row g-3">
                        <?php
                        $items = [
                            'Ano'         => $model->ano->an_ano,
                            'Código'      => $model->prolic_sequenciamodal . '/' . $model->ano->an_ano,
                            'Situação'    => Html::tag('span', Html::encode($model->situacao->sit_descricao), ['class' => 'badge bg-info']),
                            'Data Proc.'  => Yii::$app->formatter->asDate($model->prolic_dataprocesso, 'php:d/m/Y'),
                            'Recurso'     => Html::encode($model->recursos->rec_descricao),
                            'Comprador'   => Html::encode($model->comprador->comp_descricao),
                        ];
                        foreach ($items as $label => $value): ?>
                            <div class="col-6 col-md-4">
                                <small class="text-muted"><?= $label ?></small>
                                <div class="fw-semibold"><?= $value ?></div>
                            </div>
                        <?php endforeach; ?>

                        <div class="col-12">
                            <small class="text-muted">Destino(s)</small>
                            <div class="fw-normal text-wrap"><?= nl2br(Html::encode(str_replace(', ', "
", $model->getUnidades($model->prolic_destino)))) ?></div>
                        </div>

                        <div class="col-12">
                            <small class="text-muted">Artigo</small>
                            <div class="fw-normal d-flex align-items-center gap-2">
                                <?= Html::encode($model->artigo->art_descricao) ?>
                                <?= Html::tag('span', Html::encode($model->artigo->art_tipo), ['class' => 'badge ' . ($model->artigo->art_tipo == 'Valor' ? 'bg-success' : 'bg-danger')]) ?>
                            </div>
                        </div>

                        <div class="col-12">
                            <small class="text-muted">Motivo</small>
                            <div class="fw-normal text-wrap"><?= nl2br(Html::encode($model->prolic_motivo)) ?></div>
                        </div>

                        <div class="col-12">
                            <small class="text-muted">Empresas Participantes</small>
                            <div class="fw-normal text-wrap"><?= nl2br(Html::encode($model->prolic_empresa)) ?></div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Itens Complementares -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light fw-bold">Itens Complementares</div>
                <div class="card-body">
                    <div class="row g-3 text-center">
                        <?php
                        $comp = [
                            'Cotações'        => $model->prolic_cotacoes,
                            'Centro de Custo' => $model->prolic_centrocusto,
                            'Despesa'         => $model->prolic_elementodespesa,
                        ];
                        foreach ($comp as $k => $v): ?>
                            <div class="col-md-4">
                                <small class="text-muted d-block"><?= $k ?></small>
                                <div class="fw-semibold"><?= Html::encode($v ?: '0,00') ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Financeiro -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light fw-bold">Financeiro</div>
                <div class="card-body">
                    <div class="row g-3 text-center">
                        <?php
                        $fin = [
                            'Estimado' => $model->prolic_valorestimado,
                            'Aditivo'  => $model->prolic_valoraditivo,
                            'Efetivo'  => $model->prolic_valorefetivo,
                        ];
                        foreach ($fin as $k => $v):
                            $valor = ($v !== null && $v !== '') ? $v : 0.00;
                        ?>
                            <div class="col-md-4">
                                <div class="bg-light border rounded p-2">
                                    <small class="text-muted d-block"><?= $k ?></small>
                                    <div class="fw-semibold">R$ <?= Yii::$app->formatter->asDecimal($valor, 2) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="card-footer small text-muted text-center">
                    Certame: <?= Yii::$app->formatter->asDate($model->prolic_datacertame, 'php:d/m/Y') ?> |
                    Devolução: <?= Yii::$app->formatter->asDate($model->prolic_datadevolucao, 'php:d/m/Y') ?> |
                    Homologação: <?= Yii::$app->formatter->asDate($model->prolic_datahomologacao, 'php:d/m/Y') ?>
                </div>
            </div>
        </div>

        <!-- Coluna Direita: Requisições -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light fw-bold"><i class="bi bi-list-ul me-1"></i> Requisições</div>
                <div class="card-body position-relative" id="requisicao-preview">
                    <div id="requisicao-spinner" class="spinner-border text-primary position-absolute top-50 start-50 translate-middle d-none" role="status"></div>
                    <div class="accordion" id="accordionPreview"></div>
                </div>
            </div>
        </div>
    </div>
</div>