<?php

/**
 * Class iProDev_Shortcode_WGExtra
 * handles the creation of [wgextra] shortcode
 * adds a button in MCE editor allowing easy creation of shortcode
 * creates a wordpress view representing this shortcode in the editor
 * edit/delete button on wp view as well makes for easy shortcode managements.
 *
 * separate css is in style.content.css - this is loaded in frontend and also backend with add_editor_style
 *
 * Author: iprodev@gmail.com
 * Copyright 2017
 */

class iProDev_Shortcode_WGExtra extends WGExtra {
	public function __construct() {
		// comment this 'add_action' out to disable shortcode backend mce view feature
		add_action( 'admin_init', array( $this, 'init_plugin' ), 20 );

		return $this;
	}
	
	public function init_plugin() {
		add_action( 'print_media_templates', array( $this, 'print_media_templates' ) );
		add_action( 'admin_head', array( $this, 'admin_head' ) );
		add_action( 'admin_footer', array( $this, 'admin_footer' ) );
		add_filter( "mce_external_plugins", array( $this, 'mce_plugin' ) );
		add_filter( "mce_buttons", array( $this, 'mce_button' ) );
		add_filter( 'media_buttons_context', array( $this, 'media_buttons_context' ) );
	}

	public function mce_plugin( $plugin_array ) {
		$plugin_array['wgextra_sc'] = plugins_url( 'js/mce-button-wgextra-inline.js', __FILE__ );
		return $plugin_array;
	}

	public function mce_button( $buttons ) {
		array_push( $buttons, 'wgextra_sc' );
		return $buttons;
	}


	/**
	 * Append the 'Add Slider' button to selected admin pages
	 */
	public function media_buttons_context( $context ) {
		global $WordPress_Gallery_Extra;

		$context .= '<button type="button" class="button ' . $WordPress_Gallery_Extra->SLUG . '-add-grid" title="' . 
					esc_attr__( "Insert WordPress Gallery Extra grid into your post", 'wordpress-gallery-extra' ) . 
					'" data-editor="content"><span class="wp-media-buttons-icon"></span> ' .
					__( "Add Gallery", 'wordpress-gallery-extra' ) . '</button>';

		return $context;
	}

	/**
	 * Outputs the view inside the wordpress editor.
	 */
	public function print_media_templates() {
		include_once dirname( __FILE__ ) . '/templates/tmpl-editor-wgextra.html';
	}

	public function admin_head() {
		global $WordPress_Gallery_Extra, $wp_scripts, $pagenow, $wp_version;

		if ( !in_array( 'wgextra-jquery-ui', $wp_scripts->queue ) ) {
			add_editor_style( plugins_url( 'css/style.content.css', __FILE__ ) );
			wp_enqueue_style( 'wgextra-codemirror', plugins_url( 'assets/css/codemirror.css', $WordPress_Gallery_Extra->MAIN ) );
			wp_enqueue_style( 'jquery-minicolors', plugins_url( 'assets/css/jquery.minicolors.css', $WordPress_Gallery_Extra->MAIN ) );
			wp_enqueue_style( 'wgextra-multi-select', plugins_url( 'assets/css/multi-select.dist.css', $WordPress_Gallery_Extra->MAIN ) );
			wp_enqueue_style( 'wgextra-helpers', plugins_url( 'assets/css/helpers.css', $WordPress_Gallery_Extra->MAIN ) );
			wp_enqueue_style( 'jquery-ui-slider-pips', plugins_url( 'assets/css/jquery-ui-slider-pips.min.css', $WordPress_Gallery_Extra->MAIN ) );
			if ( is_rtl() ) {
				wp_enqueue_style( 'wgextra-add-grid-stylesheet', plugins_url( 'css/add-grid-style-rtl.css', __FILE__ ) );
			} else {
				wp_enqueue_style( 'wgextra-add-grid-stylesheet', plugins_url( 'css/add-grid-style.css', __FILE__ ) );
			}

			wp_enqueue_script( 'wgextra-jquery-ui', plugins_url( 'assets/js/jquery-ui.js', $WordPress_Gallery_Extra->MAIN ), array(
				'jquery'
			), '1.12.1' );
			wp_enqueue_script( 'wp-js-hooks', plugins_url( 'assets/js/event-manager.min.js', $WordPress_Gallery_Extra->MAIN ), null, '1.0.0' );
			wp_enqueue_script( 'wgextra-codemirror', plugins_url( 'assets/js/codemirror.min.js', $WordPress_Gallery_Extra->MAIN ), null, '5.25.2' );
			wp_enqueue_script( 'visibility-changed', plugins_url( 'assets/js/visibilityChanged.min.js', $WordPress_Gallery_Extra->MAIN ), null, '1.0.0' );
			wp_enqueue_script( 'jquery-quicksearch', plugins_url( 'assets/js/jquery.quicksearch.js', $WordPress_Gallery_Extra->MAIN ), array(
				'jquery'
			), '1.0.0' );
			wp_enqueue_script( 'jquery-multiselect', plugins_url( 'assets/js/jquery.multi-select.js', $WordPress_Gallery_Extra->MAIN ), array(
				'jquery',
				'jquery-quicksearch'
			), '0.9.12' );
			wp_enqueue_script( 'jquery-minicolors', plugins_url( 'assets/js/jquery.minicolors.min.js', $WordPress_Gallery_Extra->MAIN ), array(
				'jquery'
			), '2.2.4' );
			wp_enqueue_script( 'jquery-ui-slider-pips', plugins_url( 'assets/js/jquery-ui-slider-pips.min.js', $WordPress_Gallery_Extra->MAIN ), array(
				'jquery-ui-slider'
			), '0.9.12' );
		}

		wp_enqueue_script( 'wgextra-add-grid-script', plugins_url( 'js/add-grid-script.js', __FILE__ ), array(
			'wgextra-jquery-ui',
			'wp-js-hooks',
			'wgextra-codemirror',
			'visibility-changed',
			'jquery-multiselect',
			'jquery-minicolors',
			'jquery-ui-slider-pips',
			'jquery-serialize-object',
			'underscore',
			'shortcode',
			'wp-util'
		), false );
		wp_enqueue_script( 'wgextra-editor-view', plugins_url( 'js/wgextra-editor-view.js', __FILE__ ), array( 'wgextra-add-grid-script' ), false, true );

		$wp_localize_script = array(
			"templates_types" => $WordPress_Gallery_Extra->TEMPLATES_TYPES,
			"sources_types" => $WordPress_Gallery_Extra->SOURCES_TYPES,
			"default_template_options" => $WordPress_Gallery_Extra->options_to_attributes( $WordPress_Gallery_Extra->DEFAULT_TEMPLATE_OPTIONS ),
			"templates" => array(),
			"Insert" => __( 'Insert', 'wordpress-gallery-extra' ),
			"Cancel" => __( 'Cancel', 'wordpress-gallery-extra' ),
			"Reset" => __( 'Reset', 'wordpress-gallery-extra' ),
			"Please choose a template!" => __( 'Please choose a template!', 'wordpress-gallery-extra' ),
			"Please enter a valid number in the '%s' field." => __( "Please enter a valid number in the '%s' field.", 'wordpress-gallery-extra' ),
			"Item Number" => __( 'Item Number', 'wordpress-gallery-extra' ),
			"Type to Search..." => __( 'Type to Search...', 'wordpress-gallery-extra' )
		);

		foreach ( $WordPress_Gallery_Extra->TEMPLATES_TYPES as $temp_key => $temp_type ) {
			$wp_localize_script[$temp_key] = $temp_type['name'];
		}

		foreach ( $WordPress_Gallery_Extra->TEMPLATES as $tid => $template ) {
			$wp_localize_script['templates'][$tid] = $WordPress_Gallery_Extra->options_to_attributes( $template );
		}

		$post_types_taxonomies = array();

		foreach ( parent::get_public_post_types() as $post_type => $post_type_name ) {
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

		wp_localize_script( 'wgextra-add-grid-script', 'wgextraEditor', $wp_localize_script );
	}

	public function admin_footer() {
		global $WordPress_Gallery_Extra;

		$options = $WordPress_Gallery_Extra->DEFAULT_TEMPLATE_OPTIONS;

		$ordering_order_by_options = apply_filters( 'wgextra_source_ordering_order_by', array(
			'' => __( "None", 'wordpress-gallery-extra' ),
			'ID' => __( "ID", 'wordpress-gallery-extra' ),
			'author' => __( "Author", 'wordpress-gallery-extra' ),
			'title' => __( "Title", 'wordpress-gallery-extra' ),
			'date' => __( "Date", 'wordpress-gallery-extra' ),
			'name' => __( "Post slug", 'wordpress-gallery-extra' ),
			'modified' => __( "Last modified date", 'wordpress-gallery-extra' ),
			'parent' => __( "Post parent id", 'wordpress-gallery-extra' ),
			'rand' => __( "Random", 'wordpress-gallery-extra' ),
			'comment_count' => __( "Number of comments", 'wordpress-gallery-extra' ),
			'menu_order' => __( "Page Order (Menu Order)", 'wordpress-gallery-extra' ),
			'meta_value' => __( "Meta Value", 'wordpress-gallery-extra' ),
			'meta_value_num' => __( "Numeric Meta Value", 'wordpress-gallery-extra' ),
			'post__in' => __( "Preserve post ID order", 'wordpress-gallery-extra' )
		) );

		$css3_easings = apply_filters( 'wgee-css3_easings', array(
			"" => __( "None", 'wordpress-gallery-extra' ),
			"linear" => "linear",
			"ease" => "ease",
			"ease-in" => "ease-in",
			"ease-out" => "ease-out",
			"ease-in-out" => "ease-in-out"
		) );
		$jquery_easings = apply_filters( 'wgee-jquery_easings', array(
			__( "Normal", 'wordpress-gallery-extra' ) => array(
				"linear" => "linear",
				"swing" => "swing"
			),
			"Quad" => array(
				"easeInQuad" => "easeInQuad",
				"easeOutQuad" => "easeOutQuad",
				"easeInOutQuad" => "easeInOutQuad"
			),
			"Cubic" => array(
				"easeInCubic" => "easeInCubic",
				"easeOutCubic" => "easeOutCubic",
				"easeInOutCubic" => "easeInOutCubic"
			),
			"Quart" => array(
				"easeInQuart" => "easeInQuart",
				"easeOutQuart" => "easeOutQuart",
				"easeInOutQuart" => "easeInOutQuart"
			),
			"Quint" => array(
				"easeInQuint" => "easeInQuint",
				"easeOutQuint" => "easeOutQuint",
				"easeInOutQuint" => "easeInOutQuint"
			),
			"Sine" => array(
				"easeInSine" => "easeInSine",
				"easeOutSine" => "easeOutSine",
				"easeInOutSine" => "easeInOutSine"
			),
			"Expo" => array(
				"easeInExpo" => "easeInExpo",
				"easeOutExpo" => "easeOutExpo",
				"easeInOutExpo" => "easeInOutExpo"
			),
			"Circ" => array(
				"easeInCirc" => "easeInCirc",
				"easeOutCirc" => "easeOutCirc",
				"easeInOutCirc" => "easeInOutCirc"
			),
			"Elastic" => array(
				"easeInElastic" => "easeInElastic",
				"easeOutElastic" => "easeOutElastic",
				"easeInOutElastic" => "easeInOutElastic"
			),
			"Back" => array(
				"easeInBack" => "easeInBack",
				"easeOutBack" => "easeOutBack",
				"easeInOutBack" => "easeInOutBack"
			),
			"Bounce" => array(
				"easeInBounce" => "easeInBounce",
				"easeOutBounce" => "easeOutBounce",
				"easeInOutBounce" => "easeInOutBounce"
			)
		) );
		$animate_css_effects = array(
			__( "Attention Seekers", 'wordpress-gallery-extra' ) => array(
				"bounce" => __( "Bounce", 'wordpress-gallery-extra' ),
				"flash" => __( "Flash", 'wordpress-gallery-extra' ),
				"pulse" => __( "Pulse", 'wordpress-gallery-extra' ),
				"rubber-band" => __( "Rubber Band", 'wordpress-gallery-extra' ),
				"shake" => __( "Shake", 'wordpress-gallery-extra' ),
				"head-shake" => __( "Head Shake", 'wordpress-gallery-extra' ),
				"swing" => __( "Swing", 'wordpress-gallery-extra' ),
				"tada" => __( "Tada", 'wordpress-gallery-extra' ),
				"wobble" => __( "Wobble", 'wordpress-gallery-extra' ),
				"jello" => __( "Jello", 'wordpress-gallery-extra' )
			),
			__( "Bouncing Entrances", 'wordpress-gallery-extra' ) => array(
				"bounce-in" => __( "Bounce In", 'wordpress-gallery-extra' ),
				"bounce-in-down" => __( "Bounce In Down", 'wordpress-gallery-extra' ),
				"bounce-in-up" => __( "Bounce In Up", 'wordpress-gallery-extra' ),
				"bounce-in-left" => __( "Bounce In Left", 'wordpress-gallery-extra' ),
				"bounce-in-right" => __( "Bounce In Right", 'wordpress-gallery-extra' )
			),
			__( "Bouncing Exits", 'wordpress-gallery-extra' ) => array(
				"bounce-out" => __( "Bounce Out", 'wordpress-gallery-extra' ),
				"bounce-out-down" => __( "Bounce Out Down", 'wordpress-gallery-extra' ),
				"bounce-out-up" => __( "Bounce Out Up", 'wordpress-gallery-extra' ),
				"bounce-out-left" => __( "Bounce Out Left", 'wordpress-gallery-extra' ),
				"bounce-out-right" => __( "Bounce Out Right", 'wordpress-gallery-extra' )
			),
			__( "Fading Entrances", 'wordpress-gallery-extra' ) => array(
				"fade-in" => __( "Fade In", 'wordpress-gallery-extra' ),
				"fade-in-down" => __( "Fade In Down", 'wordpress-gallery-extra' ),
				"fade-in-down-big" => __( "Fade In Down Big", 'wordpress-gallery-extra' ),
				"fade-in-up" => __( "Fade In Up", 'wordpress-gallery-extra' ),
				"fade-in-up-big" => __( "Fade In Up Big", 'wordpress-gallery-extra' ),
				"fade-in-left" => __( "Fade In Left", 'wordpress-gallery-extra' ),
				"fade-in-left-big" => __( "Fade In Left Big", 'wordpress-gallery-extra' ),
				"fade-in-right" => __( "Fade In Right", 'wordpress-gallery-extra' ),
				"fade-in-right-big" => __( "Fade In Right Big", 'wordpress-gallery-extra' )
			),
			__( "Fading Exits", 'wordpress-gallery-extra' ) => array(
				"fade-out" => __( "Fade Out", 'wordpress-gallery-extra' ),
				"fade-out-down" => __( "Fade Out Down", 'wordpress-gallery-extra' ),
				"fade-out-down-big" => __( "Fade Out Down Big", 'wordpress-gallery-extra' ),
				"fade-out-up" => __( "Fade Out Up", 'wordpress-gallery-extra' ),
				"fade-out-up-big" => __( "Fade Out Up Big", 'wordpress-gallery-extra' ),
				"fade-out-left" => __( "Fade Out Left", 'wordpress-gallery-extra' ),
				"fade-out-left-big" => __( "Fade Out Left Big", 'wordpress-gallery-extra' ),
				"fade-out-right" => __( "Fade Out Right", 'wordpress-gallery-extra' ),
				"fade-out-right-big" => __( "Fade Out Right Big", 'wordpress-gallery-extra' )
			),
			__( "Flippers", 'wordpress-gallery-extra' ) => array(
				"flip" => __( "Flip", 'wordpress-gallery-extra' ),
				"flip-in-x" => __( "Flip In X", 'wordpress-gallery-extra' ),
				"flip-in-y" => __( "Flip In Y", 'wordpress-gallery-extra' ),
				"flip-out-x" => __( "Flip Out X", 'wordpress-gallery-extra' ),
				"flip-out-y" => __( "Flip Out Y", 'wordpress-gallery-extra' )
			),
			__( "Lightspeed", 'wordpress-gallery-extra' ) => array(
				"light-speed-in" => __( "Light Speed In", 'wordpress-gallery-extra' ),
				"light-speed-out" => __( "Light Speed Out", 'wordpress-gallery-extra' )
			),
			__( "Rotating Entrances", 'wordpress-gallery-extra' ) => array(
				"rotate-in" => __( "Rotate In", 'wordpress-gallery-extra' ),
				"rotate-in-down-left" => __( "Rotate In Down Left", 'wordpress-gallery-extra' ),
				"rotate-in-down-right" => __( "Rotate In Down Right", 'wordpress-gallery-extra' ),
				"rotate-in-up-left" => __( "Rotate In Up Left", 'wordpress-gallery-extra' ),
				"rotate-in-up-right" => __( "Rotate In Up Right", 'wordpress-gallery-extra' )
			),
			__( "Rotating Exits", 'wordpress-gallery-extra' ) => array(
				"rotate-out" => __( "Rotate Out", 'wordpress-gallery-extra' ),
				"rotate-out-down-left" => __( "Rotate Out Down Left", 'wordpress-gallery-extra' ),
				"rotate-out-down-right" => __( "Rotate Out Down Right", 'wordpress-gallery-extra' ),
				"rotate-out-up-left" => __( "Rotate Out Up Left", 'wordpress-gallery-extra' ),
				"rotate-out-up-right" => __( "Rotate Out Up Right", 'wordpress-gallery-extra' )
			),
			__( "Sliding Entrances", 'wordpress-gallery-extra' ) => array(
				"slide-in-up" => __( "Slide In Up", 'wordpress-gallery-extra' ),
				"slide-in-down" => __( "Slide In Down", 'wordpress-gallery-extra' ),
				"slide-in-left" => __( "Slide In Left", 'wordpress-gallery-extra' ),
				"slide-in-right" => __( "Slide In Right", 'wordpress-gallery-extra' )
			),
			__( "Sliding Exits", 'wordpress-gallery-extra' ) => array(
				"slide-out-up" => __( "Slide Out Up", 'wordpress-gallery-extra' ),
				"slide-out-down" => __( "Slide Out Down", 'wordpress-gallery-extra' ),
				"slide-out-left" => __( "Slide Out Left", 'wordpress-gallery-extra' ),
				"slide-out-right" => __( "Slide Out Right", 'wordpress-gallery-extra' )
			),
			__( "Zoom Entrances", 'wordpress-gallery-extra' ) => array(
				"zoom-in" => __( "Zoom In", 'wordpress-gallery-extra' ),
				"zoom-in-up" => __( "Zoom In Up", 'wordpress-gallery-extra' ),
				"zoom-in-down" => __( "Zoom In Down", 'wordpress-gallery-extra' ),
				"zoom-in-left" => __( "Zoom In Left", 'wordpress-gallery-extra' ),
				"zoom-in-right" => __( "Zoom In Right", 'wordpress-gallery-extra' )
			),
			__( "Zoom Exits", 'wordpress-gallery-extra' ) => array(
				"zoom-out" => __( "Zoom Out", 'wordpress-gallery-extra' ),
				"zoom-out-up" => __( "Zoom Out Up", 'wordpress-gallery-extra' ),
				"zoom-out-down" => __( "Zoom Out Down", 'wordpress-gallery-extra' ),
				"zoom-out-left" => __( "Zoom Out Left", 'wordpress-gallery-extra' ),
				"zoom-out-right" => __( "Zoom Out Right", 'wordpress-gallery-extra' )
			),
			__( "Specials", 'wordpress-gallery-extra' ) => array(
				"hinge" => __( "Hinge", 'wordpress-gallery-extra' ),
				"jack-in-the-box" => __( "Jack In The Box", 'wordpress-gallery-extra' ),
				"rollIn" => __( "Roll In", 'wordpress-gallery-extra' ),
				"rollOut" => __( "Roll Out", 'wordpress-gallery-extra' )
			)
		);
		$icon_visibilities = apply_filters(
			'wgee-icon-visibilities',
			array_merge(
				array(
					"visible" => __( "Always Visible", 'wordpress-gallery-extra' )
				),
				$animate_css_effects
			)
		);
		$caption_visibilities = apply_filters(
			'wgee-caption-visibilities',
			array_merge(
				array(
					"visible" => __( "Always Visible", 'wordpress-gallery-extra' )
				),
				$animate_css_effects
			)
		);
		$overlay_visibilities = apply_filters(
			'wgee-overlay-visibilities',
			array_merge(
				array(
					"visible" => __( "Always Visible", 'wordpress-gallery-extra' )
				),
				$animate_css_effects
			)
		);
		$thumbnail_effects = apply_filters(
			'wgee-thumbnail-effects',
			array(
				"none" => __( "None", 'wordpress-gallery-extra' ),
				__( "Zooming", 'wordpress-gallery-extra' ) => array(
					"zoom-in" => __( "Zoom In", 'wordpress-gallery-extra' ),
					"zoom-out" => __( "Zoom Out", 'wordpress-gallery-extra' )
				),
				__( "Coloring", 'wordpress-gallery-extra' ) => array(
					"colorize" => __( "Colorize", 'wordpress-gallery-extra' ),
					"grayscale" => __( "Grayscale", 'wordpress-gallery-extra' ),
					"cold" => __( "Cold", 'wordpress-gallery-extra' ),
					"warm" => __( "Warm", 'wordpress-gallery-extra' ),
					"more-colorful" => __( "More Colorful", 'wordpress-gallery-extra' ),
					"glow" => __( "Glow", 'wordpress-gallery-extra' )
				),
				__( "Bluring", 'wordpress-gallery-extra' ) => array(
					"blur-in" => __( "Blur In", 'wordpress-gallery-extra' ),
					"blur-out" => __( "Blur Out", 'wordpress-gallery-extra' )
				),
				__( "Sliding", 'wordpress-gallery-extra' ) => array(
					"slide-up" => __( "Slide Up", 'wordpress-gallery-extra' ),
					"slide-down" => __( "Slide Down", 'wordpress-gallery-extra' ),
					"slide-left" => __( "Slide Left", 'wordpress-gallery-extra' ),
					"slide-right" => __( "Slide Right", 'wordpress-gallery-extra' )
				),
				__( "Moving", 'wordpress-gallery-extra' ) => array(
					"move-up" => __( "Move Up", 'wordpress-gallery-extra' ),
					"move-down" => __( "Move Down", 'wordpress-gallery-extra' ),
					"move-left" => __( "Move Left", 'wordpress-gallery-extra' ),
					"move-right" => __( "Move Right", 'wordpress-gallery-extra' )
				)
			)
		);
		$slider_arrows_skins = apply_filters(
			'wgee-slider_arrows_skins',
			array(
				"default" => __( "Default", 'wordpress-gallery-extra' ),
				"slide" => __( "Slide", 'wordpress-gallery-extra' ),
				"image-bar" => __( "Image Bar", 'wordpress-gallery-extra' ),
				"circle-pop" => __( "Circle Pop", 'wordpress-gallery-extra' ),
				"round-slide" => __( "Round Slide", 'wordpress-gallery-extra' ),
				"split" => __( "Split", 'wordpress-gallery-extra' ),
				"reveal" => __( "Reveal", 'wordpress-gallery-extra' ),
				"thumb-flip" => __( "Thumb Flip", 'wordpress-gallery-extra' ),
				"thumb-double-flip" => __( "Thumb Double Flip", 'wordpress-gallery-extra' ),
				"circle-slide" => __( "Circle Slide", 'wordpress-gallery-extra' ),
				"grow-pop" => __( "Grow Pop", 'wordpress-gallery-extra' ),
				"diamond" => __( "Diamond", 'wordpress-gallery-extra' ),
				"fill-slide" => __( "Fill Slide", 'wordpress-gallery-extra' ),
				"fill-path" => __( "Fill Path", 'wordpress-gallery-extra' )
			)
		);
		$slider_scrollbar_skins = apply_filters(
			'wgee-slider-scrollbar-skins',
			array(
				"default" => __( "Default", 'wordpress-gallery-extra' ),
				"scale-up" => __( "Scale Up", 'wordpress-gallery-extra' ),
				"white-light" => __( "White Light", 'wordpress-gallery-extra' ),
				"silver" => __( "Silver", 'wordpress-gallery-extra' ),
				"slim-bar" => __( "Slim Bar", 'wordpress-gallery-extra' )
			)
		);
		$slider_thumbnails_skins = apply_filters(
			'wgee-slider_thumbnails_skins',
			array(
				"default" => __( "Default", 'wordpress-gallery-extra' ),
				"coverflow" => __( "Coverflow", 'wordpress-gallery-extra' ),
				"small" => __( "Small", 'wordpress-gallery-extra' ),
				"circle" => __( "Circle", 'wordpress-gallery-extra' ),
				"featured-dark" => __( "Featured Dark", 'wordpress-gallery-extra' ),
				"featured-light" => __( "Featured Light", 'wordpress-gallery-extra' ),
			)
		);
		$slider_bullets_skins = apply_filters(
			'wgee-slider_bullets_skins',
			array(
				"default" => __( "Default", 'wordpress-gallery-extra' ),
				"fill-up" => __( "Fill Up", 'wordpress-gallery-extra' ),
				"scale-up" => __( "Scale Up", 'wordpress-gallery-extra' ),
				"stroke" => __( "Stroke", 'wordpress-gallery-extra' ),
				"fill-in" => __( "Fill In", 'wordpress-gallery-extra' ),
				"grow-up" => __( "Grow Up", 'wordpress-gallery-extra' ),
				"dot-stroke" => __( "Dot Stroke", 'wordpress-gallery-extra' ),
				"draw-circle" => __( "Draw Circle", 'wordpress-gallery-extra' ),
				"worm" => __( "Worm", 'wordpress-gallery-extra' ),
				"worm-stroke" => __( "Worm Stroke", 'wordpress-gallery-extra' ),
			)
		);
		$slider_time_loader_skins = apply_filters(
			'wgee-slider_time_loader_skins',
			array(
				"default" => __( "Default", 'wordpress-gallery-extra' ),
				"pie-1" => __( "Pie Loader 1", 'wordpress-gallery-extra' ),
				"pie-2" => __( "Pie Loader 2", 'wordpress-gallery-extra' ),
				"pie-3" => __( "Pie Loader 3", 'wordpress-gallery-extra' ),
				"donut-1" => __( "Donut Loader 1", 'wordpress-gallery-extra' ),
				"donut-2" => __( "Donut Loader 2", 'wordpress-gallery-extra' ),
				"donut-3" => __( "Donut Loader 3", 'wordpress-gallery-extra' ),
			)
		);

		$icons_json = file_get_contents( plugin_dir_path( $WordPress_Gallery_Extra->MAIN ) . "assets/css/icons.json" );
		$icons = json_decode( $icons_json, true );
?>
<div id="wgextra-editor-dialog" title="<?php esc_attr_e( "WordPress Gallery Extra", 'wordpress-gallery-extra' ); ?>" style="display: none;">
	<form method="get" accept-charset="utf-8">
		<div class="white-bg ui-corner-all">
			<table class="wgee-form-table">
				<tbody>
					<tr>
						<th><label for="wgee-template"><?php _e( "Template", 'wordpress-gallery-extra' ); ?></label></th>
						<td>
							<select name="template_id" id="wgee-template">
								<option value=""><?php _e( "None", 'wordpress-gallery-extra' ); ?></option>
<?php
		$templates = $WordPress_Gallery_Extra->TEMPLATES;
		// Sort templates
		krsort( $templates );

		foreach ( $templates as $tid => $template ) {
?>
								<option value="<?php echo $tid; ?>"><?php echo esc_attr( $template['name'] ); ?></option>
<?php
		}
?>
							</select>
							<p class="description"><?php _e( "Choose a premade template to create the gallery with.", 'wordpress-gallery-extra' ); ?></p>
						</td>
					</tr>
					<tr>
						<th><label for="wgee-advanced-options"><?php _e( "Advanced", 'wordpress-gallery-extra' ); ?></label></th>
						<td>
							<div class="wgee-onoffswitch">
								<input type="checkbox" name="advanced-options" class="wgee-onoffswitch-checkbox" id="wgee-advanced-options" checked />
								<label class="wgee-onoffswitch-label" for="wgee-advanced-options">
									<div class="wgee-onoffswitch-inner">
										<div class="wgee-onoffswitch-active">ON</div>
										<div class="wgee-onoffswitch-inactive">OFF</div>
									</div>
									<div class="wgee-onoffswitch-switch"></div>
								</label>
							</div>
							<p class="description"><?php _e( "Show advanced options to take more control.", 'wordpress-gallery-extra' ); ?></p>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="wgee-divider field advanced-options" style="height: 20px;"></div>

		<div class="field advanced-options" rel="tabs" data-options="disabled: [1]">
			<ul>
				<li><a href="#wgee-general-tab"><?php _e( "General", 'wordpress-gallery-extra' ); ?></a></li>
				<li><a href="#wgee-lightbox-tab"><?php _e( "Lightbox", 'wordpress-gallery-extra' ); ?></a></li>
				<li><a href="#wgee-source-tab"><?php _e( "Source", 'wordpress-gallery-extra' ); ?></a></li>
				<li><a href="#wgee-display-tab"><?php _e( "Display", 'wordpress-gallery-extra' ); ?></a></li>
				<li><a href="#wgee-styling-tab"><?php _e( "Styling", 'wordpress-gallery-extra' ); ?></a></li>
			</ul>
			<div class="white-bg ui-corner-bottom ui-corner-tr scroll-container">
				<div id="wgee-general-tab">
					<table class="wgee-form-table">
						<tbody>
							<tr class="field" rel="gallery-type">
								<th><label for="wgee-gallery-type"><?php _e( "Gallery Type", 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<select id="wgee-gallery-type" name="template">
<?php
		foreach ( $WordPress_Gallery_Extra->TEMPLATES_TYPES as $key => $value ) {
?>
										<option value="<?php echo esc_attr( $key ); ?>"<?php selected( $options['template'], $key ); ?>><?php echo $value['name']; ?></option>
<?php
		}
?>
									</select><br />
									<p class="description"><?php _e( "Type of your gallery layout.", 'wordpress-gallery-extra' ); ?></p>
								</td>
							</tr>
							<tr class="field" rel="loading-type">
								<th><label><?php _e( 'Loading Type', 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<div class="wgee-switch-field">
										<label for="wgee-loading-type-none"><?php _e( "None", 'wordpress-gallery-extra' ); ?></label>
										<input type="radio" name="loading-type" id="wgee-loading-type-none" value="none"<?php checked( $options['loading_type'], 'none' ); ?> />
										<label for="wgee-loading-type-indicator" title="<?php _e( "An animated loading animation indicator is shown before the thumbnails have loaded.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Loading Indicator", 'wordpress-gallery-extra' ); ?></label>
										<input type="radio" name="loading-type" id="wgee-loading-type-indicator" value="indicator"<?php checked( $options['loading_type'], 'indicator' ); ?> />
									</div>
									<p class="description"><?php _e( "Thumbnails loading type in the grid.", 'wordpress-gallery-extra' ); ?></p>
								</td>
							</tr>
							<tr class="field" rel="link">
								<th><label><?php _e( 'Link', 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<div class="wgee-inside-table">
										<table class="wgee-form-table">
											<tbody>
												<tr>
													<th><label for="wgee-link-to" title="<?php esc_attr_e( "Controls where the thumbnails must link to.", 'wordpress-gallery-extra' ); ?>"><?php _e( "To", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgee-link-target" title="<?php esc_attr_e( "Set a custom target for link.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Target", 'wordpress-gallery-extra' ); ?></label></th>
												</tr>
												<tr>
													<td>
														<select id="wgee-link-to" name="link">
															<option value="none"><?php _e( "None", 'wordpress-gallery-extra' ); ?></option>
															<option value="page"<?php selected( $options['link']['to'], 'page' ); ?>><?php _e( "Page URL", 'wordpress-gallery-extra' ); ?></option>
															<option value="file"<?php selected( $options['link']['to'], 'file' ); ?>><?php _e( "Media File", 'wordpress-gallery-extra' ); ?></option>
															<option value="custom"<?php selected( $options['link']['to'], 'custom' ); ?>><?php _e( "Custom URL", 'wordpress-gallery-extra' ); ?></option>
														</select>
													</td>
													<td>
<?php
		$target_options = apply_filters( 'wgextra_attachment_field_custom_target_options', array(
			'_self'     => __( 'Open on the same page (_self)', 'wordpress-gallery-extra' ),
			'_blank'    => __( 'Open on new page (_blank)', 'wordpress-gallery-extra' ),
			'_parent'   => __( 'Open in parent frame (_parent)', 'wordpress-gallery-extra' ),
			'_top'      => __( 'Open in main frame (_top)', 'wordpress-gallery-extra' ),
			'_lightbox' => __( 'Open in LightBox (_lightbox)', 'wordpress-gallery-extra' ),
			'_video'    => __( 'Open Video in LightBox (_video)', 'wordpress-gallery-extra' ),
			'_audio'    => __( 'Open Audio in LightBox (_audio)', 'wordpress-gallery-extra' )
		) );

		$WordPress_Gallery_Extra->create_dropdown( array(
			'options' => $target_options,
			'selected' => $options['link']['target'],
			'id' => 'wgee-link-target',
			'name' => 'link-target'
		) );
?>
													</td>
												</tr>
												<tr class="field" rel="link-url">
													<th colspan="2" style="padding-top: 20px;" class="wgextra_link_url"><label for="wgee-link-custom-url" title="<?php esc_attr_e( "Point thumbnails to a custom URL.", 'wordpress-gallery-extra' ); ?>"><?php _e( "URL", 'wordpress-gallery-extra' ); ?></label></th>
												</tr>
												<tr class="field" rel="link-url">
													<td colspan="2">
														<input type="url" id="wgee-link-custom-url" name="link-url" value="<?php echo esc_attr( $options['link']['url'] ); ?>" />
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<p class="description"><?php _e( "Controls where the thumbnails must link to.", 'wordpress-gallery-extra' ); ?></p>
								</td>
							</tr>
							<tr>
								<th><label for="wgee-custom-class"><?php _e( 'Custom CSS class', 'wordpress-gallery-extra' ); ?></label></th>
								<td colspan="3">
									<input type="text" class="full-width" id="wgee-custom-class" name="class" placeholder="custom-class  another-custom-class" value="<?php echo esc_attr( $options['custom_class'] ); ?>" />
									<p class="description"><?php _e( "CSS classes separated by space. Usefull to add custom css to the grid.", 'wordpress-gallery-extra' ); ?></p>
								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<div id="wgee-lightbox-tab">
					<table class="wgee-form-table">
						<tbody>
							<tr class="field" rel="lightbox">
								<th><label for="wgee-loading-animation"><?php _e( 'Lightbox Type', 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<div class="wgee-switch-field">
										<label for="wgee-lightbox-none"><?php _e( "None", 'wordpress-gallery-extra' ); ?></label>
										<input type="radio" name="lightbox" id="wgee-lightbox-none" value="none"<?php checked( $options['lightbox_type'], 'none' ); ?> />
										<label for="wgee-lightbox-magnific"><?php _e( "Magnific Popup", 'wordpress-gallery-extra' ); ?></label>
										<input type="radio" name="lightbox" id="wgee-lightbox-magnific" value="magnific"<?php checked( $options['lightbox_type'], 'magnific' ); ?> />
										<label for="wgee-lightbox-ilightbox"<?php if( !class_exists( "iLightBox" ) ) { ?> title="<?php esc_attr_e( 'Please download and activate iLightBox plugin to enable this feature.', 'wordpress-gallery-extra' ); ?>"<?php } ?>><?php _e( "iLightBox", 'wordpress-gallery-extra' ); ?></label>
										<input type="radio" name="lightbox" id="wgee-lightbox-ilightbox" value="ilightbox"<?php checked( $options['lightbox_type'], 'ilightbox' ); ?><?php if( !class_exists( "iLightBox" ) ) { ?> disabled="disabled"<?php } ?> />
									</div>
									<p class="description"><?php _e( 'Choose the default LightBox to be used.', 'wordpress-gallery-extra' ); ?> <?php printf( __( '<a target="_blank" rel="noreferrer noopener" href="%s">Download and activate iLightBox</a> which works flawlessly with WordPress Gallery Extra.', 'wordpress-gallery-extra' ), esc_url( "http://goo.gl/DlaJq" ) ); ?></p>
								</td>
							</tr>
						</tbody>
					</table>

					<table class="wgee-form-table field" rel="magnific-lightbox">
						<tbody>
							<tr>
								<td colspan="4" style="height: 0;" class="padding-none"><hr class="margin-top-three margin-bottom-two"></td>
							</tr>
							<tr>
								<th><label for="wgee-magnific-animation"><?php _e( "Animation", 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<select name="magnific-animation" id="wgee-magnific-animation">
										<option value="mfp-none"><?php _e( "None", 'wordpress-gallery-extra' ); ?></option>
										<option value="mfp-fade"><?php _e( "Fade", 'wordpress-gallery-extra' ); ?></option>
										<option value="mfp-zoom-in"><?php _e( "Zoom In Out", 'wordpress-gallery-extra' ); ?></option>
										<option value="mfp-newspaper"><?php _e( "Newspaper", 'wordpress-gallery-extra' ); ?></option>
										<option value="mfp-move-horizontal"><?php _e( "Move Horizontal", 'wordpress-gallery-extra' ); ?></option>
										<option value="mfp-move-vertical"><?php _e( "Move Vertical", 'wordpress-gallery-extra' ); ?></option>
										<option value="mfp-3d-unfold"><?php _e( "3d Unfold", 'wordpress-gallery-extra' ); ?></option>
										<option value="mfp-zoom-out"><?php _e( "Zoom Out In", 'wordpress-gallery-extra' ); ?></option>
									</select>
									<p class="description"><?php _e( "CSS classes separated by space. Usefull to add custom css to the grid.", 'wordpress-gallery-extra' ); ?></p>
								</td>
								<th><label for="wgee-magnific-vertical-fit"><?php _e( "Vertical Fit", 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<div class="wgee-onoffswitch">
										<input type="checkbox" name="magnific-vertical-fit" class="wgee-onoffswitch-checkbox" id="wgee-magnific-vertical-fit"<?php checked( $options['lightbox_magnific']['vertical_fit'], 'yes' ); ?> />
										<label class="wgee-onoffswitch-label" for="wgee-magnific-vertical-fit">
											<div class="wgee-onoffswitch-inner">
												<div class="wgee-onoffswitch-active">ON</div>
												<div class="wgee-onoffswitch-inactive">OFF</div>
											</div>
											<div class="wgee-onoffswitch-switch"></div>
										</label>
									</div>
									<p class="description"><?php _e( "Popup opening & closing animation type.", 'wordpress-gallery-extra' ); ?></p>
								</td>
							</tr>
							<tr>
								<th><label for="wgee-magnific-preload"><?php _e( "Preload", 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<div class="wgee-onoffswitch">
										<input type="checkbox" name="magnific-preload" class="wgee-onoffswitch-checkbox" id="wgee-magnific-preload"<?php checked( $options['lightbox_magnific']['preload'], 'yes' ); ?> />
										<label class="wgee-onoffswitch-label" for="wgee-magnific-preload">
											<div class="wgee-onoffswitch-inner">
												<div class="wgee-onoffswitch-active">ON</div>
												<div class="wgee-onoffswitch-inactive">OFF</div>
											</div>
											<div class="wgee-onoffswitch-switch"></div>
										</label>
									</div>
									<p class="description"><?php _e( "Wait for images to load before displaying?", 'wordpress-gallery-extra' ); ?></p>
								</td>
								<th><label for="wgee-magnific-deeplink"><?php _e( "Deeplinking", 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<div class="wgee-onoffswitch">
										<input type="checkbox" name="magnific-deeplink" class="wgee-onoffswitch-checkbox" id="wgee-magnific-deeplink"<?php checked( $options['lightbox_magnific']['deeplink'], 'yes' ); ?>>
										<label class="wgee-onoffswitch-label" for="wgee-magnific-deeplink">
											<div class="wgee-onoffswitch-inner">
												<div class="wgee-onoffswitch-active">ON</div>
												<div class="wgee-onoffswitch-inactive">OFF</div>
											</div>
											<div class="wgee-onoffswitch-switch"></div>
										</label>
									</div>
									<p class="description"><?php _e( "Enabling the hash linking images.", 'wordpress-gallery-extra' ); ?></p>
								</td>
							</tr>
						</tbody>
					</table>

					<table class="wgee-form-table field" rel="ilightbox-lightbox">
						<tbody>
							<tr>
								<td colspan="4" style="height: 0;" class="padding-none"><hr class="margin-top-three margin-bottom-two"></td>
							</tr>
							<tr>
								<th><label for="wgee-ilightbox-skin"><?php _e( "Skin", 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<select name="ilightbox-skin" id="wgee-ilightbox-skin">
										<option value="flat-dark"<?php selected( $options['lightbox_ilightbox']['skin'], 'flat-dark' ); ?>><?php _e( "Flat Dark", 'wordpress-gallery-extra' ); ?></option>
										<option value="dark"<?php selected( $options['lightbox_ilightbox']['skin'], 'dark' ); ?>><?php _e( "Dark", 'wordpress-gallery-extra' ); ?></option>
										<option value="light"<?php selected( $options['lightbox_ilightbox']['skin'], 'light' ); ?>><?php _e( "Light", 'wordpress-gallery-extra' ); ?></option>
										<option value="smooth"<?php selected( $options['lightbox_ilightbox']['skin'], 'smooth' ); ?>><?php _e( "Smooth", 'wordpress-gallery-extra' ); ?></option>
										<option value="metro-black"<?php selected( $options['lightbox_ilightbox']['skin'], 'metro-black' ); ?>><?php _e( "Metro Black", 'wordpress-gallery-extra' ); ?></option>
										<option value="metro-white"<?php selected( $options['lightbox_ilightbox']['skin'], 'metro-white' ); ?>><?php _e( "Metro White", 'wordpress-gallery-extra' ); ?></option>
										<option value="mac"<?php selected( $options['lightbox_ilightbox']['skin'], 'mac' ); ?>><?php _e( "Mac", 'wordpress-gallery-extra' ); ?></option>
									</select>
								</td>

								<th><label for="wgee-ilightbox-direction"><?php _e( "Direction", 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<select name="ilightbox-direction" id="wgee-ilightbox-direction">
										<option value="horizontal"<?php selected( $options['lightbox_ilightbox']['direction'], 'horizontal' ); ?>><?php _e( "Horizontal", 'wordpress-gallery-extra' ); ?></option>
										<option value="vertical"<?php selected( $options['lightbox_ilightbox']['direction'], 'vertical' ); ?>><?php _e( "Vertical", 'wordpress-gallery-extra' ); ?></option>
									</select>
									<p class="description"><?php _e( "Sets direction for switching windows.", 'wordpress-gallery-extra' ); ?></p>
								</td>
							</tr>
							<tr>
								<th><label for="wgee-ilightbox-loop"><?php _e( "Loop", 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<div class="wgee-onoffswitch">
										<input type="checkbox" name="ilightbox-loop" class="wgee-onoffswitch-checkbox" id="wgee-ilightbox-loop"<?php checked( $options['lightbox_ilightbox']['loop'], 'yes' ); ?>>
										<label class="wgee-onoffswitch-label" for="wgee-ilightbox-loop">
											<div class="wgee-onoffswitch-inner">
												<div class="wgee-onoffswitch-active">ON</div>
												<div class="wgee-onoffswitch-inactive">OFF</div>
											</div>
											<div class="wgee-onoffswitch-switch"></div>
										</label>
									</div>
									<p class="description"><?php _e( "Enable infinite lightbox navigation.", 'wordpress-gallery-extra' ); ?></p>
								</td>

								<th><label for="wgee-ilightbox-carousel-mode"><?php _e( "Carousel Mode", 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<div class="wgee-onoffswitch">
										<input type="checkbox" name="ilightbox-carousel-mode" class="wgee-onoffswitch-checkbox" id="wgee-ilightbox-carousel-mode"<?php checked( $options['lightbox_ilightbox']['carousel_mode'], 'yes' ); ?>>
										<label class="wgee-onoffswitch-label" for="wgee-ilightbox-carousel-mode">
											<div class="wgee-onoffswitch-inner">
												<div class="wgee-onoffswitch-active">ON</div>
												<div class="wgee-onoffswitch-inactive">OFF</div>
											</div>
											<div class="wgee-onoffswitch-switch"></div>
										</label>
									</div>
									<p class="description"><?php _e( "Enable carousel style lightbox navigation.", 'wordpress-gallery-extra' ); ?></p>
								</td>
							</tr>
							<tr>
								<th><label for="wgee-ilightbox-deeplink"><?php _e( "Deeplinking", 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<div class="wgee-onoffswitch">
										<input type="checkbox" name="ilightbox-deeplink" class="wgee-onoffswitch-checkbox" id="wgee-ilightbox-deeplink"<?php checked( $options['lightbox_ilightbox']['deeplink'], 'yes' ); ?>>
										<label class="wgee-onoffswitch-label" for="wgee-ilightbox-deeplink">
											<div class="wgee-onoffswitch-inner">
												<div class="wgee-onoffswitch-active">ON</div>
												<div class="wgee-onoffswitch-inactive">OFF</div>
											</div>
											<div class="wgee-onoffswitch-switch"></div>
										</label>
									</div>
									<p class="description"><?php _e( "Enabling the hash linking images.", 'wordpress-gallery-extra' ); ?></p>
								</td>

								<th><label for="wgee-ilightbox-share-buttons"><?php _e( "Share Buttons", 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<div class="wgee-onoffswitch">
										<input type="checkbox" name="ilightbox-share-buttons" class="wgee-onoffswitch-checkbox" id="wgee-ilightbox-share-buttons"<?php checked( $options['lightbox_ilightbox']['share_buttons'], 'yes' ); ?>>
										<label class="wgee-onoffswitch-label" for="wgee-ilightbox-share-buttons">
											<div class="wgee-onoffswitch-inner">
												<div class="wgee-onoffswitch-active">ON</div>
												<div class="wgee-onoffswitch-inactive">OFF</div>
											</div>
											<div class="wgee-onoffswitch-switch"></div>
										</label>
									</div>
									<p class="description"><?php _e( "Display social buttons?", 'wordpress-gallery-extra' ); ?></p>
								</td>
							</tr>
							<tr>
								<th><label for="wgee-ilightbox-thumbnails"><?php _e( "Thumbnails", 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<div class="wgee-onoffswitch">
										<input type="checkbox" name="ilightbox-thumbnails" class="wgee-onoffswitch-checkbox" id="wgee-ilightbox-thumbnails"<?php checked( $options['lightbox_ilightbox']['thumbnails'], 'yes' ); ?>>
										<label class="wgee-onoffswitch-label" for="wgee-ilightbox-thumbnails">
											<div class="wgee-onoffswitch-inner">
												<div class="wgee-onoffswitch-active">ON</div>
												<div class="wgee-onoffswitch-inactive">OFF</div>
											</div>
											<div class="wgee-onoffswitch-switch"></div>
										</label>
									</div>
									<p class="description"><?php _e( "Show thumbnails navigation?", 'wordpress-gallery-extra' ); ?></p>
								</td>

								<th><label for="wgee-ilightbox-overlay-opacity"><?php _e( "Overlay Opacity", 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<input rel='number' data-options='min:0, max:1, step: 0.01' id='wgee-ilightbox-overlay-opacity' name='ilightbox-overlay-opacity' value='<?php echo esc_attr( $options['lightbox_ilightbox']['overlay_opacity'] ); ?>' />
									<p class="description"><?php _e( "Sets the opacity of the dimmed background of the page.", 'wordpress-gallery-extra' ); ?></p>
								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<div id="wgee-source-tab">
					<table class="wgee-form-table top-header">
						<tbody>
							<tr>
								<th colspan="2"><label><?php _e( "Source", 'wordpress-gallery-extra' ); ?></label></th>
							</tr>
							<tr>
								<td colspan="2">
									<div class="wgee-select-field source-types">
<?php
				foreach ( $WordPress_Gallery_Extra->SOURCES_TYPES as $key => $value ) {
?>
										<label for="wgee-source-<?php echo $key; ?>" class="<?php echo $key; ?>"><i></i><span><?php echo $value['name']; ?></span></label>
										<input type="radio" name="source" id="wgee-source-<?php echo $key; ?>" value="<?php echo $key; ?>"<?php checked( $options['source']['source'], $key ); ?> />
<?php
				}
?>
									</div>
									<p class="description"><?php _e( "Select the type of content to display inside the grid.", 'wordpress-gallery-extra' ); ?></p>
								</td>
							</tr>
							<tr>
								<td colspan="4" style="height: 0; font-size: 0;">&nbsp;</td>
							</tr>
							<tr>
								<th colspan="2"><label for="wgee-item-number"><?php _e( 'Item Number', 'wordpress-gallery-extra' ); ?></label></th>
							</tr>
							<tr>
								<td colspan="2">
									<input rel='number' data-options='min:-1' id='wgee-item-number' name='limit' value="<?php echo esc_attr( $options['source']['item_number'] ); ?>" />
									<p class="description"><?php _e( "Enter the number of items to load inside the grid.", 'wordpress-gallery-extra' ); ?></p>
								</td>
							</tr>

							<tr>
								<td colspan="4" style="height: 0; font-size: 0;">&nbsp;</td>
							</tr>
						</tbody>
					</table>

					<table class="wgee-form-table top-header field" rel="filter">
						<tbody>
							<tr class="field" rel="post_types">
								<th><label for="wgee-post-types"><?php _e( "Post Type(s)", 'wordpress-gallery-extra' ); ?></label></th>
								<th><label for="wgee-post-status"><?php _e( "Post Status", 'wordpress-gallery-extra' ); ?></label></th>
							</tr>
							<tr class="field" rel="post_status">
								<td>
									<?php
										$WordPress_Gallery_Extra->create_dropdown( array(
											'options' => parent::get_public_post_types(),
											'selected' => $options['source']['post_types'],
											'id' => 'wgee-post-types',
											'name' => 'post-types',
											'multiple' => true
										) );
									?>
									<p class="description"><?php _e( "Select one or several post type to display inside the current grid.", 'wordpress-gallery-extra' ); ?></p>
								</td>
								<td>
									<?php
										$WordPress_Gallery_Extra->create_dropdown( array(
											'options' => array(
												'any' => __( 'Any', 'wordpress-gallery-extra' ),
												'publish' => __( 'Publish', 'wordpress-gallery-extra' ),
												'pending' => __( 'Pending', 'wordpress-gallery-extra' ),
												'draft' => __( 'Draft', 'wordpress-gallery-extra' ),
												'auto-draft' => __( 'Auto Draft', 'wordpress-gallery-extra' ),
												'future' => __( 'Future', 'wordpress-gallery-extra' ),
												'private' => __( 'Private', 'wordpress-gallery-extra' ),
												'inherit' => __( 'Inherit', 'wordpress-gallery-extra' ),
												'trash' => __( 'Trash', 'wordpress-gallery-extra' )
											),
											'selected' => $options['source']['post_status'],
											'id' => 'wgee-post-status',
											'name' => 'post-status',
											'multiple' => true
										) );
									?>
									<p class="description"><?php _e( "Show posts associated with certain status.", 'wordpress-gallery-extra' ); ?></p>
								</td>
							</tr>

							<tr>
								<td colspan="4" style="height: 0; font-size: 0;">&nbsp;</td>
							</tr>

							<tr class="field" rel="taxonomies">
								<th colspan="2"><label for="wgee-taxonomies"><?php _e( "Categories/Taxonomies", 'wordpress-gallery-extra' ); ?></label></th>
							</tr>
							<tr class="field" rel="taxonomies">
								<td colspan="2">
									<div class="wgee-inside-table">
										<table class="wgee-form-table">
											<tbody>
												<tr>
													<th><label for="wgee-taxonomies" title="<?php esc_attr_e( "Select taxonomy term(s) from the current post type(s).", 'wordpress-gallery-extra' ); ?>"><?php _e( "Terms", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgee-taxonomies-relation" title="<?php esc_attr_e( "The logical relationship between each taxonomy term when there is more than one.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Relation", 'wordpress-gallery-extra' ); ?></label></th>
												</tr>
												<tr>
													<td>
														<?php
															$WordPress_Gallery_Extra->create_dropdown( array(
																'options' => array(),
																'selected' => $options['source']['taxonomies'],
																'id' => 'wgee-taxonomies',
																'name' => 'taxonomies',
																'multiple' => true
															) );
														?>
													</td>
													<td>
														<select name="taxonomies-relation" id="wgee-taxonomies-relation">
															<option value="OR"<?php selected( $options['source']['taxonomies_relation'], 'OR' ); ?>><?php _e( "Or", 'wordpress-gallery-extra' ); ?></option>
															<option value="AND"<?php selected( $options['source']['taxonomies_relation'], 'AND' ); ?>><?php _e( "And", 'wordpress-gallery-extra' ); ?></option>
														</select>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</td>
							</tr>

							<tr>
								<td colspan="4" style="height: 0; font-size: 0;">&nbsp;</td>
							</tr>

							<tr class="field" rel="authors">
								<th colspan="2"><label for="wgee-authors"><?php _e( "Author(s)", 'wordpress-gallery-extra' ); ?></label></th>
							</tr>
							<tr class="field" rel="authors">
								<td colspan="2">
									<div class="wgee-inside-table">
										<table class="wgee-form-table">
											<tbody>
												<tr>
													<th><label for="wgee-authors"><?php _e( "Terms", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgee-authors-relation" title="<?php esc_attr_e( "Include or Exclude Posts Belonging to selected Author(s)", 'wordpress-gallery-extra' ); ?>"><?php _e( "Relation", 'wordpress-gallery-extra' ); ?></label></th>
												</tr>
												<tr>
													<td>
														<?php
															$WordPress_Gallery_Extra->create_dropdown( array(
																'options' => self::get_all_authors(),
																'selected' => $options['source']['authors'],
																'id' => 'wgee-authors',
																'name' => 'authors',
																'multiple' => true
															) );
														?>
														<p class="description"><?php _e( "Select author(s) from the current post type(s).", 'wordpress-gallery-extra' ); ?></p>
														<div class="description">
															<strong><?php _e( "Note:", 'wordpress-gallery-extra' ); ?></strong>
															<ol>
																<li><?php _e( "If no author selected then all authors will be displayed", 'wordpress-gallery-extra' ); ?></li>
															</ol>
														</div>
													</td>
													<td>
														<select name="authors-relation" id="wgee-authors-relation">
															<option value="in"<?php selected( $options['source']['authors_relation'], 'in' ); ?>><?php _e( "Include", 'wordpress-gallery-extra' ); ?></option>
															<option value="not_in"<?php selected( $options['source']['authors_relation'], 'not_in' ); ?>><?php _e( "Exclude", 'wordpress-gallery-extra' ); ?></option>
														</select>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="4" style="height: 0; font-size: 0;">&nbsp;</td>
							</tr>
							<tr>
								<th class="field" rel="exclude_posts"><label for="wgee-exclude-posts"><?php _e( "Exclude Post(s)", 'wordpress-gallery-extra' ); ?></label></th>
								<th class="field" rel="include_posts"><label for="wgee-include-posts"><?php _e( "Include Post(s)", 'wordpress-gallery-extra' ); ?></label></th>
							</tr>
							<tr>
								<td class="field" rel="exclude_posts">
									<input type="text" class="full-width" id="wgee-exclude-posts" name="exclude-posts" value="<?php echo esc_attr( $options['source']['exclude_posts'] ); ?>" /><br>
									<p class="description"><?php _e( "Enter post ID(s) to exclude from the current source. Add post IDs separated by a comma (e.g: 43, 7, 99, 23, 76, 2).", 'wordpress-gallery-extra' ); ?></p>
								</td>
								<td class="field" rel="include_posts">
									<input type="text" class="full-width" id="wgee-include-posts" name="include-posts" value="<?php echo esc_attr( $options['source']['include_posts'] ); ?>" /><br>
									<p class="description"><?php _e( "Display only the specific post(s). Add post IDs separated by a comma (e.g: 43, 7, 99, 23, 76, 2).", 'wordpress-gallery-extra' ); ?></p>
								</td>
							</tr>

							<tr>
								<td colspan="4" style="height: 0; font-size: 0;">&nbsp;</td>
							</tr>

							<tr class="field" rel="ordering">
								<th colspan="2"><label><?php _e( "Ordering", 'wordpress-gallery-extra' ); ?></label></th>
							</tr>
							<tr class="field" rel="ordering">
								<td colspan="2">
									<div class="wgee-inside-table">
										<table class="wgee-form-table">
											<tbody>
												<tr>
													<th><label for="wgee-order" title="<?php esc_attr_e( "Designates the ascending or descending order of the retrieved posts sort", 'wordpress-gallery-extra' ); ?>"><?php _e( "Order", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgee-orderby" title="<?php esc_attr_e( "Sort retrieved posts.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Order By", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgee-orderby-fallback" title="<?php esc_attr_e( "Fallback to sort retrieved posts.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Order By Fallback", 'wordpress-gallery-extra' ); ?></label></th>
													<th class="field" rel="ordering-meta-key"><label for="wgee-order-meta-key" title="<?php esc_attr_e( "Enter a meta key name to order by a meta key value", 'wordpress-gallery-extra' ); ?>"><?php _e( "Meta Key", 'wordpress-gallery-extra' ); ?></label></th>
												</tr>
												<tr>
													<td>
														<select name="order" id="wgee-order">
															<option value="ASC"<?php selected( $options['source']['ordering']['order'], 'ASC' ); ?>><?php _e( "Ascending", 'wordpress-gallery-extra' ); ?></option>
															<option value="DESC"<?php selected( $options['source']['ordering']['order'], 'DESC' ); ?>><?php _e( "Descending", 'wordpress-gallery-extra' ); ?></option>
														</select>
													</td>
													<td>
														<?php
															$WordPress_Gallery_Extra->create_dropdown( array(
																'options' => $ordering_order_by_options,
																'selected' => $options['source']['ordering']['order_by'],
																'id' => 'wgee-orderby',
																'name' => 'orderby'
															) );
														?>
													</td>
													<td>
														<?php
															$WordPress_Gallery_Extra->create_dropdown( array(
																'options' => $ordering_order_by_options,
																'selected' => $options['source']['ordering']['order_by_fallback'],
																'id' => 'wgee-orderby-fallback',
																'name' => 'orderby-fallback'
															) );
														?>
													</td>
													<td class="field" rel="ordering-meta-key">
														<input type="text" id="wgee-order-meta-key" name="order-meta-key" value="<?php echo esc_attr( $options['source']['ordering']['meta_key'] ); ?>" />
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<div id="wgee-display-tab">
					<table class="wgee-form-table">
						<tbody>
							<tr class="field" rel="thumbnail_size">
								<th><label for="wgee-size"><?php _e( 'Thumbnail Size', 'wordpress-gallery-extra' ); ?></label></th>
								<td>
<?
	$thumbnails_sizes = $WordPress_Gallery_Extra->get_image_sizes();
	$possible_sizes_names = apply_filters( 'image_size_names_choose', array(
		'thumbnail'       => __('Thumbnail'),
		'medium'          => __('Medium'),
		'large'           => __('Large')
	) );
?>
									<select name="size" id="wgee-size">
<?php
	foreach ( $thumbnails_sizes as $key => $size ) {
		$size_name = isset( $possible_sizes_names[$key] ) ? $possible_sizes_names[$key] : $key;
?>
										<option value="<?php echo $key; ?>"<?php selected( $options['thumbnail_size'], $key ); ?>><?php echo $size_name; ?></option>
<?php
	}
?>
									</select>
									<p class="description"><?php _e( "Select a size for image in the grid.", 'wordpress-gallery-extra' ); ?> <?php printf( __( "You can also manage image sizes in <a target='_blank' href='%s'>Image Sizes Settings</a>.", 'wordpress-gallery-extra' ), admin_url( 'admin.php?page=wgextra_images_sizes' ) ); ?></p>
								</td>
							</tr>
							<tr class="field" rel="thumbnail_ratio">
								<th><label><?php _e( 'Thumbnail Ratio', 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<div class="wgee-switch-field">
										<label for="wgee-thumbnail-ratio-default"><?php _e( "Default", 'wordpress-gallery-extra' ); ?></label>
										<input type="radio" name="thumbnail-ratio" id="wgee-thumbnail-ratio-default" value="default"<?php checked( $options['thumbnail_ratio']['type'], 'default' ); ?> />
										<label for="wgee-thumbnail-ratio-manual"><?php _e( "Manual", 'wordpress-gallery-extra' ); ?></label>
										<input type="radio" name="thumbnail-ratio" id="wgee-thumbnail-ratio-manual" value="manual"<?php checked( $options['thumbnail_ratio']['type'], 'manual' ); ?> />
									</div>
									<div class="clear"></div>
									<div class="wgee-inside-table field" rel="thumbnail_ratio_manual">
										<table class="wgee-form-table">
											<tbody>
												<tr>
													<th><label for="wgee-fixed-ratio-width" title="<?php esc_attr_e( "Correspond to the ratio between width and height (X:Y) (e.g: 4:3 or 16:9 format)", 'wordpress-gallery-extra' ); ?>"><?php _e( "Ratio", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgee-fixed-ratio-force" title="<?php esc_attr_e( "This option will override all thumbnail sizes set in each post/item", 'wordpress-gallery-extra' ); ?>"><?php _e( "Force Thumbnail Sizes", 'wordpress-gallery-extra' ); ?></label></th>
												</tr>
												<tr>
													<td>
														<input rel='number' id='wgee-fixed-ratio-width' name='fixed-ratio-width' value='<?php echo esc_attr( $options['thumbnail_ratio']['size'][0] ); ?>' />
														&nbsp;&nbsp;:&nbsp;&nbsp;
														<input rel='number' id='wgee-fixed-ratio-height' name='fixed-ratio-height' value='<?php echo esc_attr( $options['thumbnail_ratio']['size'][1] ); ?>' />
													</td>
													<td>
														<div class="wgee-onoffswitch">
															<input type="checkbox" name="fixed-ratio-force" class="wgee-onoffswitch-checkbox" id="wgee-fixed-ratio-force"<?php checked( $options['thumbnail_ratio']['force'], 'yes' ); ?>>
															<label class="wgee-onoffswitch-label" for="wgee-fixed-ratio-force">
																<div class="wgee-onoffswitch-inner">
																	<div class="wgee-onoffswitch-active">ON</div>
																	<div class="wgee-onoffswitch-inactive">OFF</div>
																</div>
																<div class="wgee-onoffswitch-switch"></div>
															</label>
														</div>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</td>
							</tr>
							<tr class="field" rel="columns">
								<th><label for="wgee-columns"><?php _e( 'Columns', 'wordpress-gallery-extra' ); ?></label></th>
								<td colspan="3">
									<input rel='range' data-options="max: 9, min: 1, type: 'circle', pips: {}, forceTip: true" id='wgee-columns' name='columns' value='<?php echo esc_attr( $options['columns'] ); ?>' />
									<p class="description"><?php _e( "Set the number of columns you would like to have in your gallery.", 'wordpress-gallery-extra' ); ?></p>
								</td>
							</tr>
							<tr class="field" rel="last_row">
								<th><label><?php _e( 'Last row', 'wordpress-gallery-extra' ); ?></label></th>
								<td colspan="3">
									<div class="wgee-switch-field">
										<label for="wgee-last-row-1"><?php _e( "Align left", 'wordpress-gallery-extra' ); ?></label>
										<input type="radio" name="last-row" id="wgee-last-row-1" value="nojustify"<?php checked( $options['last_row'], 'nojustify' ); ?> />
										<label for="wgee-last-row-2"><?php _e( "Align center", 'wordpress-gallery-extra' ); ?></label>
										<input type="radio" name="last-row" id="wgee-last-row-2" value="center"<?php checked( $options['last_row'], 'center' ); ?> />
										<label for="wgee-last-row-3"><?php _e( "Align right", 'wordpress-gallery-extra' ); ?></label>
										<input type="radio" name="last-row" id="wgee-last-row-3" value="right"<?php checked( $options['last_row'], 'right' ); ?> />
										<label for="wgee-last-row-4"><?php _e( "Justify", 'wordpress-gallery-extra' ); ?></label>
										<input type="radio" name="last-row" id="wgee-last-row-4" value="justify"<?php checked( $options['last_row'], 'justify' ); ?> />
										<label for="wgee-last-row-5"><?php _e( "Hide", 'wordpress-gallery-extra' ); ?></label>
										<input type="radio" name="last-row" id="wgee-last-row-5" value="hide"<?php checked( $options['last_row'], 'hide' ); ?> />
									</div>
									<p class="description"><?php _e( "Decide how to position the last row of images. Default the last row images are aligned to the left. You can also hide the row if it can't be justified and aligned to the center or to the right.", 'wordpress-gallery-extra' ); ?></p>
								</td>
							</tr>
							<tr class="field" rel="alignment">
								<th><label><?php _e( 'Alignment', 'wordpress-gallery-extra' ); ?></label></th>
								<td colspan="3">
									<div class="wgee-switch-field">
										<label for="wgee-alignment-1"><?php _e( "Left", 'wordpress-gallery-extra' ); ?></label>
										<input type="radio" name="alignment" id="wgee-alignment-1" value="left"<?php checked( $options['alignment'], 'left' ); ?> />
										<label for="wgee-alignment-2"><?php _e( "Center", 'wordpress-gallery-extra' ); ?></label>
										<input type="radio" name="alignment" id="wgee-alignment-2" value="center"<?php checked( $options['alignment'], 'center' ); ?> />
										<label for="wgee-alignment-3"><?php _e( "Right", 'wordpress-gallery-extra' ); ?></label>
										<input type="radio" name="alignment" id="wgee-alignment-3" value="right"<?php checked( $options['alignment'], 'right' ); ?> />
									</div>
									<p class="description"><?php _e( "The horizontal alignment of the thumbnails inside the gallery.", 'wordpress-gallery-extra' ); ?></p>
								</td>
							</tr>
							<tr class="field" rel="vertical_alignment">
								<th><label><?php _e( 'Vertical Alignment', 'wordpress-gallery-extra' ); ?></label></th>
								<td colspan="3">
									<div class="wgee-switch-field">
										<label for="wgee-vertical-alignment-top"><?php _e( "Top", 'wordpress-gallery-extra' ); ?></label>
										<input type="radio" name="vertical-alignment" id="wgee-vertical-alignment-top" value="top"<?php checked( $options['vertical_alignment'], 'top' ); ?> />
										<label for="wgee-vertical-alignment-middle"><?php _e( "Middle", 'wordpress-gallery-extra' ); ?></label>
										<input type="radio" name="vertical-alignment" id="wgee-vertical-alignment-middle" value="middle"<?php checked( $options['vertical_alignment'], 'middle' ); ?> />
										<label for="wgee-vertical-alignment-bottom"><?php _e( "Bottom", 'wordpress-gallery-extra' ); ?></label>
										<input type="radio" name="vertical-alignment" id="wgee-vertical-alignment-bottom" value="bottom"<?php checked( $options['vertical_alignment'], 'bottom' ); ?> />
									</div>
									<p class="description"><?php _e( "The vertical alignment of the thumbnails inside the gallery.", 'wordpress-gallery-extra' ); ?></p>
								</td>
							</tr>
							<tr class="field" rel="row_height">
								<th><label for="wgee-row-height"><?php _e( 'Row Height', 'wordpress-gallery-extra' ); ?></label></th>
								<td colspan="3">
									<input rel='number' id='wgee-row-height' name='row-height' value='<?php echo esc_attr( $options['row_height'] ); ?>' />
									<p class="description"><?php _e( "The preferred height of your gallery rows in pixel.", 'wordpress-gallery-extra' ); ?></p>
								</td>
							</tr>
							<tr class="field" rel="max_row_height">
								<th><label for="wgee-max-row-height"><?php _e( 'Max Row Height', 'wordpress-gallery-extra' ); ?></label></th>
								<td colspan="3">
								<?php
									$max_row_height = $WordPress_Gallery_Extra->split_number( $options['max_row_height'] );
								?>
									<input rel='number' data-options='min:-1' id='wgee-max-row-height' name='max-row-height' value='<?php echo esc_attr( $max_row_height['number'] ); ?>' />
									<select id="wgee-max-row-height-unit" name="max-row-height-unit">
										<option value="">px</option>
										<option value="%"<?php selected( $max_row_height['unit'], '%' ); ?>>%</option>
									</select><br />
									<p class="description"><?php _e( "A number (e.g 200) which specifies the maximum row height in pixels. Use <code>-1px</code> to remove the limit of the maximum row height. Alternatively, use a percentage (e.g. 200% which means that the row height cannot exceed <code>2 * Row Height</code>)", 'wordpress-gallery-extra' ); ?></p>
								</td>
							</tr>
							<tr class="field" rel="mosaic_type">
								<th><label><?php _e( 'Mosaic Type', 'wordpress-gallery-extra' ); ?></label></th>
								<td colspan="3">
									<div class="wgee-switch-field">
										<label for="wgee-mosaic-type-auto"><?php _e( "Auto", 'wordpress-gallery-extra' ); ?></label>
										<input type="radio" name="mosaic-type" id="wgee-mosaic-type-auto" value="auto"<?php checked( $options['mosaic_type'], 'auto' ); ?> />
										<label for="wgee-mosaic-type-manual"><?php _e( "Manual", 'wordpress-gallery-extra' ); ?></label>
										<input type="radio" name="mosaic-type" id="wgee-mosaic-type-manual" value="manual"<?php checked( $options['mosaic_type'], 'manual' ); ?> />
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<div id="wgee-styling-tab">
					<table class="wgee-form-table">
						<tbody>
							<tr class="field" rel="placeholder">
								<th><label for="wgee-placeholder"><?php _e( "Use Placeholder", 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<div class="wgee-onoffswitch">
										<input type="checkbox" name="placeholder" class="wgee-onoffswitch-checkbox" id="wgee-placeholder"<?php checked( $options['styles']['use_placeholder'], 'yes' ); ?>>
										<label class="wgee-onoffswitch-label" for="wgee-placeholder">
											<div class="wgee-onoffswitch-inner">
												<div class="wgee-onoffswitch-active">ON</div>
												<div class="wgee-onoffswitch-inactive">OFF</div>
											</div>
											<div class="wgee-onoffswitch-switch"></div>
										</label>
									</div><br />
									<div class="wgee-inside-table field" rel="use_placeholder">
										<table class="wgee-form-table">
											<thead>
												<tr>
													<th><label for="wgee-placeholder-overlay"><?php _e( "Overlay", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgee-placeholder-readable-caption"><?php _e( "Readable Caption Text Color", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgee-placeholder-background"><?php _e( "Image Background", 'wordpress-gallery-extra' ); ?></label></th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>
														<div class="wgee-onoffswitch">
															<input type="checkbox" name="placeholder-overlay" class="wgee-onoffswitch-checkbox" id="wgee-placeholder-overlay"<?php checked( $options['styles']['placeholder']['overlay'], 'yes' ); ?>>
															<label class="wgee-onoffswitch-label" for="wgee-placeholder-overlay">
																<div class="wgee-onoffswitch-inner">
																	<div class="wgee-onoffswitch-active">ON</div>
																	<div class="wgee-onoffswitch-inactive">OFF</div>
																</div>
																<div class="wgee-onoffswitch-switch"></div>
															</label>
														</div>
													</td>
													<td>
														<div class="wgee-onoffswitch">
															<input type="checkbox" name="placeholder-readable-caption" class="wgee-onoffswitch-checkbox" id="wgee-placeholder-readable-caption"<?php checked( $options['styles']['placeholder']['readable_caption'], 'yes' ); ?>>
															<label class="wgee-onoffswitch-label" for="wgee-placeholder-readable-caption">
																<div class="wgee-onoffswitch-inner">
																	<div class="wgee-onoffswitch-active">ON</div>
																	<div class="wgee-onoffswitch-inactive">OFF</div>
																</div>
																<div class="wgee-onoffswitch-switch"></div>
															</label>
														</div>
													</td>
													<td>
														<div class="wgee-onoffswitch">
															<input type="checkbox" name="placeholder-background" class="wgee-onoffswitch-checkbox" id="wgee-placeholder-background"<?php checked( $options['styles']['placeholder']['background'], 'yes' ); ?>>
															<label class="wgee-onoffswitch-label" for="wgee-placeholder-background">
																<div class="wgee-onoffswitch-inner">
																	<div class="wgee-onoffswitch-active">ON</div>
																	<div class="wgee-onoffswitch-inactive">OFF</div>
																</div>
																<div class="wgee-onoffswitch-switch"></div>
															</label>
														</div>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</td>
							</tr>

							<tr class="field" rel="margin">
								<th><label for="wgee-margin"><?php _e( 'Spacing', 'wordpress-gallery-extra' ); ?></label></th>
								<td class="field" rel="margin">
									<input rel='number' id='wgee-margin' name='margin' value='<?php echo esc_attr( $options['styles']['margin'] ); ?>' /><br />
									<p class="description"><?php _e( "The spacing or gap between thumbnails in the gallery.", 'wordpress-gallery-extra' ); ?></p>
								</td>
							</tr>

							<tr class="field" rel="border">
								<th><label for="wgee-border"><?php _e( "Border", 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<div class="wgee-onoffswitch">
										<input type="checkbox" name="border" class="wgee-onoffswitch-checkbox" id="wgee-border"<?php checked( $options['styles']['has_border'], 'yes' ); ?>>
										<label class="wgee-onoffswitch-label" for="wgee-border">
											<div class="wgee-onoffswitch-inner">
												<div class="wgee-onoffswitch-active">ON</div>
												<div class="wgee-onoffswitch-inactive">OFF</div>
											</div>
											<div class="wgee-onoffswitch-switch"></div>
										</label>
									</div><br />
									<div class="wgee-inside-table field" rel="has_border">
										<table class="wgee-form-table">
											<thead>
												<tr>
													<th><label for="wgee-border-radius"><?php _e( "Radius", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgee-border-weight"><?php _e( "Weight", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgee-border-style"><?php _e( "Type", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgee-border-color"><?php _e( "Color", 'wordpress-gallery-extra' ); ?></label></th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>
														<input rel='number' id='wgee-border-radius' name='border-radius' value='<?php echo esc_attr( $options['styles']['border']['radius'] ); ?>' />
													</td>
													<td>
														<input rel='number' id='wgee-border-weight' name='border-weight' value='<?php echo esc_attr( $options['styles']['border']['weight'] ); ?>' />
													</td>
													<td>
														<select id="wgee-border-style" name="border-style">
															<option value="none"<?php selected( $options['styles']['border']['style'], 'none' ); ?>><?php _e( "None", 'wordpress-gallery-extra' ); ?></option>
															<option value="solid"<?php selected( $options['styles']['border']['style'], 'solid' ); ?>><?php _e( "Solid", 'wordpress-gallery-extra' ); ?></option>
															<option value="dotted"<?php selected( $options['styles']['border']['style'], 'dotted' ); ?>><?php _e( "Dotted", 'wordpress-gallery-extra' ); ?></option>
															<option value="dashed"<?php selected( $options['styles']['border']['style'], 'dashed' ); ?>><?php _e( "Dashed", 'wordpress-gallery-extra' ); ?></option>
															<option value="double"<?php selected( $options['styles']['border']['style'], 'double' ); ?>><?php _e( "Double", 'wordpress-gallery-extra' ); ?></option>
														</select>
													</td>
													<td>
														<input rel="colorpicker" id="wgee-border-color" name="border-color" value="<?php echo esc_attr( $options['styles']['border']['color'] ); ?>" />
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</td>
							</tr>

							<tr class="field" rel="shadow">
								<th><label for="wgee-shadow"><?php _e( "Shadow", 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<div class="wgee-onoffswitch">
										<input type="checkbox" name="shadow" class="wgee-onoffswitch-checkbox" id="wgee-shadow"<?php checked( $options['styles']['has_shadow'], 'yes' ); ?>>
										<label class="wgee-onoffswitch-label" for="wgee-shadow">
											<div class="wgee-onoffswitch-inner">
												<div class="wgee-onoffswitch-active">ON</div>
												<div class="wgee-onoffswitch-inactive">OFF</div>
											</div>
											<div class="wgee-onoffswitch-switch"></div>
										</label>
									</div><br />
									<div class="wgee-inside-table field" rel="has_shadow">
										<table class="wgee-form-table">
											<thead>
												<tr>
													<th><label for="wgee-shadow-x"><?php _e( "X-offset", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgee-shadow-y"><?php _e( "Y-offset", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgee-shadow-blur"><?php _e( "Blur", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgee-shadow-spread"><?php _e( "Spread", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgee-shadow-color"><?php _e( "Color", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgee-shadow-inset"><?php _e( "Inset", 'wordpress-gallery-extra' ); ?></label></th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>
														<input rel='number' id='wgee-shadow-x' name='shadow-x' value='<?php echo esc_attr( $options['styles']['shadow']['x'] ); ?>' />
													</td>
													<td>
														<input rel='number' id='wgee-shadow-y' name='shadow-y' value='<?php echo esc_attr( $options['styles']['shadow']['y'] ); ?>' />
													</td>
													<td>
														<input rel='number' id='wgee-shadow-blur' name='shadow-blur' value='<?php echo esc_attr( $options['styles']['shadow']['blur'] ); ?>' />
													</td>
													<td>
														<input rel='number' id='wgee-shadow-spread' name='shadow-spread' value='<?php echo esc_attr( $options['styles']['shadow']['spread'] ); ?>' />
													</td>
													<td>
														<input rel="colorpicker" id="wgee-shadow-color" name="shadow-color" value="<?php echo esc_attr( $options['styles']['shadow']['color'] ); ?>" />
													</td>
													<td>
														<div class="wgee-onoffswitch">
															<input type="checkbox" name="shadow-inset" class="wgee-onoffswitch-checkbox" id="wgee-shadow-inset"<?php checked( $options['styles']['shadow']['inset'], 'yes' ); ?>>
															<label class="wgee-onoffswitch-label" for="wgee-shadow-inset">
																<div class="wgee-onoffswitch-inner">
																	<div class="wgee-onoffswitch-active">ON</div>
																	<div class="wgee-onoffswitch-inactive">OFF</div>
																</div>
																<div class="wgee-onoffswitch-switch"></div>
															</label>
														</div>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</td>
							</tr>

							<tr class="field" rel="icon">
								<th><label for="wgee-icon"><?php _e( "Icon", 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<div class="wgee-onoffswitch">
										<input type="checkbox" name="icon" class="wgee-onoffswitch-checkbox" id="wgee-icon"<?php checked( $options['styles']['has_icon'], 'yes' ); ?>>
										<label class="wgee-onoffswitch-label" for="wgee-icon">
											<div class="wgee-onoffswitch-inner">
												<div class="wgee-onoffswitch-active">ON</div>
												<div class="wgee-onoffswitch-inactive">OFF</div>
											</div>
											<div class="wgee-onoffswitch-switch"></div>
										</label>
									</div><br />
									<div class="wgee-inside-table field" rel="has_icon">
										<table class="wgee-form-table">
											<tbody>
												<tr>
													<th><label title="<?php esc_attr_e( "Choose which icon is shown for your thumbnails.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Icon", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgee-icon-size"><?php _e( "Size", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgee-icon-color"><?php _e( "Color", 'wordpress-gallery-extra' ); ?></label></th>
												</tr>
												<tr>
													<td rowspan="7">
														<div class="wgee-select-field icons">
															<label for="wgee-icon-name-none"><i>&nbsp;&nbsp;&nbsp;&nbsp;</i></label>
															<input type="radio" name="icon-name" id="wgee-icon-name-none" value=""<?php checked( $options['styles']['icon']['icon'], '' ); ?> />
<?php
				$icons = isset( $icons['icons'] ) ? $icons['icons'] : array();
				$i = 0;
				foreach ( $icons as $icon ) {
					$id = $icon['properties']['name'];
?>
															<label for="wgee-icon-name-<?php echo $i; ?>"><i class="wgextra-icon wgextra-icon-<?php echo $id; ?>"></i></label>
															<input type="radio" name="icon-name" id="wgee-icon-name-<?php echo $i; ?>" value="<?php echo $id; ?>"<?php checked( $options['styles']['icon']['icon'], $id ); ?> />
<?php
					$i++;
				}
?>
														</div>
													</td>
													<td height="40">
														<input rel='number' id='wgee-icon-size' name='icon-size' value="<?php echo esc_attr( $options['styles']['icon']['size'] ); ?>" />
													</td>
													<td height="40">
														<input rel="colorpicker" id="wgee-icon-color" name="icon-color" value="<?php echo esc_attr( $options['styles']['icon']['color'] ); ?>" />
													</td>
												</tr>
												<tr>
													<td colspan="3"></td>
												</tr>
												<tr>
													<th><label for="wgee-icon-visibility"><?php _e( "Visiblity", 'wordpress-gallery-extra' ); ?></label></th>
													<th class="align-bottom" height="21"><label for="wgee-icon-transition-speed" title="<?php esc_attr_e( "Speed of the enter/exit transition in milliseconds.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Speed", 'wordpress-gallery-extra' ); ?></label></th>
												</tr>
												<tr>
													<td height="40">
														<?php
															$WordPress_Gallery_Extra->create_dropdown( array(
																'options' => $icon_visibilities,
																'selected' => $options['styles']['icon']['visibility'],
																'id' => 'wgee-icon-visibility',
																'name' => 'icon-visibility'
															) );
														?>
													</td>
													<td class="align-bottom" height="40">
														<input rel='number' data-options='min:0, step:50' id='wgee-icon-transition-speed' name='icon-transition-speed' value="<?php echo esc_attr( $options['styles']['icon']['transition']['speed'] ); ?>" />
													</td>
												</tr>
												<tr>
													<td colspan="3"></td>
												</tr>
												<tr>
													<th class="align-bottom" height="21"><label for="wgee-icon-transition-delay" title="<?php esc_attr_e( "Defines how long to wait and the transition actually begins.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Delay", 'wordpress-gallery-extra' ); ?></label></th>
													<th class="align-bottom" height="21"><label for="wgee-icon-transition-easing" title="<?php esc_attr_e( "Specify the rate of transition over time.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Easing", 'wordpress-gallery-extra' ); ?></label></th>
												</tr>
												<tr>
													<td class="align-bottom" height="40">
														<input rel='number' data-options='min:0, step:50' id='wgee-icon-transition-delay' name='icon-transition-delay' value="<?php echo esc_attr( $options['styles']['icon']['transition']['delay'] ); ?>" />
													</td>
													<td class="align-bottom" height="40">
														<?php
															$WordPress_Gallery_Extra->create_dropdown( array(
																'options' => $css3_easings,
																'selected' => $options['styles']['icon']['transition']['easing'],
																'id' => 'wgee-icon-transition-easing',
																'name' => 'icon-transition-easing'
															) );
														?>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</td>
							</tr>

							<tr class="field" rel="caption">
								<th><label for="wgee-caption"><?php _e( "Caption", 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<div class="wgee-onoffswitch">
										<input type="checkbox" name="caption" class="wgee-onoffswitch-checkbox" id="wgee-caption"<?php checked( $options['styles']['has_caption'], 'yes' ); ?>>
										<label class="wgee-onoffswitch-label" for="wgee-caption">
											<div class="wgee-onoffswitch-inner">
												<div class="wgee-onoffswitch-active">ON</div>
												<div class="wgee-onoffswitch-inactive">OFF</div>
											</div>
											<div class="wgee-onoffswitch-switch"></div>
										</label>
									</div><br />
									<div class="wgee-inside-table field" rel="has_caption">
										<table class="wgee-form-table">
											<tbody>
												<tr>
													<th><label for="wgee-caption-color"><?php _e( "Color", 'wordpress-gallery-extra' ); ?></label></th>
													<th colspan="2"><label for="wgee-caption-position" title="<?php esc_attr_e( "Where the captions are displayed in relation to the thumbnail.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Position", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgee-caption-inset" title="<?php esc_attr_e( "Insert the caption into thumbnail holder?", 'wordpress-gallery-extra' ); ?>"><?php _e( "Inset", 'wordpress-gallery-extra' ); ?></label></th>
												</tr>
												<tr>
													<td>
														<input rel="colorpicker" id="wgee-caption-color" name="caption-color" value="<?php echo esc_attr( $options['styles']['caption']['color'] ); ?>" />
													</td>
													<td colspan="2">
														<select id="wgee-caption-position" name="caption-position">
															<option value="none"<?php selected( $options['styles']['caption']['position'], 'none' ); ?>><?php _e( "None", 'wordpress-gallery-extra' ); ?></option>
															<option value="top-left"<?php selected( $options['styles']['caption']['position'], 'top-left' ); ?>><?php _e( "Top Left", 'wordpress-gallery-extra' ); ?></option>
															<option value="top-center"<?php selected( $options['styles']['caption']['position'], 'top-center' ); ?>><?php _e( "Top Center", 'wordpress-gallery-extra' ); ?></option>
															<option value="top-right"<?php selected( $options['styles']['caption']['position'], 'top-right' ); ?>><?php _e( "Top Right", 'wordpress-gallery-extra' ); ?></option>
															<option value="middle-left"<?php selected( $options['styles']['caption']['position'], 'middle-left' ); ?>><?php _e( "Middle Left", 'wordpress-gallery-extra' ); ?></option>
															<option value="middle-center"<?php selected( $options['styles']['caption']['position'], 'middle-center' ); ?>><?php _e( "Middle Center", 'wordpress-gallery-extra' ); ?></option>
															<option value="middle-right"<?php selected( $options['styles']['caption']['position'], 'middle-right' ); ?>><?php _e( "Middle Right", 'wordpress-gallery-extra' ); ?></option>
															<option value="bottom-left"<?php selected( $options['styles']['caption']['position'], 'bottom-left' ); ?>><?php _e( "Bottom Left", 'wordpress-gallery-extra' ); ?></option>
															<option value="bottom-center"<?php selected( $options['styles']['caption']['position'], 'bottom-center' ); ?>><?php _e( "Bottom Center", 'wordpress-gallery-extra' ); ?></option>
															<option value="bottom-right"<?php selected( $options['styles']['caption']['position'], 'bottom-right' ); ?>><?php _e( "Bottom Right", 'wordpress-gallery-extra' ); ?></option>
														</select>
													</td>
													<td>
														<div class="wgee-onoffswitch">
															<input type="checkbox" name="caption-inset" class="wgee-onoffswitch-checkbox" id="wgee-caption-inset"<?php checked( $options['styles']['caption']['inset'], 'yes' ); ?>>
															<label class="wgee-onoffswitch-label" for="wgee-caption-inset">
																<div class="wgee-onoffswitch-inner">
																	<div class="wgee-onoffswitch-active">ON</div>
																	<div class="wgee-onoffswitch-inactive">OFF</div>
																</div>
																<div class="wgee-onoffswitch-switch"></div>
															</label>
														</div>
													</td>
												</tr>
												<tr>
													<td colspan="4">&nbsp;</td>
												</tr>
												<tr>
													<th><label for="wgee-caption-visibility"><?php _e( "Visiblity", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgee-caption-transition-speed" title="<?php esc_attr_e( "Speed of the enter/exit transition in milliseconds.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Speed", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgee-caption-transition-delay" title="<?php esc_attr_e( "Defines how long to wait and the transition actually begins.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Delay", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgee-caption-transition-easing" title="<?php esc_attr_e( "Specify the rate of transition over time.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Easing", 'wordpress-gallery-extra' ); ?></label></th>
												</tr>
												<tr>
													<td>
														<?php
															$WordPress_Gallery_Extra->create_dropdown( array(
																'options' => $caption_visibilities,
																'selected' => $options['styles']['caption']['visibility'],
																'id' => 'wgee-caption-visibility',
																'name' => 'caption-visibility'
															) );
														?>
													</td>
													<td>
														<input rel='number' data-options='min:0, step:50' id='wgee-caption-transition-speed' name='caption-transition-speed' value="<?php echo esc_attr( $options['styles']['caption']['transition']['speed'] ); ?>" />
													</td>
													<td>
														<input rel='number' data-options='min:0, step:50' id='wgee-caption-transition-delay' name='caption-transition-delay' value="<?php echo esc_attr( $options['styles']['caption']['transition']['delay'] ); ?>" />
													</td>
													<td>
														<?php
															$WordPress_Gallery_Extra->create_dropdown( array(
																'options' => $css3_easings,
																'selected' => $options['styles']['caption']['transition']['easing'],
																'id' => 'wgee-caption-transition-easing',
																'name' => 'caption-transition-easing'
															) );
														?>
													</td>
												</tr>
												<tr>
													<td colspan="4">&nbsp;</td>
												</tr>
												<tr>
													<th colspan="4"><label><?php _e( "Background", 'wordpress-gallery-extra' ); ?></label></th>
												</tr>
												<tr>
													<td colspan="4">
														<select name="caption-background" id="wgee-caption-background">
															<option value="none"<?php selected( $options['styles']['caption']['background']['type'], 'none' ); ?>><?php _e( "None", 'wordpress-gallery-extra' ); ?></option>
															<option value="solid"<?php selected( $options['styles']['caption']['background']['type'], 'solid' ); ?>><?php _e( "Solid", 'wordpress-gallery-extra' ); ?></option>
															<option value="gradient"<?php selected( $options['styles']['caption']['background']['type'], 'gradient' ); ?>><?php _e( "Gradient", 'wordpress-gallery-extra' ); ?></option>
														</select>
														<div class="clear"></div>
														<div class="wgee-inside-table field" rel="caption-background-solid">
															<table class="wgee-form-table">
																<thead>
																	<tr>
																		<th><label for="wgee-caption-background-color"><?php _e( "Color", 'wordpress-gallery-extra' ); ?></label></th>
																	</tr>
																</thead>
																<tbody>
																	<tr>
																		<td>
																			<input rel="colorpicker" id="wgee-caption-background-color" name="caption-background-color" value="<?php echo esc_attr( $options['styles']['caption']['background']['solid']['color'] ); ?>" />
																		</td>
																	</tr>
																</tbody>
															</table>
														</div>
														<div class="wgee-inside-table field" rel="caption-background-gradient">
															<table class="wgee-form-table">
																<thead>
																	<tr>
																		<th><label for="wgee-caption-background-start-color"><?php _e( "Start Color", 'wordpress-gallery-extra' ); ?></label></th>
																		<th><label for="wgee-caption-background-stop-color"><?php _e( "Stop Color", 'wordpress-gallery-extra' ); ?></label></th>
																		<th><label for="wgee-caption-background-orientation"><?php _e( "Orientation", 'wordpress-gallery-extra' ); ?></label></th>
																	</tr>
																</thead>
																<tbody>
																	<tr>
																		<td>
																			<input rel="colorpicker" id="wgee-caption-background-start-color" name="caption-background-start-color" value="<?php echo esc_attr( $options['styles']['caption']['background']['gradient']['start_color'] ); ?>" />
																		</td>
																		<td>
																			<input rel="colorpicker" id="wgee-caption-background-stop-color" name="caption-background-stop-color" value="<?php echo esc_attr( $options['styles']['caption']['background']['gradient']['stop_color'] ); ?>" />
																		</td>
																		<td>
																			<select id="wgee-caption-background-orientation" name="caption-background-orientation">
																				<option value="vertical"<?php selected( $options['styles']['caption']['background']['gradient']['orientation'], 'vertical' ); ?>><?php _e( "Vertical", 'wordpress-gallery-extra' ); ?></option>
																				<option value="horizontal"<?php selected( $options['styles']['caption']['background']['gradient']['orientation'], 'horizontal' ); ?>><?php _e( "Horizontal", 'wordpress-gallery-extra' ); ?></option>
																				<option value="radial"<?php selected( $options['styles']['caption']['background']['gradient']['orientation'], 'radial' ); ?>><?php _e( "Radial", 'wordpress-gallery-extra' ); ?></option>
																				<option value="diagonal_45"<?php selected( $options['styles']['caption']['background']['gradient']['orientation'], 'diagonal_45' ); ?>><?php _e( "Diagonal", 'wordpress-gallery-extra' ); ?> 45&#xB0;</option>
																				<option value="diagonal_n45"<?php selected( $options['styles']['caption']['background']['gradient']['orientation'], 'diagonal_n45' ); ?>><?php _e( "Diagonal", 'wordpress-gallery-extra' ); ?> -45&#xB0;</option>
																			</select>
																		</td>
																	</tr>
																</tbody>
															</table>
														</div>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</td>
							</tr>

							<tr class="field" rel="overlay">
								<th><label for="wgee-overlay"><?php _e( "Overlay", 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<div class="wgee-onoffswitch">
										<input type="checkbox" name="overlay" class="wgee-onoffswitch-checkbox" id="wgee-overlay"<?php checked( $options['styles']['has_overlay'], 'yes' ); ?>>
										<label class="wgee-onoffswitch-label" for="wgee-overlay">
											<div class="wgee-onoffswitch-inner">
												<div class="wgee-onoffswitch-active">ON</div>
												<div class="wgee-onoffswitch-inactive">OFF</div>
											</div>
											<div class="wgee-onoffswitch-switch"></div>
										</label>
									</div><br />
									<div class="wgee-inside-table field" rel="has_overlay">
										<table class="wgee-form-table">
											<tbody>
												<tr>
													<th><label for="wgee-overlay-visibility" title="<?php esc_attr_e( "Hover visibility type for overlays.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Visiblity", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgee-overlay-transition-speed" title="<?php esc_attr_e( "Speed of the enter/exit transition in milliseconds.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Speed", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgee-overlay-transition-delay" title="<?php esc_attr_e( "Defines how long to wait and the transition actually begins.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Delay", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgee-overlay-transition-easing" title="<?php esc_attr_e( "Specify the rate of transition over time.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Easing", 'wordpress-gallery-extra' ); ?></label></th>
												</tr>
												<tr>
													<td>
														<?php
															$WordPress_Gallery_Extra->create_dropdown( array(
																'options' => $overlay_visibilities,
																'selected' => $options['styles']['overlay']['visibility'],
																'id' => 'wgee-overlay-visibility',
																'name' => 'overlay-visibility'
															) );
														?>
													</td>
													<td>
														<input rel='number' data-options='min:0, step:50' id='wgee-overlay-transition-speed' name='overlay-transition-speed' value="<?php echo esc_attr( $options['styles']['overlay']['transition']['speed'] ); ?>" />
													</td>
													<td>
														<input rel='number' data-options='min:0, step:50' id='wgee-overlay-transition-delay' name='overlay-transition-delay' value="<?php echo esc_attr( $options['styles']['overlay']['transition']['delay'] ); ?>" />
													</td>
													<td>
														<?php
															$WordPress_Gallery_Extra->create_dropdown( array(
																'options' => $css3_easings,
																'selected' => $options['styles']['overlay']['transition']['easing'],
																'id' => 'wgee-overlay-transition-easing',
																'name' => 'overlay-transition-easing'
															) );
														?>
													</td>
												</tr>
												<tr>
													<td colspan="4">&nbsp;</td>
												</tr>
												<tr>
													<th colspan="4"><label><?php _e( "Background", 'wordpress-gallery-extra' ); ?></label></th>
												</tr>
												<tr>
													<td colspan="4">
														<select name="overlay-background" id="wgee-overlay-background">
															<option value="solid"<?php selected( $options['styles']['overlay']['background']['type'], 'solid' ); ?>><?php _e( "Solid", 'wordpress-gallery-extra' ); ?></option>
															<option value="gradient"<?php selected( $options['styles']['overlay']['background']['type'], 'gradient' ); ?>><?php _e( "Gradient", 'wordpress-gallery-extra' ); ?></option>
														</select>
														<div class="clear"></div>
														<div class="wgee-inside-table field" rel="overlay-background-solid">
															<table class="wgee-form-table">
																<thead>
																	<tr>
																		<th><label for="wgee-overlay-background-color"><?php _e( "Color", 'wordpress-gallery-extra' ); ?></label></th>
																	</tr>
																</thead>
																<tbody>
																	<tr>
																		<td>
																			<input rel="colorpicker" id="wgee-overlay-background-color" name="overlay-background-color" value="<?php echo esc_attr( $options['styles']['overlay']['background']['solid']['color'] ); ?>" />
																		</td>
																	</tr>
																</tbody>
															</table>
														</div>
														<div class="wgee-inside-table field" rel="overlay-background-gradient">
															<table class="wgee-form-table">
																<thead>
																	<tr>
																		<th><label for="wgee-overlay-background-start-color"><?php _e( "Start Color", 'wordpress-gallery-extra' ); ?></label></th>
																		<th><label for="wgee-overlay-background-stop-color"><?php _e( "Stop Color", 'wordpress-gallery-extra' ); ?></label></th>
																		<th><label for="wgee-overlay-background-orientation"><?php _e( "Orientation", 'wordpress-gallery-extra' ); ?></label></th>
																	</tr>
																</thead>
																<tbody>
																	<tr>
																		<td>
																			<input rel="colorpicker" id="wgee-overlay-background-start-color" name="overlay-background-start-color" value="<?php echo esc_attr( $options['styles']['overlay']['background']['gradient']['start_color'] ); ?>" />
																		</td>
																		<td>
																			<input rel="colorpicker" id="wgee-overlay-background-stop-color" name="overlay-background-stop-color" value="<?php echo esc_attr( $options['styles']['overlay']['background']['gradient']['stop_color'] ); ?>" />
																		</td>
																		<td>
																			<select id="wgee-overlay-background-orientation" name="overlay-background-orientation">
																				<option value="vertical"<?php selected( $options['styles']['overlay']['background']['gradient']['orientation'], 'vertical' ); ?>><?php _e( "Vertical", 'wordpress-gallery-extra' ); ?></option>
																				<option value="horizontal"<?php selected( $options['styles']['overlay']['background']['gradient']['orientation'], 'horizontal' ); ?>><?php _e( "Horizontal", 'wordpress-gallery-extra' ); ?></option>
																				<option value="radial"<?php selected( $options['styles']['overlay']['background']['gradient']['orientation'], 'radial' ); ?>><?php _e( "Radial", 'wordpress-gallery-extra' ); ?></option>
																				<option value="diagonal_45"<?php selected( $options['styles']['overlay']['background']['gradient']['orientation'], 'diagonal_45' ); ?>><?php _e( "Diagonal", 'wordpress-gallery-extra' ); ?> 45&#xB0;</option>
																				<option value="diagonal_n45"<?php selected( $options['styles']['overlay']['background']['gradient']['orientation'], 'diagonal_n45' ); ?>><?php _e( "Diagonal", 'wordpress-gallery-extra' ); ?> -45&#xB0;</option>
																			</select>
																		</td>
																	</tr>
																</tbody>
															</table>
														</div>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</form>
</div>
<?php
	}
}

new iProDev_Shortcode_WGExtra();


