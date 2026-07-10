# Google Sheets Dynamic Tags (V3)

An ultra-lightweight WordPress plugin to synchronize and display dynamic data (text, prices, images) directly from a published Google Sheets CSV file using an optimized shortcode.


<img width="1341" height="228" alt="wordpress plugin" src="https://github.com/user-attachments/assets/898a2b62-3207-4a13-ae1e-9b39dbbe188b" />


## Features
- Smart Global Cache: Uses native WordPress transients to prevent Cache Stampede.
- Robust CSV Parsing: Full implementation of str_getcsv to handle complex cell structures and line breaks.
- Security & Performance: Strict 3-second HTTP request timeout with built-in cache buster to bypass CDN/edge caching.

## Visual Demo

1. Google Sheets Data Source: Organize your content (e.g., Dishes, Descriptions, Prices).
   ![Google Sheets Source]<img width="857" height="242" alt="Google sheets" src="https://github.com/user-attachments/assets/efd5726e-2c47-451f-9706-9e9364ecb0c5" />

2. Elementor / WordPress Integration: Drop the shortcode directly into your layouts (e.g., [menu row="2" col="1"]).
   ![Shortcode Integration]]<img width="1316" height="291" alt="plug in short code" src="https://github.com/user-attachments/assets/60032043-0af2-483f-8e1b-d81a5d8d5fc9" />


3. Page Architecture: Perfect for managing structured layouts, localized menus, or dedicated sections (Menus, Allergens, Wine lists).
   ![WordPress Pages Overview

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
