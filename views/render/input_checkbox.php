<?php
$setting = $row['setting'];
?>
<div class="mt-c-form__wrap">
    <div div class="mt-c-form__checkbox">
		<?php if ($setting['showTitle']) { ?>
            <label class="mt-o-label" v-if="setting.showTitle"><?php echo $setting['title'] ?></label>
		<?php } ?>
        <?php
        foreach ($setting['options'] as $item) {
            ?><input type="checkbox" class="mt-o-checkbox" name="mtin__<?php echo $setting['name']; ?>" value="<?php echo $item['name'] ?>"><?php
            if ($setting['showImage'] === false) {
                ?><div class="mt-c-checkbox__text"><p><?php echo $item['name'] ?></p></div><?php
            } else {
                ?>
                <div v-else class="mt-c-checkbox__image">
                    <?php if ($item['image'] !== '') {
                        ?><img src="<?php echo $item['image'] ?>" alt=""><?php
                    } else {
	                    ?><img src="https://mailtarget.co/static/assets/images/image-placeholder.svg" alt=""><?php
                    } ?>
                    <?php if ($setting['showImage']) {
                        ?><p><?php echo $item['name'] ?></p><?php
                    } ?>
                </div>
                <?php
            }
        }
        ?>
    </div>
</div>