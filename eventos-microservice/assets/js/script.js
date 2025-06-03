/**
 * script.js - Gestor de Eventos
 * Maneja las interacciones del usuario y las llamadas AJAX
 */

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar todos los handlers cuando el DOM esté listo
    initFormSubmissions();
    initConfirmations();
    initDatePickers();
    initOtherInteractions();
});

/**
 * Inicializa los manejadores para los envíos de formularios
 */
function initFormSubmissions() {
    // Manejar envío de formularios con AJAX
    document.querySelectorAll('form[data-ajax]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            submitFormViaAJAX(this);
        });
    });

    // Validación adicional para el formulario de eventos
    const eventForm = document.getElementById('evento-form');
    if (eventForm) {
        eventForm.addEventListener('submit', function(e) {
            if (!validateEventForm(this)) {
                e.preventDefault();
            }
        });
    }
}

/**
 * Envía un formulario via AJAX
 * @param {HTMLFormElement} form 
 */
function submitFormViaAJAX(form) {
    const formData = new FormData(form);
    const action = form.getAttribute('action');
    const method = form.getAttribute('method') || 'POST';

    fetch(action, {
        method: method,
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message || 'Operación exitosa');
            if (form.dataset.redirect) {
                setTimeout(() => {
                    window.location.href = form.dataset.redirect;
                }, 1500);
            }
        } else {
            showAlert('danger', data.message || 'Error en la operación');
        }
    })
    .catch(error => {
        showAlert('danger', 'Error de conexión: ' + error.message);
    });
}

/**
 * Valida el formulario de eventos
 * @param {HTMLFormElement} form 
 * @returns {boolean}
 */
function validateEventForm(form) {
    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.classList.add('is-invalid');
            field.nextElementSibling?.classList.add('d-block');
        } else {
            field.classList.remove('is-invalid');
            field.nextElementSibling?.classList.remove('d-block');
        }
    });

    // Validación adicional para fechas
    const fechaInput = form.querySelector('input[name="fecha"]');
    if (fechaInput && fechaInput.value) {
        const selectedDate = new Date(fechaInput.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        if (selectedDate < today) {
            isValid = false;
            showAlert('warning', 'La fecha del evento no puede ser en el pasado');
            fechaInput.classList.add('is-invalid');
        }
    }

    return isValid;
}

/**
 * Inicializa los diálogos de confirmación
 */
function initConfirmations() {
    // Confirmación para eliminar eventos
    document.querySelectorAll('[data-confirm]').forEach(button => {
        button.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm') || '¿Estás seguro de realizar esta acción?';
            if (!confirm(message)) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    });

    // Confirmación especial para cancelar eventos
    const cancelButtons = document.querySelectorAll('.btn-cancel-event');
    cancelButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const eventId = this.dataset.eventId;
            const eventName = this.dataset.eventName;
            
            Swal.fire({
                title: `Cancelar evento "${eventName}"`,
                text: '¿Estás seguro que deseas cancelar este evento?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, cancelar',
                cancelButtonText: 'No, volver',
                input: 'textarea',
                inputLabel: 'Motivo de cancelación (opcional)',
                inputPlaceholder: 'Describe el motivo de la cancelación...'
            }).then((result) => {
                if (result.isConfirmed) {
                    cancelEvent(eventId, result.value);
                }
            });
        });
    });
}

/**
 * Cancela un evento via AJAX
 * @param {string} eventId 
 * @param {string} motivo 
 */
function cancelEvent(eventId, motivo) {
    fetch(`../../api/eventos_delete.php?id=${eventId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ motivo })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', 'Evento cancelado correctamente');
            setTimeout(() => {
                window.location.href = 'listar.php';
            }, 1500);
        } else {
            showAlert('danger', data.message || 'Error al cancelar el evento');
        }
    })
    .catch(error => {
        showAlert('danger', 'Error de conexión: ' + error.message);
    });
}

/**
 * Inicializa los datepickers
 */
function initDatePickers() {
    // Configuración básica para inputs de fecha
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        // Establecer fecha mínima como hoy
        input.min = new Date().toISOString().split('T')[0];
        
        // Opcional: Usar un datepicker más avanzado
        if (typeof flatpickr !== 'undefined') {
            flatpickr(input, {
                dateFormat: 'Y-m-d',
                minDate: 'today'
            });
        }
    });
}

/**
 * Inicializa otras interacciones
 */
function initOtherInteractions() {
    // Toggle para mostrar/ocultar secciones
    document.querySelectorAll('[data-toggle]').forEach(toggle => {
        toggle.addEventListener('click', function() {
            const target = document.querySelector(this.dataset.target);
            if (target) {
                target.classList.toggle('d-none');
            }
        });
    });

    // Manejar tabs
    document.querySelectorAll('.nav-tabs a').forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            const tabId = this.getAttribute('href');
            document.querySelectorAll('.tab-pane').forEach(pane => {
                pane.classList.remove('active');
            });
            document.querySelector(tabId).classList.add('active');
            
            // Actualizar estado activo de los tabs
            this.parentNode.querySelectorAll('a').forEach(a => {
                a.classList.remove('active');
            });
            this.classList.add('active');
        });
    });
}

/**
 * Muestra una alerta en la interfaz
 * @param {string} type - Tipo de alerta (success, danger, warning, info)
 * @param {string} message - Mensaje a mostrar
 * @param {number} [timeout=5000] - Tiempo en ms para ocultar la alerta (0 para no ocultar)
 */
function showAlert(type, message, timeout = 5000) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    const container = document.querySelector('.alerts-container') || document.body;
    container.prepend(alertDiv);
    
    if (timeout > 0) {
        setTimeout(() => {
            alertDiv.classList.remove('show');
            setTimeout(() => alertDiv.remove(), 150);
        }, timeout);
    }
}

// Funciones utilitarias
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('es-ES', options);
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('es-AR', { style: 'currency', currency: 'ARS' }).format(amount);
}