<?php

use yii\helpers\Html;
use kartik\grid\GridView;

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
            [
                'class'=>'kartik\grid\BooleanColumn',
                'attribute'=>'status', 
                'vAlign'=>'middle'
            ], 

            ['class' => 'yii\grid\ActionColumn','template' => '{update}'],

        ],
    ]); ?>
</div>
