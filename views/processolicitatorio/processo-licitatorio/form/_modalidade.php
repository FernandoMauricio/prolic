<?php

use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\processolicitatorio\ProcessoLicitatorio */

$sumUrl = Url::toRoute(['/processolicitatorio/processo-licitatorio/get-sum-limite']);
$artigoItems = [];
foreach ($artigo as $a) {
    $artigoItems[] = [
        'id'   => $a->id,
        'text' => $a->art_descricao,
        'type' => $a->art_tipo,
    ];
}
?>


<div class="row g-3">
    <div class="col-lg-6">
        <?= $form->field($model, 'recursos_id')->widget(Select2::class, [
            'data' => ArrayHelper::map($recurso, 'id', 'rec_descricao'),
            'options' => ['placeholder' => 'Informe o Recurso...'],
            'pluginOptions' => ['allowClear' => true],
        ]) ?>
    </div>

    <div class="col-lg-6">
        <?= $form->field($model, 'comprador_id')->widget(Select2::class, [
            'data' => ArrayHelper::map($comprador, 'id', 'comp_descricao'),
            'options' => ['placeholder' => 'Informe o Comprador...'],
            'pluginOptions' => ['allowClear' => true],
        ]) ?>
    </div>

    <?php
    $tipoSelecionado = null;
    foreach ($artigo as $a) {
        if ($a->id == $model->artigo_id) {
            $tipoSelecionado = $a->art_tipo;
            break;
        }
    }
    $this->registerJs("window.tipoSelecionado = " . json_encode($tipoSelecionado) . ";", \yii\web\View::POS_HEAD);
    ?>

    <div class="col-lg-12">
        <?= $form->field($model, 'artigo_id', [
            'template' => "{label} " .
                "<span id=\"artigo-type-badge\" class=\"badge ms-2 align-middle bg-warning text-dark\"></span>\n" .
                "{input}\n{error}\n{hint}",
        ])->widget(Select2::class, [
            'data' => ArrayHelper::map($artigo, 'id', 'art_descricao'),
            'options' => [
                'id' => 'processolicitatorio-artigo_id',
                'placeholder' => 'Informe o Artigo…',
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'data' => array_map(function ($a) {
                    return [
                        'id'   => $a->id,
                        'text' => $a->art_descricao,
                        'type' => $a->art_tipo,  // "Valor" ou "Situação"
                    ];
                }, $artigo),
                'templateResult'    => new JsExpression('function(item){ return item.text; }'),
                'templateSelection' => new JsExpression('function(item){ return item.text; }'),
            ],
            'pluginEvents' => [
                "select2:select"   => new JsExpression(<<<JS
                    function(e) {
                      var tipo = e.params.data.type;
                      var badge = $("#artigo-type-badge");
                      badge
                      badge
                        .removeClass('d-none bg-success bg-warning text-dark text-white')
                        .addClass(tipo === 'Valor' ? 'bg-success text-white' : 'bg-warning text-dark')
                        .text(tipo);
                    }
                JS),
                "select2:unselect" => new JsExpression('function(){ $("#artigo-type-badge").addClass("d-none"); }'),
            ],
        ]) ?>
    </div>

    <div class="col-lg-4">
        <?php
        $data_modalidade = ArrayHelper::map($valorlimite, 'modalidade.id', 'modalidade.mod_descricao');
        $modalidadeData = $model->isNewRecord ? $data_modalidade : ArrayHelper::map(
            \app\models\base\ModalidadeValorlimite::find()
                ->innerJoinWith('modalidade')
                ->where(['mod_status' => 1])
                ->andWhere(['!=', 'homologacao_usuario', ''])
                ->andWhere(['modalidade_id' => $model->modalidadeValorlimite->modalidade->id])
                ->all(),
            'modalidade.id',
            'modalidade.mod_descricao'
        );
        echo $form->field($model, 'modalidade')->widget(Select2::class, [
            'data' => $modalidadeData,
            'options' => ['id' => 'modalidade-id', 'placeholder' => 'Selecione a Modalidade...', 'value' => $model->modalidadeValorlimite->modalidade->id],
            'pluginOptions' => ['allowClear' => true],
        ]);
        ?>
    </div>

    <div class="col-lg-8">
        <?= $form->field($model, 'modalidade_valorlimite_id')->widget(DepDrop::class, [
            'type' => DepDrop::TYPE_SELECT2,
            'select2Options' => ['pluginOptions' => ['allowClear' => true]],
            'pluginOptions' => [
                'depends' => ['modalidade-id'],
                'placeholder' => 'Selecione o Segmento...',
                'url' => Url::to(['/processolicitatorio/processo-licitatorio/limite']),
                'data' => [$model->modalidade_valorlimite_id => $model->modalidadeValorlimite->ramo->ram_descricao],
                'initialize'    => true,   // força o carregamento inicial
                'initDepends'   => ['modalidade-id'],  // (opcional, reforça que depende de #modalidade-id)
                'initValueText' => $model->modalidadeValorlimite->ramo->ram_descricao,
            ],
            'options' => [
                'id' => 'valorlimite-id',
                'onchange' => new JsExpression("
            var limiteId = this.value;
            if (!limiteId) return;
            $.getJSON('{$sumUrl}', {
                limiteId: limiteId,
                processo: {$model->id}
            })
            .done(function(data) {
                var vl = parseFloat(data.valor_limite) || 0;
                var va = parseFloat(data.valor_limite_apurado) || 0;
                // atualiza os mini-cards
                $('#card-valor-limite').data('valor', vl).text(formatarMoeda(vl));
                $('#card-limite-apurado').data('valor', va).text(formatarMoeda(va));
                calcularValores();
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.error('Falha ao buscar valores do limite:', textStatus, errorThrown);
            });
        ")
            ],
        ]); ?>
    </div>
</div>

<?php
// Registrar jQuery e Inputmask
$this->registerJsFile('https://code.jquery.com/jquery-3.6.0.min.js', ['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/inputmask@5.0.9/dist/inputmask.min.js', ['position' => \yii\web\View::POS_END]);
?>

<script>
    $(document).ready(function() {
        // Aplica a máscara de moeda aos campos
        Inputmask("currency", {
            prefix: 'R$ ',
            groupSeparator: '.',
            radixPoint: ',',
            placeholder: '0,00'
        }).mask("#processolicitatorio-valor_limite, #processolicitatorio-valor_limite_apurado, #processolicitatorio-valor_saldo");
    });
</script>

<?php
$js = <<<JS
(function(){
  var \$sel   = \$('#processolicitatorio-artigo_id');
  var \$badge = \$('#artigo-type-badge');

  function updateBadge(data){
    if (data && data.length){
      var tipo = data[0].type;
      \$badge
        .removeClass('d-none badge-success badge-warning')
        .addClass(tipo === 'Valor' ? 'badge-success' : 'badge-warning')
        .text(tipo);
    } else {
      \$badge.addClass('d-none');
    }
  }

  // dispara sempre que muda seleção
  \$sel.on('select2:select select2:unselect', function(){
    updateBadge(\$sel.select2('data'));
  });

  // dispara uma vez na carga da página
  updateBadge(\$sel.select2('data'));
})();
JS;
$this->registerJs($js);
?>

<?php

$urlBuscarArtigo = Url::to(['processolicitatorio/processo-licitatorio/buscar-artigo-tipo']);

$this->registerJs(<<<JS
function verificarTipoArtigo(artigoId) {
    var badge = $('#artigo-type-badge');
    var cards = $('#cards-financeiros');
    var infoSituacao = $('#info-artigo-situacao');
    var alerta = $('#saldo-alerta');

    if (!artigoId) {
        badge.addClass('d-none').text('');
        cards.removeClass('d-none');
        infoSituacao.addClass('d-none');
        alerta.show();
        return;
    }

    $.getJSON('{$urlBuscarArtigo}', { id: artigoId }, function (data) {
        if (data.success && data.tipo) {
            console.log('Tipo do artigo:', data.tipo);
            $('#processolicitatorio-artigo_id').data('tipo-artigo', data.tipo);

            if (data.tipo.toLowerCase().includes('situação')) {
                cards.addClass('d-none');
                infoSituacao.removeClass('d-none');
                alerta.hide();
                $('#saldo-alerta-container').html('');
            } else {
                cards.removeClass('d-none');
                infoSituacao.addClass('d-none');
                alerta.show();
            }
        } else {
            cards.removeClass('d-none');
            infoSituacao.addClass('d-none');
            alerta.show();
        }
    });
}



$('#processolicitatorio-artigo_id').on('change', function () {
    verificarTipoArtigo($(this).val());
});

$(document).ready(function () {
    const artigoId = $('#processolicitatorio-artigo_id').val();
    if (artigoId) {
        verificarTipoArtigo(artigoId);
    }
});
JS);
?>

<?php
$this->registerJs(<<<JS
$(document).ready(function () {
    // Atualiza badge ao carregar
    const badge = $('#artigo-type-badge');
    const tipo = window.tipoSelecionado;

    if (tipo) {
        badge
            .removeClass('d-none badge-success badge-warning')
            .addClass(tipo === 'Valor' ? 'badge-success' : 'badge-warning')
            .text(tipo);
    }
});
JS);
?>