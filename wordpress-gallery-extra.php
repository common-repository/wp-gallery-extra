<?php
/*
Plugin Name: WordPress Gallery Extra
Version: 2.0.2
Plugin URI: https://www.iprodev.com/go/wgextra/
Author: iProDev
Author URI: https://www.iprodev.com/
Description: The most intuitive and extensible gallery management tool ever created for WordPress.
Text Domain: wordpress-gallery-extra
Domain Path: /languages
*/

if ( !class_exists( "WordPress_Gallery_Extra" ) ) {
	abstract class WordPress_Gallery_Extra {
		public $VERSION = '2.0.2';
		public $MAIN;
		public $PATH;
		public $BASE;
		public $OPTIONS;
		public $TEMPLATES;
		public $TEMPLATES_TYPES = array();
		public $SOURCES;
		public $SOURCES_TYPES = array();
		public $SLUG = "wgextra";
		public $SETTINGS_TABS = array();
		public $SETTINGS_SECTIONS = array();
		public $SETTINGS_FIELDS = array();
		protected $LOAD_LIBRARY = false;
		protected $DEFAULT_OPTIONS = array(
			'installation_time' => 0,
			'license_id'        => null,
			'license_nonce'     => null,
			'items_per_page'    => 25,
			'delete_data'       => 'no',
			'crash_report'      => 'no',
			'debounce_resize'   => 'yes',
			'load_library'      => 'yes',
			'focus_point'       => 'yes',
			'media_taxonomies'  => 'yes',
			'grab_placeholder'  => 'yes',
			'import_from_xmp'   => 'yes',
			'audio_player_type' => 'auto',
			'image_quality'     => 90,
			'image_sizes'       => array()
		);
		protected $DEFAULT_TEMPLATE_OPTIONS = array(
			'template' => 'justified',
			'structure_type' => 'auto',
			'structure_custom' => '',

			'caption_source' => 'caption',
			'caption_custom' => '{{caption}}',

			'loading_type' => 'none',
			'use_lowres_image' => 'no',
			'loading_grid_animation' => "",

			'lightbox_type' => 'magnific',
			'lightbox' => '',
			'lightbox_magnific' => array(
				"animation" => "mfp-fade",
				"vertical_fit" => "yes",
				"preload" => "yes",
				"deeplink" => "yes"
			),
			'lightbox_photoswipe' => array(
				"skin" => "black",
				"loop" => "yes",
				"deeplink" => "yes",
				"share_buttons" => "yes",
				"download" => "yes"
			),
			'lightbox_fancybox' => array(
				"animation" => "fade",
				"transition" => "fade",
				"loop" => "yes",
				"deeplink" => "yes",
				"download" => "yes",
				"preload" => "yes"
			),
			'lightbox_ilightbox' => array(
				"skin" => "flat-dark",
				"direction" => "horizontal",
				"loop" => "yes",
				"carousel_mode" => "no",
				"deeplink" => "yes",
				"share_buttons" => "no",
				"thumbnails" => "no",
				"overlay_opacity" => 1
			),

			'slider_settings' => array(
				'sizing_method' => 'fullwidth',
				'slides_sizing_method' => 'unequal_columns',
				'width' => '',
				'height' => 300,
				'start_at' => 0,
				'auto_scale' => 'no',
				'start_random' => 'no',

				'mode' => 'horizontal',
				'type' => 'basic',
				'free_scroll' => 'no',
				'loop' => 'no',
				'speed' => 300,
				'easing' => 'swing',
				'keyboard_navigation' => '',
				'scrolling' => 1,
				'scrollbar' => 'no',
				'arrows' => 'no',
				'bullets' => 'no',
				'thumbnails' => 'no',

				'cycle_by' => '',
				'pause_time' => 5000,
				'repeat_cycling' => 'yes',
				'pause_on_hover' => 'no',
				'start_paused' => 'no',

				'mouse_dragging' => 'yes',
				'touch_dragging' => 'yes',
				'release_swing' => 'yes',
				'elastic_bounds' => 'yes',
				'one_page_drag' => 'no',

				'scrollbar_drag_handle' => 'yes',
				'scrollbar_dynamic_handle' => 'yes',
				'scrollbar_min_handle_size' => 50,
				'scrollbar_clickable' => 'yes',

				'animation_out' => array(),
				'animation_in' => array(),

				'kenburns_mode' => '',
				'kenburns_reverse' => 'yes',
				'kenburns_duration' => 10000,
			),

			'tilt_effect' => 'no',
			'tilt_options' => array(
				'mode' => 'thumb',
				'maxtilt' => 20,
				'speed' => 300,
				'scale' => 1.1,
				'glare' => 0.5,
				'perspective' => 1000,
				'reset' => 'yes',
				'axis' => 'both'
			),

			'link' => array(
				'to' => 'file',
				'target' => '_self',
				'url' => ''
			),
			'custom_class' => '',

			'default_image' => '',
			'thumbnail_size' => 'thumbnail',
			'columns' => 3,
			'last_row' => 'justify',
			'alignment' => 'center',
			'vertical_alignment' => 'top',
			'row_height' => 180,
			'max_row_height' => '200%',
			'mosaic_type' => 'auto',
			'thumbnail_ratio' => array(
				'type' => 'default',
				'force' => 'no',
				'size' => array( 1, 1 )
			),
			'detect_focus_point' => 'no',
			'grouped_items' => array(
				'mode' => 'lightbox',
				'template' => -1,
			),

			'source' => array(
				'source' => 'post_type',
				'item_number' => -1,
				'post_types' => array(),
				'post_status' => array( 'publish' ),
				'taxonomies' => array(),
				'taxonomies_relation' => "OR",
				'authors' => array(),
				'authors_relation' => "OR",
				'exclude_posts' => '',
				'include_posts' => '',
				'ordering' => array(
					'order' => 'ASC',
					'order_by' => 'post__in',
					'order_by_fallback' => '',
					'meta_key' => ''
				)
			),

			'responsive' => array(
				'desktop' => array(
					'size' => 1441
				),
				'laptop_large' => array(
					'size' => 1281,
					'columns' => '',
					'row_height' => '',
					'spacing' => '',
					'icon_size' => '',
				),
				'laptop' => array(
					'size' => 1025,
					'columns' => '',
					'row_height' => '',
					'spacing' => '',
					'icon_size' => '',
				),
				'tablet' => array(
					'size' => 769,
					'columns' => '',
					'row_height' => '',
					'spacing' => '',
					'icon_size' => '',
				),
				'tablet_small' => array(
					'size' => 481,
					'columns' => 2,
					'row_height' => '',
					'spacing' => '',
					'icon_size' => '',
				),
				'mobile' => array(
					'size' => 0,
					'columns' => 1,
					'row_height' => '',
					'spacing' => '',
					'icon_size' => '',
				)
			),

			'styles' => array(
				'defined'     => 'default',
				'margin'      => 1,
				'has_border'  => 'no',
				'has_shadow'  => 'no',
				'has_icon'    => 'no',
				'has_caption' => 'no',
				'has_overlay' => 'no',
				'border' => array(
					'weight' => 0,
					'color' => 'rgba(0, 0, 0, 0)',
					'style' => 'none',
					'radius' => 0
				),
				'shadow' => array(
					'x' => 0,
					'y' => 0,
					'blur' => 0,
					'spread' => 0,
					'color' => '',
					'inset' => 'no'
				),
				'embed_google_fonts' => '',
				'use_placeholder' => 'no',
				'placeholder' => array(
					'overlay' => 'yes',
					'readable_caption' => 'yes',
					'background' => 'yes'
				),
				'overlay' => array(
					'background' => array(
						'type' => 'solid',
						'solid' => array(
							'color' => 'rgba(0, 0, 0, 0.7)'
						),
						'gradient' => array(
							'start_color' => 'rgba(0, 0, 0, 0)',
							'stop_color' => 'rgba(0, 0, 0, 0.7)',
							'orientation' => 'vertical'
						)
					),
					'visibility' => 'fade-in',
					'transition' => array(
						'speed' => 300,
						'easing' => 'ease',
						'delay' => 0
					)
				),
				'caption' => array(
					'color' => 'rgba(255, 255, 255, 1)',
					'position' => 'bottom-center',
					'background' => array(
						'type' => 'solid',
						'solid' => array(
							'color' => 'rgba(0, 0, 0, 0.7)'
						),
						'gradient' => array(
							'start_color' => 'rgba(0, 0, 0, 0)',
							'stop_color' => 'rgba(0, 0, 0, 0.7)',
							'orientation' => 'vertical'
						)
					),
					'inset' => 'yes',
					'visibility' => 'fade-in-up',
					'transition' => array(
						'speed' => 300,
						'easing' => 'ease',
						'delay' => 0
					)
				),
				'thumbnail_effect' => array(
					'effect' => 'none',
					'transition' => array(
						'speed' => 300,
						'easing' => 'ease',
						'delay' => 0
					)
				),
				'icon' => array(
					'icon' => '',
					'color' => 'rgba(255, 255, 255, 1)',
					'size' => 36,
					'visibility' => 'fade-in',
					'transition' => array(
						'speed' => 300,
						'easing' => 'ease',
						'delay' => 0
					)
				),
				'slider' => array(
					'arrows' => array(
						'skin' => 'default',
						'hide' => 'yes',
						'under' => '',
						'under_unit' => 'px'
					),
					'scrollbar' => array(
						'skin' => 'default',
						'hide' => 'no',
						'inside' => 'no',
						'under' => '',
						'under_unit' => 'px',
						'size' => '',
						'size_unit' => 'px',
						'track_color' => array(
							'type' => 'none',
							'solid' => array(
								'color' => 'rgba(238, 238, 238, 1)'
							),
							'gradient' => array(
								'start_color' => 'rgba(238, 238, 238, 1)',
								'stop_color' => 'rgba(238, 238, 238, 1)',
								'orientation' => 'vertical'
							)
						),
						'handle_color' => array(
							'type' => 'none',
							'solid' => array(
								'color' => 'rgba(51, 51, 51, 1)'
							),
							'gradient' => array(
								'start_color' => 'rgba(51, 51, 51, 1)',
								'stop_color' => 'rgba(51, 51, 51, 1)',
								'orientation' => 'vertical'
							)
						),
					),
					'bullets' => array(
						'skin' => 'default',
						'hide' => 'no',
						'inside' => 'no',
						'under' => '',
						'under_unit' => 'px',
						'color' => ''
					),
					'thumbnails' => array(
						'skin' => 'default',
						'hide' => 'no',
						'inside' => 'no',
						'under' => '',
						'under_unit' => 'px',
						'position' => 'bottom',
						'spacing' => '',
						'spacing_unit' => 'px',
						'size' => '',
						'size_unit' => 'px'
					),
					'time_loader' => array(
						'skin' => 'default',
						'hide' => 'no',
						'under' => '',
						'under_unit' => 'px',
						'appearance' => 'none',
						'position' => '',
						'line_cap' => '',
						'stroke_size' => '',
						'circle_diameter' => '',
						'scale_length' => '',
						'rotate' => '',
						'track_space' => '',
						'offset' => '',
						'loader_color' => array(
							'type' => 'none',
							'solid' => array(
								'color' => 'rgba(255, 255, 255, 1)'
							),
							'gradient' => array(
								'start_color' => 'rgba(255, 255, 255, 1)',
								'stop_color' => 'rgba(255, 255, 255, 1)',
								'orientation' => 'linear'
							)
						),
						'track_color' => '',
						'scale_color' => '',
						'counter_color' => '',
						'counter' => 'default',
						'reverse' => 'default',
						'toggle_cycling' => 'yes'
					),
				),
				'custom_css' => ''
			)
		);

		protected $COLUMNS_DEFAULT_TEMPLATE = '
<div{% for attr, value in attributes %} {{ attr }}="{{ value|e }}"{% endfor %}>
	{% for item in items %}
		<figure{% for itemAttr, itemAttrValue in item.attributes %} {{ itemAttr }}="{{ itemAttrValue|e }}"{% endfor %}>
			{% if item.thumb %}
				<div{% for thumbAttr, thumbAttrValue in item.thumbAttributes %} {{ thumbAttr }}="{{ thumbAttrValue|e }}"{% endfor %}>
					{{ item.thumb }}
					{{ item.inlineSlider }}
					{{ item.icon }}
					{% if item.caption %}{{ item.caption }}{% endif %}
				</div>
			{% endif %}
			{% if item.captionText %}
				<figcaption{% for captionAttr, captionAttrValue in item.captionAttributes %} {{ captionAttr }}="{{ captionAttrValue|e }}"{% endfor %}>{{ item.captionText }}</figcaption>
			{% endif %}
		</figure>
	{% endfor %}
</div>
';

		protected $MASONRY_DEFAULT_TEMPLATE = '
<div{% for attr, value in attributes %} {{ attr }}="{{ value|e }}"{% endfor %}>
	<div class="wgextra-grid-size"></div>
	{% for item in items %}
		<figure{% for itemAttr, itemAttrValue in item.attributes %} {{ itemAttr }}="{{ itemAttrValue|e }}"{% endfor %}>
			{% if item.thumb %}
				<div{% for thumbAttr, thumbAttrValue in item.thumbAttributes %} {{ thumbAttr }}="{{ thumbAttrValue|e }}"{% endfor %}>
					{{ item.thumb }}
					{{ item.inlineSlider }}
					{{ item.icon }}
					{% if item.caption %}{{ item.caption }}{% endif %}
				</div>
			{% endif %}
			{% if item.captionText %}
				<figcaption{% for captionAttr, captionAttrValue in item.captionAttributes %} {{ captionAttr }}="{{ captionAttrValue|e }}"{% endfor %}>{{ item.captionText }}</figcaption>
			{% endif %}
		</figure>
	{% endfor %}
</div>
';

		protected $SLIDER_DEFAULT_TEMPLATE = '
<div{% for attr, value in attributes %} {{ attr }}="{{ value|e }}"{% endfor %}>
	<div class="wgextra-slider-parent">
		{% if arrows %}
			<div class="wgextra-arrows-parent">
				<a class="wgextra-arrows wgextra-prev" rel="wgextra-prev-page"></a>
				<a class="wgextra-arrows wgextra-next" rel="wgextra-next-page"></a>
			</div>
		{% endif %}
		<div class="wgextra-frame">
			<div class="wgextra-slideelement">
				{% for item in items %}
					<figure{% for itemAttr, itemAttrValue in item.attributes %} {{ itemAttr }}="{{ itemAttrValue|e }}"{% endfor %}>
						{% if item.thumb %}
							<div{% for thumbAttr, thumbAttrValue in item.thumbAttributes %} {{ thumbAttr }}="{{ thumbAttrValue|e }}"{% endfor %}>
								{{ item.thumb }}
								{{ item.inlineSlider }}
								{{ item.icon }}
								{% if item.caption %}{{ item.caption }}{% endif %}
							</div>
						{% endif %}
						{% if item.captionText %}
							<figcaption{% for captionAttr, captionAttrValue in item.captionAttributes %} {{ captionAttr }}="{{ captionAttrValue|e }}"{% endfor %}>{{ item.captionText }}</figcaption>
						{% endif %}
					</figure>
				{% endfor %}
			</div>
		</div>
		{% if bullets %}
			<ul class="wgextra-bullets" rel="wgextra-bullets"></ul>
		{% endif %}
		{% if thumbnails %}
			<div class="wgextra-thumbnails">
				<div class="wgextra-thumbnails-frame">
					<ul class="wgextra-thumbnails-bar" rel="wgextra-thumbnails-bar"></ul>
				</div>
			</div>
		{% endif %}
		{% if scrollbar %}
			<div class="wgextra-scrollbar" rel="wgextra-scrollbar">
				<div class="wgextra-handle"></div>
			</div>
		{% endif %}
	</div>
</div>
';

		abstract public function activate();
		abstract public function uninstall();
		abstract public function plugins_loaded();
		abstract public function wp_init();
		abstract public function ajax_actions();
		abstract protected function is_active();

		/**
		 * The WordPress Gallery Extra constructor function
		 *
		 * @param   string   $file  The plugin file path
		 * @return  object          Returns all WordPress Gallery Extra public methods and properties.
		 */
		function __construct( $file ) {
			$this->MAIN      = $file;
			$this->BASE      = plugin_basename( $file );
			$this->PATH      = dirname( $file );
			$this->OPTIONS   = $this->DEFAULT_OPTIONS;
			$this->TEMPLATES = apply_filters( 'wgextra_templates', get_option( "{$this->SLUG}_templates" ) );
			$this->SOURCES   = apply_filters( 'wgextra_sources', get_option( "{$this->SLUG}_sources" ) );

			if ( $options = get_option( "{$this->SLUG}_options" ) ) {
				$this->OPTIONS = apply_filters( 'wgextra_options', array_replace_recursive( $this->DEFAULT_OPTIONS, $options ) );
			}

			// Crash report
			if ( $this->OPTIONS['crash_report'] === 'yes' )
				set_error_handler( array($this, 'crash_report') );

			/**
			 * Add all hooks
			 */
			register_activation_hook( $file, array(
				 $this,
				'activate' 
			) );
			register_deactivation_hook( $file, array(
				 $this,
				'uninstall' 
			) );

			if ( is_admin() ) {
				add_action( 'admin_menu', array(
					 $this,
					'admin_menu'
				) );
				add_action( 'wp_ajax_' . $this->SLUG, array(
					 $this,
					'ajax_actions'
				) );
				add_action( 'admin_enqueue_scripts', array(
					 $this,
					'admin_enqueue_scripts'
				), 30 );
				add_action( 'print_media_templates', array(
					 $this,
					'gallery_custom_fields'
				) );
				add_action( 'admin_notices', array(
					 $this,
					'admin_notice'
				) );
				add_action( 'plugins_loaded', array(
					 $this,
					'plugins_loaded'
				) );

				add_filter( 'plugin_action_links', array(
					 $this,
					'action_links'
				), 10, 2 );
				add_filter( 'plugin_row_meta', array(
					 $this,
					'register_plugin_links'
				), 10, 2 );
				add_filter( 'image_size_names_choose', array(
					 $this,
					'image_size_names_choose'
				), 20 );
				add_filter( 'media_row_actions', array(
					 $this,
					'media_row_actions'
				), 20, 2 );
				add_filter( 'attachment_fields_to_edit', array(
					 $this,
					'add_fields'
				), 9, 2 );
				add_filter( 'attachment_fields_to_save', array(
					 $this,
					'save_fields'
				), 11, 2 );
			}
			else {
				add_action( 'wp_head', array(
					 $this,
					'wp_head'
				), 20 );
				add_action( 'wp_enqueue_scripts', array(
					 $this,
					'enqueue_scripts'
				), 200 );
			}

			add_action( 'iprodev_notify_daily_cron', array(
				$this,
				'check_things'
			) );

			add_action( 'init', array(
				 $this,
				'wp_init'
			), 30 );

			add_filter( 'attachment_link', array(
				 $this,
				'attachment_link_filter'
			), 20, 2 );
			add_filter( 'wp_get_attachment_link', array(
				 $this,
				'wp_get_attachment_link_filter'
			), 20, 6 );

			require_once 'includes/cron.class.php';
			require_once 'includes/shortcode-editor/class.shortcode.php';

			// Add cron if its not there
			new iProDevNotify( $file );

			return $this;
		}

		public function filter_update_checks( $queryArgs ) {
			if ( !empty( $this->OPTIONS['license_id'] ) ) {
				$queryArgs['license_id'] = $this->OPTIONS['license_id'];
				$queryArgs['license_nonce'] = $this->OPTIONS['license_nonce'];
				$queryArgs['site'] = site_url();
				$queryArgs['locale'] = ( defined( 'WPLANG' ) && WPLANG ? WPLANG : 'en_US' );
			}
			return $queryArgs;
		}

		/**
		 * Gets Image Compression quality.
		 *
		 * @access public
		 *
		 * @return float
		 */
		public function get_image_quality() {
			return (int) $this->OPTIONS['image_quality'];
		}

		/**
		 * Filter images metadata
		 *
		 * @access public
		 *
		 * @return float
		 */
		public function filter_metadata( $metadata, $attachment_id ) {
			if ( ( $this->OPTIONS['grab_placeholder'] === 'no' && $this->OPTIONS['import_from_xmp'] === 'no' ) || version_compare( PHP_VERSION, '5.3', '<' ) || !function_exists( "gd_info" ) ) {
				return $metadata;
			}

			$image_file = get_attached_file( $attachment_id );
			$thumb_file = wp_get_attachment_image_src( $attachment_id, 'medium' );
			$thumb_file = str_replace( wp_basename( $image_file ), wp_basename( $thumb_file[0] ), $image_file );

			/** Skip file if it's not a valid image */
			if ( !file_is_valid_image( $thumb_file ) ) {
				return $metadata;
			}

			if ( $this->OPTIONS['import_from_xmp'] === 'yes' ) {
				try {
					$xmp_arr = array();
					$xmp_raw = $this->get_xmp_raw( $image_file );

					if ( ! empty( $xmp_raw ) ) {
						$xmp_arr = $this->get_xmp_array( $xmp_raw );
					}

					if ( isset( $xmp_arr['Keywords'] ) ) {
						wp_set_post_terms( $attachment_id, $xmp_arr['Keywords'], "attachment_tag", true );
					}
					if ( isset( $xmp_arr['Hierarchical Keywords'] ) ) {
						wp_set_post_terms( $attachment_id, $xmp_arr['Hierarchical Keywords'], "attachment_category", true );
					}
				} catch ( Exception $e ) {}
			}

			if ( !get_post_meta( $attachment_id, '_wgextra_dominant_color', true ) && $this->OPTIONS['grab_placeholder'] === 'yes' ) {
				try {
					require_once "vendor/autoload.php";

					$image = imagecreatefromstring( file_get_contents( $thumb_file ) );
					$dominant_color = ColorThief\ColorThief::getColor( $image );
					$color_pallete = ColorThief\ColorThief::getPalette( $image );
					$dominant_color_hex = self::rgb2hex( $dominant_color );

					update_post_meta( $attachment_id, '_wgextra_dominant_color', $dominant_color_hex );
					update_post_meta( $attachment_id, '_wgextra_color_pallete', $color_pallete );
				} catch ( Exception $e ) {}
			}

			return $metadata;
		}

		/**
		 * Add the thumbnail name in the post insertion, based on new WP filter
		 *
		 * @access public
		 *
		 * @param array $sizes
		 *
		 * @return array
		 */
		public function image_size_names_choose( $sizes ) {
			// Get options
			$sizes_custom = $this->OPTIONS['image_sizes'];
			// init size array
			$add_sizes = array();

			// check there is custom sizes
			if ( is_array( $sizes_custom ) && ! empty( $sizes_custom ) ) {
				foreach ( $sizes_custom as $key => $value ) {
					// If we show this size in the admin
					if ( isset( $value['show'] ) ) {
						if ( 'yes' === $value['show'] ) {
							$add_sizes[ $key ] = isset( $value['name'] ) ? $value['name'] : $key;
						} elseif ( 'no' === $value['show'] && isset( $sizes[ $key ] ) ) {
							unset( $sizes[ $key ] );
						}
					}
					if ( isset( $value['delete'] ) && 'yes' === $value['delete'] ) {
						if ( isset( $add_sizes[ $key ] ) ) {
							unset( $add_sizes[ $key ] );
						}
						if ( isset( $sizes[ $key ] ) ) {
							unset( $sizes[ $key ] );
						}
					}
				}
			}

			// Add new size
			return array_merge( $sizes, $add_sizes );
		}

		/**
		 * Filters the permalink for an attachment.
		 *
		 * @access public
		 *
		 * @param string $link     The attachment's permalink.
		 * @param int    $post_id  Attachment ID.
		 *
		 * @return array
		 */
		public function attachment_link_filter( $link, $post_id ) {
			$custom_url    = get_post_meta( $post_id, '_wgextra_custom_url', true );

			if ( !empty( $custom_url ) ) {
				return $custom_url;
			}

			return $link;
		}

		/**
		 * Filters a retrieved attachment page link.
		 *
		 * @access public
		 *
		 * @param string        $link_html  The attachment's permalink.
		 * @param int           $id         Attachment ID.
		 * @param string|array  $size       Size of the image. Image size or array of width and height values (in that order). Default 'thumbnail'.
		 * @param bool          $permalink  Whether to add permalink to image. Default false.
		 * @param bool          $icon       Whether to include an icon. Default false.
		 * @param string|bool   $text       If string, will be link text. Default false.
		 *
		 * @return array
		 */
		public function wp_get_attachment_link_filter( $link_html, $id, $size, $permalink, $icon, $text ) {
			if ( $custom_url = get_post_meta( $id, '_wgextra_custom_url', true ) ) {
				$link_html = preg_replace( '/href=["|\']([^("|\')]+)["|\']/', "href='" . esc_attr( $custom_url ) . "'", $link_html );
			}

			if ( $custom_target = get_post_meta( $id, '_wgextra_custom_target', true ) ) {
				// Add 'target' attribute to the link markup.
				if ( preg_match( '/target=["|\']([^("|\')]+)["|\']/', $link_html ) ) {
					$link_html = preg_replace( '/target=["|\']([^("|\')]+)["|\']/', "target='$custom_target'", $link_html );
				} else {
					$link_html = preg_replace( '/<a ([^>]+?)[\/ ]*>/', "<a $1 target='$custom_target'>", $link_html );
				}
			}

			return $link_html;
		}

		/**
		 * Add a "Regenerate Thumbnails" link to the media row actions
		 *
		 * @access public
		 *
		 * @param array  $actions An array of action links for each attachment.
		 * @param object $post    (WP_Post) WP_Post object for the current attachment.
		 *
		 * @return array
		 */
		public function media_row_actions( $actions, $post ) {
			if ( 'image/' != substr( $post->post_mime_type, 0, 6 ) || ! current_user_can( "manage_options" ) )
				return $actions;

			$url = "#";
			$actions['rebuild_thumbnails'] = '<a href="' . esc_url( $url ) . '" image-id="' . $post->ID . '" nonce="' . wp_create_nonce( 'wgextra_process_image_resize' ) . '" title="' . esc_attr( __( "Rebuild the thumbnails for this single image", 'wordpress-gallery-extra' ) ) . '">' . __( 'Rebuild Thumbnails', 'wordpress-gallery-extra' ) . '</a>';

			return $actions;
		}

		/**
		 * Get WordPress Gallery Extra Custom Fields.
		 *
		 * @access public
		 *
		 * @return array
		 */
		public function get_custom_fields() {

			$icons_json = file_get_contents( plugin_dir_path( $this->MAIN ) . "assets/css/icons.json" );
			$icons = json_decode( $icons_json, true );
			$icons = isset( $icons['icons'] ) ? $icons['icons'] : array();

			$target_options = apply_filters( 'wgextra_attachment_field_custom_target_options', array(
				'_self'     => __( 'Open on the same page (_self)', 'wordpress-gallery-extra' ),
				'_blank'    => __( 'Open on new page (_blank)', 'wordpress-gallery-extra' ),
				'_parent'   => __( 'Open in parent frame (_parent)', 'wordpress-gallery-extra' ),
				'_top'      => __( 'Open in main frame (_top)', 'wordpress-gallery-extra' ),
				'_lightbox' => __( 'Open in LightBox (_lightbox)', 'wordpress-gallery-extra' ),
				'_video'    => __( 'Open Video in LightBox (_video)', 'wordpress-gallery-extra' ),
				'_audio'    => __( 'Open Audio in LightBox (_audio)', 'wordpress-gallery-extra' )
			) );
			$grid_sizes_options = apply_filters( 'wgextra_attachment_field_grid_sizes_options', array(
				''      => __( 'Auto', 'wordpress-gallery-extra' ),
				'1-12'  => '1/12',
				'2-12'  => '2/12',
				'3-12'  => '3/12',
				'4-12'  => '4/12',
				'5-12'  => '5/12',
				'6-12'  => '6/12',
				'7-12'  => '7/12',
				'8-12'  => '8/12',
				'9-12'  => '9/12',
				'10-12' => '10/12',
				'11-12' => '11/12',
				'12-12' => '12/12'
			) );

			$icons_options = array(
				'' => '&nbsp;'
			);
			foreach ( $icons as $icon ) {
				$id = $icon['properties']['name'];
				$code = dechex($icon['properties']['code']);
				$icons_options[$id] = "&#x{$code};";
			}
			$icons_options = apply_filters( 'wgextra_attachment_field_icons_options', $icons_options );

			$fields = array(
				'wgextra_dominant_color' => array(
					'label'       => __( 'Placeholder Color', 'wordpress-gallery-extra' ),
					'input'       => 'text',
					'helps'       => __( 'Image placeholder color for lazy load.', 'wordpress-gallery-extra' ),
					'exclusions'  => array( 'audio', 'video' )
				),

				'wgextra_custom_url' => array(
					'label'       => __( 'Custom URL', 'wordpress-gallery-extra' ),
					'input'       => 'text',
					'helps'       => __( 'Point your attachment to a custom URL.', 'wordpress-gallery-extra' ),
					'exclusions'  => array( 'audio', 'video' )
				),

				'wgextra_custom_target' => array(
					'label'       => __( 'Custom Target', 'wordpress-gallery-extra' ),
					'input'       => 'select',
					'helps'       => __( 'Set a custom target for your attachment.', 'wordpress-gallery-extra' ),
					'exclusions'  => array( 'audio', 'video' ),
					'options'     => $target_options
				),

				'wgextra_group_shortcode' => array(
					'label'       => __( 'Group', 'wordpress-gallery-extra' ),
					'input'       => 'text',
					'helps'       => __( 'Insert a [gallery] shortcode to enable group gallery for this attachment.', 'wordpress-gallery-extra' ),
					'exclusions'  => array( 'audio', 'video' )
				),

				'wgextra_icon' => array(
					'label'       => __( 'Icon', 'wordpress-gallery-extra' ),
					'input'       => 'select',
					'helps'       => __( 'Choose which icon is shown when hover this attachment.', 'wordpress-gallery-extra' ),
					'exclusions'  => array( 'audio', 'video' ),
					'options'     => $icons_options
				),

				'wgextra_columns_size' => array(
					'label'       => __( 'Columns Size', 'wordpress-gallery-extra' ),
					'input'       => 'select',
					'helps'       => __( 'Set columns size for your attachment in mosaic gallery type.', 'wordpress-gallery-extra' ),
					'exclusions'  => array( 'audio', 'video' ),
					'options'     => $grid_sizes_options
				),

				'wgextra_rows_size' => array(
					'label'       => __( 'Rows Size', 'wordpress-gallery-extra' ),
					'input'       => 'select',
					'helps'       => __( 'Set rows size for your attachment in mosaic gallery type.', 'wordpress-gallery-extra' ),
					'exclusions'  => array( 'audio', 'video' ),
					'options'     => $grid_sizes_options
				),

				'wgextra_ratio' => array(
					'label'       => __( 'Thumbnail Ratio', 'wordpress-gallery-extra' ),
					'input'       => 'text',
					'helps'       => __( 'Correspond to the ratio between width and height (X:Y) (e.g: 4:3 or 16:9 format)', 'wordpress-gallery-extra' ),
					'exclusions'  => array( 'audio', 'video' )
				)
			);

			$pagename = basename( $_SERVER['SCRIPT_NAME'] );
			if ( $this->OPTIONS['media_taxonomies'] === 'yes' && $pagename !== 'post.php' ) {
				$fields['attachment_tag'] = array(
					'label' => __( 'Tags' ),
					'input' => 'terms',
					'exclusions'  => array()
				);
				$fields['attachment_category'] = array(
					'label' => __( 'Categories' ),
					'input' => 'terms',
					'exclusions'  => array()
				);
			}

			return apply_filters( 'wgextra_attachment_custom_fields', $fields );
		}

		/**
		 * Fires once an existing attachment has been updated.
		 *
		 * @access public
		 *
		 * @param array  $post        array  Post attributes.
		 * @param array  $attachment  array  Attachment fields.
		 *
		 * @return array
		 */
		public function save_fields( $post, $attachment ) {
			$custom_fields = $this->get_custom_fields();
			$attachment_id = intval( $post['ID'] );

			// If our fields array is not empty
			if ( ! empty( $custom_fields ) ) {
				// We browse our set of options
				foreach ( $custom_fields as $field => $values ) {
					switch ( $values['input'] ) {
						case 'text':
						case 'textarea':
						case 'select':
						case 'radio':
						case 'checkbox':
						case 'hidden':
							// If this field has been submitted (is present in the $attachment variable)
							if ( isset( $attachment[$field] ) ) {
								// If submitted field is empty
								// We add errors to the post object with the "error_text" parameter if set in the options
								if ( strlen( trim( $attachment[$field] ) ) == 0 && isset( $values['error_text'] ) ) {
									$post['errors'][ $field ]['errors'][] = __( $values['error_text'] );
									// Otherwise we update the custom field
								} else {
									update_post_meta( $attachment_id, '_' . $field, $attachment[ $field ] );
								}
							}
							// Otherwise, we delete it if it already existed
							else {
								delete_post_meta( $attachment_id, $field );
							}
							break;

						default:
							do_action( 'wgextra_attachment_save_field_' . $values['input'], $field, $values, $post, $attachment);
					}
				}
			}

			// insert new term to each taxonomy if exist
			$taxo_names = array('attachment_category', 'attachment_tag');
			$taxo_terms = array(
				'attachment_category' => array(),
				'attachment_tag' => array()
			);
			foreach ( $taxo_names as $taxo_name ) {
				if ( isset( $_REQUEST['new-attachment-term'][$taxo_name] ) && !empty( $_REQUEST['new-attachment-term'][$taxo_name] ) ) {
					$new_term = $_REQUEST['new-attachment-term'][$taxo_name];
					$term = wp_insert_term( $new_term, $taxo_name, '' );
					if ( $term && !is_wp_error( $term ) && isset( $term['term_id'] ) ) {
						$taxo_terms[$taxo_name][] = $term['term_id'];
					}
				}
			}

			// add terms to attachment cat or tag
			foreach ( $taxo_names as $taxonomy ) {
				if ( isset( $_REQUEST['tax_input'][$taxonomy] ) ){
					$terms = $_REQUEST['tax_input'][$taxonomy];
					if ( is_array( $terms ) ) {
						$terms = array_filter( array_map( 'intval', $terms ) );
					}
				} else {
					$terms = array();
				}

				if ( isset( $taxo_terms[$taxonomy] ) && !empty( $taxo_terms[$taxonomy] ) ) {
					$terms = array_merge( $terms, $taxo_terms[$taxonomy] );
				}

				wp_set_object_terms( $attachment_id, $terms, $taxonomy, false );
			}

			return $post;
		}

		/**
		 * The "attachment_fields_to_edit" filter is used to filter the array of attachment fields that are displayed when editing an attachment.
		 *
		 * @access public
		 *
		 * @param array  $form_fields  An array of action links for each attachment.
		 * @param object $post         (WP_Post) WP_Post object for the current attachment.
		 *
		 * @return array
		 */
		public function add_fields( $form_fields, $post ) {
			$custom_fields = $this->get_custom_fields();

			// If our fields array is not empty
			if ( ! empty( $custom_fields ) ) {
				// We browse our set of options
				foreach ( $custom_fields as $field => $values ) {
					// If the field matches the current attachment mime type
					// and is not one of the exclusions
					$post_mime_type = explode( '/', $post->post_mime_type );
					if ( !in_array( $post_mime_type[0], $values['exclusions'] ) ) {
						// We get the already saved field meta value
						$meta = apply_filters( 'wgextra_attachment_custom_field_value', get_post_meta( $post->ID, '_' . $field, true ), $post->ID, $field, $values );

						switch ( $values['input'] ) {
							default:
							case 'text':
								$values['input'] = 'text';
								break;

							case 'hidden':
								$values['input'] = 'hidden';
								break;

							case 'textarea':
								$values['input'] = 'textarea';
								break;

							case 'select':

								// Select type doesn't exist, so we will create the html manually
								// For this, we have to set the input type to 'html'
								$values['input'] = 'html';

								// Create the select element with the right name (matches the one that wordpress creates for custom fields)
								$html = '<select name="attachments[' . $post->ID . '][' . $field . ']">';

								// If options array is passed
								if ( isset( $values['options'] ) ) {
									// Browse and add the options
									foreach ( $values['options'] as $k => $v ) {
										// Set the option selected or not
										$selected = selected( $k, $meta, false );

										$html .= '<option' . $selected . ' value="' . $k . '">' . $v . '</option>';
									}
								}

								$html .= '</select>';

								// Set the html content
								$values['html'] = $html;

								break;

							case 'checkbox':

								// Checkbox type doesn't exist either
								$values['input'] = 'html';

								// Set the checkbox checked or not
								$checked = checked( $meta, 'on', false );

								$html = '<input' . $checked . ' type="checkbox" name="attachments[' . $post->ID . '][' . $field . ']" id="attachments-' . $post->ID . '-' . $field . '" />';

								$values['html'] = $html;

								break;

							case 'radio':

								// radio type doesn't exist either
								$values['input'] = 'html';

								$html = '';

								if ( ! empty( $values['options'] ) ) {
									$i = 0;

									foreach ( $values['options'] as $k => $v ) {
										$checked = checked( $k, $meta, false );

										$html .= '<input' . $checked . ' value="' . $k . '" type="radio" name="attachments[' . $post->ID . '][' . $field . ']" id="' . sanitize_key( $field . '_' . $post->ID . '_' . $i ) . '" /> <label for="' . sanitize_key( $field . '_' . $post->ID . '_' . $i ) . '">' . $v . '</label><br />';
										$i++;
									}
								}

								$values['html'] = $html;

								break;

							case 'terms':
								require_once "includes/Walker_WP_Media_Taxonomy_Checklist.php";

								$values['input'] = 'html';

								$taxonomy = get_taxonomy( $field );
								$attachment_terms = wp_get_object_terms( $post->ID, $taxonomy->name, array( 'fields' => 'ids' ) );

								ob_start();
								wp_terms_checklist( $post->ID, array(
									'selected_cats' => $attachment_terms,
									'taxonomy'      => $taxonomy->name,
									'checked_ontop' => true,
									'walker'        => new Walker_WP_Media_Taxonomy_Checklist( $post->ID )
								));	
								$terms_list = ob_get_contents();
								ob_end_clean();

								$html  = '<div class="attachment-term-section">';

									$label = $taxonomy->labels->all_items;
									$html .= '<ul class="media-category-tabs category-tabs">';
										$html .= '<li class="tabs" data-tab="attachment-terms-all"><span>' . $label . '</span></li>';
										$html .= '<li class="hide-if-no-js" data-tab="attachment-terms-popular"><span>' . __( 'Most Used' ) . '</span></li>';
									$html .= '</ul>';

									$html .= '<div class="attachment-terms" data-id="' . $post->ID . '" data-taxonomy="' . $taxonomy->name . '">';
										$html .= '<ul>';
													$html .= $terms_list;	
										$html .= '</ul>';
									$html .= '</div>';

									$html .= '<h4><span class="toggle-add-attachment-term">+ ' . $taxonomy->labels->add_new_item . '</span></h4>';

									$html .= '<div class="add-new-term">';
										$html .= '<p class="category-add wp-hidden-child">';
										$html .= '<input type="text" class="text form-required" autocomplete="off" id="new-attachment-term" name="new-attachment-term[' . $taxonomy->name . ']" value="">';
										$html .= '<button class="button save-attachment-term" name="current-taxonomy">' . $taxonomy->labels->add_new_item . '</button>';
										$html .= '</p>';
									$html .= '</div>';

								$html .= '</div>';

								$values['html'] = $html;

								break;
						}

						// And set it to the field before building it
						$values['value'] = $meta;

						// We add our field into the $form_fields array
						$form_fields[$field] = apply_filters( 'wgextra_attachment_field_' . $field, $values, $post->ID );
					}
				}
			}

			// We return the completed $form_fields array
			return apply_filters( 'wgextra_attachment_add_fields', $form_fields );
		}

		/**
		 * Add menu and submenu.
		 * @return void
		 */
		public function admin_menu() {
			add_menu_page( $this->SLUG, __( 'WP Gallery Extra', 'wordpress-gallery-extra' ), 'manage_options', $this->SLUG, array(
				 $this,
				'init_page'
			), 'dashicons-images-alt2' );
			add_submenu_page( $this->SLUG, __( 'Templates', 'wordpress-gallery-extra' ), __( 'Templates', 'wordpress-gallery-extra' ), 'manage_options', $this->SLUG, array(
				 $this,
				'init_page'
			) );
			add_submenu_page( $this->SLUG, __( 'Settings', 'wordpress-gallery-extra' ), __( 'Settings', 'wordpress-gallery-extra' ), 'manage_options', "{$this->SLUG}_settings", array(
				 $this,
				'settings_page'
			) );
			add_submenu_page( $this->SLUG, __( 'Help', 'wordpress-gallery-extra' ), __( 'Help', 'wordpress-gallery-extra' ), 'manage_options', "{$this->SLUG}_help", array(
				 $this,
				'help_page'
			) );
		}

		/**
		 * Add action links on plugin page in to Plugin Name block
		 * @param  $links array() action links
		 * @param  $file  string  relative path to pugin "wordpress-gallery-extra/wordpress-gallery-extra.php"
		 * @return $links array() action links
		 */
		public function action_links( $links, $file ) {
			if ( $file == $this->BASE ) {
				$settings_link = "<a href=\"admin.php?page={$this->SLUG}_settings\">" . __( 'Settings', 'wordpress-gallery-extra' ) . '</a>';
				array_unshift( $links, $settings_link );
			}

			return $links;
		}
		
		/**
		 * Add action links on plugin page in to Plugin Description block
		 * @param  $links array() action links
		 * @param  $file  string  relative path to pugin "wordpress-gallery-extra/wordpress-gallery-extra.php"
		 * @return $links array() action links
		 */
		public function register_plugin_links( $links, $file ) {
			if ( $file == $this->BASE ) {
				$links[] = "<a href=\"admin.php?page={$this->SLUG}_settings\">" . __( 'Settings', 'wordpress-gallery-extra' ) . '</a>';
			}
			return $links;
		}
		
		/**
		 * Function to add plugin scripts
		 * @return void
		 */
		public function admin_enqueue_scripts() {
			global $wp_version, $wp_styles, $wp_scripts, $pagenow;

			$page_query = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : null;

			if ( $page_query && strpos( $page_query, 'wgextra' ) !== false ) {
				/* Deregister & dequeue styles that will break WGExtra */
				if ( $wp_styles->registered && !empty( $wp_styles->registered ) ) {
					foreach ( $wp_styles->registered as $key => $value ) {
						if (
							(
								stripos( $key, 'jquery-ui' )  !== false ||
								stripos( $key, 'jquery.ui' )  !== false ||
								stripos( $key, 'codemirror' ) !== false ||
								stripos( $key, 'isotope' )    !== false ||
								stripos( $key, 'noty' )       !== false ||
								stripos( $key, 'spinner' )    !== false ||
								stripos( $key, 'lada' )       !== false ||
								stripos( $key, 'yith-' )      === 0 ||
								stripos( $key, 'yith_' )      === 0 ||
								stripos( $key, 'yit-' )       === 0
							) &&
							stripos( $key, 'wgextra' ) === false
						) {
							wp_deregister_style( $key );
						}
					}
					foreach ( $wp_styles->queue as $queue ) {
						if (
							(
								stripos( $queue, 'jquery-ui' )  !== false ||
								stripos( $queue, 'jquery.ui' )  !== false ||
								stripos( $queue, 'codemirror' ) !== false ||
								stripos( $queue, 'isotope' )    !== false ||
								stripos( $queue, 'noty' )       !== false ||
								stripos( $queue, 'spinner' )    !== false ||
								stripos( $queue, 'lada' )       !== false ||
								stripos( $queue, 'yith-' )      === 0 ||
								stripos( $queue, 'yith_' )      === 0 ||
								stripos( $queue, 'yit-' )       === 0
							) &&
							stripos( $queue, 'wgextra' ) === false
						) {
							wp_dequeue_style( $queue );
						}
					}
				}
				/* Deregister & dequeue scripts that will break WGExtra */
				if ( $wp_scripts->registered && !empty( $wp_scripts->registered ) ) {
					foreach ( $wp_scripts->registered as $key => $value ) {
						if (
							(
								//stripos( $key, 'jquery-ui' )  !== false ||
								stripos( $key, 'jquery.ui' )  !== false ||
								stripos( $key, 'codemirror' ) !== false ||
								stripos( $key, 'isotope' )    !== false ||
								stripos( $key, 'noty' )       !== false ||
								stripos( $key, 'spinner' )    !== false ||
								stripos( $key, 'lada' )       !== false ||
								stripos( $key, 'yith-' )      === 0 ||
								stripos( $key, 'yith_' )      === 0 ||
								stripos( $key, 'yit-' )       === 0
							) &&
							stripos( $key, 'wgextra' ) === false
						) {
							wp_deregister_script( $key );
						}
					}
					foreach ( $wp_scripts->queue as $queue ) {
						if (
							(
								//stripos( $queue, 'jquery-ui' )  !== false ||
								stripos( $queue, 'jquery.ui' )  !== false ||
								stripos( $queue, 'codemirror' ) !== false ||
								stripos( $queue, 'isotope' )    !== false ||
								stripos( $queue, 'noty' )       !== false ||
								stripos( $queue, 'spinner' )    !== false ||
								stripos( $queue, 'lada' )       !== false ||
								stripos( $queue, 'yith-' )      === 0 ||
								stripos( $queue, 'yith_' )      === 0 ||
								stripos( $queue, 'yit-' )       === 0
							) &&
							stripos( $queue, 'wgextra' ) === false
						) {
							wp_dequeue_script( $queue );
						}
					}
				}

				wp_enqueue_media();
				if ( is_rtl() ) {
					wp_enqueue_style( 'wgextra-jquery-ui', plugins_url( 'assets/css/jquery-ui-rtl.css', __FILE__ ) );
					wp_enqueue_style( 'wgextra-multi-select', plugins_url( 'assets/css/multi-select-rtl.css', __FILE__ ) );
					wp_enqueue_style( 'wgextra-stylesheet', plugins_url( 'assets/css/style-rtl.css', __FILE__ ) );
				} else {
					wp_enqueue_style( 'wgextra-jquery-ui', plugins_url( 'assets/css/jquery-ui.css', __FILE__ ) );
					wp_enqueue_style( 'wgextra-multi-select', plugins_url( 'assets/css/multi-select.css', __FILE__ ) );
					wp_enqueue_style( 'wgextra-stylesheet', plugins_url( 'assets/css/style.css', __FILE__ ) );
				}
				wp_enqueue_script( 'wgextra-jquery-ui', plugins_url( 'assets/js/jquery-ui.js', __FILE__ ), array(
					'jquery'
				), '1.12.1' );
				wp_enqueue_script( 'jquery-ui-slider-pips', plugins_url( 'assets/js/jquery-ui-slider-pips.min.js', __FILE__ ), array(
					'jquery-ui-slider'
				), '0.9.12' );
				wp_enqueue_script( 'jquery.requestanimationframe', plugins_url( 'assets/js/jquery.requestanimationframe.min.js', __FILE__ ), array(
					'jquery'
				), '0.2.3' );
				wp_enqueue_script( 'jquery-minicolors', plugins_url( 'assets/js/jquery.minicolors.min.js', __FILE__ ), array(
					'jquery'
				), '2.2.4' );
				wp_enqueue_script( 'jquery-quicksearch', plugins_url( 'assets/js/jquery.quicksearch.js', __FILE__ ), array(
					'jquery'
				), '1.0.0' );
				wp_enqueue_script( 'jquery-multiselect', plugins_url( 'assets/js/jquery.multi-select.js', __FILE__ ), array(
					'jquery',
					'jquery-quicksearch'
				), '0.9.12' );
				wp_enqueue_script( 'isotope', plugins_url( 'assets/js/isotope.pkgd.min.js', __FILE__ ), array(
					'jquery'
				), '3.0.4' );
				wp_enqueue_script( 'codemirror', plugins_url( 'assets/js/codemirror.min.js', __FILE__ ), null, '5.25.2' );
				wp_enqueue_script( 'jquery.sticky', plugins_url( 'assets/js/jquery.sticky.js', __FILE__ ), array(
					'jquery'
				), '1.0.4' );
				wp_enqueue_script( 'noty', plugins_url( 'assets/js/noty.min.js', __FILE__ ), null, '4.1.0' );
				wp_enqueue_script( 'spinner', plugins_url( 'assets/js/spin.min.js', __FILE__ ), null, '1.0.0' );
				wp_enqueue_script( 'lada', plugins_url( 'assets/js/ladda.min.js', __FILE__ ), array(
					'spinner'
				), '1.0.0' );
				wp_enqueue_script( 'visibility-changed', plugins_url( 'assets/js/visibilityChanged.min.js', __FILE__ ), null, '1.0.0' );
				wp_enqueue_script( 'wgextra-script', plugins_url( 'assets/js/script.js', __FILE__ ), array(
					'jquery',
					'underscore',
					'wgextra-jquery-ui',
					'jquery-ui-slider-pips',
					'jquery-minicolors',
					'jquery-multiselect',
					'isotope',
					'codemirror',
					'jquery.sticky',
					'noty',
					'lada',
					'visibility-changed'
				) );

				$wp_localize_script = array(
					"nonce" => array(
						"create_new_template" => wp_create_nonce( 'wgextra_create_new_template' ),
						"duplicate_template" => wp_create_nonce( 'wgextra_duplicate_template' ),
						"delete_template" => wp_create_nonce( 'wgextra_delete_template' ),
						"create_new_source" => wp_create_nonce( 'wgextra_create_new_source' ),
						"duplicate_source" => wp_create_nonce( 'wgextra_duplicate_source' ),
						"delete_source" => wp_create_nonce( 'wgextra_delete_source' ),
						"verify_image_size_id" => wp_create_nonce( 'wgextra_verify_image_size_id' ),
						"backup" => wp_create_nonce( 'wgextra_backup_thumbnails' ),
						"restore" => wp_create_nonce( 'wgextra_restore' ),
						"delete_backup" => wp_create_nonce( 'wgextra_delete_backup' ),
						"clean_thumbnails" => wp_create_nonce( 'wgextra_clean_thumbnails' ),
						"process_image_resize" => wp_create_nonce( 'wgextra_process_image_resize' ),
						"activate_license" => wp_create_nonce( 'wgextra_activate_license' ),
						"deactivate_license" => wp_create_nonce( 'wgextra_deactivate_license' ),
						"general" => wp_create_nonce( 'wgextra_general' ),
						"import_template" => wp_create_nonce( 'wgextra_import_template' ),
						"check_iprodev_server_contact" => wp_create_nonce( 'wgextra_check_iprodev_server_contact' )
					),
					"templates_types" => $this->TEMPLATES_TYPES,
					"sources_types" => $this->SOURCES_TYPES,
					"version" => $this->VERSION,
					"site_url" => site_url(),
					"activation_page" => admin_url( 'admin.php?page=wgextra_settings#wgextra-license' ),
					"activation_url" => plugins_url( 'activate.php', __FILE__ ),
					"license_id" => $this->OPTIONS['license_id'],
					"license_nonce" => $this->OPTIONS['license_nonce'],
					"post(s)" => __( 'post(s)', 'wordpress-gallery-extra' ),
					"ok" => __( 'OK', 'wordpress-gallery-extra' ),
					"Yes" => __( 'Yes', 'wordpress-gallery-extra' ),
					"No" => __( 'No', 'wordpress-gallery-extra' ),
					"cancel" => __( 'Cancel', 'wordpress-gallery-extra' ),
					"Type to Search..." => __( 'Type to Search...', 'wordpress-gallery-extra' ),
					"Register a new image size" => __( 'Register a new image size', 'wordpress-gallery-extra' ),
					"Please enter a valid ID for your new image size." => __( 'Please enter a valid ID for your new image size. Only english letters and dashes allowed. e.g. <code>my-image-size</code>', 'wordpress-gallery-extra' ),
					"Request failed, please try again." => __( 'Request failed, please try again.', 'wordpress-gallery-extra' ),
					"Backup Thumbnails" => __( 'Backup Thumbnails', 'wordpress-gallery-extra' ),
					"Are you sure you want to backup your current thumbnails?" => __( 'Are you sure you want to backup your current thumbnails?', 'wordpress-gallery-extra' ),
					"Restore Backup" => __( 'Restore Backup', 'wordpress-gallery-extra' ),
					"Are you sure you want to restore your last backup?" => __( 'Are you sure you want to restore your last backup?', 'wordpress-gallery-extra' ),
					"Delete Backup" => __( 'Delete Backup', 'wordpress-gallery-extra' ),
					"Are you sure you want to delete your thumbnails backup?" => __( 'Are you sure you want to delete your thumbnails backup?', 'wordpress-gallery-extra' ),
					"Never" => __( 'Never', 'wordpress-gallery-extra' ),
					"Delete Thumbnails" => __( 'Delete Thumbnails', 'wordpress-gallery-extra' ),
					"Are you sure you want to delete thumbnails?" => __( 'Are you sure you want to delete thumbnails?', 'wordpress-gallery-extra' ),
					"Finished." => __( 'Finished.', 'wordpress-gallery-extra' ),
					"Create a new template" => __( 'Create a new template', 'wordpress-gallery-extra' ),
					"Create a new source" => __( 'Create a new source', 'wordpress-gallery-extra' ),
					"Please enter your new template name." => __( 'Please enter your new template name.', 'wordpress-gallery-extra' ),
					"Please enter your new source name." => __( 'Please enter your new source name.', 'wordpress-gallery-extra' ),
					"Duplicate Template" => __( 'Duplicate Template', 'wordpress-gallery-extra' ),
					"Duplicate Source" => __( 'Duplicate Source', 'wordpress-gallery-extra' ),
					"Are you sure you want to duplicate this gallery template?" => __( 'Are you sure you want to duplicate this gallery template?', 'wordpress-gallery-extra' ),
					"Are you sure you want to duplicate this grid source?" => __( 'Are you sure you want to duplicate this grid source?', 'wordpress-gallery-extra' ),
					"Delete Template" => __( 'Delete Template', 'wordpress-gallery-extra' ),
					"Delete Source" => __( 'Delete Source', 'wordpress-gallery-extra' ),
					"Are you sure you want to delete this gallery template?" => __( 'Are you sure you want to delete this gallery template?', 'wordpress-gallery-extra' ),
					"Are you sure you want to delete this grid source?" => __( 'Are you sure you want to delete this grid source?', 'wordpress-gallery-extra' ),
					"Your support period has been expired." => __( 'Your license support period has been expired, please renew it.', 'wordpress-gallery-extra' ),
					"You didn't purchased this item yet." => __( "You didn't purchased this item yet. By purchasing WordPress Gallery Extra license you will unlock premium options - direct plugin updates, access to template library and official support.", 'wordpress-gallery-extra' ),
					"Convert to LESS" => __( "Convert to LESS", 'wordpress-gallery-extra' ),
					"Are you sure you want to convert your CSS to LESS?" => __( 'Are you sure you want to convert your CSS to LESS?', 'wordpress-gallery-extra' ),
					"Import Template" => __( 'Import Template', 'wordpress-gallery-extra' ),
					"It looks like you have been editing something. If you leave before saving, your changes will be lost." => __( 'It looks like you have been editing something. If you leave before saving, your changes will be lost.', 'wordpress-gallery-extra' ),
					"Select an image" => __( 'Select an image', 'wordpress-gallery-extra' ),
				);

				if ( $page_query === 'wgextra' && isset( $_REQUEST['id'] ) ) {
					$post_types_taxonomies = array();

					foreach ( self::get_public_post_types() as $post_type => $post_type_name ) {
						$taxonomy_objects = get_object_taxonomies( $post_type, 'objects' );
						if ( !empty( $taxonomy_objects ) ) {
							$post_types_taxonomies[$post_type] = array();
							foreach ( $taxonomy_objects as $taxonomy_slug => $taxonomy_object ) {
								$post_types_taxonomies[$post_type][$taxonomy_slug] = array(
									"label" => $taxonomy_object->label,
									"terms" => array()
								);

								if ( version_compare( $wp_version, '4.5', '<' ) ) {
									$terms = get_terms( $taxonomy_slug, array(
										'orderby'    => 'count',
										'order'      => 'DESC',
										'parent'     => 0,
										'hide_empty' => false
									) );
								} else {
									$terms = get_terms( array(
										'taxonomy'   => $taxonomy_slug,
										'orderby'    => 'count',
										'order'      => 'DESC',
										'parent'     => 0,
										'hide_empty' => false
									) );
								}

								foreach ( $terms as $term ) {
									$post_types_taxonomies[$post_type][$taxonomy_slug]['terms'][] = array(
										"slug"  => $term->slug,
										"name"  => $term->name,
										"count" => $term->count,
									);
								}
							}
						}
					}

					$wp_localize_script['post_types_taxonomies'] = $post_types_taxonomies;
					$template = $this->TEMPLATES[$_REQUEST['id']];
					$template = array_replace_recursive( $this->DEFAULT_TEMPLATE_OPTIONS, $template );
					$wp_localize_script['source_data'] = $template['source'];
				}

				wp_localize_script( 'wgextra-script', 'wgextra', $wp_localize_script );
			}

			// WP Media
			wp_enqueue_style( 'wgextra-media-stylesheet', plugins_url( 'assets/css/media-style.css', __FILE__ ), array( 'wp-color-picker', 'jcrop' ) );
			wp_enqueue_script( 'wgextra-media-script', plugins_url( 'assets/js/media-script.js', __FILE__ ), array(
				'jquery',
				'underscore',
				'wp-color-picker',
				'jquery-ui-slider'
			), true );
			if ( $pagenow === 'post.php' || $pagenow === 'upload.php' ) {
				if ( is_rtl() ) {
					wp_enqueue_style( 'jquery-ui', plugins_url( 'assets/css/jquery-ui-rtl.css', __FILE__ ), false, null );
				} else {
					wp_enqueue_style( 'jquery-ui', plugins_url( 'assets/css/jquery-ui.css', __FILE__ ), false, null );
				}
			}
		}
		
		/**
		 * Add fields to Gallery Settings
		 * @return void
		 */
		public function gallery_custom_fields() {
			$options = $this->OPTIONS;
?>
<script type="text/html" id="tmpl-wgextra-gallery-settings">
	<label class="setting">
		<span><?php _e( 'Template', 'wordpress-gallery-extra' );?></span>
		<select data-setting="template_id">
			<option value="-1"><?php _e( 'Default', 'wordpress-gallery-extra' );?></option>
<?php
			foreach ( $this->TEMPLATES as $id => $template ) {
?>
			<option value="<?php echo $id; ?>"><?php echo $template['name']; ?></option>
<?php
			}
?>
		</select>
	</label>
</script>

<script>
jQuery(document).ready(function(){
	if (wp.media) {
		// add your shortcode attribute and its default value to the
		// gallery settings list; $.extend should work as well...
		_.extend(wp.media.gallery.defaults, {
			template_id: ''
		});

		// merge default gallery settings template with yours
		wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
			template: function(view) {
				return wp.media.template('gallery-settings')(view) + wp.media.template('wgextra-gallery-settings')(view);
			}
		});
	}
});
</script>
<?php
		}

		/**
		 * Function to add needed things to wp head
		 * @return void
		 */
		public function wp_head() {
			ob_start();
?>
		 _       ______  ____  ____  ____  ____  ________________
		| |     / / __ \/ __ \/ __ \/ __ \/ __ \/ ____/ ___/ ___/
		| | /| / / / / / /_/ / / / / /_/ / /_/ / __/  \__ \\__ \
		| |/ |/ / /_/ / _, _/ /_/ / ____/ _, _/ /___ ___/ /__/ /
		|__/|__/\____/_/ |_/_____/_/   /_/ |_/_____//____/____/
		         _________    __    __    ____________  __
		        / ____/   |  / /   / /   / ____/ __ \ \/ /
		       / / __/ /| | / /   / /   / __/ / /_/ /\  /
		      / /_/ / ___ |/ /___/ /___/ /___/ _, _/ / /
		      \____/_/  |_/_____/_____/_____/_/ |_| /_/
		               _______  ____________  ___
		              / ____/ |/ /_  __/ __ \/   |
		             / __/  |   / / / / /_/ / /| |
		            / /___ /   | / / / _, _/ ___ |
		           /_____//_/|_|/_/ /_/ |_/_/  |_|
<?php
			$ascii_art = ob_get_contents();
			ob_get_clean();
			print "\n<!--\n\n\n\n\n\n\n\n\n\n\n
$ascii_art
		Grids & Galleries powered by WordPress Gallery Extra
			    https://wgextra.iprodev.com

\n\n\n\n\n\n\n\n\n\n\n-->\n";
		}

		/**
		 * Function to add plugin scripts & styles
		 * @return void
		 */
		public function enqueue_scripts() {
			global $shortcode_tags;

			$options = $this->OPTIONS;
			$custom_css_file = path_join( $this->PATH, 'assets/css/custom.css' );

			if ( $options['load_library'] === 'yes' || $this->LOAD_LIBRARY ) {
				wp_enqueue_script( 'jquery-easing', plugins_url( 'assets/js/jquery.easing.min.js', __FILE__ ), array( 'jquery' ), '1.3.0', true );
				wp_enqueue_script( 'wgextra-plugins', plugins_url( 'assets/js/plugins.js', __FILE__ ), array( 'jquery' ), $this->VERSION, true );

				$lightbox_types = array();
				foreach ( $this->TEMPLATES as $template ) {
					$template = array_replace_recursive( $this->DEFAULT_TEMPLATE_OPTIONS, $template );

					if ( !in_array( $template['lightbox_type'], $lightbox_types ) ) {
						$lightbox_types[] = $template['lightbox_type'];
					}
				}

				if ( in_array( 'magnific', $lightbox_types ) ) {
					wp_enqueue_script( 'magnific-popup', plugins_url( 'assets/js/jquery.magnific-popup.min.js', __FILE__ ), array( 'jquery' ), '1.1.0', true );
					wp_enqueue_style( 'magnific-popup', plugins_url( 'assets/css/magnific-popup.min.css', __FILE__ ), false, '1.1.0' );
					wp_dequeue_script( 'avia-popup' );
					wp_dequeue_style( 'avia-popup-css' );
				}

				wp_enqueue_script( 'wgextra-front', plugins_url( 'assets/js/front.js', __FILE__ ), array( 'wgextra-plugins', 'underscore' ), $this->VERSION, true );
				wp_enqueue_style( 'wgextra', plugins_url( 'assets/css/front.css', __FILE__ ), false, $this->VERSION );
				wp_enqueue_style( 'wgextra-custom', plugins_url( 'assets/css/custom.css?fm=' . filemtime( $custom_css_file ), __FILE__ ), false, $this->VERSION );
				$wp_localize_script = array(
					"dir_url" => plugin_dir_url( __FILE__ ),
					"debounce_resize" => $options['debounce_resize'],
					"audio_player_type" => $options['audio_player_type'],
					"error_message" => __( 'WordPress Gallery Extra: ', 'wordpress-gallery-extra' ),
					"lightbox_error_message" => __( 'WordPress Gallery Extra Lightbox: ', 'wordpress-gallery-extra' )
				);

				wp_localize_script( 'wgextra-front', 'wgextra', $wp_localize_script );
			}
		}

		/**
		 * Function to check things
		 * @return void
		 */
		public function check_things() {
			/* Check license */
			$options = $this->OPTIONS;
			$license_id = $options['license_id'];
			$license_nonce = $options['license_nonce'];

			if ( $license_id && $license_nonce ){
				$response = json_decode( wp_remote_retrieve_body( wp_remote_get( "https://verify.iprodev.com/check.php?license_id=" . $license_id . "&license_nonce=" . $license_nonce . "&site=" . urlencode( site_url() ) ) ), true );

				if ( isset( $response['status'] ) && $response['status'] != 200 ) {
					$options['license_id'] = "";
					$options['license_nonce'] = "";
					update_option( "{$this->SLUG}_options", $options );
				}
			}

			/* Report Errors */
			if ( $options['crash_report'] === 'yes' ) {
				$errors = get_option( "{$this->SLUG}_errors" );
				$errors_to_report = array();

				foreach ( $errors as $id => $error ) {
					if ( !$error['reported'] ) {
						$errors_to_report[] = $error;
						$errors[$id]['reported'] = true;
					}
				}

				$errors_array = array(
					"plugin" => 'wordpress_gallery_extra',
					"system_info" => $this->system_info_array(),
					"errors" => $errors_to_report
				);

				$report_errors = wp_remote_post(
					"https://api.iprodev.com/crash-report/",
					array(
						'sslverify'  => false,
						'timeout'    => 60,
						'user-agent' => 'iProDev Crash Reporter',
						'body'       => $errors_array
					)
				);

				if ( !is_wp_error( $report_errors ) ) {
					update_option( "{$this->SLUG}_errors", $errors );
				}
			}
		}

		/**
		 * First Page contents initialize.
		 *
		 * @return  void
		 */
		public function init_page() {
			require_once "vendor/autoload.php";
			include_once 'templates/admin/templates.php';
		}


		/**
		 * Settings Page contents initialize.
		 *
		 * @return  void
		 */
		public function settings_page() {
			include_once 'templates/admin/settings.php';
		}


		/**
		 * Help Page contents initialize.
		 *
		 * @return  void
		 */
		public function help_page() {
			include_once 'templates/admin/help.php';
		}
		
		public function admin_notice() {
			if ( !$this->is_ok() ) {
				$settings_url = admin_url( 'admin.php?page=wgextra_settings' );
?>
				<div class="notice notice-error">
					<p><?php printf( __( 'Please configure your gallery credentials in the <a href="%s">settings menu</a> in order to show galleries using "WP Gallery Extra" plugin.', 'wordpress-gallery-extra' ), esc_url( $settings_url ) ); ?></p>
				</div>
				<?php
			}
		}
		
		/**
		 * Function to test all credentials
		 * @return text or errors
		 */
		public function is_ok() {
			return true;
		}

		protected function get_source_items( $template, $atts ) {
			if ( ! $template ) {
				$template = $this->DEFAULT_TEMPLATE_OPTIONS;
			}

			$items = array();

			if ( $template['source']['item_number'] == 0 ) {
				$template['source']['item_number'] = get_option( 'posts_per_page' );
			}

			if ( $template['source']['source'] === 'post_type' ) {
				$search_args = array(
					'posts_per_page' => (int) $template['source']['item_number'],
					'post_status' => 'any',
					'post_type' => 'any',
					'order' => $template['source']['ordering']['order'],
					'orderby' => trim( $template['source']['ordering']['order_by'] ." " . $template['source']['ordering']['order_by_fallback'] )
				);

				// Ordering by Meta Key
				if ( strpos( $search_args['orderby'], 'meta_value' ) !== false ) {
					if ( $template['source']['ordering']['meta_key'] )
						$search_args['meta_key'] = $template['source']['ordering']['meta_key'];
					else
						$search_args['orderby'] = 'menu_order ID';
				}

				// Include posts
				if ( ! empty( $template['source']['include_posts'] ) ) {
					$search_args['post__in'] = wp_parse_id_list( $template['source']['include_posts'] );
				} else {
					// Post Types
					if ( ! empty( $template['source']['post_types'] ) )
						$search_args['post_type'] = $template['source']['post_types'];

					// Post Statuses
					if ( ! empty( $template['source']['post_status'] ) )
						$search_args['post_status'] = $template['source']['post_status'];

					// Post Authors
					if ( ! empty( $template['source']['authors'] ) )
						$search_args['author__' . $template['source']['authors_relation']] = $template['source']['authors'];

					// Post Taxonomies
					if ( ! empty( $template['source']['taxonomies'] ) ) {
						$taxonomies = array();

						foreach ( $template['source']['taxonomies'] as $tax ) {
							$tax_exp = explode( ':', $tax );
							$taxonomy = $tax_exp[0];
							$term = $tax_exp[1];

							if ( ! isset( $taxonomies[$taxonomy] ) )
								$taxonomies[$taxonomy] = array();

							$taxonomies[$taxonomy][] = $term;
						}

						$search_args['tax_query'] = array(
							'relation' => $template['source']['taxonomies_relation']
						);

						foreach ( $taxonomies as $taxonomy => $terms ) {
							$search_args['tax_query'][] = array(
								'taxonomy' => $taxonomy,
								'field'    => 'slug',
								'terms'    => $terms
							);
						}
					}

					// Exclude posts
					if ( ! empty( $search_args['exclude_posts'] ) )
						$search_args['post__not_in'] = wp_parse_id_list( $template['source']['exclude_posts'] );
				}

				$search_args['ignore_sticky_posts'] = true;
				$search_args['no_found_rows'] = true;

				$get_posts = new WP_Query;
				$posts = $get_posts->query( $search_args );

				foreach ( $posts as $post ) {
					$author_data = get_userdata( $post->post_author );
					$date = strtotime( $post->post_date_gmt );
					$id = $post->ID;
					$is_attachment = $post->post_type === 'attachment';
					$is_product = in_array( $post->post_type, array( 'product', 'product_variation' ), true );
					$permalink = get_permalink( $id );
					$post_metadata = self::get_post_meta( $id );
					$custom_url = isset( $post_metadata['_wgextra_custom_url'] ) ? $post_metadata['_wgextra_custom_url'] : false;
					$custom_target = isset( $post_metadata['_wgextra_custom_target'] ) ? $post_metadata['_wgextra_custom_target'] : false;

					// Get thumbnail id
					$thumbnail_id = $is_attachment ? $id : get_post_thumbnail_id( $post );
					if ( $template['default_image'] && is_numeric( $thumbnail_id ) ) {
						$default_image = json_decode( $template['default_image'], true );
						$thumbnail_id = $default_image['id'];
					}

					if ( $custom_url )
						$link = $custom_url;
					else if ( $template['link']['to'] === 'page' )
						$link = $permalink;
					else if ( $template['link']['to'] === 'file' && is_numeric( $thumbnail_id ) )
						$link = wp_get_attachment_url( $thumbnail_id );
					else if ( $template['link']['to'] === 'custom' )
						$link = $template['link']['url'];
					else
						$link = '';

					if ( $custom_target )
						$link_target = $custom_target;
					else
						$link_target = $template['link']['target'];

					$author_avatar = get_avatar( $author_data->ID );
					preg_match('/src=["|\']([^("|\')]+)["|\']/', $author_avatar, $author_avatar_url );

					$item = array(
						'details' => array(
							'id' => $id,
							'title' => nl2br( trim( $post->post_title ) ),
							'caption' => nl2br( trim( $post->post_excerpt ) ),
							'description' => nl2br( trim( $post->post_excerpt ) ),
							'alt' => nl2br( trim( $post->post_title ) ),
							'author' => array(
								'username' => $author_data->user_login,
								'id' => $author_data->ID,
								'name' => $author_data->display_name,
								'first_name' => $author_data->first_name,
								'last_name' => $author_data->last_name,
								'profile' => get_author_posts_url( $author_data->ID ),
								'avatar' => $author_avatar,
								'avatar_url' => !empty( $author_avatar_url ) ? $author_avatar_url[1] : '',
							),
							'time_ago' => sprintf( __( "%s ago", 'wordpress-gallery-extra' ), human_time_diff( $date ) ),
							'date' => date_i18n( "c", $date ),
							'year' => date_i18n( "Y", $date ),
							'month' => date_i18n( "F", $date ),
							'monthnum' => date_i18n( "m", $date ),
							'day' => date_i18n( "l", $date ),
							'daynum' => date_i18n( "d", $date ),
							'post_type' => $post->post_type,
							'placeholder_color' => '',
							'permalink' => $permalink,
							'link' => $link,
							'link_target' => $link_target,
							'items' => null
						),
						'replacable' => array()
					);

					if ( $is_attachment ) {
						$attachment_details = wp_prepare_attachment_for_js( $post );
						$item['details']['description'] = nl2br( trim( $post->post_content ) );
						$item['details']['alt'] = nl2br( trim( $attachment_details['alt'] ) );

						// Group Items
						$shortcode = isset( $post_metadata['_wgextra_group_shortcode'] ) ? $post_metadata['_wgextra_group_shortcode'] : '';
						$group_items = $this->get_shortcode_items( $shortcode );
						$item['details']['items'] = $group_items;
						$item['details']['length'] = !empty( $group_items ) ? count( $group_items ) : 1;

						if ( $template['grouped_items']['mode'] === 'slider' ) {
							$template_id = $template['grouped_items']['template'];
							$thumbnail_size = $template['thumbnail_size'];
							$group_link_to = $template['link']['to'] === "none" ? "none" : "custom";
							$group_link_url = $item['details']['link'];
							$group_link_target = str_replace( "_self", "_parent", $item['details']['link_target'] );
							if ( $template_id >= 0 ) {
								$shortcode = preg_replace( '/template_id=["|\']([^("|\')]+)["|\']/i', 'template_id="' . $template_id . '"', $shortcode );
							}
							if ( preg_match( '/template_id=["|\']([^("|\')]+)["|\']/', $shortcode ) ) {
								$shortcode = preg_replace( '/\[gallery ([^>]+?)[\/ ]*\]/i', '[gallery $1 link="' . $group_link_to . '" link-url="' . $group_link_url . '" link-target="' . $group_link_target . '" lightbox="none" template="slider" slider-sizing-method="fullscreen"]', $shortcode );
							} else {
								$shortcode = preg_replace( '/\[gallery ([^>]+?)[\/ ]*\]/i', '[gallery $1 size="' . $thumbnail_size . '" link="' . $group_link_to . '" link-url="' . $group_link_url . '" link-target="' . $group_link_target . '" lightbox="none" template="slider" columns="1" slider-sizing-method="fullscreen" slider-slides-sizing-method="equal_columns" slider-scrolling="0" slider-arrows="yes" margin="0"]', $shortcode );
							}
							$item['details']['inline_slider'] = htmlspecialchars( $this->do_shortcode( $shortcode ) );
						}
					}

					if ( is_numeric( $thumbnail_id ) ) {
						$attachment_metadata = ( $is_attachment ) ? $post_metadata : self::get_post_meta( $thumbnail_id );
						$original          = wp_get_attachment_image_src( $thumbnail_id, 'original', false );
						$thumb             = wp_get_attachment_image_src( $thumbnail_id, $template['thumbnail_size'], false );
						$medium            = wp_get_attachment_image_src( $thumbnail_id, 'medium', false );
						$thumb_info        = $this->get_image_size( $template['thumbnail_size'] );
						$placeholder_color = isset( $attachment_metadata['_wgextra_dominant_color'] ) ? $attachment_metadata['_wgextra_dominant_color'] : false;
						$ratio             = isset( $attachment_metadata['_wgextra_ratio'] )          ? $attachment_metadata['_wgextra_ratio']          : false;

						$item['details']['placeholder_color'] = $placeholder_color;
						$item['details']['ratio'] = $ratio;

						$item['details']['images'] = array(
							'original' => array(
								'src' => $original[0],
								'width' => $original[1],
								'height' => $original[2],
								'orientation' => $original[2] > $original[1] ? 'portrait' : 'landscape'
							),
							'medium' => array(
								'src' => $medium[0],
								'width' => $medium[1],
								'height' => $medium[2],
								'orientation' => $medium[2] > $medium[1] ? 'portrait' : 'landscape'
							),
							'thumb' => array(
								'src' => $thumb[0],
								'width' => $thumb[1],
								'height' => $thumb[2],
								'orientation' => $thumb[2] > $thumb[1] ? 'portrait' : 'landscape',
								'cropped' => $thumb_info['crop'] ? 'yes' : 'no',
							)
						);

						$item['details']['image_file'] = $original[0];
						$item['details']['thumb_file'] = $thumb[0];

						/* Get image link html */
						if ( 'none' === $template['link']['to'] ) {
							$item['details']['thumbnail'] = wp_get_attachment_image( $thumbnail_id, $template['thumbnail_size'], false );
						} else {
							$item['details']['thumbnail'] = preg_replace( '/href=["|\']([^("|\')]+)["|\']/i', 'href="' . esc_attr( $link ) . '"', wp_get_attachment_link( $thumbnail_id, $template['thumbnail_size'], false, false, false ) );
						}

						// Add 'target' attribute to the link markup.
						if ( preg_match( '/target=["|\']([^("|\')]+)["|\']/', $item['details']['thumbnail'] ) ) {
							$item['details']['thumbnail'] = preg_replace( '/target=["|\']([^("|\')]+)["|\']/i', 'target="' . $link_target . '"', $item['details']['thumbnail'] );
						} else {
							$item['details']['thumbnail'] = preg_replace( '/<a ([^>]+?)[\/ ]*>/i', '<a $1 target="' . $link_target . '">', $item['details']['thumbnail'] );
						}
					}

					$item['replacable'] = $item['details'];

					foreach ( $post_metadata as $meta_key => $meta_value ) {
						if ( is_string( $meta_value ) || is_numeric( $meta_value ) ) {
							$item['replacable'][$meta_key] = $meta_value;
						}
					}

					foreach ( get_post_taxonomies( $post ) as $taxonomy ) {
						$item['replacable'][$taxonomy] = implode( ", ", wp_get_post_terms( $id, $taxonomy, array( 'fields' => 'names' ) ) );
					}

					$items[$id] = $item;
				}
			}

			return apply_filters( 'wgextra_source_items', $items, $template );
		}

		protected function get_shortcode_attachments( $attr ) {
			$post = get_post();

			if ( ! empty( $attr['ids'] ) ) {
				// 'ids' is explicitly ordered, unless you specify otherwise.
				if ( empty( $attr['orderby'] ) ) {
					$attr['orderby'] = 'post__in';
				}
				$attr['include'] = $attr['ids'];
			}

			$atts = shortcode_atts( array(
				'order'       => 'ASC',
				'orderby'     => 'post__in',
				'id'          => $post ? $post->ID : 0,
				'columns'     => 3,
				'size'        => 'thumbnail',
				'include'     => '',
				'exclude'     => '',
				'link'        => 'page',
				'categories'  => '',
				'tags'        => '',
				'limit'       => '-1'
			), $attr, 'gallery' );

			$id = intval( $atts['id'] );

			$search_args = array(
				'posts_per_page' => $atts['limit'],
				'post_status' => 'inherit',
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
				'order' => $atts['order'],
				'orderby' => $atts['orderby']
			);

			if ( empty( $atts['include'] ) && empty( $atts['categories'] ) && empty( $atts['tags'] ) ) {
				$search_args['post_parent'] = $id;
				$attachments = get_children( $search_args );
			} else {
				if ( $this->OPTIONS['media_taxonomies'] === 'yes' && ( ! empty( $atts['categories'] ) || ! empty( $atts['tags'] ) ) ) {
					$search_args['tax_query'] = array();

					if ( ! empty( $atts['categories'] ) && ! empty( $atts['tags'] ) ) {
						$search_args['tax_query']['relation'] = "OR";
					}

					if ( ! empty( $atts['categories'] ) ) {
						$search_args['tax_query'][] = array(
							'taxonomy' => 'attachment_category',
							'field'    => 'name',
							'terms'    => array_keys( array_flip( array_map( 'trim', explode( ',', trim( $atts['categories'] ) ) ) ) ),
						);
					}
					if ( ! empty( $atts['tags'] ) ) {
						$search_args['tax_query'][] = array(
							'taxonomy' => 'attachment_tag',
							'field'    => 'name',
							'terms'    => array_keys( array_flip( array_map( 'trim', explode( ',', trim( $atts['tags'] ) ) ) ) ),
						);
					}
				}
				if ( ! empty( $atts['include'] ) ) {
					$search_args['include'] = $atts['include'];
				}
				if ( ! empty( $atts['exclude'] ) ) {
					$search_args['exclude'] = $atts['exclude'];
				}

				$_attachments = get_posts( $search_args );

				$attachments = array();
				foreach ( $_attachments as $key => $val ) {
					$attachments[$val->ID] = $val;
				}
			}

			return $attachments;
		}

		protected function get_shortcode_items( $shortcode = '' ) {
			$shortcode = trim( $shortcode );
			$items = array();

			if ( empty( $shortcode ) ) {
				return $items;
			}

			$post = get_post();

			$pattern = get_shortcode_regex( array( 'gallery' ) );
			preg_match( "/$pattern/", $shortcode, $m );
			$attr = shortcode_parse_atts( $m[3] );
			$attachments = $this->get_shortcode_attachments( $attr );

			foreach ( $attachments as $id => $attachment ) {
				$attachment_details = wp_prepare_attachment_for_js( $attachment );
				$custom_url         = get_post_meta( $id, '_wgextra_custom_url', true );
				$custom_target      = get_post_meta( $id, '_wgextra_custom_target', true );
				$image_url          = wp_get_attachment_image_src( $id, 'original', false );
				$thumb_url          = wp_get_attachment_image_src( $id, 'thumbnail', false );
				$medium_url         = wp_get_attachment_image_src( $id, 'medium', false );
				$title              = nl2br( trim( $attachment_details['title'] ) );
				$caption            = nl2br( trim( $attachment_details['caption'] ) );
				$description        = nl2br( trim( $attachment_details['description'] ) );

				$item = array(
					"href"    => !empty( $custom_url ) ? $custom_url : $image_url[0],
					"image"  => $image_url,
					"medium"  => $medium_url,
					"target"  => $custom_target,
					"title"   => $title,
					"caption" => !empty( $caption ) ? $caption : $description,
				);

				$items[] = $item;
			}

			return $items;
		}

		/**
		 * Builds the Gallery shortcode output.
		 *
		 * This implements the functionality of the Gallery Shortcode for displaying
		 * WordPress images on a post.
		 *
		 * @staticvar int $instance
		 *
		 * @param array $attr {
		 *     Attributes of the gallery shortcode.
		 *
		 *     @type string       $order      Order of the images in the gallery. Default 'ASC'. Accepts 'ASC', 'DESC'.
		 *     @type string       $orderby    The field to use when ordering the images. Default 'menu_order ID'.
		 *                                    Accepts any valid SQL ORDERBY statement.
		 *     @type int          $id         Post ID.
		 *     @type int          $columns    Number of columns of images to display. Default 3.
		 *     @type string|array $size       Size of the images to display. Accepts any valid image size, or an array of width
		 *                                    and height values in pixels (in that order). Default 'thumbnail'.
		 *     @type string       $ids        A comma-separated list of IDs of attachments to display. Default empty.
		 *     @type string       $include    A comma-separated list of IDs of attachments to include. Default empty.
		 *     @type string       $exclude    A comma-separated list of IDs of attachments to exclude. Default empty.
		 *     @type string       $link       What to link each image to. Default empty (links to the attachment page).
		 *                                    Accepts 'file', 'none'.
		 * }
		 * @return string HTML content to display gallery.
		 */
		public function gallery_shortcode( $attr ) {
			global $wp_version;

			static $wgextra_instance = 0;
			$wgextra_instance++;
			$instance = $wgextra_instance;

			/**
			 * Filters the default gallery shortcode output.
			 *
			 * If the filtered output isn't empty, it will be used instead of generating
			 * the default gallery template.
			 *
			 * @see gallery_shortcode()
			 *
			 * @param string $output   The gallery output. Default empty.
			 * @param array  $attr     Attributes of the gallery shortcode.
			 * @param int    $instance Unique numeric ID of this gallery shortcode instance.
			 */
			$output = apply_filters( 'post_gallery', '', $attr, $instance );
			if ( $output != '' ) {
				return $output;
			}

			$post = get_post();

			$atts = shortcode_atts( array(
				'order'       => 'ASC',
				'orderby'     => 'menu_order ID',
				'id'          => $post ? $post->ID : 0,
				'template_id' => -1,
				'link'        => 'page',
				'options'     => ''
			), $attr, 'gallery' );

			$id = intval( $atts['id'] );

			$generating_styles = false;

			if ( $atts['template_id'] >= 0 && isset( $this->TEMPLATES[$atts['template_id']] ) ) {
				$options = array_replace_recursive( $this->DEFAULT_TEMPLATE_OPTIONS, $this->TEMPLATES[$atts['template_id']] );
			} else {
				$options = $this->DEFAULT_TEMPLATE_OPTIONS;
			}

			if ( ! empty( $atts['options'] ) ) {
				parse_str( rawurldecode( $atts['options'] ), $inline_options );
				$options = array_replace_recursive( $options, $inline_options );

				if ( isset( $inline_options['template'] ) || isset( $inline_options['styles'] ) || isset( $inline_options['slider_settings'] ) )
					$generating_styles = true;
			}

			$options_form_attributes = $this->attributes_to_options( $attr, $options );
			if ( ! empty( $options_form_attributes ) ) {
				$options = array_replace_recursive( $options, $options_form_attributes );
				if ( isset( $options_form_attributes['template'] ) || isset( $options_form_attributes['styles'] ) || isset( $options_form_attributes['slider_settings'] ) )
					$generating_styles = true;
			}

			$atts['size'] = $options['thumbnail_size'];

			$items = $this->get_source_items( $options, $atts );

			if ( empty( $items ) ) {
				return '';
			}

			if ( is_feed() ) {
				$output = "\n";
				foreach ( $items as $item ) {
					$output .= $item['image'] . "\n";
				}
				return $output;
			}

			require_once 'vendor/autoload.php';

			$gallery = array(
				"template" => $options['template'],
				"options" => $options,
				"version" => $this->VERSION,
				"attributes" => array(),
				"items" => array()
			);

			$mosaic_auto_block = array(
				// 2 items
				array( 'columns-6-12 rows-4-12', 'columns-6-12 rows-4-12' ),
				// 3 items
				array( 'columns-8-12 rows-6-12', 'columns-4-12 rows-3-12', 'columns-4-12 rows-3-12' ),
				// 4 items
				array( 'columns-8-12 rows-6-12', 'columns-4-12 rows-3-12', 'columns-4-12 rows-3-12', 'columns-12-12 rows-8-12' ),
				// 5 items
				array( 'columns-8-12 rows-6-12', 'columns-4-12 rows-3-12', 'columns-4-12 rows-3-12', 'columns-6-12 rows-4-12', 'columns-6-12 rows-4-12' ),
				// 6 items
				array( 'columns-6-12 rows-4-12', 'columns-6-12 rows-3-12', 'columns-6-12 rows-4-12', 'columns-6-12 rows-6-12', 'columns-3-12 rows-3-12', 'columns-3-12 rows-3-12' ),
				// 7 items
				array( 'columns-7-12 rows-4-12', 'columns-5-12 rows-4-12', 'columns-8-12 rows-6-12', 'columns-4-12 rows-3-12', 'columns-4-12 rows-3-12', 'columns-5-12 rows-4-12', 'columns-7-12 rows-4-12' ),
				// 8 items
				array( 'columns-7-12 rows-4-12', 'columns-5-12 rows-4-12', 'columns-8-12 rows-6-12', 'columns-4-12 rows-4-12', 'columns-4-12 rows-3-12', 'columns-4-12 rows-4-12', 'columns-4-12 rows-4-12', 'columns-4-12 rows-3-12' ),
				// 9 items
				array( 'columns-7-12 rows-4-12', 'columns-5-12 rows-4-12', 'columns-8-12 rows-6-12', 'columns-4-12 rows-4-12', 'columns-4-12 rows-4-12', 'columns-4-12 rows-3-12', 'columns-4-12 rows-6-12', 'columns-4-12 rows-4-12', 'columns-4-12 rows-3-12' ),
				// 10 items
				array( 'columns-7-12 rows-4-12', 'columns-5-12 rows-4-12', 'columns-4-12 rows-6-12', 'columns-4-12 rows-3-12', 'columns-4-12 rows-6-12', 'columns-4-12 rows-3-12', 'columns-8-12 rows-3-12', 'columns-4-12 rows-6-12', 'columns-4-12 rows-3-12', 'columns-4-12 rows-3-12' ),
			);

			$columns       = self::within( intval( $options['columns'] ), 1, 9 );
			$show_caption  = $options['styles']['has_caption'] === "yes";

			$selector      = "{$this->SLUG}-gallery-{$instance}";

			$size_class = sanitize_html_class( $atts['size'] );
			$classes = array( "{$this->SLUG}-gallery", "galleryid-{$id}" );
			$classes[] = "{$this->SLUG}-{$options['template']}";

			if ( $atts['template_id'] >= 0 && isset( $this->TEMPLATES[$atts['template_id']] ) && !$generating_styles ) {
				$classes[] = "{$this->SLUG}-template-" . $atts['template_id'];
			}

			if ( $options['template'] === 'columns' || $options['template'] === 'masonry' ) {
				$classes[] = "{$this->SLUG}-columns-{$columns}";
			}

			if ( $options['template'] === 'slider' ) {
				$classes[] = "{$this->SLUG}-slider-{$options['slider_settings']['sizing_method']}";
				$classes[] = "{$this->SLUG}-slider-{$options['slider_settings']['slides_sizing_method']}";
				$classes[] = "{$this->SLUG}-slider-{$options['slider_settings']['mode']}";
				$gallery['scrollbar']  = $options['slider_settings']['scrollbar'] === 'yes';
				$gallery['arrows']     = $options['slider_settings']['arrows'] === 'yes';
				$gallery['bullets']    = $options['slider_settings']['bullets'] === 'yes';
				$gallery['thumbnails'] = $options['slider_settings']['thumbnails'] === 'yes';

				if ( $options['slider_settings']['auto_scale'] === 'yes' ) {
					$classes[] = "{$this->SLUG}-slider-auto-scale";
				}
				if ( $options['slider_settings']['scrollbar'] === 'yes' ) {
					$classes[] = "{$this->SLUG}-slider-has-scrollbar";
					$classes[] = "{$this->SLUG}-slider-scrollbar-skin-{$options['styles']['slider']['scrollbar']['skin']}";
					if ( $options['styles']['slider']['scrollbar']['hide'] === 'yes' ) {
						$classes[] = "{$this->SLUG}-slider-scrollbar-hide-on-leave";
					} else {
						$classes[] = "{$this->SLUG}-slider-scrollbar-visible-always";
					}
					if ( $options['styles']['slider']['scrollbar']['inside'] === 'yes' ) {
						$classes[] = "{$this->SLUG}-slider-scrollbar-is-inside";
					} else {
						$classes[] = "{$this->SLUG}-slider-scrollbar-not-inside";
					}
				}
				if ( $options['slider_settings']['arrows'] === 'yes' ) {
					$classes[] = "{$this->SLUG}-slider-has-arrows";
					$classes[] = "{$this->SLUG}-slider-arrows-skin-{$options['styles']['slider']['arrows']['skin']}";
					if ( $options['styles']['slider']['arrows']['hide'] === 'yes' ) {
						$classes[] = "{$this->SLUG}-slider-arrows-hide-on-leave";
					} else {
						$classes[] = "{$this->SLUG}-slider-arrows-visible-always";
					}
				}
				if ( $options['slider_settings']['bullets'] === 'yes' ) {
					$classes[] = "{$this->SLUG}-slider-has-bullets";
					$classes[] = "{$this->SLUG}-slider-bullets-skin-{$options['styles']['slider']['bullets']['skin']}";
					if ( $options['styles']['slider']['bullets']['hide'] === 'yes' ) {
						$classes[] = "{$this->SLUG}-slider-bullets-hide-on-leave";
					} else {
						$classes[] = "{$this->SLUG}-slider-bullets-visible-always";
					}
					if ( $options['styles']['slider']['bullets']['inside'] === 'yes' ) {
						$classes[] = "{$this->SLUG}-slider-bullets-is-inside";
					} else {
						$classes[] = "{$this->SLUG}-slider-bullets-not-inside";
					}
				}
				if ( $options['slider_settings']['thumbnails'] === 'yes' ) {
					$classes[] = "{$this->SLUG}-slider-has-thumbnails";
					$classes[] = "{$this->SLUG}-slider-thumbnails-skin-{$options['styles']['slider']['thumbnails']['skin']}";
					$classes[] = "{$this->SLUG}-slider-thumbnails-position-{$options['styles']['slider']['thumbnails']['position']}";
					if ( $options['styles']['slider']['thumbnails']['hide'] === 'yes' ) {
						$classes[] = "{$this->SLUG}-slider-thumbnails-hide-on-leave";
					} else {
						$classes[] = "{$this->SLUG}-slider-thumbnails-visible-always";
					}
					if ( $options['styles']['slider']['thumbnails']['inside'] === 'yes' ) {
						$classes[] = "{$this->SLUG}-slider-thumbnails-is-inside";
					} else {
						$classes[] = "{$this->SLUG}-slider-thumbnails-not-inside";
					}
				}
				if ( $options['slider_settings']['cycle_by'] && $options['styles']['slider']['time_loader']['appearance'] !== 'none' ) {
					$classes[] = "{$this->SLUG}-slider-has-time-loader";
					$classes[] = "{$this->SLUG}-time-loader-skin-{$options['styles']['slider']['time_loader']['skin']}";
					$classes[] = "{$this->SLUG}-time-loader-appearance-{$options['styles']['slider']['time_loader']['appearance']}";
					$classes[] = "{$this->SLUG}-time-loader-position-{$options['styles']['slider']['time_loader']['position']}";
					if ( $options['styles']['slider']['time_loader']['hide'] === 'yes' ) {
						$classes[] = "{$this->SLUG}-slider-time-loader-hide-on-leave";
					} else {
						$classes[] = "{$this->SLUG}-slider-time-loader-visible-always";
					}
					if ( $options['styles']['slider']['time_loader']['toggle_cycling'] === 'yes' ) {
						$classes[] = "{$this->SLUG}-slider-time-loader-toggle-cycling";
					} else {
						$classes[] = "{$this->SLUG}-slider-time-loader-not-toggle-cycling";
					}
				}
			}

			if ( $show_caption ) {
				$classes[] = "{$this->SLUG}-caption-{$options['styles']['caption']['visibility']}";
				$classes[] = "{$this->SLUG}-caption-{$options['styles']['caption']['position']}";

				if ( $options['styles']['caption']['inset'] === "yes" ) {
					$classes[] = "{$this->SLUG}-caption-inset";
				}
			}

			// Add oading type to class list
			$classes[] = "{$this->SLUG}-loading-" . $options['loading_type'];

			if ( $options['loading_type'] !== 'none' && $options['loading_grid_animation'] ) {
				$classes[] = "{$this->SLUG}-loading-grid-effect";
				$classes[] = "{$this->SLUG}-loading-grid-effect-" . $options['loading_grid_animation'];
			}

			if ( $options['lightbox_type'] !== 'none' ) {
				$classes[] = "disable-lightbox";
			}

			if ( $options['tilt_effect'] === 'yes' ) {
				$classes[] = "{$this->SLUG}-has-tilt";
			}

			if ( !empty( $options['custom_class'] ) ) {
				$classes[] = esc_attr( $options['custom_class'] );
			}

			$classes[] = "{$this->SLUG}-thumb-effect-{$options['styles']['thumbnail_effect']['effect']}";
			$classes[] = "{$this->SLUG}-gallery-size-{$size_class}";

			if ( $options['styles']['has_icon'] === 'yes' ) {
				$classes[] = "{$this->SLUG}-icon-{$options['styles']['icon']['visibility']}";
			}

			if ( $options['styles']['has_overlay'] === 'yes' ) {
				$classes[] = "{$this->SLUG}-overlay-{$options['styles']['overlay']['visibility']}";
			}

			if ( ! empty( $atts['link'] ) ) {
				$classes[] = "{$this->SLUG}-link-{$atts['link']}";
			}

			$classes = apply_filters( 'gallery_classes', $classes );

			$classes_string = implode( ' ', $classes );
			$data_attr_arr  = array(
				"atts" => array(
					"link" => $atts['link'],
					"columns" => $columns
				),
				"template" => $options['template'],
				"loading_type" => $options['loading_type'],
				"last_row" => $options['last_row'],
				"row_height" => (int) $options['row_height'],
				"max_row_height" => $options['max_row_height'],
				"margin" => (int) $options['styles']['margin'],
				"responsive" => $options['responsive'],
				"lightbox_type" => $options['lightbox_type'],
				"loading_grid_animation" => $options['loading_grid_animation'],
				"tilt" => $options['tilt_effect'] === 'yes' ? array(
					"mode" => $options['tilt_options']['mode'],
					"axis" => $options['tilt_options']['axis'] !== 'both' ? $options['tilt_options']['axis'] : null,
					"maxTilt" => $options['tilt_options']['maxtilt'],
					"speed" => $options['tilt_options']['speed'],
					"scale" => $options['tilt_options']['scale'],
					"glare" => $options['tilt_options']['speed'] != 0,
					"maxGlare" => $options['tilt_options']['glare'],
					"perspective" => $options['tilt_options']['perspective'],
					"reset" => $options['tilt_options']['reset'] === 'yes',
					"transition" => $options['tilt_options']['speed'] != 0
				) : false
			);

			if ( $options['lightbox_type'] === 'magnific' ) {
				$data_attr_arr['lightbox_options'] = $options['lightbox_magnific'];
			} else if ( $options['lightbox_type'] === 'ilightbox' ) {
				$data_attr_arr['lightbox_options'] = $options['lightbox_ilightbox'];
			}

			if ( $options['template'] === 'slider' ) {
				$data_attr_arr['slider_settings'] = $options['slider_settings'];
				$data_attr_arr['slider_styles'] = $options['styles']['slider'];
			}

			$data_attr_arr['grouped_items_mode'] = $options['grouped_items']['mode'];

			if ( in_array( $options['template'], array( "columns", "masonry" ) ) && $options['thumbnail_ratio']['type'] !== 'default' ) {
				$data_attr_arr['thumbnail_ratio'] = $options['thumbnail_ratio'];
			}

			$data_attr      = json_encode( $data_attr_arr );
			$gallery['attributes']['id'] = $selector;
			$gallery['attributes']['data-gallery'] = $data_attr;
			$gallery['attributes']['class'] = $classes_string;

			$css_rules = array(
				"#wgextra-gallery-{$instance} .wgextra-item" => array()
			);

			if ( $options['template'] === 'slider' && $options['slider_settings']['slides_sizing_method'] === 'equal_columns' ) {
				$slides_size = 100 / $columns;
				$margin_size = $options['styles']['margin'] / 2 . "px";

				if ( $options['slider_settings']['mode'] === 'horizontal' ) {
					$css_rules["#wgextra-gallery-{$instance} .wgextra-item"] = array_replace_recursive(
						$css_rules["#wgextra-gallery-{$instance} .wgextra-item"],
						array(
							"width" => $columns === 1 ? "{$slides_size}%" : "calc({$slides_size}% - $margin_size)"
						)
					);
				} else {
					$css_rules["#wgextra-gallery-{$instance} .wgextra-item"] = array_replace_recursive(
						$css_rules["#wgextra-gallery-{$instance} .wgextra-item"],
						array(
							"height" => $columns === 1 ? "{$slides_size}%" : "calc({$slides_size}% - $margin_size)"
						)
					);
				}
			}

			$i = 0;
			$i2 = 0;
			$index = 1;
			$count = count( $items );
			foreach ( $items as $item ) {
				$item_details = $item['details'];
				$id = $item_details['id'];
				$item_classes = array( "{$this->SLUG}-item", "{$this->SLUG}-item-{$id}" );
				$group_items  = $item_details['items'];

				$item_details['index'] = sprintf( '%02d', $index );

				$gallery['items'][$i2] = array(
					'attributes' => array()
				);

				$css_rules["#wgextra-gallery-{$instance} .wgextra-item-{$id}"] = array();

				$placeholder_color = isset( $item_details['placeholder_color'] ) ? $item_details['placeholder_color'] : '';

				if ( $placeholder_color ) {
					$parse_placeholder_color = self::color_parse_string( $placeholder_color );
					$brightness = (299 * $parse_placeholder_color['r'] + 587 * $parse_placeholder_color['g'] + 114 * $parse_placeholder_color['b']) / 1000;

					$item_classes[] = ( $brightness < 125 ? "dark" : "light" ) . "-thumb";
				}

				if ( $options['styles']['use_placeholder'] === 'yes' && $placeholder_color ) {
					$css_rules["#wgextra-gallery-{$instance} .wgextra-item-{$id} .wgextra-thumb"] = array();
					$css_rules["#wgextra-gallery-{$instance} .wgextra-item-{$id} .wgextra-thumb:after"] = array();
					$css_rules["#wgextra-gallery-{$instance} .wgextra-item-{$id} .wgextra-caption .wgextra-caption-inner"] = array();
					$css_rules["#wgextra-gallery-{$instance} .wgextra-item-{$id} .wgextra_reveal"] = array();

					if ( $options['styles']['placeholder']['overlay'] === 'yes' && $options['styles']['has_overlay'] === 'yes' ) {
						if ( $options['styles']['overlay']['background']['type'] === 'solid' ) {
							$overlay_color      = array_replace_recursive( self::color_parse_string( $options['styles']['overlay']['background']['solid']['color'] ), $parse_placeholder_color );
							$overlay_background = "rgba({$overlay_color['r']}, {$overlay_color['g']}, {$overlay_color['b']}, {$overlay_color['a']})";
						}
						else if ( $options['styles']['overlay']['type'] === 'gradient' ) {
							$start_color = array_replace_recursive( self::color_parse_string( $options['styles']['overlay']['background']['gradient']['start_color'] ), $parse_placeholder_color );
							$stop_color = array_replace_recursive( self::color_parse_string( $options['styles']['overlay']['background']['gradient']['stop_color'] ), $parse_placeholder_color );

							$overlay_background = self::svg_background( $start_color, $stop_color, $options['styles']['overlay']['background']['gradient']['orientation'] );
						}

						$css_rules["#wgextra-gallery-{$instance} .wgextra-item-{$id} .wgextra-thumb:after"] = array_replace_recursive(
							$css_rules["#wgextra-gallery-{$instance} .wgextra-item-{$id} .wgextra-thumb:after"],
							array(
								"background" => $overlay_background
							)
						);
					}

					if ( $options['styles']['placeholder']['readable_caption'] === 'yes' && $options['styles']['has_caption'] === 'yes' ) {
						if ( $options['styles']['caption']['background']['type'] !== 'none' ) {
							if ( $options['styles']['caption']['background']['type'] === 'solid' ) {
								$caption_bg_color   = array_replace_recursive( self::color_parse_string( $options['styles']['caption']['background']['solid']['color'] ), $parse_placeholder_color );
								$caption_background = "rgba({$caption_bg_color['r']}, {$caption_bg_color['g']}, {$caption_bg_color['b']}, {$caption_bg_color['a']})";
							}
							else if ( $options['styles']['caption']['background']['type'] === 'gradient' ) {
								$start_color = array_replace_recursive( self::color_parse_string( $options['styles']['caption']['background']['gradient']['start_color'] ), $parse_placeholder_color );
								$stop_color = array_replace_recursive( self::color_parse_string( $options['styles']['caption']['background']['gradient']['stop_color'] ), $parse_placeholder_color );

								$caption_background = self::svg_background( $start_color, $stop_color, $options['styles']['caption']['background']['gradient']['orientation'] );
							}

							$css_rules["#wgextra-gallery-{$instance} .wgextra-item-{$id} .wgextra-caption .wgextra-caption-inner"] = array_replace_recursive(
								$css_rules["#wgextra-gallery-{$instance} .wgextra-item-{$id} .wgextra-caption .wgextra-caption-inner"],
								array(
									"background" => $caption_background
								)
							);
						}

						$css_rules["#wgextra-gallery-{$instance} .wgextra-item-{$id} .wgextra-caption .wgextra-caption-inner"] = array_replace_recursive(
							$css_rules["#wgextra-gallery-{$instance} .wgextra-item-{$id} .wgextra-caption .wgextra-caption-inner"],
							array(
								"color" => $parse_placeholder_color['r'] + $parse_placeholder_color['g'] + $parse_placeholder_color['b'] > 382 ? "rgba(0, 0, 0, 0.5)" : "rgba(255, 255, 255, 0.7)"
							)
						);
					}

					if ( $options['styles']['placeholder']['background'] === 'yes' && $options['loading_type'] === 'lazyload' && $options['use_lowres_image'] !== "yes" ) {
						$css_rules["#wgextra-gallery-{$instance} .wgextra-item-{$id} .wgextra-thumb"] = array_replace_recursive(
							$css_rules["#wgextra-gallery-{$instance} .wgextra-item-{$id} .wgextra-thumb"],
							array(
								"background" => $placeholder_color
							)
						);
						$css_rules["#wgextra-gallery-{$instance} .wgextra-item-{$id} .wgextra_reveal"] = array_replace_recursive(
							$css_rules["#wgextra-gallery-{$instance} .wgextra-item-{$id} .wgextra_reveal"],
							array(
								"background" => $placeholder_color
							)
						);
					}
				}

				$link_target    = $item_details['link_target'];
				$original_image = $item_details['images']['original'];
				$thumb_image    = $item_details['images']['thumb'];
				$medium_image   = $item_details['images']['medium'];

				if ( $options['template'] === 'mosaic' ) {
					if ( $options['mosaic_type'] === 'auto' ) {
						if ( $count % 10 === 0 ) {
							$block_size = $mosaic_auto_block[8][$i2 % 10];
							$item_classes[] = $block_size;
						} elseif ( $count % 9 === 0 ) {
							$block_size = $mosaic_auto_block[7][$i2 % 9];
							$item_classes[] = $block_size;
						} elseif ( $count % 8 === 0 ) {
							$block_size = $mosaic_auto_block[6][$i2 % 8];
							$item_classes[] = $block_size;
						} elseif ( $count % 7 === 0 ) {
							$block_size = $mosaic_auto_block[5][$i2 % 7];
							$item_classes[] = $block_size;
						} elseif ( $count % 6 === 0 ) {
							$block_size = $mosaic_auto_block[4][$i2 % 6];
							$item_classes[] = $block_size;
						} elseif ( $count % 5 === 0 ) {
							$block_size = $mosaic_auto_block[3][$i2 % 5];
							$item_classes[] = $block_size;
						} elseif ( $count % 4 === 0 ) {
							$block_size = $mosaic_auto_block[2][$i2 % 4];
							$item_classes[] = $block_size;
						} elseif ( $count % 3 === 0 ) {
							$block_size = $mosaic_auto_block[1][$i2 % 3];
							$item_classes[] = $block_size;
						} elseif ( $count % 2 === 0 ) {
							$block_size = $mosaic_auto_block[0][$i2 % 2];
							$item_classes[] = $block_size;
						} else {
							if ( $i2 === $count - 1 ) {
								if ( $count % 5 === 1 || $count % 5 === 4 ) {
									$item_classes[] = "columns-12-12 rows-6-12";
								} elseif ( $count % 5 === 2 ) {
									$item_classes[] = "columns-4-12 rows-6-12";
								} elseif ( $count % 5 === 3 ) {
									$item_classes[] = "columns-4-12 rows-3-12";
								}
							} else {
								$block_size = $mosaic_auto_block[3][$i2 % 5];
								$item_classes[] = $block_size;
							}
						}
					} else {
						$columns_size = isset( $item['replacable']['_wgextra_columns_size'] ) ? $item['replacable']['_wgextra_columns_size'] : '';
						$rows_size    = isset( $item['replacable']['_wgextra_rows_size'] ) ? $item['replacable']['_wgextra_rows_size'] : '';

						// Columns size
						if ( $columns_size ) {
							$item_classes[] = "columns-{$columns_size}";
						}
						// Rows size
						if ( $rows_size ) {
							$item_classes[] = "rows-{$rows_size}";
						}
					}
				}

				if ( $options['caption_source'] === 'custom' ) {
					$replacable = isset( $item['replacable'] ) ? $item['replacable'] : array();
					$caption_custom = $options['caption_custom'];

					try {
						$caption_loader = new Twig_Loader_Array( array(
							'index' => $caption_custom,
						) );
						$twig = new Twig_Environment( $caption_loader, array(
							'autoescape' => false
						) );

						$caption_text = $twig->render( 'index', $replacable );
					} catch ( Exception $e ) {
						$caption_text = $caption_custom;
					}
				} else {
					$caption_text = nl2br( trim( $item_details[$options['caption_source']] ) );
				}

				$caption_text = do_shortcode( apply_filters( 'gallery_caption_text', $caption_text ) );

				$attr = ( $caption_text ) ? array( 'aria-describedby' => "$selector-$id" ) : '';

				/* Get attachment link html */
				$image_output = $item_details['thumbnail'];

				$image_output = preg_replace_callback( '/(<img.*class=["|\']([^("|\')]+)["|\'][^>]*>)/i', function( $matches ) use ( $id ) {
					$matches[1] = str_replace( $matches[2], $matches[2] . " wp-image-{$id} wgextra-img", $matches[1] );
					return $matches[1];
				}, $image_output );

				if ( $options['link']['to'] !== 'none' && $options['lightbox_type'] !== 'none' ) {
					if ( in_array( $link_target, array( "_lightbox", "_video", "_audio" ) ) ) {
						$item_classes[] = "{$this->SLUG}-open-in-lightbox";
						if ( $link_target === "_video" ) {
							$item_classes[] = "{$this->SLUG}-link-is-video";
						}
						else if ( $link_target === "_audio" ) {
							$item_classes[] = "{$this->SLUG}-link-is-audio";
						}
					}
				}

				if ( !empty( $group_items ) ) {
					$item_classes[] = "{$this->SLUG}-is-group";
					if ( $options['grouped_items']['mode'] === 'lightbox' && $options['lightbox_type'] !== 'none' ) {
						// Add 'target' attribute to the link markup.
						if ( preg_match( '/target=["|\']([^("|\')]+)["|\']/', $image_output ) ) {
							$image_output = preg_replace( '/target=["|\']([^("|\')]+)["|\']/i', 'target="_gallery"', $image_output );
						} else {
							$image_output = preg_replace( '/<a ([^>]+?)[\/ ]*>/i', '<a $1 target="_gallery">', $image_output );
						}
					}

					if ( $options['grouped_items']['mode'] === 'slider' && isset( $item_details['inline_slider'] ) ) {
						$gallery['items'][$i2]['inlineSlider'] = "<div class=\"{$this->SLUG}-inline-slider\" data-content=\"{$item_details['inline_slider']}\"></div>";
						unset( $item_details['inline_slider'] );
					}
				}

				// Hover Icon
				$custom_icon = get_post_meta( $id, '_wgextra_icon', true );
				if ( $options['styles']['has_icon'] === 'yes' || $custom_icon ) {
					$icon = $custom_icon ? $custom_icon : $options['styles']['icon']['icon'];
					$icon_html = "<div class=\"{$this->SLUG}-icon wgextra-icon-{$icon}\"></div>";
					$icon_html = apply_filters( 'gallery_item_icon', $icon_html, $icon );
					$gallery['items'][$i2]['icon'] = $icon_html;
				}

				// Caption text
				if ( $caption_text && $show_caption ) {
					$item_classes[] = $this->SLUG . "-has-caption";
				} else {
					$item_classes[] = $this->SLUG . "-no-caption";
				}

				$item_classes[] = apply_filters( 'gallery_item_orientation', $item_details['images']['original']['orientation'], $item_details['images']['original']['width'], $item_details['images']['original']['height'] );
				if ( $caption_text ) {
					$gallery['items'][$i2]['captionAttributes'] = array(
						"class" => "wp-caption-text",
						"id" => "$selector-$id"
					);
					$item_details['captionText'] = stripslashes( $caption_text );
					$item_details['caption'] = "<div class='{$this->SLUG}-caption'><div class='{$this->SLUG}-caption-inner'>" . stripslashes( $caption_text ) . "</div></div>";
				}
				$gallery['items'][$i2]['attributes']['class'] = implode( ' ', $item_classes );
				$gallery['items'][$i2]['attributes']['data-image'] = $original_image['src'];
				$gallery['items'][$i2]['attributes']['data-image-width'] = $original_image['width'];
				$gallery['items'][$i2]['attributes']['data-image-height'] = $original_image['height'];
				$gallery['items'][$i2]['attributes']['data-medium'] = $medium_image['src'];
				$gallery['items'][$i2]['attributes']['data-medium-width'] = $medium_image['width'];
				$gallery['items'][$i2]['attributes']['data-medium-height'] = $medium_image['height'];
				$gallery['items'][$i2]['attributes']['data-thumb'] = $thumb_image['src'];
				$gallery['items'][$i2]['attributes']['data-thumb-width'] = $thumb_image['width'];
				$gallery['items'][$i2]['attributes']['data-thumb-height'] = $thumb_image['height'];
				$gallery['items'][$i2]['attributes']['data-thumb-cropped'] = $thumb_image['cropped'];

				if( isset( $item_details['ratio'] ) && $item_details['ratio'] )
					$gallery['items'][$i2]['attributes']['data-thumb-ratio'] = $item_details['ratio'];

				$gallery['items'][$i2]['thumbAttributes']['class'] = $this->SLUG . "-thumb";
				$item_details['thumb'] = $image_output;

				$gallery['items'][$i2] = array_replace_recursive( $gallery['items'][$i2], $item_details );

				unset( $item_details['thumb'] );
				unset( $item_details['thumbnail'] );
				$gallery['items'][$i2]['attributes']['data-details'] = json_encode( $item_details );

				$index++;
				$i2++;
			}

			$gallery_style = "";

			if ( $generating_styles ) {
				$gallery_style .= $this->generating_template_css( $options, "#" . $selector, $instance );
			}

			if ( $inline_styles = $this->generate_css_properties( $css_rules ) ) {
				$gallery_style .= $this->minifyCSS( $inline_styles );
			}

			$gallery_style = $gallery_style ? "\n<style type='text/css'>{$gallery_style}</style>\n" : "";

			if ( $options['structure_type'] === 'manual' ) {
				$template = stripcslashes( $options['structure_custom'] );
			} else {
				$template = $this->COLUMNS_DEFAULT_TEMPLATE;

				if ( $options['template'] === 'masonry' || $options['template'] === 'mosaic' ) {
					$template = $this->MASONRY_DEFAULT_TEMPLATE;
				} elseif ( $options['template'] === 'slider' ) {
					$template = $this->SLIDER_DEFAULT_TEMPLATE;
				}
			}

			try {
				$template = "{% htmlcompress %}" . $template . "{% endhtmlcompress %}";
				$template_loader = new Twig_Loader_Array( array(
					'index' => $template,
				) );
				$twig = new Twig_Environment( $template_loader, array(
					'autoescape' => false
				) );
				$twig->addExtension(new \nochso\HtmlCompressTwig\Extension());

				$output = $gallery_style . $twig->render( 'index', $gallery );
				$output .= "<svg style='display: none;'><filter id='grayscale'><feColorMatrix type='matrix' values='0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0'/></filter></svg>";
				$output .= "<svg style='display: none;'><filter id='blur_filter'><feGaussianBlur in='SourceGraphic' stdDeviation='8'/></filter></svg>";
			} catch ( Exception $e ) {
				$output .= __( 'The gallery structure is not valid.', 'wordpress-gallery-extra' );
				$output .= "\nCaught exception: " .  $e->getMessage();
			}

			// Lazyload
			if ( $options['loading_type'] === 'lazyload' ) {
				$output = self::filter_images( $output, array( "preview" => $options['use_lowres_image'] ) );
			}

			if ( version_compare( $wp_version, '4.4', '<' ) ) {
				$output = $this->wp_make_content_images_responsive( $output );
			}

			$this->LOAD_LIBRARY = true;
			if ( $this->OPTIONS['load_library'] === 'no' ) {
				$this->enqueue_scripts();
			}

			if ( !empty( $options['styles']['embed_google_fonts'] ) ) {
				$output = $options['styles']['embed_google_fonts'] . $output;
			}

			$output = apply_filters( 'wgextra_gallery_output', $output, $atts, $options, $instance );

			return $output;
		}

		/**
		 * Split number and unit
		 * @param  $input  number
		 * @return $output string
		 */
		public function split_number( $input = '0') {
			$output = $input;

			$number = intval( $input );
			$unit   = str_replace($number, '', $input);

			return compact('number', 'unit');
		}

		/**
		 * Get size information for all currently-registered image sizes.
		 *
		 * @global $_wp_additional_image_sizes
		 * @uses   get_intermediate_image_sizes()
		 * @return array $sizes Data for all currently-registered image sizes.
		 */
		public static function get_image_sizes() {
			global $_wp_additional_image_sizes;

			$sizes = array();

			foreach ( get_intermediate_image_sizes() as $_size ) {
				if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {
					$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
					$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
					$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
				} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
					$sizes[ $_size ] = array(
						'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
						'height' => $_wp_additional_image_sizes[ $_size ]['height'],
						'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
					);
				}
			}

			return $sizes;
		}

		/**
		 * Get size information for specific registered image size.
		 *
		 * @param $size
		 * @return array Data for specific registered image size.
		 */
		public function get_image_size( $size ) {
			$sizes = self::get_image_sizes();

			if ( isset( $sizes[ $size ] ) ) {
				return $sizes[ $size ];
			}

			return false;
		}

		/**
		 * Get all the available cropping
		 *
		 * @return array
		 *
		 * @param void
		 *
		 * @author Nicolas Juen
		 */
		public function get_available_crop() {
			global $wp_version;

			/**
			 * Base crops
			 */
			$crops = array(
				0 => __( 'No','wordpress-gallery-extra' ),
				1 => __( 'Yes','wordpress-gallery-extra' ),
			);

			if ( version_compare( $wp_version, '3.5', '>=' ) ) {
				$x = array(
					'left'   => __( 'Left', 'wordpress-gallery-extra' ),
					'center' => __( 'Center', 'wordpress-gallery-extra' ),
					'right'  => __( 'Right', 'wordpress-gallery-extra' ),
				);

				$y = array(
					'top'    => __( 'Top', 'wordpress-gallery-extra' ),
					'center' => __( 'Center', 'wordpress-gallery-extra' ),
					'bottom' => __( 'Bottom', 'wordpress-gallery-extra' ),
				);

				foreach ( $x as $x_pos => $x_pos_label ) {
					foreach ( $y as $y_pos => $y_pos_label ) {
						$crops[ $x_pos . ',' . $y_pos ] = $x_pos_label . ', ' . $y_pos_label;
					}
				}
			}

			return $crops;
		}


		/**
		 *
		 * Get a human readable file size
		 *
		 * @param  $bytes     number
		 * @param  $decimals  number
		 * @return            string
		 */
		protected function human_filesize( $bytes, $decimals = 2 ) { 
			$size = array('B','KB','MB','GB','TB','PB','EB','ZB','YB');
			$factor = floor((strlen($bytes) - 1) / 3);
			return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
		} 


		/**
		 *
		 * Get the media list to regenerate
		 *
		 * @param : void
		 *
		 * @return void
		 */
		protected function get_images_list( array $options = array() ) {
			/**
			 * @var $wpdb wpdb
			 */
			global $wpdb;

			/** Creates a default array overwritten with user set options if they exist */
			$default_options = array(
				 "post_types" => ""
			);
			
			$options     = array_merge( $default_options, $options );
			$attachments = array();

			if ( is_array( $options['post_types'] ) && !empty( $options['post_types'] ) ) {

				foreach ( $options['post_types'] as $key => $type ) {
					if ( ! post_type_exists( $type ) ) {
						unset( $options['post_types'][ $key ] );
					}
				}

				if ( empty( $options['post_types'][ $key ] ) ) {
					return array();
				}

				// Get image medias
				$whichmimetype = wp_post_mime_type_where( 'image', $wpdb->posts );

				// Get all parent from post type
				$attachments = $wpdb->get_results( "SELECT ID
					FROM $wpdb->posts
					WHERE 1 = 1
					AND post_type = 'attachment'
					$whichmimetype
					AND post_parent IN (
						SELECT DISTINCT ID 
						FROM $wpdb->posts 
						WHERE post_type IN ('" . implode( "', '", $options['post_types'] ) . "')
					)" );

			} else {

				$attachments = get_posts( array(
					'post_type'      => 'attachment',
					'post_mime_type' => 'image',
					'numberposts'    => - 1,
					'post_status'    => null,
					'post_parent'    => null,
					'output'         => 'ids'
				) );

			}

			if ( !empty( $attachments ) ) {
				$new_attachments = array();
				foreach ( $attachments as $attachment ) {
					$new_attachments[] = intval( $attachment->ID );
				}

				return $new_attachments;
			}

			return $attachments;
		}

		/**
		* Returns the path of the upload directory.
		*
		* @return array|string
		*/
		public function get_uploads_directory() {
			$uploads_directory = wp_upload_dir();
			$uploads_directory = realpath( $uploads_directory["basedir"] );

			return $uploads_directory;
		}
		
		/**
		 * Recursively deletes a directory and its content.
		 *
		 * @since 1.0.0
		 *
		 * @param $directory
		 *
		 * @return bool
		 */
		protected function rmdir( $directory ) {
			if ( is_dir( $directory ) ) {
				return false;
			}

			$objects = (array) scandir( $directory );

			foreach ( $objects as $object ) {
				if ( $object !== "." && $object !== ".." ) {
					if ( filetype( $directory . "/" . $object ) === "dir" ) {
						$this->rmdir( $directory . "/" . $object );
					} else {
						unlink( $directory . "/" . $object );
					}
				}
			}

			reset( $objects );

			return rmdir( $directory );
		}

		/**
		 * Generate post thumbnail attachment meta data.
		 *
		 * @param int $attachment_id Attachment Id to process.
		 * @param string $file Filepath of the Attached image.
		 *
		 * @param null|array $thumbnails: thumbnails to regenerate, if null all
		 *
		 * @return mixed Metadata for attachment.
		 */
		public static function wp_generate_attachment_metadata( $attachment_id, $file, $thumbnails = null ) {
			$attachment = get_post( $attachment_id );

			$meta_datas = get_post_meta( $attachment_id, '_wp_attachment_metadata', true );

			$metadata = array();
			if ( preg_match( '!^image/!', get_post_mime_type( $attachment ) ) && file_is_displayable_image( $file ) ) {
				$imagesize          = getimagesize( $file );
				$metadata['width']  = $imagesize[0];
				$metadata['height'] = $imagesize[1];
				list( $uwidth, $uheight ) = wp_constrain_dimensions( $metadata['width'], $metadata['height'], 128, 96 );
				$metadata['hwstring_small'] = "height='$uheight' width='$uwidth'";

				// Make the file path relative to the upload dir
				$metadata['file'] = _wp_relative_upload_path( $file );

				// make thumbnails and other intermediate sizes
				global $_wp_additional_image_sizes;

				foreach ( get_intermediate_image_sizes() as $s ) {
					$sizes[ $s ] = array( 'width' => '', 'height' => '', 'crop' => false );
					if ( isset( $_wp_additional_image_sizes[ $s ]['width'] ) ) {
						$sizes[ $s ]['width'] = intval( $_wp_additional_image_sizes[ $s ]['width'] );
					} // For theme-added sizes
					else {
						$sizes[ $s ]['width'] = get_option( "{$s}_size_w" );
					} // For default sizes set in options
					if ( isset( $_wp_additional_image_sizes[ $s ]['height'] ) ) {
						$sizes[ $s ]['height'] = intval( $_wp_additional_image_sizes[ $s ]['height'] );
					} // For theme-added sizes
					else {
						$sizes[ $s ]['height'] = get_option( "{$s}_size_h" );
					} // For default sizes set in options
					if ( isset( $_wp_additional_image_sizes[ $s ]['crop'] ) ) {
						$sizes[ $s ]['crop'] = intval( $_wp_additional_image_sizes[ $s ]['crop'] );
					} // For theme-added sizes
					else {
						$sizes[ $s ]['crop'] = get_option( "{$s}_crop" );
					} // For default sizes set in options
				}

				$sizes = apply_filters( 'intermediate_image_sizes_advanced', $sizes );

				// Only if not all sizes
				if ( isset( $thumbnails ) && is_array( $thumbnails ) && isset( $meta_datas['sizes'] ) && ! empty( $meta_datas['sizes'] ) ) {
					// Fill the array with the other sizes not have to be done
					foreach ( $meta_datas['sizes'] as $name => $fsize ) {
						$metadata['sizes'][ $name ] = $fsize;
					}
				}

				foreach ( $sizes as $size => $size_data ) {
					if ( isset( $thumbnails ) ) {
						if ( ! in_array( $size, $thumbnails ) ) {
							continue;
						}
					}

					$resized = image_make_intermediate_size( $file, $size_data['width'], $size_data['height'], $size_data['crop'] );

					if ( isset( $meta_datas['size'][ $size ] ) ) {
						// Remove the size from the orignal sizes for after work
						unset( $meta_datas['size'][ $size ] );
					}

					if ( $resized ) {
						$metadata['sizes'][ $size ] = $resized;
					}
				}

				// fetch additional metadata from exif/iptc
				$image_meta = wp_read_image_metadata( $file );
				if ( $image_meta ) {
					$metadata['image_meta'] = $image_meta;
				}
			}

			return apply_filters( 'wp_generate_attachment_metadata', $metadata, $attachment_id );
		}

		/**
		* Converts a multidimensional array of CSS rules into a CSS string.
		*
		* @param array $rules
		*   An array of CSS rules in the form of:
		*   array('selector'=>array('property' => 'value')). Also supports selector
		*   nesting, e.g.,
		*   array('selector' => array('selector'=>array('property' => 'value'))).
		*
		* @return string
		*   A CSS string of rules. This is not wrapped in <style> tags.
		*/
		protected function generate_css_properties( $rules, $indent = 0 ) {
			$css = '';
			$prefix = str_repeat( "\t", $indent );
			$rules = array_filter( $rules );
			foreach ( $rules as $key => $value ) {
				if ( is_array( $value ) ) {
					$selector = $key;
					$properties = $value;

					$css .= $prefix . "$selector {\n";
					$css .= $prefix . $this->generate_css_properties( $properties, $indent + 1 );
					$css .= $prefix . "}\n";
				}
				else {
					$property = $key;
					$css .= $prefix . "$property: $value;\n";
				}
			}
			return $css;
		}

		protected function sanitize_text_field( $value ) {
			if ( is_string( $value ) ) {
				return sanitize_text_field( $value );
			} elseif ( is_array( $value ) ) {
				return array_map( array( $this, 'sanitize_text_field' ), $value );
			} else {
				return $value;
			}
		}

		// Helper to make a JSON error message
		protected function die_json_error_msg( $id, $message ) {
			wp_send_json( array(
				'status' => 400,
				'message' => sprintf( __( '"%1$s" (ID %2$s) failed to resize. The error message was: %3$s', 'wordpress-gallery-extra' ), esc_html( get_the_title( $id ) ), $id, $message )
			) );
		}

		// Crash report function
		public function crash_report( $errno, $errstr, $errfile, $errline ) {
			$errfile_path = dirname( $errfile );

			if ( !( error_reporting() & $errno ) ) {
				/* This error code is not included in error_reporting, so let it fall
				through to the standard PHP error handler */
				return false;
			}

			/* This error code is not from this directory, so let it fall
			through to the standard PHP error handler */
			if ( stripos( $errfile_path, $this->PATH ) === false ) {
				return false;
			}

			$error_types = array(
				"E_ERROR" => E_ERROR,
				"E_WARNING" => E_WARNING,
				"E_PARSE" => E_PARSE,
				"E_NOTICE" => E_NOTICE,
				"E_CORE_ERROR" => E_CORE_ERROR,
				"E_CORE_WARNING" => E_CORE_WARNING,
				"E_COMPILE_ERROR" => E_COMPILE_ERROR,
				"E_COMPILE_WARNING" => E_COMPILE_WARNING,
				"E_USER_ERROR" => E_USER_ERROR,
				"E_USER_WARNING" => E_USER_WARNING,
				"E_USER_NOTICE" => E_USER_NOTICE,
				"E_STRICT" => E_STRICT,
				"E_RECOVERABLE_ERROR" => E_RECOVERABLE_ERROR,
				"E_DEPRECATED" => E_DEPRECATED,
				"E_USER_DEPRECATED" => E_USER_DEPRECATED
			);

			$error_array = array(
				"type" => array_search( $errno, $error_types ),
				"string" => $errstr,
				"file" => $errfile,
				"line" => $errline,
				"reported" => false
			);

			if ( isset( $_POST ) && !empty( $_POST ) ) {
				$error_array['POST'] = $_POST;
			}

			if ( isset( $_GET ) && !empty( $_GET ) ) {
				$error_array['GET'] = $_GET;
			}

			$errors = get_option( "{$this->SLUG}_errors" );

			$insert_error = true;
			foreach ( $errors as $error ) {
				if ( $error['type'] === $error_array['type'] && $error['string'] === $error_array['string'] && $error['file'] === $error_array['file'] && $error['line'] === $error_array['line'] ) {
					$insert_error = false;
					break;
				}
			}

			if ( $insert_error ) {
				$errors[] = $error_array;
				update_option( "{$this->SLUG}_errors", $errors );
			}

			return false;
		}


		/**
		 * Generate system info for error debug.
		 *
		 * @return  string
		 */
		protected function system_info_array() {
			global $wpdb;

			$info = array();

			$theme_data = wp_get_theme();
			$info['WORDPRESS'] = array();
			$info['WORDPRESS']['active_theme'] = $theme_data->Name . ' ' . $theme_data->Version;
			$info['WORDPRESS']['site_url'] = site_url();
			$info['WORDPRESS']['home_url'] = home_url();
			$info['WORDPRESS']['multisite'] = ( is_multisite() ? 'Yes' : 'No' );
			$info['WORDPRESS']['version'] = get_bloginfo( 'version' );
			$info['WORDPRESS']['locale'] = ( defined( 'WPLANG' ) && WPLANG ? WPLANG : 'en_US' );
			$info['WORDPRESS']['ABSPATH'] = ABSPATH;
			$info['WORDPRESS']['WP_DEBUG'] = ( defined( 'WP_DEBUG' ) ? WP_DEBUG ? 'Enabled' : 'Disabled' : 'Not set' );
			$info['WORDPRESS']['MEMORY_LIMIT'] = WP_MEMORY_LIMIT;
			$info['WEBSERVER']['WEBSERVER'] = $_SERVER['SERVER_SOFTWARE'];
			$info['WEBSERVER']['PHP_VERSION'] = PHP_VERSION;
			$info['WEBSERVER']['DB_VERSION'] = $wpdb->db_version();
			$info['WEBSERVER']['safe_mode'] = ( ini_get( 'safe_mode' ) ? 'Enabled' : 'Disabled' );
			$info['WEBSERVER']['upload_max_filesize'] = ini_get( 'upload_max_filesize' );
			$info['WEBSERVER']['post_max_size'] = ini_get( 'post_max_size' );
			$info['WEBSERVER']['max_execution_time'] = ini_get( 'max_execution_time' );
			$info['WEBSERVER']['max_input_vars'] = ini_get( 'max_input_vars' );
			$info['WEBSERVER']['display_errors'] = ( ini_get( 'display_errors' ) ? 'On (' . ini_get( 'display_errors' ) . ')' : 'N/A' );
			$info['WEBSERVER']['cURL'] = ( function_exists( 'curl_init' ) ? 'Supported' : 'Not Supported' );
			$info['WEBSERVER']['fsockopen'] = ( function_exists( 'fsockopen' ) ? 'Supported' : 'Not Supported' );
			$info['WEBSERVER']['SOAP'] = ( class_exists( 'SoapClient' ) ? 'Installed' : 'Not Installed' );

			return $info;
		}


		/**
		 * Generate system info for error debug.
		 *
		 * @return  string
		 */
		protected function system_info() {
			global $wpdb;

			ob_start();
			print_r( $this->OPTIONS );
			$options = ob_get_contents();
			ob_end_clean();

			// Get theme info
			$theme_data = wp_get_theme();
			$theme      = $theme_data->Name . ' ' . $theme_data->Version;

			$return  = '### Begin System Info ###' . "\n\n";
			// Start with the basics...
			$return .= '-- Site Info' . "\n\n";
			$return .= 'Site URL:                 ' . site_url() . "\n";
			$return .= 'Home URL:                 ' . home_url() . "\n";
			$return .= 'Multisite:                ' . ( is_multisite() ? 'Yes' : 'No' ) . "\n";

			// WordPress configuration
			$return .= "\n" . '-- WordPress Configuration' . "\n\n";
			$return .= 'Version:                  ' . get_bloginfo( 'version' ) . "\n";
			$return .= 'Language:                 ' . ( defined( 'WPLANG' ) && WPLANG ? WPLANG : 'en_US' ) . "\n";
			$return .= 'Permalink Structure:      ' . ( get_option( 'permalink_structure' ) ? get_option( 'permalink_structure' ) : 'Default' ) . "\n";
			$return .= 'Active Theme:             ' . $theme . "\n";
			$return .= 'Show On Front:            ' . get_option( 'show_on_front' ) . "\n";
			// Only show page specs if frontpage is set to 'page'
			if( get_option( 'show_on_front' ) == 'page' ) {
				$front_page_id = get_option( 'page_on_front' );
				$blog_page_id = get_option( 'page_for_posts' );
				$return .= 'Page On Front:            ' . ( $front_page_id != 0 ? get_the_title( $front_page_id ) . ' (#' . $front_page_id . ')' : 'Unset' ) . "\n";
				$return .= 'Page For Posts:           ' . ( $blog_page_id != 0 ? get_the_title( $blog_page_id ) . ' (#' . $blog_page_id . ')' : 'Unset' ) . "\n";
			}
			$return .= 'ABSPATH:                  ' . ABSPATH . "\n";
			// Make sure wp_remote_post() is working
			$request['cmd'] = '_notify-validate';
			$params = array(
				'sslverify'     => false,
				'timeout'       => 60,
				'user-agent'    => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36',
				'body'          => $request
			);
			$response = wp_remote_post( 'https://www.paypal.com/cgi-bin/webscr', $params );
			if( !is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) {
				$WP_REMOTE_POST = 'wp_remote_post() works';
			} else {
				$WP_REMOTE_POST = 'wp_remote_post() does not work';
			}
			$return .= 'Remote Post:              ' . $WP_REMOTE_POST . "\n";
			$return .= 'Table Prefix:             ' . 'Length: ' . strlen( $wpdb->prefix ) . '   Status: ' . ( strlen( $wpdb->prefix ) > 16 ? 'ERROR: Too long' : 'Acceptable' ) . "\n";

			$return .= 'WP_DEBUG:                 ' . ( defined( 'WP_DEBUG' ) ? WP_DEBUG ? 'Enabled' : 'Disabled' : 'Not set' ) . "\n";
			$return .= 'Memory Limit:             ' . WP_MEMORY_LIMIT . "\n";

			$return .= "\n-- WordPress Gallery Extra Version\n";
			$return .= $this->VERSION;

			// Configurations
			$return .= "\n\n-- WordPress Gallery Extra Configurations\n";
			$return .= $options;


			// Get plugins that have an update
			$updates = get_plugin_updates();
			// Must-use plugins
			// NOTE: MU plugins can't show updates!
			$muplugins = get_mu_plugins();
			if( count( $muplugins > 0 ) ) {
				$return .= "\n" . '-- Must-Use Plugins' . "\n\n";
				foreach( $muplugins as $plugin => $plugin_data ) {
					$return .= $plugin_data['Name'] . ': ' . $plugin_data['Version'] . "\n";
				}

			}
			// WordPress active plugins
			$return .= "\n" . '-- WordPress Active Plugins' . "\n\n";
			$plugins = get_plugins();
			$active_plugins = get_option( 'active_plugins', array() );
			foreach( $plugins as $plugin_path => $plugin ) {
				if( !in_array( $plugin_path, $active_plugins ) )
					continue;
				$update = ( array_key_exists( $plugin_path, $updates ) ) ? ' (needs update - ' . $updates[$plugin_path]->update->new_version . ')' : '';
				$return .= $plugin['Name'] . ': ' . $plugin['Version'] . $update . "\n";
			}

			// WordPress inactive plugins
			$return .= "\n" . '-- WordPress Inactive Plugins' . "\n\n";
			foreach( $plugins as $plugin_path => $plugin ) {
				if( in_array( $plugin_path, $active_plugins ) )
					continue;
				$update = ( array_key_exists( $plugin_path, $updates ) ) ? ' (needs update - ' . $updates[$plugin_path]->update->new_version . ')' : '';
				$return .= $plugin['Name'] . ': ' . $plugin['Version'] . $update . "\n";
			}

			if( is_multisite() ) {
				// WordPress Multisite active plugins
				$return .= "\n" . '-- Network Active Plugins' . "\n\n";
				$plugins = wp_get_active_network_plugins();
				$active_plugins = get_site_option( 'active_sitewide_plugins', array() );
				foreach( $plugins as $plugin_path ) {
					$plugin_base = plugin_basename( $plugin_path );
					if( !array_key_exists( $plugin_base, $active_plugins ) )
						continue;
					$update = ( array_key_exists( $plugin_path, $updates ) ) ? ' (needs update - ' . $updates[$plugin_path]->update->new_version . ')' : '';
					$plugin  = get_plugin_data( $plugin_path );
					$return .= $plugin['Name'] . ': ' . $plugin['Version'] . $update . "\n";
				}
			}
			// Server configuration (really just versioning)
			$return .= "\n" . '-- Webserver Configuration' . "\n\n";
			$return .= 'PHP Version:              ' . PHP_VERSION . "\n";
			$return .= 'MySQL Version:            ' . $wpdb->db_version() . "\n";
			$return .= 'Webserver Info:           ' . $_SERVER['SERVER_SOFTWARE'] . "\n";

			// PHP configs... now we're getting to the important stuff
			$return .= "\n" . '-- PHP Configuration' . "\n\n";
			$return .= 'Safe Mode:                ' . ( ini_get( 'safe_mode' ) ? 'Enabled' : 'Disabled' . "\n" );
			$return .= 'Memory Limit:             ' . ini_get( 'memory_limit' ) . "\n";
			$return .= 'Upload Max Size:          ' . ini_get( 'upload_max_filesize' ) . "\n";
			$return .= 'Post Max Size:            ' . ini_get( 'post_max_size' ) . "\n";
			$return .= 'Upload Max Filesize:      ' . ini_get( 'upload_max_filesize' ) . "\n";
			$return .= 'Time Limit:               ' . ini_get( 'max_execution_time' ) . "\n";
			$return .= 'Max Input Vars:           ' . ini_get( 'max_input_vars' ) . "\n";
			$return .= 'Display Errors:           ' . ( ini_get( 'display_errors' ) ? 'On (' . ini_get( 'display_errors' ) . ')' : 'N/A' ) . "\n";

			// PHP extensions and such
			$return .= "\n" . '-- PHP Extensions' . "\n\n";
			$return .= 'cURL:                     ' . ( function_exists( 'curl_init' ) ? 'Supported' : 'Not Supported' ) . "\n";
			$return .= 'fsockopen:                ' . ( function_exists( 'fsockopen' ) ? 'Supported' : 'Not Supported' ) . "\n";
			$return .= 'SOAP Client:              ' . ( class_exists( 'SoapClient' ) ? 'Installed' : 'Not Installed' ) . "\n";
			$return .= 'Suhosin:                  ' . ( extension_loaded( 'suhosin' ) ? 'Installed' : 'Not Installed' ) . "\n";

			$return .= "\n" . '### End System Info ###';

			return $return;
		}


		/**
		 * Validate color string
		 *
		 * @param sring $color - the color value
		 *
		 * @return boolean
		 */
		public static function is_valid_color( $color ) {
			if ( $color[0] === "#" ) {
				$color = substr( $color, 1 );
				return in_array( strlen( $color ), [3, 4, 6, 8] ) && ctype_xdigit( $color ) ;
			} else {
				return preg_match( "/^(rgb|hsl)a?\((\d+%?(deg|rad|grad|turn)?[,\s]+){2,3}[\s\/]*[\d\.]+%?\)$/i", $color );
			}
		}

		/**
		 * Parse a string color into the components
		 *
		 * @param sring $string - the string value
		 *
		 * @return array('r'=> , 'g' =>, 'b' => )
		 */
		public static function color_parse_string( $string ) {
			$string = str_replace( ' ', '', strtolower( $string ) );
			
			// Hex colors #000000
			if ( preg_match('/^#(?P<r>[0-9A-F]{2})(?P<g>[0-9A-F]{2})(?P<b>[0-9A-F]{2})$/i', $string, $matches ) ) {
				return array(
					'r' => hexdec( $matches['r'] ),
					'g' => hexdec( $matches['g'] ),
					'b' => hexdec( $matches['b'] ),
				);
			}
			// Hex color #000
			if ( preg_match('/^#(?P<r>[0-9A-F])(?P<g>[0-9A-F])(?P<b>[0-9A-F])$/i', $string, $matches ) ) {
				return array(
					'r' => hexdec( $matches['r'] . $matches['r'] ),
					'g' => hexdec( $matches['g'] . $matches['g'] ),
					'b' => hexdec( $matches['b'] . $matches['b'] ),
				);
			}
			// rgb(255,255,255)
			if ( preg_match('/^rgb(?P<is_a>a)?\((?P<r>\d+),(?P<g>\d+),(?P<b>\d+)(,(?P<a>\d(\.\d*)?))?\)$/i', $string, $matches ) ) {
				$r = array(
					'r' => intval( $matches['r'] ),
					'g' => intval( $matches['g'] ),
					'b' => intval( $matches['b'] ),
				);
				if ( $matches['is_a'] && isset($matches['a']) ) {
					$r['a'] = floatval( $matches['a'] );
				}
				return $r;
			}
			// rgb(50%,0%,100%)
			if ( preg_match('/^rgb(?P<is_a>a)?\((?P<r>\d+(\.\d*)?)%,(?P<g>\d+(\.\d*)?)%,(?P<b>\d+(\.\d*)?)%(,(?P<a>\d(\.\d*)?))?\)$/i', $string, $matches ) ) {
				$r = array(
					'r' => round( floatval( $matches['r'] ) * 2.55 ),
					'g' => round( floatval( $matches['g'] ) * 2.55 ),
					'b' => round( floatval( $matches['b'] ) * 2.55 ),
				);
				if ( $matches['is_a'] && isset($matches['a']) ) {
					$r['a'] = floatval( $matches['a'] );
				}
				return $r;
			}
			// hsl(360,0%,100%)
			if ( preg_match('/^hsl(?P<is_a>a)?\((?P<h>\d+(\.\d*)?),(?P<s>\d+(\.\d*)?)%,(?P<l>\d+(\.\d*)?)%(,(?P<a>\d(\.\d*)?))?\)$/i', $string, $matches ) ) {
				$r = array(
					'h' => floatval( $matches['h'] ),
					's' => floatval( $matches['s'] ) / 100,
					'l' => floatval( $matches['l'] ) / 100,
				);
				if ( $matches['is_a'] && isset($matches['a']) ) {
					$r['a'] = floatval( $matches['a'] );
				}
				return $r;
			}
			return array();
		}

		/**
		 * Convert any color type to rgba
		 *
		 * @param sring $color - the color value
		 *
		 * @return string
		 */
		public static function convert_to_rgba( $color ) {
			if ( !self::is_valid_color( $color ) ) {
				return false;
			}

			if ( substr( $color, 0, 4 ) === 'rgba' ) {
				return $color;
			}

			$rgba = self::color_parse_string( $color );

			if ( !isset( $rgba['a'] ) ) {
				$rgba['a'] = 1;
			}

			return 'rgba(' . implode( ', ', $rgba ) . ')';
		}


		/**
		 * Convert hex color to rgb
		 *
		 * @param sring $color - the hex string value
		 *
		 * @return array('r'=> , 'g' =>, 'b' => )
		 */
		public static function hex2rgb( $color ) {
			if ( $color[0] == '#' ) {
				$color = substr($color, 1);
			}

			if ( strlen( $color ) == 6 ) {
				list( $r, $g, $b ) = array( $color[0].$color[1], $color[2].$color[3], $color[4].$color[5] );
			}

			elseif (strlen($color) == 3) {
				list( $r, $g, $b ) = array( $color[0].$color[0], $color[1].$color[1], $color[2].$color[2] );
			}
			else {
				return false;
			}

			$r = hexdec( $r ); $g = hexdec( $g ); $b = hexdec( $b );

			return array( $r, $g, $b );
		}

		/**
		 * Convert rgb color to hex
		 *
		 * @param sring/array $r - the red string value or array with red green blue colors
		 * @param sring       $g - the string value
		 * @param sring       $b - the string value
		 *
		 * @return string
		 */
		public static function rgb2hex( $r, $g=-1, $b=-1 ) {
			if ( is_array( $r ) && sizeof( $r ) == 3 ) {
				list( $r, $g, $b ) = $r;
			}

			$r = intval( $r );
			$g = intval( $g );
			$b = intval( $b );

			$r = dechex( $r < 0 ? 0 : ( $r > 255 ? 255 : $r ) );
			$g = dechex( $g < 0 ? 0 : ( $g > 255 ? 255 : $g ) );
			$b = dechex( $b < 0 ? 0 : ( $b > 255 ? 255 : $b ) );

			$color  = ( strlen( $r ) < 2 ? '0' : '' ) . $r;
			$color .= ( strlen( $g ) < 2 ? '0' : '' ) . $g;
			$color .= ( strlen( $b ) < 2 ? '0' : '' ) . $b;
			return '#' . $color;
		}


		public static function svg_background( $start_color, $stop_color, $orientation ) {
			if ( !is_array( $start_color ) )
				$start_color = self::color_parse_string( $start_color );
			if ( !is_array( $stop_color ) )
				$stop_color = self::color_parse_string( $stop_color );

			$svg =  "<?xml version=\"1.0\" ?>" .
					"<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"100%\" height=\"100%\" viewBox=\"0 0 1 1\" preserveAspectRatio=\"none\">";

			if ( $orientation === 'vertical' ) {
				$svg .= "<linearGradient id=\"grad-wgextra-generated\" gradientUnits=\"userSpaceOnUse\" x1=\"0%\" y1=\"0%\" x2=\"0%\" y2=\"100%\">" .
							"<stop offset=\"0%\" stop-opacity=\"{$start_color['a']}\" stop-color=\"rgb({$start_color['r']}, {$start_color['g']}, {$start_color['b']})\"/>" .
							"<stop offset=\"100%\" stop-opacity=\"{$stop_color['a']}\" stop-color=\"rgb({$stop_color['r']}, {$stop_color['g']}, {$stop_color['b']})\"/>" .
						"</linearGradient>";
			} else if ( $orientation === 'diagonal_45' ) {
				$svg .= "<linearGradient id=\"grad-wgextra-generated\" gradientUnits=\"userSpaceOnUse\" x1=\"0%\" y1=\"100%\" x2=\"100%\" y2=\"0%\">" .
							"<stop offset=\"0%\" stop-opacity=\"{$start_color['a']}\" stop-color=\"rgb({$start_color['r']}, {$start_color['g']}, {$start_color['b']})\"/>" .
							"<stop offset=\"100%\" stop-opacity=\"{$stop_color['a']}\" stop-color=\"rgb({$stop_color['r']}, {$stop_color['g']}, {$stop_color['b']})\"/>" .
						"</linearGradient>";
			} else if ( $orientation === 'diagonal_n45' ) {
				$svg .= "<linearGradient id=\"grad-wgextra-generated\" gradientUnits=\"userSpaceOnUse\" x1=\"0%\" y1=\"0%\" x2=\"100%\" y2=\"100%\">" .
							"<stop offset=\"0%\" stop-opacity=\"{$start_color['a']}\" stop-color=\"rgb({$start_color['r']}, {$start_color['g']}, {$start_color['b']})\"/>" .
							"<stop offset=\"100%\" stop-opacity=\"{$stop_color['a']}\" stop-color=\"rgb({$stop_color['r']}, {$stop_color['g']}, {$stop_color['b']})\"/>" .
						"</linearGradient>";
			} else if ( $orientation === 'radial' ) {
				$svg .= "<radialGradient id=\"grad-wgextra-generated\" gradientUnits=\"userSpaceOnUse\" cx=\"50%\" cy=\"50%\" r=\"50%\">" .
							"<stop offset=\"0%\" stop-opacity=\"{$stop_color['a']}\" stop-color=\"rgb({$stop_color['r']}, {$stop_color['g']}, {$stop_color['b']})\"/>" .
							"<stop offset=\"100%\" stop-opacity=\"{$start_color['a']}\" stop-color=\"rgb({$start_color['r']}, {$start_color['g']}, {$start_color['b']})\"/>" .
						"</radialGradient>";
			} else {
				$svg .= "<linearGradient id=\"grad-wgextra-generated\" gradientUnits=\"userSpaceOnUse\" x1=\"0%\" y1=\"0%\" x2=\"100%\" y2=\"0%\">" .
							"<stop offset=\"0%\" stop-opacity=\"{$start_color['a']}\" stop-color=\"rgb({$start_color['r']}, {$start_color['g']}, {$start_color['b']})\"/>" .
							"<stop offset=\"100%\" stop-opacity=\"{$stop_color['a']}\" stop-color=\"rgb({$stop_color['r']}, {$stop_color['g']}, {$stop_color['b']})\"/>" .
						"</linearGradient>";
			}

			if ( $orientation === 'radial' ) {
				$svg .= "<rect x=\"-50\" y=\"-50\" width=\"101\" height=\"101\" fill=\"url(#grad-wgextra-generated)\" />";
			} else {
				$svg .= "<rect x=\"0\" y=\"0\" width=\"1\" height=\"1\" fill=\"url(#grad-wgextra-generated)\" />";
			}

			$svg .= "</svg>";
			return "url(data:image/svg+xml;base64," . base64_encode( $svg ) . ")";
		}


		protected function generating_template_css( $template = array(), $template_class = ".wgextra-gallery", $id ) {
			$output = "";

			$template = array_replace_recursive( $this->DEFAULT_TEMPLATE_OPTIONS, $template );
			$css_rules  = array();
			$variables  = array();
			$has        = array(
				"element_gap" => in_array( $template['template'], array( 'columns', 'masonry', 'mosaic' ) ),
				"border" => $template['styles']['has_border'] === 'yes',
				"shadow" => $template['styles']['has_shadow'] === 'yes',
				"icon" => $template['styles']['has_icon'] === 'yes',
				"caption" => $template['styles']['has_caption'] === 'yes',
				"overlay" => $template['styles']['has_overlay'] === 'yes'
			);
			$slider_settings = $template['slider_settings'];
			$slider_styles = $template['styles']['slider'];

			$css_rules[$template_class] = array(
				".wgextra-frame" => array(),
				".wgextra-item" => array(),
				".wgextra-thumb" => array(
					"&:hover" => array(),
					"&:before" => array(),
					"&:after" => array(),
					"a" => array(),
					"img" => array(),
					".wgextra-icon" => array()
				),
				".wgextra-caption" => array(
					".wgextra-caption-inner" => array()
				),
				".wp-caption-text" => array(),
				".wgextra-scrollbar" => array(
					".wgextra-handle" => array()
				),
				".wgextra-bullets" => array(),
				".wgextra-thumbnails" => array(
					"li" => array()
				)
			);

			$margin_size = $variables['spacing'] = $template['styles']['margin'] . "px";
			$margin_size_half = $template['styles']['margin'] / 2 . "px";

			if ( $template['template'] !== 'slider' && $margin_size != '0px' ) {
				$css_rules[$template_class]["width"] = "calc(~\"100% + \"($margin_size * 2))";
			}

			if ( $template['template'] === 'justified' && $margin_size != '0px' ) {
				$css_rules[$template_class]["margin"] = "-" . $margin_size;
			}

			if ( $has['element_gap'] ) {
				$alignment = $template['alignment'];
				$vertical_alignment = $template['vertical_alignment'];

				$css_rules[$template_class] = array_replace_recursive(
					$css_rules[$template_class],
					array(
						"width" => "calc(~\"100% + $margin_size\")",
						"text-align" => $alignment,
						"margin" => "-" . $margin_size_half
					)
				);
				$css_rules[$template_class]['.wgextra-item']["padding"] = $margin_size_half;
				$css_rules[$template_class]['.wgextra-item']["vertical-align"] = $vertical_alignment;
				/*if ( is_rtl() ) {
					$css_rules[$template_class] = array_replace_recursive(
						$css_rules[$template_class],
						array(
							"margin-right" => "-" . $margin_size
						)
					);
					$css_rules[$template_class]['.wgextra-item'] = array_replace_recursive(
						$css_rules[$template_class]['.wgextra-item'],
						array(
							"padding-right" => $margin_size,
							"padding-bottom" => $margin_size
						)
					);
				} else {
					$css_rules[$template_class] = array_replace_recursive(
						$css_rules[$template_class],
						array(
							"margin-left" => "-" . $margin_size
						)
					);
					$css_rules[$template_class]['.wgextra-item'] = array_replace_recursive(
						$css_rules[$template_class]['.wgextra-item'],
						array(
							"padding-left" => $margin_size,
							"padding-bottom" => $margin_size
						)
					);
				}*/
			} elseif ( $template['template'] === 'slider' ) {
				if ( $slider_settings['sizing_method'] !== 'fullscreen' ) {
					$width  = $slider_settings['width'] . "px";
					$height = $slider_settings['height'] . "px";

					$css_rules[$template_class]['.wgextra-frame']["width"] = $slider_settings['sizing_method'] === 'boxed' ? $width : null;
					$css_rules[$template_class]['.wgextra-frame']["height"] = $height;
				}

				if ( $slider_settings['mode'] === 'horizontal' ) {
					$css_rules[$template_class]['.wgextra-item']["margin-right"] = $margin_size;
				} else {
					$css_rules[$template_class]['.wgextra-item']["margin-bottom"] = $margin_size;
				}

				if ( $slider_settings['scrollbar'] === 'yes' ) {
					$variables['scrollbar-track-color-type'] = $slider_styles['scrollbar']['track_color']['type'];
					$variables['scrollbar-handle-color-type'] = $slider_styles['scrollbar']['handle_color']['type'];
					$track_background = "";
					$handle_background = "";

					// Scrollbar track background
					if ( $variables['scrollbar-track-color-type'] === 'solid' ) {
						$track_background = $variables['scrollbar-track-color-background'] = $slider_styles['scrollbar']['track_color']['solid']['color'];
					}
					else if ( $variables['scrollbar-track-color-type'] === 'gradient' ) {
						$variables['scrollbar-track-color-start-color'] = $slider_styles['scrollbar']['track_color']['gradient']['start_color'];
						$variables['scrollbar-track-color-stop-color'] = $slider_styles['scrollbar']['track_color']['gradient']['stop_color'];
						$variables['scrollbar-track-color-orientation'] = $slider_styles['scrollbar']['track_color']['gradient']['orientation'];

						$track_background = $variables['scrollbar-track-color-background'] = self::svg_background( $variables['scrollbar-track-color-start-color'], $variables['scrollbar-track-color-stop-color'], $variables['scrollbar-track-color-orientation'] );
					}

					// Scrollbar handle background
					if ( $variables['scrollbar-handle-color-type'] === 'solid' ) {
						$handle_background = $variables['scrollbar-handle-color-background'] = $slider_styles['scrollbar']['handle_color']['solid']['color'];
					}
					else if ( $variables['scrollbar-handle-color-type'] === 'gradient' ) {
						$variables['scrollbar-handle-color-start-color'] = $slider_styles['scrollbar']['handle_color']['gradient']['start_color'];
						$variables['scrollbar-handle-color-stop-color'] = $slider_styles['scrollbar']['handle_color']['gradient']['stop_color'];
						$variables['scrollbar-handle-color-orientation'] = $slider_styles['scrollbar']['handle_color']['gradient']['orientation'];

						$handle_background = $variables['scrollbar-handle-color-background'] = self::svg_background( $variables['scrollbar-handle-color-start-color'], $variables['scrollbar-handle-color-stop-color'], $variables['scrollbar-handle-color-orientation'] );
					}

					if ( $track_background ) {
						$css_rules[$template_class]['.wgextra-scrollbar']["background"] = $track_background;
					}

					if ( $handle_background ) {
						$css_rules[$template_class]['.wgextra-scrollbar']['.wgextra-handle']["background"] = $handle_background;
					}

					if ( $slider_styles['scrollbar']['size'] ) {
						$variables['scrollbar-size'] = $slider_styles['scrollbar']['size'] . $slider_styles['scrollbar']['size_unit'];
						if ( $slider_settings['mode'] === 'horizontal' ) {
							$css_rules[$template_class]['.wgextra-scrollbar']["width"] = $variables['scrollbar-size'] . " !important";
						} else {
							$css_rules[$template_class]['.wgextra-scrollbar']["height"] = $variables['scrollbar-size'] . " !important";
						}
					}
				}

				if ( $slider_settings['bullets'] === 'yes' && $slider_styles['bullets']['color'] ) {
					$variables['bullets-color'] = $slider_styles['bullets']['color'];

					if ( $variables['bullets-color'] ) {
						$css_rules[$template_class]['.wgextra-bullets']["color"] = $variables['bullets-color'];
					}
				}

				if ( $slider_settings['thumbnails'] === 'yes' ) {
					$is_horizontal = in_array( $slider_styles['thumbnails']['position'], array( "top", "bottom" ) );
					if ( $slider_styles['thumbnails']['spacing'] ) {
						$variables['thumbnails-spacing'] = $slider_styles['thumbnails']['spacing'] . $slider_styles['thumbnails']['spacing_unit'];

						if ( $is_horizontal ) {
							$css_rules[$template_class]['.wgextra-thumbnails']['li']["margin-right"] = $variables['thumbnails-spacing'];
						} else {
							$css_rules[$template_class]['.wgextra-thumbnails']['li']["margin-bottom"] = $variables['thumbnails-spacing'];
						}
					}
					if ( $slider_styles['thumbnails']['size'] ) {
						$variables['thumbnails-size'] = $slider_styles['thumbnails']['size'] . $slider_styles['thumbnails']['size_unit'];

						if ( $is_horizontal ) {
							$css_rules[$template_class]['.wgextra-thumbnails']["width"] = $variables['thumbnails-size'];
						} else {
							$css_rules[$template_class]['.wgextra-thumbnails']["height"] = $variables['thumbnails-size'];
						}
					}
				}
			}

			// Thumbnail Effect trasnsition
			if ( $template['styles']['thumbnail_effect']['effect'] !== 'none' ) {
				$thumb_transition_speed = $template['styles']['thumbnail_effect']['transition']['speed'];
				$thumb_transition_delay = $template['styles']['thumbnail_effect']['transition']['delay'];
				$thumb_transition_easing = $template['styles']['thumbnail_effect']['transition']['easing'];
				$variables['thumbnail-transition-speed'] = $thumb_transition_speed;
				$variables['thumbnail-transition-delay'] = $thumb_transition_delay;
				if( $thumb_transition_easing )
					$variables['thumbnail-transition-easing'] = $thumb_transition_easing;

				$css_rules[$template_class]['.wgextra-thumb']['img'] = array_replace_recursive(
					$css_rules[$template_class]['.wgextra-thumb']['img'],
					array(
						"-webkit-transition-duration" => $thumb_transition_speed . "ms",
						"-webkit-transition-delay" => $thumb_transition_delay . "ms",
						"-webkit-transition-timing-function" => $thumb_transition_easing,
						"transition-duration" => $thumb_transition_speed . "ms",
						"transition-delay" => $thumb_transition_delay . "ms",
						"transition-timing-function" => $thumb_transition_easing
					)
				);
			}

			// Border
			if ( $has['border'] ) {
				$border_weight = $variables['border-weight'] = $template['styles']['border']['weight'] . "px";
				$border_style = $variables['border-style'] = $template['styles']['border']['style'];
				$border_color = $variables['border-color'] = $template['styles']['border']['color'];
				$border_radius = $variables['border-radius'] = $template['styles']['border']['radius'] . "px";
				$variables['border'] = "{$border_weight} {$border_style} {$border_color}";

				$css_rules[$template_class]['.wgextra-thumb'] = array_replace_recursive(
					$css_rules[$template_class]['.wgextra-thumb'],
					array(
						"border" => $variables['border'],
						"border-radius" => $border_radius,
						"&:before" => array(
							"border-radius" => $border_radius
						)
					)
				);
			}

			// Box Shadow
			if ( $has['shadow'] ) {
				$shadow_x = $variables['shadow-x'] = $template['styles']['shadow']['x'] . "px";
				$shadow_y = $variables['shadow-y'] = $template['styles']['shadow']['y'] . "px";
				$shadow_blur = $variables['shadow-blur'] = $template['styles']['shadow']['blur'] . "px";
				$shadow_spread = $variables['shadow-spread'] = $template['styles']['shadow']['spread'] . "px";
				$shadow_color = $variables['shadow-color'] = $template['styles']['shadow']['color'];

				if ( $template['styles']['shadow']['inset'] === 'yes' ) {
					$variables['shadow'] = "{$shadow_x} {$shadow_y} {$shadow_blur} {$shadow_spread} {$shadow_color} inset";
					$css_rules[$template_class]['.wgextra-thumb'] = array_replace_recursive(
						$css_rules[$template_class]['.wgextra-thumb'],
						array(
							"&:before" => array(
								"content" => "''",
								"box-shadow" => $variables['shadow']
							)
						)
					);
				} else {
					$variables['shadow'] = "{$shadow_x} {$shadow_y} {$shadow_blur} {$shadow_spread} {$shadow_color}";
					$css_rules[$template_class]['.wgextra-thumb']["box-shadow"] = $variables['shadow'];
				}
			}

			// Icon
			if ( $has['icon'] ) {
				$icon_color = $template['styles']['icon']['color'];
				$icon_size = $template['styles']['icon']['size'] . "px";
				$icon_transition_speed = $template['styles']['icon']['transition']['speed'];
				$icon_transition_delay = $template['styles']['icon']['transition']['delay'];
				$icon_transition_easing = $template['styles']['icon']['transition']['easing'];
				$variables['icon-color'] = $icon_color;
				$variables['icon-size'] = $icon_size;
				$variables['icon-transition-speed'] = $icon_transition_speed;
				$variables['icon-transition-delay'] = $icon_transition_delay;
				if( $icon_transition_easing )
					$variables['icon-transition-easing'] = $icon_transition_easing;

				$css_rules[$template_class]['.wgextra-thumb']['.wgextra-icon'] = array_replace_recursive(
					$css_rules[$template_class]['.wgextra-thumb']['.wgextra-icon'],
					array(
						"color" => $icon_color,
						"font-size" => $icon_size,
						"&:before" => array(
							"-webkit-animation-duration" => $icon_transition_speed . "ms",
							"-webkit-animation-timing-function" => $icon_transition_easing,
							"animation-duration" => $icon_transition_speed . "ms",
							"animation-timing-function" => $icon_transition_easing
						)
					)
				);
				$css_rules[$template_class]['.wgextra-thumb'] = array_replace_recursive(
					$css_rules[$template_class]['.wgextra-thumb'],
					array(
						"&:hover .wgextra-icon:before" => array(
							"-webkit-animation-delay" => $icon_transition_delay . "ms",
							"animation-delay" => $icon_transition_delay . "ms"
						)
					)
				);
			}

			// Caption
			if ( $has['caption'] ) {
				$caption_color = $template['styles']['caption']['color'];
				$variables['caption-color'] = $caption_color;
				$variables['caption-bg-type'] = $template['styles']['caption']['background']['type'];
				$caption_transition_speed = $template['styles']['caption']['transition']['speed'];
				$caption_transition_delay = $template['styles']['caption']['transition']['delay'];
				$caption_transition_easing = $template['styles']['caption']['transition']['easing'];
				$variables['caption-transition-speed'] = $caption_transition_speed;
				$variables['caption-transition-delay'] = $caption_transition_delay;
				if( $caption_transition_easing )
					$variables['caption-transition-easing'] = $caption_transition_easing;

				if ( $variables['caption-bg-type'] === 'solid' ) {
					$caption_background = $variables['caption-background'] = $variables['caption-bg-color'] = $template['styles']['caption']['background']['solid']['color'];
				}
				else if ( $variables['caption-bg-type'] === 'gradient' ) {
					$variables['caption-bg-start-color'] = $template['styles']['caption']['background']['gradient']['start_color'];
					$variables['caption-bg-stop-color'] = $template['styles']['caption']['background']['gradient']['stop_color'];
					$variables['caption-bg-orientation'] = $template['styles']['caption']['background']['gradient']['orientation'];

					$caption_background = $variables['caption-background'] = self::svg_background( $variables['caption-bg-start-color'], $variables['caption-bg-stop-color'], $variables['caption-bg-orientation'] );
				} else {
					$caption_background = $variables['caption-background'] = "transparent";
				}

				if ( $template['styles']['caption']['inset'] === 'yes' ) {
					$css_rules[$template_class]['.wgextra-caption']['.wgextra-caption-inner'] = array_replace_recursive(
						$css_rules[$template_class]['.wgextra-caption']['.wgextra-caption-inner'],
						array(
							"color" => $caption_color,
							"background" => $caption_background,
							"background-size" => "100% 100%",
							"-webkit-animation-duration" => $caption_transition_speed . "ms",
							"-webkit-animation-timing-function" => $caption_transition_easing,
							"animation-duration" => $caption_transition_speed . "ms",
							"animation-timing-function" => $caption_transition_easing
						)
					);
					$css_rules[$template_class]['.wgextra-thumb'] = array_replace_recursive(
						$css_rules[$template_class]['.wgextra-thumb'],
						array(
							"&:hover .wgextra-caption > .wgextra-caption-inner" => array(
								"-webkit-animation-delay" => $caption_transition_delay . "ms",
								"animation-delay" => $caption_transition_delay . "ms"
							)
						)
					);
				} else {
					$css_rules[$template_class]['.wp-caption-text'] = array_replace_recursive(
						$css_rules[$template_class]['.wp-caption-text'],
						array(
							"color" => $caption_color,
							"background" => $caption_background,
							"background-size" => "100% 100%",
							"-webkit-animation-duration" => $caption_transition_speed . "ms",
							"-webkit-animation-timing-function" => $caption_transition_easing,
							"animation-duration" => $caption_transition_speed . "ms",
							"animation-timing-function" => $caption_transition_easing
						)
					);
					$css_rules[$template_class]['.wgextra-thumb'] = array_replace_recursive(
						$css_rules[$template_class]['.wgextra-thumb'],
						array(
							"&:hover .wp-caption-text" => array(
								"-webkit-animation-delay" => $caption_transition_delay . "ms",
								"animation-delay" => $caption_transition_delay . "ms"
							)
						)
					);
				}
			}

			// Overlay
			if ( $has['overlay'] ) {
				$variables['overlay-type'] = $template['styles']['overlay']['background']['type'];

				$overlay_transition_speed = $template['styles']['overlay']['transition']['speed'];
				$overlay_transition_delay = $template['styles']['overlay']['transition']['delay'];
				$overlay_transition_easing = $template['styles']['overlay']['transition']['easing'];
				$variables['overlay-transition-speed'] = $overlay_transition_speed;
				$variables['overlay-transition-delay'] = $overlay_transition_delay;
				if( $overlay_transition_easing )
					$variables['overlay-transition-easing'] = $overlay_transition_easing;

				if ( $variables['overlay-type'] === 'solid' ) {
					$overlay_background = $variables['overlay-background'] = $variables['overlay-color'] = $template['styles']['overlay']['background']['solid']['color'];
				}
				else if ( $variables['overlay-type'] === 'gradient' ) {
					$variables['overlay-start-color'] = $template['styles']['overlay']['background']['gradient']['start_color'];
					$variables['overlay-stop-color'] = $template['styles']['overlay']['background']['gradient']['stop_color'];
					$variables['overlay-orientation'] = $template['styles']['overlay']['background']['gradient']['orientation'];

					$overlay_background = $variables['overlay-background'] = self::svg_background( $variables['overlay-start-color'], $variables['overlay-stop-color'], $variables['overlay-orientation'] );
				} else {
					$overlay_background = $variables['overlay-background'] = "transparent";
				}

				$css_rules[$template_class]['.wgextra-thumb'] = array_replace_recursive(
					$css_rules[$template_class]['.wgextra-thumb'],
					array(
						"&:after" => array(
							"content" => "''",
							"background" => $overlay_background,
							"background-size" => "100% 100%",
							"-webkit-animation-duration" => $overlay_transition_speed . "ms",
							"-webkit-animation-timing-function" => $overlay_transition_easing,
							"animation-duration" => $overlay_transition_speed . "ms",
							"animation-timing-function" => $overlay_transition_easing
						)
					)
				);
				$css_rules[$template_class]['.wgextra-thumb'] = array_replace_recursive(
					$css_rules[$template_class]['.wgextra-thumb'],
					array(
						"&:hover:after" => array(
							"-webkit-animation-delay" => $overlay_transition_delay . "ms",
							"animation-delay" => $overlay_transition_delay . "ms"
						)
					)
				);
			}

			$input  = $this->generate_css_properties( $css_rules );
			$custom_css = stripslashes( $template['styles']['custom_css'] );
			if ( $custom_css ) {
				$custom_css = str_replace( array(
					"%id%",
					"%gallery%",
					".%gallery%"
				), array(
					$id,
					$template_class,
					$template_class
				), $custom_css );
				$input .= $custom_css;
			}

			if ( $template['template'] === 'slider' ) {
				if ( $slider_settings['arrows'] === 'yes' && $slider_styles['arrows']['under'] ) {
					$hide_arrows_under = $slider_styles['arrows']['under'] . $slider_styles['arrows']['under_unit'];
					$input .= '@media only screen and (max-width: ' . $hide_arrows_under . ') {' . $template_class . ' .wgextra-arrows {display: none !important;}}';
				}
				if ( $slider_settings['scrollbar'] === 'yes' && $slider_styles['scrollbar']['under'] ) {
					$hide_scrollbar_under = $slider_styles['scrollbar']['under'] . $slider_styles['scrollbar']['under_unit'];
					$input .= '@media only screen and (max-width: ' . $hide_scrollbar_under . ') {' . $template_class . ' .wgextra-scrollbar {display: none !important;}}';
				}
				if ( $slider_settings['bullets'] === 'yes' && $slider_styles['bullets']['under'] ) {
					$hide_bullets_under = $slider_styles['bullets']['under'] . $slider_styles['bullets']['under_unit'];
					$input .= '@media only screen and (max-width: ' . $hide_bullets_under . ') {' . $template_class . ' .wgextra-bullets {display: none !important;}}';
				}
				if ( $slider_settings['cycle_by'] && $slider_styles['time_loader']['appearance'] !== 'none' ) {
					$hide_time_loader_under = $slider_styles['time_loader']['under'] . $slider_styles['time_loader']['under_unit'];
					$input .= '@media only screen and (max-width: ' . $hide_time_loader_under . ') {' . $template_class . ' .timeLoader {display: none !important;}}';
				}
			}

			$responsive = $template['responsive'];
			$max_width = $responsive['desktop']['size'] - 1;
			$mediaquery_css = "";
			foreach ( $responsive as $desvice => $value ) {
				if ( $desvice === 'desktop' )
					continue;

				$mediaquery = "@media screen ";

				if ( $max_width )
					$mediaquery .= "and (max-width: {$max_width}px) ";

				$mediaquery .= '{';

				$responsive_css_rules[$template_class] = array(
					".wgextra-item" => array(),
					".wgextra-thumb" => array(
						".wgextra-icon" => array()
					)
				);

				// Spacing
				if ( isset( $value['spacing'] ) && $value['spacing'] ) {
					$margin_size = "{$value['spacing']}px";
					$margin_size_half = $value['spacing'] / 2 . "px";

					if ( $template['template'] !== 'slider' && $margin_size != '0px' )
						$responsive_css_rules[$template_class]["width"] = "calc(~\"100% + \"($margin_size * 2))";

					if ( $template['template'] === 'justified' && $margin_size != '0px' )
						$responsive_css_rules[$template_class]["margin"] = "-" . $margin_size;

					if ( $has['element_gap'] ) {
						$responsive_css_rules[$template_class]["width"] = "calc(~\"100% + $margin_size\")";
						$responsive_css_rules[$template_class]["margin"] = "-" . $margin_size_half;
						$responsive_css_rules[$template_class]['.wgextra-item']["padding"] = $margin_size_half;
					} elseif ( $template['template'] === 'slider' ) {
						if ( $slider_settings['mode'] === 'horizontal' ) {
							$responsive_css_rules[$template_class]['.wgextra-item']["margin-right"] = $margin_size;
						} else {
							$responsive_css_rules[$template_class]['.wgextra-item']["margin-bottom"] = $margin_size;
						}
					}
				}

				// Icon
				if ( $has['icon'] && isset( $value['icon_size'] ) && $value['icon_size'] )
					$css_rules[$template_class]['.wgextra-thumb']['.wgextra-icon']['font-size'] = $value['icon_size'] . "px";

				$generate_css = $this->generate_css_properties( $responsive_css_rules );

				if ( $generate_css === $mediaquery_css )
					continue;

				$mediaquery .= $generate_css;

				$mediaquery .= '}';

				// Set max-width
				$max_width = $value['size'] - 1;
				// Reset last media-query-css
				$mediaquery_css = $generate_css;

				if ( $generate_css )
					$input .= $mediaquery;
			}

			$output = apply_filters( 'wgextra_template_css', $this->less2css( $input, $variables ), $template );

			return $output;
		}



		/**
		 * Generate templates styles css file
		 *
		 * @return array
		 */
		public function generating_styles( $templates = array(), $options = array() ) {
			if ( empty( $templates ) ) {
				$templates = $this->TEMPLATES;
			}
			$error   = array();
			$output  = "";

			foreach ( $templates as $id => $template ) {
				$output .= $this->generating_template_css( $template, ".wgextra-template-{$id}", $id );
			}

			if ( empty( $error ) && !empty( $output ) ) {
				return trim( $output );
			}
			else {
				return null;
			}
		}

		/**
		 * Generate templates styles css file
		 *
		 * @return array
		 */
		protected function save_styles( $templates = array() ) {
			$output = $this->generating_styles( $templates );

			if ( $output ) {
				$cssfile = path_join( $this->PATH, 'assets/css/custom.css' );
				// Change custom.css file permissions to Read and write for owner, read for everybody else
				if ( !is_writable( $cssfile ) )
					chmod( $cssfile, 0644 );
				return file_put_contents( $cssfile, $output, LOCK_EX ) ;
			}

			return false;
		}


		/**
		 * Filters 'img' elements in post content to add 'srcset' and 'sizes' attributes.
		 *
		 * @access protected
		 *
		 * @param string $content The raw post content to be filtered.
		 * @return string Converted content with 'srcset' and 'sizes' attributes added to images.
		 */
		protected function wp_make_content_images_responsive( $content ) {
			if ( ! preg_match_all( '/<img [^>]+>/', $content, $matches ) ) {
				return $content;
			}
		 
			$selected_images = $attachment_ids = array();
		 
			foreach( $matches[0] as $image ) {
				if ( false === strpos( $image, ' srcset=' ) && preg_match( '/wp-image-([0-9]+)/i', $image, $class_id ) &&
					( $attachment_id = absint( $class_id[1] ) ) ) {
		 
					/*
					 * If exactly the same image tag is used more than once, overwrite it.
					 * All identical tags will be replaced later with 'str_replace()'.
					 */
					$selected_images[ $image ] = $attachment_id;
					// Overwrite the ID when the same image is included more than once.
					$attachment_ids[ $attachment_id ] = true;
				}
			}
		 
			if ( count( $attachment_ids ) > 1 ) {
				/*
				 * Warm object cache for use with 'get_post_meta()'.
				 *
				 * To avoid making a database call for each image, a single query
				 * warms the object cache with the meta information for all images.
				 */
				update_meta_cache( 'post', array_keys( $attachment_ids ) );
			}
		 
			foreach ( $selected_images as $image => $attachment_id ) {
				$image_meta = wp_get_attachment_metadata( $attachment_id );
				$content = str_replace( $image, $this->wp_image_add_srcset_and_sizes( $image, $image_meta, $attachment_id ), $content );
			}
		 
			return $content;
		}


		/**
		 * Adds 'srcset' and 'sizes' attributes to an existing 'img' element.
		 *
		 * @access protected
		 *
		 * @param string $image         An HTML 'img' element to be filtered.
		 * @param array  $image_meta    The image meta data as returned by 'wp_get_attachment_metadata()'.
		 * @param int    $attachment_id Image attachment ID.
		 * @return string Converted 'img' element with 'srcset' and 'sizes' attributes added.
		 */
		protected function wp_image_add_srcset_and_sizes( $image, $image_meta, $attachment_id ) {
			// Ensure the image meta exists.
			if ( empty( $image_meta['sizes'] ) ) {
				return $image;
			}
		 
			$image_src = preg_match( '/src="([^"]+)"/', $image, $match_src ) ? $match_src[1] : '';
			list( $image_src ) = explode( '?', $image_src );

			// Return early if we couldn't get the image source.
			if ( ! $image_src ) {
				return $image;
			}

			// Bail early if an image has been inserted and later edited.
			if ( preg_match( '/-e[0-9]{13}/', $image_meta['file'], $img_edit_hash ) &&
				strpos( wp_basename( $image_src ), $img_edit_hash[0] ) === false ) {
		 
				return $image;
			}

			$width  = preg_match( '/ width="([0-9]+)"/',  $image, $match_width  ) ? (int) $match_width[1]  : 0;
			$height = preg_match( '/ height="([0-9]+)"/', $image, $match_height ) ? (int) $match_height[1] : 0;

			if ( ! $width || ! $height ) {
				/*
				 * If attempts to parse the size value failed, attempt to use the image meta data to match
				 * the image file name from 'src' against the available sizes for an attachment.
				 */
				$image_filename = wp_basename( $image_src );

				if ( $image_filename === wp_basename( $image_meta['file'] ) ) {
					$width = (int) $image_meta['width'];
					$height = (int) $image_meta['height'];
				} else {
					foreach( $image_meta['sizes'] as $image_size_data ) {
						if ( $image_filename === $image_size_data['file'] ) {
							$width = (int) $image_size_data['width'];
							$height = (int) $image_size_data['height'];
							break;
						}
					}
				}
			}

			if ( ! $width || ! $height ) {
				return $image;
			}

			$size_array = array( $width, $height );
			$srcset = $this->wp_calculate_image_srcset( $size_array, $image_src, $image_meta, $attachment_id );

			if ( $srcset ) {
				// Check if there is already a 'sizes' attribute.
				$sizes = strpos( $image, ' sizes=' );

				if ( ! $sizes ) {
					$sizes = $this->wp_calculate_image_sizes( $size_array, $image_src, $image_meta, $attachment_id );
				}
			}

			if ( $srcset && $sizes ) {
				// Format the 'srcset' and 'sizes' string and escape attributes.
				$attr = sprintf( ' srcset="%s"', esc_attr( $srcset ) );

				if ( is_string( $sizes ) ) {
					$attr .= sprintf( ' sizes="%s"', esc_attr( $sizes ) );
				}

				// Add 'srcset' and 'sizes' attributes to the image markup.
				$image = preg_replace( '/<img ([^>]+?)[\/ ]*>/', '<img $1' . $attr . ' />', $image );
			}

			return $image;
		}


		/**
		 * Retrieves the value for an image attachment's 'srcset' attribute.
		 *
		 * @access protected
		 *
		 * @param int          $attachment_id Image attachment ID.
		 * @param array|string $size          Optional. Image size. Accepts any valid image size, or an array of
		 *                                    width and height values in pixels (in that order). Default 'medium'.
		 * @param array        $image_meta    Optional. The image meta data as returned by 'wp_get_attachment_metadata()'.
		 *                                    Default null.
		 * @return string|bool A 'srcset' value string or false.
		 */
		protected function wp_calculate_image_srcset( $size_array, $image_src, $image_meta, $attachment_id = 0 ) {
			/**
			 * Let plugins pre-filter the image meta to be able to fix inconsistencies in the stored data.
			 *
			 * @param array  $image_meta    The image meta data as returned by 'wp_get_attachment_metadata()'.
			 * @param array  $size_array    Array of width and height values in pixels (in that order).
			 * @param string $image_src     The 'src' of the image.
			 * @param int    $attachment_id The image attachment ID or 0 if not supplied.
			 */
			$image_meta = apply_filters( 'wp_calculate_image_srcset_meta', $image_meta, $size_array, $image_src, $attachment_id );
		 
			if ( empty( $image_meta['sizes'] ) || ! isset( $image_meta['file'] ) || strlen( $image_meta['file'] ) < 4 ) {
				return false;
			}
		 
			$image_sizes = $image_meta['sizes'];
		 
			// Get the width and height of the image.
			$image_width = (int) $size_array[0];
			$image_height = (int) $size_array[1];
		 
			// Bail early if error/no width.
			if ( $image_width < 1 ) {
				return false;
			}
		 
			$image_basename = wp_basename( $image_meta['file'] );
		 
			/*
			 * WordPress flattens animated GIFs into one frame when generating intermediate sizes.
			 * To avoid hiding animation in user content, if src is a full size GIF, a srcset attribute is not generated.
			 * If src is an intermediate size GIF, the full size is excluded from srcset to keep a flattened GIF from becoming animated.
			 */
			if ( ! isset( $image_sizes['thumbnail']['mime-type'] ) || 'image/gif' !== $image_sizes['thumbnail']['mime-type'] ) {
				$image_sizes[] = array(
					'width'  => $image_meta['width'],
					'height' => $image_meta['height'],
					'file'   => $image_basename,
				);
			} elseif ( strpos( $image_src, $image_meta['file'] ) ) {
				return false;
			}
		 
			// Retrieve the uploads sub-directory from the full size image.
			$dirname = $this->_wp_get_attachment_relative_path( $image_meta['file'] );
		 
			if ( $dirname ) {
				$dirname = trailingslashit( $dirname );
			}
		 
			$upload_dir = $this->get_uploads_directory();
			$image_baseurl = trailingslashit( $upload_dir ) . $dirname;
		 
			/*
			 * If currently on HTTPS, prefer HTTPS URLs when we know they're supported by the domain
			 * (which is to say, when they share the domain name of the current request).
			 */
			if ( is_ssl() && 'https' !== substr( $image_baseurl, 0, 5 ) && parse_url( $image_baseurl, PHP_URL_HOST ) === $_SERVER['HTTP_HOST'] ) {
				$image_baseurl = set_url_scheme( $image_baseurl, 'https' );
			}
		 
			/*
			 * Images that have been edited in WordPress after being uploaded will
			 * contain a unique hash. Look for that hash and use it later to filter
			 * out images that are leftovers from previous versions.
			 */
			$image_edited = preg_match( '/-e[0-9]{13}/', wp_basename( $image_src ), $image_edit_hash );
		 
			/**
			 * Filters the maximum image width to be included in a 'srcset' attribute.
			 *
			 * @since 4.4.0
			 *
			 * @param int   $max_width  The maximum image width to be included in the 'srcset'. Default '1600'.
			 * @param array $size_array Array of width and height values in pixels (in that order).
			 */
			$max_srcset_image_width = apply_filters( 'max_srcset_image_width', 1600, $size_array );
		 
			// Array to hold URL candidates.
			$sources = array();
		 
			/**
			 * To make sure the ID matches our image src, we will check to see if any sizes in our attachment
			 * meta match our $image_src. If no matches are found we don't return a srcset to avoid serving
			 * an incorrect image. See #35045.
			 */
			$src_matched = false;
		 
			/*
			 * Loop through available images. Only use images that are resized
			 * versions of the same edit.
			 */
			foreach ( $image_sizes as $image ) {
				$is_src = false;
		 
				// Check if image meta isn't corrupted.
				if ( ! is_array( $image ) ) {
					continue;
				}
		 
				// If the file name is part of the `src`, we've confirmed a match.
				if ( ! $src_matched && false !== strpos( $image_src, $dirname . $image['file'] ) ) {
					$src_matched = $is_src = true;
				}
		 
				// Filter out images that are from previous edits.
				if ( $image_edited && ! strpos( $image['file'], $image_edit_hash[0] ) ) {
					continue;
				}
		 
				/*
				 * Filters out images that are wider than '$max_srcset_image_width' unless
				 * that file is in the 'src' attribute.
				 */
				if ( $max_srcset_image_width && $image['width'] > $max_srcset_image_width && ! $is_src ) {
					continue;
				}
		 
				// If the image dimensions are within 1px of the expected size, use it.
				if ( $this->wp_image_matches_ratio( $image_width, $image_height, $image['width'], $image['height'] ) ) {
					// Add the URL, descriptor, and value to the sources array to be returned.
					$source = array(
						'url'        => $image_baseurl . $image['file'],
						'descriptor' => 'w',
						'value'      => $image['width'],
					);
		 
					// The 'src' image has to be the first in the 'srcset', because of a bug in iOS8. See #35030.
					if ( $is_src ) {
						$sources = array( $image['width'] => $source ) + $sources;
					} else {
						$sources[ $image['width'] ] = $source;
					}
				}
			}
		 
			/**
			 * Filters an image's 'srcset' sources.
			 *
			 * @since 4.4.0
			 *
			 * @param array  $sources {
			 *     One or more arrays of source data to include in the 'srcset'.
			 *
			 *     @type array $width {
			 *         @type string $url        The URL of an image source.
			 *         @type string $descriptor The descriptor type used in the image candidate string,
			 *                                  either 'w' or 'x'.
			 *         @type int    $value      The source width if paired with a 'w' descriptor, or a
			 *                                  pixel density value if paired with an 'x' descriptor.
			 *     }
			 * }
			 * @param array  $size_array    Array of width and height values in pixels (in that order).
			 * @param string $image_src     The 'src' of the image.
			 * @param array  $image_meta    The image meta data as returned by 'wp_get_attachment_metadata()'.
			 * @param int    $attachment_id Image attachment ID or 0.
			 */
			$sources = apply_filters( 'wp_calculate_image_srcset', $sources, $size_array, $image_src, $image_meta, $attachment_id );
		 
			// Only return a 'srcset' value if there is more than one source.
			if ( ! $src_matched || count( $sources ) < 2 ) {
				return false;
			}
		 
			$srcset = '';
		 
			foreach ( $sources as $source ) {
				$srcset .= str_replace( ' ', '%20', $source['url'] ) . ' ' . $source['value'] . $source['descriptor'] . ', ';
			}
		 
			return rtrim( $srcset, ', ' );
		}

		/**
		 * Helper function to test if aspect ratios for two images match.
		 *
		 * @access protected
		 *
		 * @param int $source_width  Width of the first image in pixels.
		 * @param int $source_height Height of the first image in pixels.
		 * @param int $target_width  Width of the second image in pixels.
		 * @param int $target_height Height of the second image in pixels.
		 * @return bool True if aspect ratios match within 1px. False if not.
		 */
		protected function wp_image_matches_ratio( $source_width, $source_height, $target_width, $target_height ) {
			/*
			 * To test for varying crops, we constrain the dimensions of the larger image
			 * to the dimensions of the smaller image and see if they match.
			 */
			if ( $source_width > $target_width ) {
				$constrained_size = wp_constrain_dimensions( $source_width, $source_height, $target_width );
				$expected_size = array( $target_width, $target_height );
			} else {
				$constrained_size = wp_constrain_dimensions( $target_width, $target_height, $source_width );
				$expected_size = array( $source_width, $source_height );
			}
		 
			// If the image dimensions are within 1px of the expected size, we consider it a match.
			$matched = ( abs( $constrained_size[0] - $expected_size[0] ) <= 1 && abs( $constrained_size[1] - $expected_size[1] ) <= 1 );
		 
			return $matched;
		}

		/**
		 * Creates a 'sizes' attribute value for an image.
		 *
		 * @access protected
		 *
		 * @param array|string $size          Image size to retrieve. Accepts any valid image size, or an array
		 *                                    of width and height values in pixels (in that order). Default 'medium'.
		 * @param string       $image_src     Optional. The URL to the image file. Default null.
		 * @param array        $image_meta    Optional. The image meta data as returned by 'wp_get_attachment_metadata()'.
		 *                                    Default null.
		 * @param int          $attachment_id Optional. Image attachment ID. Either `$image_meta` or `$attachment_id`
		 *                                    is needed when using the image size name as argument for `$size`. Default 0.
		 * @return string|bool A valid source size value for use in a 'sizes' attribute or false.
		 */
		protected function wp_calculate_image_sizes( $size, $image_src = null, $image_meta = null, $attachment_id = 0 ) {
			$width = 0;

			if ( is_array( $size ) ) {
				$width = absint( $size[0] );
			} elseif ( is_string( $size ) ) {
				if ( ! $image_meta && $attachment_id ) {
					$image_meta = wp_get_attachment_metadata( $attachment_id );
				}

				if ( is_array( $image_meta ) ) {
					$size_array = _wp_get_image_size_from_meta( $size, $image_meta );
					if ( $size_array ) {
						$width = absint( $size_array[0] );
					}
				}
			}

			if ( ! $width ) {
				return false;
			}

			// Setup the default 'sizes' attribute.
			$sizes = sprintf( '(max-width: %1$dpx) 100vw, %1$dpx', $width );

			/**
			 * Filters the output of 'wp_calculate_image_sizes()'.
			 *
			 * @since 4.4.0
			 *
			 * @param string       $sizes         A source size value for use in a 'sizes' attribute.
			 * @param array|string $size          Requested size. Image size or array of width and height values
			 *                                    in pixels (in that order).
			 * @param string|null  $image_src     The URL to the image file or null.
			 * @param array|null   $image_meta    The image meta data as returned by wp_get_attachment_metadata() or null.
			 * @param int          $attachment_id Image attachment ID of the original image or 0.
			 */
			return apply_filters( 'wp_calculate_image_sizes', $sizes, $size, $image_src, $image_meta, $attachment_id );
		}

		/**
		 * Get the attachment path relative to the upload directory.
		 *
		 * @access protected
		 *
		 * @param string $file Attachment file name.
		 * @return string Attachment path relative to the upload directory.
		 */
		protected function _wp_get_attachment_relative_path( $file ) {
			$dirname = dirname( $file );
		 
			if ( '.' === $dirname ) {
				return '';
			}
		 
			if ( false !== strpos( $dirname, 'wp-content/uploads' ) ) {
				// Get the directory name relative to the upload directory (back compat for pre-2.7 uploads)
				$dirname = substr( $dirname, strpos( $dirname, 'wp-content/uploads' ) + 18 );
				$dirname = ltrim( $dirname, '/' );
			}
		 
			return $dirname;
		}

		/**
		 * Replace images with placeholders in the content
		 *
		 * @param string $content The HTML to do the filtering on
		 * @return string The HTML with the images replaced
		 */
		public static function filter_images( $content, $options = array() ) {
			$placeholder_url = 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==';

			$matches = array();
			preg_match_all( '/<img[\s\r\n]+.*?>/is', $content, $matches );

			$search = array();
			$replace = array();

			foreach ( $matches[0] as $imgHTML ) {

				// don't to the replacement if the image is a data-uri
				if ( ! preg_match( "/src=['\"]data:image/is", $imgHTML ) ) {

					$placeholder_url_used = $placeholder_url;
					// use low res preview image as placeholder if applicable
					if ( isset( $options['preview'] ) && 'yes' == $options['preview'] ) {
						if( preg_match( '/class=["\'].*?wp-image-([0-9]*)/is', $imgHTML, $id_matches ) ) {
							$img_id = intval($id_matches[1]);
							$tiny_img_data  = wp_get_attachment_image_src( $img_id, 'tiny-lazy' );
							$tiny_url = $tiny_img_data[0];
							$placeholder_url_used = $tiny_url;
						}
					}

					// replace the src and add the data-src attribute
					$replaceHTML = preg_replace( '/<img(.*?)src=/is', '<img$1src="' . esc_attr( $placeholder_url_used ) . '" data-src=', $imgHTML );

					// also replace the srcset (responsive images)
					$replaceHTML = str_replace( 'srcset', 'data-srcset', $replaceHTML );

					// add the lazy class to the img element
					if ( preg_match( '/class=["\']/i', $replaceHTML ) ) {
						$replaceHTML = preg_replace( '/class=(["\'])(.*?)["\']/is', 'class=$1$2 lazy lazy-hidden$1', $replaceHTML );
					} else {
						$replaceHTML = preg_replace( '/<img/is', '<img class="lazy lazy-hidden"', $replaceHTML );
					}

					$replaceHTML .= '<noscript>' . $imgHTML . '</noscript>';

					array_push( $search, $imgHTML );
					array_push( $replace, $replaceHTML );
				}
			}

			$content = str_replace( $search, $replace, $content );

			return $content;
		}

		/**
		 * Create dom elements with given array.
		 *
		 * @param  array   $elements Elements array.
		 * @return string
		 */
		public function create_elements( $elements, $domInstance = null, $elInstance = null ) {
			$dom = !$domInstance ? new DOMDocument('1.0') : $domInstance;

			foreach ( $elements as $element ) {
				if ( is_string( $element ) ) {
					$textNode = $dom->createTextNode( $element );
					if ( $elInstance )
						$elInstance->appendChild( $textNode );
					else
						$dom->appendChild( $textNode );
				} else {
					$elementDom = $dom->createElement( $element['tag'], '' );
					$attributes = isset( $element['attributes'] ) ? $element['attributes'] : array();

					foreach ( $attributes as $key => $value ) {
						$elementDom->setAttribute( $key, is_array( $value ) ? implode( ' ', $value ) : $value );
					}

					if ( isset( $element['nodes'] ) && !empty( $element['nodes'] ) ) {
						$this->create_elements( $element['nodes'], $dom, $elementDom );
					}

					if ( $elInstance )
						$elInstance->appendChild( $elementDom );
					else
						$dom->appendChild( $elementDom );
				}
			}

			return $dom->saveHTML();
		}



		public function get_xmp_raw( $filepath ) {

			$max_size = 1024000;
			$chunk_size = 65536;
			$start_tag = '<x:xmpmeta';
			$end_tag = '</x:xmpmeta>';
			$xmp_raw = null; 

			if ( $file_fh = fopen( $filepath, 'rb' ) ) {

				$chunk = '';
				$file_size = filesize( $filepath );

				while ( ( $file_pos = ftell( $file_fh ) ) < $file_size  && $file_pos < $max_size ) {

					$chunk .= fread( $file_fh, $chunk_size );

					if ( ( $end_pos = strpos( $chunk, $end_tag ) ) !== false ) {

						if ( ( $start_pos = strpos( $chunk, $start_tag ) ) !== false ) {

							$xmp_raw = substr( $chunk, $start_pos, 
								$end_pos - $start_pos + strlen( $end_tag ) );
						}
						break;	// stop reading after finding the xmp data
					}
				}
				fclose( $file_fh );
			}

			return $xmp_raw;
		}

		public function get_xmp_array( $xmp_raw ) {
			$xmp_arr = array();
			foreach ( array(
				'Creator Email'		=> '<Iptc4xmpCore:CreatorContactInfo[^>]+?CiEmailWork="([^"]*)"',
				'Owner Name'		=> '<rdf:Description[^>]+?aux:OwnerName="([^"]*)"',
				'Creation Date'		=> '<rdf:Description[^>]+?xmp:CreateDate="([^"]*)"',
				'Modification Date'	=> '<rdf:Description[^>]+?xmp:ModifyDate="([^"]*)"',
				'Label'			=> '<rdf:Description[^>]+?xmp:Label="([^"]*)"',
				'Credit'		=> '<rdf:Description[^>]+?photoshop:Credit="([^"]*)"',
				'Source'		=> '<rdf:Description[^>]+?photoshop:Source="([^"]*)"',
				'Headline'		=> '<rdf:Description[^>]+?photoshop:Headline="([^"]*)"',
				'City'			=> '<rdf:Description[^>]+?photoshop:City="([^"]*)"',
				'State'			=> '<rdf:Description[^>]+?photoshop:State="([^"]*)"',
				'Country'		=> '<rdf:Description[^>]+?photoshop:Country="([^"]*)"',
				'Country Code'		=> '<rdf:Description[^>]+?Iptc4xmpCore:CountryCode="([^"]*)"',
				'Location'		=> '<rdf:Description[^>]+?Iptc4xmpCore:Location="([^"]*)"',
				'Title'			=> '<dc:title>\s*<rdf:Alt>\s*(.*?)\s*<\/rdf:Alt>\s*<\/dc:title>',
				'Description'		=> '<dc:description>\s*<rdf:Alt>\s*(.*?)\s*<\/rdf:Alt>\s*<\/dc:description>',
				'Creator'		=> '<dc:creator>\s*<rdf:Seq>\s*(.*?)\s*<\/rdf:Seq>\s*<\/dc:creator>',
				'Keywords'		=> '<dc:subject>\s*<rdf:Bag>\s*(.*?)\s*<\/rdf:Bag>\s*<\/dc:subject>',
				'Hierarchical Keywords'	=> '<lr:hierarchicalSubject>\s*<rdf:Bag>\s*(.*?)\s*<\/rdf:Bag>\s*<\/lr:hierarchicalSubject>'
			) as $key => $regex ) {

				// get a single text string
				$xmp_arr[$key] = preg_match( "/$regex/is", $xmp_raw, $match ) ? $match[1] : '';

				// if string contains a list, then re-assign the variable as an array with the list elements
				$xmp_arr[$key] = preg_match_all( "/<rdf:li[^>]*>([^>]*)<\/rdf:li>/is", $xmp_arr[$key], $match ) ? $match[1] : $xmp_arr[$key];

				// hierarchical keywords need to be split into a third dimension
				if ( ! empty( $xmp_arr[$key] ) && $key == 'Hierarchical Keywords' ) {
					foreach ( $xmp_arr[$key] as $li => $val ) $xmp_arr[$key][$li] = explode( '|', $val );
					unset ( $li, $val );
				}
			}
			return $xmp_arr;
		}

		protected function array_whitelist_filter( $array = array(), $allowed = array() ) {
			return array_intersect_key( $array, array_flip( $allowed ) );
		}

		protected function create_dropdown( $args = array() ) {
			$defaults = array(
				'options'       => array(),
				'selected'      => '',
				'name'          => '',
				'id'            => '',
				'class'         => '',
				'echo'          => true,
				'required'      => false,
				'multiple'      => false,
				'hide_if_empty' => false
			);

			$r = wp_parse_args( $args, $defaults );

			$name = esc_attr( $r['name'] );
			$class = esc_attr( $r['class'] );
			$id = $r['id'] ? esc_attr( $r['id'] ) : $name;
			$required = $r['required'] ? ' required' : '';
			$multiple = $r['multiple'] ? ' multiple' : '';

			if ( ! $r['hide_if_empty'] || ! empty( $r['options'] ) ) {
				$output = "<select name='$name' id='$id' class='{$class}'{$required}{$multiple}>\n";
			} else {
				$output = '';
			}

			if ( ! empty( $r['options'] ) ) {
				foreach ( $r['options'] as $key => $value ) {
					if ( is_array( $value ) ) {
						$show_optgroup = "\t<optgroup label='" . esc_attr( $key ) . "'>";
						foreach ( $value as $optkey => $optvalue ) {
							if ( is_array( $r['selected'] ) ) {
								$selected = selected( in_array( $optkey, $r['selected'] ), true, false );
							} else {
								$selected = selected( $optkey, $r['selected'], false );
							}
							$show_option = "\t<option value='" . esc_attr( $optkey ) . "'{$selected}>{$optvalue}</option>\n";
							$show_optgroup .= apply_filters( 'wgextra_dropdown_option_' . $name, $show_option );
						}
						$show_optgroup .= "</optgroup>\n";
						$output .= apply_filters( 'wgextra_dropdown_optgroup_' . $name, $show_optgroup );
					} else {
						if ( is_array( $r['selected'] ) ) {
							$selected = selected( in_array( $key, $r['selected'] ), true, false );
						} else {
							$selected = selected( $key, $r['selected'], false );
						}
						$show_option = "\t<option value='" . esc_attr( $key ) . "'$selected>$value</option>\n";
						$output .= apply_filters( 'wgextra_dropdown_option_' . $name, $show_option );
					}
				}
			}

			if ( ! $r['hide_if_empty'] || ! empty( $r['options'] ) ) {
				$output .= "</select>\n";
			}

			/**
			 * Filters drop-down output.
			 *
			 * @param string $output HTML output.
			 * @param array  $r      Arguments used to build the drop-down.
			 */
			$output = apply_filters( 'wgextra_dropdown_' . $name, $output, $r );

			if ( $r['echo'] ) {
				echo $output;
			}
			return $output;
		}

		public function less2css( $input, $variables ) {
			if ( empty( $input ) ) {
				return "";
			}

			if ( !class_exists( 'lessc' ) ) {
				require_once "includes/lessc.inc.php";
			}

			$output = "";

			try {
				$less = new lessc;
				$less->addImportDir( path_join( $this->PATH, "assets/less" ) );
				$less->setVariables( $variables );
				$less->setFormatter( "compressed" );

				$output = $less->compile( $input );
			} catch ( Exception $e ) {}

			return $output;
		}

		/**
		 * Quick and dirty way to mostly minify CSS.
		 *
		 * @param string $css CSS to minify
		 * @return string Minified CSS
		 */
		protected function minifyCSS( $css ) {
			// Normalize whitespace
			$css = preg_replace( '/\s+/', ' ', $css );

			// Remove spaces before and after comment
			$css = preg_replace( '/(\s+)(\/\*(.*?)\*\/)(\s+)/', '$2', $css );
			// Remove comment blocks, everything between /* and */, unless
			// preserved with /*! ... */ or /** ... */
			$css = preg_replace( '~/\*(?![\!|\*])(.*?)\*/~', '', $css );
			// Remove ; before }
			$css = preg_replace( '/;(?=\s*})/', '', $css );
			// Remove space after , : ; { } */ >
			$css = preg_replace( '/(,|:|;|\{|}|\*\/|>) /', '$1', $css );
			// Remove space before , ; { } ( ) >
			$css = preg_replace( '/ (,|;|\{|}|\(|\)|>)/', '$1', $css );
			// Strips leading 0 on decimal values (converts 0.5px into .5px)
			$css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );
			// Strips units if value is 0 (converts 0px to 0)
			// $css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );
			// Converts all zeros value into short-hand
			$css = preg_replace( '/0 0 0 0/', '0', $css );
			// Shortern 6-character hex color codes to 3-character where possible
			$css = preg_replace( '/#([a-f0-9])\\1([a-f0-9])\\2([a-f0-9])\\3/i', '#\1\2\3', $css );
			return trim( $css );
		}

		/**
		 * Convert gallery shortcode attributes to options.
		 *
		 * @param  array  $attrs
		 * @param  array  $defaults
		 * @return array
		 */
		public function attributes_to_options( $attrs, $defaults, $type = 'template' ) {
			$options = array();

			if ( $type === 'template' ) {
				$options = $defaults;

				foreach ( $attrs as $key => $value ) {
					// Template Options
					if      ( $key === 'template' )                       $options['template'] = $value;
					else if ( $key === 'caption-source' )                 $options['caption_source'] = $value;
					else if ( $key === 'captions' ) {
						$options['caption_source'] = 'custom';
						$options['caption_custom'] = $value;
					}
					else if ( $key === 'loading-type' )                   $options['loading_type'] = $value;
					else if ( $key === 'lightbox' )                       $options['lightbox_type'] = $value;
					else if ( $key === 'magnific-animation' )             $options['lightbox_magnific']['animation'] = $value;
					else if ( $key === 'magnific-vertical-fit' )          $options['lightbox_magnific']['vertical_fit'] = $value;
					else if ( $key === 'magnific-preload' )               $options['lightbox_magnific']['preload'] = $value;
					else if ( $key === 'magnific-deeplink' )              $options['lightbox_magnific']['deeplink'] = $value;
					else if ( $key === 'ilightbox-skin' )                 $options['lightbox_ilightbox']['skin'] = $value;
					else if ( $key === 'ilightbox-direction' )            $options['lightbox_ilightbox']['direction'] = $value;
					else if ( $key === 'ilightbox-loop' )                 $options['lightbox_ilightbox']['loop'] = $value;
					else if ( $key === 'ilightbox-carousel-mode' )        $options['lightbox_ilightbox']['carousel_mode'] = $value;
					else if ( $key === 'ilightbox-deeplink' )             $options['lightbox_ilightbox']['deeplink'] = $value;
					else if ( $key === 'ilightbox-share-buttons' )        $options['lightbox_ilightbox']['share_buttons'] = $value;
					else if ( $key === 'ilightbox-thumbnails' )           $options['lightbox_ilightbox']['thumbnails'] = $value;
					else if ( $key === 'ilightbox-overlay-opacity' )      $options['lightbox_ilightbox']['overlay_opacity'] = $value;

					else if ( $key === 'columns' )                        $options['columns'] = $value;
					else if ( $key === 'size' )                           $options['thumbnail_size'] = $value;
					else if ( $key === 'link' )                           $options['link']['to'] = $value;
					else if ( $key === 'link-target' )                    $options['link']['target'] = $value;
					else if ( $key === 'link-url' )                       $options['link']['url'] = $value;
					else if ( $key === 'last-row' )                       $options['last_row'] = $value;
					else if ( $key === 'row-height' )                     $options['row_height'] = $value;
					else if ( $key === 'max-row-height' )                 $options['max_row_height'] = $value;
					else if ( $key === 'alignment' )                      $options['alignment'] = $value;
					else if ( $key === 'vertical-alignment' )             $options['vertical_alignment'] = $value;
					else if ( $key === 'mosaic-type' )                    $options['mosaic_type'] = $value;
					else if ( $key === 'thumbnail-ratio' )                $options['thumbnail_ratio']['type'] = $value;
					else if ( $key === 'fixed-ratio' ) {
						$options['thumbnail_ratio']['type'] = "manual";
						$options['thumbnail_ratio']['size'] = array_map( "floatval", explode( ":", trim( $value ) ) );
					}
					else if ( $key === 'fixed-ratio-force' )              $options['thumbnail_ratio']['force'] = $value;
					else if ( $key === 'class' )                          $options['custom_class'] = $value;

					// Source
					else if ( $key === 'source' )                         $options['source']['source'] = $value;
					else if ( $key === 'limit' )                          $options['source']['item_number'] = $value;
					else if ( $key === 'post-types' )                     $options['source']['post_types'] = array_keys( array_flip( explode( ',', $value ) ) );
					else if ( $key === 'post-status' )                    $options['source']['post_status'] = array_keys( array_flip( explode( ',', $value ) ) );
					else if ( $key === 'taxonomies' )                     $options['source']['taxonomies'] = array_keys( array_flip( explode( ',', $value ) ) );
					else if ( $key === 'taxonomies-relation' )            $options['source']['taxonomies_relation'] = $value;
					else if ( $key === 'authors' )                        $options['source']['authors'] = array_keys( array_flip( explode( ',', $value ) ) );
					else if ( $key === 'authors-relation' )               $options['source']['authors_relation'] = $value;
					else if ( $key === 'ids' || $key === 'include' )      $options['source']['include_posts'] = $value;
					else if ( $key === 'exclude' )                        $options['source']['exclude_posts'] = $value;
					else if ( $key === 'order' )                          $options['source']['ordering']['order'] = $value;
					else if ( $key === 'orderby' )                        $options['source']['ordering']['order_by'] = $value;
					else if ( $key === 'orderby-fallback' )               $options['source']['ordering']['order_by_fallback'] = $value;
					else if ( $key === 'order-meta-key' )                 $options['source']['ordering']['meta_key'] = $value;
					else if ( $key === 'categories' ) {
						$terms = array_keys( array_flip( explode( ',', $value ) ) );
						$taxonomies = array();

						foreach( $terms as $term ) {
							$taxonomies[] = "attachment_category:{$term}";
						}

						$options['source']['taxonomies'] = array_merge( $options['source']['taxonomies'], $taxonomies );

					}
					else if ( $key === 'tags' ) {
						$terms = array_keys( array_flip( explode( ',', $value ) ) );
						$taxonomies = array();

						foreach( $terms as $term ) {
							$taxonomies[] = "attachment_tag:{$term}";
						}

						$options['source']['taxonomies'] = array_merge( $options['source']['taxonomies'], $taxonomies );

					}

					// Allowed styles
					else if ( $key === 'border' )                         $options['styles']['has_border'] = $value;
					else if ( $key === 'shadow' )                         $options['styles']['has_shadow'] = $value;
					else if ( $key === 'icon' )                           $options['styles']['has_icon'] = $value;
					else if ( $key === 'caption' )                        $options['styles']['has_caption'] = $value;
					else if ( $key === 'overlay' )                        $options['styles']['has_overlay'] = $value;

					// Global Styles
					else if ( $key === 'margin' )                         $options['styles']['margin'] = $value;

					// Border
					else if ( $key === 'border-weight' )                  $options['styles']['border']['weight'] = $value;
					else if ( $key === 'border-color' )                   $options['styles']['border']['color'] = $value;
					else if ( $key === 'border-style' )                   $options['styles']['border']['style'] = $value;
					else if ( $key === 'border-radius' )                  $options['styles']['border']['radius'] = $value;

					// Shadow
					else if ( $key === 'shadow-x' )                       $options['styles']['shadow']['x'] = $value;
					else if ( $key === 'shadow-y' )                       $options['styles']['shadow']['y'] = $value;
					else if ( $key === 'shadow-blur' )                    $options['styles']['shadow']['blur'] = $value;
					else if ( $key === 'shadow-spread' )                  $options['styles']['shadow']['spread'] = $value;
					else if ( $key === 'shadow-color' )                   $options['styles']['shadow']['color'] = $value;
					else if ( $key === 'shadow-inset' )                   $options['styles']['shadow']['inset'] = $value;

					// Placeholder
					else if ( $key === 'placeholder' )                    $options['styles']['use_placeholder'] = $value;
					else if ( $key === 'placeholder-overlay' )            $options['styles']['placeholder']['overlay'] = $value;
					else if ( $key === 'placeholder-readable-caption' )   $options['styles']['placeholder']['readable_caption'] = $value;
					else if ( $key === 'placeholder-background' )         $options['styles']['placeholder']['background'] = $value;

					// Overlay
					else if ( $key === 'overlay-background' )             $options['styles']['overlay']['background']['type'] = $value;
					else if ( $key === 'overlay-background-color' )       $options['styles']['overlay']['background']['solid']['color'] = $value;
					else if ( $key === 'overlay-background-start-color' ) $options['styles']['overlay']['background']['gradient']['start_color'] = $value;
					else if ( $key === 'overlay-background-stop-color' )  $options['styles']['overlay']['background']['gradient']['stop_color'] = $value;
					else if ( $key === 'overlay-background-orientation' ) $options['styles']['overlay']['background']['gradient']['orientation'] = $value;
					else if ( $key === 'overlay-visibility' )             $options['styles']['overlay']['visibility'] = $value;
					else if ( $key === 'overlay-transition-speed' )       $options['styles']['overlay']['transition']['speed'] = $value;
					else if ( $key === 'overlay-transition-easing' )      $options['styles']['overlay']['transition']['easing'] = $value;
					else if ( $key === 'overlay-transition-delay' )       $options['styles']['overlay']['transition']['delay'] = $value;

					// Caption
					else if ( $key === 'caption-color' )                  $options['styles']['caption']['color'] = $value;
					else if ( $key === 'caption-position' )               $options['styles']['caption']['position'] = $value;
					else if ( $key === 'caption-background' )             $options['styles']['caption']['background']['type'] = $value;
					else if ( $key === 'caption-background-color' )       $options['styles']['caption']['background']['solid']['color'] = $value;
					else if ( $key === 'caption-background-start-color' ) $options['styles']['caption']['background']['gradient']['start_color'] = $value;
					else if ( $key === 'caption-background-stop-color' )  $options['styles']['caption']['background']['gradient']['stop_color'] = $value;
					else if ( $key === 'caption-background-orientation' ) $options['styles']['caption']['background']['gradient']['orientation'] = $value;
					else if ( $key === 'caption-inset' )                  $options['styles']['caption']['inset'] = $value;
					else if ( $key === 'caption-visibility' )             $options['styles']['caption']['visibility'] = $value;
					else if ( $key === 'caption-transition-speed' )       $options['styles']['caption']['transition']['speed'] = $value;
					else if ( $key === 'caption-transition-easing' )      $options['styles']['caption']['transition']['easing'] = $value;
					else if ( $key === 'caption-transition-delay' )       $options['styles']['caption']['transition']['delay'] = $value;

					// Icon
					else if ( $key === 'icon-name' )                      $options['styles']['icon']['icon'] = $value;
					else if ( $key === 'icon-color' )                     $options['styles']['icon']['color'] = $value;
					else if ( $key === 'icon-size' )                      $options['styles']['icon']['size'] = $value;
					else if ( $key === 'icon-visibility' )                $options['styles']['icon']['visibility'] = $value;
					else if ( $key === 'icon-transition-speed' )          $options['styles']['icon']['transition']['speed'] = $value;
					else if ( $key === 'icon-transition-easing' )         $options['styles']['icon']['transition']['easing'] = $value;
					else if ( $key === 'icon-transition-delay' )          $options['styles']['icon']['transition']['delay'] = $value;
				}

				$options = self::array_diff( $defaults, $options );
			}

			return $options;
		}

		/**
		 * Convert options to gallery shortcode attributes.
		 *
		 * @param  array  $options
		 * @return array
		 */
		public function options_to_attributes( $options, $type = 'template' ) {
			$attrs = array();

			if ( $type === 'template' ) {
				$options = array_replace_recursive( $this->DEFAULT_TEMPLATE_OPTIONS, $options );

				// Template Options
				$attrs['template']                    = $options['template'];
				$attrs['caption-source']              = $options['caption_source'];
				$attrs['loading-type']                = $options['loading_type'];
				$attrs['lightbox']                    = $options['lightbox_type'];
				$attrs['magnific-animation']          = $options['lightbox_magnific']['animation'];
				$attrs['magnific-vertical-fit']       = $options['lightbox_magnific']['vertical_fit'];
				$attrs['magnific-preload']            = $options['lightbox_magnific']['preload'];
				$attrs['magnific-deeplink']           = $options['lightbox_magnific']['deeplink'];
				$attrs['ilightbox-skin']              = $options['lightbox_ilightbox']['skin'];
				$attrs['ilightbox-direction']         = $options['lightbox_ilightbox']['direction'];
				$attrs['ilightbox-loop']              = $options['lightbox_ilightbox']['loop'];
				$attrs['ilightbox-carousel-mode']     = $options['lightbox_ilightbox']['carousel_mode'];
				$attrs['ilightbox-deeplink']          = $options['lightbox_ilightbox']['deeplink'];
				$attrs['ilightbox-share-buttons']     = $options['lightbox_ilightbox']['share_buttons'];
				$attrs['ilightbox-thumbnails']        = $options['lightbox_ilightbox']['thumbnails'];
				$attrs['ilightbox-overlay_opacity']   = $options['lightbox_ilightbox']['overlay_opacity'];

				$attrs['columns']                     = $options['columns'];
				$attrs['class']                       = $options['custom_class'];
				$attrs['size']                        = $options['thumbnail_size'];
				$attrs['link']                        = $options['link']['to'];
				$attrs['link-target']                 = $options['link']['target'];
				$attrs['link-url']                    = $options['link']['url'];
				$attrs['last-row']                    = $options['last_row'];
				$attrs['row-height']                  = $options['row_height'];
				$attrs['max-row-height']              = $options['max_row_height'];
				$attrs['alignment']                   = $options['alignment'];
				$attrs['vertical-alignment']          = $options['vertical_alignment'];
				$attrs['mosaic-type']                 = $options['mosaic_type'];
				$attrs['thumbnail-ratio']             = $options['thumbnail_ratio']['type'];
				$attrs['fixed-ratio']                 = implode( ':', $options['thumbnail_ratio']['size'] );
				$attrs['fixed-ratio-force']           = $options['thumbnail_ratio']['force'];

				// Source
				$attrs['source']                      = $options['source']['source'];
				$attrs['limit']                       = $options['source']['item_number'];
				$attrs['post-types']                  = $options['source']['post_types'];
				$attrs['post-status']                 = $options['source']['post_status'];
				$attrs['taxonomies']                  = $options['source']['taxonomies'];
				$attrs['taxonomies-relation']         = $options['source']['taxonomies_relation'];
				$attrs['authors']                     = $options['source']['authors'];
				$attrs['authors-relation']            = $options['source']['authors_relation'];
				$attrs['ids']                         = $options['source']['include_posts'];
				$attrs['exclude']                     = $options['source']['exclude_posts'];
				$attrs['order']                       = $options['source']['ordering']['order'];
				$attrs['orderby']                     = $options['source']['ordering']['order_by'];
				$attrs['orderby-fallback']            = $options['source']['ordering']['order_by_fallback'];
				$attrs['order-meta-key']              = $options['source']['ordering']['meta_key'];

				// Allowed styles
				$attrs['border']                      = $options['styles']['has_border'];
				$attrs['shadow']                      = $options['styles']['has_shadow'];
				$attrs['icon']                        = $options['styles']['has_icon'];
				$attrs['caption']                     = $options['styles']['has_caption'];
				$attrs['overlay']                     = $options['styles']['has_overlay'];

				// Global Styles
				$attrs['margin']                      = $options['styles']['margin'];

				// Border
				$attrs['border-weight']               = $options['styles']['border']['weight'];
				$attrs['border-color']                = $options['styles']['border']['color'];
				$attrs['border-style']                = $options['styles']['border']['style'];
				$attrs['border-radius']               = $options['styles']['border']['radius'];

				// Shadow
				$attrs['shadow-x']                    = $options['styles']['shadow']['x'];
				$attrs['shadow-y']                    = $options['styles']['shadow']['y'];
				$attrs['shadow-blur']                 = $options['styles']['shadow']['blur'];
				$attrs['shadow-spread']               = $options['styles']['shadow']['spread'];
				$attrs['shadow-color']                = $options['styles']['shadow']['color'];
				$attrs['shadow-inset']                = $options['styles']['shadow']['inset'];

				// Placeholder
				$attrs['placeholder']                 = $options['styles']['use_placeholder'];
				$attrs['placeholder-overlay']         = $options['styles']['placeholder']['overlay'];
				$attrs['placeholder-readable-caption'] = $options['styles']['placeholder']['readable_caption'];
				$attrs['placeholder-background']      = $options['styles']['placeholder']['background'];

				// Overlay
				$attrs['overlay-background']          = $options['styles']['overlay']['background']['type'];
				$attrs['overlay-background-color']    = $options['styles']['overlay']['background']['solid']['color'];
				$attrs['overlay-background-start-color'] = $options['styles']['overlay']['background']['gradient']['start_color'];
				$attrs['overlay-background-stop-color']  = $options['styles']['overlay']['background']['gradient']['stop_color'];
				$attrs['overlay-background-orientation'] = $options['styles']['overlay']['background']['gradient']['orientation'];
				$attrs['overlay-visibility']          = $options['styles']['overlay']['visibility'];
				$attrs['overlay-transition-speed']    = $options['styles']['overlay']['transition']['speed'];
				$attrs['overlay-transition-easing']   = $options['styles']['overlay']['transition']['easing'];
				$attrs['overlay-transition-delay']    = $options['styles']['overlay']['transition']['delay'];

				// Caption
				$attrs['caption-color']               = $options['styles']['caption']['color'];
				$attrs['caption-position']            = $options['styles']['caption']['position'];
				$attrs['caption-background']          = $options['styles']['caption']['background']['type'];
				$attrs['caption-background-color']       = $options['styles']['caption']['background']['solid']['color'];
				$attrs['caption-background-start-color'] = $options['styles']['caption']['background']['gradient']['start_color'];
				$attrs['caption-background-stop-color']  = $options['styles']['caption']['background']['gradient']['stop_color'];
				$attrs['caption-background-orientation'] = $options['styles']['caption']['background']['gradient']['orientation'];
				$attrs['caption-inset']               = $options['styles']['caption']['inset'];
				$attrs['caption-visibility']          = $options['styles']['caption']['visibility'];
				$attrs['caption-transition-speed']    = $options['styles']['caption']['transition']['speed'];
				$attrs['caption-transition-easing']   = $options['styles']['caption']['transition']['easing'];
				$attrs['caption-transition-delay']    = $options['styles']['caption']['transition']['delay'];

				// Icon
				$attrs['icon-name']                   = $options['styles']['icon']['icon'];
				$attrs['icon-color']                  = $options['styles']['icon']['color'];
				$attrs['icon-size']                   = $options['styles']['icon']['size'];
				$attrs['icon-visibility']             = $options['styles']['icon']['visibility'];
				$attrs['icon-transition-speed']       = $options['styles']['icon']['transition']['speed'];
				$attrs['icon-transition-easing']       = $options['styles']['icon']['transition']['easing'];
				$attrs['icon-transition-delay']       = $options['styles']['icon']['transition']['delay'];
			}

			return $attrs;
		}

		/**
		 * Computes the difference of arrays
		 *
		 * @param  array $array1
		 * @param  array $array2
		 * @return array
		 */
		public static function array_diff( $array1, $array2 ) {
			$diff = array();
			
			// Check the similarities
			foreach( $array1 as $k1 => $v1 ) {
				if ( isset( $array2[$k1] ) ) {
					$v2 = $array2[$k1];
					if ( is_array( $v1 ) && is_array( $v2 ) ){
						// 2 arrays: just go further...
						// .. and explain it's an update!
						$changes = self::array_diff( $v1, $v2 );
						if ( count( $changes ) > 0 ){
							// If we have no change, simply ignore
							$diff[$k1] = $changes;
						}
						unset( $array2[$k1] ); // don't forget
					}
					else if ( $v2 === $v1 ){
						// unset the value on the second array
						// for the "surplus"
						unset( $array2[$k1] );
					}
					else {
						// Don't mind if arrays or not.
						$diff[$k1] = $v2;
						unset( $array2[$k1] );
					}
				}
				else {
					// remove information
					$diff[$k1] = $v1; 
				}
			}

			reset( $array2 );
			foreach ( $array2 as $key => $value ){
				$diff[$key] = $value;
			}
			return $diff;
		}

		/**
		 * Check if an number is within a range of numbers.
		 *
		 * @param  number $number
		 * @param  number $min
		 * @param  number $max
		 * @return number
		 */
		public static function within( $number, $min, $max ) {
			return $number < $min ? $min : $number > $max ? $max : $number;
		}

		/**
		 * Add a new tabs to a settings page.
		 *
		 * Use this to define new settings tabs for settings pages.
		 * Add settings sections to your tab with add_settings_section()
		 *
		 * The $callback argument should be the name of a function that echoes out any
		 * content you want to show at the top of the settings tabs before the actual
		 * sections. It can output nothing if you want.
		 *
		 * @param string   $id       Slug-name to identify the tab. Used in the 'id' attribute of tags.
		 * @param string   $title    Formatted title of the tab. Shown as the heading for the tab.
		 * @param string   $description Formatted description of the tab. Shown as the heading for the tab.
		 * @param callable $callback Function that echos out any content at the top of the tab (between heading and fields).
		 * @param string   $page     The slug-name of the settings page on which to show the tab. Built-in pages include
		 *                           'templates', 'sources', 'settings', 'images-sizes', etc.
		 */
		public function add_settings_tabs( $id, $title, $description, $callback, $page ) {
			$this->SETTINGS_TABS[$page][$id] = array( 'id' => $id, 'title' => $title, 'description' => $description, 'callback' => $callback );
		}

		/**
		 * Add a new section to a settings tab.
		 *
		 * Use this to define new settings sections for settings tab.
		 * Add settings fields to your section with add_settings_field()
		 *
		 * The $callback argument should be the name of a function that echoes out any
		 * content you want to show at the top of the settings section before the actual
		 * fields. It can output nothing if you want.
		 *
		 * @param string   $id       Slug-name to identify the section. Used in the 'id' attribute of tags.
		 * @param string   $title    Formatted title of the section. Shown as the heading for the section.
		 * @param callable $callback Function that echos out any content at the top of the section (between heading and fields).
		 * @param string   $tab      The slug-name of the settings tab on which to show the section. Create your own using add_settings_tabs();
		 * @param string   $page     The slug-name of the settings page on which to show the tab.
		 */
		public function add_settings_section( $id, $title, $callback, $tab, $page ) {
			$this->SETTINGS_SECTIONS[$page][$tab][$id] = array( 'id' => $id, 'title' => $title, 'callback' => $callback );
		}

		/**
		 * Add a new field to a section of a settings tab
		 *
		 * Use this to define a settings field that will show
		 * as part of a settings section inside a settings tab. The fields are shown using
		 * do_settings_fields() in do_settings-sections()
		 *
		 * The $callback argument should be the name of a function that echoes out the
		 * html input tags for this setting field. Use get_option() to retrieve existing
		 * values to show.
		 *
		 * @param string   $id       Slug-name to identify the field. Used in the 'id' attribute of tags.
		 * @param string   $title    Formatted title of the field. Shown as the label for the field
		 *                           during output.
		 * @param callable $callback Function that fills the field with the desired form inputs. The
		 *                           function should echo its output.
		 * @param string   $tab      The slug-name of the settings tab on which to show the section
		 *                           (general, reading, writing, ...).
		 * @param string   $section  Optional. The slug-name of the section of the settings tab
		 *                           in which to show the box. Default 'default'.
		 * @param array    $args {
		 *     Optional. Extra arguments used when outputting the field.
		 *
		 *     @type string $label_for When supplied, the setting title will be wrapped
		 *                             in a `<label>` element, its `for` attribute populated
		 *                             with this value.
		 *     @type string $class     CSS Class to be added to the `<tr>` element when the
		 *                             field is output.
		 * }
		 */
		public function add_settings_field( $id, $title, $callback, $page, $tab, $section = 'default', $args = array() ) {
			$this->SETTINGS_FIELDS[$page][$tab][$section][$id] = array( 'id' => $id, 'title' => $title, 'callback' => $callback, 'args' => $args );
		}

		/**
		 * Prints out all settings sections added to a particular settings tab
		 *
		 * Use this in a settings tab callback function
		 * to output all the sections and fields that were added to that $tab with
		 * add_settings_section() and add_settings_field()
		 *
		 * @param string $tab The slug name of the tab whose settings sections you want to output
		 * @param string $page Slug title of the settings page who's sections you want to show.
		 */
		public function do_settings_tabs( $page ) {
			if ( ! isset( $this->SETTINGS_TABS[$page] ) )
				return;

			echo "<hr>";

			foreach ( (array) $this->SETTINGS_TABS[$page] as $tab ) {
				if ( $tab['callback'] )
					call_user_func( $tab['callback'], $tab );

				if ( !isset( $this->SETTINGS_SECTIONS[$page] ) || !isset( $this->SETTINGS_SECTIONS[$page][$tab['id']] ) )
					continue;
				echo '<div class="inside" id="wgextra-' . $tab['id'] . '">';
				if ( $tab['description'] )
					echo "<p>{$tab['description']}</p>\n";
				$this->do_settings_sections( $page, $tab['id'] );
				echo '</div>';
			}
		}

		/**
		 * Prints out all settings sections added to a particular settings tab
		 *
		 * Use this in a settings tab callback function
		 * to output all the sections and fields that were added to that $tab with
		 * add_settings_section() and add_settings_field()
		 *
		 * @param string $tab The slug name of the tab whose settings sections you want to output
		 * @param string $page Slug title of the settings page who's sections you want to show.
		 */
		public function do_settings_sections( $page, $tab ) {
			if ( ! isset( $this->SETTINGS_SECTIONS[$page][$tab] ) )
				return;

			echo "<hr>";

			foreach ( (array) $this->SETTINGS_SECTIONS[$page][$tab] as $section ) {
				if ( $section['title'] )
					echo "<h2>{$section['title']}</h2>\n";

				if ( $section['callback'] )
					call_user_func( $section['callback'], $section );

				if ( !isset( $this->SETTINGS_FIELDS[$tab] ) || !isset( $this->SETTINGS_FIELDS[$tab][$section['id']] ) )
					continue;
				echo '<table class="form-table">';
				$this->do_settings_fields( $page, $tab, $section['id'] );
				echo '</table>';
			}
		}

		/**
		 * Print out the settings fields for a particular settings section
		 *
		 * Part of the Settings API. Use this in a settings tab to output
		 * a specific section. Should normally be called by do_settings_sections()
		 * rather than directly.
		 *
		 * @global $wp_settings_fields Storage array of settings fields and their pages/sections
		 *
		 * @param string $page Slug title of the admin page who's settings tabs you want to show.
		 * @param string $tab Slug title of the admin tab who's settings sections you want to show.
		 * @param string $section Slug title of the settings section who's fields you want to show.
		 */
		public function do_settings_fields( $page, $tab, $section ) {
			if ( ! isset( $this->SETTINGS_FIELDS[$page][$tab][$section] ) )
				return;

			foreach ( (array) $this->SETTINGS_FIELDS[$page][$tab][$section] as $field ) {
				$classes = ['ad_opt'];

				if ( ! empty( $field['args']['class'] ) ) {
					$classes = array_merge( $classes, explode( $field['args']['class'] ) );
				}

				$classes = array_keys( array_flip( $classes ) );

				echo '<tr class="' . esc_attr( implode( ' ', $classes ) ) . '">';

				if ( ! empty( $field['args']['label_for'] ) ) {
					echo '<th scope="row"><label for="' . esc_attr( $field['args']['label_for'] ) . '">' . $field['title'] . '</label></th>';
				} else {
					echo '<th scope="row">' . $field['title'] . '</th>';
				}

				echo '<td>';
				call_user_func( $field['callback'], $field['args'] );
				echo '</td>';
				echo '</tr>';
			}
		}

		/**
		* Get Public Post Types
		*/
		public static function get_public_post_types() {
			$post_types = array();
			$public_post_types = get_post_types( array( 'public' => true ), 'objects' );

			foreach( $public_post_types as $key => $type ) {
				$post_types[$key] = $type->labels->name;
			}

			return $post_types;
		}

		/**
		* Get Public Post Types Meta Data
		*/
		public static function get_all_meta( $type ) {
			global $wpdb;
			$result = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT meta_key, meta_value FROM {$wpdb->posts}, {$wpdb->postmeta} WHERE post_type = %s AND {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id GROUP BY {$wpdb->postmeta}.meta_key",
					$type
				),
				ARRAY_A
			);
			return $result;
		}

		/**
		* Get Post Meta Data
		*/
		public static function get_post_meta( $id ) {
			$post_metadata = get_post_meta( $id );
			$metadata = array();

			foreach( $post_metadata as $meta_key => $meta_value ) {
				if ( is_array( $meta_value ) ) 
					$metadata[$meta_key] = maybe_unserialize( $meta_value[0] );
				else
					$metadata[$meta_key] = $meta_value;
			}

			return $metadata;
		}

		/**
		* Get authors
		*/
		public static function get_all_authors() {
			$authors = get_users( array(
				'orderby' => 'post_count',
				'order'   => 'DESC',
				'fields'  => array( 'ID', 'display_name' ),
				'who'     => 'authors'
			) );

			if ( $authors ) {
				$array = array();
				foreach( $authors as $author ) {
					$array[$author->ID] = $author->display_name;
				}
				return $array;
			}
		}

		/**
		* Do shortcode with blank HTML
		*/
		protected function do_shortcode( $shortcode ) {
			ob_start();
			$includes_url = includes_url();
			$custom_css_file = path_join( $this->PATH, 'assets/css/custom.css' );
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="<?php echo plugins_url( 'assets/css/front.css', __FILE__ ); ?>">
		<link rel="stylesheet" href="<?php echo plugins_url( 'assets/css/custom.css?fm=' . filemtime( $custom_css_file ), __FILE__ ); ?>">
		<style>
			/* http://meyerweb.com/eric/tools/css/reset/ 
			   v2.0 | 20110126
			   License: none (public domain)
			*/

			html, body, div, span, applet, object, iframe,
			h1, h2, h3, h4, h5, h6, p, blockquote, pre,
			a, abbr, acronym, address, big, cite, code,
			del, dfn, em, img, ins, kbd, q, s, samp,
			small, strike, strong, sub, sup, tt, var,
			b, u, i, center,
			dl, dt, dd, ol, ul, li,
			fieldset, form, label, legend,
			table, caption, tbody, tfoot, thead, tr, th, td,
			article, aside, canvas, details, embed, 
			figure, figcaption, footer, header, hgroup, 
			menu, nav, output, ruby, section, summary,
			time, mark, audio, video {
				margin: 0;
				padding: 0;
				border: 0;
				font-size: 100%;
				font: inherit;
				vertical-align: baseline;
			}
			/* HTML5 display-role reset for older browsers */
			article, aside, details, figcaption, figure, 
			footer, header, hgroup, menu, nav, section {
				display: block;
			}
			body {
				line-height: 1;
			}
			ol, ul {
				list-style: none;
			}
			blockquote, q {
				quotes: none;
			}
			blockquote:before, blockquote:after,
			q:before, q:after {
				content: '';
				content: none;
			}
			table {
				border-collapse: collapse;
				border-spacing: 0;
			}
		</style>
	</head>
	<body>
<?php
	echo do_shortcode( $shortcode );
?>
		<script src="<?php echo plugins_url( 'assets/js/plugins.js', __FILE__ ); ?>" type="text/javascript" charset="utf-8"></script>
		<script src="<?php echo plugins_url( 'assets/js/front.js', __FILE__ ); ?>" type="text/javascript" charset="utf-8"></script>
	</body>
</html>
<?php
			$return = ob_get_contents();
			ob_end_clean();

			return $return;
		}
	}

	require_once "includes/methods.php";

	// Run WordPress_Gallery_Extra
	$WordPress_Gallery_Extra = new WGExtra( __FILE__ );

	add_action( 'plugins_loaded', array( $WordPress_Gallery_Extra, 'plugins_loaded' ) );
}
