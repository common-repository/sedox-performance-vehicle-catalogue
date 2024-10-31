<?php
/**
 * @package SedoxPerformanceCatalogPlugin
 */

namespace SedoxVDb\Api\Callbacks;


use SedoxVDb\Base\BaseController;

class ManagerCallbacks extends BaseController
{
	public function textSanitize($input)
	{

		foreach ($this->options['configuration'] as $option) {
			if(isset($input[$option['name']]) && $option['type'] == 'text') {
				$input[$option['name']] = sanitize_text_field($input[$option['name']]);
			}
		}
		foreach ($this->options['customization'] as $option) {
			if(isset($input[$option['name']]) && $option['type'] == 'text') {
				$input[$option['name']] = sanitize_text_field($input[$option['name']]);
			}
		}

		return $input;
	}

	public function selectSanitize($input)
	{
		foreach ($this->options['configuration'] as $option) {
			if(isset($input[$option['name']]) && $option['type'] == 'select') {
				$input[$option['name']] = sanitize_text_field($input[$option['name']]);
			}
		}
		foreach ($this->options['customization'] as $option) {
			if(isset($input[$option['name']]) && $option['type'] == 'select') {
				$input[$option['name']] = sanitize_text_field($input[$option['name']]);
			}
		}

		return $input;
	}

	public function checkboxSanitize($input)
	{
		foreach ($this->options['configuration'] as $option) {
			if(isset($input[$option['name']]) && $option['type'] == 'checkbox') {
				$input[$option['name']] = 1;
			}
		}
		foreach ($this->options['customization'] as $k => $val) {
			if(isset($input[$option['name']]) && $option['type'] == 'checkbox') {
				$output[$option['name']] = 1;
			}
		}

		return $input;
	}

	public function adminConfigurationManager()
	{
		echo '<p>&nbsp;</p>';
	}

	public function adminCustomizationManager()
	{
		echo '<p>Select how the plugin will appear on the website.</p>';
	}

	public function textField($args)
	{
		$name = $args['label_for'];
		$classes = $args['class'] ?? '';
		$optionName = $args['optionName'];
		$savedOptions = get_option($optionName);
		$value = esc_attr($savedOptions[$name] ?? '');
		$fieldNameAttr = $optionName . '[' . $name . ']';

		echo '<input 
				type="text"
				class="'.$classes.'"
				id="' . $name . '"
				name="' . $fieldNameAttr . '"
				value="'.$value.'"
				/>';
	}

	public function buttonField($args)
	{
		$classes = $args['elementClass'] ?? '';

		echo '<button 
				type="button"
				class="'.$classes.'"
				id="' . $args['label_for'] . '"
				>'.$args['title'].'</button>
				<span class="dashicons dashicons-warning has-tooltip" 
                    data-tooltip="'.($args['tooltip'] ?? '').'"></span>
				';
	}

	public function clearCacheButtonField($args)
	{
		$classes = $args['elementClass'] ?? '';

		echo '<button 
				type="button"
				class="'.$classes.'"
				id="' . $args['label_for'] . '"
				>'.$args['title'].'</button>
				<span class="dashicons dashicons-warning has-tooltip" 
                    data-tooltip="'.($args['tooltip'] ?? '').'"></span>
                <span class="cache-cleared" id="cacheCleared">'.(esc_html__('Cache cleared', SEDOX_VDB_TEXT_DOMAIN)).'</span>
                '    ;
	}

	public function mediaField($args)
	{
		$name = $args['label_for'];
		$optionName = $args['optionName'];
		$savedOptions = get_option($optionName);
		$value = esc_attr($savedOptions[$name] ?? '');
		$fieldNameAttr = $optionName . '[' . $name . ']';

		$defaultUrl = plugin_dir_url(dirname(dirname(__DIR__))) . 'assets/images/logo.png';
        if( intval( $value ) > 0 ) {
            $image = wp_get_attachment_image(
                $value,
                'thumbnail',
                false,
                ['id' => 'logo-preview-image', 'data-default' => $defaultUrl]
            );
        } else {
            $image = '<img id="logo-preview-image" src="'.$defaultUrl.'" />';
        }

		echo '<div class="box-field box-field--logo">
		<div class="logo-preview">
          '.$image.'      
        </div>
		<input type="hidden" name="'.$fieldNameAttr.'" id="logo_image_id" value="'.$value.'" class="regular-text" />
        <input type="button" class="button-primary" value="Select image" id="sedox_media_manager"/>
		<button type="button" class="btn btn-sm btn-primary mt-2 btnRevertDefault">
		    Reset to default
        </button>
        </div></div>';
	}

	public function colorField($args)
	{
		$name = $args['label_for'];
		$classes = $args['class'] ?? '';
		$optionName = $args['optionName'];
		$savedOptions = get_option($optionName);
		$value = esc_attr($savedOptions[$name] ?? $args['placeholder']);
		$fieldNameAttr = $optionName . '[' . $name . ']';

		echo '<div class="box-field box-field--color d-flex align-items-center"><input 
				type="color"
				class="wp-color-picker '.$classes.'"
				id="' . $name . '"
				name="' . $fieldNameAttr . '"
				value="'.$value.'"
				/><button type="button" class="btn btn-sm btn-primary ml-2 btnRevertDefault" data-default="'.$args['placeholder'].'">
				    Reset to default
                </button></div>';
	}

	public function checkboxField($args)
	{
		$name = $args['label_for'];
		$classes = $args['class'] ?? '';
		$optionName = $args['optionName'];
		$savedOptions = get_option($optionName);
		$checkbox = $savedOptions[$name] ?? false;
		$fieldNameAttr = $optionName . '[' . $name . ']';

		echo '<div class="' . $classes . '" >'.
		     '<input type="checkbox" name="' . $fieldNameAttr . '" id="' . $name . '"' .
		     '" value="1" ' . ($checkbox ? 'checked' : '') . '>'.
		     '<label for="' . $name . '"><div></div></label>'.
            ($args['extraInfo'] ? '<span class="extra-info">'.$args['extraInfo'].'</span>' : '') .
		     '</div>';
	}

	public function selectField($args)
	{
		$name = $args['label_for'];
		$optionName = $args['optionName'];
		$savedOptions = get_option($optionName);
		$selectedValue = $savedOptions[$name] ?? '';

		if($name == 'language' && !$args['options']) {
		    $html = '<p>No languages. Please enter API key</p>';
        } else {
            $fieldNameAttr = $optionName . '[' . $name . ']';
            $html = '<select name="' . $fieldNameAttr . '" id="' . $name . '">';

            foreach($args['options'] as $value => $label) {
                $html .= '<option value="'. $value .'" '.
                    ($value == $selectedValue ? 'selected' : '').'>'.
                    $label .'</option>';
            }
            $html .= '</select>';
            $html .= ($args['extraInfo'] ? '<div style="margin-top: 10px;color:#349bb8;max-width:600px">'.$args['extraInfo'].'</div>' : '');
        }

		echo $html;
	}

	public function hiddenField($args)
	{
        $name = $args['label_for'];
        $classes = $args['class'] ?? '';
        $optionName = $args['optionName'];
        $savedOptions = get_option($optionName);
        $value = esc_attr($savedOptions[$name] ?? '');
        $fieldNameAttr = $optionName . '[' . $name . ']';

        echo '<input 
				type="hidden"
				class="'.$classes.'"
				id="' . $name . '"
				name="' . $fieldNameAttr . '"
				value="'.$value.'"
				/>';
	}

	public function apiKeyField($args)
	{
		$name = $args['label_for'];
        $optionName = $args['optionName'];
        $savedOptions = get_option($optionName);
        $value = esc_attr($savedOptions[$name] ?? '');
        $fieldNameAttr = $optionName . '[' . $name . ']';

		echo '<div>
            <input 
				type="text"
				class=""
				id="' . $name . '"
				name="' . $fieldNameAttr . '"
				value="'.$value.'"
				placeholder="' . $args['hint'] . '"
				/>
                <a href="https://tuningfiles.com/vehicle-api/" target="_blank" class="more-info-popup" id="apiKeyWhere">Where can I find my API key?</a>
            </div>
            <div class="hint">'. $args['hint'] .'</div>
            <div id="apiKeyStatus" class="mt-2"></div>
        ';
	}
}
