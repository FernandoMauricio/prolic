<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\base\Recursos */

$this->title = 'Novo Recurso';
$this->params['breadcrumbs'][] = ['label' => 'Listagem de Recursos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recursos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
