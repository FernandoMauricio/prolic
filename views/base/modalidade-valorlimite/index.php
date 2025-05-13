<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\widgets\DatePicker;
use yii\widgets\Pjax;
use app\models\base\Modalidade;
use app\models\base\Ano;
use app\models\base\Ramo;

$this->title = 'Valor Limite por Modalidade';
$this->params['breadcrumbs'][] = ['label' => 'Parâmetros do Sistema', 'url' => ['/site/parametros']];
$this->params['breadcrumbs'][] = $this->title;

$status = Yii::$app->request->get('status', 1);  // 1 = ativos, 0 = inativos
$anoFiltro = Yii::$app->request->get('ano', 'corrente'); // 'corrente' | 'anteriores'

$searchModel->status = $status;

if ($status == 1) {
    if ($anoFiltro === 'corrente') {
        $searchModel->ano_id = \app\models\base\Ano::find()
            ->select('id')
            ->where(['an_ano' => date('Y')])
            ->scalar(); // só um valor
    } elseif ($anoFiltro === 'anteriores') {
        $searchModel->ano_id = \app\models\base\Ano::find()
            ->select('id')
            ->where(['<', 'an_ano', date('Y')])
            ->column(); // array com vários
    }
}

$panelType = $status == 1 ? GridView::TYPE_SUCCESS : GridView::TYPE_DANGER;
?>

<div class="modalidade-valorlimite-index">

    <h1 class="fs-3 fw-bold text-primary d-flex align-items-center gap-2 mb-4">
        <i class="bi bi-graph-up-arrow"></i> <?= Html::encode($this->title) ?>
    </h1>

    <p class="mb-4 d-flex flex-wrap gap-2">
        <?= Html::a('<i class="bi bi-plus-circle me-1"></i> Novo Valor Limite', ['create'], ['class' => 'btn btn-success shadow-sm']) ?>
        <button type="button" class="btn btn-outline-dark shadow-sm" data-bs-toggle="modal" data-bs-target="#modalLimitesLegais">
            <i class="bi bi-info-circle-fill me-1"></i> Ver Limites Legais
        </button>
    </p>

    <ul class="nav nav-tabs mb-3">
        <!-- Ativos do ano corrente -->
        <li class="nav-item">
            <a class="nav-link <?= ($status == 1 && $anoFiltro === 'corrente') ? 'active' : '' ?>"
                href="<?= Url::to(['index', 'status' => 1, 'ano' => 'corrente']) ?>">
                <i class="bi bi-check-circle-fill me-1"></i> Ano: <?= date('Y') ?>
            </a>
        </li>

        <!-- Ativos de anos anteriores -->
        <li class="nav-item">
            <a class="nav-link <?= ($status == 1 && $anoFiltro === 'anteriores') ? 'active' : '' ?>"
                href="<?= Url::to(['index', 'status' => 1, 'ano' => 'anteriores']) ?>">
                <i class="bi bi-clock-history me-1"></i> Anos Anteriores
            </a>
        </li>

        <!-- Inativos -->
        <li class="nav-item">
            <a class="nav-link <?= $status == 0 ? 'active' : '' ?>"
                href="<?= Url::to(['index', 'status' => 0]) ?>">
                <i class="bi bi-x-circle-fill me-1"></i> Inativos
            </a>
        </li>
    </ul>

    <?php Pjax::begin(['id' => 'pjax-grid']); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'hover' => true,
        'pjax' => true,
        'tableOptions' => ['class' => 'table table-bordered table-hover table-sm'],
        'summary' => 'Mostrando <strong>{begin}-{end}</strong> de <strong>{totalCount}</strong> itens',
        'panel' => [
            'type' => $panelType,
            'heading' => '<i class="bi bi-table me-2"></i>Valores Limite',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'tipo_modalidade',
                'label' => 'Tipo de<br> Modalidade',
                'encodeLabel' => false,
                'format' => 'raw',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => [
                    'Obras e serviços de engenharia' => 'Obras e serviços de engenharia',
                    'Compras e demais serviços' => 'Compras e demais serviços',
                    'Alienações de bens, sempre precedidas de avaliação' => 'Alienações de bens, sempre precedidas de avaliação',
                ],
                'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true]],
                'filterInputOptions' => ['placeholder' => 'Tipo...'],
            ],
            [
                'attribute' => 'modalidade_id',
                'value' => fn($model) => $model->modalidade->mod_descricao,
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(Modalidade::find()->where(['mod_status' => 1])->all(), 'id', 'mod_descricao'),
                'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true]],
                'filterInputOptions' => ['placeholder' => 'Modalidade...'],
            ],
            [
                'attribute' => 'ramo_id',
                'value' => fn($model) => $model->ramo->ram_descricao,
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(Ramo::find()->where(['ram_status' => 1])->all(), 'id', 'ram_descricao'),
                'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true]],
                'filterInputOptions' => ['placeholder' => 'Segmento...'],
            ],
            [
                'attribute' => 'valor_limite',
                'format' => 'raw',
                'hAlign' => 'right',
                'value' => function ($model) {
                    $tetoIlimitado = $model->valor_limite >= 999999999.99;
                    return $tetoIlimitado
                        ? Html::tag('span', '(não aplicável)', ['class' => 'text-muted fst-italic'])
                        : Html::tag(
                            'span',
                            Yii::$app->formatter->asCurrency($model->valor_limite),
                            ['title' => Yii::$app->formatter->asCurrency($model->valor_limite)]
                        );
                },
            ],

            [
                'label' => 'Limite Apurado',
                'format' => 'raw',
                'hAlign' => 'right',
                'value' => function ($model) {
                    $apurado = $model->valorApurado;
                    return Html::tag(
                        'span',
                        Yii::$app->formatter->asCurrency($apurado),
                        ['title' => Yii::$app->formatter->asCurrency($apurado)]
                    );
                },
            ],
            [
                'label' => 'Saldo',
                'format' => 'raw',
                'hAlign' => 'right',
                'value' => function ($model) {
                    if ($model->valor_limite >= 999999999.99) {
                        return Html::tag('span', '(não aplicável)', ['class' => 'text-muted fst-italic']);
                    }

                    $apurado = $model->valorApurado;
                    $saldo = $model->valor_limite - $apurado;
                    $classe = $saldo >= 0 ? 'text-success' : 'text-danger';

                    return Html::tag(
                        'span',
                        Yii::$app->formatter->asCurrency($saldo),
                        ['class' => $classe, 'title' => 'Saldo disponível']
                    );
                },
            ],

            [
                'attribute' => 'homologacao_data',
                'label' => 'Data<br>Homologação',
                'encodeLabel' => false,
                'format' => ['date', 'php:d/m/Y'],
                'hAlign' => 'center',
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'homologacao_data',
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'pluginOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd'],
                ]),
            ],

            [
                'attribute' => 'status',
                'format' => 'raw',
                'label' => 'Ativo',
                'filter' => [
                    1 => 'Ativo',
                    0 => 'Inativo',
                ],
                'contentOptions' => ['class' => 'text-center'],
                'value' => function ($model) {
                    $checked = $model->status ? 'checked' : '';
                    $id = $model->id;
                    $switchId = "switch-status-$id";

                    return Html::tag(
                        'div',
                        Html::checkbox('status', $model->status, [
                            'class' => 'form-check-input status-switch switch-lg',
                            'data-id' => $model->id,
                            'data-url' => Url::to(['base/modalidade-valorlimite/toggle-status']),
                            'data-container' => '#pjax-grid',
                        ]) .
                            Html::label('', $switchId, ['class' => 'form-check-label']),
                        ['class' => 'form-check form-switch d-inline-block']
                    );
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {homologar} {delete}',
                'header' => 'Ações',
                'contentOptions' => ['class' => 'text-center', 'width' => '130px'],
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<i class="bi bi-eye-fill"></i>', $url, [
                            'class' => 'btn btn-outline-primary btn-sm',
                            'title' => 'Visualizar Valor Limite',
                        ]);
                    },
                    'homologar' => function ($url, $model) {
                        if (!empty($model->homologacao_usuario) || !empty($model->homologacao_data)) {
                            return ''; // já homologado
                        }

                        return Html::a('<i class="bi bi-patch-check-fill"></i>', $url, [
                            'class' => 'btn btn-outline-success btn-sm',
                            'title' => 'Homologar Valor',
                            'data' => [
                                'confirm' => 'Você deseja <b>homologar</b> este valor limite?',
                                'method' => 'post',
                            ],
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        if ($model->getProcessos()->exists()) {
                            return ''; // não pode excluir se tiver processo
                        }

                        return Html::a('<i class="bi bi-trash3"></i>', $url, [
                            'class' => 'btn btn-outline-danger btn-sm',
                            'title' => 'Excluir Valor Limite',
                            'data' => [
                                'confirm' => 'Deseja realmente <b>excluir</b> este valor limite? Essa ação não poderá ser desfeita.',
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
            ],

        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>
<?php
\yii\bootstrap5\Modal::begin([
    'id' => 'modalLimitesLegais',
    'title' => '<i class="bi bi-info-circle-fill me-2"></i> Art. 7.º — Limites legais por tipo de modalidade',
    'size' => 'modal-lg',
]);
?>

<div class="mb-3">
    <ul class="mb-0 small ps-3">
        <li><strong>I - Obras e serviços de engenharia:</strong>
            <ul>
                <li>CONVITE: até <strong>R$ 2.465.000,00</strong></li>
                <li>CONCORRÊNCIA: acima de <strong>R$ 2.465.000,00</strong></li>
            </ul>
        </li>
        <li><strong>II - Compras e demais serviços:</strong>
            <ul>
                <li>CONVITE: até <strong>R$ 826.000,00</strong></li>
                <li>CONCORRÊNCIA: acima de <strong>R$ 826.000,00</strong></li>
            </ul>
        </li>
        <li><strong>III - Alienações de bens:</strong>
            <ul>
                <li>LEILÃO ou CONCORRÊNCIA: acima de <strong>R$ 92.000,00</strong>, dispensável na fase de habilitação</li>
            </ul>
        </li>
    </ul>
</div>

<?php \yii\bootstrap5\Modal::end(); ?>