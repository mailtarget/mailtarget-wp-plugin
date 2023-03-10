<?php
/**
 * Input text
 *
 * @category   Input text
 * @package    Mailtarget Form
 */

$setting = $row['setting'];

?>
<div class="mt-c-form__wrap">
	<div div class="mt-c-form__input">
		<?php if ( $setting['showTitle'] ) { ?>
			<label class="mt-o-label" v-if="setting.showTitle"><?php echo esc_html( $setting['title'] ); ?></label>
		<?php } ?>
		<input
				type="<?php echo esc_html( $setting['fieldType'] ); ?>"
				name="mtin__<?php echo esc_html( $setting['name'] ); ?>"
				class="mt-o-input"
				<?php
				if ( $setting['required'] ) {
					?>
					required="required" <?php } ?>
				placeholder="<?php echo esc_html( $setting['description'] ); ?>">
	</div>
</div>
