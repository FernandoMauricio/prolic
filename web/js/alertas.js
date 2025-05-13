window.exibirAlertaSaldoNegativo = function (mensagem) {
    const alertaHtml = `
        <div class="alert alert-danger d-flex align-items-center shadow-sm fade show shake-erro"
             role="alert" id="alerta-saldo-negativo">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <div>${mensagem}</div>
        </div>
    `;
    $('#saldo-alerta-container').html(alertaHtml);
};
