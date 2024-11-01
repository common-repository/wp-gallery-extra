<?php

class WGExtra extends WordPress_Gallery_Extra {
	function __construct( $file ) {
		parent::__construct( $file );

		if ( $this->OPTIONS['installation_time'] + ( 30 * 24 * 60 * 60 ) < time() ) {
			add_filter( 'wgextra_gallery_output', array(
				$this,
				'gallery_output'
			), 99 );
		}
	}

	/**
	 * Activating handler.
	 * @return void
	 */
	public function activate() {
		// Add retina sizes for default WordPress image sizes
		$this->DEFAULT_OPTIONS = array_replace_recursive( $this->DEFAULT_OPTIONS, array(
			"installation_time" => time(),
			"image_sizes" => array_filter(
				array(
					"tiny-lazy" => array(
						"self_created" => true,
						"width" => 30,
						"height" => 30,
						"crop" => false,
						"show" => 'no',
						"delete" => 'no',
						"name" => 'Tiny Lazyload Size'
					),
					"thumbnail-2x" => array(
						"self_created" => true,
						"width" => get_option('thumbnail_size_w') * 2,
						"height" => get_option('thumbnail_size_h') * 2,
						"crop" => true,
						"show" => 'yes',
						"delete" => 'no',
						"name" => 'Thumbnail @2x'
					),
					"medium-2x" => get_option('medium_large_size_w') ? null : array(
						"self_created" => true,
						"width" => get_option('medium_size_w') * 2,
						"height" => get_option('medium_size_h') * 2,
						"crop" => false,
						"show" => 'yes',
						"delete" => 'no',
						"name" => 'Medium @2x'
					),
					"large-2x" => array(
						"self_created" => true,
						"width" => get_option('large_size_w') * 2,
						"height" => get_option('large_size_h') * 2,
						"crop" => false,
						"show" => 'yes',
						"delete" => 'no',
						"name" => 'Large @2x'
					)
				)
			)
		) );

		/* install the default options */
		if ( get_option( "{$this->SLUG}_options" ) === false ) {
			add_option( "{$this->SLUG}_options", $this->DEFAULT_OPTIONS, '', 'yes' );
		}
		if ( get_option( "{$this->SLUG}_templates" ) === false ) {
			add_option( "{$this->SLUG}_templates", array(), '', 'yes' );
		}
		if ( get_option( "{$this->SLUG}_sources" ) === false ) {
			add_option( "{$this->SLUG}_sources", array(), '', 'yes' );
		}
		if ( get_option( "{$this->SLUG}_errors" ) === false ) {
			add_option( "{$this->SLUG}_errors", array(), '', 'yes' );
		}
	}

	/**
	 * Uninstalling handler.
	 * @return void
	 */
	public function uninstall() {
		/* delete plugin options */
		if ( $this->OPTIONS['delete_data'] === 'yes' ) {
			delete_site_option( "{$this->SLUG}_options" );
			delete_site_option( "{$this->SLUG}_templates" );
			delete_option( "{$this->SLUG}_options" );
			delete_option( "{$this->SLUG}_templates" );
		}

		//Clear iProDevNotify
		iProDevNotify::clear_schedule_cron( __FILE__ );
	}

	/**
	 * Plugins loaded handler.
	 * @return void
	 */
	public function plugins_loaded() {
		$path = path_join( $this->PATH, 'languages/' );
		load_plugin_textdomain( 'wordpress-gallery-extra', false, $path );

		if ( get_option( "{$this->SLUG}_sources" ) === false ) {
			add_option( "{$this->SLUG}_sources", array(), '', 'yes' );
		}

		$custom_css_file = path_join( $this->PATH, 'assets/css/custom.css' );
		if ( !file_exists( $custom_css_file ) || !filesize( $custom_css_file ) ) {
			$this->save_styles( $this->TEMPLATES );
		}

		update_option( "{$this->SLUG}_version", $this->VERSION );
	}

	/**
	 * WordPress Initialization.
	 * @return void
	 */
	public function wp_init() {
		global $pagenow;

		if ( !is_array( $this->SOURCES ) ) {
			$this->SOURCES = array();
			add_option( "{$this->SLUG}_sources", array(), '', 'yes' );
		}

		add_filter( 'wp_generate_attachment_metadata', array(
			 $this,
			'filter_metadata'
		), 10, 2 );

		add_filter( 'jpeg_quality', array(
			 $this,
			'get_image_quality'
		), 10, 2 );
		add_filter( 'wp_editor_set_quality', array(
			 $this,
			'get_image_quality'
		), 10, 2 );  // Filter added in WP 3.5

		if ( $this->OPTIONS['media_taxonomies'] === 'yes' ) {
			/* Register Attachments Tags & Categories */
			$categories_args = array(
				'labels'            => array(),
				'hierarchical'      => true,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'show_ui'           => true
			);
			$tags_args = array(
				'labels'            => array(),
				'hierarchical'      => false,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'show_ui'           => true
			);
			register_taxonomy( 'attachment_tag', 'attachment', $tags_args );
			register_taxonomy( 'attachment_category', 'attachment', $categories_args );
		}

		// Remove previous gallery shortcode
		remove_shortcode( 'gallery' );
		// Add WordPress Gallery Extra shortcode
		add_shortcode( 'gallery', array(
			 $this,
			'gallery_shortcode'
		) );
		// Add WordPress Gallery Extra shortcode
		add_shortcode( 'wgextra', array(
			 $this,
			'gallery_shortcode'
		) );

		// Templates types
		$this->TEMPLATES_TYPES = apply_filters( 'wgextra-templates-types', array(
			'columns' => array (
				"name" => __( "Columns", "wordpress-gallery-extra" ),
				"fields" => "display-grid, responsive, loading_type, lightbox, caption_source, gallery_structure, vertical_alignment, alignment, tilt, default_image, thumbnail_size, columns, thumbnail_ratio"
			),
			'justified' => array (
				"name" => __( "Justified", "wordpress-gallery-extra" ),
				"fields" => "display-grid, responsive, loading_type, lightbox, caption_source, gallery_structure, last_row, row_height, max_row_height, tilt, default_image, thumbnail_size"
			),
			'masonry' => array (
				"name" => __( "Masonry", "wordpress-gallery-extra" ),
				"fields" => "display-grid, responsive, loading_type, lightbox, caption_source, gallery_structure, tilt, default_image, thumbnail_size, columns, thumbnail_ratio"
			),
			'mosaic' => array (
				"name" => __( "Mosaic", "wordpress-gallery-extra" ),
				"fields" => "display-grid, responsive, loading_type, lightbox, caption_source, gallery_structure, mosaic_type, tilt, default_image, thumbnail_size"
			),
			'slider' => array (
				"name" => __( "Slider", "wordpress-gallery-extra" ),
				"fields" => "display-grid, responsive, loading_type, lightbox, caption_source, gallery_structure, slider_settings, tilt, default_image, thumbnail_size, columns"
			)
		) );

		// Templates types
		$this->SOURCES_TYPES = apply_filters( 'wgextra-sources-types', array(
			'post_type' => array (
				"name" => __( "Post Type", "wordpress-gallery-extra" ),
				"fields" => "filter, post_types, post_status, taxonomies, authors, exclude_posts, include_posts, ordering"
			)
		) );

		/* Begin images sizes */
		$sizes = $this->OPTIONS['image_sizes'];

		// Return false if empty
		if ( !empty( $sizes ) && is_array( $sizes ) ) {
			// Set the new sizes
			foreach ( $sizes as $name => $size ) {
				if ( empty( $size ) ) {
					continue;
				}

				if ( isset( $size['delete'] ) && $size['delete'] === 'yes' ) {
					// Remove the image size
					remove_image_size( $name );
				} else {
					if ( !isset( $size['width'] ) || !isset( $size['height'] ) ) {
						continue;
					}

					$crop = ( isset( $size['crop'] ) && !empty( $size['crop'] ) ) ? $size['crop'] : false;

					if ( is_string( $crop ) && !is_numeric( $crop ) ) {
						$crop = explode( ',', $crop );
					} elseif ( is_numeric( $crop ) ) {
						$crop = true;
					}

					if ( !isset( $size['self_created'] ) ) {
						remove_image_size( $name );
					}
					// Add the image size
					add_image_size( $name, (int) $size['width'], (int) $size['height'], $crop );
				}
			}
		}
		/* End images sizes */

		if ( is_admin() && $pagenow === 'admin.php' && isset( $_GET['page'] ) && strpos( $_GET['page'], "wgextra" ) !== false ) {
			wp_deregister_script('heartbeat');
		}
	}

	/**
	 * Check whether the license is active or no.
	 *
	 * @access protected
	 *
	 * @return boolean
	 */
	protected function is_active() {
		return false;
	}

	public function gallery_output( $content ) {
		return $content . '<a href="https://wgextra.iprodev.com/" target="_blank" class="wgextra-powered-link" rel="creator" title="Above Grid Made by WordPress Gallery Extra">WordPress Gallery Extra</a>';
	}

	/**
	 * Register ajax actions.
	 *
	 * @return  {void}
	 */
	public function ajax_actions() {
		if ( !WP_DEBUG && function_exists( 'error_reporting' ) ) {
			@error_reporting( 0 ); // Don't break the JSON result
		}

		@set_time_limit( 900 ); // 15 minutes time limit

		header( 'Content-type: application/json' );

		$result = array();
		$p      = @stripslashes_deep( $_POST );
		//$p      = array_map( array( $this, 'sanitize_text_field' ), $p );

		$task = @$p['wgextra_task'];
		$nonce = @$p['wgextra_nonce'];

		unset( $p['wgextra_task'] );

		// check for rights
		if (
			!current_user_can( "manage_options" ) ||
			!$task ||
			( $task === "create_new_template"          && ! wp_verify_nonce( $nonce, 'wgextra_create_new_template' ) ) ||
			( $task === "import_template"              && ! wp_verify_nonce( $nonce, 'wgextra_import_template' ) ) ||
			( $task === "duplicate_template"           && ! wp_verify_nonce( $nonce, 'wgextra_duplicate_template' ) ) ||
			( $task === "delete_template"              && ! wp_verify_nonce( $nonce, 'wgextra_delete_template' ) ) ||
			( $task === "save_template_settings"       && ! wp_verify_nonce( $nonce, 'wgextra_save_template_settings' ) ) ||
			( $task === "save_settings"                && ! wp_verify_nonce( $nonce, 'wgextra_save_settings' ) ) ||
			( $task === "image_sizes"                  && ! wp_verify_nonce( $nonce, 'wgextra_save_images_sizes' ) ) ||
			( $task === "start_regenerator"            && ! wp_verify_nonce( $nonce, 'wgextra_start_regenerator' ) ) ||
			( $task === "verify_image_size_id"         && ! wp_verify_nonce( $nonce, 'wgextra_verify_image_size_id' ) ) ||
			( $task === "save_images_sizes"            && ! wp_verify_nonce( $nonce, 'wgextra_save_images_sizes' ) ) ||
			( $task === "backup_thumbnails"            && ! wp_verify_nonce( $nonce, 'wgextra_backup_thumbnails' ) ) ||
			( $task === "restore_backup"               && ! wp_verify_nonce( $nonce, 'wgextra_restore' ) ) ||
			( $task === "delete_backup"                && ! wp_verify_nonce( $nonce, 'wgextra_delete_backup' ) ) ||
			( $task === "analyze_thumbnails"           && ! wp_verify_nonce( $nonce, 'wgextra_clean_thumbnails' ) ) ||
			( $task === "clean_thumbnails"             && ! wp_verify_nonce( $nonce, 'wgextra_clean_thumbnails' ) ) ||
			( $task === "process_image_resize"         && ! wp_verify_nonce( $nonce, 'wgextra_process_image_resize' ) ) ||
			( $task === "convert_to_less"              && ! wp_verify_nonce( $nonce, 'wgextra_general' ) ) ||
			( $task === "check_iprodev_server_contact" && ! wp_verify_nonce( $nonce, 'wgextra_check_iprodev_server_contact' ) )
		) {
			$result = array(
				'status' => 403,
				'message' => __( 'You are not allowed to change WordPress Gallery Extra settings.', 'wordpress-gallery-extra' ) 
			);
		} else {
			$message = '';
			$error   = array();

			if ( $task === "create_new_template" ) {
				$name   = trim( $p['name'] );
				$result = array(
					'status' => 200
				);

				if ( strlen( $name ) > 3 ) {
					$templates = $this->TEMPLATES;
					$uid       = get_current_user_id();
					$user_info = get_userdata( $uid );

					$template       = array_merge( $this->DEFAULT_TEMPLATE_OPTIONS, array( "name" => $name, "uid" => $uid, "lastEdit" => time() ) );
					$templates[]    = $template;
					$templates_keys = array_keys( $templates );
					$id             = end( $templates_keys );

					update_option( "{$this->SLUG}_templates", $templates );
					$this->save_styles( $templates );

					$result['id']          = $id;
					$result['name']        = $name;
					$result['last_modify'] = __( "Now", 'wordpress-gallery-extra' );
					$result['user']        = $user_info->display_name;
					$result['template']    = $this->TEMPLATES_TYPES[$template['template']]['name'];
				} else {
					$result['status']  = 400;
					$result['message'] = __( "Your template name must be at least 3 characters.", 'wordpress-gallery-extra' );
				}
			}

			else if ( $task === "import_template" ) {
				$tid    = trim( $p['id'] );
				$result = array(
					'status' => 200
				);

				if ( strlen( $tid ) > 1 ) {
					$options = $this->OPTIONS;
					$license_id = $options['license_id'];
					$license_nonce = $options['license_nonce'];

					$response = wp_remote_get( "https://wgextra.iprodev.com/templates/import.php?id=$tid&license_id=$license_id&license_nonce=$license_nonce&site=" . urlencode( site_url() ) );

					if ( is_wp_error( $response ) ) {
						$result = array(
							'status' => 400,
							'message' => $response->get_error_message()
						);
					} else {
						$data = json_decode( wp_remote_retrieve_body( $response ), true );
						if ( $data['status'] != 200 ) {
							$result = array(
								'status' => $data['status'],
								'message' => $data['message']
							);
						} else {
							$template  = $data['template']['data'];
							$templates = $this->TEMPLATES;
							$time      = time();
							$uid       = get_current_user_id();
							$user_info = get_userdata( $uid );

							$templates[]    = array_merge( $this->DEFAULT_TEMPLATE_OPTIONS, $template, array( "name" => $data['template']['name'], "uid" => $uid, "lastEdit" => $time ) );
							$templates_keys = array_keys( $templates );
							$id             = end( $templates_keys );

							update_option( "{$this->SLUG}_templates", $templates );
							$this->save_styles( $templates );

							$result['id']          = $id;
							$result['name']        = $data['template']['name'];
							$result['last_modify'] = __( "Now", 'wordpress-gallery-extra' );
							$result['user']        = $user_info->display_name;
							$result['template']    = $this->TEMPLATES_TYPES[$template['template']]['name'];
						}
					}
				} else {
					$result['status']  = 400;
					$result['message'] = __( "Please choose an valid template to import.", 'wordpress-gallery-extra' );
				}
			}

			else if ( $task === "duplicate_template" ) {
				$id     = $p['id'];
				$result = array(
					'status' => 200
				);

				$templates = $this->TEMPLATES;

				if ( !isset( $templates[$id] ) ) {
					wp_send_json( array(
						"status" => 404,
						"message" => __( "Intended template is not exists.", 'wordpress-gallery-extra' )
					) );
				}

				$template  = $templates[$id];
				$time      = time();
				$uid       = get_current_user_id();
				$user_info = get_userdata( $uid );

				$templates[]    = array_merge( $template, array( "uid" => $uid, "lastEdit" => $time ) );
				$templates_keys = array_keys( $templates );
				$dId            = end( $templates_keys );

				update_option( "{$this->SLUG}_templates", $templates );
				$this->save_styles( $templates );

				$result['id']          = $dId;
				$result['name']        = $template['name'];
				$result['user']        = $user_info->display_name;
				$result['last_modify'] = __( "Now", 'wordpress-gallery-extra' );
				$result['template']    = $this->TEMPLATES_TYPES[$template['template']]['name'];
			}

			else if ( $task === "delete_template" ) {
				$id     = $p['id'];
				$result = array(
					'status' => 200
				);

				$templates = $this->TEMPLATES;

				if ( !isset( $templates[$id] ) ) {
					wp_send_json( array(
						"status" => 404,
						"message" => __( "Intended template is not exists.", 'wordpress-gallery-extra' )
					) );
				}

				unset( $templates[$id] );

				update_option( "{$this->SLUG}_templates", $templates );
				$this->save_styles( $templates );
			}

			else if ( $task === "save_template_settings" ) {
				$id        = $p['id'];
				$templates = $this->TEMPLATES;

				if ( !isset( $templates[$id] ) ) {
					wp_send_json( array(
						"status" => 404,
						"message" => __( "Intended template is not exists.", 'wordpress-gallery-extra' )
					) );
				}

				//$options = array_replace_recursive( $this->DEFAULT_TEMPLATE_OPTIONS, $this->array_whitelist_filter( $templates[$id], array( "uid" ) ) );
				$options = $this->DEFAULT_TEMPLATE_OPTIONS;
				$options['uid'] = $templates[$id]['uid'];

				if ( isset( $p['wgextra_name'] ) && strlen( trim( $p['wgextra_name'] ) ) > 3 ) {
					$options['name'] = trim( $p['wgextra_name'] );
				} else {
					$error[] = "<li>" . __( "Your template name must be at least 3 characters.", 'wordpress-gallery-extra' ) . "</li>";
				}

				if ( isset( $p['wgextra_template'] ) ) {
					$options['template'] = $p['wgextra_template'];
				}
				if ( isset( $p['wgextra_loading_type'] ) ) {
					$options['loading_type'] = $p['wgextra_loading_type'];
				}

				if ( isset( $p['wgextra_lightbox_type'] ) ) {
					$options['lightbox_type'] = $p['wgextra_lightbox_type'];

					if ( $options['lightbox_type'] === 'magnific' ) {
						$options['lightbox_magnific']['animation']    = $p['wgextra_lightbox_magnific_animation'];
						$options['lightbox_magnific']['vertical_fit'] = ( isset( $p['wgextra_lightbox_magnific_vertical_fit'] ) )  ? "yes" : "no";
						$options['lightbox_magnific']['preload']      = ( isset( $p['wgextra_lightbox_magnific_preload'] ) )       ? "yes" : "no";
						$options['lightbox_magnific']['deeplink']     = ( isset( $p['wgextra_lightbox_magnific_deeplink'] ) )      ? "yes" : "no";
					} else if ( $options['lightbox_type'] === 'ilightbox' ) {
						$options['lightbox_ilightbox']['skin']            = $p['wgextra_lightbox_ilightbox_skin'];
						$options['lightbox_ilightbox']['direction']       = $p['wgextra_lightbox_ilightbox_direction'];
						$options['lightbox_ilightbox']['overlay_opacity'] = $p['wgextra_lightbox_ilightbox_overlay_opacity'];
						$options['lightbox_ilightbox']['deeplink']        = ( isset( $p['wgextra_lightbox_ilightbox_deeplink'] ) )      ? "yes" : "no";
						$options['lightbox_ilightbox']['carousel_mode']   = ( isset( $p['wgextra_lightbox_ilightbox_carousel_mode'] ) ) ? "yes" : "no";
						$options['lightbox_ilightbox']['loop']            = ( isset( $p['wgextra_lightbox_ilightbox_loop'] ) )          ? "yes" : "no";
						$options['lightbox_ilightbox']['share_buttons']   = ( isset( $p['wgextra_lightbox_ilightbox_share_buttons'] ) ) ? "yes" : "no";
						$options['lightbox_ilightbox']['thumbnails']      = ( isset( $p['wgextra_lightbox_ilightbox_thumbnails'] ) )    ? "yes" : "no";
					}
				}

				if ( isset( $p['wgextra_caption_source'] ) ) {
					$options['caption_source'] = $p['wgextra_caption_source'];
				}
				if ( isset( $p['wgextra_custom_class'] ) ) {
					$options['custom_class'] = trim( $p['wgextra_custom_class'] );
				}
				if ( isset( $p['wgextra_link_to'] ) ) {
					$options['link']['to'] = $p['wgextra_link_to'];
					$options['link']['target'] = $p['wgextra_link_target'];
					$options['link']['url'] = trim( $p['wgextra_link_url'] );
				}

				/* Source */
				if ( isset( $p['wgextra_source'] ) ) {
					$options['source']['source'] = $p['wgextra_source'];
				}
				/* Check value from "Item Number" option */
				if ( isset( $p['wgextra_item_number'] ) ) {
					if ( !is_numeric( $p['wgextra_item_number'] ) ) {
						$error[] = "<li>" . sprintf( __( "Please enter a valid value in the '%s' field.", 'wordpress-gallery-extra' ), __( "Item Number", 'wordpress-gallery-extra' ) ) . "</li>";
					} else {
						$options['source']['item_number'] = floatval( $p['wgextra_item_number'] );
					}
				}

				$options['source']['post_types']  = ( isset( $p['wgextra_post_types'] ) )  ? (array) $p['wgextra_post_types']  : array();
				$options['source']['post_status'] = ( isset( $p['wgextra_post_status'] ) ) ? (array) $p['wgextra_post_status'] : array();
				$options['source']['taxonomies']  = ( isset( $p['wgextra_taxonomies'] ) )  ? (array) $p['wgextra_taxonomies']  : array();
				$options['source']['authors']     = ( isset( $p['wgextra_authors'] ) )     ? (array) $p['wgextra_authors']     : array();

				if ( isset( $p['wgextra_taxonomies_relation'] ) )
					$options['source']['taxonomies_relation'] = $p['wgextra_taxonomies_relation'];

				if ( isset( $p['wgextra_authors_relation'] ) )
					$options['source']['authors_relation'] = $p['wgextra_authors_relation'];

				if ( isset( $p['wgextra_exclude_posts'] ) )
					$options['source']['exclude_posts'] = $p['wgextra_exclude_posts'];

				if ( isset( $p['wgextra_include_posts'] ) )
					$options['source']['include_posts'] = $p['wgextra_include_posts'];

				if ( isset( $p['wgextra_ordering_order'] ) )
					$options['source']['ordering']['order'] = $p['wgextra_ordering_order'];

				if ( isset( $p['wgextra_ordering_order_by'] ) )
					$options['source']['ordering']['order_by'] = $p['wgextra_ordering_order_by'];

				if ( isset( $p['wgextra_ordering_order_by_fallback'] ) )
					$options['source']['ordering']['order_by_fallback'] = $p['wgextra_ordering_order_by_fallback'];

				if ( isset( $p['wgextra_ordering_meta_key'] ) )
					$options['source']['ordering']['meta_key'] = $p['wgextra_ordering_meta_key'];


				/* Display */
				if ( isset( $p['wgextra_default_image'] ) ) {
					$options['default_image'] = $p['wgextra_default_image'];
				}
				if ( isset( $p['wgextra_thumbnail_size'] ) ) {
					$options['thumbnail_size'] = $p['wgextra_thumbnail_size'];
				}
				if ( isset( $p['wgextra_last_row'] ) ) {
					$options['last_row'] = $p['wgextra_last_row'];
				}
				if ( isset( $p['wgextra_alignment'] ) ) {
					$options['alignment'] = $p['wgextra_alignment'];
				}
				if ( isset( $p['wgextra_vertical_alignment'] ) ) {
					$options['vertical_alignment'] = $p['wgextra_vertical_alignment'];
				}
				if ( isset( $p['wgextra_mosaic_type'] ) ) {
					$options['mosaic_type'] = $p['wgextra_mosaic_type'];
				}
				if ( isset( $p['wgextra_thumbnail_ratio'] ) ) {
					$options['thumbnail_ratio']['type'] = $p['wgextra_thumbnail_ratio'];
				}
				if ( isset( $p['wgextra_thumbnail_ratio_size'] ) ) {
					$options['thumbnail_ratio']['size'] = (array) array_map( 'floatval', $p['wgextra_thumbnail_ratio_size'] );
				}
				$options['thumbnail_ratio']['force'] = ( isset( $p['wgextra_thumbnail_ratio_force'] ) ) ? "yes" : "no";

				/* Check value from "Columns" option */
				if ( isset( $p['wgextra_columns'] ) ) {
					if ( !is_numeric( $p['wgextra_columns'] ) ) {
						$error[] = "<li>" . sprintf( __( "Please enter a valid value in the '%s' field.", 'wordpress-gallery-extra' ), __( "Columns", 'wordpress-gallery-extra' ) ) . "</li>";
					} else {
						$options['columns'] = floatval( $p['wgextra_columns'] );
					}
				}

				/* Check value from "Row Height" option */
				if ( isset( $p['wgextra_row_height'] ) ) {
					if ( !is_numeric( $p['wgextra_row_height'] ) ) {
						$error[] = "<li>" . sprintf( __( "Please enter a valid value in the '%s' field.", 'wordpress-gallery-extra' ), __( "Row Height", 'wordpress-gallery-extra' ) ) . "</li>";
					} else {
						$options['row_height'] = floatval( $p['wgextra_row_height'] );
					}
				}

				/* Check value from "Max Row Height" option */
				if ( isset( $p['wgextra_max_row_height'] ) ) {
					if ( !is_numeric( $p['wgextra_max_row_height'] ) ) {
						$error[] = "<li>" . sprintf( __( "Please enter a valid value in the '%s' field.", 'wordpress-gallery-extra' ), __( "Max Row Height", 'wordpress-gallery-extra' ) ) . "</li>";
					} else {
						$options['max_row_height'] = floatval( $p['wgextra_max_row_height'] ) . $p['wgextra_max_row_height_unit'];
					}
				}



				/* Styles */
				if ( isset( $p['wgextra_style'] ) ) {
					$options['styles']['defined'] = $p['wgextra_style'];
				}

				if ( isset( $p['wgextra_style_thumbnail_effect'] ) ) {
					$options['styles']['thumbnail_effect']['effect'] = $p['wgextra_style_thumbnail_effect'];
					$options['styles']['thumbnail_effect']['transition']['easing'] = $p['wgextra_style_thumbnail_effect_transition_easing'];

					/* Check value from "Thumbnail Effect Speed" option */
					if ( isset( $p['wgextra_style_thumbnail_effect_transition_speed'] ) ) {
						if ( !is_numeric( $p['wgextra_style_thumbnail_effect_transition_speed'] ) ) {
							$error[] = "<li>" . sprintf( __( "Please enter a valid value in the '%s' field.", 'wordpress-gallery-extra' ), __( "Thumbnail Effect", 'wordpress-gallery-extra' ) . " - " . __( "Speed", 'wordpress-gallery-extra' ) ) . "</li>";
						} else {
							$options['styles']['thumbnail_effect']['transition']['speed'] = floatval( $p['wgextra_style_thumbnail_effect_transition_speed'] );
						}
					}
					/* Check value from "Thumbnail Effect Delay" option */
					if ( isset( $p['wgextra_style_thumbnail_effect_transition_delay'] ) ) {
						if ( !is_numeric( $p['wgextra_style_thumbnail_effect_transition_delay'] ) ) {
							$error[] = "<li>" . sprintf( __( "Please enter a valid value in the '%s' field.", 'wordpress-gallery-extra' ), __( "Thumbnail Effect", 'wordpress-gallery-extra' ) . " - " . __( "Delay", 'wordpress-gallery-extra' ) ) . "</li>";
						} else {
							$options['styles']['thumbnail_effect']['transition']['delay'] = floatval( $p['wgextra_style_thumbnail_effect_transition_delay'] );
						}
					}
				}

				$options['styles']['has_border']      = isset( $p['wgextra_style_border'] )          ? 'yes' : 'no';
				$options['styles']['has_shadow']      = isset( $p['wgextra_style_shadow'] )          ? 'yes' : 'no';
				$options['styles']['has_icon']        = isset( $p['wgextra_style_icon'] )            ? 'yes' : 'no';
				$options['styles']['has_caption']     = isset( $p['wgextra_style_caption'] )         ? 'yes' : 'no';
				$options['styles']['has_overlay']     = isset( $p['wgextra_style_overlay'] )         ? 'yes' : 'no';
				$options['styles']['use_placeholder'] = isset( $p['wgextra_style_use_placeholder'] ) ? 'yes' : 'no';

				if ( isset( $p['wgextra_style_embed_google_fonts'] ) ) {
					$options['styles']['embed_google_fonts'] = $p['wgextra_style_embed_google_fonts'];
				}

				if ( isset( $p['wgextra_style_custom_css'] ) ) {
					$options['styles']['custom_css'] = $p['wgextra_style_custom_css'];
				}

				// Border style
				if ( $options['styles']['has_border'] === 'yes' ) {
					/* Check value from "Border Radius" option */
					if ( isset( $p['wgextra_style_border_radius'] ) ) {
						if ( !is_numeric( $p['wgextra_style_border_radius'] ) ) {
							$error[] = "<li>" . sprintf( __( "Please enter a valid value in the '%s' field.", 'wordpress-gallery-extra' ), __( "Border Radius", 'wordpress-gallery-extra' ) ) . "</li>";
						} else {
							$options['styles']['border']['radius'] = floatval( $p['wgextra_style_border_radius'] );
						}
					}
					/* Check value from "Border Weight" option */
					if ( isset( $p['wgextra_style_border_weight'] ) ) {
						if ( !is_numeric( $p['wgextra_style_border_weight'] ) ) {
							$error[] = "<li>" . sprintf( __( "Please enter a valid value in the '%s' field.", 'wordpress-gallery-extra' ), __( "Border Weight", 'wordpress-gallery-extra' ) ) . "</li>";
						} else {
							$options['styles']['border']['weight'] = floatval( $p['wgextra_style_border_weight'] );
						}
					}
					if ( isset( $p['wgextra_style_border_style'] ) ) {
						$options['styles']['border']['style'] = $p['wgextra_style_border_style'];
					}
					if ( isset( $p['wgextra_style_border_color'] ) ) {
						$options['styles']['border']['color'] = $p['wgextra_style_border_color'];
					}
				}

				// Shadow style
				if ( $options['styles']['has_shadow'] === 'yes' ) {
					/* Check value from "Shadow X-offset" option */
					if ( isset( $p['wgextra_style_shadow_x'] ) ) {
						if ( !is_numeric( $p['wgextra_style_shadow_x'] ) ) {
							$error[] = "<li>" . sprintf( __( "Please enter a valid value in the '%s' field.", 'wordpress-gallery-extra' ), __( "Shadow X-offset", 'wordpress-gallery-extra' ) ) . "</li>";
						} else {
							$options['styles']['shadow']['x'] = floatval( $p['wgextra_style_shadow_x'] );
						}
					}
					/* Check value from "Shadow Y-offset" option */
					if ( isset( $p['wgextra_style_shadow_y'] ) ) {
						if ( !is_numeric( $p['wgextra_style_shadow_y'] ) ) {
							$error[] = "<li>" . sprintf( __( "Please enter a valid value in the '%s' field.", 'wordpress-gallery-extra' ), __( "Shadow Y-offset", 'wordpress-gallery-extra' ) ) . "</li>";
						} else {
							$options['styles']['shadow']['y'] = floatval( $p['wgextra_style_shadow_y'] );
						}
					}
					/* Check value from "Shadow Blur" option */
					if ( isset( $p['wgextra_style_shadow_blur'] ) ) {
						if ( !is_numeric( $p['wgextra_style_shadow_blur'] ) ) {
							$error[] = "<li>" . sprintf( __( "Please enter a valid value in the '%s' field.", 'wordpress-gallery-extra' ), __( "Shadow Blur", 'wordpress-gallery-extra' ) ) . "</li>";
						} else {
							$options['styles']['shadow']['blur'] = floatval( $p['wgextra_style_shadow_blur'] );
						}
					}
					/* Check value from "Shadow Spread" option */
					if ( isset( $p['wgextra_style_shadow_spread'] ) ) {
						if ( !is_numeric( $p['wgextra_style_shadow_spread'] ) ) {
							$error[] = "<li>" . sprintf( __( "Please enter a valid value in the '%s' field.", 'wordpress-gallery-extra' ), __( "Shadow Spread", 'wordpress-gallery-extra' ) ) . "</li>";
						} else {
							$options['styles']['shadow']['spread'] = floatval( $p['wgextra_style_shadow_spread'] );
						}
					}
					if ( isset( $p['wgextra_style_shadow_color'] ) ) {
						$options['styles']['shadow']['color'] = $p['wgextra_style_shadow_color'];
					}
					$options['styles']['shadow']['inset'] = ( isset( $p['wgextra_style_shadow_inset'] ) )  ? "yes" : "no";
				}

				// Placeholder style
				if ( $options['styles']['use_placeholder'] === 'yes' ) {
					$options['styles']['placeholder']['overlay']        = ( isset( $p['wgextra_style_placeholder_overlay'] ) )         ? "yes" : "no";
					$options['styles']['placeholder']['readable_caption'] = ( isset( $p['wgextra_style_placeholder_readable_caption'] ) )  ? "yes" : "no";
					$options['styles']['placeholder']['background']     = ( isset( $p['wgextra_style_placeholder_background'] ) )      ? "yes" : "no";
				}

				/* Check value from "Spacing" option */
				if ( isset( $p['wgextra_style_margin'] ) ) {
					if ( !is_numeric( $p['wgextra_style_margin'] ) ) {
						$error[] = "<li>" . sprintf( __( "Please enter a valid value in the '%s' field.", 'wordpress-gallery-extra' ), __( "Spacing", 'wordpress-gallery-extra' ) ) . "</li>";
					} else {
						$options['styles']['margin'] = floatval( $p['wgextra_style_margin'] );
					}
				}

				// Hover type styles
				if ( $options['styles']['has_icon'] === 'yes' ) {
					if ( isset( $p['wgextra_style_icon_icon'] ) ) {
						$options['styles']['icon']['icon'] = $p['wgextra_style_icon_icon'];
					}
					if ( isset( $p['wgextra_style_icon_visibility'] ) ) {
						$options['styles']['icon']['visibility'] = $p['wgextra_style_icon_visibility'];
					}
					if ( isset( $p['wgextra_style_icon_color'] ) ) {
						$options['styles']['icon']['color'] = $p['wgextra_style_icon_color'];
					}
					if ( isset( $p['wgextra_style_icon_transition_easing'] ) ) {
						$options['styles']['icon']['transition']['easing'] = $p['wgextra_style_icon_transition_easing'];
					}
					/* Check value from "Icon Size" option */
					if ( isset( $p['wgextra_style_icon_size'] ) ) {
						if ( !is_numeric( $p['wgextra_style_icon_size'] ) ) {
							$error[] = "<li>" . sprintf( __( "Please enter a valid value in the '%s' field.", 'wordpress-gallery-extra' ), __( "Icon", 'wordpress-gallery-extra' ) . " - " . __( "Size", 'wordpress-gallery-extra' ) ) . "</li>";
						} else {
							$options['styles']['icon']['size'] = floatval( $p['wgextra_style_icon_size'] );
						}
					}
					/* Check value from "Icon Speed" option */
					if ( isset( $p['wgextra_style_icon_transition_speed'] ) ) {
						if ( !is_numeric( $p['wgextra_style_icon_transition_speed'] ) ) {
							$error[] = "<li>" . sprintf( __( "Please enter a valid value in the '%s' field.", 'wordpress-gallery-extra' ), __( "Icon", 'wordpress-gallery-extra' ) . " - " . __( "Speed", 'wordpress-gallery-extra' ) ) . "</li>";
						} else {
							$options['styles']['icon']['transition']['speed'] = floatval( $p['wgextra_style_icon_transition_speed'] );
						}
					}
					/* Check value from "Icon Delay" option */
					if ( isset( $p['wgextra_style_icon_transition_delay'] ) ) {
						if ( !is_numeric( $p['wgextra_style_icon_transition_delay'] ) ) {
							$error[] = "<li>" . sprintf( __( "Please enter a valid value in the '%s' field.", 'wordpress-gallery-extra' ), __( "Icon", 'wordpress-gallery-extra' ) . " - " . __( "Delay", 'wordpress-gallery-extra' ) ) . "</li>";
						} else {
							$options['styles']['icon']['transition']['delay'] = floatval( $p['wgextra_style_icon_transition_delay'] );
						}
					}
				}

				if ( $options['styles']['has_caption'] === 'yes' ) {
					$options['styles']['caption']['inset'] = isset( $p['wgextra_style_caption_inset'] ) ? 'yes' : 'no';

					if ( isset( $p['wgextra_style_caption_color'] ) ) {
						$options['styles']['caption']['color'] = $p['wgextra_style_caption_color'];
					}
					if ( isset( $p['wgextra_style_caption_position'] ) ) {
						$options['styles']['caption']['position'] = $p['wgextra_style_caption_position'];
					}
					if ( isset( $p['wgextra_style_caption_visibility'] ) ) {
						$options['styles']['caption']['visibility'] = $p['wgextra_style_caption_visibility'];
					}
					if ( isset( $p['wgextra_style_caption_transition_easing'] ) ) {
						$options['styles']['caption']['transition']['easing'] = $p['wgextra_style_caption_transition_easing'];
					}
					if ( isset( $p['wgextra_style_caption_background'] ) ) {
						$options['styles']['caption']['background']['type'] = $p['wgextra_style_caption_background'];
					}

					if ( $options['styles']['caption']['background']['type'] === 'solid' ) {
						if ( isset( $p['wgextra_style_caption_background_solid_color'] ) ) {
							$options['styles']['caption']['background']['solid']['color'] = $p['wgextra_style_caption_background_solid_color'];
						}
					} else if ( $options['styles']['caption']['background']['type'] === 'gradient' ) {
						if ( isset( $p['wgextra_style_caption_background_gradient_start_color'] ) ) {
							$options['styles']['caption']['background']['gradient']['start_color'] = $p['wgextra_style_caption_background_gradient_start_color'];
						}
						if ( isset( $p['wgextra_style_caption_background_gradient_stop_color'] ) ) {
							$options['styles']['caption']['background']['gradient']['stop_color'] = $p['wgextra_style_caption_background_gradient_stop_color'];
						}
						if ( isset( $p['wgextra_style_caption_background_gradient_orientation'] ) ) {
							$options['styles']['caption']['background']['gradient']['orientation'] = $p['wgextra_style_caption_background_gradient_orientation'];
						}
					}

					/* Check value from "Caption Speed" option */
					if ( isset( $p['wgextra_style_caption_transition_speed'] ) ) {
						if ( !is_numeric( $p['wgextra_style_caption_transition_speed'] ) ) {
							$error[] = "<li>" . sprintf( __( "Please enter a valid value in the '%s' field.", 'wordpress-gallery-extra' ), __( "Caption", 'wordpress-gallery-extra' ) . " - " . __( "Speed", 'wordpress-gallery-extra' ) ) . "</li>";
						} else {
							$options['styles']['caption']['transition']['speed'] = floatval( $p['wgextra_style_caption_transition_speed'] );
						}
					}
					/* Check value from "Caption Delay" option */
					if ( isset( $p['wgextra_style_caption_transition_delay'] ) ) {
						if ( !is_numeric( $p['wgextra_style_caption_transition_delay'] ) ) {
							$error[] = "<li>" . sprintf( __( "Please enter a valid value in the '%s' field.", 'wordpress-gallery-extra' ), __( "Caption", 'wordpress-gallery-extra' ) . " - " . __( "Delay", 'wordpress-gallery-extra' ) ) . "</li>";
						} else {
							$options['styles']['caption']['transition']['delay'] = floatval( $p['wgextra_style_caption_transition_delay'] );
						}
					}
				}

				// Handle overlay styles
				if ( $options['styles']['has_overlay'] === 'yes' ) {
					if ( isset( $p['wgextra_style_overlay_visibility'] ) ) {
						$options['styles']['overlay']['visibility'] = $p['wgextra_style_overlay_visibility'];
					}
					if ( isset( $p['wgextra_style_overlay_background'] ) ) {
						$options['styles']['overlay']['background']['type'] = $p['wgextra_style_overlay_background'];
					}
					if ( isset( $p['wgextra_style_overlay_transition_easing'] ) ) {
						$options['styles']['overlay']['transition']['easing'] = $p['wgextra_style_overlay_transition_easing'];
					}

					if ( $options['styles']['overlay']['background']['type'] === 'solid' ) {
						if ( isset( $p['wgextra_style_overlay_background_solid_color'] ) ) {
							$options['styles']['overlay']['background']['solid']['color'] = $p['wgextra_style_overlay_background_solid_color'];
						}
					} else if ( $options['styles']['overlay']['background']['type'] === 'gradient' ) {
						if ( isset( $p['wgextra_style_overlay_background_gradient_start_color'] ) ) {
							$options['styles']['overlay']['background']['gradient']['start_color'] = $p['wgextra_style_overlay_background_gradient_start_color'];
						}
						if ( isset( $p['wgextra_style_overlay_background_gradient_stop_color'] ) ) {
							$options['styles']['overlay']['background']['gradient']['stop_color'] = $p['wgextra_style_overlay_background_gradient_stop_color'];
						}
						if ( isset( $p['wgextra_style_overlay_background_gradient_orientation'] ) ) {
							$options['styles']['overlay']['background']['gradient']['orientation'] = $p['wgextra_style_overlay_background_gradient_orientation'];
						}
					}
					/* Check value from "Overlay Speed" option */
					if ( isset( $p['wgextra_style_overlay_transition_speed'] ) ) {
						if ( !is_numeric( $p['wgextra_style_overlay_transition_speed'] ) ) {
							$error[] = "<li>" . sprintf( __( "Please enter a valid value in the '%s' field.", 'wordpress-gallery-extra' ), __( "Overlay", 'wordpress-gallery-extra' ) . " - " . __( "Speed", 'wordpress-gallery-extra' ) ) . "</li>";
						} else {
							$options['styles']['overlay']['transition']['speed'] = floatval( $p['wgextra_style_overlay_transition_speed'] );
						}
					}
					/* Check value from "Overlay Delay" option */
					if ( isset( $p['wgextra_style_overlay_transition_delay'] ) ) {
						if ( !is_numeric( $p['wgextra_style_overlay_transition_delay'] ) ) {
							$error[] = "<li>" . sprintf( __( "Please enter a valid value in the '%s' field.", 'wordpress-gallery-extra' ), __( "Overlay", 'wordpress-gallery-extra' ) . " - " . __( "Delay", 'wordpress-gallery-extra' ) ) . "</li>";
						} else {
							$options['styles']['overlay']['transition']['delay'] = floatval( $p['wgextra_style_overlay_transition_delay'] );
						}
					}
				}

				$options = apply_filters( 'wgextra_edit_template_settings', $options, $id, $p );
				$error = apply_filters( 'wgextra_edit_template_settings_errors', $error, $options, $id, $p );

				/* Update settings in the database */
				if ( empty( $error ) ) {
					$options['lastEdit'] = time();
					$templates[$id] = $options;

					update_option( "{$this->SLUG}_templates", $templates );
					$this->save_styles( $templates );
					$message = __( "Settings saved.", 'wordpress-gallery-extra' );
				} else {
					$message = __( "Settings are not saved.", 'wordpress-gallery-extra' );
				}

				$result = array(
					'status' => empty( $error ) ? 200 : 403,
					'error' => $error,
					'message' => $message 
				);
			}

			else if ( $task === "save_settings" ) {
				$options = $this->OPTIONS;

				$options['load_library']      = ( isset( $p['wgextra_load_library'] ) )      ? "yes" : "no";
				$options['debounce_resize']   = ( isset( $p['wgextra_debounce_resize'] ) )   ? "yes" : "no";
				$options['media_taxonomies']  = ( isset( $p['wgextra_media_taxonomies'] ) )  ? "yes" : "no";
				$options['grab_placeholder']  = ( isset( $p['wgextra_grab_placeholder'] ) )  ? "yes" : "no";
				$options['import_from_xmp']   = ( isset( $p['wgextra_import_from_xmp'] ) )   ? "yes" : "no";
				$options['crash_report']      = ( isset( $p['wgextra_crash_report'] ) )      ? "yes" : "no";
				$options['delete_data']       = ( isset( $p['wgextra_delete_data'] ) )       ? "yes" : "no";

				/* Check value from "Items per page" option */
				if ( isset( $p['wgextra_items_per_page'] ) ) {
					if ( !is_numeric( $p['wgextra_items_per_page'] ) || ( is_numeric( $p['wgextra_items_per_page'] ) && $p['wgextra_items_per_page'] < 5 ) ) {
						$error[] = "<li>" . sprintf( __( "Please enter a valid value in the '%s' field.", 'wordpress-gallery-extra' ), __( "Items per page", 'wordpress-gallery-extra' ) ) . "</li>";
					} else {
						$options['items_per_page'] = floatval( $p['wgextra_items_per_page'] );
					}
				}

				$options = apply_filters( 'wgextra_edit_settings', $options, $p );

				/* Update settings in the database */
				if ( empty( $error ) ) {
					update_option( "{$this->SLUG}_options", $options );
					$message = __( "Settings saved.", 'wordpress-gallery-extra' );
				} else {
					$message = __( "Settings are not saved.", 'wordpress-gallery-extra' );
				}

				$result = array(
					'status' => empty( $error ) ? 200 : 403,
					'error' => $error,
					'message' => $message 
				);
			}

			else if ( $task === "verify_image_size_id" ) {
				wp_send_json( array(
					"status" => 403,
					"message" => __( "Please activate WordPress Gallery Extra plugin to enable this feature.", 'wordpress-gallery-extra' )
				) );
			}

			else if ( $task === "save_images_sizes" ) {
				wp_send_json( array(
					"status" => 403,
					"message" => __( "Please activate WordPress Gallery Extra plugin to enable this feature.", 'wordpress-gallery-extra' )
				) );
			}

			else if ( $task === "backup_thumbnails" ) {
				wp_send_json( array(
					"status" => 403,
					"message" => __( "Please activate WordPress Gallery Extra plugin to enable this feature.", 'wordpress-gallery-extra' )
				) );
			}

			else if ( $task === "restore_backup" ) {
				$time_start = microtime(true);
				// Restore backup
				if ( $this->restore() ) {
					$time_end       = microtime(true);
					$execution_time = round( $time_end - $time_start, 2 );
					$result         = array(
						'status' => 200,
						'message' => sprintf( __( "Your thumbnails has been restored in %s seconds.", 'wordpress-gallery-extra' ), $execution_time )
					);
				} else {
					$result = array(
						'status' => 400,
						'message' => __( "Request failed, please try again.", 'wordpress-gallery-extra' )
					);
				}
			}

			else if ( $task === "delete_backup" ) {
				// Delete backups
				if ( $this->delete_backups() ) {
					$result = array(
						'status' => 200
					);
				} else {
					$result = array(
						'status' => 400,
						'message' => __( "Request failed, please try again.", 'wordpress-gallery-extra' )
					);
				}
			}

			else if ( $task === "analyze_thumbnails" ) {
				wp_send_json( array(
					"status" => 403,
					"message" => __( "Please activate WordPress Gallery Extra plugin to enable this feature.", 'wordpress-gallery-extra' )
				) );
			}

			else if ( $task === "clean_thumbnails" ) {
				wp_send_json( array(
					"status" => 403,
					"message" => __( "Please activate WordPress Gallery Extra plugin to enable this feature.", 'wordpress-gallery-extra' )
				) );
			}

			else if ( $task === "start_regenerator" ) {
				wp_send_json( array(
					"status" => 403,
					"message" => __( "Please activate WordPress Gallery Extra plugin to enable this feature.", 'wordpress-gallery-extra' )
				) );
			}

			else if ( $task === "process_image_resize" ) {
				wp_send_json( array(
					"status" => 403,
					"message" => __( "Please activate WordPress Gallery Extra plugin to enable this feature.", 'wordpress-gallery-extra' )
				) );
			}

			else if ( $task === "check_iprodev_server_contact" ) {
				$response = wp_remote_get(
					"https://wgextra.iprodev.com/",
					array(
						'sslverify'  => false,
						'timeout'    => 10
					)
				);

				$result = array(
					'status' => wp_remote_retrieve_response_code( $response )
				);

				if ( is_wp_error( $response ) ) {
					$result['message'] = $response->get_error_message();
				}
			}

			else if ( $task === "dismiss_notify" ) {
				$notify = get_option( "{$this->SLUG}_notify" );

				$notify['dismissed'] = true;
				update_option( "{$this->SLUG}_notify", $notify );

				$result = array(
					'status' => 200
				);
			}

			else
				$result = array(
					'status' => 400,
					'message' => __( "Bad Request", 'wordpress-gallery-extra' ) 
				);
		}

		wp_send_json( $result );
	}
}

?>
