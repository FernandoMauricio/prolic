<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\editable\Editable;
use yii\bootstrap\Modal;
use kartik\widgets\DatePicker;

use app\models\base\Ano;
use app\models\base\ModalidadeValorlimite;
use app\models\base\Comprador;
use app\models\base\Artigo;
use app\models\base\Situacao;
use app\models\base\Modalidade;

/* @var $this yii\web\View */
/* @var $searchModel app\models\processolicitatorio\ProcessoLicitatorioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Acompanhamento de Processos Licitatórios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="processo-licitatorio-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::button('Novo Processo Licitatório', ['value'=> Url::to(['processolicitatorio/processo-licitatorio/create']), 'class' => 'btn btn-success', 'id'=>'modalButton']) ?>
    </p>

<?php
    Modal::begin([
        'header' => '<h3>Novo Processo Licitatório</h3>',
        'id' => 'modal',
        'size' => 'modal-lg',
        ]);
    echo "<div id='modalContent'></div>";
    Modal::end();
?>

<?php

$gridColumns = [
        [
            'attribute'=>'ano_id', 
            'width'=>'310px',
            'value'=>function ($model, $key, $index, $widget) { 
                return $model->ano->an_ano;
            },
            'filterType'=>GridView::FILTER_SELECT2,
            'filter'=>ArrayHelper::map(Ano::find()->orderBy('id')->asArray()->all(), 'id', 'an_ano'), 
            'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
            ],
            'filterInputOptions'=>['placeholder'=>'Ano...'],
            'group'=>true,  // enable grouping
        ],

        [
            'attribute' => 'prolic_dataprocesso',
            'format' => ['date', 'php:d/m/Y'],
            'width' => '190px',
            'hAlign' => 'center',
            'filter'=> DatePicker::widget([
            'model' => $searchModel, 
            'attribute' => 'prolic_dataprocesso',
            'pluginOptions' => [
                 'autoclose'=>true,
                 'format' => 'yyyy-mm-dd',
                ]
            ])
        ],

        [
            'attribute'=>'modalidade', 
            'width'=>'250px',
            'value'=>function ($model, $key, $index, $widget) { 
                return $model->modalidadeValorlimite->modalidade->mod_descricao;
            },
            'filterType'=>GridView::FILTER_SELECT2,
            'filter'=>ArrayHelper::map(Modalidade::find()->where(['mod_status' => 1])->asArray()->all(), 'id', 'mod_descricao'), 
            'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
            ],
            'filterInputOptions'=>['placeholder'=>'Modalidade...'],
            'group'=>true,  // enable grouping
            'subGroupOf'=>1 // supplier column index is the parent group
        ],

        [
            'attribute'=>'modalidade_valorlimite_id', 
            'width'=>'250px',
            'value'=>function ($model, $key, $index, $widget) { 
                return $model->modalidadeValorlimite->ramo->ram_descricao;
            },
            'filterType'=>GridView::FILTER_SELECT2,
            'filter'=>ArrayHelper::map(ModalidadeValorlimite::find()->innerJoinWith('ramo')->where(['status' => 1])->andWhere(['!=','homologacao_usuario', ''])->orderBy('id')->asArray()->all(), 'id', 'ramo.ram_descricao'), 
            'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
            ],
            'filterInputOptions'=>['placeholder'=>'Ramo...'],
            'group'=>true,  // enable grouping
            'subGroupOf'=>1 // supplier column index is the parent group
        ],

        'id',
        [
            'attribute' => 'prolic_sequenciamodal',
            'value'=>function ($model, $key, $index, $widget) { 
                return $model->prolic_sequenciamodal;
            },
        ],
        'prolic_codmxm',
        'prolic_objeto:ntext',

        [
            'attribute'=>'artigo_id', 
            'width'=>'250px',
            'value'=>function ($model, $key, $index, $widget) { 
                return '('.$model->artigo->art_tipo.') - ' . $model->artigo->art_descricao;
            },
            'filterType'=>GridView::FILTER_SELECT2,
            'filter'=>ArrayHelper::map(Artigo::find()->where(['art_status' => 1])->orderBy('id')->asArray()->all(), 'id', 'art_descricao'), 
            'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
            ],
            'filterInputOptions'=>['placeholder'=>'Artigo...'],
        ],

        [
            'attribute'=>'comprador_id', 
            'width'=>'200px',
            'value'=>function ($model, $key, $index, $widget) { 
                return $model->comprador->comp_descricao;
            },
            'filterType'=>GridView::FILTER_SELECT2,
            'filter'=>ArrayHelper::map(Comprador::find()->where(['comp_status' => 1])->orderBy('comp_descricao')->asArray()->all(), 'id', 'comp_descricao'), 
            'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
            ],
            'filterInputOptions'=>['placeholder'=>'Comprador...'],
        ],

        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'situacao_id',
            'value'=>function ($model, $key, $index, $widget) { 
                return $model->situacao->sit_descricao;
            },
            'readonly'=>function($model, $key, $index, $widget) {
                return (!$model->situacao_id); // do not allow editing of inactive records
            },
            'filterType'=>GridView::FILTER_SELECT2,
            'filter'=>ArrayHelper::map(Situacao::find()->where(['sit_status' => 1])->orderBy('id')->asArray()->all(), 'id', 'sit_descricao'),
            'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
            ],
                'filterInputOptions'=>['placeholder'=>'Situação...'],
            //CAIXA DE ALTERAÇÕES DA SITUAÇÃO
            'editableOptions' => [
                'header' => 'Situação',
                'data'=>ArrayHelper::map(Situacao::find()->where(['sit_status' => 1])->orderBy('id')->asArray()->all(), 'id', 'sit_descricao'),
                'inputType' => Editable::INPUT_DROPDOWN_LIST,
            ],          
        ],

        // 'prolic_datahomologacao',
        // 'prolic_datacertame',
        // 'prolic_datacriacao',

        [
            'attribute' => 'ciclototal',
            'value'=>function ($model, $key, $index, $widget) { 
                    $data_inicio = new DateTime($model->prolic_datahomologacao);
                    $data_fim = new DateTime($model->prolic_dataprocesso);
                    $dateInterval = $data_inicio->diff($data_fim);
                return $dateInterval->days;
            },
        ],

        [
            'attribute' => 'ciclocertame',
            'value'=>function ($model, $key, $index, $widget) { 
                    $data_inicio = new DateTime($model->prolic_datahomologacao);
                    $data_fim = new DateTime($model->prolic_datacertame);
                    $dateInterval = $data_inicio->diff($data_fim);
                return $dateInterval->days;
            },
        ],

        ['class' => 'yii\grid\ActionColumn',
        'template' => ' {observacoes} {view} {update} {delete}',
        'contentOptions' => ['style' => 'width: 5%;'],
        'buttons' => [

            //VISUALIZAR
            'view' => function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-eye-open"></span> ', $url, [
                            'class'=>'btn btn-default btn-xs',
                            'title' => Yii::t('app', 'Visualizar'),
                        ]);
            },

            //SOMENTE APARECERÁ CASO O PROCESSO NÃO ESTEJA CANCELADO
            'update' => function ($url, $model) {
                return $model->situacao_id !== 7 ? Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'class'=>'btn btn-primary btn-xs',
                    'title' => Yii::t('app', 'Atualizar'),
                       ]): '';
            },

            //DELETAR
            'delete' => function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span> ', $url, [
                            'class'=>'btn btn-danger btn-xs',
                            'title' => Yii::t('app', 'Deletar'),
                            'data' =>  [
                                            'confirm' => 'Você tem CERTEZA que deseja EXCLUIR esse item?',
                                            'method' => 'post',
                                       ],
                        ]);
                },  
            ],
        ],
    ];
 ?>

    <?php Pjax::begin(); ?>

    <?php 
    echo GridView::widget([
    'dataProvider'=>$dataProvider,
    'filterModel'=>$searchModel,
    'columns'=>$gridColumns,
    'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
    'headerRowOptions'=>['class'=>'kartik-sheet-style'],
    'filterRowOptions'=>['class'=>'kartik-sheet-style'],
    'pjax'=>true, // pjax is set to always true for this demo

    'beforeHeader'=>[
        [
            'columns'=>[
                ['content'=>'Detalhes dos Processos', 'options'=>['colspan'=>13, 'class'=>'text-center warning']], 
                ['content'=>'Área de Ações', 'options'=>['colspan'=>1, 'class'=>'text-center warning']], 
            ],
        ]
    ],
        'hover' => true,
        'panel' => [
        'type'=>GridView::TYPE_PRIMARY,
        'heading'=> '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Listagem - Processos Licitatórios</h3>',
    ],
]);
    ?>
    <?php Pjax::end(); ?>

</div>
