<?php
/**
 * @package SedoxPerformanceCatalogPlugin
 */

namespace SedoxVDb\Controllers;


use Exception;
use SedoxVDb\Base\BaseController;
use SedoxVDb\Traits\ApiConnection;
use SedoxVDb\Util\Util;
use SedoxVDb\Util\Validator;
use SedoxWPCatalogue\Dependencies\Tuningfiles\Api\VehicleDatabaseApi;
use SedoxWPCatalogue\Dependencies\Tuningfiles\ApiException;
use SedoxWPCatalogue\Dependencies\Tuningfiles\Configuration;
use SedoxWPCatalogue\Dependencies\GuzzleHttp\Client as GuzzleCLient;

class DataController extends BaseController
{
    use ApiConnection;

    public function renderCatalogCode()
    {
        wp_enqueue_style('sedox_fonts');
        wp_enqueue_style('sedox_google_fonts');
        wp_enqueue_style('sedox_catalog_style');
        wp_enqueue_style('sedox-catalogue-charts-style');
        wp_enqueue_script('sedox-catalogue-charts-script');
        wp_enqueue_script('sedox-catalogue-front-script');

        $this->initApi();
        $configuration = get_option( SEDOX_VDB_MAIN_MENU_SLUG . '_configuration' );
        $customization = get_option( SEDOX_VDB_MAIN_MENU_SLUG . '_customization' );
        $debugOn = isset($configuration['debug']) && $configuration['debug'];
        if($this->api->connected === true) {
            $data = $this->api->getVehicleTypes();
            if(isset($data['error'])) {
                if($debugOn) {
                    return $data['error']['message'];
                }

                return '';
            }

            ob_start();
            require_once $this->pluginPath . 'views/content.php';

            $html = ob_get_clean();

            return $html;

        } else if ($debugOn) {
            return $this->api->error;
        }

        return '';
    }

    public function getApiData()
    {
        if(isset($_POST['endpoint'])) {
            $this->initApi();
            if($this->api->connected) {
                switch ($_POST['endpoint']) {
                    case 'manufacturers':
                        if(isset($_POST['vehicleType']) && Validator::validateId($_POST['vehicleType'])) {
                            $this->getManufacturers(sanitize_text_field($_POST['vehicleType']));
                        }
                        break;
                    case 'models':
                        if(isset($_POST['manufacturerId']) && Validator::validateId($_POST['manufacturerId'])) {
                            $this->getModels(sanitize_text_field($_POST['manufacturerId']));
                        }
                        break;
                    case 'generations':
                        if(isset($_POST['modelId']) && Validator::validateId($_POST['modelId'])) {
                            $this->getGenerations(sanitize_text_field($_POST['modelId']));
                        }
                        break;
                    case 'engines':
                        if(isset($_POST['generationId']) && Validator::validateId($_POST['generationId'])) {
                            $this->getEngines(sanitize_text_field($_POST['generationId']));
                        }
                        break;
                    case 'data':
                        if(isset($_POST['engineId']) && Validator::validateId($_POST['engineId'])) {
                            $this->getEngineData(sanitize_text_field($_POST['engineId']));
                        }
                        break;
                    case 'dataHtml':
                        if(isset($_POST['engineId'])
                            && isset($_POST['manufacturer'])
                            && isset($_POST['model'])
                            && isset($_POST['generation'])
                            && Validator::validateId($_POST['engineId'])
                        ) {
                            $engineId = sanitize_text_field($_POST['engineId']);
                            $generation = sanitize_text_field($_POST['generation']);
                            $manufacturer = sanitize_text_field($_POST['manufacturer']);
                            $model = sanitize_text_field($_POST['model']);
                            $this->getDetailsHtml($engineId, $generation, $manufacturer, $model);
                        }
                        break;
                }
            }

            // some endpoints dont require api->connected, e.g. checking api key
            switch ($_POST['endpoint']) {
                case 'checkApiKey':
                    if(strlen(trim($_POST['apiKey'])) == 0) {
                        echo json_encode([
                            'error' => [
                                'message' => 'No API KEY entered'
                            ]
                        ]);
                        wp_die();
                    }

                    $config = Configuration::getDefaultConfiguration()
                        ->setApiKey( 'x-api-key', $_POST['apiKey'] );

                    try {
                        $apiInstance = new VehicleDatabaseApi(
                            new GuzzleCLient(['timeout' => 6.0, 'connect_timeout' => 6.0]),
                            $config
                        );

                        $subscription = $apiInstance->vdbSubscription();

                        $subEnd = $subscription->getEndDate();

                        echo json_encode([
                            'name' => $subscription->getName(),
                            'active' => $subscription->getActive(),
                            'endDate' => $subEnd ? $subEnd->format('d.m.Y') : '-',
                        ]);
                        wp_die();

                    } catch(ApiException $e) {
                        echo $e->getResponseBody();
                        $this->logger->error($e->getMessage(), $e->getTrace());
                        wp_die();
                    } catch (Exception $e) {
                        echo json_encode([
                            'error' => json_decode($e->getMessage())
                        ]);
                        $this->logger->error($e->getMessage(), $e->getTrace());
                        wp_die();
                    }
                    break;
            }

        } else {
            echo 'Missing endpoint field';
        }
    }

    public function getManufacturers($vehicleType)
    {
        if($vehicleType) {
            $manufacturers = $this->api->getManufacturers($vehicleType);
            echo json_encode($manufacturers);
        }

        wp_die();
    }

    public function getModels($manufacturerId)
    {
        if($manufacturerId) {
            $models = $this->api->getModels($manufacturerId);
            echo json_encode($models);
        }

        wp_die();
    }

    public function getGenerations($modelId)
    {
        if($modelId) {
            $models = $this->api->getGenerations($modelId);
            echo json_encode($models);
        }

        wp_die();
    }

    public function getEngines($generationId)
    {
        if($generationId) {
            $engines = $this->api->getEngines($generationId);
            echo json_encode($engines);
        }

        wp_die();
    }

    public function getEngineData($engineId)
    {
        if($engineId) {
            $engineData = $this->api->getEngineData($engineId);
            echo json_encode($engineData);
        }

        wp_die();
    }

    public function getDetailsHtml($engineId, $generation, $manufacturer, $model)
    {
        if($engineId) {
            $engineData = $this->api->getEngineData($engineId);
            $generation = json_decode(stripslashes($generation), true);
            $generationData = $this->api->getGenerationData($generation['id']);
            $data = [
                'manufacturer' => json_decode(stripcslashes($manufacturer), true),
                'model' => json_decode(stripcslashes($model), true),
                'generation' => $generationData,
                'engine' => $engineData,
            ];

            $pluginLocale = Util::getPluginLocale();
            $originalLocale = determine_locale();
            switch_to_locale($pluginLocale['wp_code']);

            ob_start();
            require_once $this->pluginPath . 'views/partials/car_content.php';

            $html = ob_get_clean();

            header('Content-type:application/json;charset=utf-8');
            echo json_encode([
                'data' => $data,
                'html' => $html
            ]);

            switch_to_locale($originalLocale);
        }

        wp_die();
    }

    public function clearCache()
    {
        Util::clearCache();

        echo json_encode([
            'message' => 'Cache cleared',
        ]);
        wp_die();
    }

}
