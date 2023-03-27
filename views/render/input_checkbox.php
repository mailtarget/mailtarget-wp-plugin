<?php //phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Input checkbox
 *
 * @category   Input checkbox
 * @package    Mailtarget Form
 * @TODO support CKEditor tag, adjust esc_html to support CKEditor tag //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
 */

$setting = $row['setting']; //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
?>
<div class="mt-c-form__wrap">
	<div div class="mt-c-form__checkbox">
		<?php if ( $setting['showTitle'] ) { ?>
			<label class="mt-o-label" v-if="setting.showTitle"><?php echo esc_html( $setting['title'] ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></label>
		<?php } ?>
		<div class="<?php echo ( $setting['showImage'] && 'grid' === $setting['styleOption'] ) ? 'mt-c-checkbox__wrap--grid' : 'mt-c-checkbox__wrap'; ?>">
			<?php
			foreach ( $setting['options'] as $item ) { //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
				?>
				<label class="mt-c-checkbox">
					<input type="checkbox" class="mt-o-checkbox" name="mtin__<?php echo esc_html( $setting['name'] ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>[]" value="<?php echo esc_html( $item['name'] ); ?>">
																						<?php
																						if ( false === $setting['showImage'] ) {
																							?>
						<div class="mt-c-checkbox__text"><p><?php echo esc_html( $item['name'] ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p></div>
																							<?php
																						} else {
																							?>
						<div v-else class="mt-c-checkbox__image">
																								<?php
																								if ( '' !== $item['image'] ) {
																									?>
								<img src="<?php echo esc_html( $item['image'] ); ?>" alt="">
																									<?php
																								} else {
																									?>
								<img src="https://mailtarget.co/static/assets/images/image-placeholder.svg" alt="">
																									<?php
																								}
																								?>
																								<?php
																								if ( $setting['showImage'] ) {
																									?>
								<p style="font-weight: normal;"><?php echo esc_html( $item['name'] ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
																									<?php
																								}
																								?>
						</div>
																							<?php
																						}
																						?>
				</label>
				<?php
			}
			?>
			<?php
			if ( $setting['showOtherOption'] ) {
				?>
				<div class="mt-c-checkbox">
					<input type="checkbox" class="mt-o-checkbox" name="mtiot__<?php echo esc_html( $setting['name'] ); ?>" value="yes">
					<div class="mt-c-checkbox__input">
						<input type="text" class="mt-o-input" placeholder="Other" name="mtino__<?php echo esc_html( $setting['name'] ); ?>">
					</div>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</div>
