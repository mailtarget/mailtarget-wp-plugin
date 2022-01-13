<?php
$setting = $row['setting'];
//print_r($setting);
?>
<div class="mt-c-form__wrap">
    <div div class="mt-c-form__upload">
		<?php if ($setting['showTitle']) { ?>
            <label class="mt-o-label"><?php echo $setting['title'] ?></label>
		<?php } ?>
        <input
            type="file"
            name="mtin__<?php echo $setting['name']; ?>"
            class="mt-o-upload"
            <?php if ($setting['required']) { ?> required="required" <?php } ?> />
    </div>
</div>
