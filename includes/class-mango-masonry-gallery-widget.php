<?php
/**
 * Elementor widget for Mango Masonry Gallery.
 * Loaded only when Elementor is active — see Mango_Masonry_Gallery::register_elementor_widget().
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

class Mango_Masonry_Gallery_Widget extends Widget_Base {

	public function get_name() {
		return 'mango-masonry-gallery';
	}

	public function get_title() {
		return __( 'Mango Masonry Gallery', 'mango-masonry-gallery' );
	}

	public function get_icon() {
		return 'mmg-widget-icon';
	}

	public function get_categories() {
		return array( 'mango-masonry-gallery' );
	}

	public function get_keywords() {
		return array( 'gallery', 'masonry', 'images', 'lightbox', 'photos', 'mango' );
	}

	public function get_style_depends() {
		return array( Mango_Masonry_Gallery::HANDLE );
	}

	public function get_script_depends() {
		return array( Mango_Masonry_Gallery::HANDLE );
	}

	protected function register_controls() {

		// ── Content: Images ─────────────────────────────────────────────
		$this->start_controls_section(
			'section_images',
			array(
				'label' => __( 'Images', 'mango-masonry-gallery' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'gallery_images',
			array(
				'label'   => __( 'Add Images', 'mango-masonry-gallery' ),
				'type'    => Controls_Manager::GALLERY,
				'default' => array(),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'thumbnail',
				'exclude' => array( 'custom' ),
				'default' => 'large',
			)
		);

		$this->end_controls_section();

		// ── Content: Layout ─────────────────────────────────────────────
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => __( 'Layout', 'mango-masonry-gallery' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => __( 'Columns', 'mango-masonry-gallery' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options'        => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'selectors'      => array(
					'{{WRAPPER}} .mmg-grid' => '--mmg-columns: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => __( 'Gap', 'mango-masonry-gallery' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 7,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mmg-grid' => '--mmg-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'radius',
			array(
				'label'      => __( 'Image Corner Radius', 'mango-masonry-gallery' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
					'%'  => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 2,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mmg-grid' => '--mmg-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// ── Style: Colors ────────────────────────────────────────────────
		$this->start_controls_section(
			'section_style',
			array(
				'label' => __( 'Colors', 'mango-masonry-gallery' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'overlay_color',
			array(
				'label'     => __( 'Hover Overlay Color', 'mango-masonry-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(20,20,20,0.45)',
				'selectors' => array(
					'{{WRAPPER}} .mmg-grid' => '--mmg-overlay: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'accent_color',
			array(
				'label'       => __( 'Lightbox Accent Color', 'mango-masonry-gallery' ),
				'description' => __( 'Used for the close button, arrows, and counter text.', 'mango-masonry-gallery' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#d4b5a5',
				'selectors'   => array(
					'{{WRAPPER}} .mmg-grid' => '--mmg-accent: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'lightbox_bg_color',
			array(
				'label'     => __( 'Lightbox Background', 'mango-masonry-gallery' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(10,7,5,0.96)',
				'selectors' => array(
					'{{WRAPPER}} .mmg-grid' => '--mmg-lightbox-bg: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$image_ids = array();
		if ( ! empty( $settings['gallery_images'] ) && is_array( $settings['gallery_images'] ) ) {
			foreach ( $settings['gallery_images'] as $image ) {
				if ( ! empty( $image['id'] ) ) {
					$image_ids[] = $image['id'];
				}
			}
		}

		if ( empty( $image_ids ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div style="padding:2rem;text-align:center;border:1px dashed #ccc;color:#888;">'
					. esc_html__( 'Add images in the Content tab to preview the Mango Masonry Gallery.', 'mango-masonry-gallery' )
					. '</div>';
			}
			return;
		}

		// Layout and colors are applied through the control selectors above.
		$html = Mango_Masonry_Gallery::instance()->render_gallery(
			array(
				'image_ids'  => $image_ids,
				'image_size' => ! empty( $settings['thumbnail_size'] ) ? $settings['thumbnail_size'] : 'large',
			)
		);
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- render_gallery() escapes internally.
	}
}
