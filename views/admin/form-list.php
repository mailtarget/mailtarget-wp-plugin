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

$target_page = 'mailtarget-form-plugin--admin-menu-widget-add';
$for         = isset( $_GET['for'] ) ? sanitize_text_field( wp_unslash( $_GET['for'] ) ) : '';
if ( 'popup' === $for ) {
    $target_page = 'mailtarget-form-plugin--admin-menu-popup-main';
}

$pg = isset( $_GET['pg'] ) ? intval( $_GET['pg'] ) : 1;
?>
<div class="mtg-form-plugin">
	<div class="mtg-banner">
		<img src="<?php echo esc_url( MAILTARGET_PLUGIN_URL . '/assets/image/logo.png' ); ?>" />
	</div>

	<div class="wrap">
		<h1 class="wp-heading-inline">Select Form - MTARGET Form</h1>
		<p>Below is list of your MTARGET Form, select one of your form to setup.</p>

		<?php if ( count( $forms['data'] ) < 1 ) { 
			$url = wp_nonce_url( $target_page, 'admin-menu-widget-form-action' );
			?>
			<div class="update-nag">List empty, start by
				<a href="<?php echo esc_url( $url ); ?>">creating one</a></div>
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
				$no = ( 10 * ( $pg - 1 ) );
				foreach ( $forms['data'] as $item ) {
					$no++;
					?>
					<tr>
						<td><?php echo esc_attr( $no ); ?></td>
						<td><?php echo esc_attr( gmdate( 'Y-m-d H:i', $item['createdAt'] / 1000 ) ); ?></td>
						<td><?php echo esc_attr( $item['name'] ); ?></td>
						<td>
						<?php
							$statuse = ( $item['published'] ) ? 'published' : 'not published';
						if ( $item['setting']['captcha'] ) {
							$statuse .= ', captcha enabled';
						}
							echo esc_attr( $statuse );
						?>
							</td>
						<td>
							<?php
							if ( $item['published'] ) {
								?>
								<a href="admin.php?page=<?php echo esc_attr( $target_page ); ?>&form_id=<?php echo esc_attr( $item['formId'] ); ?>">Select</a>
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
			if ( $pg > 1 ) {
				$prev_url = wp_nonce_url( 'admin.php?page=mailtarget-form-plugin--admin-menu-widget-form' . esc_attr( $for ) . '&pg=' . esc_attr( $pg - 1 ), 'admin-menu-widget-form-action' );
				?>
				<a class="page-title-action" href="<?php echo esc_url( $prev_url ); ?>">Previous</a> 
				<?php
			}
			if ( count( $forms['data'] ) > 9 ) {
				$next_url = wp_nonce_url( 'admin.php?page=mailtarget-form-plugin--admin-menu-widget-form' . esc_attr( $for ) . '&pg=' . esc_attr( $pg + 1 ), 'admin-menu-widget-form-action' );
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