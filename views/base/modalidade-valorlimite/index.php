<?php

use yii\helpers\Html;
use kartik\widgets\DatePicker;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\editable\Editable;
use yii\bootstrap\Modal;

use app\models\base\Ano;
use app\models\base\ModalidadeValorlimite;
use app\models\base\Comprador;
use app\models\base\Artigo;
use app\models\base\Situacao;
use app\models\base\Modalidade;

/* @var $this yii\web\View */
/* @var $searchModel app\models\base\ModalidadeValorlimiteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Listagem de Valor Limite - Modalidade';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modalidade-valorlimite-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Novo Valor Limite - Modalidade', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

<?php

$gridColumns = [
        [
            'attribute'=>'modalidade_id', 
            'width'=>'250px',
            'value'=>function ($model, $key, $index, $widget) { 
                return $model->modalidade->mod_descricao;
            },
            'filterType'=>GridView::FILTER_SELECT2,
            'filter'=>ArrayHelper::map(Modalidade::find()->where(['mod_status' => 1])->asArray()->all(), 'mod_descricao', 'mod_descricao'), 
            'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
            ],
            'filterInputOptions'=>['placeholder'=>'Modalidade...'],
            'group'=>true,  // enable grouping
            'subGroupOf'=>1 // supplier column index is the parent group
        ],

        [
            'attribute'=>'ramo_id', 
            'width'=>'250px',
            'value'=>function ($model, $key, $index, $widget) { 
                return $model->ramo->ram_descricao;
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

        [
            'attribute' => 'ano_id',
            'value' =>'ano.an_ano',
        ],

        [
            'format' => 'Currency',
            'attribute' => 'valor_limite',
        ],

        'homologacao_usuario',

        [
            'attribute' => 'homologacao_data',
            'format' => ['datetime', 'php:d/m/Y'],
            'width' => '190px',
            'hAlign' => 'center',
            'filter'=> DatePicker::widget([
            'model' => $searchModel, 
            'attribute' => 'homologacao_data',
            'pluginOptions' => [
                 'autoclose'=>true,
                 'format' => 'yyyy-mm-dd',
                ]
            ])
        ],

        [
            'attribute'=>'tipo',
            'format'=>'raw',
            'width'=>'6%',
            'value' => function ($data) { return $data->tipo == 0 ? '<span class="label label-warning"> Limitado</span>' : '<span class="label label-success"> Ilimitado</span>'; },
            'filterType'=>GridView::FILTER_SELECT2,
            'filter'=> ['0'=>'Limitado','1'=>'Ilimitado'],
            'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
            ],
                'filterInputOptions'=>['placeholder'=>'Tipo'],
        ],

        [
            'class'=>'kartik\grid\BooleanColumn',
            'attribute'=>'status', 
            'vAlign'=>'middle'
        ], 

        ['class' => 'yii\grid\ActionColumn',
                'template' => '{update} {homologar}',
                'contentOptions' => ['style' => 'width: 7%;'],
                'buttons' => [

                //VISUALIZAR/IMPRIMIR
                'update' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span> ', $url, [
                                'class'=>'btn btn-default btn-xs',
                                'title' => Yii::t('app', 'Atualizar'),
                
                    ]);
                },

                //HOMOLOGAR - Acesso somente para o Gerente do GGP 7 - ggp / 1 - responsavel setor
                'homologar' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-ok"></span>', $url, [
                                 'class'=>'btn btn-success btn-xs',
                                 'title' => Yii::t('app', 'Homologar Cargo'),
                                 'data' =>  [
                                                 'confirm' => 'Você tem CERTEZA que deseja <b>HOMOLOGAR</b> esse item?',
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
                ['content'=>'Detalhes dos Processos', 'options'=>['colspan'=>8, 'class'=>'text-center warning']], 
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
