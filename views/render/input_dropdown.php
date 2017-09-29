<?php
$setting = $row['setting'];
//print_r($setting);
?>
<div class="mt-c-form__wrap">
    <div div class="mt-c-form__input">
		<?php if ($setting['showTitle']) { ?>
            <label class="mt-o-label" v-if="setting.showTitle"><?php echo $setting['title'].$setting['fieldType'] ?></label>
		<?php } ?>
        <input type="<?php echo $setting['fieldType']; ?>" class="mt-o-input" :placeholder="<?php echo $setting['description']; ?>">
    </div>
</div>