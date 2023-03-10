<?php
/**
 * Input dropdown
 *
 * @category   Input dropdown
 * @package    Mailtarget Form
 */

$setting = $row['setting'];

$options = array();
if ( in_array( $setting['name'], array( 'country', 'city', 'gender' ), true ) ) {
	$options = $setting['options'];
} else {
	foreach ( $setting['options'] as $item ) {
		$options[] = $item['name'];
	}
}
?>
<div class="mt-c-form__wrap">
	<div div class="mt-c-form__dropdown">
		<?php if ( $setting['showTitle'] ) { ?>
			<label class="mt-o-label" v-if="setting.showTitle"><?php echo esc_html( $setting['title'] ); ?></label>
		<?php } ?>
		<select name="mtin__<?php echo esc_html( $setting['name'] ); ?>">
			<?php
			foreach ( $options as $item ) {
				?>
				<option value="<?php echo esc_html( $item ); ?>"><?php echo esc_html( $item ); ?></option>
				<?php
			}
			?>
		</select>
	</div>
</div>
