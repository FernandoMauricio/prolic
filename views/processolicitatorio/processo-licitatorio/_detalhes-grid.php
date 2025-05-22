<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var app\models\processolicitatorio\ProcessoLicitatorio $model */
?>

<div class="card shadow-sm border mb-3">
    <div class="card-header bg-light fw-bold text-primary">
        <i class="bi bi-info-circle me-1"></i> Detalhes do Processo
    </div>
    <div class="card-body p-3">
        <div class="row g-3">
            <div class="col-md-6">
                <strong>Segmento:</strong><br>
                <?= Html::encode($model->modalidadeValorlimite->ramo->ram_descricao ?? '(não definido)') ?>
            </div>
            <div class="col-md-6">
                <strong>Empresa(s):</strong><br>
                <?= Html::encode($model->prolic_empresa ?: '(não definido)') ?>
            </div>
            <div class="col-md-6">
                <strong>Valor Estimado:</strong><br>
                <?= Yii::$app->formatter->asCurrency($model->prolic_valorestimado) ?>
            </div>
            <div class="col-md-6">
                <strong>Valor Efetivo:</strong><br>
                <?= Yii::$app->formatter->asCurrency($model->prolic_valorefetivo) ?>
            </div>
            <div class="col-md-6">
                <strong>Comprador:</strong><br>
                <?= Html::encode($model->comprador->comp_descricao ?? '(não definido)') ?>
            </div>
            <div class="col-md-6">
                <strong>Códigos MXM:</strong><br>
                <?php
                $codigos = array_filter(array_map('trim', explode(';', $model->prolic_codmxm)));

                echo empty($codigos)
                    ? '<span class="text-danger fst-italic">(não definido)</span>'
                    : implode(' ', array_map(function ($c) {
                        $badge = Html::tag('span', $c . ' <i class="bi bi-box-arrow-up-right"></i>', [
                            'class' => 'badge fs-6 bg-primary-subtle text-primary border border-primary fw-normal me-1 text-decoration-hover-underline',
                            'title' => 'Ver detalhes da requisição',
                            'data-bs-toggle' => 'tooltip',
                        ]);
                        return Html::a(
                            Html::decode($badge),
                            ['mxm/reqcompra-rco/view', 'id' => $c],
                            [
                                'target' => '_blank',
                                'data-pjax' => '0',
                                'class' => 'text-decoration-none'
                            ]
                        );
                    }, $codigos));
                ?>
            </div>

            <div class="col-md-6">
                <strong>Centro de Custo:</strong><br>
                <?= Html::encode($model->prolic_centrocusto ?: '(não definido)') ?>
            </div>
            <div class="col-md-6">
                <strong>Elemento de Despesa:</strong><br>
                <?= Html::encode($model->prolic_elementodespesa ?: '(não definido)') ?>
            </div>
            <div class="col-12">
                <strong>Motivo:</strong><br>
                <div class="text-muted small">
                    <?= nl2br(Html::encode($model->prolic_motivo ?: '(não definido)')) ?>
                </div>
            </div>
        </div>
    </div>
</div>