<?php
$targetPage = 'mailtarget-form-plugin--admin-menu-widget-add';
if (isset($_GET['for'])) {
    if ($_GET['for'] == 'popup') $targetPage = 'mailtarget-form-plugin--admin-menu-popup-main';
}
$pg = 1;
if (isset($_GET['pg'])) $pg = $_GET['pg'];
?>
<div class="mtg-form-plugin">
    <div class="mtg-banner">
        <img src="<?php echo MAILTARGET_PLUGIN_URL ?>/assets/image/logo.png" />
    </div>

    <div class="wrap">
        <h1 class="wp-heading-inline">Select Form - Mailtarget Form</h1>
        <p>Below is list of your MailTarget Form, select one of your form to setup.</p>

        <?php if (count($forms['data']) < 1) {?>
            <div class="update-nag">List empty, start by
                <a href="admin.php?page=mailtarget-form-plugin--admin-menu-widget-add">creating one</a></div><?php
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
                $no = (10 * ($pg - 1));
                foreach ($forms['data'] as $item) {
                    $no++;
                    ?>
                    <tr>
                        <td><?php echo $no ?></td>
                        <td><?php echo date('Y-m-d H:i', $item['createdAt']/1000) ?></td>
                        <td><?php echo $item['name'] ?></td>
                        <td><?php
                            $status = ($item['published']) ? 'published' : 'not published';
                            if ($item['setting']['captcha']) $status .= ', captcha enabled';
                            echo $status;
                            ?></td>
                        <td>
                            <?php if ($item['published']) {
                                ?><a href="admin.php?page=<?php echo $targetPage ?>&form_id=<?php echo $item['formId'] ?>">Select</a><?php
                            } else {
                                ?><?php
                            } ?>

                        </td>
                    </tr>
                    <?php
                }

                ?></table>
            <div class="nav" style="margin-top: 15px;">
            <?php
            if ($pg > 1) {
                ?><a class="page-title-action" href="admin.php?page=mailtarget-form-plugin--admin-menu-widget-form&pg=<?php echo ($pg - 1) ?>">previous</a> <?php
            }
            if (count($forms['data']) > 9) {
                ?><a class="page-title-action" style="float: right" href="admin.php?page=mailtarget-form-plugin--admin-menu-widget-form&pg=<?php echo ($pg + 1) ?>">next</a><?php
            }
            ?></div><?php
        }
        ?>
    </div>
</div>