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

return [

    [
        'attribute' => 'ano_id',
        'group' => true,
        'groupedRow' => true, // mostra o ano como uma linha inteira separadora
        'groupOddCssClass' => 'kv-grouped-row', // classe Bootstrap opcional
        'groupEvenCssClass' => 'kv-grouped-row', // mesma coisa aqui
        'value' => fn($model) => $model->ano->an_ano,
        'filterType' => GridView::FILTER_SELECT2,
        'headerOptions' => ['style' => 'display:none'], // não mostrar o cabeçalho da coluna
    ],

    [
        'attribute' => 'prolic_dataprocesso',
        'format' => ['date', 'php:d/m/Y'],
        'filterType' => GridView::FILTER_DATE,
        'width' => '120px',
        'hAlign' => 'center',
    ],

    [
        'attribute' => 'modalidade',
        'value' => fn($model) => $model->modalidadeValorlimite->modalidade->mod_descricao,
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Modalidade::find()->where(['mod_status' => 1])->asArray()->all(), 'id', 'mod_descricao'),
        'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true]],
        'filterInputOptions' => ['placeholder' => 'Modalidade...'],
        'width' => '5%'
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
        'filterInputOptions' => ['placeholder' => 'Ramo...'],
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
        'label' => 'Req. MXM',
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

    ['attribute' => 'prolic_valorestimado', 'label' => 'V. Estimado', 'format' => 'currency'],
    ['attribute' => 'prolic_valorefetivo', 'label' => 'V. Efetivo', 'format' => 'currency'],

    [
        'attribute' => 'prolic_objeto',
        'format' => 'ntext',
        'value' => fn($model) => StringHelper::truncate($model->prolic_objeto, 50),
        'width' => '280px'
    ],

    [
        'attribute' => 'artigo_id',
        'value' => fn($model) => '(' . $model->artigo->art_tipo . ') - ' . $model->artigo->art_descricao,
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Artigo::find()->where(['art_status' => 1])->orderBy('id')->asArray()->all(), 'id', 'art_descricao'),
        'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true]],
        'filterInputOptions' => ['placeholder' => 'Artigo...'],
        'width' => '250px'
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
        'attribute' => 'prolic_datahomologacao',
        'label' => 'Homologação',
        'format' => ['date', 'php:d/m/Y'],
        'filterType' => GridView::FILTER_DATE,
        'width' => '120px',
        'hAlign' => 'center',
    ],

    [
        'class' => 'yii\grid\ActionColumn',
        'template' => '{view} {update}',
        'header' => 'Ações',
        'headerOptions' => ['style' => 'text-align: center; width: 80px;'],
        'contentOptions' => ['style' => 'text-align: center; white-space: nowrap;'],
        'buttons' => [
            'view' => fn($url, $model) => Html::a(
                '<i class="glyphicon glyphicon-eye-open"></i>',
                $url,
                ['class' => 'btn btn-default btn-xs', 'title' => 'Visualizar', 'data-toggle' => 'tooltip']
            ),
            'update' => fn($url, $model) =>
            $model->situacao_id !== 7
                ? Html::a(
                    '<i class="glyphicon glyphicon-pencil"></i>',
                    $url,
                    ['class' => 'btn btn-primary btn-xs', 'title' => 'Atualizar', 'data-toggle' => 'tooltip']
                )
                : '',
        ],
    ],

];
