// web/js/processolicitatorio/view.js

function fadeInOnReady({ loaderSelector, contentSelector, scroll = true, delay = 0 }) {
    const $loader = $(loaderSelector);
    const $content = $(contentSelector);

    setTimeout(() => {
        $loader.fadeOut(300, () => {
            $content
                .css('opacity', 0)
                .removeClass('d-none')
                .animate({ opacity: 1 }, 300);
        });
    }, delay);
}

$(document).ready(function () {
    $.ajax({
        url: 'index.php?r=processolicitatorio%2Fprocesso-licitatorio%2Frequisicoes-ajax&id=' + processoId,
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.html) {
                $('#accordion-requisicoes-container').html(response.html);
                fadeInOnReady({
                    loaderSelector: '#loading-requisicoes',
                    contentSelector: '#conteudo-requisicoes',
                    scroll: false,
                    delay: 0
                });
            } else {
                $('#accordion-requisicoes-container').html('<div class="alert alert-warning mb-0">Nenhuma requisição vinculada encontrada.</div>');
            }
        },
        error: function () {
            $('#accordion-requisicoes-container').html('<div class="alert alert-danger mb-0">Erro ao carregar requisições vinculadas.</div>');
        }
    });
});

$(document).on('click', '#toggleRequisicoes', function () {
    const resumo = $('#requisicoes-resumo');
    const detalhadas = $('#requisicoes-detalhadas');
    const mostrandoResumo = !resumo.hasClass('d-none');
    const destinoScroll = $('#conteudo-requisicoes');
    const botao = $(this);

    botao.find('i').addClass('rotate-animation');

    function destacar(elemento) {
        elemento
            .css({
                'box-shadow': '0 0 0.5rem rgba(0, 123, 255, 0.5)',
                'transform': 'scale(1.01)',
                'transition': 'all 0.3s ease-in-out'
            })
            .delay(1000)
            .queue(function (next) {
                $(this).css({ 'box-shadow': '', 'transform': 'scale(1)' });
                next();
            });
    }

    if (mostrandoResumo) {
        resumo.fadeOut(200, () => {
            resumo.addClass('d-none');
            detalhadas.removeClass('d-none').hide().fadeIn(250, () => {
                destacar(detalhadas);
                botao.html('<i class="bi bi-list"></i> Ver resumo');
            });
        });
    } else {
        detalhadas.fadeOut(200, () => {
            detalhadas.addClass('d-none');
            resumo.removeClass('d-none').hide().fadeIn(250, () => {
                destacar(resumo);
                botao.html('<i class="bi bi-card-text"></i> Ver detalhes');
            });
        });
    }
});
