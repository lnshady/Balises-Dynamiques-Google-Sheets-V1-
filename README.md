# Google Sheets Dynamic Tags (V3)

An ultra-lightweight WordPress plugin to synchronize and display dynamic data (text, prices, images) directly from a published Google Sheets CSV file using an optimized shortcode.

## Features
- Smart Global Cache: Uses native WordPress transients to prevent Cache Stampede.
- Robust CSV Parsing: Full implementation of str_getcsv to handle complex cell structures and line breaks.
- Security & Performance: Strict 3-second HTTP request timeout with built-in cache buster to bypass CDN/edge caching.

## Visual Demo

1. Google Sheets Data Source: Organize your content (e.g., Dishes, Descriptions, Prices).
   ![Google Sheets Source](assets/Google%20sheets.png)

2. Elementor / WordPress Integration: Drop the shortcode directly into your layouts (e.g., [menu row="2" col="1"]).
   ![Shortcode Integration](assets/plug%20in%20short%20code.png)

3. Page Architecture: Perfect for managing structured layouts, localized menus, or dedicated sections (Menus, Allergens, Wine lists).
   ![WordPress Pages Overview](assets/trad%20menu%20allerg.png)

## Usage

[menu row="2" col="3" type="text" default="Loading..." cache="60"]

### Available Attributes:
- row: Row index (1-based).
- col: Column index (1-based).
- type: text (default) or image/img (renders a secure <img> tag).
- default: Fallback value if the cell is empty or if the request fails.
- cache: Transient expiration time in seconds (default: 60).

## Configuration

To change the source Google Sheets URL, modify the constant at the top of the main PHP file:
define('RDL_SHEET_URL', 'https://docs.google.com/spreadsheets/d/.../pub?output=csv');

## License
This project is licensed under the MIT License - see the LICENSE file for details.
