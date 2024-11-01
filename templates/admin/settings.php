<?php
	$page = "settings";
	$options = $this->OPTIONS;
	$pagename = __( 'Settings', 'wordpress-gallery-extra' );
	$tabs = array(
		"wgextra-general"      => __( 'General', 'wordpress-gallery-extra' ),
		"wgextra-media"        => __( 'Media', 'wordpress-gallery-extra' ),
		"wgextra-license"      => __( 'License', 'wordpress-gallery-extra' ),
		"wgextra-requirements" => __( 'System Requirements', 'wordpress-gallery-extra' )
	);
	$box_title_elements = array(
		array(
			"tag" => "button",
			"attributes" => array(
				"type" => "submit",
				"class" => array("submit-button", "blue"),
				"data-style" => "expand-left"
			),
			"nodes" => array(
				__( "Save Changes", 'wordpress-gallery-extra' )
			)
		)
	);
	include_once 'header.php';

	$system_info              = $this->system_info_array();
	$wordpress_is_ok          = version_compare( $system_info['WORDPRESS']['version'], '4.0.0', '>=' );
	$php_is_ok                = version_compare( $system_info['WEBSERVER']['PHP_VERSION'], '5.3.0', '>=' );
	$memory_is_ok             = intval( $system_info['WORDPRESS']['MEMORY_LIMIT'] ) >= 96;
	$upload_is_ok             = intval( $system_info['WEBSERVER']['upload_max_filesize'] ) >= 64;
	$post_is_ok               = intval( $system_info['WEBSERVER']['post_max_size'] ) >= 64;
	$max_input_vars_is_ok     = intval( $system_info['WEBSERVER']['max_input_vars'] ) >= 1000;
	$max_execution_time_is_ok = intval( $system_info['WEBSERVER']['max_execution_time'] ) >= 60;
	$uploads_folder_writable  = is_writable( $this->get_uploads_directory() );

?>
				<form class="warn-about-change" id="wgextra_settings_form" method="post" action="">
					<input type="hidden" name="wgextra_task" value="save_settings">
					<input type="hidden" name="wgextra_nonce" value="<?php echo esc_attr( wp_create_nonce( 'wgextra_save_settings' ) ); ?>">

					<!-- start of inside -->
					<div class="inside ui-tabs-panel" id="wgextra-general">
						<p><?php _e( "Configure general WordPress Gallery Extra settings.", 'wordpress-gallery-extra' ); ?></p>
						<table class="form-table">
							<tr class="ad_opt field" rel="load_library">
								<th><label for="wgextra_items_per_page"><?php _e( 'Items per page', 'wordpress-gallery-extra' ); ?></label></th>
								<td colspan="3">
									<input rel='number' data-options='min:5, max:100' id='wgextra_items_per_page' name='wgextra_items_per_page' value='<?php echo esc_attr( $options['items_per_page'] ); ?>' />
									<p class="description">
										<?php _e( "Number of the displayed items per page in WordPress Gallery Extra plugin dashboard.", 'wordpress-gallery-extra' ); ?>
									</p>
								</td>
							</tr>
							<tr class="ad_opt field" rel="load_library">
								<th><label for="wgextra_load_library"><?php _e( 'Load Library', 'wordpress-gallery-extra' ); ?></label></th>
								<td colspan="3">
									<div class="onoffswitch">
										<input type="checkbox" name="wgextra_load_library" class="onoffswitch-checkbox" id="wgextra_load_library"<?php checked( $options['load_library'], 'yes' ); ?>>
										<label class="onoffswitch-label" for="wgextra_load_library">
											<div class="onoffswitch-inner">
												<div class="onoffswitch-active">ON</div>
												<div class="onoffswitch-inactive">OFF</div>
											</div>
											<div class="onoffswitch-switch"></div>
										</label>
									</div>
									<p class="description">
										<?php _e( "Include WordPress Gallery Extra library globally. If enabled, CSS and JS files of WordPress Gallery Extra will be loaded in all pages. If disabled, CSS and JS files of WordPress Gallery Extra will be only loaded on pages where its shortcode exists.", 'wordpress-gallery-extra' ); ?>
									</p>
								</td>
							</tr>
							<tr class="ad_opt field" rel="debounce_resize">
								<th><label for="wgextra_debounce_resize"><?php _e( 'Debounce Resize', 'wordpress-gallery-extra' ); ?></label></th>
								<td colspan="3">
									<div class="onoffswitch">
										<input type="checkbox" name="wgextra_debounce_resize" class="onoffswitch-checkbox" id="wgextra_debounce_resize"<?php checked( $options['debounce_resize'], 'yes' ); ?>>
										<label class="onoffswitch-label" for="wgextra_debounce_resize">
											<div class="onoffswitch-inner">
												<div class="onoffswitch-active">ON</div>
												<div class="onoffswitch-inactive">OFF</div>
											</div>
											<div class="onoffswitch-switch"></div>
										</label>
									</div>
									<p class="description"><?php _e( "By using debounce resize, you will reduce the number of calculation during resizing the browser. This allows you to improve performance while resizing browser. If you encounter any problem, please deactivate this option.", 'wordpress-gallery-extra' ); ?></p>
								</td>
							</tr>
							<tr class="ad_opt field" rel="crash_report">
								<th><label for="wgextra_crash_report"><?php _e( 'Crash Report', 'wordpress-gallery-extra' ); ?></label></th>
								<td colspan="3">
									<div class="onoffswitch">
										<input type="checkbox" name="wgextra_crash_report" class="onoffswitch-checkbox" id="wgextra_crash_report"<?php checked( $options['crash_report'], 'yes' ); ?>>
										<label class="onoffswitch-label" for="wgextra_crash_report">
											<div class="onoffswitch-inner">
												<div class="onoffswitch-active">ON</div>
												<div class="onoffswitch-inactive">OFF</div>
											</div>
											<div class="onoffswitch-switch"></div>
										</label>
									</div>
									<p class="description"><?php _e( "Crash reports help iProDev fix problems and make \"WordPress Gallery Extra\" more stable and secure.", 'wordpress-gallery-extra' ); ?></p>
								</td>
							</tr>
							<tr class="ad_opt field" rel="delete_data">
								<th><label for="wgextra_delete_data"><?php _e( 'Delete Data', 'wordpress-gallery-extra' ); ?></label></th>
								<td colspan="3">
									<div class="onoffswitch">
										<input type="checkbox" name="wgextra_delete_data" class="onoffswitch-checkbox" id="wgextra_delete_data"<?php checked( $options['delete_data'], 'yes' ); ?>>
										<label class="onoffswitch-label" for="wgextra_delete_data">
											<div class="onoffswitch-inner">
												<div class="onoffswitch-active">ON</div>
												<div class="onoffswitch-inactive">OFF</div>
											</div>
											<div class="onoffswitch-switch"></div>
										</label>
									</div>
									<p class="description">
										<?php _e( "Delete all the data from the database. Even if you delete \"WP Gallery Extra\" from the plugin page, all the data stored in the database will be still available, so you won't lose your settings.", 'wordpress-gallery-extra' ); ?><br>
										<?php _e( "But if you want to permanently delete \"WP Gallery Extra\" and all the data stored in your database, before deactivating and deleting the plugin switch on this field.", 'wordpress-gallery-extra' ); ?>
									</p>
								</td>
							</tr>
<?php
	$this->do_settings_fields( $page, 'general', 'default' );
?>
						</table>
<?php
	$this->do_settings_sections( $page, 'general' );
?>
					</div><!-- end of inside -->

					<!-- start of inside -->
					<div class="inside ui-tabs-panel" id="wgextra-media">
						<p><?php _e( "Configure media settings that will be controlled by WordPress Gallery Extra.", 'wordpress-gallery-extra' ); ?></p>
						<table class="form-table">
							<tr class="ad_opt field" rel="media_taxonomies">
								<th><label for="wgextra_media_taxonomies"><?php _e( 'Media Taxonomies', 'wordpress-gallery-extra' ); ?></label></th>
								<td colspan="3">
									<div class="onoffswitch">
										<input type="checkbox" name="wgextra_media_taxonomies" class="onoffswitch-checkbox" id="wgextra_media_taxonomies"<?php checked( $options['media_taxonomies'], 'yes' ); ?>>
										<label class="onoffswitch-label" for="wgextra_media_taxonomies">
											<div class="onoffswitch-inner">
												<div class="onoffswitch-active">ON</div>
												<div class="onoffswitch-inactive">OFF</div>
											</div>
											<div class="onoffswitch-switch"></div>
										</label>
									</div>
									<p class="description">
										<?php _e( "Enable Categories and Tags for your attachments to group similar images.", 'wordpress-gallery-extra' ); ?>
									</p>
								</td>
							</tr>
							<tr class="ad_opt field" rel="grab_placeholder">
								<th><label for="wgextra_grab_placeholder"><?php _e( 'Capture Placeholder Color', 'wordpress-gallery-extra' ); ?></label></th>
								<td colspan="3">
									<div class="onoffswitch">
										<input type="checkbox" name="wgextra_grab_placeholder" class="onoffswitch-checkbox" id="wgextra_grab_placeholder"<?php checked( $options['grab_placeholder'], 'yes' ); ?>>
										<label class="onoffswitch-label" for="wgextra_grab_placeholder">
											<div class="onoffswitch-inner">
												<div class="onoffswitch-active">ON</div>
												<div class="onoffswitch-inactive">OFF</div>
											</div>
											<div class="onoffswitch-switch"></div>
										</label>
									</div>
									<p class="description">
										<?php _e( "Capture placeholder color automatically while uploading photos.", 'wordpress-gallery-extra' ); ?>
									</p>
								</td>
							</tr>
							<tr class="ad_opt field" rel="import_from_xmp">
								<th><label for="wgextra_import_from_xmp"><?php _e( 'Capture IPTC metadata', 'wordpress-gallery-extra' ); ?></label></th>
								<td colspan="3">
									<div class="onoffswitch">
										<input type="checkbox" name="wgextra_import_from_xmp" class="onoffswitch-checkbox" id="wgextra_import_from_xmp"<?php checked( $options['import_from_xmp'], 'yes' ); ?>>
										<label class="onoffswitch-label" for="wgextra_import_from_xmp">
											<div class="onoffswitch-inner">
												<div class="onoffswitch-active">ON</div>
												<div class="onoffswitch-inactive">OFF</div>
											</div>
											<div class="onoffswitch-switch"></div>
										</label>
									</div>
									<p class="description">
										<?php _e( "Capture keywords and other IPTC metadata automatically while uploading photos.", 'wordpress-gallery-extra' ); ?>
									</p>
								</td>
							</tr>
<?php
	$this->do_settings_fields( $page, 'media', 'default' );
?>
						</table>
<?php
	$this->do_settings_sections( $page, 'media' );
?>
					</div><!-- end of inside -->

					<!-- start of inside -->
					<div class="inside ui-tabs-panel" id="wgextra-license">
						<h3><?php _e( 'Product License', 'wordpress-gallery-extra' ); ?></h3>
						<p><?php _e( "In order to receive all benefits of WordPress Gallery Extra, you need to activate your copy of the plugin. By activating WordPress Gallery Extra license you will unlock premium options - direct plugin updates, access to template library and official support.", 'wordpress-gallery-extra' ); ?></p>
						<div class="button_box activation_box">
							<a class="ui-button large green" href="https://www.iprodev.com/go/wgextra-purchase/"><?php _e( "Download WordPress Gallery Extra Premium Version", 'wordpress-gallery-extra' ); ?></a>
							<p class="description"><?php printf( __( 'Don\'t have direct license yet? <a href="%s" target="_blank" rel="noreferrer noopener">Purchase WordPress Gallery Extra license</a>.', 'wordpress-gallery-extra' ), esc_url( "https://www.iprodev.com/go/wgextra-purchase/" ) ); ?></p>
						</div>
<?php
	$this->do_settings_sections( $page, 'license' );
?>
					</div><!-- end of inside -->

					<!-- start of inside -->
					<div class="inside ui-tabs-panel" id="wgextra-requirements">
						<p><?php _e( "Useful for solving issues with updating, imports and memory.", 'wordpress-gallery-extra' ); ?></p>
						<table class="form-table">
							<thead>
								<tr class="ad_opt">
									<th><label><?php _e( "Requirement", 'wordpress-gallery-extra' ); ?></label></th>
									<th><label><?php _e( "Current", 'wordpress-gallery-extra' ); ?></label></th>
									<th><label><?php _e( "Needed", 'wordpress-gallery-extra' ); ?></label></th>
									<th><label><?php _e( "Status", 'wordpress-gallery-extra' ); ?></label></th>
								</tr>
							</thead>
							<tbody>
								<tr class="ad_opt">
									<td class="padding-left-none">
										<?php _e( "WordPress Version", 'wordpress-gallery-extra' ); ?>
									</td>
									<td class="padding-left-none">
										<?php echo $system_info['WORDPRESS']['version']; ?>
									</td>
									<td class="padding-left-none">
										4.0.0
									</td>
									<td class="padding-left-none<?php if ( $wordpress_is_ok ) echo " success"; else echo " alert"; ?>">
										<span class="dashicons dashicons-<?php if ( $wordpress_is_ok ) echo "yes"; else echo "no-alt"; ?>"></span>
									</td>
								</tr>
								<tr class="ad_opt">
									<td class="padding-left-none">
										<?php _e( "PHP Version", 'wordpress-gallery-extra' ); ?>
									</td>
									<td class="padding-left-none">
										<?php echo $system_info['WEBSERVER']['PHP_VERSION']; ?>
									</td>
									<td class="padding-left-none">
										5.3.0
									</td>
									<td class="padding-left-none<?php if ( $php_is_ok ) echo " success"; else echo " alert"; ?>">
										<span class="dashicons dashicons-<?php if ( $php_is_ok ) echo "yes"; else echo "no-alt"; ?>"></span>
									</td>
								</tr>
								<tr class="ad_opt">
									<td class="padding-left-none">
										<?php _e( "Memory Limit", 'wordpress-gallery-extra' ); ?>
									</td>
									<td class="padding-left-none">
										<?php echo $system_info['WORDPRESS']['MEMORY_LIMIT']; ?>
									</td>
									<td class="padding-left-none">
										<?php printf( __( "%s (recommended)", 'wordpress-gallery-extra' ), '96M' ); ?>
									</td>
									<td class="padding-left-none<?php if ( $memory_is_ok ) echo " success"; else echo " alert"; ?>">
										<span class="dashicons dashicons-<?php if ( $memory_is_ok ) echo "yes"; else echo "no-alt"; ?>"></span>
									</td>
								</tr>
								<tr class="ad_opt">
									<td class="padding-left-none">
										<?php _e( "Max. Upload Filesize", 'wordpress-gallery-extra' ); ?>
									</td>
									<td class="padding-left-none">
										<?php echo $system_info['WEBSERVER']['upload_max_filesize']; ?>
									</td>
									<td class="padding-left-none">
										<?php printf( __( "%s (recommended)", 'wordpress-gallery-extra' ), '64M' ); ?>
									</td>
									<td class="padding-left-none<?php if ( $upload_is_ok ) echo " success"; else echo " alert"; ?>">
										<span class="dashicons dashicons-<?php if ( $upload_is_ok ) echo "yes"; else echo "no-alt"; ?>"></span>
									</td>
								</tr>
								<tr class="ad_opt">
									<td class="padding-left-none">
										<?php _e( "Max. Post Size", 'wordpress-gallery-extra' ); ?>
									</td>
									<td class="padding-left-none">
										<?php echo $system_info['WEBSERVER']['post_max_size']; ?>
									</td>
									<td class="padding-left-none">
										<?php printf( __( "%s (recommended)", 'wordpress-gallery-extra' ), '64M' ); ?>
									</td>
									<td class="padding-left-none<?php if ( $post_is_ok ) echo " success"; else echo " alert"; ?>">
										<span class="dashicons dashicons-<?php if ( $post_is_ok ) echo "yes"; else echo "no-alt"; ?>"></span>
									</td>
								</tr>
								<tr class="ad_opt">
									<td class="padding-left-none">
										<?php _e( "Max. Input Variables", 'wordpress-gallery-extra' ); ?>
									</td>
									<td class="padding-left-none">
										<?php echo $system_info['WEBSERVER']['max_input_vars']; ?>
									</td>
									<td class="padding-left-none">
										1000
									</td>
									<td class="padding-left-none<?php if ( $max_input_vars_is_ok ) echo " success"; else echo " alert"; ?>">
										<span class="dashicons dashicons-<?php if ( $max_input_vars_is_ok ) echo "yes"; else echo "no-alt"; ?>"></span>
									</td>
								</tr>
								<tr class="ad_opt">
									<td class="padding-left-none">
										<?php _e( "Max. Execution Time", 'wordpress-gallery-extra' ); ?>
									</td>
									<td class="padding-left-none">
										<?php echo $system_info['WEBSERVER']['max_execution_time']; ?>
									</td>
									<td class="padding-left-none">
										60
									</td>
									<td class="padding-left-none<?php if ( $max_execution_time_is_ok ) echo " success"; else echo " alert"; ?>">
										<span class="dashicons dashicons-<?php if ( $max_execution_time_is_ok ) echo "yes"; else echo "no-alt"; ?>"></span>
									</td>
								</tr>
								<tr class="ad_opt">
									<td class="padding-left-none">
										<?php _e( "Uploads folder writable", 'wordpress-gallery-extra' ); ?>
									</td>
									<td class="padding-left-none">
										<?php _e( $uploads_folder_writable ? "Yes" : "No", 'wordpress-gallery-extra' ); ?>
									</td>
									<td class="padding-left-none">
										<?php _e( "Yes", 'wordpress-gallery-extra' ); ?>
									</td>
									<td class="padding-left-none<?php if ( $uploads_folder_writable ) echo " success"; else echo " alert"; ?>">
										<span class="dashicons dashicons-<?php if ( $uploads_folder_writable ) echo "yes"; else echo "no-alt"; ?>"></span>
									</td>
								</tr>
								<tr class="ad_opt">
									<td class="padding-left-none">
										<?php _e( "Contact iProDev Server", 'wordpress-gallery-extra' ); ?>
									</td>
									<td class="padding-left-none">
										<a class="ui-button small" id="check_iprodev_contact"><?php _e( "Check Now", 'wordpress-gallery-extra' ); ?></a>
									</td>
									<td class="padding-left-none">
										<?php _e( "Yes", 'wordpress-gallery-extra' ); ?>
									</td>
									<td class="padding-left-none">
										<span class="dashicons"></span>
									</td>
								</tr>
							</tbody>
							<tfoot>
								<tr class="ad_opt">
									<th><label><?php _e( "Requirement", 'wordpress-gallery-extra' ); ?></label></th>
									<th><label><?php _e( "Current", 'wordpress-gallery-extra' ); ?></label></th>
									<th><label><?php _e( "Needed", 'wordpress-gallery-extra' ); ?></label></th>
									<th><label><?php _e( "Status", 'wordpress-gallery-extra' ); ?></label></th>
								</tr>
							</tfoot>
						</table>
<?php
	$this->do_settings_sections( $page, 'license' );
?>
					</div><!-- end of inside -->
				</form>
<?php
	include_once "footer.php";
?>