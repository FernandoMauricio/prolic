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

    <p class="mb-4">
        <?= Html::a('<i class="bi bi-plus-circle me-1"></i> Novo Valor Limite', ['create'], ['class' => 'btn btn-success shadow-sm']) ?>
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

    <?php Pjax::begin(); ?>

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
                'attribute' => 'homologacao_usuario',
                'label' => 'Usuário<br>Homologação',
                'encodeLabel' => false,
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
                'class' => 'kartik\grid\BooleanColumn',
                'attribute' => 'status',
                'vAlign' => 'middle',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{homologar} {delete}',
                'header' => 'Ações',
                'contentOptions' => ['class' => 'text-center', 'width' => '110px'],
                'buttons' => [
                    'homologar' => function ($url, $model) {
                        if (!empty($model->homologacao_usuario) || !empty($model->homologacao_data)) {
                            return ''; // já homologado, não exibe botão
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
                            return ''; // tem vínculo com processo, não exibe botão
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