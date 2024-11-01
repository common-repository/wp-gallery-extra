<?php
	$page = "templates";
	$templates = $this->TEMPLATES;

	if ( !isset( $_GET['id'] ) ) {
		$templates_num = count( $templates );

		// Sort templates
		krsort( $templates );

		$per_page = $this->OPTIONS['items_per_page'];
		$total_pages = ceil( $templates_num / $per_page );
		$current_page = intval( isset( $_GET['p'] ) ? ( ( $_GET['p'] > $total_pages || $_GET['p'] < 1 ) ? 1 : $_GET['p'] ) : 1 );
		$pages_templates = array_chunk( $templates, $per_page, true );
		$page_templates = $pages_templates[$current_page - 1];
		$urlPattern = 'admin.php?page=wgextra&p=(:num)';

		$paginator = new JasonGrimes\Paginator( $templates_num, $per_page, $current_page, $urlPattern );
		$paginator->setMaxPagesToShow( 4 );

		$pagename = __( 'Templates', 'wordpress-gallery-extra' );

		$box_title_elements = array(
			array(
				"tag" => "div",
				"attributes" => array(
					"class" => array("controlgroup", "templates", "float-right"),
				),
				"nodes" => array(
					array(
						"tag" => "button",
						"attributes" => array(
							"id" => "wgextra_import_template",
							"class" => array("ui-button", "grey"),
						),
						"nodes" => array(
							array(
								"tag" => "span",
								"attributes" => array(
									"class" => array("dashicons", "dashicons-migrate"),
								)
							),
							__( "Import Template", 'wordpress-gallery-extra' )
						)
					),
					array(
						"tag" => "button",
						"attributes" => array(
							"id" => "wgextra_new_template",
							"class" => array("ui-button", "grey"),
						),
						"nodes" => array(
							array(
								"tag" => "span",
								"attributes" => array(
									"class" => array("dashicons", "dashicons-plus-alt"),
								)
							),
							__( "New Template", 'wordpress-gallery-extra' )
						)
					)
				)
			)
		);

		if ( $paginator->getNumPages() > 1 ) {
			$pagination = array(
				"tag" => "ul",
				"attributes" => array(
					"class" => array("pagination"),
				),
				"nodes" => array()
			);

			if ( $paginator->getPrevUrl() ) {
				$pagination['nodes'][] = array(
					"tag" => "li",
					"attributes" => array(
						"class" => array("prev"),
					),
					"nodes" => array(
						array(
							"tag" => "a",
							"attributes" => array(
								"href" => $paginator->getPrevUrl()
							),
							"nodes" => array(
								__( "Previous", 'wordpress-gallery-extra' )
							)
						)
					)
				);
			}

			foreach ( $paginator->getPages() as $page ) {
				if ( $page['url'] ) {
					$pagination['nodes'][] = array(
						"tag" => "li",
						"attributes" => array(
							"class" => $page['isCurrent'] ? array("active") : array(),
						),
						"nodes" => array(
							array(
								"tag" => "a",
								"attributes" => array(
									"href" => $page['url']
								),
								"nodes" => array(
									(string) $page['num']
								)
							)
						)
					);
				}
				else {
					$pagination['nodes'][] = array(
						"tag" => "li",
						"attributes" => array(
							"class" => array("disabled")
						),
						"nodes" => array(
							array(
								"tag" => "span",
								"attributes" => array(),
								"nodes" => array(
									(string) $page['num']
								)
							)
						)
					);
				}
			}

			if ( $paginator->getNextUrl() ) {
				$pagination['nodes'][] = array(
					"tag" => "li",
					"attributes" => array(
						"class" => array("next"),
					),
					"nodes" => array(
						array(
							"tag" => "a",
							"attributes" => array(
								"href" => $paginator->getNextUrl()
							),
							"nodes" => array(
								__( "Next", 'wordpress-gallery-extra' )
							)
						)
					)
				);
			}

			array_unshift( $box_title_elements, $pagination );
		}

		include_once 'header.php';
?>
				<div class="inside">
					<p class="float-left"><?php _e( "Extend your WordPress native gallery with creating nice gallery templates.", 'wordpress-gallery-extra' ); ?></p>

					<div class="clear"></div>

					<p id="empty_templates"<?php if ( !empty( $templates ) ) echo " style='display: none;'"; ?>><?php _e( "No template exists, please create one.", 'wordpress-gallery-extra' ); ?></p>

					<table class="form-table"<?php if ( empty( $templates ) ) echo " style='display: none;'"; ?>>
<?php

				foreach ( $page_templates as $id => $template ) {
					$name = $template['name'];
					$user_info = get_userdata( $template['uid'] );
?>
						<tr>
							<th><?php echo $name; ?></th>
							<td>
								<div class="inside-table">
									<table class="form-table middle">
										<thead>
											<tr>
												<th><label><?php _e( "ID", 'wordpress-gallery-extra' ); ?></label></th>
												<th><label><?php _e( "Author", 'wordpress-gallery-extra' ); ?></label></th>
												<th><label><?php _e( "Type", 'wordpress-gallery-extra' ); ?></label></th>
												<th><label><?php _e( "Last Modify", 'wordpress-gallery-extra' ); ?></label></th>
												<th><label><?php _e( "Actions", 'wordpress-gallery-extra' ); ?></label></th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<?php echo $id; ?>
												</td>
												<td>
													<?php echo $user_info->display_name; ?>
												</td>
												<td>
<?php
					echo $this->TEMPLATES_TYPES[$template['template']]['name'];
?>
												</td>
												<td>
													<?php printf( __( "%s ago", 'wordpress-gallery-extra' ), human_time_diff( $template['lastEdit'] ) ); ?>
												</td>
												<td nowrap>
													<a href="<?php echo admin_url( 'admin.php?page=wgextra' ); ?>&id=<?php echo $id; ?>" class="ui-button green"><span class="dashicons dashicons-edit"></span><?php _e( "Edit", 'wordpress-gallery-extra' ); ?></a>
													<a rel="duplicate_template" identifier="<?php echo $id; ?>" class="ui-button grey"><span class="dashicons dashicons-admin-page"></span><?php _e( "Duplicate", 'wordpress-gallery-extra' ); ?></a>
													<a rel="delete_template" identifier="<?php echo $id; ?>" class="ui-button red"><span class="dashicons dashicons-trash"></span><?php _e( "Delete", 'wordpress-gallery-extra' ); ?></a>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</td>
						</tr>
<?php
				}
?>
					</table>
				</div><!-- end of inside -->
				<script id="new_template_row" type="text/template">
<tr>
	<th><%= name %></th>
	<td>
		<div class="inside-table">
			<table class="form-table middle">
				<thead>
					<tr>
						<th><label><?php _e( "ID", 'wordpress-gallery-extra' ); ?></label></th>
						<th><label><?php _e( "Author", 'wordpress-gallery-extra' ); ?></label></th>
						<th><label><?php _e( "Type", 'wordpress-gallery-extra' ); ?></label></th>
						<th><label><?php _e( "Last Modify", 'wordpress-gallery-extra' ); ?></label></th>
						<th><label><?php _e( "Actions", 'wordpress-gallery-extra' ); ?></label></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<%= id %>
						</td>
						<td>
							<%= user %>
						</td>
						<td>
							<%= template %>
						</td>
						<td>
							<%= last_modify %>
						</td>
						<td nowrap>
							<a href="<?php echo admin_url( 'admin.php?page=wgextra' ); ?>&id=<%= id %>" class="ui-button green"><span class="dashicons dashicons-edit"></span><?php _e( "Edit", 'wordpress-gallery-extra' ); ?></a>
							<a rel="duplicate_template" identifier="<%= id %>" class="ui-button grey"><span class="dashicons dashicons-admin-page"></span><?php _e( "Duplicate", 'wordpress-gallery-extra' ); ?></a>
							<a rel="delete_template" identifier="<%= id %>" class="ui-button red"><span class="dashicons dashicons-trash"></span><?php _e( "Delete", 'wordpress-gallery-extra' ); ?></a>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</td>
</tr>
				</script>
				<script id="show_templates" type="text/template">
					<div id="wgextra_show_templates" title="<%= title %>">
						<div class="top-columns">
							<div class="search_field_column">
								<input type="search" placeholder="<?php esc_attr_e( "Type to Search...", 'wordpress-gallery-extra' ); ?>" />
							</div>
							<div class="template_type_column">
								<select id="template_type">
									<option value=""><?php _e( "All", 'wordpress-gallery-extra' ); ?></option>
									<option value=".columns"><?php _e( "Columns", 'wordpress-gallery-extra' ); ?></option>
									<option value=".justified"><?php _e( "Justified", 'wordpress-gallery-extra' ); ?></option>
									<option value=".masonry"><?php _e( "Masonry", 'wordpress-gallery-extra' ); ?></option>
									<option value=".mosaic"><?php _e( "Mosaic", 'wordpress-gallery-extra' ); ?></option>
									<option value=".slider"><?php _e( "Slider", 'wordpress-gallery-extra' ); ?></option>
								</select>
							</div>
						</div>
						<div class="scroller">
							<% if (Object.keys(templates).length) { %>
							<div id="importable-templates">
								<% _.each(templates, function(template, id){ %>
								<div class="template <%= template.types.join(' ') %>" data-date="<%= template.date %>" data-name="<%= template.name %>">
									<div class="template-inner">
										<img src="<%= template.screenshot.medium_large.url %>" srcset="<%= template.screenshot.medium_large.url %> <%= template.screenshot.medium_large.width %>w, <%= template.screenshot.full.url %> <%= template.screenshot.full.width %>w" sizes="(max-width: <%= template.screenshot.medium_large.width %>px) 100vw, <%= template.screenshot.medium_large.width %>px">
										<a class="template-action preview" href="<%= template.url %>" target="_blank" rel="noreferrer noopener"><?php _e( "Preview", 'wordpress-gallery-extra' ); ?></a>
										<a class="template-action import" rel="<%= id %>"><?php _e( "Import", 'wordpress-gallery-extra' ); ?></a>
									</div>
									<div class="template-name"><%= template.name %></div>
								</div>
								<% }); %>
							</div>
							<% } else { %>
							<p class="empty_templates"><?php _e( "No template exists, please create one.", 'wordpress-gallery-extra' ); ?></p>
							<% } %>
						</div>
					</div>
				</script>
			<?php
			}
			else {
				$id = $_GET['id'];
				$template = $templates[$id];
				$default_options = $this->DEFAULT_TEMPLATE_OPTIONS;
				$options = array_replace_recursive( $default_options, $template );
				$tabs = array(
					"wgextra-settings" => __( 'Settings', 'wordpress-gallery-extra' ),
					"wgextra-source" => __( 'Source', 'wordpress-gallery-extra' ),
					"wgextra-display" => __( 'Display', 'wordpress-gallery-extra' ),
					"wgextra-styling" => __( 'Styling', 'wordpress-gallery-extra' )
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

				$target_options = apply_filters( 'wgextra_attachment_field_custom_target_options', array(
					'_self'     => __( 'Open on the same page (_self)', 'wordpress-gallery-extra' ),
					'_blank'    => __( 'Open on new page (_blank)', 'wordpress-gallery-extra' ),
					'_parent'   => __( 'Open in parent frame (_parent)', 'wordpress-gallery-extra' ),
					'_top'      => __( 'Open in main frame (_top)', 'wordpress-gallery-extra' ),
					'_lightbox' => __( 'Open in LightBox (_lightbox)', 'wordpress-gallery-extra' ),
					'_video'    => __( 'Open Video in LightBox (_video)', 'wordpress-gallery-extra' ),
					'_audio'    => __( 'Open Audio in LightBox (_audio)', 'wordpress-gallery-extra' )
				) );
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

				$preset_styles = apply_filters( 'wgextra_preset_styles', array(
					array(
						"style" => "border: 3px solid #FFF; box-shadow: 0 11px 15px -8px rgba(0, 0, 0, 0.3);",
						"form_data" => array(
							"wgextra_style_border" => true,
							"wgextra_style_shadow" => true,
							"wgextra_style_border_radius" => 0,
							"wgextra_style_border_weight" => 3,
							"wgextra_style_border_style" => "solid",
							"wgextra_style_border_color" => "rgba(255, 255, 255, 1)",
							"wgextra_style_shadow_x" => 0,
							"wgextra_style_shadow_y" => 11,
							"wgextra_style_shadow_blur" => 15,
							"wgextra_style_shadow_spread" => -8,
							"wgextra_style_shadow_color" => "rgba(0, 0, 0, 0.3)",
							"wgextra_style_shadow_inset" => false,
							"wgextra_style_margin" => 15,
							"wgextra_style_icon" => true,
							"wgextra_style_icon_icon" => "eye",
							"wgextra_style_icon_color" => "rgba(153, 153, 153, 1)",
							"wgextra_style_overlay" => true,
							"wgextra_style_overlay_background" => "solid",
							"wgextra_style_overlay_background_solid_color" => "rgba(255, 255, 255, 0.7)"
						)
					),
					array(
						"style" => "border: 6px solid #000; box-shadow: 0 14px 5px -9px rgba(0, 0, 0, 0.7);",
						"form_data" => array(
							"wgextra_style_border" => true,
							"wgextra_style_shadow" => true,
							"wgextra_style_border_radius" => 0,
							"wgextra_style_border_weight" => 6,
							"wgextra_style_border_style" => "solid",
							"wgextra_style_border_color" => "rgba(0, 0, 0, 1)",
							"wgextra_style_shadow_x" => 0,
							"wgextra_style_shadow_y" => 14,
							"wgextra_style_shadow_blur" => 5,
							"wgextra_style_shadow_spread" => -9,
							"wgextra_style_shadow_color" => "rgba(0, 0, 0, 0.7)",
							"wgextra_style_shadow_inset" => false,
							"wgextra_style_margin" => 15,
							"wgextra_style_icon" => true,
							"wgextra_style_icon_icon" => "magnifier-3",
							"wgextra_style_icon_color" => "rgba(255, 255, 255, 1)",
							"wgextra_style_overlay" => true,
							"wgextra_style_overlay_background" => "solid",
							"wgextra_style_overlay_background_solid_color" => "rgba(0, 0, 0, 0.7)"
						)
					),
					array(
						"style" => "box-shadow: 0 0 55px rgba(0, 0, 0, .2); filter: url(\"data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg'><filter id='grayscale'><feColorMatrix type='matrix' values='0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0'/></filter></svg>#grayscale\"); filter: gray; filter: grayscale(100%); -webkit-filter: grayscale(100%); border-radius: 7px;",
						"form_data" => array(
							"wgextra_style_border" => true,
							"wgextra_style_shadow" => true,
							"wgextra_style_border_radius" => 7,
							"wgextra_style_border_weight" => 0,
							"wgextra_style_border_style" => "solid",
							"wgextra_style_border_color" => "rgba(0, 0, 0, 1)",
							"wgextra_style_shadow_x" => 0,
							"wgextra_style_shadow_y" => 0,
							"wgextra_style_shadow_blur" => 55,
							"wgextra_style_shadow_spread" => 0,
							"wgextra_style_shadow_color" => "rgba(0, 0, 0, 0.2)",
							"wgextra_style_shadow_inset" => false,
							"wgextra_style_margin" => 110,
							"wgextra_style_thumbnail_effect" => "colorize"
						)
					),
					array(
						"style" => "border: 10px solid #fff; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);",
						"form_data" => array(
							"wgextra_style_border" => true,
							"wgextra_style_shadow" => true,
							"wgextra_style_border_radius" => 0,
							"wgextra_style_border_weight" => 10,
							"wgextra_style_border_style" => "solid",
							"wgextra_style_border_color" => "rgba(255, 255, 255, 1)",
							"wgextra_style_shadow_x" => 0,
							"wgextra_style_shadow_y" => 1,
							"wgextra_style_shadow_blur" => 2,
							"wgextra_style_shadow_spread" => 0,
							"wgextra_style_shadow_color" => "rgba(0, 0, 0, 0.15)",
							"wgextra_style_shadow_inset" => false,
							"wgextra_style_margin" => 20
						)
					)
				) );

				$css3_easings = apply_filters( 'wgextra_style_css3_easings', array(
					"" => __( "None", 'wordpress-gallery-extra' ),
					"linear" => "linear",
					"ease" => "ease",
					"ease-in" => "ease-in",
					"ease-out" => "ease-out",
					"ease-in-out" => "ease-in-out"
				) );
				$jquery_easings = apply_filters( 'wgextra_style_jquery_easings', array(
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
					'wgextra_style_icon_visibilities',
					array_merge(
						array(
							"visible" => __( "Always Visible", 'wordpress-gallery-extra' )
						),
						$animate_css_effects
					)
				);
				$caption_visibilities = apply_filters(
					'wgextra_style_caption_visibilities',
					array_merge(
						array(
							"visible" => __( "Always Visible", 'wordpress-gallery-extra' )
						),
						$animate_css_effects
					)
				);
				$overlay_visibilities = apply_filters(
					'wgextra_style_overlay_visibilities',
					array_merge(
						array(
							"visible" => __( "Always Visible", 'wordpress-gallery-extra' )
						),
						$animate_css_effects
					)
				);
				$thumbnail_effects = apply_filters(
					'wgextra_style_thumbnail_effects',
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
					'wgextra_style_slider_arrows_skins',
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
					'wgextra_style_slider_scrollbar_skins',
					array(
						"default" => __( "Default", 'wordpress-gallery-extra' ),
						"scale-up" => __( "Scale Up", 'wordpress-gallery-extra' ),
						"white-light" => __( "White Light", 'wordpress-gallery-extra' ),
						"silver" => __( "Silver", 'wordpress-gallery-extra' ),
						"slim-bar" => __( "Slim Bar", 'wordpress-gallery-extra' )
					)
				);
				$slider_thumbnails_skins = apply_filters(
					'wgextra_style_slider_thumbnails_skins',
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
					'wgextra_style_slider_bullets_skins',
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
					'wgextra_style_slider_time_loader_skins',
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

				$icons_json = file_get_contents( plugin_dir_path( $this->MAIN ) . "assets/css/icons.json" );
				$icons = json_decode( $icons_json, true );
?>
				<form class="warn-about-change" id="wgextra_template_form" method="post" action="">
					<input type="hidden" name="wgextra_task" value="save_template_settings">
					<input type="hidden" name="wgextra_nonce" value="<?php echo esc_attr( wp_create_nonce( 'wgextra_save_template_settings' ) ); ?>">
					<input type="hidden" name="id" value="<?php echo $id; ?>">
					<div class="inside ui-tabs-panel" id="wgextra-settings">
						<p><?php _e( "Personalize your gallery template with the following settings.", 'wordpress-gallery-extra' ); ?></p>
						<table class="form-table">
							<tr>
								<th><label for="wgextra_name"><?php _e( "Name", 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<input type="text" id="wgextra_name" name="wgextra_name" value="<?php echo esc_attr( $options['name'] ); ?>" /><br>
									<p class="description"><?php _e( "Enter the name of the current template. The template name must be unique in order to prevent any conflict.", 'wordpress-gallery-extra' ); ?></p>
								</td>
							</tr>
							<tr>
								<th><label for="wgextra_template"><?php _e( "Gallery Type", 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<select id="wgextra_template" name="wgextra_template">
<?php
				foreach ( $this->TEMPLATES_TYPES as $key => $value ) {
?>
										<option value="<?php echo esc_attr( $key ); ?>"<?php selected( $options['template'], $key ); ?>><?php echo $value['name']; ?></option>
<?php
				}
?>
									</select><br />
									<p class="description"><?php _e( "The gallery type that will be used when the gallery is output to the frontend.", 'wordpress-gallery-extra' ); ?></p>
								</td>
							</tr>
							<tr class="field" rel="loading_type">
								<th><label><?php _e( 'Loading Type', 'wordpress-gallery-extra' ); ?></label></th>
								<td colspan="3">
									<div class="switch-field">
										<label for="wgextra_loading_type-none"><?php _e( "None", 'wordpress-gallery-extra' ); ?></label>
										<input type="radio" name="wgextra_loading_type" id="wgextra_loading_type-none" value="none"<?php checked( $options['loading_type'], 'none' ); ?> />
										<label for="wgextra_loading_type-indicator" title="<?php _e( "An animated loading animation indicator is shown before the thumbnails have loaded.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Loading Indicator", 'wordpress-gallery-extra' ); ?></label>
										<input type="radio" name="wgextra_loading_type" id="wgextra_loading_type-indicator" value="indicator"<?php checked( $options['loading_type'], 'indicator' ); ?> />
									</div>
									<p class="description"><?php _e( "Thumbnails loading type in the grid.", 'wordpress-gallery-extra' ); ?></p>
								</td>
							</tr>
							<tr class="field" rel="lightbox">
								<th><label><?php _e( 'Lightbox Type', 'wordpress-gallery-extra' ); ?></label></th>
								<td colspan="3">
									<div class="switch-field">
										<label for="wgextra_lightbox_type-none"><?php _e( "None", 'wordpress-gallery-extra' ); ?></label>
										<input type="radio" name="wgextra_lightbox_type" id="wgextra_lightbox_type-none" value="none"<?php checked( $options['lightbox_type'], 'none' ); ?> />
										<label for="wgextra_lightbox_type-magnific"><?php _e( "Magnific Popup", 'wordpress-gallery-extra' ); ?></label>
										<input type="radio" name="wgextra_lightbox_type" id="wgextra_lightbox_type-magnific" value="magnific"<?php checked( $options['lightbox_type'], 'magnific' ); ?> />
										<label for="wgextra_lightbox_type-ilightbox"<?php if( !class_exists( "iLightBox" ) ) { ?> title="<?php esc_attr_e( 'Please download and activate iLightBox plugin to enable this feature.', 'wordpress-gallery-extra' ); ?>"<?php } ?>><?php _e( "iLightBox", 'wordpress-gallery-extra' ); ?></label>
										<input type="radio" name="wgextra_lightbox_type" id="wgextra_lightbox_type-ilightbox" value="ilightbox"<?php checked( $options['lightbox_type'], 'ilightbox' ); ?><?php if( !class_exists( "iLightBox" ) ) { ?> disabled="disabled"<?php } ?> />
									</div>
									<p class="description"><?php _e( 'Choose the default LightBox to be used.', 'wordpress-gallery-extra' ); ?> <?php printf( __( '<a target="_blank" rel="noreferrer noopener" href="%s">Download and activate iLightBox</a> which works flawlessly with WordPress Gallery Extra.', 'wordpress-gallery-extra' ), esc_url( "http://goo.gl/DlaJq" ) ); ?></p>
									<div class="inside-table" id="magnific-popup-settings">
										<table class="form-table">
											<tbody>
												<tr>
													<th><label for="wgextra_lightbox_magnific_animation" title="<?php esc_attr_e( "Popup opening & closing animation type.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Animation", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgextra_lightbox_magnific_vertical_fit" title="<?php esc_attr_e( "Fits image in area vertically.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Vertical Fit", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgextra_lightbox_magnific_preload" title="<?php esc_attr_e( "Wait for images to load before displaying?", 'wordpress-gallery-extra' ); ?>"><?php _e( "Preload", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgextra_lightbox_magnific_deeplink" title="<?php esc_attr_e( "Enabling the hash linking images.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Deeplinking", 'wordpress-gallery-extra' ); ?></label></th>
												</tr>
												<tr>
													<td>
														<select name="wgextra_lightbox_magnific_animation" id="wgextra_lightbox_magnific_animation">
															<option value="mfp-none"<?php selected( $options['lightbox_magnific']['animation'], 'mfp-none' ); ?>><?php _e( "None", 'wordpress-gallery-extra' ); ?></option>
															<option value="mfp-fade"<?php selected( $options['lightbox_magnific']['animation'], 'mfp-fade' ); ?>><?php _e( "Fade", 'wordpress-gallery-extra' ); ?></option>
															<option value="mfp-zoom-in"<?php selected( $options['lightbox_magnific']['animation'], 'mfp-zoom-in' ); ?>><?php _e( "Zoom In Out", 'wordpress-gallery-extra' ); ?></option>
															<option value="mfp-newspaper"<?php selected( $options['lightbox_magnific']['animation'], 'mfp-newspaper' ); ?>><?php _e( "Newspaper", 'wordpress-gallery-extra' ); ?></option>
															<option value="mfp-move-horizontal"<?php selected( $options['lightbox_magnific']['animation'], 'mfp-move-horizontal' ); ?>><?php _e( "Move Horizontal", 'wordpress-gallery-extra' ); ?></option>
															<option value="mfp-move-vertical"<?php selected( $options['lightbox_magnific']['animation'], 'mfp-move-vertical' ); ?>><?php _e( "Move Vertical", 'wordpress-gallery-extra' ); ?></option>
															<option value="mfp-3d-unfold"<?php selected( $options['lightbox_magnific']['animation'], 'mfp-3d-unfold' ); ?>><?php _e( "3d Unfold", 'wordpress-gallery-extra' ); ?></option>
															<option value="mfp-zoom-out"<?php selected( $options['lightbox_magnific']['animation'], 'mfp-zoom-out' ); ?>><?php _e( "Zoom Out In", 'wordpress-gallery-extra' ); ?></option>
														</select>
													</td>
													<td>
														<div class="onoffswitch">
															<input type="checkbox" name="wgextra_lightbox_magnific_vertical_fit" class="onoffswitch-checkbox" id="wgextra_lightbox_magnific_vertical_fit"<?php checked( $options['lightbox_magnific']['vertical_fit'], 'yes' ); ?>>
															<label class="onoffswitch-label" for="wgextra_lightbox_magnific_vertical_fit">
																<div class="onoffswitch-inner">
																	<div class="onoffswitch-active">ON</div>
																	<div class="onoffswitch-inactive">OFF</div>
																</div>
																<div class="onoffswitch-switch"></div>
															</label>
														</div>
													</td>
													<td>
														<div class="onoffswitch">
															<input type="checkbox" name="wgextra_lightbox_magnific_preload" class="onoffswitch-checkbox" id="wgextra_lightbox_magnific_preload"<?php checked( $options['lightbox_magnific']['preload'], 'yes' ); ?>>
															<label class="onoffswitch-label" for="wgextra_lightbox_magnific_preload">
																<div class="onoffswitch-inner">
																	<div class="onoffswitch-active">ON</div>
																	<div class="onoffswitch-inactive">OFF</div>
																</div>
																<div class="onoffswitch-switch"></div>
															</label>
														</div>
													</td>
													<td>
														<div class="onoffswitch">
															<input type="checkbox" name="wgextra_lightbox_magnific_deeplink" class="onoffswitch-checkbox" id="wgextra_lightbox_magnific_deeplink"<?php checked( $options['lightbox_magnific']['deeplink'], 'yes' ); ?>>
															<label class="onoffswitch-label" for="wgextra_lightbox_magnific_deeplink">
																<div class="onoffswitch-inner">
																	<div class="onoffswitch-active">ON</div>
																	<div class="onoffswitch-inactive">OFF</div>
																</div>
																<div class="onoffswitch-switch"></div>
															</label>
														</div>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="inside-table" id="ilightbox-settings">
										<table class="form-table">
											<tbody>
												<tr>
													<th><label for="wgextra_lightbox_ilightbox_skin"><?php _e( "Skin", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgextra_lightbox_ilightbox_direction" title="<?php esc_attr_e( "Sets direction for switching windows.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Direction", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgextra_lightbox_ilightbox_loop" title="<?php esc_attr_e( "Enable infinite lightbox navigation.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Loop", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgextra_lightbox_ilightbox_carousel_mode" title="<?php esc_attr_e( "Enable carousel style lightbox navigation.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Carousel Mode", 'wordpress-gallery-extra' ); ?></label></th>
												</tr>
												<tr>
													<td>
														<select name="wgextra_lightbox_ilightbox_skin" id="wgextra_lightbox_ilightbox_skin">
															<option value="flat-dark"<?php selected( $options['lightbox_ilightbox']['skin'], 'flat-dark' ); ?>><?php _e( "Flat Dark", 'wordpress-gallery-extra' ); ?></option>
															<option value="dark"<?php selected( $options['lightbox_ilightbox']['skin'], 'dark' ); ?>><?php _e( "Dark", 'wordpress-gallery-extra' ); ?></option>
															<option value="light"<?php selected( $options['lightbox_ilightbox']['skin'], 'light' ); ?>><?php _e( "Light", 'wordpress-gallery-extra' ); ?></option>
															<option value="smooth"<?php selected( $options['lightbox_ilightbox']['skin'], 'smooth' ); ?>><?php _e( "Smooth", 'wordpress-gallery-extra' ); ?></option>
															<option value="metro-black"<?php selected( $options['lightbox_ilightbox']['skin'], 'metro-black' ); ?>><?php _e( "Metro Black", 'wordpress-gallery-extra' ); ?></option>
															<option value="metro-white"<?php selected( $options['lightbox_ilightbox']['skin'], 'metro-white' ); ?>><?php _e( "Metro White", 'wordpress-gallery-extra' ); ?></option>
															<option value="mac"<?php selected( $options['lightbox_ilightbox']['skin'], 'mac' ); ?>><?php _e( "Mac", 'wordpress-gallery-extra' ); ?></option>
														</select>
													</td>
													<td>
														<select name="wgextra_lightbox_ilightbox_direction" id="wgextra_lightbox_ilightbox_direction">
															<option value="horizontal"<?php selected( $options['lightbox_ilightbox']['direction'], 'horizontal' ); ?>><?php _e( "Horizontal", 'wordpress-gallery-extra' ); ?></option>
															<option value="vertical"<?php selected( $options['lightbox_ilightbox']['direction'], 'vertical' ); ?>><?php _e( "Vertical", 'wordpress-gallery-extra' ); ?></option>
														</select>
													</td>
													<td>
														<div class="onoffswitch">
															<input type="checkbox" name="wgextra_lightbox_ilightbox_loop" class="onoffswitch-checkbox" id="wgextra_lightbox_ilightbox_loop"<?php checked( $options['lightbox_ilightbox']['loop'], 'yes' ); ?>>
															<label class="onoffswitch-label" for="wgextra_lightbox_ilightbox_loop">
																<div class="onoffswitch-inner">
																	<div class="onoffswitch-active">ON</div>
																	<div class="onoffswitch-inactive">OFF</div>
																</div>
																<div class="onoffswitch-switch"></div>
															</label>
														</div>
													</td>
													<td>
														<div class="onoffswitch">
															<input type="checkbox" name="wgextra_lightbox_ilightbox_carousel_mode" class="onoffswitch-checkbox" id="wgextra_lightbox_ilightbox_carousel_mode"<?php checked( $options['lightbox_ilightbox']['carousel_mode'], 'yes' ); ?>>
															<label class="onoffswitch-label" for="wgextra_lightbox_ilightbox_carousel_mode">
																<div class="onoffswitch-inner">
																	<div class="onoffswitch-active">ON</div>
																	<div class="onoffswitch-inactive">OFF</div>
																</div>
																<div class="onoffswitch-switch"></div>
															</label>
														</div>
													</td>
												</tr>
												<tr>
													<td colspan="4">&nbsp;</td>
												</tr>
												<tr>
													<th><label for="wgextra_lightbox_ilightbox_deeplink" title="<?php esc_attr_e( "Enabling the hash linking images.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Deeplinking", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgextra_lightbox_ilightbox_share_buttons" title="<?php esc_attr_e( "Display social buttons?", 'wordpress-gallery-extra' ); ?>"><?php _e( "Share Buttons", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgextra_lightbox_ilightbox_thumbnails" title="<?php esc_attr_e( "Show thumbnails navigation?", 'wordpress-gallery-extra' ); ?>"><?php _e( "Thumbnails", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgextra_lightbox_ilightbox_overlay_opacity" title="<?php esc_attr_e( "Sets the opacity of the dimmed background of the page.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Overlay Opacity", 'wordpress-gallery-extra' ); ?></label></th>
												</tr>
												<tr>
													<td>
														<div class="onoffswitch">
															<input type="checkbox" name="wgextra_lightbox_ilightbox_deeplink" class="onoffswitch-checkbox" id="wgextra_lightbox_ilightbox_deeplink"<?php checked( $options['lightbox_ilightbox']['deeplink'], 'yes' ); ?>>
															<label class="onoffswitch-label" for="wgextra_lightbox_ilightbox_deeplink">
																<div class="onoffswitch-inner">
																	<div class="onoffswitch-active">ON</div>
																	<div class="onoffswitch-inactive">OFF</div>
																</div>
																<div class="onoffswitch-switch"></div>
															</label>
														</div>
													</td>
													<td>
														<div class="onoffswitch">
															<input type="checkbox" name="wgextra_lightbox_ilightbox_share_buttons" class="onoffswitch-checkbox" id="wgextra_lightbox_ilightbox_share_buttons"<?php checked( $options['lightbox_ilightbox']['share_buttons'], 'yes' ); ?>>
															<label class="onoffswitch-label" for="wgextra_lightbox_ilightbox_share_buttons">
																<div class="onoffswitch-inner">
																	<div class="onoffswitch-active">ON</div>
																	<div class="onoffswitch-inactive">OFF</div>
																</div>
																<div class="onoffswitch-switch"></div>
															</label>
														</div>
													</td>
													<td>
														<div class="onoffswitch">
															<input type="checkbox" name="wgextra_lightbox_ilightbox_thumbnails" class="onoffswitch-checkbox" id="wgextra_lightbox_ilightbox_thumbnails"<?php checked( $options['lightbox_ilightbox']['thumbnails'], 'yes' ); ?>>
															<label class="onoffswitch-label" for="wgextra_lightbox_ilightbox_thumbnails">
																<div class="onoffswitch-inner">
																	<div class="onoffswitch-active">ON</div>
																	<div class="onoffswitch-inactive">OFF</div>
																</div>
																<div class="onoffswitch-switch"></div>
															</label>
														</div>
													</td>
													<td>
														<input rel='number' data-options='min:0, max:1, step: 0.01' id='wgextra_lightbox_ilightbox_overlay_opacity' name='wgextra_lightbox_ilightbox_overlay_opacity' value='<?php echo esc_attr( $options['lightbox_ilightbox']['overlay_opacity'] ); ?>' />
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</td>
							</tr>
							<tr class="field" rel="caption_source">
								<th><label><?php _e( 'Caption Source', 'wordpress-gallery-extra' ); ?></label></th>
								<td colspan="3">
									<div class="switch-field">
										<label for="wgextra_caption_source-1"><?php _e( "Title", 'wordpress-gallery-extra' ); ?></label>
										<input type="radio" name="wgextra_caption_source" id="wgextra_caption_source-1" value="title"<?php checked( $options['caption_source'], 'title' ); ?> />
										<label for="wgextra_caption_source-2"><?php _e( "Caption", 'wordpress-gallery-extra' ); ?></label>
										<input type="radio" name="wgextra_caption_source" id="wgextra_caption_source-2" value="caption"<?php checked( $options['caption_source'], 'caption' ); ?> />
										<label for="wgextra_caption_source-3"><?php _e( "Alt Text", 'wordpress-gallery-extra' ); ?></label>
										<input type="radio" name="wgextra_caption_source" id="wgextra_caption_source-3" value="alt"<?php checked( $options['caption_source'], 'alt' ); ?> />
										<label for="wgextra_caption_source-4"><?php _e( "Description", 'wordpress-gallery-extra' ); ?></label>
										<input type="radio" name="wgextra_caption_source" id="wgextra_caption_source-4" value="description"<?php checked( $options['caption_source'], 'description' ); ?> />
									</div>
									<p class="description"><?php _e( "Pull captions from either the attachment Title, Caption, Alt Text or Description.", 'wordpress-gallery-extra' ); ?></p>
								</td>
							</tr>
							<tr>
								<th><label><?php _e( 'Link', 'wordpress-gallery-extra' ); ?></label></th>
								<td colspan="3">
									<div class="inside-table">
										<table class="form-table">
											<tbody>
												<tr>
													<th><label for="wgextra_link_to" title="<?php esc_attr_e( "Controls where the thumbnails must link to.", 'wordpress-gallery-extra' ); ?>"><?php _e( "To", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgextra_link_target" title="<?php esc_attr_e( "Set a custom target for link.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Target", 'wordpress-gallery-extra' ); ?></label></th>
													<th class="wgextra_link_url"><label for="wgextra_link_url" title="<?php esc_attr_e( "Point thumbnails to a custom URL.", 'wordpress-gallery-extra' ); ?>"><?php _e( "URL", 'wordpress-gallery-extra' ); ?></label></th>
												</tr>
												<tr>
													<td>
														<select id="wgextra_link_to" name="wgextra_link_to">
															<option value="none"><?php _e( "None", 'wordpress-gallery-extra' ); ?></option>
															<option value="page"<?php selected( $options['link']['to'], 'page' ); ?>><?php _e( "Page URL", 'wordpress-gallery-extra' ); ?></option>
															<option value="file"<?php selected( $options['link']['to'], 'file' ); ?>><?php _e( "Media File", 'wordpress-gallery-extra' ); ?></option>
															<option value="custom"<?php selected( $options['link']['to'], 'custom' ); ?>><?php _e( "Custom URL", 'wordpress-gallery-extra' ); ?></option>
														</select>
													</td>
													<td>
														<?php
															$this->create_dropdown( array(
																'options' => $target_options,
																'selected' => $options['link']['target'],
																'id' => 'wgextra_link_target',
																'name' => 'wgextra_link_target'
															) );
														?>
													</td>
													<td class="wgextra_link_url">
														<input type="text" id='wgextra_link_url' name='wgextra_link_url' value='<?php echo esc_attr( $options['link']['url'] ); ?>' />
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</td>
							</tr>
							<tr>
								<th><label for="wgextra_custom_class"><?php _e( 'Custom CSS class', 'wordpress-gallery-extra' ); ?></label></th>
								<td colspan="3">
									<input type="text" id="wgextra_custom_class" name="wgextra_custom_class" value="<?php echo esc_attr( $options['custom_class'] ); ?>" placeholder="custom-class  another-custom-class" />
									<p class="description"><?php _e( "CSS classes separated by space. Usefull to add custom css to the grid.", 'wordpress-gallery-extra' ); ?></p>
								</td>
							</tr>
<?php
	$this->do_settings_fields( $page, 'settings', 'default' );
?>
						</table>
<?php
	$this->do_settings_sections( $page, 'settings' );
?>
					</div><!-- end of inside -->

					<div class="inside ui-tabs-panel" id="wgextra-source">
						<p><?php _e( "Personalize your grid source with the following settings.", 'wordpress-gallery-extra' ); ?></p>
						<table class="form-table">
							<tr>
								<th><label for="wgextra_source"><?php _e( "Source", 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<div class="select-field source-types">
<?php
				foreach ( $this->SOURCES_TYPES as $key => $value ) {
?>
										<label for="wgextra_source-<?php echo $key; ?>" class="<?php echo $key; ?>"><i></i><span><?php echo $value['name']; ?></span></label>
										<input type="radio" name="wgextra_source" id="wgextra_source-<?php echo $key; ?>" value="<?php echo $key; ?>"<?php checked( $options['source']['source'], $key ); ?> />
<?php
				}
?>
									</div>
									<p class="description"><?php _e( "Select the type of content to display inside the grid.", 'wordpress-gallery-extra' ); ?></p>
								</td>
							</tr>
							<tr>
								<th><label for="wgextra_item_number"><?php _e( 'Item Number', 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<input rel='number' data-options='min:-1' id='wgextra_item_number' name='wgextra_item_number' value='<?php echo esc_attr( $options['source']['item_number'] ); ?>' />
									<p class="description"><?php _e( "Enter the number of items to load inside the grid.", 'wordpress-gallery-extra' ); ?></p>
									<div class="description">
										<strong><?php _e( "Note:", 'wordpress-gallery-extra' ); ?></strong>
										<ol>
											<li><?php _e( "-1 allows to load all items (only for Post Type)", 'wordpress-gallery-extra' ); ?></li>
											<li><?php printf( __( "0 corresponds to the default number of <a target='_blank' href='%s'>post per page</a>.", 'wordpress-gallery-extra' ), admin_url( 'options-reading.php' ) ); ?></li>
										</ol>
									</div>
								</td>
							</tr>
<?php
	$this->do_settings_fields( $page, 'source', 'default' );
?>
						</table>

						<div class="field" rel="filter">
							<hr>
							<h3 class="margin-bottom-none"><?php _e( "Filter Items", 'wordpress-gallery-extra' ); ?></h3>
							<p><?php _e( "Filter your grid source with the following options.", 'wordpress-gallery-extra' ); ?></p>

							<table class="form-table">
								<tr class="field" rel="post_types">
									<th scope="row"><label for="wgextra_post_types"><?php _e( "Post Type(s)", 'wordpress-gallery-extra' ); ?></label></th>
									<td>
										<?php
											$this->create_dropdown( array(
												'options' => self::get_public_post_types(),
												'selected' => $options['source']['post_types'],
												'id' => 'wgextra_post_types',
												'name' => 'wgextra_post_types',
												'multiple' => true
											) );
										?>
										<p class="description"><?php _e( "Select one or several post type to display inside the current grid.", 'wordpress-gallery-extra' ); ?></p>
									</td>
								</tr>
								<tr class="field" rel="post_status">
									<th scope="row"><label for="wgextra_post_status"><?php _e( "Post Status", 'wordpress-gallery-extra' ); ?></label></th>
									<td>
										<?php
											$this->create_dropdown( array(
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
												'id' => 'wgextra_post_status',
												'name' => 'wgextra_post_status',
												'multiple' => true
											) );
										?>
										<p class="description"><?php _e( "Show posts associated with certain status.", 'wordpress-gallery-extra' ); ?></p>
									</td>
								</tr>
								<tr class="field" rel="taxonomies">
									<th scope="row"><label for="wgextra_taxonomies"><?php _e( "Categories/Taxonomies", 'wordpress-gallery-extra' ); ?></label></th>
									<td>
										<div class="inside-table">
											<table class="form-table">
												<tbody>
													<tr>
														<th><label for="wgextra_taxonomies" title="<?php esc_attr_e( "Select taxonomy term(s) from the current post type(s).", 'wordpress-gallery-extra' ); ?>"><?php _e( "Terms", 'wordpress-gallery-extra' ); ?></label></th>
														<th><label for="wgextra_taxonomies_relation" title="<?php esc_attr_e( "The logical relationship between each taxonomy term when there is more than one.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Relation", 'wordpress-gallery-extra' ); ?></label></th>
													</tr>
													<tr>
														<td>
															<?php
																$this->create_dropdown( array(
																	'options' => array(),
																	'selected' => $options['source']['taxonomies'],
																	'id' => 'wgextra_taxonomies',
																	'name' => 'wgextra_taxonomies',
																	'multiple' => true
																) );
															?>
														</td>
														<td>
															<select name="wgextra_taxonomies_relation" id="wgextra_taxonomies_relation">
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
								<tr class="field" rel="authors">
									<th scope="row"><label for="wgextra_authors"><?php _e( "Author(s)", 'wordpress-gallery-extra' ); ?></label></th>
									<td>
										<div class="inside-table">
											<table class="form-table">
												<tbody>
													<tr>
														<th><label for="wgextra_authors"><?php _e( "Terms", 'wordpress-gallery-extra' ); ?></label></th>
														<th><label for="wgextra_authors_relation" title="<?php esc_attr_e( "Include or Exclude Posts Belonging to selected Author(s)", 'wordpress-gallery-extra' ); ?>"><?php _e( "Relation", 'wordpress-gallery-extra' ); ?></label></th>
													</tr>
													<tr>
														<td>
															<?php
																$this->create_dropdown( array(
																	'options' => self::get_all_authors(),
																	'selected' => $options['source']['authors'],
																	'id' => 'wgextra_authors',
																	'name' => 'wgextra_authors',
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
															<select name="wgextra_authors_relation" id="wgextra_authors_relation">
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
								<tr class="field" rel="exclude_posts">
									<th scope="row"><label for="wgextra_exclude_posts"><?php _e( "Exclude Post(s)", 'wordpress-gallery-extra' ); ?></label></th>
									<td>
										<input type="text" id="wgextra_exclude_posts" name="wgextra_exclude_posts" value="<?php echo esc_attr( $options['source']['exclude_posts'] ); ?>" /><br>
										<p class="description"><?php _e( "Enter post ID(s) to exclude from the current source. Add post IDs separated by a comma (e.g: 43, 7, 99, 23, 76, 2).", 'wordpress-gallery-extra' ); ?></p>
									</td>
								</tr>
								<tr class="field" rel="include_posts">
									<th scope="row"><label for="wgextra_include_posts"><?php _e( "Include Post(s)", 'wordpress-gallery-extra' ); ?></label></th>
									<td>
										<input type="text" id="wgextra_include_posts" name="wgextra_include_posts" value="<?php echo esc_attr( $options['source']['include_posts'] ); ?>" /><br>
										<p class="description"><?php _e( "Display only the specific post(s). Add post IDs separated by a comma (e.g: 43, 7, 99, 23, 76, 2).", 'wordpress-gallery-extra' ); ?></p>
									</td>
								</tr>
								<tr class="field" rel="ordering">
									<th scope="row"><label><?php _e( "Ordering", 'wordpress-gallery-extra' ); ?></label></th>
									<td>
										<div class="inside-table">
											<table class="form-table">
												<tbody>
													<tr>
														<th><label for="wgextra_ordering_order" title="<?php esc_attr_e( "Designates the ascending or descending order of the retrieved posts sort", 'wordpress-gallery-extra' ); ?>"><?php _e( "Order", 'wordpress-gallery-extra' ); ?></label></th>
														<th><label for="wgextra_ordering_order_by" title="<?php esc_attr_e( "Sort retrieved posts.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Order By", 'wordpress-gallery-extra' ); ?></label></th>
														<th><label for="wgextra_ordering_order_by_fallback" title="<?php esc_attr_e( "Fallback to sort retrieved posts.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Order By Fallback", 'wordpress-gallery-extra' ); ?></label></th>
														<th class="wgextra_ordering_meta_key hidden"><label for="wgextra_ordering_meta_key" title="<?php esc_attr_e( "Enter a meta key name to order by a meta key value", 'wordpress-gallery-extra' ); ?>"><?php _e( "Meta Key", 'wordpress-gallery-extra' ); ?></label></th>
													</tr>
													<tr>
														<td>
															<select name="wgextra_ordering_order" id="wgextra_ordering_order">
																<option value="ASC"<?php selected( $options['source']['ordering']['order'], 'ASC' ); ?>><?php _e( "Ascending", 'wordpress-gallery-extra' ); ?></option>
																<option value="DESC"<?php selected( $options['source']['ordering']['order'], 'DESC' ); ?>><?php _e( "Descending", 'wordpress-gallery-extra' ); ?></option>
															</select>
														</td>
														<td>
															<?php
																$this->create_dropdown( array(
																	'options' => $ordering_order_by_options,
																	'selected' => $options['source']['ordering']['order_by'],
																	'id' => 'wgextra_ordering_order_by',
																	'name' => 'wgextra_ordering_order_by'
																) );
															?>
														</td>
														<td>
															<?php
																$this->create_dropdown( array(
																	'options' => $ordering_order_by_options,
																	'selected' => $options['source']['ordering']['order_by_fallback'],
																	'id' => 'wgextra_ordering_order_by_fallback',
																	'name' => 'wgextra_ordering_order_by_fallback'
																) );
															?>
														</td>
														<td class="wgextra_ordering_meta_key hidden">
															<input type="text" id="wgextra_ordering_meta_key" name="wgextra_ordering_meta_key" value="<?php echo esc_attr( $options['source']['ordering']['meta_key'] ); ?>" />
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</td>
								</tr>
<?php
	$this->do_settings_fields( $page, 'source', 'filter' );
?>
							</table>
						</div><!-- .field[rel="filter"] -->
<?php
	$this->do_settings_sections( $page, 'source' );
?>
					</div><!-- end of inside -->

					<div class="inside ui-tabs-panel" id="wgextra-display">
						<div class="field" rel="display-grid">
							<h3 class="margin-bottom-none"><?php _e( "Grid Settings", 'wordpress-gallery-extra' ); ?></h3>
							<p><?php _e( "Configure grid structure and how it will be displayed.", 'wordpress-gallery-extra' ); ?></p>

							<table class="form-table">
								<tr class="field" rel="default_image">
									<th><label for="wgextra_default_image"><?php _e( 'Default Image', 'wordpress-gallery-extra' ); ?></label></th>
									<td>
										<input rel='image' id='wgextra_default_image' name='wgextra_default_image' value='<?php echo esc_attr( $options['default_image'] ); ?>' />
										<p class="description"><?php _e( "Add a default image if image is missing.", 'wordpress-gallery-extra' ); ?></p>
									</td>
								</tr>
								<tr class="field" rel="thumbnail_size">
									<th><label for="wgextra_thumbnail_size"><?php _e( 'Thumbnail Size', 'wordpress-gallery-extra' ); ?></label></th>
									<td>
<?
	$thumbnails_sizes = $this->get_image_sizes();
	$possible_sizes_names = apply_filters( 'image_size_names_choose', array(
		'thumbnail'       => __('Thumbnail'),
		'medium'          => __('Medium'),
		'large'           => __('Large')
	) );
?>
										<select name="wgextra_thumbnail_size" id="wgextra_thumbnail_size">
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
										<div class="switch-field">
											<label for="wgextra_thumbnail_ratio-default"><?php _e( "Default", 'wordpress-gallery-extra' ); ?></label>
											<input type="radio" name="wgextra_thumbnail_ratio" id="wgextra_thumbnail_ratio-default" value="default"<?php checked( $options['thumbnail_ratio']['type'], 'default' ); ?> />
											<label for="wgextra_thumbnail_ratio-manual"><?php _e( "Manual", 'wordpress-gallery-extra' ); ?></label>
											<input type="radio" name="wgextra_thumbnail_ratio" id="wgextra_thumbnail_ratio-manual" value="manual"<?php checked( $options['thumbnail_ratio']['type'], 'manual' ); ?> />
										</div>
										<div class="clear"></div>
										<div class="inside-table">
											<table class="form-table">
												<tbody>
													<tr>
														<th><label for="wgextra_thumbnail_ratio_size" title="<?php esc_attr_e( "Correspond to the ratio between width and height (X:Y) (e.g: 4:3 or 16:9 format)", 'wordpress-gallery-extra' ); ?>"><?php _e( "Ratio", 'wordpress-gallery-extra' ); ?></label></th>
														<th><label for="wgextra_thumbnail_ratio_force" title="<?php esc_attr_e( "This option will override all thumbnail sizes set in each post/item", 'wordpress-gallery-extra' ); ?>"><?php _e( "Force Thumbnail Sizes", 'wordpress-gallery-extra' ); ?></label></th>
													</tr>
													<tr>
														<td>
															<input rel='number' id='wgextra_thumbnail_ratio_size' name='wgextra_thumbnail_ratio_size[]' value='<?php echo esc_attr( $options['thumbnail_ratio']['size'][0] ); ?>' />
															&nbsp;&nbsp;:&nbsp;&nbsp;
															<input rel='number' id='wgextra_thumbnail_ratio_size' name='wgextra_thumbnail_ratio_size[]' value='<?php echo esc_attr( $options['thumbnail_ratio']['size'][1] ); ?>' />
														</td>
														<td>
															<div class="onoffswitch">
																<input type="checkbox" name="wgextra_thumbnail_ratio_force" class="onoffswitch-checkbox" id="wgextra_thumbnail_ratio_force"<?php checked( $options['thumbnail_ratio']['force'], 'yes' ); ?>>
																<label class="onoffswitch-label" for="wgextra_thumbnail_ratio_force">
																	<div class="onoffswitch-inner">
																		<div class="onoffswitch-active">ON</div>
																		<div class="onoffswitch-inactive">OFF</div>
																	</div>
																	<div class="onoffswitch-switch"></div>
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
									<th><label for="wgextra_columns"><?php _e( 'Columns', 'wordpress-gallery-extra' ); ?></label></th>
									<td colspan="3">
										<input rel='range' data-options="max: 9, min: 1, type: 'circle', pips: {}, forceTip: true" id='wgextra_columns' name='wgextra_columns' value='<?php echo esc_attr( $options['columns'] ); ?>' />
										<p class="description"><?php _e( "Set the number of columns you would like to have in your gallery.", 'wordpress-gallery-extra' ); ?></p>
									</td>
								</tr>
								<tr class="field" rel="last_row">
									<th><label><?php _e( 'Last row', 'wordpress-gallery-extra' ); ?></label></th>
									<td colspan="3">
										<div class="switch-field">
											<label for="wgextra_last_row-1"><?php _e( "Align left", 'wordpress-gallery-extra' ); ?></label>
											<input type="radio" name="wgextra_last_row" id="wgextra_last_row-1" value="nojustify"<?php checked( $options['last_row'], 'nojustify' ); ?> />
											<label for="wgextra_last_row-2"><?php _e( "Align center", 'wordpress-gallery-extra' ); ?></label>
											<input type="radio" name="wgextra_last_row" id="wgextra_last_row-2" value="center"<?php checked( $options['last_row'], 'center' ); ?> />
											<label for="wgextra_last_row-3"><?php _e( "Align right", 'wordpress-gallery-extra' ); ?></label>
											<input type="radio" name="wgextra_last_row" id="wgextra_last_row-3" value="right"<?php checked( $options['last_row'], 'right' ); ?> />
											<label for="wgextra_last_row-4"><?php _e( "Justify", 'wordpress-gallery-extra' ); ?></label>
											<input type="radio" name="wgextra_last_row" id="wgextra_last_row-4" value="justify"<?php checked( $options['last_row'], 'justify' ); ?> />
											<label for="wgextra_last_row-5"><?php _e( "Hide", 'wordpress-gallery-extra' ); ?></label>
											<input type="radio" name="wgextra_last_row" id="wgextra_last_row-5" value="hide"<?php checked( $options['last_row'], 'hide' ); ?> />
										</div>
										<p class="description"><?php _e( "Decide how to position the last row of images. Default the last row images are aligned to the left. You can also hide the row if it can't be justified and aligned to the center or to the right.", 'wordpress-gallery-extra' ); ?></p>
									</td>
								</tr>
								<tr class="field" rel="alignment">
									<th><label><?php _e( 'Alignment', 'wordpress-gallery-extra' ); ?></label></th>
									<td colspan="3">
										<div class="switch-field">
											<label for="wgextra_alignment-1"><?php _e( "Left", 'wordpress-gallery-extra' ); ?></label>
											<input type="radio" name="wgextra_alignment" id="wgextra_alignment-1" value="left"<?php checked( $options['alignment'], 'left' ); ?> />
											<label for="wgextra_alignment-2"><?php _e( "Center", 'wordpress-gallery-extra' ); ?></label>
											<input type="radio" name="wgextra_alignment" id="wgextra_alignment-2" value="center"<?php checked( $options['alignment'], 'center' ); ?> />
											<label for="wgextra_alignment-3"><?php _e( "Right", 'wordpress-gallery-extra' ); ?></label>
											<input type="radio" name="wgextra_alignment" id="wgextra_alignment-3" value="right"<?php checked( $options['alignment'], 'right' ); ?> />
										</div>
										<p class="description"><?php _e( "The horizontal alignment of the thumbnails inside the gallery.", 'wordpress-gallery-extra' ); ?></p>
									</td>
								</tr>
								<tr class="field" rel="vertical_alignment">
									<th><label><?php _e( 'Vertical Alignment', 'wordpress-gallery-extra' ); ?></label></th>
									<td colspan="3">
										<div class="switch-field">
											<label for="wgextra_vertical_alignment-top"><?php _e( "Top", 'wordpress-gallery-extra' ); ?></label>
											<input type="radio" name="wgextra_vertical_alignment" id="wgextra_vertical_alignment-top" value="top"<?php checked( $options['vertical_alignment'], 'top' ); ?> />
											<label for="wgextra_vertical_alignment-middle"><?php _e( "Middle", 'wordpress-gallery-extra' ); ?></label>
											<input type="radio" name="wgextra_vertical_alignment" id="wgextra_vertical_alignment-middle" value="middle"<?php checked( $options['vertical_alignment'], 'middle' ); ?> />
											<label for="wgextra_vertical_alignment-bottom"><?php _e( "Bottom", 'wordpress-gallery-extra' ); ?></label>
											<input type="radio" name="wgextra_vertical_alignment" id="wgextra_vertical_alignment-bottom" value="bottom"<?php checked( $options['vertical_alignment'], 'bottom' ); ?> />
										</div>
										<p class="description"><?php _e( "The vertical alignment of the thumbnails inside the gallery.", 'wordpress-gallery-extra' ); ?></p>
									</td>
								</tr>
								<tr class="field" rel="row_height">
									<th><label for="wgextra_row_height"><?php _e( 'Row Height', 'wordpress-gallery-extra' ); ?></label></th>
									<td colspan="3">
										<input rel='number' id='wgextra_row_height' name='wgextra_row_height' value='<?php echo esc_attr( $options['row_height'] ); ?>' />
										<p class="description"><?php _e( "The preferred height of your gallery rows in pixel.", 'wordpress-gallery-extra' ); ?></p>
									</td>
								</tr>
								<tr class="field" rel="max_row_height">
									<th><label for="wgextra_max_row_height"><?php _e( 'Max Row Height', 'wordpress-gallery-extra' ); ?></label></th>
									<td colspan="3">
									<?php
										$max_row_height = $this->split_number( $options['max_row_height'] );
									?>
										<input rel='number' data-options='min:-1' id='wgextra_max_row_height' name='wgextra_max_row_height' value='<?php echo esc_attr( $max_row_height['number'] ); ?>' />
										<select id="wgextra_max_row_height_unit" name="wgextra_max_row_height_unit">
											<option value="">px</option>
											<option value="%"<?php selected( $max_row_height['unit'], '%' ); ?>>%</option>
										</select><br />
										<p class="description"><?php _e( "A number (e.g 200) which specifies the maximum row height in pixels. Use <code>-1px</code> to remove the limit of the maximum row height. Alternatively, use a percentage (e.g. 200% which means that the row height cannot exceed <code>2 * Row Height</code>)", 'wordpress-gallery-extra' ); ?></p>
									</td>
								</tr>
								<tr class="field" rel="mosaic_type">
									<th><label><?php _e( 'Mosaic Type', 'wordpress-gallery-extra' ); ?></label></th>
									<td colspan="3">
										<div class="switch-field">
											<label for="wgextra_mosaic_type-auto"><?php _e( "Auto", 'wordpress-gallery-extra' ); ?></label>
											<input type="radio" name="wgextra_mosaic_type" id="wgextra_mosaic_type-auto" value="auto"<?php checked( $options['mosaic_type'], 'auto' ); ?> />
											<label for="wgextra_mosaic_type-manual"><?php _e( "Manual", 'wordpress-gallery-extra' ); ?></label>
											<input type="radio" name="wgextra_mosaic_type" id="wgextra_mosaic_type-manual" value="manual"<?php checked( $options['mosaic_type'], 'manual' ); ?> />
										</div>
									</td>
								</tr>
<?php
	$this->do_settings_fields( $page, 'display', 'grid' );
?>
							</table>
						</div>
<?php
	$this->do_settings_sections( $page, 'display' );
?>
					</div><!-- end of inside -->

					<div class="inside ui-tabs-panel" id="wgextra-styling">
						<input type="hidden" name="preset_styles" value="<?php echo esc_attr( json_encode( $preset_styles ) ); ?>">
						<p><?php _e( "Style your gallery template with the following options.", 'wordpress-gallery-extra' ); ?></p>
						<table class="form-table">
							<tr class="field" rel="placeholder">
								<th><label for="wgextra_style_use_placeholder"><?php _e( "Use Placeholder", 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<div class="onoffswitch">
										<input type="checkbox" name="wgextra_style_use_placeholder" class="onoffswitch-checkbox" id="wgextra_style_use_placeholder"<?php checked( $options['styles']['use_placeholder'], 'yes' ); ?>>
										<label class="onoffswitch-label" for="wgextra_style_use_placeholder">
											<div class="onoffswitch-inner">
												<div class="onoffswitch-active">ON</div>
												<div class="onoffswitch-inactive">OFF</div>
											</div>
											<div class="onoffswitch-switch"></div>
										</label>
									</div><br />
									<div class="inside-table">
										<table class="form-table">
											<thead>
												<tr>
													<th><label for="wgextra_style_placeholder_overlay"><?php _e( "Overlay", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgextra_style_placeholder_readable_caption"><?php _e( "Readable Caption Text Color", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgextra_style_placeholder_background"><?php _e( "Image Background", 'wordpress-gallery-extra' ); ?></label></th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>
														<div class="onoffswitch">
															<input type="checkbox" name="wgextra_style_placeholder_overlay" class="onoffswitch-checkbox" id="wgextra_style_placeholder_overlay"<?php checked( $options['styles']['placeholder']['overlay'], 'yes' ); ?>>
															<label class="onoffswitch-label" for="wgextra_style_placeholder_overlay">
																<div class="onoffswitch-inner">
																	<div class="onoffswitch-active">ON</div>
																	<div class="onoffswitch-inactive">OFF</div>
																</div>
																<div class="onoffswitch-switch"></div>
															</label>
														</div>
													</td>
													<td>
														<div class="onoffswitch">
															<input type="checkbox" name="wgextra_style_placeholder_readable_caption" class="onoffswitch-checkbox" id="wgextra_style_placeholder_readable_caption"<?php checked( $options['styles']['placeholder']['readable_caption'], 'yes' ); ?>>
															<label class="onoffswitch-label" for="wgextra_style_placeholder_readable_caption">
																<div class="onoffswitch-inner">
																	<div class="onoffswitch-active">ON</div>
																	<div class="onoffswitch-inactive">OFF</div>
																</div>
																<div class="onoffswitch-switch"></div>
															</label>
														</div>
													</td>
													<td>
														<div class="onoffswitch">
															<input type="checkbox" name="wgextra_style_placeholder_background" class="onoffswitch-checkbox" id="wgextra_style_placeholder_background"<?php checked( $options['styles']['placeholder']['background'], 'yes' ); ?>>
															<label class="onoffswitch-label" for="wgextra_style_placeholder_background">
																<div class="onoffswitch-inner">
																	<div class="onoffswitch-active">ON</div>
																	<div class="onoffswitch-inactive">OFF</div>
																</div>
																<div class="onoffswitch-switch"></div>
															</label>
														</div>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</td>
							</tr>
							<tr>
								<th><label for="wgextra_style_embed_google_fonts"><?php _e( "Embed Google Fonts", 'wordpress-gallery-extra' ); ?></label> <sup><?php _e( "Beta", 'wordpress-gallery-extra' ); ?></sup></th>
								<td>
									<input type="text" id="wgextra_style_embed_google_fonts" name="wgextra_style_embed_google_fonts" value="<?php echo esc_attr( $options['styles']['embed_google_fonts'] ); ?>" />
									<a class="ui-button" href="https://fonts.google.com" target="_blank"><?php _e( "Generate Embed Code", 'wordpress-gallery-extra' ); ?></a>
									<br>
									<p class="description"><?php _e( "If you want to use Google fonts in your template you need to insert your customized embed code here.", 'wordpress-gallery-extra' ); ?></p>
								</td>
							</tr>
<?php
	$this->do_settings_fields( $page, 'styling', 'default_top' );
?>
						</table>
						<hr />
						<table class="form-table">
							<tr>
								<th><label for="wgextra_template"><?php _e( "Presets", 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<div class="select-field">
										<label for="wgextra_style-default"><img src="<?php echo plugins_url( 'assets/img/style-icon.jpg', $this->MAIN ); ?>" /></label>
										<input type="radio" name="wgextra_style" id="wgextra_style-default" value="default"<?php checked( $options['styles']['defined'], 'default' ); ?> />
<?php
				foreach ( $preset_styles as $pid => $preset ) {
?>
										<label for="wgextra_style-<?php echo $pid; ?>"><img src="<?php echo plugins_url( 'assets/img/style-icon.jpg', $this->MAIN ); ?>" style="<?php echo esc_attr( $preset['style'] ); ?>" /></label>
										<input type="radio" name="wgextra_style" id="wgextra_style-<?php echo $pid; ?>" value="<?php echo $pid; ?>"<?php checked( $options['styles']['defined'], $pid ); ?> />
<?php
				}
?>
									</div>
									<p class="description"><?php _e( "The style for each thumbnail in the gallery.", 'wordpress-gallery-extra' ); ?></p>
								</td>
							</tr>
							<tr class="field" rel="margin">
								<th><label for="wgextra_style_margin"><?php _e( 'Spacing', 'wordpress-gallery-extra' ); ?></label></th>
								<td colspan="3">
									<input rel='number' id='wgextra_style_margin' name='wgextra_style_margin' value='<?php echo esc_attr( $options['styles']['margin'] ); ?>' /><br />
									<p class="description"><?php _e( "The spacing or gap between thumbnails in the gallery.", 'wordpress-gallery-extra' ); ?></p>
								</td>
							</tr>
							<tr class="field" rel="border">
								<th><label for="wgextra_style_border"><?php _e( "Border", 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<div class="onoffswitch">
										<input type="checkbox" name="wgextra_style_border" class="onoffswitch-checkbox" id="wgextra_style_border"<?php checked( $options['styles']['has_border'], 'yes' ); ?>>
										<label class="onoffswitch-label" for="wgextra_style_border">
											<div class="onoffswitch-inner">
												<div class="onoffswitch-active">ON</div>
												<div class="onoffswitch-inactive">OFF</div>
											</div>
											<div class="onoffswitch-switch"></div>
										</label>
									</div><br />
									<div class="inside-table">
										<table class="form-table">
											<thead>
												<tr>
													<th><label for="wgextra_style_border_radius"><?php _e( "Radius", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgextra_style_border_weight"><?php _e( "Weight", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgextra_style_border_style"><?php _e( "Type", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgextra_style_border_color"><?php _e( "Color", 'wordpress-gallery-extra' ); ?></label></th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>
														<input rel='number' id='wgextra_style_border_radius' name='wgextra_style_border_radius' value='<?php echo esc_attr( $options['styles']['border']['radius'] ); ?>' />
													</td>
													<td>
														<input rel='number' id='wgextra_style_border_weight' name='wgextra_style_border_weight' value='<?php echo esc_attr( $options['styles']['border']['weight'] ); ?>' />
													</td>
													<td>
														<select id="wgextra_style_border_style" name="wgextra_style_border_style">
															<option value="none"<?php selected( $options['styles']['border']['style'], 'none' ); ?>><?php _e( "None", 'wordpress-gallery-extra' ); ?></option>
															<option value="solid"<?php selected( $options['styles']['border']['style'], 'solid' ); ?>><?php _e( "Solid", 'wordpress-gallery-extra' ); ?></option>
															<option value="dotted"<?php selected( $options['styles']['border']['style'], 'dotted' ); ?>><?php _e( "Dotted", 'wordpress-gallery-extra' ); ?></option>
															<option value="dashed"<?php selected( $options['styles']['border']['style'], 'dashed' ); ?>><?php _e( "Dashed", 'wordpress-gallery-extra' ); ?></option>
															<option value="double"<?php selected( $options['styles']['border']['style'], 'double' ); ?>><?php _e( "Double", 'wordpress-gallery-extra' ); ?></option>
														</select>
													</td>
													<td>
														<input rel="colorpicker" id="wgextra_style_border_color" name="wgextra_style_border_color" value="<?php echo esc_attr( $options['styles']['border']['color'] ); ?>" />
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</td>
							</tr>
							<tr class="field" rel="shadow">
								<th><label for="wgextra_style_shadow"><?php _e( "Shadow", 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<div class="onoffswitch">
										<input type="checkbox" name="wgextra_style_shadow" class="onoffswitch-checkbox" id="wgextra_style_shadow"<?php checked( $options['styles']['has_shadow'], 'yes' ); ?>>
										<label class="onoffswitch-label" for="wgextra_style_shadow">
											<div class="onoffswitch-inner">
												<div class="onoffswitch-active">ON</div>
												<div class="onoffswitch-inactive">OFF</div>
											</div>
											<div class="onoffswitch-switch"></div>
										</label>
									</div><br />
									<div class="inside-table">
										<table class="form-table">
											<thead>
												<tr>
													<th><label for="wgextra_style_shadow_x"><?php _e( "X-offset", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgextra_style_shadow_y"><?php _e( "Y-offset", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgextra_style_shadow_blur"><?php _e( "Blur", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgextra_style_shadow_spread"><?php _e( "Spread", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgextra_style_shadow_color"><?php _e( "Color", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgextra_style_shadow_inset"><?php _e( "Inset", 'wordpress-gallery-extra' ); ?></label></th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>
														<input rel='number' id='wgextra_style_shadow_x' name='wgextra_style_shadow_x' value='<?php echo esc_attr( $options['styles']['shadow']['x'] ); ?>' />
													</td>
													<td>
														<input rel='number' id='wgextra_style_shadow_y' name='wgextra_style_shadow_y' value='<?php echo esc_attr( $options['styles']['shadow']['y'] ); ?>' />
													</td>
													<td>
														<input rel='number' id='wgextra_style_shadow_blur' name='wgextra_style_shadow_blur' value='<?php echo esc_attr( $options['styles']['shadow']['blur'] ); ?>' />
													</td>
													<td>
														<input rel='number' id='wgextra_style_shadow_spread' name='wgextra_style_shadow_spread' value='<?php echo esc_attr( $options['styles']['shadow']['spread'] ); ?>' />
													</td>
													<td>
														<input rel="colorpicker" id="wgextra_style_shadow_color" name="wgextra_style_shadow_color" value="<?php echo esc_attr( $options['styles']['shadow']['color'] ); ?>" />
													</td>
													<td>
														<div class="onoffswitch">
															<input type="checkbox" name="wgextra_style_shadow_inset" class="onoffswitch-checkbox" id="wgextra_style_shadow_inset"<?php checked( $options['styles']['shadow']['inset'], 'yes' ); ?>>
															<label class="onoffswitch-label" for="wgextra_style_shadow_inset">
																<div class="onoffswitch-inner">
																	<div class="onoffswitch-active">ON</div>
																	<div class="onoffswitch-inactive">OFF</div>
																</div>
																<div class="onoffswitch-switch"></div>
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
								<th><label for="wgextra_style_icon"><?php _e( "Icon", 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<div class="onoffswitch">
										<input type="checkbox" name="wgextra_style_icon" class="onoffswitch-checkbox" id="wgextra_style_icon"<?php checked( $options['styles']['has_icon'], 'yes' ); ?>>
										<label class="onoffswitch-label" for="wgextra_style_icon">
											<div class="onoffswitch-inner">
												<div class="onoffswitch-active">ON</div>
												<div class="onoffswitch-inactive">OFF</div>
											</div>
											<div class="onoffswitch-switch"></div>
										</label>
									</div><br />
									<div class="inside-table">
										<table class="form-table">
											<tbody>
												<tr>
													<th><label title="<?php esc_attr_e( "Choose which icon is shown for your thumbnails.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Icon", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgextra_style_icon_size"><?php _e( "Size", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgextra_style_icon_color"><?php _e( "Color", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgextra_style_icon_visibility"><?php _e( "Visiblity", 'wordpress-gallery-extra' ); ?></label></th>
												</tr>
												<tr>
													<td rowspan="4">
														<div class="select-field icons">
															<label for="wgextra_style_icon_icon-none"><i>&nbsp;&nbsp;&nbsp;&nbsp;</i></label>
															<input type="radio" name="wgextra_style_icon_icon" id="wgextra_style_icon_icon-none" value=""<?php checked( $options['styles']['icon']['icon'], '' ); ?> />
<?php
				$icons = isset( $icons['icons'] ) ? $icons['icons'] : array();
				$i = 0;
				foreach ( $icons as $icon ) {
					$id = $icon['properties']['name'];
?>
															<label for="wgextra_style_icon_icon-<?php echo $i; ?>"><i class="wgextra-icon wgextra-icon-<?php echo $id; ?>"></i></label>
										<input type="radio" name="wgextra_style_icon_icon" id="wgextra_style_icon_icon-<?php echo $i; ?>" value="<?php echo $id; ?>"<?php checked( $options['styles']['icon']['icon'], $id ); ?> />
<?php
					$i++;
				}
?>
														</div>
													</td>
													<td height="40">
														<input rel='number' id='wgextra_style_icon_size' name='wgextra_style_icon_size' value="<?php echo esc_attr( $options['styles']['icon']['size'] ); ?>" />
													</td>
													<td height="40">
														<input rel="colorpicker" id="wgextra_style_icon_color" name="wgextra_style_icon_color" value="<?php echo esc_attr( $options['styles']['icon']['color'] ); ?>" />
													</td>
													<td height="40">
														<?php
															$this->create_dropdown( array(
																'options' => $icon_visibilities,
																'selected' => $options['styles']['icon']['visibility'],
																'id' => 'wgextra_style_icon_visibility',
																'name' => 'wgextra_style_icon_visibility'
															) );
														?>
													</td>
												</tr>
												<tr>
													<td colspan="3"></td>
												</tr>
												<tr>
													<th class="align-bottom" height="21"><label for="wgextra_style_icon_transition_speed" title="<?php esc_attr_e( "Speed of the enter/exit transition in milliseconds.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Speed", 'wordpress-gallery-extra' ); ?></label></th>
													<th class="align-bottom" height="21"><label for="wgextra_style_icon_transition_delay" title="<?php esc_attr_e( "Defines how long to wait and the transition actually begins.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Delay", 'wordpress-gallery-extra' ); ?></label></th>
													<th class="align-bottom" height="21"><label for="wgextra_style_icon_transition_easing" title="<?php esc_attr_e( "Specify the rate of transition over time.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Easing", 'wordpress-gallery-extra' ); ?></label></th>
												</tr>
												<tr>
													<td class="align-bottom" height="40">
														<input rel='number' data-options='min:0, step:50' id='wgextra_style_icon_transition_speed' name='wgextra_style_icon_transition_speed' value="<?php echo esc_attr( $options['styles']['icon']['transition']['speed'] ); ?>" />
													</td>
													<td class="align-bottom" height="40">
														<input rel='number' data-options='min:0, step:50' id='wgextra_style_icon_transition_delay' name='wgextra_style_icon_transition_delay' value="<?php echo esc_attr( $options['styles']['icon']['transition']['delay'] ); ?>" />
													</td>
													<td class="align-bottom" height="40">
														<?php
															$this->create_dropdown( array(
																'options' => $css3_easings,
																'selected' => $options['styles']['icon']['transition']['easing'],
																'id' => 'wgextra_style_icon_transition_easing',
																'name' => 'wgextra_style_icon_transition_easing'
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
								<th><label for="wgextra_style_caption"><?php _e( "Caption", 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<div class="onoffswitch">
										<input type="checkbox" name="wgextra_style_caption" class="onoffswitch-checkbox" id="wgextra_style_caption"<?php checked( $options['styles']['has_caption'], 'yes' ); ?>>
										<label class="onoffswitch-label" for="wgextra_style_caption">
											<div class="onoffswitch-inner">
												<div class="onoffswitch-active">ON</div>
												<div class="onoffswitch-inactive">OFF</div>
											</div>
											<div class="onoffswitch-switch"></div>
										</label>
									</div><br />
									<div class="inside-table">
										<table class="form-table">
											<tbody>
												<tr>
													<th><label for="wgextra_style_caption_color"><?php _e( "Color", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgextra_style_caption_position" title="<?php esc_attr_e( "Where the captions are displayed in relation to the thumbnail.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Position", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label><?php _e( "Background", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgextra_style_caption_inset" title="<?php esc_attr_e( "Insert the caption into thumbnail holder?", 'wordpress-gallery-extra' ); ?>"><?php _e( "Inset", 'wordpress-gallery-extra' ); ?></label></th>
												</tr>
												<tr>
													<td>
														<input rel="colorpicker" id="wgextra_style_caption_color" name="wgextra_style_caption_color" value="<?php echo esc_attr( $options['styles']['caption']['color'] ); ?>" />
													</td>
													<td>
														<select id="wgextra_style_caption_position" name="wgextra_style_caption_position">
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
													<td id="wgextra_style_caption_background">
														<div class="switch-field">
															<label for="wgextra_style_caption_background-solid"><?php _e( "Solid", 'wordpress-gallery-extra' ); ?></label>
															<input type="radio" name="wgextra_style_caption_background" id="wgextra_style_caption_background-solid" value="solid"<?php checked( $options['styles']['caption']['background']['type'], 'solid' ); ?> />
															<label for="wgextra_style_caption_background-gradient"><?php _e( "Gradient", 'wordpress-gallery-extra' ); ?></label>
															<input type="radio" name="wgextra_style_caption_background" id="wgextra_style_caption_background-gradient" value="gradient"<?php checked( $options['styles']['caption']['background']['type'], 'gradient' ); ?> />
															<label for="wgextra_style_caption_background-none"><?php _e( "None", 'wordpress-gallery-extra' ); ?></label>
															<input type="radio" name="wgextra_style_caption_background" id="wgextra_style_caption_background-none" value="none"<?php checked( $options['styles']['caption']['background']['type'], 'none' ); ?> />
														</div>
														<div class="clear"></div>
														<div class="inside-table" id="wgextra_style_caption_background_solid">
															<table class="form-table">
																<thead>
																	<tr>
																		<th><label for="wgextra_style_caption_background_solid_color"><?php _e( "Color", 'wordpress-gallery-extra' ); ?></label></th>
																	</tr>
																</thead>
																<tbody>
																	<tr>
																		<td>
																			<input rel="colorpicker" id="wgextra_style_caption_background_solid_color" name="wgextra_style_caption_background_solid_color" value="<?php echo esc_attr( $options['styles']['caption']['background']['solid']['color'] ); ?>" />
																		</td>
																	</tr>
																</tbody>
															</table>
														</div>
														<div class="inside-table" id="wgextra_style_caption_background_gradient">
															<table class="form-table">
																<thead>
																	<tr>
																		<th><label for="wgextra_style_caption_background_gradient_start_color"><?php _e( "Start Color", 'wordpress-gallery-extra' ); ?></label></th>
																		<th><label for="wgextra_style_caption_background_gradient_stop_color"><?php _e( "Stop Color", 'wordpress-gallery-extra' ); ?></label></th>
																		<th><label for="wgextra_style_caption_background_gradient_orientation"><?php _e( "Orientation", 'wordpress-gallery-extra' ); ?></label></th>
																	</tr>
																</thead>
																<tbody>
																	<tr>
																		<td>
																			<input rel="colorpicker" id="wgextra_style_caption_background_gradient_start_color" name="wgextra_style_caption_background_gradient_start_color" value="<?php echo esc_attr( $options['styles']['caption']['background']['gradient']['start_color'] ); ?>" />
																		</td>
																		<td>
																			<input rel="colorpicker" id="wgextra_style_caption_background_gradient_stop_color" name="wgextra_style_caption_background_gradient_stop_color" value="<?php echo esc_attr( $options['styles']['caption']['background']['gradient']['stop_color'] ); ?>" />
																		</td>
																		<td>
																			<select id="wgextra_style_caption_background_gradient_orientation" name="wgextra_style_caption_background_gradient_orientation">
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
													<td>
														<div class="onoffswitch">
															<input type="checkbox" name="wgextra_style_caption_inset" class="onoffswitch-checkbox" id="wgextra_style_caption_inset"<?php checked( $options['styles']['caption']['inset'], 'yes' ); ?>>
															<label class="onoffswitch-label" for="wgextra_style_caption_inset">
																<div class="onoffswitch-inner">
																	<div class="onoffswitch-active">ON</div>
																	<div class="onoffswitch-inactive">OFF</div>
																</div>
																<div class="onoffswitch-switch"></div>
															</label>
														</div>
													</td>
												</tr>
												<tr>
													<td colspan="4">&nbsp;</td>
												</tr>
												<tr>
													<th><label for="wgextra_style_caption_visibility"><?php _e( "Visiblity", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgextra_style_caption_transition_speed" title="<?php esc_attr_e( "Speed of the enter/exit transition in milliseconds.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Speed", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgextra_style_caption_transition_delay" title="<?php esc_attr_e( "Defines how long to wait and the transition actually begins.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Delay", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgextra_style_caption_transition_easing" title="<?php esc_attr_e( "Specify the rate of transition over time.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Easing", 'wordpress-gallery-extra' ); ?></label></th>
												</tr>
												<tr>
													<td>
														<?php
															$this->create_dropdown( array(
																'options' => $caption_visibilities,
																'selected' => $options['styles']['caption']['visibility'],
																'id' => 'wgextra_style_caption_visibility',
																'name' => 'wgextra_style_caption_visibility'
															) );
														?>
													</td>
													<td>
														<input rel='number' data-options='min:0, step:50' id='wgextra_style_caption_transition_speed' name='wgextra_style_caption_transition_speed' value="<?php echo esc_attr( $options['styles']['caption']['transition']['speed'] ); ?>" />
													</td>
													<td>
														<input rel='number' data-options='min:0, step:50' id='wgextra_style_caption_transition_delay' name='wgextra_style_caption_transition_delay' value="<?php echo esc_attr( $options['styles']['caption']['transition']['delay'] ); ?>" />
													</td>
													<td>
														<?php
															$this->create_dropdown( array(
																'options' => $css3_easings,
																'selected' => $options['styles']['caption']['transition']['easing'],
																'id' => 'wgextra_style_caption_transition_easing',
																'name' => 'wgextra_style_caption_transition_easing'
															) );
														?>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</td>
							</tr>
							<tr class="field" rel="overlay">
								<th><label for="wgextra_style_overlay"><?php _e( "Overlay", 'wordpress-gallery-extra' ); ?></label></th>
								<td>
									<div class="onoffswitch">
										<input type="checkbox" name="wgextra_style_overlay" class="onoffswitch-checkbox" id="wgextra_style_overlay"<?php checked( $options['styles']['has_overlay'], 'yes' ); ?>>
										<label class="onoffswitch-label" for="wgextra_style_overlay">
											<div class="onoffswitch-inner">
												<div class="onoffswitch-active">ON</div>
												<div class="onoffswitch-inactive">OFF</div>
											</div>
											<div class="onoffswitch-switch"></div>
										</label>
									</div><br />
									<div class="inside-table">
										<table class="form-table">
											<thead>
												<tr>
													<th><label><?php _e( "Background", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgextra_style_overlay_visibility" title="<?php esc_attr_e( "Hover visibility type for overlays.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Visiblity", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgextra_style_overlay_transition_speed" title="<?php esc_attr_e( "Speed of the enter/exit transition in milliseconds.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Speed", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgextra_style_overlay_transition_delay" title="<?php esc_attr_e( "Defines how long to wait and the transition actually begins.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Delay", 'wordpress-gallery-extra' ); ?></label></th>
													<th><label for="wgextra_style_overlay_transition_easing" title="<?php esc_attr_e( "Specify the rate of transition over time.", 'wordpress-gallery-extra' ); ?>"><?php _e( "Easing", 'wordpress-gallery-extra' ); ?></label></th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td id="wgextra_style_overlay_background">
														<div class="switch-field">
															<label for="wgextra_style_overlay_background-solid"><?php _e( "Solid", 'wordpress-gallery-extra' ); ?></label>
															<input type="radio" name="wgextra_style_overlay_background" id="wgextra_style_overlay_background-solid" value="solid"<?php checked( $options['styles']['overlay']['background']['type'], 'solid' ); ?> />
															<label for="wgextra_style_overlay_background-gradient"><?php _e( "Gradient", 'wordpress-gallery-extra' ); ?></label>
															<input type="radio" name="wgextra_style_overlay_background" id="wgextra_style_overlay_background-gradient" value="gradient"<?php checked( $options['styles']['overlay']['background']['type'], 'gradient' ); ?> />
														</div>
														<div class="clear"></div>
														<div class="inside-table" id="wgextra_style_overlay_background_solid">
															<table class="form-table">
																<thead>
																	<tr>
																		<th><label for="wgextra_style_overlay_background_solid_color"><?php _e( "Color", 'wordpress-gallery-extra' ); ?></label></th>
																	</tr>
																</thead>
																<tbody>
																	<tr>
																		<td>
																			<input rel="colorpicker" id="wgextra_style_overlay_background_solid_color" name="wgextra_style_overlay_background_solid_color" value="<?php echo esc_attr( $options['styles']['overlay']['background']['solid']['color'] ); ?>" />
																		</td>
																	</tr>
																</tbody>
															</table>
														</div>
														<div class="inside-table" id="wgextra_style_overlay_background_gradient">
															<table class="form-table">
																<thead>
																	<tr>
																		<th><label for="wgextra_style_overlay_background_gradient_start_color"><?php _e( "Start Color", 'wordpress-gallery-extra' ); ?></label></th>
																		<th><label for="wgextra_style_overlay_background_gradient_stop_color"><?php _e( "Stop Color", 'wordpress-gallery-extra' ); ?></label></th>
																		<th><label for="wgextra_style_overlay_background_gradient_orientation"><?php _e( "Orientation", 'wordpress-gallery-extra' ); ?></label></th>
																	</tr>
																</thead>
																<tbody>
																	<tr>
																		<td>
																			<input rel="colorpicker" id="wgextra_style_overlay_background_gradient_start_color" name="wgextra_style_overlay_background_gradient_start_color" name="wgextra_style_overlay_background_gradient_start_color" value="<?php echo esc_attr( $options['styles']['overlay']['background']['gradient']['start_color'] ); ?>" />
																		</td>
																		<td>
																			<input rel="colorpicker" id="wgextra_style_overlay_background_gradient_stop_color" name="wgextra_style_overlay_background_gradient_stop_color" value="<?php echo esc_attr( $options['styles']['overlay']['background']['gradient']['stop_color'] ); ?>" />
																		</td>
																		<td>
																			<select id="wgextra_style_overlay_background_gradient_orientation" name="wgextra_style_overlay_background_gradient_orientation">
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
													<td>
														<?php
															$this->create_dropdown( array(
																'options' => $overlay_visibilities,
																'selected' => $options['styles']['overlay']['visibility'],
																'id' => 'wgextra_style_overlay_visibility',
																'name' => 'wgextra_style_overlay_visibility'
															) );
														?>
													</td>
													<td>
														<input rel='number' data-options='min:0, step:50' id='wgextra_style_overlay_transition_speed' name='wgextra_style_overlay_transition_speed' value="<?php echo esc_attr( $options['styles']['overlay']['transition']['speed'] ); ?>" />
													</td>
													<td>
														<input rel='number' data-options='min:0, step:50' id='wgextra_style_overlay_transition_delay' name='wgextra_style_overlay_transition_delay' value="<?php echo esc_attr( $options['styles']['overlay']['transition']['delay'] ); ?>" />
													</td>
													<td>
														<?php
															$this->create_dropdown( array(
																'options' => $css3_easings,
																'selected' => $options['styles']['overlay']['transition']['easing'],
																'id' => 'wgextra_style_overlay_transition_easing',
																'name' => 'wgextra_style_overlay_transition_easing'
															) );
														?>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</td>
							</tr>
<?php
	$this->do_settings_fields( $page, 'styling', 'default' );
?>
						</table>
<?php
	$this->do_settings_sections( $page, 'styling' );
?>
					</div><!-- end of inside -->
				</form>
<?php
			}

			include_once "footer.php";
?>
