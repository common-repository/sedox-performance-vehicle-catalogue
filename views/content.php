<?php
/**
 * @var $data array
 */

use SedoxVDb\Util\Util;

$customizationOptions = get_option(SEDOX_VDB_MAIN_MENU_SLUG.'_customization');
$theme = esc_html($customizationOptions['catalog_theme']);
$customCssClass = esc_html($customizationOptions['catalog_custom_css_class']);
$primaryColor = esc_html($customizationOptions['primary_color']);
$secondaryColor = esc_html($customizationOptions['secondary_color']);

$pluginLocale = Util::getPluginLocale();

$logoUrl = $customizationOptions['company_logo'] ?
    wp_get_attachment_url($customizationOptions['company_logo']) :
    plugin_dir_url(__DIR__) . 'assets/images/logo.png';

$langClass = '';
$cyrillicLanguages = ['bg', 'be', 'kk', 'mk', 'mn', 'ru', 'sr', 'tt'];
$originalLocale = determine_locale();

switch_to_locale($pluginLocale['wp_code']);

if($pluginLocale) {
    $localeShort = substr($pluginLocale['wp_code'], 0, 2);
    if(in_array($localeShort, $cyrillicLanguages)) {
        $langClass = 'cyrillic';
    }
}
?>

<div class="configurator-content <?= $langClass . ' ' . $theme . ' ' . $customCssClass ?>" id="sedox-vdb-plugin">
    <div class="configurator-dropdowns">
        <div class="configurator-dropdowns-content">
            <div class="configurator-dropdowns-top">
                <h1 style="color: <?= $primaryColor ?>">
                    <?= esc_html__('Please Select Your Vehicle', SEDOX_VDB_TEXT_DOMAIN) ?>
                </h1>
                <ul class="box-brand-list" id="boxBrandList">
	                <?php foreach ($data['vehicleTypes'] as $k => $vehicleType) {
	                    $name = explode(' ', $vehicleType['name']);
	                    $name = array_shift($name);
	                    ?>
                    <li class="item item--<?= strtolower($name) ?> <?= $k > 0 ? ' b-left':'selected' ?>"
                        data-id="<?= $vehicleType['id'] ?>"
                        data-slug="<?= $vehicleType['slug'] ?>">Cars</li>
	                <?php } ?>
                </ul>
            </div>
            <div class="sedox-logo">
                <img src="<?= $logoUrl ?>" alt="">
            </div>
        </div>
        <div class="box-dropdowns">
            <div id="sedox-loader-wrapper" class="hidden">
                <div class="sedox-loader"></div>
            </div>
            <section class="col-dropdown">
                <label class="" for="inpManufacturer">
	                <?= esc_html__('Brand', SEDOX_VDB_TEXT_DOMAIN) ?>:
                </label>
                <select name="brand" id="inpManufacturer" class="custom-select" disabled>
                    <option value="" selected=""><?= esc_html__('Please select brand', SEDOX_VDB_TEXT_DOMAIN) ?></option>
                </select>
            </section>
            <section class="col-dropdown">
                <label class="" for="inpModel">
	                <?= esc_html__('Model', SEDOX_VDB_TEXT_DOMAIN) ?>:
                </label>
                <select name="model" id="inpModel" class="custom-select" disabled>
                    <option value="" selected=""><?= esc_html__('Please select model', SEDOX_VDB_TEXT_DOMAIN) ?></option>
                </select>
            </section>
            <section class="col-dropdown">
                <label class="" for="inpGeneration">
	                <?= esc_html__('Generation', SEDOX_VDB_TEXT_DOMAIN) ?>:
                </label>
                <select name="model" id="inpGeneration" class="custom-select" disabled>
                    <option value="" selected=""><?= esc_html__('Please select generation', SEDOX_VDB_TEXT_DOMAIN) ?></option>
                </select>
            </section>
            <section class="col-dropdown">
                <label class="" for="inpEngine">
	                <?= esc_html__('Engine', SEDOX_VDB_TEXT_DOMAIN) ?>:
                </label>
                <select name="engine" id="inpEngine" class="custom-select" disabled>
                    <option value="" selected="">
		                <?= esc_html__('Please select engine', SEDOX_VDB_TEXT_DOMAIN) ?>
                    </option>
                    <optgroup data-group="Diesel" label="<?= esc_html__('Diesel', SEDOX_VDB_TEXT_DOMAIN) ?>" id="diesel"></optgroup>
                    <optgroup data-group="Petrol" label="<?= esc_html__('Petrol', SEDOX_VDB_TEXT_DOMAIN) ?>" id="petrol"></optgroup>
                    <optgroup data-group="Hybrid" label="<?= esc_html__('Hybrid', SEDOX_VDB_TEXT_DOMAIN) ?>" id="hybrid"></optgroup>
                    <optgroup data-group="" label="<?= esc_html__('Other', SEDOX_VDB_TEXT_DOMAIN) ?>" id="other"></optgroup>
                </select>
            </section>
            <section class="col-dropdown col-dropdown--last text-right">
                <button type="submit" disabled id="btnShowRemaps" class="btn-show-remaps hidden" style="background-color: <?= $primaryColor ?>">
	                <?= esc_html__('View remaps', SEDOX_VDB_TEXT_DOMAIN) ?>
                </button>
                <button type="button" id="btnCopyLink" class="btn-copy-link hidden" style="background-color: <?= $secondaryColor ?>">
                    <?= esc_html__('Copy link', SEDOX_VDB_TEXT_DOMAIN) ?>
                </button>
                <input type="text" style="position: absolute !important; left: -99999px" id="inpUrlToCopy">
            </section>
        </div>
    </div>

    <div id="boxCarData" class="boxCarData hidden"></div>
</div>
<?php
switch_to_locale($originalLocale);
