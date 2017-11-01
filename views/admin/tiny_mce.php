<html>
<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script type="text/javascript"
	        src="<?php echo site_url() . '/wp-includes/js/tinymce/tiny_mce_popup.js'; ?>"></script>
</head>

<body>

<h2>Add form</h2>

<form id="mailtarget_tinymce_form" action="" method="post">

	<p><?php _e('Select form from list below, and hit "Add Shortcode" to add the shortcode to your post!', 'mailtarget'); ?></p>

	<p>
		<label for="mailtarget_form_id">Form :</label>
		<select class="widefat" id="mailtarget_form_id" name="mailtarget_form_id" style="font-size: 14px;">
			<?php foreach ($forms as $form): ?>
				<option value="<?php echo esc_attr($form->id); ?>"><?php echo esc_attr($form->name); ?></option>
			<?php endforeach; ?>
		</select>
	</p>
    <p>
        <input type="button" name="<?php _e('Add Shortcode', 'mailtarget'); ?>" value="Add Shortcode" style="font-size: 14px;">
    </p>


</form>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('form#mailtarget_tinymce_form input:button').click(function () {
            var form_id = jQuery('form#mailtarget_tinymce_form #mailtarget_form_id').val();
            var shortcode = '[mailtarget_form form_id=' + form_id + ']';
            tinyMCEPopup.execCommand("mceInsertContent", false, shortcode);
            tinyMCEPopup.close();
            return false;
        });
    });
</script>
</body>
</html>
