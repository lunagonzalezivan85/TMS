/**
 * UI.js
 * Helpers globales para la interfaz de usuario (Vanilla JS + Bootstrap 5 SDK)
 */
class UI {
    /**
     * Muestra una alerta (Toast o Modal según el caso)
     */
    static showToast(type = 'success', message = '') {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        Toast.fire({
            icon: type,
            title: message
        });
    }

    /**
     * Alterna la visibilidad de un spinner en un botón
     */
    static toggleButtonLoading(btn, isLoading = true, originalHtml = null) {
        if (!btn) return;
        
        if (isLoading) {
            btn.dataset.original = btn.innerHTML;
            btn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Cargando...`;
            btn.disabled = true;
        } else {
            btn.innerHTML = originalHtml || btn.dataset.original || 'Enviar';
            btn.disabled = false;
        }
    }

    /**
     * Muestra/Oculta un skeleton en un contenedor
     */
    static toggleSkeleton(containerId, isShow = true) {
        const container = document.getElementById(containerId);
        if (!container) return;

        if (isShow) {
            container.classList.add('loading-skeleton');
        } else {
            container.classList.remove('loading-skeleton');
        }
    }
}

window.UI = UI;
