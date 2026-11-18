// Pide confirmación antes de enviar los formularios marcados con data-confirm
document.querySelectorAll('form[data-confirm]').forEach((formulario) => {
    formulario.addEventListener('submit', (evento) => {
        if (!window.confirm(formulario.dataset.confirm)) {
            evento.preventDefault();
        }
    });
});
