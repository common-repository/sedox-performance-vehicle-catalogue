<?php
/**
 * VdbEngineInfoTest
 *
 * PHP version 5
 *
 * @category Class
 * @package  SedoxWPCatalogue\Dependencies\Tuningfiles
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */

/**
 * TuningFiles API
 *
 * Welcome to the TuningFiles API documentation.  # Language  All API methods accept language parameter, which can be set via the `X-LANG` custom header. Content of the header should be the code of the language you are requesting.  Available languages:    * English (`en`)   * Chinese traditional (`zh-hant`)   * Chinese simplified (`zh-hans`)   * Russian (`ru`)   * Norwegian Bokmål (`nb`)   * Latvian (`lv`)   * Lithuanian (`lt`)   * Croatian (`hr`)   * Spanish (`es`)  Set language to English: ``` curl -X GET \"https://api.tuningfiles.com/method\" -H \"x-lang: en\" ```  # Errors If there is an error, API will return appropriate error code and message like so:  ```json {   \"error\": {     \"code\": 404,     \"message\": \"Resource doesn't exist\"   } } ```  HTTP status code will be the same as the error code. In the case above, returned http status code will be 404.   Failed API authentication: ```json {   \"error\": {     \"code\": 403,     \"message\": \"Invalid API key\"   } } ```  API Key doesn't have enough permissions to access requested resource (some API methods require a paid subscription): ```json {   \"error\": {     \"code\": 401,     \"message\": \"This API key does not have enough permissions\"   } } ```  Not found: ```json {   \"error\": {     \"code\": 404,     \"message\": \"Resource doesn't exist\"   } } ```  Bad request: ```json {   \"error\": {     \"code\": 400,     \"message\": \"Bad request / Wrong parameters\"   } } ```  Server error: ```json {   \"error\": {     \"code\": 500,     \"message\": \"Internal server error\"   } } ``` # Rate limits To protect from flood and abuse, this API implements rate limiting. Currently, only requests made to the Vehicle Database REST API are limited. All the requests made to the File Tuning REST API are not rate-limited (this may change in the future).  **Vehicle Database REST API limits:**   - All methods: *3600 calls/hour*  **File Tuning REST API limits:**   - All methods: *no limits*  **Support REST API limits:**   - All methods: *3600 calls/hour*    Limits are per API Key and are reset every hour. If your app hits those limits, API will return error code `429` with message `This API key has reached the time limit for this method`        # Webhooks You can use webhook to receive notifications about particular events. When you enable webhook in your [API settings page](https://app.tuningfiles.com/api), you can let your app execute code immediately after specific events occur, instead of having to make API calls periodically. For example, you can rely on webhooks to trigger an action in your app when project is created, it's status is updated or when file is purchased.  Webhook notification is using `POST` as http request method and contains a JSON payload, and HTTP headers that provide context.      For example, when project is created, webhook will contains the following headers:    **X-TF-EVENT**: project_create     **X-TF-HMAC-SHA256**: aV95gkmXR75CFvdeIn9DwOmpTCBndDqo/70uJWiYtaY=    and JSON body:   ```   {     \"id\": 146356,     \"uuid\": \"07b2402b-00cd-4d61-9bc4-13b85a1849fb\",     \"name\": \"Audi A3 8P 2.0 TDI 136hp 320Nm 2017\",      \"status\": \"Waiting\",     \"status_code\": 0,     ...     \"files\": [       {         \"id\": 246810,         \"uuid\": \"3dbb36a1-d50f-4327-8100-83a8c2f1b869\",         \"project\": 146356,         \"type\": \"Original\",         ...       }     ]   }   ```    ## Headers Webhook notifications are using following custom http headers:      `X-TF-EVENT`: Event which triggered this webhook     `X-TF-HMAC-SHA256`: Webhook verification hash  ## Events Each webhook will contain `X-TF-EVENT` header. This header represents the event which triggered this webhook.  File Tuning REST API event types:   - `project_create` - Project is created. JSON body will contain the full project and it's file(s) object(s) as [follows.](#operation/project_view)    - `project_update` - Project is updated. JSON body will contain the full project and it's file(s) object(s) as [follows.](#operation/project_view)   - `file_purchase` - File is purchased. JSON body will contain the full project and it's file(s) object(s) as [follows.](#operation/project_view)    Support REST API event types:   - `ticket_created` - Support request (ticket) was created. JSON body will contain the full ticket and it's messages metadata as [follows.](#operation/support_view_ticket)   - `ticket_opened` - Support request (ticket) status was set to \"Opened\". JSON body will contain the full ticket and it's messages metadata as [follows.](#operation/support_view_ticket)   - `ticket_reopened` - Support request (ticket) status was set to \"Opened\". This event is triggered when a \"closed\" ticket has been re-opened again. JSON body will contain the full ticket and it's messages metadata as [follows.](#operation/support_view_ticket)   - `ticket_answered` - Support request (ticket) status was set to \"Answered\". JSON body will contain the full ticket and it's messages metadata as [follows.](#operation/support_view_ticket)   - `ticket_closed` - Support request (ticket) status was set to \"Closed\". JSON body will contain the full ticket and it's messages metadata as [follows.](#operation/support_view_ticket)   - `message_created` - Triggered when a new message (reply) is added into the ticket. JSON body will contain the full ticket and it's messages metadata as [follows.](#operation/support_view_ticket)    ## Verification To allow a client to verify a webhook message has in fact come from TuningFiles, an `X-TF-HMAC-SHA256` header is included in each webhook POST message. The contents of this header is the Base64 encoded output of the HMAC SHA256 encoding of the JSON body of the message, using your API Secret as the encryption key.   Following psuedo PHP example shows how we generate the `X-TF-HMAC-SHA256` value: ```php   base64_encode(hash_hmac('sha256', $webhook_json, $your_api_secret, true)); ```  To verify the authenticity of the webhook message, you should calculate this value yourself and verify it equals the value contained in the header. # SDKs TuningFiles offers a PHP SDK to help interact with the API.  However, no SDK is required to use the API. ## PHP SDK [PHP SDK](https://github.com/sedox/tuningfiles-php-sdk) is hosted on [Github](https://github.com/sedox/tuningfiles-php-sdk). For all PHP SDK examples provided in these docs you will need to configure the `$apiInstance`. You may do it like this:   - For Vehicle Database API:      ```php       $apiInstance = new SedoxWPCatalogue\Dependencies\Tuningfiles\\Api\\VehicleDatabaseApi(         new SedoxWPCatalogue\Dependencies\GuzzleHttp\\Client(['timeout' => 6.0, 'connect_timeout' => 6.0]),         SedoxWPCatalogue\Dependencies\Tuningfiles\\Configuration::getDefaultConfiguration()->setApiKey('x-api-key', 'YOUR_API_KEY')       );     ```    - For Tuning API:          ```php       $apiInstance = new SedoxWPCatalogue\Dependencies\Tuningfiles\\Api\\TuningApi(         new SedoxWPCatalogue\Dependencies\GuzzleHttp\\Client(['timeout' => 6.0, 'connect_timeout' => 6.0]),         SedoxWPCatalogue\Dependencies\Tuningfiles\\Configuration::getDefaultConfiguration()->setApiKey('x-api-key', 'YOUR_API_KEY')       );     ```
 *
 * OpenAPI spec version: 1.2.1
 * Contact: support@tuningfiles.com
 * Generated by: https://github.com/swagger-api/swagger-codegen.git
 * Swagger Codegen version: 3.0.33
 */
/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Please update the test case below to test the model.
 */

namespace SedoxWPCatalogue\Dependencies\Tuningfiles;

/**
 * VdbEngineInfoTest Class Doc Comment
 *
 * @category    Class
 * @description VdbEngineInfo
 * @package     SedoxWPCatalogue\Dependencies\Tuningfiles
 * @author      Swagger Codegen team
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class VdbEngineInfoTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Setup before running any test case
     */
    public static function setUpBeforeClass()
    {
    }

    /**
     * Setup before running each test case
     */
    public function setUp()
    {
    }

    /**
     * Clean up after running each test case
     */
    public function tearDown()
    {
    }

    /**
     * Clean up after running all test cases
     */
    public static function tearDownAfterClass()
    {
    }

    /**
     * Test "VdbEngineInfo"
     */
    public function testVdbEngineInfo()
    {
    }

    /**
     * Test attribute "id"
     */
    public function testPropertyId()
    {
    }

    /**
     * Test attribute "name"
     */
    public function testPropertyName()
    {
    }

    /**
     * Test attribute "slug"
     */
    public function testPropertySlug()
    {
    }

    /**
     * Test attribute "type"
     */
    public function testPropertyType()
    {
    }

    /**
     * Test attribute "capacity"
     */
    public function testPropertyCapacity()
    {
    }

    /**
     * Test attribute "power"
     */
    public function testPropertyPower()
    {
    }

    /**
     * Test attribute "torque"
     */
    public function testPropertyTorque()
    {
    }

    /**
     * Test attribute "cylinders"
     */
    public function testPropertyCylinders()
    {
    }

    /**
     * Test attribute "year"
     */
    public function testPropertyYear()
    {
    }

    /**
     * Test attribute "ecu"
     */
    public function testPropertyEcu()
    {
    }

    /**
     * Test attribute "tcu"
     */
    public function testPropertyTcu()
    {
    }

    /**
     * Test attribute "hp_values"
     */
    public function testPropertyHpValues()
    {
    }

    /**
     * Test attribute "nm_values"
     */
    public function testPropertyNmValues()
    {
    }

    /**
     * Test attribute "rpm_values"
     */
    public function testPropertyRpmValues()
    {
    }

    /**
     * Test attribute "engine_code"
     */
    public function testPropertyEngineCode()
    {
    }

    /**
     * Test attribute "fuel_name"
     */
    public function testPropertyFuelName()
    {
    }

    /**
     * Test attribute "options"
     */
    public function testPropertyOptions()
    {
    }

    /**
     * Test attribute "remap_stages"
     */
    public function testPropertyRemapStages()
    {
    }

    /**
     * Test attribute "read_tools"
     */
    public function testPropertyReadTools()
    {
    }

    /**
     * Test attribute "read_methods"
     */
    public function testPropertyReadMethods()
    {
    }
}
