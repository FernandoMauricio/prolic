// public/js/processolicitatorio.js

$(function () {
    const campoRequisicao = '#processolicitatorio-prolic_codmxm';
    const containerPreview = '#requisicao-preview';

    // Armazena números de requisições já carregadas
    const requisicoesExibidas = new Set();

    $(campoRequisicao).on('select2:select', function (e) {
        const numero = e.params.data.id;

        if (requisicoesExibidas.has(numero)) {
            mostrarFeedback(`Requisição ${numero} já adicionada.`, 'warning');
            return;
        }

        $.getJSON("/prolic/web/index.php?r=processolicitatorio/processo-licitatorio/buscar-requisicao", {
            codigoEmpresa: '02',
            numeroRequisicao: numero
        }, function (response) {
            if (response.success && response.html) {
                const htmlComRemocao = `
                <div class="requisicao-preview-item" data-id="${numero}">
                    <button class="btn btn-xs btn-danger requisicao-remover" style="position: absolute; top: 5px; right: 5px;">Remover</button>
                    ${response.html}
                </div>`;
                $(containerPreview).append(htmlComRemocao);
                requisicoesExibidas.add(numero);
                mostrarFeedback(`Requisição ${numero} carregada com sucesso.`, 'success');
            } else {
                mostrarFeedback(`Falha ao carregar a requisição ${numero}.`, 'danger');
            }
        }).fail(function () {
            mostrarFeedback(`Erro ao consultar a requisição ${numero}.`, 'danger');
        });
    });


    $(document).on('click', '.requisicao-remover', function () {
        const $item = $(this).closest('.requisicao-preview-item');
        const id = $item.data('id');
        $item.remove();
        requisicoesExibidas.delete(id);

        // Remover do select2 visualmente também
        const option = $(campoRequisicao + ' option[value="' + id + '"]');
        if (option.length) {
            option.prop('selected', false);
            $(campoRequisicao).trigger('change');
        }
    });

    // Limpar previews ao limpar o select2
    $(campoRequisicao).on('select2:clear', function () {
        $(containerPreview).empty();
        requisicoesExibidas.clear();
    });
});

function mostrarFeedback(mensagem, tipo) {
    const $alerta = $('#requisicao-feedback');
    $alerta
        .removeClass()
        .addClass('alert alert-' + tipo)
        .html('<strong>' + mensagem + '</strong>')
        .fadeIn(200)
        .delay(3000)
        .fadeOut(400);
}
