<?php //phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Input text
 *
 * @category   Input text
 * @package    Mailtarget Form
 * @NeedTODO support CKEditor tag, adjust esc_html to support CKEditor tag //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
 */

$setting = $row['setting']; //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

?>
<div class="mt-c-form__wrap">
	<div div class="mt-c-form__input">
		<?php if ( $setting['showTitle'] ) { ?>
			<div>
				<label class="mt-o-label" v-if="setting.showTitle"><?php echo esc_html( strip_html_tags( $setting['title'] ) ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></label>
			</div>
		<?php } ?>
		<div>
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
</div>
