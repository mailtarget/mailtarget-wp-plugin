<?php //phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Input dropdown
 *
 * @category   Input dropdown
 * @package    Mailtarget Form
 * @NeedTODO support CKEditor tag, adjust esc_html to support CKEditor tag //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
 */

$setting = $row['setting']; //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

$options = array();  //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
if ( in_array( $setting['name'], array( 'country', 'city', 'gender' ), true ) ) {
	$options = $setting['options'];  //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
} else {
	foreach ( $setting['options'] as $item ) { //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
		$options[] = $item['name']; //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	}
}
?>
<div class="mt-c-form__wrap">
	<div div class="mt-c-form__dropdown">
		<?php if ( $setting['showTitle'] ) { ?>
			<label class="mt-o-label" v-if="setting.showTitle"><?php echo esc_html( $setting['title'] ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></label>
		<?php } ?>
		<select name="mtin__<?php echo esc_html( $setting['name'] ); ?>">
			<?php
			foreach ( $options as $item ) { //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
				?>
				<option value="<?php echo esc_html( $item ); ?>"><?php echo esc_html( $item ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></option>
				<?php
			}
			?>
		</select>
	</div>
</div>
