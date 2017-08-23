<?php
/**
 * RockPress Settings
 *
 * @package RockPress
 * @version 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * RockPress_Settings class
 */
class RockPress_Settings {

	/**
	 * Text Input Field
	 *
	 * @param array $args Arguments to pass to the function. (See below).
	 *
	 * @return void
	 */
	public function input_callback( $args ) {

		// Set the defaults.
		$defaults = array(
			'field_id'		=> null,
			'page_id'		=> null,
			'label'      	=> null,
			'type'          => 'text',
			'size'          => 'regular',
			'before'        => null,
			'after'         => null,
		);

		// Parse the arguments.
		$args = wp_parse_args( $args, $defaults );

		// Get the saved values from WordPress.
		$options = get_option( $args['page_id'] );

		// Start the output buffer.
		ob_start();
		?>
		<?php echo $args['before']; ?>
		<input type="<?php echo esc_attr( $args['type'] ); ?>" id="<?php echo esc_attr( $args['field_id'] ); ?>" name="<?php echo esc_attr( $args['page_id'] ); ?>[<?php echo esc_attr( $args['field_id'] ); ?>]" value="<?php echo ( isset( $options[ $args['field_id'] ] ) ? esc_attr( $options[ $args['field_id'] ] ) : '' ); ?>" class="<?php echo esc_attr( $args['size'] ); ?>-text" />
		<?php echo $args['after']; ?>
		<?php if ( '' !== $args['label'] ) : ?>
			<p class="description"><?php echo esc_html( $args['label'] ); ?></p>
		<?php endif; ?>

		<?php
		// Print the output.
		echo ob_get_clean();

	} // input_callback().

	/**
	 * License Key Field
	 *
	 * @param array $args Arguments to pass to the function. (See below).
	 *
	 * @return void
	 */
	public function license_key_callback( $args ) {

		// Set the defaults.
		$defaults = array(
			'field_id'		=> null,
			'page_id'		=> null,
			'label'      	=> null,
		);

		// Parse the arguments.
		$args = wp_parse_args( $args, $defaults );

		// Get the saved values from WordPress.
		$options = get_option( $args['page_id'] );

		// Start the output buffer.
		ob_start();
		?>
		<?php wp_nonce_field( $args['field_id'] . '-nonce', $args['field_id'] . '-nonce' ); ?>
		<input type="text" id="<?php echo esc_attr( $args['field_id'] ); ?>" name="<?php echo esc_attr( $args['page_id'] ); ?>[<?php echo esc_attr( $args['field_id'] ); ?>]" value="<?php echo ( isset( $options[ $args['field_id'] ] ) ? esc_attr( $options[ $args['field_id'] ] ) : '' ); ?>" class="regular-text" />
		<?php if ( 'valid' === get_option( $args['field_id'] . '_active' ) ) : ?>
			<input type="submit" class="button-secondary" name="<?php echo esc_attr( $args['field_id'] . '_deactivate' ); ?>" value="<?php esc_attr_e( 'Deactivate License', 'ft-rockpress' ); ?>">
		<?php endif; ?>
		<?php if ( '' !== $args['label'] ) : ?>
			<p class="description"><?php echo esc_html( $args['label'] ); ?></p>
		<?php endif; ?>

		<?php
		// Print the output.
		echo ob_get_clean();

	} // license_key_callback()

	/**
	 * Checkbox Input Field
	 *
	 * @param array $args Arguments to pass to the function.
	 *
	 * @return void
	 */
	public function checkbox_callback( $args ) {

		// Set the defaults.
		$defaults = array(
			'field_id'		=> null,
			'page_id'		=> null,
			'value'			=> '1',
			'label'      	=> null,
			'before'        => null,
			'after'         => null,
		);

		// Parse the arguments.
		$args = wp_parse_args( $args, $defaults );

	    // Get the saved values from WordPress.
		$options = get_option( $args['page_id'] );

		// Start the output buffer.
		ob_start();
		?>
		<?php echo $args['before']; ?>
		<input type="checkbox" id="<?php echo esc_attr( $args['field_id'] ); ?>" name="<?php echo esc_attr( $args['page_id'] ); ?>[<?php echo esc_attr( $args['field_id'] ); ?>]" value="<?php echo esc_attr( $args['value'] ); ?>" <?php isset( $options[ $args['field_id'] ] ) ? checked( $options[ $args['field_id'] ] ) : '' ?>/>
		<?php if ( '' !== $args['label'] ) : ?>
			<label for="<?php echo esc_attr( $args['field_id'] ); ?>" class="description"><?php echo esc_html( $args['label'] ); ?></label>
		<?php endif; ?>
		<?php echo $args['after']; ?>

		<?php
		// Print the output.
		echo ob_get_clean();

	} // input_callback()


	/**
	 * Select Input Field
	 *
	 * @param array $args Arguments to pass to the function.
	 *
	 * @return void
	 */
	public function select_callback( $args ) {

		// Set the defaults.
		$defaults = array(
			'field_id'      => null,
			'page_id'       => null,
			'label'         => null,
			'default'		=> '',
			'options'       => array(),
		);

		// Parse the arguments.
		$args = wp_parse_args( $args, $defaults );

		// Pull the variables from the array.
		$field_id		= $args['field_id'];
	    $page_id		= $args['page_id'];
	    $label_text		= $args['label'];
	    $select_options	= $args['options'];

	    // Get the saved values from WordPress.
		$options = get_option( $args['page_id'] );

		ob_start(); ?>
		<select id="<?php echo esc_attr( $args['field_id'] ); ?>" name="<?php echo esc_attr( $args['page_id'] ); ?>[<?php echo esc_attr( $args['field_id'] ); ?>]">
	    <?php
		// Loop through all of the available options.
		foreach ( $args['options'] as $key => $value ) : ?>
			<option <?php echo selected( ( empty( $options[ $args['field_id'] ] ) ? $args['default'] : $options[ $args['field_id'] ] ), $key, false ); ?> value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></option>
		<?php endforeach; ?>
		</select>
	    <span class="description"><?php echo esc_html( $args['label'] ); ?></span>
		<?php
	    // Print the output.
		echo ob_get_clean();

	} // select_callback()

	/**
	 * List Select Input Field
	 *
	 * @param array $args Arguments to pass to the function.
	 *
	 * @return void
	 */
	public function list_select_callback( $args ) {

		wp_enqueue_script( 'rockpress-fields', ROCKPRESS_PLUGIN_URL . 'assets/js/admin/fields.js', array( 'jquery' ), '1.0.0' );
		wp_enqueue_style( 'rockpress-fields', ROCKPRESS_PLUGIN_URL . 'assets/css/admin/fields.css' );

		// Set the defaults.
		$defaults = array(
			'field_id'      => null,
			'page_id'       => null,
			'label'         => null,
	        'options'       => array(),
			'input_title'   => null,
	        'select_title'  => null,
	        'right_title'   => null,
		);

		// Parse the arguments.
		$args = wp_parse_args( $args, $defaults );

	    // Get the saved values from WordPress.
		$options = get_option( $args['page_id'] );
	    $selected_values_original = ( empty( $options ) ? '' : $options[ $args['field_id'] ] );
	    $selected_values = explode( '&&', $selected_values_original );

	    ob_start(); ?>
	    <div class="description"><?php echo esc_html( $args['label'] ); ?></div><br />
	    <fieldset class="rockpress_list_select">
			<label for="<?php echo esc_attr( $args['field_id'] ); ?>_input"><strong><?php echo esc_html( $args['input_title'] ); ?></strong></label>
			<input type="text" id="<?php echo esc_attr( $args['field_id'] ); ?>_input" />
	    	<label for="<?php echo esc_attr( $args['field_id'] ); ?>_select"><strong><?php echo esc_html( $args['select_title'] ); ?></strong></label>
	    	<select id="<?php echo esc_attr( $args['field_id'] ); ?>_select">
			<?php foreach ( $args['options'] as $select_option ) : ?>
				<option value="<?php echo esc_attr( $select_option['value'] ); ?>"><?php echo esc_html( $select_option['name'] ); ?></option>
			<?php endforeach; ?>
			</select>
			<button class="button rockpress_list_select_add" id="<?php echo esc_attr( $args['field_id'] ); ?>_add"><?php esc_html_e( 'Add', 'ft-rockpress' ); ?></button>
		</fieldset>
		<fieldset class="rockpress_list_select_right">
			<label for="<?php echo esc_attr( $args['field_id'] ); ?>_selected"><strong><?php echo esc_html( $args['right_title'] ); ?></strong></label>
			<select size="15" id="<?php echo esc_attr( $args['field_id'] ); ?>_selected">
			<?php
			// Find all of the values that have been selected.
			foreach ( $args['options'] as $select_option ) :
				// Check that the value is in the array of selected values.
				foreach ( $selected_values as $value ) :
					$value_array = explode( '==', $value );
					if ( isset( $value_array[1] ) && $select_option['value'] === $value_array[1] ) : ?>
						<option value="<?php echo esc_attr( $select_option['value'] ); ?>"><?php echo esc_html( $value_array[0] ); ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endforeach; ?>
			</select>
			<button class="button rockpress_list_select_edit" id="<?php echo esc_attr( $args['field_id'] ); ?>_edit"><?php esc_html_e( 'Edit', 'ft-rockpress' ); ?></button>&nbsp;
			<button class="button rockpress_list_select_remove" id="<?php echo esc_attr( $args['field_id'] ); ?>_remove"><?php esc_html_e( 'Remove', 'ft-rockpress' ); ?></button>
		</fieldset>
		<input type="hidden" id="<?php echo esc_attr( $args['field_id'] ); ?>_values" name="<?php echo esc_attr( $args['page_id'] ); ?>[<?php echo esc_attr( $args['field_id'] ); ?>]" value="<?php echo esc_attr( $selected_values_original ); ?>" />
		<?php
		// Print the output.
		echo ob_get_clean();

	} // list_select_callback()

	/**
	 * Multi Select Input Field
	 *
	 * @param array $args Arguments to pass to the function.
	 *
	 * @return void
	 */
	public function multi_select_callback( $args ) {

		wp_enqueue_script( 'rockpress-fields', ROCKPRESS_PLUGIN_URL . 'assets/js/admin/fields.js', array( 'jquery' ), '1.0.0' );
		wp_enqueue_style( 'rockpress-fields', ROCKPRESS_PLUGIN_URL . 'assets/css/admin/fields.css' );

		// Set the defaults.
		$defaults = array(
			'field_id'      => null,
			'page_id'       => null,
			'label'         => null,
			'options'       => array(),
		);

		// Parse the arguments.
		$args = wp_parse_args( $args, $defaults );

	    // Get the saved values from WordPress.
		$options = get_option( $args['page_id'] );
	    $selected_values_original = ( empty( $options ) ? '' : ( isset( $options[ $args['field_id'] ] ) ? $options[ $args['field_id'] ] : '' ) );
	    $selected_values = explode( ',', $selected_values_original );

		ob_start(); ?>
		<?php if ( ! is_null( $args['label'] ) ) : ?>
			<div class="description"><?php echo esc_html( $args['label'] ); ?></div><br />
		<?php endif; ?>
	    <fieldset class="rockpress_multi_select">
	    	<legend><?php esc_html_e( 'Unselected:', 'ft-rockpress' ); ?></legend>
	    	<select multiple="multiple" size="15" id="<?php echo esc_attr( $args['field_id'] ); ?>_all">
			<?php
			if ( '' !== $args['options'] ) :
				// Find all of the values that have not been selected.
				foreach ( $args['options'] as $select_option ) :
					// Check that the value is not in the array of selected values.
					if ( ! in_array( $select_option['value'], $selected_values, true ) ) : ?>
						<option value="<?php echo esc_attr( $select_option['value'] ); ?>"><?php echo esc_html( $select_option['name'] ); ?></option>
			    	<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			</select>
	    	<button class="button rockpress_multi_select_add" id="<?php echo esc_attr( $args['field_id'] ); ?>_add"><?php esc_html_e( 'Add To Selected', 'ft-rockpress' ); ?></button>
	    </fieldset>
	    <fieldset class="rockpress_multi_select">
	    	<legend><?php esc_html_e( 'Selected:', 'ft-rockpress' ); ?></legend>
	    	<select multiple="multiple" size="15" id="<?php echo esc_attr( $args['field_id'] ); ?>_selected">
			<?php
			if ( '' !== $args['options'] ) :
				// Find all of the values that have been selected.
				foreach ( $args['options'] as $select_option ) :
					// Check that the value is in the array of selected values.
					if ( in_array( $select_option['value'], $selected_values, true ) ) : ?>
						<option value="<?php echo esc_attr( $select_option['value'] ); ?>"><?php echo esc_html( $select_option['name'] ); ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			</select>
			<button class="button rockpress_multi_select_remove" id="<?php echo esc_attr( $args['field_id'] ); ?>_remove"><?php esc_html_e( 'Remove From Selected', 'ft-rockpress' ); ?></button>
	    </fieldset>
	    <input type="hidden" id="<?php echo esc_attr( $args['field_id'] ); ?>_values" name="<?php echo esc_attr( $args['page_id'] ); ?>[<?php echo esc_attr( $args['field_id'] ); ?>]" value="<?php echo esc_attr( $selected_values_original ); ?>" />
		<?php
	    // Print the output.
		echo ob_get_clean();

	} // multi_select_callback()

	/**
	 * Textarea Input Field
	 *
	 * @param array $args Arguments to pass to the function.
	 *
	 * @return void
	 */
	public function textarea_callback( $args ) {

		// Set the defaults.
		$defaults = array(
			'field_id'      => null,
			'page_id'       => null,
			'textarea_id'   => null,
			'media_upload'	=> true,
			'rows'			=> get_option( 'default_post_edit_rows', 10 ),
			'cols'			=> 40,
			'minimal'		=> false,
			'wysiwyg'		=> false,
			'wpautop'		=> false,
		);

		// Parse the arguments.
		$args = wp_parse_args( $args, $defaults );

		// Get the saved values from WordPress.
		$options = get_option( $args['page_id'] );
		$editor_value = $options[ $args['field_id'] ];

	    // Checks if it should display the WYSIWYG editor.
		if ( true === $args['wysiwyg'] ) {

			wp_editor( $editor_value, $args['textarea_id'], array(
			    'textarea_name'	=> $args['page_id'] . '[' . $args['field_id'] . ']',
			    'media_buttons'	=> $args['media_upload'],
			    'textarea_rows'	=> $args['rows'],
			    'wpautop'		=> $args['wpautop'],
			    'teeny'			=> $args['minimal'],
		    ) );

	    } else {

			// Display the plain textarea field.
			echo '<textarea rows="' . esc_attr( $args['rows'] ) . '" cols="' . esc_attr( $args['cols'] ) . '" name="' . esc_attr( $args['page_id'] ) . '[' . esc_attr( $args['field_id'] ) . ']" id="' . esc_attr( $args['textarea_id'] ) . '" class="rockpress code">' . esc_html( $editor_value ) . '</textarea>';

	    }

	} // textarea_callback()

	/**
	 * Text
	 *
	 * @param array $args Arguments to pass to the function.
	 *
	 * @return void
	 */
	public function text_callback( $args ) {

		// Set the defaults.
		$defaults = array(
			'header'	=> 'h2',
			'title'		=> null,
			'content'	=> null,
		);

		// Parse the arguments.
		$args = wp_parse_args( $args, $defaults );

		ob_start();
		// Check that the title and header_type are not blank.
		if ( ! is_null( $args['title'] ) ) {
			echo '<' . esc_attr( $args['header'] ) . '>' . esc_html( $args['title'] ) . '</' . esc_attr( $args['header'] ) . '>';
	    }

	    // Check that the content is not blank.
		if ( ! is_null( $args['content'] ) ) {
			echo $args['content'];
	    }

		// Print the output.
	    echo ob_get_clean();

	} // text_callback()

}
