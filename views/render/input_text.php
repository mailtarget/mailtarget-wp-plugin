<?php //phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Input text
 *
 * @category   Input text
 * @package    Mailtarget Form
 */

$setting = $row['setting']; //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

?>
<div class="mt-c-form__wrap">
	<div div class="mt-c-form__input">
		<?php if ( $setting['showTitle'] ) { ?>
			<label class="mt-o-label" v-if="setting.showTitle"><?php echo $setting['title']; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></label>
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
