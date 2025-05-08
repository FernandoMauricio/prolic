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
        <!-- Coluna Esquerda -->
        <div class="col-lg-8">
            <!-- Detalhes do Processo -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light fw-bold">Detalhes do Processo</div>
                <div class="card-body">
                    <div class="row g-3">
                        <?php
                        // Itens principais sem Destinos
                        $fields = [
                            'Ano'        => $model->ano->an_ano,
                            'Código'     => $model->prolic_sequenciamodal . '/' . $model->ano->an_ano,
                            'Situação'   => Html::tag('span', Html::encode($model->situacao->sit_descricao), ['class' => 'badge bg-success']),
                            'Data Proc.' => Yii::$app->formatter->asDate($model->prolic_dataprocesso, 'php:d/m/Y'),
                            'Recurso'    => $model->recursos->rec_descricao,
                            'Comprador'  => $model->comprador->comp_descricao,
                            'Modalidade' => $model->modalidadeValorlimite->modalidade->mod_descricao,
                            'Ramo'       => $model->modalidadeValorlimite->ramo->ram_descricao,
                        ];
                        foreach ($fields as $label => $value):
                            $display = ($value !== null && $value !== '') ? $value : '<span class="text-danger fst-italic">(não definido)</span>';
                            $raw = ($label === 'Situação');
                        ?>
                            <div class="col-6 col-md-4">
                                <small class="text-muted"><?= $label ?></small>
                                <div class="fw-semibold">
                                    <?= $raw ? $display : Html::encode(strip_tags($display)) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <!-- Destinos como badges em linha flexível -->
                        <div class="col-12">
                            <small class="text-muted">Destino(s)</small>
                            <div class="d-flex flex-wrap align-items-center gap-2 fw-semibold">
                                <?php
                                $destinos = $model->getUnidades($model->prolic_destino);
                                if ($destinos) {
                                    foreach (explode(', ', $destinos) as $dest) {
                                        echo Html::tag('span', Html::encode($dest), ['class' => 'badge bg-secondary fs-7 px-2 py-1 fw-light']);
                                    }
                                } else {
                                    echo '<span class="text-danger fst-italic">(não definido)</span>';
                                }
                                ?>
                            </div>
                        </div>

                        <!-- Artigo -->
                        <div class="col-12">
                            <small class="text-muted">Artigo</small>
                            <div class="d-flex align-items-center gap-2 fw-semibold">
                                <?= Html::encode($model->artigo->art_descricao ?: '(não definido)') ?>
                                <?= Html::tag('span', Html::encode($model->artigo->art_tipo ?: '(não definido)'), ['class' => 'badge ' . ($model->artigo->art_tipo === 'Valor' ? 'bg-success' : 'bg-danger')]) ?>
                            </div>
                        </div>

                        <!-- Motivo -->
                        <div class="col-12">
                            <small class="text-muted">Motivo</small>
                            <div class="fw-normal text-wrap">
                                <?= $model->prolic_motivo
                                    ? nl2br(Html::encode($model->prolic_motivo))
                                    : '<span class="text-danger fst-italic">(não definido)</span>';
                                ?>
                            </div>
                        </div>

                        <!-- Empresas Participantes -->
                        <div class="col-12">
                            <small class="text-muted">Empresas Participantes</small>
                            <div class="d-flex flex-wrap align-items-center gap-2 fw-semibold">
                                <?php
                                $empresas = $model->prolic_empresa;
                                if ($empresas) {
                                    foreach (explode(', ', $empresas) as $emp) {
                                        echo Html::tag('span', Html::encode($emp), ['class' => 'badge bg-secondary fs-7 px-2 py-2 fw-light']);
                                    }
                                } else {
                                    echo '<span class="text-danger fst-italic">(não definido)</span>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Itens Complementares -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light fw-bold">Itens Complementares</div>
                <div class="card-body text-center">
                    <div class="row g-3">
                        <?php
                        $comp = [
                            'Cotações'        => $model->prolic_cotacoes,
                            'Centro de Custo' => $model->prolic_centrocusto,
                            'Despesa'         => $model->prolic_elementodespesa,
                        ];
                        foreach ($comp as $k => $v):
                            $displayComp = ($v !== null && $v !== '') ? $v : '<span class="text-danger fst-italic">(não definido)</span>';
                        ?>
                            <div class="col-md-4">
                                <small class="text-muted d-block"><?= $k ?></small>
                                <div class="fw-semibold"><?= strip_tags($displayComp) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Financeiro -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light fw-bold">Financeiro</div>
                <div class="card-body text-center">
                    <div class="row g-3">
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
                    Certame: <b><?= Yii::$app->formatter->asDate($model->prolic_datacertame, 'php:d/m/Y') ?> </b>|
                    Devolução: <b><?= Yii::$app->formatter->asDate($model->prolic_datadevolucao, 'php:d/m/Y') ?> </b>|
                    Homologação: <b><?= Yii::$app->formatter->asDate($model->prolic_datahomologacao, 'php:d/m/Y') ?></b>
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