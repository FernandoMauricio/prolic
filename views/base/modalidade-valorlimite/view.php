<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\processolicitatorio\ProcessoLicitatorio;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\base\ModalidadeValorlimite */

$this->title = 'Valor Limite #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Parâmetros do Sistema', 'url' => ['/site/parametros']];
$this->params['breadcrumbs'][] = ['label' => 'Valor Limite por Modalidade', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$apurado = $model->valorApurado;
$saldo = $model->valor_limite - $apurado;
$tetoIlimitado = $model->valor_limite >= 999999999.99;
?>

<div class="modalidade-valorlimite-view">
    <h1 class="fs-3 fw-bold text-primary d-flex align-items-center gap-2 mb-4">
        <i class="bi bi-eye-fill"></i> <?= Html::encode($this->title) ?>
    </h1>

    <p>
        <?= Html::a('<i class="bi bi-arrow-left"></i> Voltar', ['index'], ['class' => 'btn btn-secondary']) ?>
        <?php if (!$model->getProcessos()->exists()): ?>
            <?= Html::a('<i class="bi bi-trash3"></i> Excluir', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Deseja realmente excluir este valor limite?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
    </p>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow border-start border-4 border-primary">
                <div class="card-body">
                    <h6 class="card-subtitle mb-1 text-muted">Valor Limite</h6>
                    <h5 class="card-title">
                        <?= $tetoIlimitado
                            ? Html::tag('span', '(não aplicável)', ['class' => 'text-muted fst-italic'])
                            : Yii::$app->formatter->asCurrency($model->valor_limite) ?>
                    </h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow border-start border-4 border-info">
                <div class="card-body">
                    <h6 class="card-subtitle mb-1 text-muted">Limite Apurado</h6>
                    <h5 class="card-title">
                        <?= Yii::$app->formatter->asCurrency($apurado) ?>
                    </h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow border-start border-4 <?= $saldo >= 0 ? 'border-success' : 'border-danger' ?>">
                <div class="card-body">
                    <h6 class="card-subtitle mb-1 text-muted">Saldo</h6>
                    <h5 class="card-title">
                        <?= $tetoIlimitado
                            ? Html::tag('span', '(não aplicável)', ['class' => 'text-muted fst-italic'])
                            : Html::tag('span', Yii::$app->formatter->asCurrency($saldo), ['class' => $saldo >= 0 ? 'text-success' : 'text-danger']) ?>
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => 'Modalidade',
                'value' => $model->modalidade->mod_descricao,
            ],
            [
                'label' => 'Segmento (Ramo)',
                'value' => $model->ramo->ram_descricao,
            ],
            [
                'label' => 'Ano',
                'value' => $model->ano->an_ano,
            ],
            'tipo_modalidade',
            [
                'attribute' => 'homologacao_usuario',
                'label' => 'Usuário Homologação',
            ],
            [
                'attribute' => 'homologacao_data',
                'format' => ['date', 'php:d/m/Y'],
                'label' => 'Data de Homologação',
            ],
            [
                'attribute' => 'status',
                'value' => $model->status ? 'Ativo' : 'Inativo',
            ],
        ],
    ]) ?>

    <hr class="my-5">

    <h4 class="fw-bold mb-3"><i class="bi bi-file-earmark-text"></i> Processos que utilizaram este limite</h4>

    <?php
    $processos = ProcessoLicitatorio::find()
        ->where(['modalidade_valorlimite_id' => $model->id])
        ->all();
    ?>

    <?php if ($processos): ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Código</th>
                        <th>Descrição</th>
                        <th>Valor Estimado</th>
                        <th>Valor Aditivo</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($processos as $i => $proc): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= Html::encode($proc->id) ?></td>
                            <td><?= Html::encode($proc->prolic_objeto ?? '(sem descrição)') ?></td>
                            <td><?= Yii::$app->formatter->asCurrency($proc->prolic_valorestimado ?? 0) ?></td>
                            <td><?= Yii::$app->formatter->asCurrency($proc->prolic_valoraditivo ?? 0) ?></td>
                            <td><?= Yii::$app->formatter->asCurrency(($proc->prolic_valorestimado ?? 0) + ($proc->prolic_valoraditivo ?? 0)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-muted fst-italic">Nenhum processo licitatório vinculado a este valor limite.</p>
    <?php endif; ?>
</div>