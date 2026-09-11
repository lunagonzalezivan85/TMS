<?php

/**
 * Date Helper
 * 
 * Funciones auxiliares para manejo de fechas
 */

if (!function_exists('time_ago')) {
    /**
     * Convierte una fecha en texto relativo (hace X tiempo)
     * 
     * @param string $datetime Fecha en formato Y-m-d H:i:s
     * @return string Texto relativo de tiempo
     */
    function time_ago($datetime) {
        if (empty($datetime) || $datetime === '0000-00-00 00:00:00') {
            return 'No especificado';
        }
        
        $time = time() - strtotime($datetime);
        $time = ($time < 1) ? 1 : $time;
        
        $tokens = [
            31536000 => 'año',
            2592000  => 'mes',
            604800   => 'semana',
            86400    => 'día',
            3600     => 'hora',
            60       => 'minuto',
            1        => 'segundo'
        ];
        
        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            
            if ($numberOfUnits > 1) {
                // Plurales
                $plurals = [
                    'año' => 'años',
                    'mes' => 'meses',
                    'semana' => 'semanas',
                    'día' => 'días',
                    'hora' => 'horas',
                    'minuto' => 'minutos',
                    'segundo' => 'segundos'
                ];
                $text = $plurals[$text] ?? $text;
            }
            
            return 'Hace ' . $numberOfUnits . ' ' . $text;
        }
        
        return 'Hace un momento';
    }
}

if (!function_exists('format_date')) {
    /**
     * Formatea una fecha en formato legible
     * 
     * @param string $date Fecha en formato Y-m-d H:i:s
     * @param string $format Formato de salida (default: d/m/Y H:i)
     * @return string Fecha formateada
     */
    function format_date($date, $format = 'd/m/Y H:i') {
        if (empty($date) || $date === '0000-00-00 00:00:00') {
            return 'No especificado';
        }
        
        return date($format, strtotime($date));
    }
}
