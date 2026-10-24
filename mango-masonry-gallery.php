<?php
/**
 * Plugin Name:       Mango Masonry Gallery for Elementor
 * Plugin URI:        https://shihabiiuc.com/
 * Description:       A lightweight masonry image gallery with a full-screen lightbox (prev/next, keyboard navigation, counter). Use the "Mango Masonry Gallery" Elementor widget or the [mango_masonry_gallery] shortcode.
 * Version:           1.0.0
 * Author:            Shihab
 * Author URI:        https://shihabiiuc.com/shihab
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       mango-masonry-gallery
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Elementor tested up to: 4.3.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MANGO_MG_VERSION', '1.0.0' );
define( 'MANGO_MG_FILE', __FILE__ );
define( 'MANGO_MG_URL', plugin_dir_url( __FILE__ ) );

final class Mango_Masonry_Gallery {

	const HANDLE = 'mango-masonry-gallery';

	/** @var self|null */
	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'init', array( $this, 'register_assets' ) );
		add_shortcode( 'mango_masonry_gallery', array( $this, 'render_shortcode' ) );
		add_action( 'elementor/widgets/register', array( $this, 'register_elementor_widget' ) );
		add_action( 'elementor/elements/categories_registered', array( $this, 'register_elementor_category' ) );
		add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'enqueue_editor_styles' ) );
	}

	public function register_assets() {
		wp_register_style( self::HANDLE, MANGO_MG_URL . 'assets/css/mango-masonry-gallery.css', array(), MANGO_MG_VERSION );
		wp_register_script( self::HANDLE, MANGO_MG_URL . 'assets/js/mango-masonry-gallery.js', array(), MANGO_MG_VERSION, true );
		wp_localize_script(
			self::HANDLE,
			'mangoMasonryGalleryL10n',
			array(
				'viewer'  => __( 'Image viewer', 'mango-masonry-gallery' ),
				'close'   => __( 'Close lightbox', 'mango-masonry-gallery' ),
				'prev'    => __( 'Previous image', 'mango-masonry-gallery' ),
				'next'    => __( 'Next image', 'mango-masonry-gallery' ),
				/* translators: %d: image number. */
				'imageAlt' => __( 'Gallery image %d', 'mango-masonry-gallery' ),
			)
		);
	}

	/**
	 * Custom widget icon for the Elementor panel.
	 */
	public function enqueue_editor_styles() {
		wp_enqueue_style( self::HANDLE . '-editor', MANGO_MG_URL . 'assets/css/editor.css', array(), MANGO_MG_VERSION );
	}

	public function register_elementor_category( $elements_manager ) {
		$elements_manager->add_category(
			'mango-masonry-gallery',
			array(
				'title' => __( 'Mango Masonry Gallery', 'mango-masonry-gallery' ),
				'icon'  => 'fa fa-plug',
			)
		);
	}

	public function register_elementor_widget( $widgets_manager ) {
		require_once __DIR__ . '/includes/class-mango-masonry-gallery-widget.php';
		$widgets_manager->register( new Mango_Masonry_Gallery_Widget() );
	}

	/**
	 * Render the gallery markup.
	 *
	 * Style arguments left as null are not output inline, so they can be
	 * controlled by stylesheet rules instead (used by the Elementor widget).
	 *
	 * @param array $args Gallery arguments.
	 * @return string
	 */
	public function render_gallery( $args ) {
		$defaults = array(
			'image_ids'      => array(),
			'columns'        => null,
			'columns_tablet' => null,
			'columns_mobile' => null,
			'gap'            => null,
			'radius'         => null,
			'overlay'        => null,
			'accent'         => null,
			'lightbox_bg'    => null,
			'image_size'     => 'large',
		);
		$args = wp_parse_args( $args, $defaults );

		$image_ids = array_filter( array_map( 'absint', (array) $args['image_ids'] ) );
		if ( empty( $image_ids ) ) {
			return '';
		}

		wp_enqueue_style( self::HANDLE );
		wp_enqueue_script( self::HANDLE );

		$style_vars = '';
		foreach ( array( 'columns', 'columns_tablet', 'columns_mobile' ) as $key ) {
			if ( null !== $args[ $key ] ) {
				$style_vars .= sprintf( '--mmg-%s:%d;', str_replace( '_', '-', $key ), max( 1, min( 6, (int) $args[ $key ] ) ) );
			}
		}
		foreach ( array( 'gap', 'radius' ) as $key ) {
			$value = self::sanitize_css_length( $args[ $key ] );
			if ( '' !== $value ) {
				$style_vars .= sprintf( '--mmg-%s:%s;', $key, $value );
			}
		}
		foreach ( array( 'overlay', 'accent', 'lightbox_bg' ) as $key ) {
			$value = self::sanitize_css_color( $args[ $key ] );
			if ( '' !== $value ) {
				$style_vars .= sprintf( '--mmg-%s:%s;', str_replace( '_', '-', $key ), $value );
			}
		}

		ob_start();
		?>
		<div class="mmg-grid" id="<?php echo esc_attr( 'mmg-' . wp_unique_id() ); ?>"<?php echo $style_vars ? ' style="' . esc_attr( $style_vars ) . '"' : ''; ?>>
			<?php foreach ( array_values( $image_ids ) as $index => $attachment_id ) :
				$full_src = wp_get_attachment_image_url( $attachment_id, 'full' );
				if ( ! $full_src ) {
					continue;
				}
				$alt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
				if ( ! $alt ) {
					/* translators: %d: image number. */
					$alt = sprintf( __( 'Gallery image %d', 'mango-masonry-gallery' ), $index + 1 );
				}
				/* translators: %d: image number. */
				$label = sprintf( __( 'Open image %d', 'mango-masonry-gallery' ), $index + 1 );
				?>
				<div class="mmg-grid__item">
					<div
						class="mmg-grid__btn"
						role="button"
						tabindex="0"
						aria-label="<?php echo esc_attr( $label ); ?>"
						data-src="<?php echo esc_url( $full_src ); ?>"
						data-alt="<?php echo esc_attr( $alt ); ?>"
					>
						<?php
						echo wp_get_attachment_image(
							$attachment_id,
							$args['image_size'],
							false,
							array(
								'class'   => 'mmg-grid__img',
								'loading' => $index < 4 ? 'eager' : 'lazy',
								'alt'     => $alt,
							)
						);
						?>
						<span class="mmg-grid__overlay">
							<span class="mmg-grid__zoom" aria-hidden="true">+</span>
						</span>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
		return ob_get_clean();
	}

	public function render_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'ids'            => '',
				'columns'        => 3,
				'columns_tablet' => '',
				'columns_mobile' => 1,
				'gap'            => '7px',
				'radius'         => '2px',
				'overlay'        => 'rgba(20,20,20,0.45)',
				'accent'         => '#d4b5a5',
				'lightbox_bg'    => 'rgba(10,7,5,0.96)',
				'size'           => 'large',
			),
			$atts,
			'mango_masonry_gallery'
		);

		$columns = max( 1, min( 6, (int) $atts['columns'] ) );

		return $this->render_gallery(
			array(
				'image_ids'      => explode( ',', $atts['ids'] ),
				'columns'        => $columns,
				'columns_tablet' => '' !== $atts['columns_tablet'] ? $atts['columns_tablet'] : min( 2, $columns ),
				'columns_mobile' => $atts['columns_mobile'],
				'gap'            => $atts['gap'],
				'radius'         => $atts['radius'],
				'overlay'        => $atts['overlay'],
				'accent'         => $atts['accent'],
				'lightbox_bg'    => $atts['lightbox_bg'],
				'image_size'     => sanitize_key( $atts['size'] ),
			)
		);
	}

	/**
	 * Allow a number with an optional CSS unit, e.g. "20", "20px", "1.5rem", "5%".
	 */
	public static function sanitize_css_length( $value ) {
		$value = trim( (string) $value );
		if ( preg_match( '/^\d+(\.\d+)?$/', $value ) ) {
			return $value . 'px';
		}
		return preg_match( '/^\d+(\.\d+)?(px|em|rem|%|vw|vh)$/', $value ) ? $value : '';
	}

	/**
	 * Allow hex, rgb()/rgba()/hsl()/hsla() and named colors.
	 */
	public static function sanitize_css_color( $value ) {
		$value = trim( (string) $value );
		if ( preg_match( '/^#([0-9a-f]{3,4}|[0-9a-f]{6}|[0-9a-f]{8})$/i', $value )
			|| preg_match( '/^(rgb|rgba|hsl|hsla)\([0-9.,%\s\/]+\)$/i', $value )
			|| preg_match( '/^[a-z]+$/i', $value ) ) {
			return $value;
		}
		return '';
	}
}

Mango_Masonry_Gallery::instance();
