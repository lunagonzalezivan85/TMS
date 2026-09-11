/**
 * HttpClient.js
 * Wrapper moderno para Fetch API con soporte para CSRF y manejo de errores.
 */
class HttpClient {
    /**
     * Realiza una petición GET
     * @param {string} url 
     * @param {object} options 
     */
    static async get(url, options = {}) {
        return this.request(url, { ...options, method: 'GET' });
    }

    /**
     * Realiza una petición POST
     * @param {string} url 
     * @param {object} body 
     * @param {object} options 
     */
    static async post(url, body = {}, options = {}) {
        return this.request(url, {
            ...options,
            method: 'POST',
            body: body instanceof FormData ? body : JSON.stringify(body)
        });
    }

    /**
     * Método base para todas las peticiones
     */
    static async request(url, options = {}) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        const csrfHeader = document.querySelector('meta[name="csrf-header"]')?.content || 'X-CSRF-TOKEN';

        const defaultHeaders = {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        };

        if (!(options.body instanceof FormData)) {
            defaultHeaders['Content-Type'] = 'application/json';
        }

        if (csrfToken) {
            defaultHeaders[csrfHeader] = csrfToken;
        }

        const config = {
            ...options,
            headers: {
                ...defaultHeaders,
                ...options.headers
            }
        };

        try {
            const response = await fetch(url, config);
            const data = await response.json();

            if (!response.ok) {
                throw {
                    status: response.status,
                    message: data.message || 'Error en la petición',
                    errors: data.errors || null
                };
            }

            // Actualizar token CSRF si viene en la respuesta (regeneración)
            if (data.csrf_token) {
                const meta = document.querySelector('meta[name="csrf-token"]');
                if (meta) meta.content = data.csrf_token;
            }

            return data;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    }
}

// Exportar globalmente para que esté disponible sin módulos si es necesario
window.HttpClient = HttpClient;
