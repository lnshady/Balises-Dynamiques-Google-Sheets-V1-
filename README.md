# Google Sheets Dynamic Tags (V3)

An ultra-lightweight WordPress plugin to synchronize and display dynamic data (text, prices, images) from a published Google Sheets CSV — using a single shortcode. No admin panel, no API key, no iframe.

> **Perfect for**: restaurant daily menus, hotel rates, event schedules, product prices, or any content that changes often and should be edited in Google Sheets, not WordPress.

![WordPress plugin screenshot](https://github.com/user-attachments/assets/898a2b62-3207-4a13-ae1e-9b39dbbe188b)

---

## Features

- **Zero config** — publish your Google Sheet as CSV, paste the URL in one constant, done.
- **Cell-level shortcodes** — `[menu row="2" col="3"]` fetches exactly one cell, not an entire table.
- **Smart cache** — uses WordPress transients with time-slice rotation to prevent cache stampede.
- **Image support** — `type="image"` renders an `<img>` tag from a URL in the cell.
- **Cache-busting** — bypasses Google's CDN/proxy cache for fresh data every time.
- **3-second timeout** — your page won't hang if the sheet is unreachable.

---

## Usage

### Basic text cell
```
[menu row="2" col="3"]
```

### Image cell (cell contains an image URL)
```
[menu row="1" col="4" type="image"]
```

### With fallback and custom cache
```
[menu row="5" col="2" type="text" default="Prix non disponible" cache="120"]
```

### Available attributes

| Attribute | Default   | Description                                  |
|-----------|-----------|----------------------------------------------|
| `row`     | `1`       | Row index (1-based)                          |
| `col`     | `1`       | Column index (1-based)                       |
| `type`    | `text`    | `text` or `image`/`img`                      |
| `default` | `—`       | Fallback display if cell is empty or fails   |
| `cache`   | `60`      | Cache duration in seconds                    |

---

## Configuration

Open `google-sheets-dynamic-tags.php` and replace the URL constant:

```php
define('GSHEETS_CSV_URL', 'https://docs.google.com/spreadsheets/d/.../pub?output=csv');
```

**How to get your CSV URL:**

1. Open your Google Sheet
2. **File → Share → Publish to web**
3. In the popup, choose **"Comma-separated values (.csv)"**
4. Copy the generated link and paste it into the constant above

---

## Requirements

- WordPress 5.0+
- PHP 7.4+

## Installation

1. Upload `google-sheets-dynamic-tags.php` to `/wp-content/plugins/google-sheets-dynamic-tags/`
2. Activate the plugin from **Plugins** screen
3. Set your Google Sheets CSV URL in the plugin file
4. Use `[menu ...]` in any post, page, or widget

---

## License

[MIT](LICENSE) © 2026 Lounes G
