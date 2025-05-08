<?php

use kartik\grid\GridView;
use kartik\editable\Editable;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\base\Ano;
use app\models\base\Modalidade;
use app\models\base\ModalidadeValorlimite;
use app\models\base\Empresa;
use app\models\base\Artigo;
use app\models\base\Comprador;
use app\models\base\Situacao;
use yii\helpers\StringHelper;
use kartik\grid\ActionColumn;

return [

    [
        'attribute' => 'ano_id',
        'group' => true,
        'groupedRow' => true, // mostra o ano como uma linha inteira separadora
        'groupOddCssClass' => 'kv-grouped-row', // classe Bootstrap opcional
        'groupEvenCssClass' => 'kv-grouped-row', // mesma coisa aqui
        'value' => fn($model) => $model->ano->an_ano,
        'filterType' => GridView::FILTER_SELECT2,
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
        'width' => '350px',
        'hAlign' => 'center',
    ],

    [
        'attribute'           => 'modalidade',
        'value'               => fn($model) => $model->modalidadeValorlimite->modalidade->mod_descricao,
        'filterType'          => GridView::FILTER_SELECT2,
        'filter'              => ArrayHelper::map(
            Modalidade::find()->where(['mod_status' => 1])->asArray()->all(),
            'id',
            'mod_descricao'
        ),
        'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true]],
        'filterInputOptions'  => ['placeholder' => 'Modalidade...'],
        'width'               => '5%',
    ],

    [
        'attribute' => 'modalidade_valorlimite_id',
        'format' => 'raw',
        'value' => fn($model) => Html::tag(
            'span',
            \yii\helpers\StringHelper::truncate($model->modalidadeValorlimite->ramo->ram_descricao, 30),
            ['title' => $model->modalidadeValorlimite->ramo->ram_descricao]
        ),
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            ModalidadeValorlimite::find()
                ->innerJoinWith('ramo')
                ->where(['status' => 1])
                ->andWhere(['!=', 'homologacao_usuario', ''])
                ->orderBy('id')
                ->asArray()
                ->all(),
            'id',
            'ramo.ram_descricao'
        ),
        'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true]],
        'filterInputOptions' => ['placeholder' => 'Segmento...'],
        'width' => '160px',
    ],

    [
        'attribute' => 'prolic_empresa',
        'value' => fn($model) => StringHelper::truncate($model->prolic_empresa, 50),
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Empresa::find()->where(['emp_status' => 1])->asArray()->all(), 'emp_descricao', 'emp_descricao'),
        'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true]],
        'filterInputOptions' => ['placeholder' => 'Empresa...'],
        'subGroupOf' => 1,
        'width' => '140px'
    ],

    ['attribute' => 'prolic_codprocesso', 'label' => 'Processo'],
    ['attribute' => 'prolic_sequenciamodal'],
    [
        'attribute' => 'prolic_codmxm',
        'label' => 'Requisição <br>MXM',
        'encodeLabel' => false,
        'format' => 'raw',
        'value' => function ($model) {
            $itens = array_filter(array_map('trim', explode(';', $model->prolic_codmxm)));
            return implode(' ', array_map(
                fn($item) =>
                Html::tag('span', Html::encode($item), ['class' => 'label label-info', 'style' => 'margin-right:2px;']),
                $itens
            ));
        },
        'width' => '120px',
        'contentOptions' => ['style' => 'white-space: normal; word-break: break-word;'],
    ],

    [
        'attribute' => 'prolic_valorestimado',
        'label' => 'Valor <br>Estimado',
        'encodeLabel' => false,
        'format' => 'currency'
    ],

    [
        'attribute' => 'prolic_valorefetivo',
        'label' => 'Valor <br>Efetivo',
        'encodeLabel' => false,
        'format' => 'currency'
    ],

    [
        'attribute' => 'prolic_objeto',
        'format' => 'ntext',
        'value' => fn($model) => StringHelper::truncate($model->prolic_objeto, 50),
        'width' => '280px'
    ],

    [
        'attribute' => 'comprador_id',
        'value' => fn($model) => $model->comprador->comp_descricao,
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Comprador::find()->where(['comp_status' => 1])->orderBy('comp_descricao')->asArray()->all(), 'id', 'comp_descricao'),
        'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true]],
        'filterInputOptions' => ['placeholder' => 'Comprador...'],
        'width' => '150px'
    ],

    [
        'class' => 'kartik\grid\EditableColumn',
        'attribute' => 'situacao_id',
        'value' => fn($model) => $model->situacao->sit_descricao,
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Situacao::find()->where(['sit_status' => 1])->orderBy('id')->asArray()->all(), 'id', 'sit_descricao'),
        'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true]],
        'filterInputOptions' => ['placeholder' => 'Situação...'],
        'editableOptions' => [
            'header' => 'Situação',
            'inputType' => Editable::INPUT_DROPDOWN_LIST,
            'data' => ArrayHelper::map(Situacao::find()->where(['sit_status' => 1])->orderBy('id')->asArray()->all(), 'id', 'sit_descricao'),
        ],
        'width' => '120px'
    ],

    [
        'attribute' => 'ciclototal',
        'label' => 'Ciclo<br>Total',
        'encodeLabel' => false,
        'format' => 'raw',
        'value' => fn($model) => $model->cicloTotal,
        'width' => '80px',
        'headerOptions' => ['style' => 'text-align: center;']
    ],
    [
        'attribute' => 'ciclocertame',
        'label' => 'Ciclo<br>Certame',
        'encodeLabel' => false,
        'format' => 'raw',
        'value' => fn($model) => $model->cicloCertame,
        'width' => '80px',
        'headerOptions' => ['style' => 'text-align: center;']
    ],

    [
        'class' => ActionColumn::class,
        'template' => '{view} {update}',
        'buttons' => [
            'view' => function ($url) {
                return Html::a('<i class="bi bi-eye"></i>', $url, [
                    'title' => 'Visualizar',
                    'class' => 'btn btn-sm btn-outline-primary me-1',
                    'data-pjax' => '0'
                ]);
            },
            'update' => function ($url) {
                return Html::a('<i class="bi bi-pencil"></i>', $url, [
                    'title' => 'Editar',
                    'class' => 'btn btn-sm btn-outline-secondary me-1',
                    'data-pjax' => '0'
                ]);
            },
        ],
        'header' => 'Ações',
        'headerOptions' => ['class' => 'text-center'],
        'contentOptions' => ['class' => 'text-center'],
    ],

];
