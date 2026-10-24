# Mango Masonry Gallery for Elementor

A lightweight masonry image gallery with a full-screen lightbox, available as an Elementor widget and a shortcode.

Images keep their natural aspect ratio and flow into columns. Clicking an image opens it in a full-screen lightbox with previous/next navigation, keyboard support and an image counter.

**WordPress.org:** https://wordpress.org/plugins/mango-masonry-gallery/

## Features

- Elementor widget with a live masonry preview in the editor
- Responsive columns (1–6) for desktop, tablet and mobile
- Adjustable gap, corner radius and image size
- Full-screen lightbox with previous/next buttons, keyboard navigation (←, →, Esc) and an image counter
- Custom hover overlay, lightbox accent and lightbox background colors
- Accessible: keyboard-focusable images, ARIA labels, and focus returns to the image after the lightbox closes
- No jQuery and no external libraries; CSS and JS load only on pages that show a gallery
- Works without Elementor through the `[mango_masonry_gallery]` shortcode

## Requirements

- WordPress 5.8 or later
- PHP 7.4 or later
- Elementor (optional; only needed for the widget)

## Installation

### From WordPress.org (recommended)

1. In your WordPress admin, go to **Plugins → Add New**.
2. Search for **Mango Masonry Gallery** and click **Install Now**, then **Activate**.

### From this repository

1. Download this repository as a ZIP (or clone it) into `/wp-content/plugins/mango-masonry-gallery/`.
2. Activate the plugin from the **Plugins** screen.

## Usage

### Elementor

Search for **Mango Masonry Gallery** in the Elementor widgets panel and drag it onto the page. Add images in the Content tab, then adjust layout and colors.

> The lightbox is disabled inside the Elementor editor, because clicks there select and edit the widget. Preview or view the page to try it.

### Shortcode

```
[mango_masonry_gallery ids="12,34,56" columns="3"]
```

| Attribute        | Description                                   | Default               |
| ---------------- | --------------------------------------------- | --------------------- |
| `ids`            | Comma-separated attachment IDs (required)     | —                     |
| `columns`        | Desktop columns, 1–6                          | `3`                   |
| `columns_tablet` | Tablet columns                                | `2` (or fewer if `columns` is lower) |
| `columns_mobile` | Mobile columns                                | `1`                   |
| `gap`            | Space between images, e.g. `20px`             | `7px`                 |
| `radius`         | Image corner radius, e.g. `4px`               | `2px`                 |
| `size`           | Registered image size used in the grid        | `large`               |
| `overlay`        | Hover overlay color                           | `rgba(20,20,20,0.45)` |
| `accent`         | Lightbox controls color                       | `#d4b5a5`             |
| `lightbox_bg`    | Lightbox background color                     | `rgba(10,7,5,0.96)`   |

Images flow top to bottom in each column, then on to the next column.

## Changelog

See [readme.txt](readme.txt) for the full changelog.

## License

Mango Masonry Gallery is licensed under the [GNU General Public License v2 or later](https://www.gnu.org/licenses/gpl-2.0.html).
