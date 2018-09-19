<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\processolicitatorio\Observacoes */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Observacoes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="observacoes-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'obs_descricao',
            'obs_usuariocriacao',
            'obs_datacriacao',
            'processo_licitatorio_id',
        ],
    ]) ?>

</div>
