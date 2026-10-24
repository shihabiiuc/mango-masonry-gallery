=== Mango Masonry Gallery for Elementor ===
Contributors: shihab0915
Tags: gallery, masonry, elementor, lightbox, images
Requires at least: 5.8
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A lightweight masonry image gallery with a full-screen lightbox, as an Elementor widget and a shortcode.

== Description ==

Mango Masonry Gallery adds a clean, responsive masonry gallery to your site. Images keep their natural aspect ratio and flow into columns, and clicking an image opens it in a full-screen lightbox.

Features:

* Elementor widget with a live masonry preview in the editor
* Responsive columns (1–6) for desktop, tablet and mobile
* Adjustable gap, corner radius and image size
* Full-screen lightbox with previous/next buttons, keyboard navigation (←, →, Esc) and an image counter
* Custom hover overlay, lightbox accent and lightbox background colors
* Accessible: keyboard-focusable images, ARIA labels, focus is returned after closing the lightbox
* No jQuery and no external libraries; CSS and JS load only on pages that show a gallery
* Works without Elementor through the `[mango_masonry_gallery]` shortcode

= Shortcode =

`[mango_masonry_gallery ids="12,34,56" columns="3"]`

Available attributes:

* `ids` – comma-separated attachment IDs (required)
* `columns` – desktop columns, 1–6 (default 3)
* `columns_tablet` – tablet columns (default: 2, or fewer if `columns` is lower)
* `columns_mobile` – mobile columns (default 1)
* `gap` – space between images, e.g. `20px` (default `7px`)
* `radius` – image corner radius, e.g. `4px` (default `2px`)
* `size` – registered image size used in the grid (default `large`)
* `overlay` – hover overlay color (default `rgba(20,20,20,0.45)`)
* `accent` – lightbox controls color (default `#d4b5a5`)
* `lightbox_bg` – lightbox background color (default `rgba(10,7,5,0.96)`)

== Installation ==

1. Upload the `mango-masonry-gallery` folder to `/wp-content/plugins/`, or install it from the Plugins screen.
2. Activate the plugin.
3. In Elementor, search for "Mango Masonry Gallery" in the widgets panel and drag it onto the page. Or use the shortcode in any post or page.

== Frequently Asked Questions ==

= Do I need Elementor? =

No. The Elementor widget is only registered when Elementor is active. Without it, use the `[mango_masonry_gallery]` shortcode.

= Why doesn't the lightbox open in the Elementor editor? =

Clicks in the editor are used to select and edit the widget, so the lightbox is disabled there. Preview or view the page to try it.

= In which order are images shown? =

Images flow top to bottom in each column, then on to the next column.

== Screenshots ==

1. Masonry gallery on the front end.
2. Full-screen lightbox.
3. Widget settings in the Elementor editor.

== Changelog ==

= 1.0.0 =
* Initial release.
