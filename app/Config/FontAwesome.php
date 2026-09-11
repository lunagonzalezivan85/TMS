<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class FontAwesome extends BaseConfig
{
    /**
     * Iconos de FontAwesome organizados por categorías
     * Para uso en CRUD de menús dinámicos
     */
    
    /**
     * Iconos de Navegación y Interfaz
     */
    public array $navigation = [
        'fas fa-home' => 'Inicio',
        'fas fa-tachometer-alt' => 'Dashboard',
        'fas fa-bars' => 'Menú',
        'fas fa-th' => 'Grid',
        'fas fa-th-large' => 'Grid Grande',
        'fas fa-th-list' => 'Lista',
        'fas fa-list' => 'Lista Simple',
        'fas fa-list-ul' => 'Lista con Viñetas',
        'fas fa-list-ol' => 'Lista Numerada',
        'fas fa-sitemap' => 'Mapa del Sitio',
        'fas fa-compass' => 'Brújula',
        'fas fa-map' => 'Mapa',
        'fas fa-map-marker-alt' => 'Marcador',
        'fas fa-route' => 'Ruta',
        'fas fa-directions' => 'Direcciones'
    ];

    /**
     * Iconos de Vehículos y Transporte
     */
    public array $vehicles = [
        'fas fa-car' => 'Automóvil',
        'fas fa-car-side' => 'Auto Lateral',
        'fas fa-truck' => 'Camión',
        'fas fa-truck-moving' => 'Camión en Movimiento',
        'fas fa-bus' => 'Autobús',
        'fas fa-motorcycle' => 'Motocicleta',
        'fas fa-bicycle' => 'Bicicleta',
        'fas fa-taxi' => 'Taxi',
        'fas fa-shuttle-van' => 'Van',
        'fas fa-trailer' => 'Remolque',
        'fas fa-gas-pump' => 'Gasolinera',
        'fas fa-oil-can' => 'Aceite',
        'fas fa-tools' => 'Herramientas',
        'fas fa-wrench' => 'Llave Inglesa',
        'fas fa-cog' => 'Engranaje',
        'fas fa-cogs' => 'Engranajes',
        'fas fa-tire' => 'Llanta',
        'fas fa-road' => 'Carretera',
        'fas fa-parking' => 'Estacionamiento'
    ];

    /**
     * Iconos de Usuarios y Personas
     */
    public array $users = [
        'fas fa-user' => 'Usuario',
        'fas fa-users' => 'Usuarios',
        'fas fa-user-plus' => 'Agregar Usuario',
        'fas fa-user-minus' => 'Eliminar Usuario',
        'fas fa-user-edit' => 'Editar Usuario',
        'fas fa-user-check' => 'Usuario Verificado',
        'fas fa-user-times' => 'Usuario Bloqueado',
        'fas fa-user-shield' => 'Usuario Protegido',
        'fas fa-user-tie' => 'Empleado',
        'fas fa-user-hard-hat' => 'Trabajador',
        'fas fa-user-cog' => 'Administrador',
        'fas fa-users-cog' => 'Gestión de Usuarios',
        'fas fa-id-card' => 'Identificación',
        'fas fa-id-badge' => 'Credencial',
        'fas fa-address-card' => 'Tarjeta de Contacto'
    ];

    /**
     * Iconos de Conductores y Personal
     */
    public array $drivers = [
        'fas fa-user-tie' => 'Conductor',
        'fas fa-hard-hat' => 'Operario',
        'fas fa-user-hard-hat' => 'Trabajador',
        'fas fa-clipboard-user' => 'Personal',
        'fas fa-id-card-alt' => 'Licencia',
        'fas fa-certificate' => 'Certificado',
        'fas fa-award' => 'Premio/Certificación',
        'fas fa-medal' => 'Medalla',
        'fas fa-trophy' => 'Trofeo',
        'fas fa-star' => 'Estrella',
        'fas fa-crown' => 'Corona'
    ];

    /**
     * Iconos de Documentos y Archivos
     */
    public array $documents = [
        'fas fa-file' => 'Archivo',
        'fas fa-file-alt' => 'Documento',
        'fas fa-file-text' => 'Archivo de Texto',
        'fas fa-file-pdf' => 'PDF',
        'fas fa-file-word' => 'Word',
        'fas fa-file-excel' => 'Excel',
        'fas fa-file-powerpoint' => 'PowerPoint',
        'fas fa-file-image' => 'Imagen',
        'fas fa-file-video' => 'Video',
        'fas fa-file-audio' => 'Audio',
        'fas fa-file-archive' => 'Archivo Comprimido',
        'fas fa-file-code' => 'Código',
        'fas fa-file-csv' => 'CSV',
        'fas fa-folder' => 'Carpeta',
        'fas fa-folder-open' => 'Carpeta Abierta',
        'fas fa-folder-plus' => 'Nueva Carpeta',
        'fas fa-archive' => 'Archivo',
        'fas fa-clipboard' => 'Portapapeles',
        'fas fa-clipboard-list' => 'Lista de Verificación'
    ];

    /**
     * Iconos de Finanzas y Contabilidad
     */
    public array $finance = [
        'fas fa-dollar-sign' => 'Dólar',
        'fas fa-euro-sign' => 'Euro',
        'fas fa-pound-sign' => 'Libra',
        'fas fa-yen-sign' => 'Yen',
        'fas fa-coins' => 'Monedas',
        'fas fa-money-bill' => 'Billete',
        'fas fa-money-bill-wave' => 'Dinero',
        'fas fa-credit-card' => 'Tarjeta de Crédito',
        'fas fa-wallet' => 'Billetera',
        'fas fa-piggy-bank' => 'Alcancía',
        'fas fa-chart-line' => 'Gráfico Lineal',
        'fas fa-chart-bar' => 'Gráfico de Barras',
        'fas fa-chart-pie' => 'Gráfico Circular',
        'fas fa-chart-area' => 'Gráfico de Área',
        'fas fa-calculator' => 'Calculadora',
        'fas fa-receipt' => 'Recibo',
        'fas fa-invoice' => 'Factura'
    ];

    /**
     * Iconos de Reportes y Análisis
     */
    public array $reports = [
        'fas fa-chart-line' => 'Tendencia',
        'fas fa-chart-bar' => 'Barras',
        'fas fa-chart-pie' => 'Circular',
        'fas fa-analytics' => 'Análisis',
        'fas fa-poll' => 'Encuesta',
        'fas fa-poll-h' => 'Encuesta Horizontal',
        'fas fa-clipboard-check' => 'Reporte Verificado',
        'fas fa-file-contract' => 'Contrato',
        'fas fa-file-invoice' => 'Factura',
        'fas fa-file-invoice-dollar' => 'Factura con Precio',
        'fas fa-table' => 'Tabla',
        'fas fa-database' => 'Base de Datos',
        'fas fa-server' => 'Servidor'
    ];

    /**
     * Iconos de Mantenimiento y Reparación
     */
    public array $maintenance = [
        'fas fa-tools' => 'Herramientas',
        'fas fa-wrench' => 'Llave',
        'fas fa-screwdriver' => 'Destornillador',
        'fas fa-hammer' => 'Martillo',
        'fas fa-cog' => 'Configuración',
        'fas fa-cogs' => 'Configuraciones',
        'fas fa-oil-can' => 'Aceite',
        'fas fa-spray-can' => 'Spray',
        'fas fa-fire-extinguisher' => 'Extintor',
        'fas fa-hard-hat' => 'Casco',
        'fas fa-clipboard-list' => 'Lista de Verificación',
        'fas fa-tasks' => 'Tareas',
        'fas fa-calendar-check' => 'Programado',
        'fas fa-exclamation-triangle' => 'Advertencia',
        'fas fa-times-circle' => 'Error',
        'fas fa-check-circle' => 'Completado'
    ];

    /**
     * Iconos de Acciones Generales
     */
    public array $actions = [
        'fas fa-plus' => 'Agregar',
        'fas fa-plus-circle' => 'Agregar Circular',
        'fas fa-minus' => 'Quitar',
        'fas fa-minus-circle' => 'Quitar Circular',
        'fas fa-edit' => 'Editar',
        'fas fa-pen' => 'Escribir',
        'fas fa-pencil-alt' => 'Lápiz',
        'fas fa-trash' => 'Eliminar',
        'fas fa-trash-alt' => 'Papelera',
        'fas fa-save' => 'Guardar',
        'fas fa-download' => 'Descargar',
        'fas fa-upload' => 'Subir',
        'fas fa-copy' => 'Copiar',
        'fas fa-cut' => 'Cortar',
        'fas fa-paste' => 'Pegar',
        'fas fa-undo' => 'Deshacer',
        'fas fa-redo' => 'Rehacer',
        'fas fa-search' => 'Buscar',
        'fas fa-filter' => 'Filtrar',
        'fas fa-sort' => 'Ordenar',
        'fas fa-print' => 'Imprimir',
        'fas fa-share' => 'Compartir',
        'fas fa-export' => 'Exportar',
        'fas fa-import' => 'Importar'
    ];

    /**
     * Iconos de Estado y Notificaciones
     */
    public array $status = [
        'fas fa-check' => 'Correcto',
        'fas fa-check-circle' => 'Aprobado',
        'fas fa-times' => 'Incorrecto',
        'fas fa-times-circle' => 'Rechazado',
        'fas fa-exclamation' => 'Exclamación',
        'fas fa-exclamation-circle' => 'Advertencia',
        'fas fa-exclamation-triangle' => 'Peligro',
        'fas fa-question' => 'Pregunta',
        'fas fa-question-circle' => 'Ayuda',
        'fas fa-info' => 'Información',
        'fas fa-info-circle' => 'Info Circular',
        'fas fa-bell' => 'Notificación',
        'fas fa-bell-slash' => 'Sin Notificaciones',
        'fas fa-flag' => 'Bandera',
        'fas fa-bookmark' => 'Marcador',
        'fas fa-heart' => 'Favorito',
        'fas fa-star' => 'Estrella',
        'fas fa-thumbs-up' => 'Me Gusta',
        'fas fa-thumbs-down' => 'No Me Gusta'
    ];

    /**
     * Iconos de Configuración y Sistema
     */
    public array $system = [
        'fas fa-cog' => 'Configuración',
        'fas fa-cogs' => 'Configuraciones',
        'fas fa-sliders-h' => 'Ajustes',
        'fas fa-wrench' => 'Herramientas',
        'fas fa-screwdriver' => 'Mantenimiento',
        'fas fa-database' => 'Base de Datos',
        'fas fa-server' => 'Servidor',
        'fas fa-cloud' => 'Nube',
        'fas fa-wifi' => 'WiFi',
        'fas fa-signal' => 'Señal',
        'fas fa-plug' => 'Conexión',
        'fas fa-power-off' => 'Encender/Apagar',
        'fas fa-sync' => 'Sincronizar',
        'fas fa-sync-alt' => 'Actualizar',
        'fas fa-history' => 'Historial',
        'fas fa-backup' => 'Respaldo',
        'fas fa-shield-alt' => 'Seguridad',
        'fas fa-lock' => 'Bloqueado',
        'fas fa-unlock' => 'Desbloqueado',
        'fas fa-key' => 'Clave'
    ];

    /**
     * Iconos de Comunicación
     */
    public array $communication = [
        'fas fa-phone' => 'Teléfono',
        'fas fa-mobile-alt' => 'Móvil',
        'fas fa-envelope' => 'Correo',
        'fas fa-envelope-open' => 'Correo Abierto',
        'fas fa-paper-plane' => 'Enviar',
        'fas fa-inbox' => 'Bandeja de Entrada',
        'fas fa-comment' => 'Comentario',
        'fas fa-comments' => 'Conversación',
        'fas fa-chat' => 'Chat',
        'fas fa-sms' => 'SMS',
        'fas fa-microphone' => 'Micrófono',
        'fas fa-volume-up' => 'Volumen Alto',
        'fas fa-volume-down' => 'Volumen Bajo',
        'fas fa-volume-mute' => 'Silencio'
    ];

    /**
     * Iconos de Tiempo y Calendario
     */
    public array $time = [
        'fas fa-clock' => 'Reloj',
        'fas fa-calendar' => 'Calendario',
        'fas fa-calendar-alt' => 'Calendario Alternativo',
        'fas fa-calendar-day' => 'Día',
        'fas fa-calendar-week' => 'Semana',
        'fas fa-calendar-month' => 'Mes',
        'fas fa-calendar-year' => 'Año',
        'fas fa-calendar-plus' => 'Agregar Evento',
        'fas fa-calendar-minus' => 'Quitar Evento',
        'fas fa-calendar-check' => 'Evento Confirmado',
        'fas fa-calendar-times' => 'Evento Cancelado',
        'fas fa-stopwatch' => 'Cronómetro',
        'fas fa-hourglass' => 'Reloj de Arena',
        'fas fa-hourglass-start' => 'Iniciando',
        'fas fa-hourglass-half' => 'En Progreso',
        'fas fa-hourglass-end' => 'Terminando'
    ];

    /**
     * Obtener todos los iconos organizados por categorías
     */
    public function getAllIcons(): array
    {
        return [
            'Navegación' => $this->navigation,
            'Vehículos' => $this->vehicles,
            'Usuarios' => $this->users,
            'Conductores' => $this->drivers,
            'Documentos' => $this->documents,
            'Finanzas' => $this->finance,
            'Reportes' => $this->reports,
            'Mantenimiento' => $this->maintenance,
            'Acciones' => $this->actions,
            'Estado' => $this->status,
            'Sistema' => $this->system,
            'Comunicación' => $this->communication,
            'Tiempo' => $this->time
        ];
    }

    /**
     * Obtener iconos de una categoría específica
     */
    public function getIconsByCategory(string $category): array
    {
        $property = strtolower($category);
        return property_exists($this, $property) ? $this->$property : [];
    }

    /**
     * Obtener todos los iconos en un array plano
     */
    public function getFlatIconsList(): array
    {
        $allIcons = [];
        $categories = $this->getAllIcons();
        
        foreach ($categories as $categoryName => $icons) {
            foreach ($icons as $class => $name) {
                $allIcons[$class] = $name . ' (' . $categoryName . ')';
            }
        }
        
        return $allIcons;
    }

    /**
     * Buscar iconos por nombre o clase
     */
    public function searchIcons(string $query): array
    {
        $results = [];
        $allIcons = $this->getFlatIconsList();
        
        foreach ($allIcons as $class => $description) {
            if (stripos($class, $query) !== false || stripos($description, $query) !== false) {
                $results[$class] = $description;
            }
        }
        
        return $results;
    }

    /**
     * Verificar si un icono existe
     */
    public function iconExists(string $iconClass): bool
    {
        $allIcons = $this->getFlatIconsList();
        return array_key_exists($iconClass, $allIcons);
    }

    /**
     * Obtener nombre descriptivo de un icono
     */
    public function getIconName(string $iconClass): string
    {
        $allIcons = $this->getFlatIconsList();
        return $allIcons[$iconClass] ?? 'Icono Desconocido';
    }
}
