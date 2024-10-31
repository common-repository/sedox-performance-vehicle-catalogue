<?php
/**
 * @package SedoxPerformanceCatalogPlugin
 */

namespace SedoxVDb\Util;


use SedoxWPCatalogue\Dependencies\Tuningfiles\Model\VdbEngineInfo;

class Util
{
	public static function kw2hp($powerInKw)
	{
		return round($powerInKw * 1.34102);
	}

	public static function nm2ftlb($torqueInNm)
	{
		return round($torqueInNm * 0.7375621493);
	}

	public static function hp2kw($powerInHP)
	{
		return round($powerInHP * 0.745699872);
	}

	public static function ftlb2nm($torqueInFtlb)
	{
		return round($torqueInFtlb / 0.7375621493);
	}

	public  static function getPreselected()
    {
        $hash=parse_url($_SERVER["REQUEST_URI"], PHP_URL_FRAGMENT);
        echo '<pre>'; var_dump($hash); die();
    }

    /**
     * @param VdbEngineInfo $engine
     * @return mixed
     */
    public static function engineNameWithKW($engine)
    {
        return str_replace(
            $engine->getPower().'hp',
            $engine->getPower().'hp' . ' (' . Util::hp2kw($engine->getPower()) . 'kW)',
            $engine->getName()
        );
    }

    public static function clearCache()
    {
        global $wpdb;
        $sql = "SELECT `option_name` AS `name`, `option_value` AS `value`
            FROM  $wpdb->options
            WHERE `option_name` LIKE '%transient_%'
            ORDER BY `option_name`";

        $results = $wpdb->get_results( $sql );
        $transients = array();

        foreach ( $results as $result )
        {
            if ( 0 === strpos( $result->name, '_site_transient_' ) ) {
                if ( 0 === strpos( $result->name, '_site_transient_timeout_') )
                    $transients['site_transient_timeout'][ $result->name ] = $result->value;
                else
                    $transients['site_transient'][ $result->name ] = maybe_unserialize( $result->value );
            } else {
                if ( 0 === strpos( $result->name, '_transient_timeout_') )
                    $transients['transient_timeout'][ $result->name ] = $result->value;
                else
                    $transients['transient'][ $result->name ] = maybe_unserialize( $result->value );
            }
        }

        foreach($transients['transient'] as $k => $transient) {
            if( 0 === strpos($k, '_transient_sedox')) {
                delete_transient(substr($k, 11));
            }
        }
    }

    public static function getPluginLocale()
    {
        $customizationOptions = get_option(SEDOX_VDB_MAIN_MENU_SLUG.'_customization');
        if (isset($customizationOptions['language']) && $customizationOptions['language']) {
            $parts = explode('|', $customizationOptions['language']);
            return [
                'code' => $parts[0],
                'wp_code' => $parts[1],
            ];
        }

        return [
            'code' => get_locale(),
            'wp_code' => get_locale(),
        ];
    }
}
