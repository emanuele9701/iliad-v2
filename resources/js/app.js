import './bootstrap';

window.openFormElimina = function openFormElimina(element) {
    var parent = $(element.currentTarget).parent();
    $($(parent).find('#modalDelete')).modal('show');
}
