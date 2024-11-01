<div class="wrap <?php echo $page; ?>" id="wgextra">
	<a href="https://www.iprodev.com/go/wgextra/" target="_blank" class="ui-button green" style="float: right"><span class="dashicons dashicons-performance"></span><?php _e( "Upgrade to PRO", 'wordpress-gallery-extra' ) ?></a>
	<h2 id="wgextra-logo"><?php _e( "WP Gallery Extra", 'wordpress-gallery-extra' ) ?></h2>
	<div class="ui-loader"><?php _e( "Please wait...", 'wordpress-gallery-extra' ); ?></div>
	<div id="post-body">

		<div id="wgextra-settings-notice" class="wgextra-yellow-box" style="display:none">
			<strong><?php _e( "Notice:", 'wordpress-gallery-extra' ); ?></strong> <?php _e( "The plugin's settings have been changed. In order to save them please don't forget to click the 'Save Changes' button.", 'wordpress-gallery-extra' ); ?>
		</div>

		<div class="wgextra-box"<?php if ( isset( $tabs ) && !empty( $tabs ) ) { ?> rel="tabs"<?php } ?>>
			<div class="box-title clearfix">
<?php
	if ( isset( $box_title_elements ) && !empty( $box_title_elements ) ) {
?>
				<div class="box_title_elements">
<?php
		$box_title_elements = apply_filters( 'wgextra_box_title_elements', $box_title_elements );

		echo $this->create_elements( $box_title_elements );
?>
				</div>
<?php
	}

	if ( isset( $tabs ) && !empty( $tabs ) ) {
?>
					<ul>
<?php
		if ( isset( $this->SETTINGS_TABS[$page] ) )
			foreach ( $this->SETTINGS_TABS[$page] as $tab ) {
				$tabs["wgextra-{$tab['id']}"] = $tab['title'];
			}

		foreach ( $tabs as $key => $value ) {
?>
						<li><a href="#<?php echo $key; ?>"><?php echo $value; ?></a></li>
<?php
		}
?>
					</ul>
<?php
	} else {
?>
				<h3><?php echo $pagename; ?></h3>
<?php
	}
?>
				<div class="bottom-gradient"></div>
			</div>