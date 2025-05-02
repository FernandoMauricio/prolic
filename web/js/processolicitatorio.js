$(function () {
    const campoRequisicao = '#processolicitatorio-prolic_codmxm';
    const containerPreview = '#requisicao-preview';

    // Armazena números de requisições já carregadas
    const requisicoesExibidas = new Set();

    // Função para adicionar o spinner
    function adicionarSpinner() {
        const spinnerHTML = `
            <div class="spinner-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); display: flex; justify-content: center; align-items: center; z-index: 9999;">
                <div class="spinner-border text-light" role="status" style="width: 4rem; height: 4rem; border-width: 0.4em;">
                    <span class="visually-hidden">Carregando...</span>
                </div>
            </div>
        `;
        $('body').append(spinnerHTML);
    }

    // Função para remover o spinner
    function removerSpinner() {
        $('body').find('.spinner-overlay').remove();
    }

    // Usando o evento de seleção no select2
    $(campoRequisicao).on('select2:select', function (e) {
        const numero = e.params.data.id;

        // Se a requisição já foi carregada, não faz nada
        if (requisicoesExibidas.has(numero)) {
            mostrarFeedback(`Requisição ${numero} já adicionada.`, 'warning');
            return;
        }

        // Adiciona o spinner logo antes de começar a requisição
        adicionarSpinner();  // Spinner no body
        mostrarFeedback(`Carregando requisição ${numero}...`, 'info');

        $.getJSON("/prolic/web/index.php?r=processolicitatorio/processo-licitatorio/buscar-requisicao", {
            codigoEmpresa: '02',
            numeroRequisicao: numero
        }, function (response) {
            // Remover o spinner após os dados serem carregados
            removerSpinner();

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
            // Remover o spinner em caso de erro
            removerSpinner();
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

// Função para mostrar feedback com animação
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
