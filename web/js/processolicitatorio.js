$(document).ready(function () {
    let requisicoesPendentes = 0;
    const campoRequisicao = '#processolicitatorio-prolic_codmxm';
    const containerPreview = '#requisicao-preview';
    const accordionContainer = '#accordionPreview';
    const requisicoesExibidas = new Set();

    function adicionarSpinner(numero) {
        removerSpinner(); // evita múltiplos spinners
        const spinnerHTML = `
            <div class="spinner-overlay position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-75 d-flex flex-column justify-content-center align-items-center z-1050">
                <div class="spinner-border text-light mb-3" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
                <div class="text-white fw-bold fs-5 text-center">
                    Carregando Requisição MXM...<br>
                    <span class="text-warning fs-4">${numero}</span>
                </div>
            </div>`;
        $('body').append(spinnerHTML);
    }

    function removerSpinner() {
        $('.spinner-overlay').remove();
    }

    function atualizarMensagemSemRequisicoes() {
        if ($('#accordionPreview .accordion-item').length === 0) {
            $('#sem-requisicoes').removeClass('d-none');
        } else {
            $('#sem-requisicoes').addClass('d-none');
        }
    }

    function carregarRequisicao(numero, callback = () => { }) {
        if (requisicoesExibidas.has(numero)) {
            callback();
            return;
        }

        requisicoesPendentes++;
        adicionarSpinner(numero);
        atualizarSpinnerMensagem(`Carregando Requisição MXM...<br><span class="text-warning fs-4">${numero}</span>`);

        $.ajax({
            url: "/prolic/web/index.php?r=processolicitatorio/processo-licitatorio/buscar-requisicao",
            method: "GET",
            dataType: "json",
            data: {
                codigoEmpresa: '02',
                numeroRequisicao: numero,
                id: typeof processoId !== 'undefined' ? processoId : null
            }
        })
            .done(function (response) {
                if (response.jaUtilizada) {
                    mostrarFeedback(`Requisição ${numero} já está vinculada a outro processo.`, 'warning');
                    const selected = $(campoRequisicao).val() || [];
                    const atualizadas = selected.filter(v => v !== numero);
                    $(campoRequisicao).val(atualizadas).trigger('change.select2');
                    requisicoesExibidas.delete(numero);
                    return;
                }

                const encontrado = response.encontrada === true;
                requisicoesExibidas.add(numero);

                const corClasse = encontrado ? 'border-success bg-success-subtle' : 'border-danger bg-warning-subtle';
                const textoClasse = encontrado ? 'text-success' : 'text-danger';
                const icone = encontrado ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';
                const titulo = encontrado
                    ? `<i class="bi ${icone} me-2"></i> Requisição: ${numero}`
                    : `<i class="bi ${icone} me-2"></i> Requisição: ${numero} (não localizada)`;

                const htmlComRemocao = `<div class="requisicao-preview-item" data-id="${numero}">${response.html}</div>`;

                const accordionItem = `
                <div class="accordion-item ${corClasse}" id="accordion-${numero}">
                    <h2 class="accordion-header" id="heading${numero}">
                        <button class="accordion-button ${textoClasse}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${numero}" aria-expanded="true" aria-controls="collapse${numero}">
                            ${titulo}
                        </button>
                    </h2>
                    <div id="collapse${numero}" class="accordion-collapse collapse show" aria-labelledby="heading${numero}" data-bs-parent="#accordionPreview">
                        <div class="accordion-body">${htmlComRemocao}</div>
                    </div>
                </div>`;

                $(accordionContainer).append(accordionItem);
                atualizarMensagemSemRequisicoes();

                const feedbackMensagem = encontrado
                    ? `Requisição ${numero} carregada com sucesso.`
                    : `Requisição ${numero} adicionada manualmente (não localizada).`;
                mostrarFeedback(feedbackMensagem, encontrado ? 'success' : 'warning');
            })
            .fail(function () {
                requisicoesExibidas.add(numero);
                mostrarFeedback(`Não foi possível consultar a requisição ${numero} devido a um problema técnico.`, 'danger');
            })
            .always(function () {
                requisicoesPendentes--;
                if (requisicoesPendentes <= 0) {
                    setTimeout(removerSpinner, 500);
                    requisicoesPendentes = 0;
                }
                callback();
            });
    }


    function atualizarSpinnerMensagem(mensagem) {
        $('.spinner-overlay .text-white').html(mensagem);
    }

    $(campoRequisicao).on('select2:select', function (e) {
        const numero = e.params.data.id;

        if (requisicoesExibidas.has(numero)) {
            mostrarFeedback(`Requisição ${numero} já adicionada.`, 'warning');
            return;
        }

        carregarRequisicao(numero);
    });

    $(campoRequisicao).on('select2:clear', function () {
        $(containerPreview).empty();
        requisicoesExibidas.clear();
        atualizarMensagemSemRequisicoes();
    });

    $(campoRequisicao).on('select2:unselect', function (e) {
        const numero = e.params.data.id;
        $(`#accordion-${numero}`).remove();
        requisicoesExibidas.delete(numero);
        atualizarMensagemSemRequisicoes();
        mostrarFeedback(`Requisição ${numero} removida.`, 'info');
    });

    $(document).on('click', '.requisicao-remover', function () {
        const $item = $(this).closest('.requisicao-preview-item');
        const id = $item.data('id');
        $item.remove();
        requisicoesExibidas.delete(id);
        atualizarMensagemSemRequisicoes();
    });

    function carregarRequisicoesSalvas() {
        if (typeof requisicoesSalvas === 'undefined') return;

        const requisicoesValidas = requisicoesSalvas.filter(v => v && v.trim() !== '');
        if (requisicoesValidas.length > 0) {
            (async function () {
                for (const numero of requisicoesValidas) {
                    await new Promise(resolve => carregarRequisicao(numero, resolve));
                }
            })();
        } else {
            $('#sem-requisicoes').removeClass('d-none');
        }
    }

    carregarRequisicoesSalvas();
    atualizarMensagemSemRequisicoes();
});

function mostrarFeedback(mensagem, tipo) {
    const $alerta = $('#requisicao-feedback');
    $alerta
        .stop(true, true)
        .removeClass()
        .addClass('alert alert-' + tipo)
        .html('<strong>' + mensagem + '</strong>')
        .fadeIn(200)
        .delay(3000)
        .fadeOut(400, function () {
            $alerta.addClass('d-none');
        });
}
