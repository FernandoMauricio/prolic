<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View */
/* @var $model app\models\base\ModalidadeValorlimite */

$this->title = 'Valor Limite #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Parâmetros do Sistema', 'url' => ['/site/parametros']];
$this->params['breadcrumbs'][] = ['label' => 'Valor Limite por Modalidade', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$valorLimite = $model->valor_limite;
$valorApurado = $model->valorApurado;
$saldo = $valorLimite - $valorApurado;
$tetoIlimitado = $valorLimite >= 999999999.99;

$processos = $model->getProcessos()->all();
$dataProvider = new ArrayDataProvider([
    'allModels' => array_map(function ($proc) {
        return [
            'sequencia' => $proc->prolic_sequenciamodal . '/' . ($proc->ano->an_ano ?? ''),
            'objeto' => $proc->prolic_objeto ?? '(sem descrição)',
            'estimado' => $proc->prolic_valorestimado ?? 0,
            'aditivo' => $proc->prolic_valoraditivo ?? 0,
            'total' => ($proc->prolic_valorestimado ?? 0) + ($proc->prolic_valoraditivo ?? 0),
        ];
    }, $processos),
    'pagination' => false,
]);
?>


<div class="modalidade-valorlimite-view container">
    <h1 class="fs-3 fw-bold text-primary mb-4 d-flex align-items-center gap-2">
        <i class="bi bi-graph-up-arrow"></i> <?= Html::encode($this->title) ?>
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

    <div class="mb-4 d-flex gap-3">
        <div class="card border-primary border-4 shadow-sm flex-fill">
            <div class="card-body">
                <h6 class="text-muted">Valor Limite</h6>
                <h4>
                    <?= $tetoIlimitado
                        ? Html::tag('span', '(não aplicável)', ['class' => 'text-muted fst-italic'])
                        : Yii::$app->formatter->asCurrency($valorLimite) ?>
                </h4>
            </div>
        </div>
        <div class="card border-info border-4 shadow-sm flex-fill">
            <div class="card-body">
                <h6 class="text-muted">Limite Apurado</h6>
                <h4><?= Yii::$app->formatter->asCurrency($valorApurado) ?></h4>
            </div>
        </div>
        <div class="card border-<?= $saldo >= 0 ? 'success' : 'danger' ?> border-4 shadow-sm flex-fill">
            <div class="card-body">
                <h6 class="text-muted">Saldo</h6>
                <h4 class="text-<?= $saldo >= 0 ? 'success' : 'danger' ?>">
                    <?= $tetoIlimitado
                        ? Html::tag('span', '(não aplicável)', ['class' => 'text-muted fst-italic'])
                        : Yii::$app->formatter->asCurrency($saldo) ?>
                </h4>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                [
                    'label' => 'Modalidade',
                    'value' => $model->modalidade->mod_descricao ?? '(não definido)'
                ],
                [
                    'label' => 'Segmento (Ramo)',
                    'value' => $model->ramo->ram_descricao ?? '(não definido)'
                ],
                [
                    'label' => 'Ano',
                    'value' => $model->ano->an_ano ?? '(não definido)'
                ],
                'tipo_modalidade',
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => Html::tag(
                        'span',
                        $model->status ? 'Ativo' : 'Inativo',
                        [
                            'class' => 'fw-semibold ' . ($model->status ? 'text-success' : 'text-danger')
                        ]
                    ),
                ],

                [
                    'label' => 'Homologado por',
                    'format' => 'raw',
                    'value' => $model->homologacao_usuario
                        ? Html::encode($model->homologacao_usuario)
                        : Html::tag('span', '(pendente)', ['class' => 'text-danger fst-italic']),
                ],
                [
                    'label' => 'Data da Homologação',
                    'format' => 'raw',
                    'value' => $model->homologacao_data
                        ? Yii::$app->formatter->asDate($model->homologacao_data)
                        : Html::tag('span', '(pendente)', ['class' => 'text-danger fst-italic']),
                ],

            ],
        ]) ?>
    </div>

    <?php if (!empty($processos)) : ?>
        <div class="card shadow-sm border-secondary">
            <div class="card-header bg-secondary text-white">
                <h6 class="mb-0"><i class="bi bi-list-check me-1"></i> Processos vinculados</h6>
            </div>
            <div class="card-body p-0">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'layout' => "{items}",
                    'columns' => [
                        ['attribute' => 'sequencia', 'label' => 'Sequência/Ano'],
                        ['attribute' => 'objeto', 'label' => 'Objeto'],
                        [
                            'attribute' => 'estimado',
                            'format' => ['currency'],
                        ],
                        [
                            'attribute' => 'aditivo',
                            'format' => ['currency'],
                        ],
                        [
                            'attribute' => 'total',
                            'format' => ['currency'],
                            'contentOptions' => ['class' => 'fw-bold text-end']
                        ],
                    ]
                ]) ?>
            </div>
        </div>
    <?php else: ?>
        <div class="card shadow-sm border-secondary">
            <div class="card-header bg-secondary text-white d-flex align-items-center gap-2">
                <i class="bi bi-list-check"></i>
                <span>Processos vinculados</span>
            </div>
            <div class="card-body text-center text-muted fst-italic py-4">
                <i class="bi bi-exclamation-circle me-2"></i>
                Nenhum processo licitatório vinculado a este valor limite.
            </div>
        </div>
    <?php endif; ?>

</div>