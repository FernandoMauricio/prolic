<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\base\ArtigoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Listagem de Artigos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="artigo-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Novo Artigo', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'art_descricao',
            'art_tipo',
            'art_homologacaousuario',
            [
                'attribute' => 'art_homologacaodata',
                'format' => ['datetime', 'php:d/m/Y'],
                'width' => '190px',
                'hAlign' => 'center',
                'filter'=> DatePicker::widget([
                'model' => $searchModel, 
                'attribute' => 'art_homologacaodata',
                'pluginOptions' => [
                     'autoclose'=>true,
                     'format' => 'yyyy-mm-dd',
                    ]
                ])
            ],
            [
                'class'=>'kartik\grid\BooleanColumn',
                'attribute'=>'art_status', 
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
