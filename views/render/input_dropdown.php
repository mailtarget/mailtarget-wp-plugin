<?php
$setting = $row['setting'];
//print_r($setting);
$options = array();
if (in_array($setting['name'], array('country', 'city', 'gender'))) {
	$options = $setting['options'];
} else {
	foreach ($setting['options'] as $item) {
		$options[] = $item['name'];
	}
}
?>
<div class="mt-c-form__wrap">
    <div div class="mt-c-form__dropdown">
		<?php if ($setting['showTitle']) { ?>
            <label class="mt-o-label" v-if="setting.showTitle"><?php echo $setting['title'] ?></label>
		<?php } ?>
        <select name="mtin__<?php echo $setting['name']; ?>">
            <?php
            foreach ($options as $item) {
                ?><option value="<?php echo $item; ?>"><?php echo $item; ?></option><?php
            }
            ?>
        </select>
    </div>
</div>