<?php

use kartik\grid\GridView;
use kartik\editable\Editable;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\base\Modalidade;
use app\models\base\ModalidadeValorlimite;
use app\models\base\Empresa;
use app\models\base\Comprador;
use app\models\base\Situacao;
use yii\helpers\StringHelper;
use kartik\grid\ActionColumn;

return [
    [
        'class' => 'kartik\grid\ExpandRowColumn',
        'width' => '50px',
        'value' => function () {
            return GridView::ROW_COLLAPSED;
        },
        'detail' => function ($model) {
            return Yii::$app->controller->renderPartial('_detalhes-grid', [
                'model' => $model,
            ]);
        },
        'expandOneOnly' => true,
    ],

    [
        'attribute' => 'prolic_codprocesso',
        'label' => 'Processo',
        'width' => '80px',
        'hAlign' => 'center',
    ],

    [
        'attribute' => 'prolic_sequenciamodal',
        'label' => 'Número<br>Sequencial <i class="bi bi-info-circle text-primary ms-1" data-bs-toggle="tooltip" title="Número sequencial do processo dentro da modalidade."></i>',
        'encodeLabel' => false,
        'width' => '80px',
        'hAlign' => 'center',
    ],

    [
        'attribute' => 'modalidade',
        'value' => fn($model) => $model->modalidadeValorlimite->modalidade->mod_descricao,
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Modalidade::find()->where(['mod_status' => 1])->asArray()->all(),
            'id',
            'mod_descricao'
        ),
        'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true]],
        'filterInputOptions' => ['placeholder' => 'Modalidade...'],
        'width' => '120px',
    ],

    [
        'label' => 'Tipo de<br> Artigo <i class="bi bi-info-circle text-primary ms-1" data-bs-toggle="tooltip" title="Define se o artigo se refere a uma situação específica ou modalidade genérica."></i>',
        'encodeLabel' => false,
        'width' => '50px',
        'attribute' => 'artigo_id',
        'format' => 'raw',
        'value' => function ($model) {
            $tipo = $model->artigo->art_tipo ?? '(não definido)';
            $badgeClass = '';

            if (stripos($tipo, 'situação') !== false) {
                $badgeClass = 'warning text-dark';
            } elseif ($tipo !== '(não definido)') {
                $badgeClass = 'success';
            } else {
                $badgeClass = 'secondary';
            }

            return "<span class='badge bg-$badgeClass'>$tipo</span>";
        },
    ],

    [
        'attribute' => 'prolic_objeto',
        'format' => 'raw',
        'value' => function ($model) {
            $textoCompleto = $model->prolic_objeto;
            $resumo = \yii\helpers\StringHelper::truncate($textoCompleto, 20);
            return Html::tag('span', Html::encode($resumo), [
                'title' => $textoCompleto,
                'data-bs-toggle' => 'tooltip',
                'data-bs-placement' => 'top',
                'style' => 'cursor: help;',
            ]);
        },
        'contentOptions' => ['style' => 'max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'],
    ],


    [
        'attribute' => 'prolic_valorestimado',
        'label' => 'Estimado',
        'format' => 'currency',
        'width' => '100px',
        'hAlign' => 'right',
    ],

    [
        'attribute' => 'prolic_valorefetivo',
        'label' => 'Efetivo',
        'format' => 'currency',
        'width' => '100px',
        'hAlign' => 'right',
    ],

    [
        'attribute' => 'situacao_id',
        'value' => fn($model) => $model->situacao->sit_descricao,
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Situacao::find()->where(['sit_status' => 1])->asArray()->all(), 'id', 'sit_descricao'),
        'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true]],
        'filterInputOptions' => ['placeholder' => 'Situação...'],
        'width' => '120px',
        'format' => 'raw',
    ],
    [
        'attribute' => 'ciclototal',
        'label' => 'Ciclo<br>Total <i class="bi bi-info-circle text-primary ms-1" data-bs-toggle="tooltip" title="Dias entre a Data do Processo e a Homologação."></i>',
        'encodeLabel' => false,
        'format' => 'raw',
        'value' => fn($model) => $model->cicloTotal,
        'width' => '80px',
        'hAlign' => 'center',
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
    ],
    [
        'attribute' => 'ciclocertame',
        'label' => 'Ciclo<br>Certame <i class="bi bi-info-circle text-primary ms-1" data-bs-toggle="tooltip" title="Dias entre a Data do Certame e a Homologação."></i>',
        'encodeLabel' => false,
        'format' => 'raw',
        'value' => fn($model) => $model->cicloCertame,
        'width' => '80px',
        'hAlign' => 'center',
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
    ],

    [
        'class' => ActionColumn::class,
        'template' => '{view} {update}',
        'buttons' => [
            'view' => fn($url) => Html::a('<i class="bi bi-eye"></i>', $url, [
                'title' => 'Visualizar',
                'class' => 'btn btn-sm btn-outline-primary me-1',
                'data-pjax' => '0',
            ]),
            'update' => function ($url) {
                if (\app\components\helpers\RbacHelper::isAdmin()) {
                    return Html::a('<i class="bi bi-pencil"></i>', $url, [
                        'title' => 'Editar',
                        'class' => 'btn btn-sm btn-outline-secondary me-1',
                        'data-pjax' => '0',
                    ]);
                }
                return '';
            },
        ],
        'header' => 'Ações',
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
    ],

];
