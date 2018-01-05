<div class="wrap">
    <h1>Widget List - Mailtarget Form</h1>
    <?php if ($valid === null) { ?>
        <div class="update-nag">Token not correctly set / empty, please update
            <a href="admin.php?page=mailtarget-form-plugin--admin-menu-config">config</a></div><?php
    } else { ?><p>List widget page</p><?php }?>

    <?php if ($valid) {
        if (count($widgets) < 1) {?>
            <div class="update-nag">List empty, start by
                <a href="admin.php?page=mailtarget-form-plugin--admin-menu-widget-add">creating one</a></div><?php
        } else {
            ?>
            <a href="admin.php?page=mailtarget-form-plugin--admin-menu-widget-add">new widget</a>
            <table>
                <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
                </thead>
            <?php
            $no = 1;
            foreach ($widgets as $item) {
                ?>
                <tr>
                    <td><?php echo $no ?></td>
                    <td><?php echo $item->name ?></td>
                    <td><?php echo $item->time ?></td>
                    <td>
                        <a href="admin.php?page=mailtarget-form-plugin--admin-menu-widget-edit&id=<?php echo $item->id ?>">Edit</a>
                        <a href="admin.php?page=mailtarget-form-plugin--admin-menu&action=delete&id=<?php echo $item->id ?>">Delete</a>
                    </td>
                </tr>
                <?php
                $no++;
            }
            ?></table><?php
        }
    } ?>
</div>