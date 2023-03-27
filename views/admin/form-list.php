<?php
/**
 * Form List
 *
 * @package  Mailtarget_Form
 * @author    {{author_name}} <{{author_email}}>
 * @copyright {{author_copyright}}
 * @license   {{author_license}}
 * @link      {{author_url}}
 */

$mailtarget_target_page = 'mailtarget-form-plugin--admin-menu-widget-add'; //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$mailtarget_for         = isset( $_GET['for'] ) //phpcs:ignore WordPress.Security.NonceVerification.Recommended
	?
	sanitize_text_field( wp_unslash( $_GET['for'] ) ) //phpcs:ignore WordPress.Security.NonceVerification.Recommended
	: '';
if ( 'popup' === $mailtarget_for ) {
	$mailtarget_target_page = 'mailtarget-form-plugin--admin-menu-popup-main';
}

$mailtarget_pg = isset( $_GET['pg'] ) ? intval( $_GET['pg'] ) : 1; //phpcs:ignore WordPress.Security.NonceVerification.Recommended
?>
<div class="mtg-form-plugin">
	<div class="mtg-banner">
		<img src="<?php echo esc_url( MAILTARGET_PLUGIN_URL . '/assets/image/logo.svg' ); ?>" />
	</div>

	<div class="wrap">
		<h1 class="wp-heading-inline">Select Form - MTARGET Form</h1>
		<p>Below is list of your MTARGET Form, select one of your form to setup.</p>

		<?php
		if ( count( $forms['data'] ) < 1 ) {
			$mailtarget_url = wp_nonce_url( $mailtarget_target_page, 'admin-menu-widget-form-action' );
			?>
			<div class="update-nag">List empty, start by
				<a href="<?php echo esc_url( $mailtarget_url ); ?>">creating one</a></div>
				<?php
		} else {
			?>
			<table class="wp-list-table widefat fixed striped pages">
				<thead>
				<tr>
					<th width="5%">No</th>
					<th>Created At</th>
					<th>Name</th>
					<th>Status</th>
					<th>Action</th>
				</tr>
				</thead>
				<?php
				$no = ( 10 * ( $mailtarget_pg - 1 ) ); //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
				foreach ( $forms['data'] as $item ) { //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
					$no++;
					?>
					<tr>
						<td><?php echo esc_attr( $no ); ?></td>
						<td><?php echo esc_attr( gmdate( 'Y-m-d H:i', $item['createdAt'] / 1000 ) ); ?></td>
						<td><?php echo esc_attr( $item['name'] ); ?></td>
						<td>
						<?php
							$statuse = ( $item['published'] ) ? 'published' : 'not published'; //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
						if ( $item['setting']['captcha'] ) {
							$statuse .= ', captcha enabled'; //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
						}
							echo esc_attr( $statuse );
						?>
							</td>
						<td>
							<?php
							if ( $item['published'] ) {
								?>
								<a href="admin.php?page=<?php echo esc_attr( $mailtarget_target_page ); ?>&form_id=<?php echo esc_attr( $item['formId'] ); ?>">Select</a>
								<?php
							} else {
								?>
								<span></span>
								<?php
							}
							?>

						</td>
					</tr>
					<?php
				}

				?>
				</table>
			<div class="nav" style="margin-top: 15px;">
			<?php
			if ( $mailtarget_pg > 1 ) {
				$prev_url = wp_nonce_url( 'admin.php?page=mailtarget-form-plugin--admin-menu-widget-form' . esc_attr( $mailtarget_for ) . '&pg=' . esc_attr( $mailtarget_pg - 1 ), 'admin-menu-widget-form-action' ); //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
				?>
				<a class="page-title-action" href="<?php echo esc_url( $prev_url ); ?>">Previous</a> 
				<?php
			}
			if ( count( $forms['data'] ) > 9 ) {
				$next_url = wp_nonce_url( 'admin.php?page=mailtarget-form-plugin--admin-menu-widget-form' . esc_attr( $mailtarget_for ) . '&pg=' . esc_attr( $mailtarget_pg + 1 ), 'admin-menu-widget-form-action' ); //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
				?>
				<a class="page-title-action" style="float: right" href="<?php echo esc_url( $next_url ); ?>">Next</a>
				<?php
			}
			?>
			</div>
			<?php
		}
		?>
	</div>
</div>
