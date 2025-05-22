<?php
// views/processolicitatorio/view.php

use yii\helpers\Html;
use yii\bootstrap5\Modal;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $model app\models\processolicitatorio\ProcessoLicitatorio */

$this->title = $model->prolic_sequenciamodal;
$this->params['breadcrumbs'][] = ['label' => 'Processos Licitatórios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Estilos e scripts
$this->registerCssFile('@web/css/requisicao-preview.css', ['depends' => [\yii\bootstrap5\BootstrapAsset::class]]);
$this->registerJsFile('@web/js/processolicitatorio.js', ['depends' => [\yii\web\JqueryAsset::class]]);
// $this->registerJs('var requisicoesSalvas = ' . json_encode($model->requisicoesCodmxm) . ';', View::POS_HEAD);
// $this->registerJs('carregarRequisicoesSalvas();', View::POS_READY);
$this->registerJsFile('@web/js/requisicoes-handler.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJs('var processoId = ' . (int) $model->id . ';', View::POS_HEAD);

?>

<div class="processo-licitatorio-view container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fs-3 fw-bold text-primary d-flex align-items-center gap-2 mb-0">
            <i class="bi bi-file-earmark-text fs-2"></i>
            Acompanhamento de <span class="text-dark"><?= Html::encode($this->title) . '/' . $model->ano ?></span>
        </h1>
        <div class="btn-toolbar">
            <?= Html::a('<i class="bi bi-arrow-left"></i>', ['index'], [
                'class' => 'btn btn-outline-secondary me-2',
                'title' => 'Voltar',
                'aria-label' => 'Voltar'
            ]) ?>
            <?= Html::a('<i class="bi bi-pencil"></i>', ['update', 'id' => $model->id], [
                'class' => 'btn btn-primary me-2',
                'title' => 'Editar',
                'aria-label' => 'Editar'
            ]) ?>
            <div class="btn-group">
                <?= Html::button('<i class="bi bi-three-dots-vertical"></i>', [
                    'class' => 'btn btn-outline-secondary dropdown-toggle',
                    'data-bs-toggle' => 'dropdown',
                    'aria-expanded' => false,
                    'title' => 'Mais ações',
                    'aria-label' => 'Mais ações',
                ]) ?>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><?= Html::button('<i class="bi bi-chat-left-text me-1"></i> Observação', [
                            'class' => 'dropdown-item',
                            'value' => Url::to(['observacoes', 'id' => $model->id]),
                            'id' => 'modalButton'
                        ]) ?></li>
                    <li><?= Html::button('<i class="bi bi-printer-fill me-1"></i> Gerar Capa', [
                            'class' => 'dropdown-item',
                            'value' => Url::to(['processolicitatorio/capas/gerar-relatorio', 'id' => $model->id]),
                            'id' => 'modalButton2'
                        ]) ?></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><?= Html::button('<i class="bi bi-printer me-1"></i> Imprimir', [
                            'class' => 'dropdown-item',
                            'onclick' => 'window.print()'
                        ]) ?></li>
                </ul>
            </div>
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
        <!-- Coluna Esquerda: Dados e Seções -->
        <div class="col-lg-6">
            <!-- Detalhes do Processo -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light fw-bold"><i class="bi bi-info-circle me-1"></i> Detalhes do Processo</div>
                <div class="card-body">
                    <div class="row g-3">
                        <?php
                        // Campos principais
                        $fields = [
                            'Ano'        => $model->ano,
                            'Código'     => $model->prolic_sequenciamodal . '/' . $model->ano,
                            'Situação'   => Html::tag('span', Html::encode($model->situacao->sit_descricao), ['class' => 'badge bg-success']),
                            'Data Proc.' => Yii::$app->formatter->asDate($model->prolic_dataprocesso, 'php:d/m/Y'),
                            'Recurso'    => $model->recursos->rec_descricao,
                            'Comprador'  => $model->comprador->comp_descricao,
                            'Modalidade' => $model->modalidadeValorlimite->modalidade->mod_descricao,
                            'Segmento'   => $model->modalidadeValorlimite->ramo->ram_descricao,
                            'Requisições' => function () use ($model) {
                                $codigos = array_filter(explode(';', $model->prolic_codmxm));
                                if (!empty($codigos)) {
                                    return implode(' ', array_map(
                                        fn($codigo) =>
                                        Html::tag('span', Html::encode($codigo), ['class' => 'badge bg-primary me-1']),
                                        $codigos
                                    ));
                                }
                                return '<span class="text-danger fst-italic">(não definido)</span>';
                            },

                        ];
                        foreach ($fields as $label => $value):
                            if ($value instanceof \Closure) {
                                $display = call_user_func($value);
                            } else {
                                $display = $value;
                            }

                            $display = ($display !== null && $display !== '') ? $display : '<span class="text-danger fst-italic">(não definido)</span>';
                            $raw = ($label === 'Situação' || $label === 'Requisições');
                        ?>
                            <div class="col-6 col-md-4">
                                <small class="text-muted"><?= $label ?></small>
                                <div class="fw-semibold">
                                    <?= $raw ? $display : Html::encode(strip_tags($display)) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <!-- Demandantes como badges em linha flexível -->
                        <div class="col-12">
                            <small class="text-muted">Demandante(s)</small>
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
                                <?= Html::tag('span', Html::encode($model->artigo->art_tipo ?: '(não definido)'), ['class' => 'badge ' . ($model->artigo->art_tipo === 'Valor' ? 'bg-info' : 'bg-warning text-dark')]) ?>
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

                        <!-- Empresa(s) Participante(s) -->
                        <div class="col-12">
                            <small class="text-muted">Empresa(s) Participante(s)</small>
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

            <!-- Financeiro -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light fw-bold"><i class="bi bi-currency-dollar me-1"></i> Financeiro</div>
                <div class="card-body text-center">
                    <div class="row g-3">
                        <?php
                        $fin = [
                            'Estimado' => $model->prolic_valorestimado,
                            'Efetivo'  => $model->prolic_valorefetivo,
                        ];
                        foreach ($fin as $k => $v):
                            $valor = ($v !== null && $v !== '') ? $v : 0.00;
                            // Se valor efetivo for menor que estimado, destaque em verde
                            if ($k === 'Efetivo' && $valor < $model->prolic_valorestimado) {
                                $cardClass = 'bg-success text-white';
                                $labelClass = 'text-white';
                            } else if ($k === 'Efetivo' && $valor > $model->prolic_valorestimado) {
                                $cardClass = 'bg-danger text-white';
                                $labelClass = 'text-white';
                            } else {
                                $cardClass = 'bg-light';
                                $labelClass = 'text-muted';
                            }
                        ?>
                            <div class="col-md-4">
                                <div class="<?= $cardClass ?> border rounded p-2">
                                    <small class="<?= $labelClass ?> d-block"><?= $k ?></small>
                                    <div class="fw-semibold <?= ($labelClass === 'text-white' ? 'text-white' : '') ?>">
                                        R$ <?= Yii::$app->formatter->asDecimal($valor, 2) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="card-footer small text-muted text-center">
                    Certame: <strong><?= Yii::$app->formatter->asDate($model->prolic_datacertame, 'php:d/m/Y') ?></strong> |
                    Devolução: <strong><?= Yii::$app->formatter->asDate($model->prolic_datadevolucao, 'php:d/m/Y') ?></strong> |
                    Homologação: <strong><?= Yii::$app->formatter->asDate($model->prolic_datahomologacao, 'php:d/m/Y') ?></strong>
                </div>
            </div>

            <!-- Itens Complementares -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light fw-bold"><i class="bi bi-list-columns me-1"></i> Itens Complementares</div>
                <div class="card-body text-center">
                    <div class="row g-3">
                        <?php
                        $comp = [
                            'Cotações'            => $model->prolic_cotacoes,
                            'Centro de Custo'     => $model->prolic_centrocusto,
                            'Elemento de Despesa' => $model->prolic_elementodespesa,
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

            <!-- Observações (exibe apenas se houver) -->
            <?php if (!empty($model->observacoes)): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light fw-bold"><i class="bi bi-chat-left-text me-1"></i> Observações</div>
                    <div class="card-body">
                        <?php foreach ($model->observacoes as $obs): ?>
                            <div class="mb-3">
                                <small class="text-muted"><?= Yii::$app->formatter->asDate($obs->obs_datacriacao, 'php:d/m/Y') ?> - <?= Html::encode($obs->obs_usuariocriacao) ?></small>
                                <p class="mb-1"><?= nl2br(Html::encode($obs->obs_descricao)) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
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


        <!-- Coluna Direita: Requisições (Consulta a API PEDIDOS DE COMPRA) -->
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
</div>