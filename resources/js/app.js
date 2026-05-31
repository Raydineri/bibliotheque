import './bootstrap';
import './dashboard-charts';

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

window.Alpine = Alpine;
window.Swal = Swal;

Alpine.start();

const confirmFormSubmit = (form, method) => {
    const isDelete = method === 'DELETE';
    const title = isDelete ? 'Supprimer cet element ?' : 'Confirmer la modification ?';
    const text = isDelete
        ? 'Cette action est irreversible.'
        : 'Les changements seront enregistres.';

    Swal.fire({
        title,
        text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: isDelete ? 'Supprimer' : 'Confirmer',
        cancelButtonText: 'Annuler',
        confirmButtonColor: isDelete ? '#e11d48' : '#0f172a'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
};

document.addEventListener('submit', (event) => {
    const form = event.target;
    if (!(form instanceof HTMLFormElement)) {
        return;
    }

    const methodInput = form.querySelector('input[name="_method"]');
    const method = (methodInput?.value || form.method || 'POST').toUpperCase();

    if (!['PUT', 'PATCH', 'DELETE'].includes(method)) {
        return;
    }

    if (form.dataset.swal === 'off') {
        return;
    }

    event.preventDefault();
    confirmFormSubmit(form, method);
});
