<div class="wrap">
	<h1>Mailtarget Form Setting</h1>
	<p>Create your widget</p>
	<form method="post" action="options-general.php?page=mailtarget-form-plugin--admin-menu">
		<?php settings_fields( $this->option_group ); ?>
		<?php do_settings_sections( $this->option_group ); ?>
		<table class="form-table">
			<tr class="user-rich-editing-wrap">
				<th>Form</th>
				<td><strong><?php echo $form['name'] ?></strong>
                    <input type="hidden" name="form_id" value="<?php echo $form['formId'] ?>">
                </td>
			</tr>
			<tr class="user-rich-editing-wrap">
				<th>Name</th>
				<td>
                    <input type="text" class="regular-text" name="widget_name">
                </td>
			</tr>
			<tr class="user-rich-editing-wrap">
				<th>Title</th>
				<td>
                    <input type="text" class="regular-text" name="widget_title">
                </td>
			</tr>
			<tr class="user-rich-editing-wrap">
				<th>Description</th>
				<td>
                    <textarea class="regular-text" name="widget_description"></textarea>
                </td>
			</tr>
			<tr class="user-rich-editing-wrap">
				<th>Submit Title</th>
				<td>
                    <input type="text" class="regular-text" name="widget_submit_desc">
                </td>
			</tr>

			<tr>
				<td></td>
				<td>
					<input type="hidden" value="create_widget" name="mailtarget_form_action">
					<?php submit_button('Create Widget'); ?></td>
			</tr>
		</table>
	</form>
</div>