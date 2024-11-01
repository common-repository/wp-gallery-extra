<?php
class WGExtra_Posts_Metabox {

	public function __construct() {

		if ( is_admin() ) {
			add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
		}

	}

	public function init_metabox() {

		add_action( 'add_meta_boxes', array( $this, 'add_metabox'  )        );
		add_action( 'save_post',      array( $this, 'save_metabox' ), 10, 2 );

	}

	public function add_metabox() {

		$post_types = array();

		foreach ( get_post_types( array( 'public' => true ) ) as $key => $post_type ) {
			if ( in_array( $post_type, array( "attachment" ) ) )
				continue;

			$post_types[] = $post_type;
		}

		add_meta_box(
			'car_info',
			esc_html__( 'Car Info', 'text_domain' ),
			array( $this, 'render_metabox' ),
			'post',
			'advanced',
			'default'
		);

	}

	public function render_metabox( $post ) {

		// Add nonce for security and authentication.
		wp_nonce_field( 'car_nonce_action', 'car_nonce' );

		// Retrieve an existing value from the database.
		$car_year = get_post_meta( $post->ID, 'car_year', true );
		$car_mileage = get_post_meta( $post->ID, 'car_mileage', true );
		$car_cruise_control = get_post_meta( $post->ID, 'car_cruise_control', true );
		$car_power_windows = get_post_meta( $post->ID, 'car_power_windows', true );
		$car_sunroof = get_post_meta( $post->ID, 'car_sunroof', true );

		// Set default values.
		if( empty( $car_year ) ) $car_year = '';
		if( empty( $car_mileage ) ) $car_mileage = '';
		if( empty( $car_cruise_control ) ) $car_cruise_control = '';
		if( empty( $car_power_windows ) ) $car_power_windows = '';
		if( empty( $car_sunroof ) ) $car_sunroof = '';

		// Form fields.
		echo '<table class="form-table">';

		echo '	<tr>';
		echo '		<th><label for="car_year" class="car_year_label">' . __( 'Year', 'text_domain' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="text" id="car_year" name="car_year" class="car_year_field" placeholder="' . esc_attr__( '', 'text_domain' ) . '" value="' . esc_attr__( $car_year ) . '">';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="car_mileage" class="car_mileage_label">' . __( 'Mileage', 'text_domain' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="number" id="car_mileage" name="car_mileage" class="car_mileage_field" placeholder="' . esc_attr__( '', 'text_domain' ) . '" value="' . esc_attr__( $car_mileage ) . '">';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="car_cruise_control" class="car_cruise_control_label">' . __( 'Cruise Control', 'text_domain' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="checkbox" id="car_cruise_control" name="car_cruise_control" class="car_cruise_control_field" value="' . $car_cruise_control . '" ' . checked( $car_cruise_control, 'checked', false ) . '> ' . __( '', 'text_domain' );
		echo '			<span class="description">' . __( 'Car has cruise control.', 'text_domain' ) . '</span>';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="car_power_windows" class="car_power_windows_label">' . __( 'Power Windows', 'text_domain' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="checkbox" id="car_power_windows" name="car_power_windows" class="car_power_windows_field" value="' . $car_power_windows . '" ' . checked( $car_power_windows, 'checked', false ) . '> ' . __( '', 'text_domain' );
		echo '			<span class="description">' . __( 'Car has power windows.', 'text_domain' ) . '</span>';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="car_sunroof" class="car_sunroof_label">' . __( 'Sunroof', 'text_domain' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="checkbox" id="car_sunroof" name="car_sunroof" class="car_sunroof_field" value="' . $car_sunroof . '" ' . checked( $car_sunroof, 'checked', false ) . '> ' . __( '', 'text_domain' );
		echo '			<span class="description">' . __( 'Car has sunroof.', 'text_domain' ) . '</span>';
		echo '		</td>';
		echo '	</tr>';

		echo '</table>';

	}

	public function save_metabox( $post_id, $post ) {

		// Add nonce for security and authentication.
		$nonce_name   = $_POST['car_nonce'];
		$nonce_action = 'car_nonce_action';

		// Check if a nonce is set.
		if ( ! isset( $nonce_name ) )
			return;

		// Check if a nonce is valid.
		if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) )
			return;

		// Check if the user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return;

		// Check if it's not an autosave.
		if ( wp_is_post_autosave( $post_id ) )
			return;

		// Check if it's not a revision.
		if ( wp_is_post_revision( $post_id ) )
			return;

		// Sanitize user input.
		$car_new_year = isset( $_POST[ 'car_year' ] ) ? sanitize_text_field( $_POST[ 'car_year' ] ) : '';
		$car_new_mileage = isset( $_POST[ 'car_mileage' ] ) ? sanitize_text_field( $_POST[ 'car_mileage' ] ) : '';
		$car_new_cruise_control = isset( $_POST[ 'car_cruise_control' ] ) ? 'checked' : '';
		$car_new_power_windows = isset( $_POST[ 'car_power_windows' ] ) ? 'checked' : '';
		$car_new_sunroof = isset( $_POST[ 'car_sunroof' ] ) ? 'checked' : '';

		// Update the meta field in the database.
		update_post_meta( $post_id, 'car_year', $car_new_year );
		update_post_meta( $post_id, 'car_mileage', $car_new_mileage );
		update_post_meta( $post_id, 'car_cruise_control', $car_new_cruise_control );
		update_post_meta( $post_id, 'car_power_windows', $car_new_power_windows );
		update_post_meta( $post_id, 'car_sunroof', $car_new_sunroof );

	}

}

new WGExtra_Posts_Metabox;
?>