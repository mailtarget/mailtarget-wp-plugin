<div class="mtg-form-plugin">
    <div class="mtg-banner">
        <img src="<?php echo esc_url(MAILTARGET_PLUGIN_URL.'/assets/image/logo.png') ?>" />
    </div>
    <div class="wrap">
        <h1 class="wp-heading-inline">List - Mailtarget Form</h1>
        <?php if (count($widgets) < 1) {?>
            <div class="update-nag">List empty, start by
                <a href="admin.php?page=mailtarget-form-plugin--admin-menu-widget-form">creating one</a></div><?php
        } else {
            ?>
            <a class="page-title-action" href="admin.php?page=mailtarget-form-plugin--admin-menu-widget-form">new form</a>
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
            $no = 1;
            foreach ($widgets as $item) {
                ?>
                <tr>
                    <td><?php echo esc_attr($no) ?></td>
                    <td><?php echo esc_attr($item->name) ?></td>
                    <td>[mailtarget_form form_id=<?php echo esc_attr($item->id) ?>]</td>
                    <td><?php echo esc_attr($item->time) ?></td>
                    <td>
                        <a href="admin.php?page=mailtarget-form-plugin--admin-menu-widget-edit&id=<?php echo esc_attr($item->id) ?>">Edit</a> |
                        <a href="admin.php?page=mailtarget-form-plugin--admin-menu&action=delete&id=<?php echo esc_attr($item->id) ?>">Delete</a>
                    </td>
                </tr>
                <?php
                $no++;
            }
            ?></table><?php
        } ?>
    </div>
</div>