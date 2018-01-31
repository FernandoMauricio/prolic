<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\widgets\DatePicker;

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

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'modalidade_id',
                'value' =>'modalidade.mod_descricao',
            ],
            [
                'attribute' => 'ramo_id',
                'value' =>'ramo.ram_descricao',
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

        ],
    ]); ?>
</div>
