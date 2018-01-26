<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\base\Recursos */

$this->title = 'Atualizar Recurso: '.$model->id.'';
$this->params['breadcrumbs'][] = ['label' => 'Recursos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="recursos-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
