<?php
/**
 * WP Form List
 *
 * @category   WP Form List
 * @package    Mailtarget Form
 */

?>
<div class="mtg-form-plugin">
	<div class="mtg-banner">
		<img src="<?php echo esc_url( MAILTARGET_PLUGIN_URL . '/assets/image/logo.svg' ); ?>" />
	</div>
	<div class="wrap">
		<h1 class="wp-heading-inline">List - MTARGET Form</h1>
		<?php if ( count( $widgets ) < 1 ) { ?>
			<div class="update-nag">List empty, start by
				<?php
					$create_url = wp_nonce_url( 'admin.php?page=mailtarget-form-plugin--admin-menu-widget-form', 'admin-menu-widget-form-action' ); //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
				?>
				<a href="<?php echo esc_url( $create_url ); ?>">creating one</a></div>
				<?php
		} else {
				$new_url = wp_nonce_url( 'admin.php?page=mailtarget-form-plugin--admin-menu-widget-form', 'admin-menu-widget-form-action' ); //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
			?>
			<a class="page-title-action" href="<?php echo esc_url( $new_url ); ?>">New form</a>
			<p>Use this form widget as embed to your post or as sidebar widget. Manage your widget so users easily access your form.</p>
			<hr class="wp-header-end">
			<table class="wp-list-table widefat fixed striped pages">
			<thead>
			<tr>
				<th width="5%">No</th>
				<th>Name</th>
				<th>Shortcode</th>
				<th>Created At</th>
				<th>Action</th>
			</tr>
			</thead>
			<?php
			$no = 1; //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
			foreach ( $widgets as $item ) { //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
				?>
				<tr>
					<td><?php echo esc_attr( $no ); ?></td>
					<td><?php echo esc_attr( $item->name ); ?></td>
					<td>[mailtarget_form form_id=<?php echo esc_attr( $item->id ); ?>]</td>
					<td><?php echo esc_attr( $item->time ); ?></td>
					<td>
						<?php
							$edit_url   = wp_nonce_url( 'admin.php?page=mailtarget-form-plugin--admin-menu-widget-edit&id=' . $item->id, 'edit_action' ); //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
							$delete_url = wp_nonce_url( 'admin.php?page=mailtarget-form-plugin--admin-menu&action=delete&id=' . $item->id, 'delete_action' ); //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
						?>
						<a href="<?php echo esc_url( $edit_url ); ?>">Edit</a> |
						<a href="<?php echo esc_url( $delete_url ); ?>">Delete</a>
					</td>
				</tr>
				<?php
				$no++;
			}
			?>
			</table>
			<?php
		}
		?>
	</div>
</div>
