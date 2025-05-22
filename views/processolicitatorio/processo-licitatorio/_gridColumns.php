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
        'attribute' => 'prolic_dataprocesso',
        'format'    => ['date', 'php:d/m/Y'],
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => [
                'autoclose'     => true,
                'format'        => 'dd/mm/yyyy',
                'todayHighlight' => true,
            ],
        ],
        'filterInputOptions' => [
            'placeholder' => 'dd/mm/aaaa',
            'class'       => 'form-control'
        ],
        'width' => '150px',
        'hAlign' => 'center',
    ],

    [
        'attribute' => 'prolic_codprocesso',
        'label' => 'Processo',
        'width' => '80px',
        'hAlign' => 'center',
    ],

    [
        'attribute' => 'prolic_sequenciamodal',
        'label' => 'Código',
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
        'attribute' => 'prolic_objeto',
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
                'data-pjax' => '0'
            ]),
            'update' => fn($url) => Html::a('<i class="bi bi-pencil"></i>', $url, [
                'title' => 'Editar',
                'class' => 'btn btn-sm btn-outline-secondary me-1',
                'data-pjax' => '0'
            ]),
        ],
        'header' => 'Ações',
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
    ],
];
