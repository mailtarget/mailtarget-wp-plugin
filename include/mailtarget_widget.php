<?php //phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * MailTarget_Widget.php Doc Comment
 *
 * MailTarget_Widget is file that make mailtarget widget.
 *
 * @category   MailTarget_Widget
 * @package    Mailtarget Form
 */

/**
 * MailTarget_Widget
 */
class MailTarget_Widget extends WP_Widget {

	/**
	 * __construct
	 */
	public function __construct() {
		parent::__construct(
			'mailtarget_widget',
			__( 'MailTarget Form', 'mailtarget' ),
			array(
				'description' => __(
					'MailTarget Form Widget',
					'mailtarget'
				),
			)
		);
	}

	/**
	 * Widget
	 *
	 * @param mixed $args is arguments passed.
	 * @param mixed $instance is instance passed.
	 */
	public function widget( $args, $instance ) {
		$widget_id = '';
		if ( ! isset( $instance['mailtarget_form_id'] ) ) {
			$widget_id = sanitize_key( preg_replace( '/[^0-9]/', '', $args['id'] ) );
		} else {
			$widget_id = sanitize_key( $instance['mailtarget_form_id'] );
		}

		if ( '' === $widget_id ) {
			echo 'id not recognize';
			return false;
		}

		require_once MAILTARGET_PLUGIN_DIR . '/include/mailtarget_form.php';
		mailtarget_load_form( $widget_id );
	}

	/**
	 * Form
	 *
	 * @param mixed $instance is instance passed.
	 */
	public function form( $instance ) {
		global $wpdb;
		$widgets = $wpdb->get_results( 'select * from ' . $wpdb->base_prefix . 'mailtarget_forms order by time desc limit 500' );  // WPCS: db call ok. // WPCS: cache ok.
		$id      = 0;
		if ( isset( $instance['mailtarget_form_id'] ) ) {
			$id = $instance['mailtarget_form_id'];
		}
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'mailtarget_form_id' ) ); ?>">
				<?php echo esc_html( 'Select form:' ); ?>
			</label>
			<select class="widefat"
					id="<?php echo esc_attr( $this->get_field_id( 'mailtarget_form_id' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'mailtarget_form_id' ) ); ?>"
			>
				<?php
				foreach ( $widgets as $item ) {
					?>
					<option value="<?php echo esc_attr( $item->id ); ?>"
					<?php echo $item->id === $id ? esc_html( 'selected="selected"' ) : ''; ?>
					><?php echo esc_attr( $item->name ); ?></option>
								<?php
				}
				?>
			</select>
		</p>
		<?php
	}
}

/**
 * Mailtarget_register_widget.
 */
function mailtarget_register_widget() {
	register_widget( 'MailTarget_Widget' );
}

add_action( 'widgets_init', 'mailtarget_register_widget' );
