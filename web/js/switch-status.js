function bindStatusSwitch() {
    $(document).off('change', '.status-switch'); // evita duplicidade
    $(document).on('change', '.status-switch', function () {
        const checkbox = $(this);
        const id = checkbox.data('id');
        const url = checkbox.data('url');
        const container = checkbox.data('container') || '#pjax-grid';

        if (!url || typeof id === 'undefined') {
            console.error('Switch Status: data-id ou data-url ausente.');
            return;
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: { id: id },
            success: function (data) {
                if (!data.success) {
                    showToast(data.message || 'Erro ao atualizar status.', 'danger');
                    checkbox.prop('checked', !checkbox.prop('checked'));
                } else {
                    showToast(data.status ? 'Ativado com sucesso.' : 'Desativado com sucesso.', 'success');
                    if (container) {
                        $.pjax.reload({ container: container, timeout: 2000 });
                    }
                }
            },
            error: function () {
                showToast('Erro de comunicação com o servidor.', 'danger');
                checkbox.prop('checked', !checkbox.prop('checked'));
            }
        });
    });
}

function showToast(message, type) {
    const toast = $(`
        <div class="toast align-items-center text-white bg-${type} border-0 show position-fixed top-0 end-0 shadow fs-5"
             role="alert" style="z-index: 1080; margin-top: 6.5rem; min-width: 320px; padding: 1rem 1.25rem;">
            <div class="d-flex align-items-center">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white ms-3" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `);

    $('body').append(toast);

    setTimeout(() => {
        toast.fadeOut(500, function () {
            $(this).remove();
        });
    }, 2500);
}

// Vincula eventos ao carregar e após reload PJAX
$(document).ready(bindStatusSwitch);
$(document).on('pjax:complete', bindStatusSwitch);
