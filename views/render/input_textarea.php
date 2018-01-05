<?php
$setting = $row['setting'];
//print_r($setting);
?>
<div class="mt-c-form__wrap">
    <div div class="mt-c-form__textarea">
		<?php if ($setting['showTitle']) { ?>
            <label class="mt-o-label" v-if="setting.showTitle"><?php echo $setting['title'] ?></label>
		<?php } ?>
        <textarea class="mt-o-textarea" :placeholder="<?php echo $setting['description']; ?>"></textarea>
    </div>
</div>