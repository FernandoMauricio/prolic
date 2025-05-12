<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\base\Artigo */

$this->title = 'Atualizar Artigo: ' . $model->id . '';
$this->params['breadcrumbs'][] = ['label' => 'Parâmetros do Sistema', 'url' => ['/site/parametros']];
$this->params['breadcrumbs'][] = ['label' => 'Artigos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="artigo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>