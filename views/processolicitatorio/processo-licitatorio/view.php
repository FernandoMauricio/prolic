<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\processolicitatorio\ProcessoLicitatorio */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Processo Licitatorios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="processo-licitatorio-view">

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

<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> DETALHES DO PROCESSO LICITATÓRIO</h3>
  </div>
    <div class="panel-body">
        <div class="row">
<?php
    $attributes=[
            [
                'group'=>true,
                'label'=>'SEÇÃO 1: Informações',
                'rowOptions'=>['class'=>'info']
            ],

            [
                'columns' => [
                    [
                        'attribute'=>'ano_id',
                        'value'=> $model->ano->an_ano, 
                        'displayOnly'=>true,
                    ],

                    [
                        'attribute'=>'prolic_codmxm', 
                        'displayOnly'=>true
                    ],

                    [
                        'attribute'=>'prolic_sequenciamodal', 
                        'value'=> $model->prolic_sequenciamodal.'/'.$model->ano->an_ano, 
                        'displayOnly'=>true
                    ],

                    [
                        'attribute'=>'situacao_id',
                        'value'=> $model->situacao->sit_descricao, 
                        'displayOnly'=>true,
                        'labelColOptions'=>['style'=>'width:0%'],
                    ],

                ],
            ],

            [
                'columns' => [
                    [
                        'attribute'=>'prolic_destino',
                        'format' => 'ntext',
                        'value'=>$model->prolic_destino,
                        'type'=>DetailView::INPUT_TEXTAREA, 
                        'options'=>['rows'=>4]
                    ]
                ],
            ],

            [
                'columns' => [
                    [
                        'attribute'=>'prolic_objeto',
                        'format' => 'ntext',
                        'value'=>$model->prolic_objeto,
                        'type'=>DetailView::INPUT_TEXTAREA, 
                        'options'=>['rows'=>4]
                    ]
                ],
            ],

            [
                'columns' => [
                    [
                        'attribute'=>'modalidade',
                        'value'=> $model->modalidadeValorlimite->modalidade->mod_descricao, 
                        'displayOnly'=>true,
                    ],

                    [
                        'attribute'=>'ramo',
                        'value'=> $model->modalidadeValorlimite->ramo->ram_descricao, 
                        'displayOnly'=>true,
                    ],

                    [
                        'attribute'=>'artigo_id',
                        'value'=> $model->artigo->art_descricao, 
                        'displayOnly'=>true,
                    ],
                ],
            ],

            [
                'columns' => [
                    [
                        'attribute'=>'prolic_cotacoes',
                        'value'=> $model->prolic_cotacoes, 
                        'displayOnly'=>true,
                    ],

                    [
                        'attribute'=>'prolic_centrocusto',
                        'value'=> $model->prolic_centrocusto, 
                        'displayOnly'=>true,
                        'labelColOptions'=>['style'=>'width:0%'],
                    ],

                    [
                        'attribute'=>'prolic_elementodespesa',
                        'value'=> $model->prolic_elementodespesa, 
                        'displayOnly'=>true,
                    ],

                    [
                        'attribute'=>'recursos_id',
                        'value'=> $model->recursos->rec_descricao, 
                        'displayOnly'=>true,
                    ],
                ],
            ],

            [
                'columns' => [
                    [
                        'attribute'=>'prolic_motivo',
                        'format' => 'ntext',
                        'value'=>$model->prolic_motivo,
                        'type'=>DetailView::INPUT_TEXTAREA, 
                        'options'=>['rows'=>4]
                    ]
                ],
            ],

            [
                'columns' => [
                    [
                        'attribute'=>'comprador_id',
                        'value'=> $model->comprador->comp_descricao, 
                        'displayOnly'=>true,
                    ],
                ],
            ],

            [
                'group'=>true,
                'label'=>'SEÇÃO 2: Informações Financeiras',
                'rowOptions'=>['class'=>'info']
            ],

            [
                'columns' => [
                    [
                        'attribute'=>'prolic_valorestimado',
                        'label'=>'Valor Estimado (R$)',
                        'format'=>['decimal', 2],
                        'inputContainer' => ['class'=>'col-sm-6'],
                    ],
                ],
            ],

            [
                'columns' => [
                    [
                        'attribute'=>'prolic_valoraditivo',
                        'label'=>'Valor Aditivo (R$)',
                        'format'=>['decimal', 2],
                        'inputContainer' => ['class'=>'col-sm-6'],
                    ],
                ],
            ],

            [
                'columns' => [
                    [
                        'attribute'=>'prolic_valorefetivo',
                        'label'=>'Valor Efetivo (R$)',
                        'format'=>['decimal', 2],
                        'inputContainer' => ['class'=>'col-sm-6'],
                    ],
                ],
            ],

            [
                'group'=>true,
                'label'=>'SEÇÃO 3: Datas',
                'rowOptions'=>['class'=>'info']
            ],

            [
                'columns' => [
                    [
                        'attribute'=>'prolic_datacertame',
                        'value'=> $model->prolic_datacertame, 
                        'displayOnly'=>true,
                    ],

                    [
                        'attribute'=>'prolic_datadevolucao',
                        'value'=> $model->prolic_datadevolucao, 
                        'displayOnly'=>true,
                    ],

                    [
                        'attribute'=>'prolic_datahomologacao',
                        'value'=> $model->prolic_datahomologacao, 
                        'displayOnly'=>true,
                    ],

                ],
            ],

            [
                'group'=>true,
                'label'=>'SEÇÃO 4: Auditoria',
                'rowOptions'=>['class'=>'info']
            ],

            [
                'columns' => [
                    [
                        'attribute'=>'prolic_usuariocriacao',
                        'value'=> $model->prolic_usuariocriacao, 
                        'displayOnly'=>true,
                    ],

                    [
                        'attribute'=>'prolic_datacriacao',
                        'value'=> $model->prolic_datacriacao, 
                        'displayOnly'=>true,
                    ],
                ],
            ],

            [
                'columns' => [
                    [
                        'attribute'=>'prolic_usuarioatualizacao',
                        'value'=> $model->prolic_usuarioatualizacao, 
                        'displayOnly'=>true,
                    ],

                    [
                        'attribute'=>'prolic_dataatualizacao',
                        'value'=> $model->prolic_dataatualizacao, 
                        'displayOnly'=>true,
                    ],
                ],
            ],

        ];

    echo DetailView::widget([
        'model'=>$model,
        'condensed'=>true,
        'hover'=>true,
        'mode'=>DetailView::MODE_VIEW,
        'attributes'=> $attributes,
    ]);

?>
        </div>
    </div>
</div>
</div>
