<?php
/**
 * Input upload
 *
 * @category   Input upload
 * @package    Mailtarget Form
 */

$setting = $row['setting'];

?>
<div class="mt-c-form__wrap">
	<div div class="mt-c-form__upload">
		<?php if ( $setting['showTitle'] ) { ?>
			<label class="mt-o-label"><?php echo esc_html( $setting['title'] ); ?></label>
		<?php } ?>
		<input
			type="file"
			name="mtin__<?php echo esc_html( $setting['name'] ); ?>"
			class="mt-o-upload"
			<?php
			if ( $setting['required'] ) {
				?>
				required="required" <?php } ?> />
	</div>
</div>
