<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;

use app\models\base\Ano;
use app\models\base\ModalidadeValorlimite;
use app\models\base\Comprador;
use app\models\base\Artigo;
use app\models\base\Situacao;

/* @var $this yii\web\View */
/* @var $searchModel app\models\processolicitatorio\ProcessoLicitatorioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Acompanhmento de Processos Licitatórios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="processo-licitatorio-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Novo Processo Licitatório', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

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
            'filterInputOptions'=>['placeholder'=>'Selecione o Ano'],
            'group'=>true,  // enable grouping
        ],

        [
            'attribute'=>'modalidade', 
            'width'=>'250px',
            'value'=>function ($model, $key, $index, $widget) { 
                return $model->modalidadeValorlimite->modalidade->mod_descricao;
            },
            'filterType'=>GridView::FILTER_SELECT2,
            'filter'=>ArrayHelper::map(ModalidadeValorlimite::find()->innerJoinWith('modalidade')->where(['status' => 1])->andWhere(['!=','homologacao_usuario', ''])->orderBy('id')->asArray()->all(), 'id', 'modalidade.mod_descricao'), 
            'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
            ],
            'filterInputOptions'=>['placeholder'=>'Selecione a Modalidade...'],
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
            'filterInputOptions'=>['placeholder'=>'Selecione o Ramo...'],
            'group'=>true,  // enable grouping
            'subGroupOf'=>1 // supplier column index is the parent group
        ],

        'prolic_codmxm',
        'prolic_sequenciamodal',
        'prolic_objeto:ntext',
        'prolic_centrocusto:ntext',
        'prolic_destino:ntext',

        [
            'attribute'=>'artigo_id', 
            'width'=>'250px',
            'value'=>function ($model, $key, $index, $widget) { 
                return $model->artigo->art_descricao;
            },
            'filterType'=>GridView::FILTER_SELECT2,
            'filter'=>ArrayHelper::map(Artigo::find()->where(['art_status' => 1])->orderBy('id')->asArray()->all(), 'id', 'art_descricao'), 
            'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
            ],
            'filterInputOptions'=>['placeholder'=>'Selecione o Artigo...'],
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
            'filterInputOptions'=>['placeholder'=>'Selecione o Comprador...'],
        ],

        [
            'attribute'=>'situacao_id', 
            'width'=>'250px',
            'value'=>function ($model, $key, $index, $widget) { 
                return $model->situacao->sit_descricao;
            },
            'filterType'=>GridView::FILTER_SELECT2,
            'filter'=>ArrayHelper::map(Situacao::find()->where(['sit_status' => 1])->orderBy('id')->asArray()->all(), 'id', 'sit_descricao'), 
            'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
            ],
            'filterInputOptions'=>['placeholder'=>'Selecione a Situação...'],
        ],

            ['class' => 'yii\grid\ActionColumn'],
    ];
 ?>

<?php
            //'prolic_cotacoes',
            //'prolic_elementodespesa:ntext',
            //'prolic_valorestimado',
            //'prolic_valoraditivo',
            //'prolic_valorefetivo',
            //'prolic_datacertame',
            //'prolic_datadevolucao',
            //'prolic_datahomologacao',
            //'prolic_motivo:ntext',
            //'prolic_empresa',
            //'ramo_descricao',
            //'prolic_usuariocriacao',
            //'prolic_datacriacao',
            //'prolic_usuarioatualizacao',
            //'prolic_dataatualizacao',
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
                ['content'=>'Detalhes dos Processos', 'options'=>['colspan'=>11, 'class'=>'text-center warning']], 
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
