<?php

/* @var $this yii\web\View */
/* @var $model app\models\processolicitatorio\ProcessoLicitatorio */

use yii\web\JqueryAsset;

$this->title = 'Editar Processo #' . $model->id;
// $this->registerJsFile(
//     '@web/js/processolicitatorio.js',
//     ['depends' => [JqueryAsset::class]]
// );

$this->title = 'Atualizar Processo Licitatório: ' . $model->id . '';
$this->params['breadcrumbs'][] = ['label' => 'Processo Licitatórios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Atualizar';

// $this->registerJs('var processoId = ' . (int) $model->id . ';', \yii\web\View::POS_HEAD);
?>
<?php if (Yii::$app->session->hasFlash('empresaAtualizadaViaApi')): ?>
    <div class="alert alert-warning border-start border-4 border-warning d-flex align-items-center gap-2" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-4 text-warning"></i>
        <div>
            <strong>Dados atualizados:</strong><br>
            Detectamos que esta requisição possuía dados antigos de empresa. Eles foram atualizados automaticamente com base na API do MXM.
        </div>
    </div>
<?php endif; ?>

<div class="processo-licitatorio-update">

    <?= $this->render('form/_form', [
        'model' => $model,
        'ramo' => $ramo,
        'destinos' => $destinos,
        'valorlimite' => $valorlimite,
        'artigo' => $artigo,
        'centrocusto' => $centrocusto,
        'recurso' => $recurso,
        'comprador' => $comprador,
        'situacao' => $situacao,
        'requisicoes' => $requisicoes,
    ]) ?>

</div>