
		</div><!-- end of .wgextra-box -->
		<a href="https://iprodev.com" target="_blank" rel="noreferrer noopener" class="socials-icons" title="<?php esc_attr_e( 'Visit author website', 'wordpress-gallery-extra' ); ?>"><span class="dashicons dashicons-admin-site"></span></a>
		<a href="https://facebook.com/iprofessionaldev" target="_blank" rel="noreferrer noopener" class="socials-icons" title="<?php esc_attr_e( 'Like author Facebook page', 'wordpress-gallery-extra' ); ?>"><span class="dashicons dashicons-facebook"></span></a>
		<a href="https://twitter.com/chawroka" target="_blank" rel="noreferrer noopener" class="socials-icons" title="<?php esc_attr_e( 'Follow author on Twitter', 'wordpress-gallery-extra' ); ?>"><span class="dashicons dashicons-twitter"></span></a>
		<div class="report-bug"><span class="dashicons dashicons-warning"></span><?php printf( __( 'Found an error? Help making <strong>WordPress Gallery Extra</strong> better by <a target="_blank" rel="noreferrer noopener" href="%s" title="%s">quickly reporting the bug</a>.', 'wordpress-gallery-extra' ), esc_url( "https://support.iprodev.com/forum/report-bugs/wordpress-gallery-extra/" ), esc_attr__( 'Click here to report a bug', 'wordpress-gallery-extra' ) ); ?></div>
		<script id="regenator_dialog" type="text/template">
			<div id="wgextra_regenator_dialog" title="<?php esc_attr_e( 'Thumbnails Regenerator', 'wordpress-gallery-extra' ); ?>">
				<div class="dialog-top">
					<progress value="0" max="100"></progress>
					<div class="stats">
						<span id="total-images"><?php _e( 'Total Images:', 'wordpress-gallery-extra' ); ?> <em><%= totalItems %></em></span>
						<span id="total-successes"><?php _e( 'Successes:', 'wordpress-gallery-extra' ); ?> <em>0</em></span>
						<span id="total-failures"><?php _e( 'Failures:', 'wordpress-gallery-extra' ); ?> <em>0</em></span>
					</div>
				</div>
				<div class="dialog-bottom">
					<ol></ol>
				</div>
			</div>
		</script>
		<script id="prompt_dialog" type="text/template">
			<div id="wgextra_prompt_dialog" title="<%= title %>">
				<p><%= message %></p>
				<input type="text" name="prompt" value="<%= defaultValue %>" />
			</div>
		</script>
		<script id="confirm_dialog" type="text/template">
			<div id="wgextra_prompt_dialog" title="<%= title %>">
				<p><%= message %></p>
			</div>
		</script>
		<script id="alert_dialog" type="text/template">
			<div id="wgextra_prompt_dialog" title="<?php esc_attr_e( 'Alert!', 'wordpress-gallery-extra' ); ?>">
				<p><%= message %></p>
			</div>
		</script>
	</div><!-- end of #poststuff and #post-body -->
</div><!--  end of #wgextra -->