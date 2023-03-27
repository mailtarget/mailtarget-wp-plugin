<?php //phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Input phone
 *
 * @category   Input phone
 * @package    Mailtarget Form
 * @NeedTODO support CKEditor tag, adjust esc_html to support CKEditor tag //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
 */

$setting = $row['setting']; //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
?>
<div class="mt-c-form__wrap">
	<div div class="mt-c-form__input">
		<?php if ( $setting['showTitle'] ) { ?>
			<label class="mt-o-label"><?php echo esc_html( $setting['title'] ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></label>
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
