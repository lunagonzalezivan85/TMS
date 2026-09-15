<?php

/**
 * vehiculo_helper.php
 *
 * Funciones reutilizables para presentación del módulo Vehículos.
 * Uso en vistas: helper('vehiculo') o cargar en el controller con helper('vehiculo').
 */

if (!function_exists('vehiculo_estado_badge')) {
    /**
     * Devuelve un <span class="badge"> de Bootstrap 5 para el estado dado.
     *
     * @param string $estado  ACTIVO | INACTIVO | EN REPARACION | EN MANTENIMIENTO
     */
    function vehiculo_estado_badge(string $estado): string
    {
        $map = [
            'ACTIVO'           => ['bg' => 'success',   'label' => 'Activo'],
            'INACTIVO'         => ['bg' => 'secondary', 'label' => 'Inactivo'],
            'EN REPARACION'    => ['bg' => 'warning',   'label' => 'En Reparación'],
            'EN MANTENIMIENTO' => ['bg' => 'info',      'label' => 'En Mantenimiento'],
        ];

        $cfg   = $map[$estado] ?? ['bg' => 'secondary', 'label' => esc($estado)];
        $class = 'bg-' . $cfg['bg'];

        return '<span class="badge ' . $class . '">' . $cfg['label'] . '</span>';
    }
}

if (!function_exists('vehiculo_estado_class')) {
    /**
     * Devuelve solo la clase Bootstrap (sin el tag) para uso en PHP puro.
     *
     * @param string $estado
     * @return string  e.g. "success" | "secondary" | "warning" | "info"
     */
    function vehiculo_estado_class(string $estado): string
    {
        $map = [
            'ACTIVO'           => 'success',
            'INACTIVO'         => 'secondary',
            'EN REPARACION'    => 'warning',
            'EN MANTENIMIENTO' => 'info',
        ];

        return $map[$estado] ?? 'secondary';
    }
}

if (!function_exists('vehiculo_estado_label')) {
    /**
     * Devuelve el texto legible del estado.
     *
     * @param string $estado
     * @return string  e.g. "Activo" | "Inactivo" | "En Reparación" | "En Mantenimiento"
     */
    function vehiculo_estado_label(string $estado): string
    {
        $map = [
            'ACTIVO'           => 'Activo',
            'INACTIVO'         => 'Inactivo',
            'EN REPARACION'    => 'En Reparación',
            'EN MANTENIMIENTO' => 'En Mantenimiento',
        ];

        return $map[$estado] ?? $estado;
    }
}
