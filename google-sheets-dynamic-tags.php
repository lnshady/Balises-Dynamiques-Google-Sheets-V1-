<?php
/**
 * Plugin Name: Google Sheets Dynamic Tags
 * Plugin URI:  https://github.com/lnshady/Balises-Dynamiques-Google-Sheets-V1-
 * Description: Connect your WordPress site directly to a published Google Sheets CSV. Display live data (text, prices, images) anywhere with a simple shortcode — no admin panel needed.
 * Version:     3.0
 * Author:      Lounes G
 * Author URI:  https://github.com/lnshady
 * License:     MIT
 * License URI: https://opensource.org/licenses/MIT
 */

if (!defined('ABSPATH')) {
    exit;
}

/*
 * ✏️ CONFIGURE YOUR GOOGLE SHEET URL
 * ──────────────────────────────────
 * 1. Create a Google Sheet with your data (row = entry, col = field)
 * 2. File → Share → Publish to web → "Comma-separated values (.csv)"
 * 3. Copy the published CSV URL and paste it below
 */
define('GSHEETS_CSV_URL', 'https://docs.google.com/spreadsheets/d/e/2PACX-1vTtSI54pxXsZgUOfjc16m6WeB2zBkhWNUWxDrHQPpPe587HDjohhH4ZdsZywmRQ2vYEOAk0_uYhCtKY/pub?output=csv');

/**
 * Shortcode handler: [menu row="1" col="1" type="text" default="..." cache="60"]
 *
 * Attributes:
 *   row     - Row index (1-based)
 *   col     - Column index (1-based)
 *   type    - Output type: "text" (default) or "image"/"img"
 *   default - Fallback text if cell is empty
 *   cache   - Cache duration in seconds (default: 60)
 */
function gs_dynamic_tag_shortcode($atts) {
    $atts = shortcode_atts([
        'row'     => 1,
        'col'     => 1,
        'type'    => 'text',
        'default' => '',
        'cache'   => 60,
    ], $atts, 'menu');

    $cache_time = absint($atts['cache']);
    $row_idx    = max(0, intval($atts['row']) - 1);
    $col_idx    = max(0, intval($atts['col']) - 1);
    $type       = strtolower($atts['type']);

    // ⌛ Time-sliced cache key — rotates every $cache_time seconds to prevent stale data
    $time_slice = floor(time() / $cache_time);
    $cache_key  = 'gs_dynamic_csv_' . $time_slice;

    $csv_data = get_transient($cache_key);

    if ($csv_data === false) {
        // Cache-buster query param to bypass CDN/proxy caching
        $url = add_query_arg(['v' => $time_slice], GSHEETS_CSV_URL);

        $response = wp_remote_get($url, [
            'timeout'    => 3,
            'sslverify'  => true,
            'user-agent' => 'GoogleSheetsDynamicTags/3.0',
        ]);

        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            return esc_html($atts['default'] ?: '—');
        }

        $csv_data = wp_remote_retrieve_body($response);

        if ($cache_time > 0) {
            set_transient($cache_key, $csv_data, $cache_time);
        }
    }

    // Parse CSV
    $rows = array_filter(explode("\n", str_replace("\r", "", trim($csv_data))));

    if (!isset($rows[$row_idx])) {
        $value = $atts['default'] ?: '';
    } else {
        $separator = (strpos($rows[$row_idx], ';') !== false) ? ';' : ',';
        $cells     = str_getcsv($rows[$row_idx], $separator);
        $value     = isset($cells[$col_idx])
            ? trim($cells[$col_idx], " \t\n\r\0\x0B\"'")
            : ($atts['default'] ?: '');
    }

    return gs_format_value($value, $type);
}

/**
 * Format a cell value for output.
 *
 * @param string $value Raw cell content
 * @param string $type  "text" or "image"/"img"
 * @return string Escaped HTML
 */
function gs_format_value($value, $type) {
    if ($type === 'img' || $type === 'image') {
        return filter_var($value, FILTER_VALIDATE_URL)
            ? '<img src="' . esc_url($value) . '" alt="" style="max-width:100%; height:auto; display:block;">'
            : '';
    }

    return esc_html($value);
}

add_shortcode('menu', 'gs_dynamic_tag_shortcode');
