$(document).ready(function () {
    let requisicoesPendentes = 0;
    const campoRequisicao = '#processolicitatorio-prolic_codmxm';
    const containerPreview = '#requisicao-preview';
    const accordionContainer = '#accordionPreview';

    const requisicoesExibidas = new Set();

    // Função para adicionar o spinner
    function adicionarSpinner(numero) {
        const spinnerHTML = `
            <div class="spinner-overlay position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-75 d-flex flex-column justify-content-center align-items-center z-1050">
                <div class="spinner-border text-light mb-3" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
                <div class="text-white fw-bold fs-5 text-center">
                    Carregando Requisição MXM...<br>
                    <span class="text-warning fs-4">${numero}</span>
                </div>
            </div>
        `;
        $('body').append(spinnerHTML);
    }

    function atualizarMensagemSemRequisicoes() {
        if ($('#accordionPreview .accordion-item').length === 0) {
            $('#sem-requisicoes').removeClass('d-none');
        } else {
            $('#sem-requisicoes').addClass('d-none');
        }
    }

    // Função para remover o spinner
    function removerSpinner() {
        $('body').find('.spinner-overlay').remove();
    }

    // Caso existam valores salvos, carregue-os automaticamente
    const requisicoesValidas = requisicoesSalvas.filter(function (numero) {
        return numero && numero.trim() !== '';
    });

    // Verifica se há requisições válidas
    if (requisicoesValidas.length > 0) {
        (async function () {
            for (const numero of requisicoesValidas) {
                await new Promise(resolve => {
                    carregarRequisicao(numero, resolve);
                });
            }
        })();
    }

    // Usando o evento de seleção no select2
    $(campoRequisicao).on('select2:select', function (e) {
        const numero = e.params.data.id;

        if (requisicoesExibidas.has(numero)) {
            mostrarFeedback(`Requisição ${numero} já adicionada.`, 'warning');
            return;
        }

        adicionarSpinner(numero);
        mostrarFeedback(`Carregando requisição ${numero}...`, 'info');

        if (requisicoesPendentes === 0) {
            adicionarSpinner(numero);
        } else {
            atualizarSpinnerMensagem(`Carregando Requisição MXM...<br><span class="text-warning fs-4">${numero}</span>`);
        }
    });

    // Atualiza o texto do spinner dinamicamente
    function atualizarSpinnerMensagem(mensagem) {
        $('.spinner-overlay .text-white').html(mensagem);
    }

    // Função para carregar a requisição
    function carregarRequisicao(numero, callback = () => { }) {
        requisicoesPendentes++;
        adicionarSpinner(numero);
        atualizarSpinnerMensagem(`Carregando Requisição MXM...<br><span class="text-warning fs-4">${numero}</span>`);

        $.getJSON("/prolic/web/index.php?r=processolicitatorio/processo-licitatorio/buscar-requisicao", {
            codigoEmpresa: '02',
            numeroRequisicao: numero,
            id: typeof processoId !== 'undefined' ? processoId : null
        }, function (response) {
            if (response.jaUtilizada) {
                mostrarFeedback(`Requisição ${numero} já está vinculada a outro processo.`, 'warning');

                // Remove apenas a requisição inválida do Select2
                const selected = $(campoRequisicao).val() || [];
                const atualizadas = selected.filter(v => v !== numero);
                $(campoRequisicao).val(atualizadas).trigger('change.select2');

                requisicoesExibidas.delete(numero);
                callback();
                return;
            }

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
                atualizarMensagemSemRequisicoes();
                requisicoesExibidas.add(numero);

                // Adiciona ao Select2 se não existir
                if ($(campoRequisicao).find(`option[value="${numero}"]`).length === 0) {
                    const newOption = new Option(numero, numero, true, true);
                    $(campoRequisicao).append(newOption).trigger('change');
                } else {
                    const valoresAtuais = $(campoRequisicao).val() || [];
                    if (!valoresAtuais.includes(numero)) {
                        valoresAtuais.push(numero);
                    }
                    $(campoRequisicao).val(valoresAtuais).trigger('change');
                }

                mostrarFeedback(`Requisição ${numero} carregada com sucesso.`, 'success');
            } else {
                const accordionItemErro = `
                <div class="accordion-item border-danger" id="accordion-${numero}">
                    <h2 class="accordion-header" id="headingErro${numero}">
                        <button class="accordion-button bg-danger bg-opacity-10 text-danger fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseErro${numero}" aria-expanded="true" aria-controls="collapseErro${numero}">
                            Requisição não encontrada: ${numero}
                        </button>
                    </h2>
                    <div id="collapseErro${numero}" class="accordion-collapse collapse show" aria-labelledby="headingErro${numero}" data-bs-parent="#accordionPreview">
                        <div class="accordion-body">
                            <p class="text-danger mb-2">A requisição <strong>${numero}</strong> não foi localizada na API MXM ou está inacessível.</p>
                        </div>
                    </div>
                </div>`;
                $(accordionContainer).append(accordionItemErro);
                mostrarFeedback(`Requisição ${numero} não foi encontrada.`, 'warning');
            }
        }).fail(function () {
            mostrarFeedback(`Erro ao consultar a requisição ${numero}.`, 'danger');
        }).always(function () {
            requisicoesPendentes--;
            if (requisicoesPendentes === 0) {
                setTimeout(removerSpinner, 500); // atraso leve para leitura da mensagem
            }
            callback(); // Chama o resolve() do loop
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
        atualizarMensagemSemRequisicoes();
    });

    $(campoRequisicao).on('select2:unselect', function (e) {
        const numero = e.params.data.id;
        $(`#accordion - ${numero}`).remove(); // Remove o accordion correspondente
        requisicoesExibidas.delete(numero); // Remove do Set de controle
        atualizarMensagemSemRequisicoes();
        mostrarFeedback(`Requisição ${numero} removida.`, 'info');
    });
    atualizarMensagemSemRequisicoes();
});

function carregarRequisicoesSalvas() {
    if (typeof requisicoesSalvas === 'undefined') return;

    const requisicoesValidas = requisicoesSalvas.filter(function (numero) {
        return numero && numero.trim() !== '';
    });

    if (requisicoesValidas.length > 0) {
        (async function () {
            for (const numero of requisicoesValidas) {
                await new Promise(resolve => {
                    carregarRequisicao(numero, resolve);
                });
            }
        })();
    } else {
        $('#sem-requisicoes').removeClass('d-none');
    }
}

// Função para mostrar feedback com animação
function mostrarFeedback(mensagem, tipo) {
    const $alerta = $('#requisicao-feedback');

    $alerta
        .stop(true, true) // cancela animações pendentes
        .removeClass('d-none')
        .removeClass()
        .addClass('alert alert-' + tipo)
        .html('<strong>' + mensagem + '</strong>')
        .fadeIn(200)
        .delay(3000)
        .fadeOut(400, function () {
            $alerta.addClass('d-none');
        });
};
