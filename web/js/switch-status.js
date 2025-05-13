$(document).on('change', '.status-switch', function () {
    const checkbox = $(this);
    const id = checkbox.data('id');
    const url = checkbox.data('url'); // precisa passar via data-url

    $.ajax({
        url: url,
        type: 'POST',
        data: { id: id },
        success: function (data) {
            if (!data.success) {
                showToast('Erro ao atualizar status.', 'danger');
                checkbox.prop('checked', !checkbox.prop('checked'));
            } else {
                const msg = data.status ? 'Ativado com sucesso.' : 'Desativado com sucesso.';
                showToast(msg, 'success');
                $.pjax.reload({ container: '#pjax-grid', timeout: 2000 });
            }
        },
        error: function () {
            showToast('Erro de comunicação com o servidor.', 'danger');
            checkbox.prop('checked', !checkbox.prop('checked'));
        }
    });
});

function showToast(message, type) {
    const toast = $(`
        <div class="toast align-items-center text-white bg-${type} border-0 show position-fixed bottom-0 end-0 m-3 shadow" role="alert">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
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
