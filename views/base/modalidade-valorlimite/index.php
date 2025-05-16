<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\widgets\DatePicker;
use yii\widgets\Pjax;
use app\models\base\Modalidade;
use app\models\base\Ramo;

$this->title = 'Valores por Modalidade';
$this->params['breadcrumbs'][] = ['label' => 'Parâmetros do Sistema', 'url' => ['/site/parametros']];
$this->params['breadcrumbs'][] = $this->title;

$status = Yii::$app->request->get('status', 1);  // 1 = ativos, 0 = inativos
$anoFiltro = Yii::$app->request->get('ano', 'corrente'); // 'corrente' | 'anteriores'

$searchModel->status = $status;

if ($status == 1) {
    if ($anoFiltro === 'corrente') {
        $searchModel->ano = date('Y');
    } elseif ($anoFiltro === 'anteriores') {
        $searchModel->ano_menor_que = date('Y');
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
                'template' => '{view} {update} {homologar} {delete}',
                'header' => 'Ações',
                'contentOptions' => ['class' => 'text-center', 'width' => '170px'],
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<i class="bi bi-eye-fill"></i>', $url, [
                            'class' => 'btn btn-outline-primary btn-sm',
                            'title' => 'Visualizar Valor Limite',
                        ]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<i class="bi bi-pencil-fill"></i>', $url, [
                            'class' => 'btn btn-outline-secondary btn-sm',
                            'title' => 'Editar Valor Limite',
                        ]);
                    },
                    'homologar' => function ($url, $model) {
                        if (!empty($model->homologacao_usuario) || !empty($model->homologacao_data)) {
                            return '';
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
                            return '';
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
    'title' => '<i class="bi bi-info-circle-fill me-2"></i> Referências Legais — Resoluções 007/2025 e 1270/2024',
    'size' => 'modal-lg',
    'options' => ['class' => 'fade shadow'],
]);
?>

<!-- Bloco explicativo — Resolução 007/2025 -->
<div class="alert alert-warning border-start border-4 border-warning shadow-sm mb-4" role="alert">
    <div class="d-flex justify-content-between align-items-start mb-3">
        <h6 class="fw-bold text-warning mb-0">
            <i class="bi bi-info-circle-fill me-2"></i>
            Art. 5.º — Instâncias autorizadoras de despesas
        </h6>
        <a href="/prolic/web/uploads/Resolucao_007_2025.pdf" target="_blank" class="btn btn-sm btn-outline-warning ms-3">
            <i class="bi bi-box-arrow-up-right me-1"></i> Resolução 007/2025
        </a>
    </div>
    <ul class="mb-3 small ps-3">
        <li><strong>I - Direção da Divisão Administrativa:</strong> até <strong>R$ 92.000,00</strong></li>
        <li><strong>II - Direção Regional:</strong> de <strong>R$ 92.000,01</strong> até <strong>R$ 826.000,00</strong></li>
        <li><strong>III - Presidência do Conselho Regional:</strong> a partir de <strong>R$ 826.000,01</strong></li>
    </ul>
</div>

<!-- Bloco explicativo — Resolução 1270/2024 -->
<div class="alert alert-info border-start border-4 border-primary shadow-sm mb-0" role="alert">
    <div class="d-flex justify-content-between align-items-start mb-3">
        <h6 class="fw-bold text-primary mb-0">
            <i class="bi bi-info-circle-fill me-2"></i>
            Art. 6.º e 7.º — Limites legais por tipo de modalidade
        </h6>
        <a href="/prolic/web/uploads/Resolucao_1270_2024.pdf" target="_blank" class="btn btn-sm btn-outline-primary ms-3">
            <i class="bi bi-box-arrow-up-right me-1"></i> Resolução 1270/2024
        </a>
    </div>

    <p class="small text-muted mb-2"><strong>Art. 6.º</strong> — Para as modalidades licitatórias, aplicam-se os limites legais:</p>
    <ul class="mb-3 small ps-3">
        <li><strong>I - Obras e serviços de engenharia:</strong>
            <ul class="mb-2">
                <li>CONVITE: até <strong>R$ 2.465.000,00</strong></li>
                <li>CONCORRÊNCIA: acima de <strong>R$ 2.465.000,00</strong></li>
            </ul>
        </li>
        <li><strong>II - Compras e demais serviços:</strong>
            <ul class="mb-2">
                <li>CONVITE: até <strong>R$ 826.000,00</strong></li>
                <li>CONCORRÊNCIA: acima de <strong>R$ 826.000,00</strong></li>
            </ul>
        </li>
        <li><strong>III - Alienações de bens:</strong>
            <ul class="mb-0">
                <li>LEILÃO ou CONCORRÊNCIA: acima de <strong>R$ 92.000,00</strong>, dispensável na fase de habilitação</li>
            </ul>
        </li>
    </ul>
</div>

<?php \yii\bootstrap5\Modal::end(); ?>