<?php
/*
Plugin Name: Balises Dynamiques Google Sheets (V1)
Description: 29/01/2026: Google sheets vers wordpress Balises dynamiques optimisées avec cache global intelligent.
Version: 3.0
Author: Lounes G
*/

if (!defined('ABSPATH')) {
    exit;
}

// 🔗 TON GOOGLE SHEET (CSV publié)
$rdl_sheet_url = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vTtSI54pxXsZgUOfjc16m6WeB2zBkhWNUWxDrHQPpPe587HDjohhH4ZdsZywmRQ2vYEOAk0_uYhCtKY/pub?output=csv';

function rdl_menu_dynamic($atts) {
    global $rdl_sheet_url;

    $atts = shortcode_atts([
        'row'     => 1,
        'col'     => 1,
        'type'    => 'text',
        'default' => '',
        'cache'   => 60, // 🔥 60 secondes recommandé
    ], $atts, 'menu');

    $cache_time = absint($atts['cache']);
    $row_idx = max(0, intval($atts['row']) - 1);
    $col_idx = max(0, intval($atts['col']) - 1);
    $type    = strtolower($atts['type']);

    // 🔥 Cache global + rotation automatique (évite blocage 24h)
    $time_slice = floor(time() / $cache_time);
    $cache_key = 'rdl_menu_csv_' . $time_slice;

    $csv_data = get_transient($cache_key);

    if ($csv_data === false) {

        // Cache buster pour éviter cache Google/serveur
        $url = add_query_arg([
            'v' => $time_slice,
        ], $rdl_sheet_url);

        $response = wp_remote_get($url, [
            'timeout'    => 3,
            'sslverify'  => true,
            'user-agent' => 'RelaisDuLouvre/3.0',
        ]);

        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            return esc_html($atts['default'] ?: '—');
        }

        $csv_data = wp_remote_retrieve_body($response);

        if ($cache_time > 0) {
            set_transient($cache_key, $csv_data, $cache_time);
        }
    }

    // 🔽 Traitement CSV
    $rows = array_filter(explode("\n", str_replace("\r", "", trim($csv_data))));

    if (!isset($rows[$row_idx])) {
        $value = $atts['default'] ?: '';
    } else {
        $separator = (strpos($rows[$row_idx], ';') !== false) ? ';' : ',';
        $cells = str_getcsv($rows[$row_idx], $separator);
        $value = isset($cells[$col_idx]) ? trim($cells[$col_idx], " \t\n\r\0\x0B\"'") : ($atts['default'] ?: '');
    }

    return rdl_format_value($value, $type);
}

function rdl_format_value($value, $type) {
    if ($type === 'img' || $type === 'image') {
        return filter_var($value, FILTER_VALIDATE_URL)
            ? '<img src="' . esc_url($value) . '" alt="Image menu" style="max-width:100%; height:auto; display:block;">'
            : '';
    }

    return esc_html($value);
}

add_shortcode('menu', 'rdl_menu_dynamic');