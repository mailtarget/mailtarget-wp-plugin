<?php
$setting = $row['setting'];
//print_r($setting);
?>
<div class="mt-c-form__wrap">
    <div div class="mt-c-form__textarea">
		<?php if ($setting['showTitle']) { ?>
            <label class="mt-o-label" v-if="setting.showTitle"><?php echo $setting['title'] ?></label>
		<?php } ?>
        <textarea class="mt-o-textarea"
                  name="mtin__<?php echo $setting['name']; ?>"
            <?php if ($setting['required']) { ?> required="required" <?php } ?>
                  placeholder="<?php echo $setting['description']; ?>"></textarea>
    </div>
</div>