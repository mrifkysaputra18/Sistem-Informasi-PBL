<?php

if (!function_exists('format_number_id')) {
    /**
     * Format number with Indonesian format (comma as decimal separator)
     * 
     * @param mixed $number
     * @param int $decimals
     * @return string
     */
    function format_number_id($number, int $decimals = 2): string
    {
        if ($number === null || $number === '') {
            return '0' . ($decimals > 0 ? ',' . str_repeat('0', $decimals) : '');
        }
        
        // Convert to float
        $number = (float) $number;
        
        // Format with comma as decimal separator and dot as thousands separator
        return number_format($number, $decimals, ',', '.');
    }
}

if (!function_exists('parse_number_id')) {
    /**
     * Parse Indonesian formatted number to float
     * 
     * @param string $number
     * @return float
     */
    function parse_number_id(string $number): float
    {
        // Remove thousands separator (dot)
        $number = str_replace('.', '', $number);
        
        // Replace decimal separator (comma) with dot
        $number = str_replace(',', '.', $number);
        
        return (float) $number;
    }
}
