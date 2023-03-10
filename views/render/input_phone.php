<?php
/**
 * Input phone
 *
 * @category   Input phone
 * @package    Mailtarget Form
 */

$setting = $row['setting'];

?>
<div class="mt-c-form__wrap">
	<div div class="mt-c-form__input">
		<?php if ( $setting['showTitle'] ) { ?>
			<label class="mt-o-label"><?php echo esc_html( $setting['title'] ); ?></label>
		<?php } ?>
		<input
			type="<?php echo esc_html( $setting['fieldType'] ); ?>"
			name="mtin__<?php echo esc_html( $setting['name'] ); ?>"
			class="mt-o-input-phone"
			<?php
			if ( $setting['required'] ) {
				?>
				required="required" <?php } ?>
			placeholder="<?php echo esc_html( $setting['description'] . ' (+62-8xxxxxxxxxx)' ); ?>">
	</div>
</div>
