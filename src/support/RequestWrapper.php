<?php

declare(strict_types=1);

namespace TommyDo;

use ErrorException;

/**
 * This class is just a helper for the example not a main service
 */
class RequestWrapper
{
    /**
     * @url: Required | The API url
     * @token: Optional | The API access token
     * @endpoint: Optional | The API endpoint
     */
    private $api_config;

    /**
     * The initialize function
     */
    public function __construct($api_config = [])
    {
        if(!isset($api_config) || empty($api_config)) {
            throw new ErrorException("API config not found", 1);
        }

        // Check the params before request
        if(!isset($api_config['url']) || (isset($api_config['url']) && empty($api_config['url']))) {
            throw new ErrorException("API url not found", 1);
        }

        $this->api_config = $api_config;
    }

    /**
     * Handle a specific request.
     */
    public function send()
    {
        $request_url = $this->api_config['url'];

        if(null != $this->api_config['endpoint'] && !empty($this->api_config['endpoint'])) {
            // Check the endpoint should start with a "/"
            if($this->api_config['endpoint'][0] !== '/') {
                throw new ErrorException("Invalid Endpoint", 1);
            }
            $request_url .= $this->api_config['endpoint'];
        }
        $res_data = $this->doRequest($request_url);

        return $res_data;
    }

    /**
     * Do CURL request in common
     * Don't put anything specific into this method
     * 
     */
    private function doRequest($url, $method = "GET", $data = [])
    {
        if (!extension_loaded('curl')) {
            throw new ErrorException('Extension CURL is not enabled');
        }

        $ch = curl_init();
        $curlConfig = [
            CURLOPT_URL            => $url,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_POSTFIELDS     => http_build_query($data)
        ];
        curl_setopt_array($ch, $curlConfig);

        $res = curl_exec($ch);

        $res_info = curl_getinfo($ch);
        if(isset($res_info['http_code'])) {
            switch ($res_info['http_code']) {
                case 404:
                    throw new ErrorException('API\'s URL not found or incorrect');
                    break;
                case 405:
                    throw new ErrorException('Request method invalid');
                    break;

            }
            
        }

        if($res === false) {
            $error_msg = curl_error($ch);
            throw new ErrorException(stripslashes($error_msg));
        }
        curl_close($ch);

        return $res;
    }

    /**
     * Dump function to print and beautify output to browser or terminal
     * 
     * @value: Mix | The value that need to show
     * @dump: 0/1 | Default 0 | 0: just only print value, 1: dump value with data type
     * @exit: 0/1 | Default 1 | Stop the rest of script after this function. Set to 0 for multiple dump til want to stop.
     * 
     * @return void
     */
    function pr($value, $dump = 0, $exit = 1): void
    {
        echo ('<pre><br>');
        if ($dump == 1) {
            var_dump($value);
        } else {
            print_r($value);
        }
        echo ('</pre></br>');
        if ($exit) {
            echo ('++++++++++++ ' . date('Y-m-d h:m:s', strtotime('now')) . ' +++++++++++++');
            echo ('<br>');
            exit("++++++++++++++++ END DUMP ++++++++++++++++");
        }
    }
}
