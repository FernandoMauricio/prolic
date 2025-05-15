$(document).ready(function () {
    let requisicoesPendentes = 0;
    const campoRequisicao = '#processolicitatorio-prolic_codmxm';
    const containerPreview = '#requisicao-preview';
    const accordionContainer = '#accordionPreview';

    const requisicoesExibidas = new Set();

    // Função para adicionar o spinner
    function adicionarSpinner() {
        const spinnerHTML = `
            <div class="spinner-overlay">
                <div class="spinner-border text-light" role="status">
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

    // Caso existam valores salvos, carregue-os automaticamente
    const requisicoesValidas = requisicoesSalvas.filter(function (numero) {
        return numero && numero.trim() !== '';
    });

    if (requisicoesValidas.length > 0) {
        adicionarSpinner();
        requisicoesValidas.forEach(function (numero) {
            carregarRequisicao(numero);
        });
    }

    // Usando o evento de seleção no select2
    $(campoRequisicao).on('select2:select', function (e) {
        const numero = e.params.data.id;

        if (requisicoesExibidas.has(numero)) {
            mostrarFeedback(`Requisição ${numero} já adicionada.`, 'warning');
            return;
        }

        adicionarSpinner();
        mostrarFeedback(`Carregando requisição ${numero}...`, 'info');

        carregarRequisicao(numero);
    });

    // Função para carregar a requisição
    function carregarRequisicao(numero) {
        requisicoesPendentes++;
        adicionarSpinner();

        $.getJSON("/prolic/web/index.php?r=processolicitatorio/processo-licitatorio/buscar-requisicao", {
            codigoEmpresa: '02',
            numeroRequisicao: numero
        }, function (response) {
            if (response.success && response.html) {
                const htmlComRemocao = `
                    <div class="requisicao-preview-item" data-id="${numero}">
                        ${response.html}
                    </div>`;

                const accordionItem = `
                    <div class="accordion-item" id="accordion-${numero}">
                        <h2 class="accordion-header" id="heading${numero}">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${numero}" aria-expanded="true" aria-controls="collapse${numero}">
                                Requisição: ${numero}
                            </button>
                        </h2>
                        <div id="collapse${numero}" class="accordion-collapse collapse show" aria-labelledby="heading${numero}" data-bs-parent="#accordionPreview">
                            <div class="accordion-body">
                                ${htmlComRemocao}
                            </div>
                        </div>
                    </div>`;

                $(accordionContainer).append(accordionItem);
                requisicoesExibidas.add(numero);

                mostrarFeedback(`Requisição ${numero} carregada com sucesso.`, 'success');
            } else {
                mostrarFeedback(`Falha ao carregar a requisição ${numero}.`, 'danger');
            }
        }).fail(function () {
            mostrarFeedback(`Erro ao consultar a requisição ${numero}.`, 'danger');
        }).always(function () {
            requisicoesPendentes--;
            if (requisicoesPendentes === 0) removerSpinner();
        });
    }


    // Evento de remoção de requisição
    $(document).on('click', '.requisicao-remover', function () {
        const $item = $(this).closest('.requisicao-preview-item');
        const id = $item.data('id');
        $item.remove();
        requisicoesExibidas.delete(id);
    });

    // Limpeza dos dados ao limpar o select2
    $(campoRequisicao).on('select2:clear', function () {
        $(containerPreview).empty();
        requisicoesExibidas.clear();
    });

    $(campoRequisicao).on('select2:unselect', function (e) {
        const numero = e.params.data.id;
        $(`#accordion-${numero}`).remove(); // Remove o accordion correspondente
        requisicoesExibidas.delete(numero); // Remove do Set de controle
        mostrarFeedback(`Requisição ${numero} removida.`, 'info');
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
