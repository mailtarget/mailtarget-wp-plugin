<?php
$setting = $row['setting'];

?>
<div class="mt-c-form__wrap">
    <div div class="mt-c-form__input">
		<?php if ($setting['showTitle']) { ?>
            <label class="mt-o-label"><?php echo $setting['title'] ?></label>
		<?php } ?>
        <input
            type="<?php echo $setting['fieldType']; ?>"
            name="mtin__<?php echo $setting['name']; ?>"
            class="mt-o-input-phone"
            <?php if ($setting['required']) { ?> required="required" <?php } ?>
            placeholder="<?php echo $setting['description'] . ' (+62-8xxxxxxxxxx)'; ?>">
    </div>
</div>
