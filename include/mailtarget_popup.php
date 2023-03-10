<?php //phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * MailTarget_Popup.php Doc Comment
 *
 * Mailtarget Popup is file that show on popup.
 *
 * @category   MailTarget_Popup
 * @package    Mailtarget Form
 */

/**
 * MailTarget_Popup
 */
class MailTarget_Popup {

	/**
	 * Init.
	 **/
	public static function init() {
		$form_id      = esc_attr( get_option( 'mtg_popup_form_id' ) );
		$delay        = intval( esc_attr( get_option( 'mtg_popup_delay' ) ) ) * 1000;
		$popup_enable = esc_attr( get_option( 'mtg_popup_enable' ) ) === '1';
		if ( '' === $form_id ) {
			return false;
		}
		if ( ! $popup_enable ) {
			return false;
		}
		require_once MAILTARGET_PLUGIN_DIR . '/include/mailtarget_form.php';
		?>
		<div class="mtg-popup-modal" style="display: none">
			<div class="modal"><?php echo esc_html( mailtarget_load_popup( $form_id ) ); ?></div>
		</div>
		<script>
			var delay = <?php echo esc_attr( $delay ); ?>;
			var modal = new tingle.modal({
				footer: false,
				stickyFooter: false,
				closeMethods: ['overlay', 'button', 'escape'],
				closeLabel: "Close",
				cssClass: ['custom-class-1', 'custom-class-2'],
				onOpen: function() {
					console.log('modal open');
				},
				onClose: function() {
					console.log('modal closed');
				},
				beforeClose: function() {
					return true; // close the modal
				}
			});
			modal.setContent(document.querySelector('.mtg-popup-modal').innerHTML);

			if (getCookie('mtg_popup_time') === '') {
				setTimeout(function () {
					modal.open()
					setCookie('mtg_popup_time', 1, 20)
				}, delay)
			}

			function setCookie(cname, cvalue, exmin) {
				var d = new Date();
				d.setTime(d.getTime() + (exmin*60*1000));
				var expires = "expires="+ d.toUTCString();
				document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
				console.log('cookie setted')
			}

			function getCookie(cname) {
				var name = cname + "=";
				var decodedCookie = decodeURIComponent(document.cookie);
				var ca = decodedCookie.split(';');
				for(var i = 0; i <ca.length; i++) {
					var c = ca[i];
					while (c.charAt(0) == ' ') {
						c = c.substring(1);
					}
					if (c.indexOf(name) == 0) {
						return c.substring(name.length, c.length);
					}
				}
				return "";
			}
		</script>
		<?php
	}
}

add_action( 'wp_footer', array( 'MailTarget_Popup', 'init' ) );
