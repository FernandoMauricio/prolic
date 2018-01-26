<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\base\ModalidadeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Modalidades';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modalidade-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Modalidade', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'mod_descricao',
            'mod_status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
