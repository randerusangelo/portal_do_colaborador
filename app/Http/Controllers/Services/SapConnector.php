<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use SoapClient;

class SapConnector extends Controller
{
    protected $wsdl;

    function __construct( $pEnvWSDL )
    {
        $this->wsdl = $pEnvWSDL;
    }

    public function conn()
    {
        $opts = array(
            'http' => array(
                'user_agent' => 'PHPSoapClient'
            )
        );
        $context = stream_context_create($opts);

        $options = [
            "login"          => config('sap.auth.login'),
            "password"       => config('sap.auth.password'),
            "features"       => SOAP_SINGLE_ELEMENT_ARRAYS,
            "stream_context" => $context,
            "cache_wsdl"     => WSDL_CACHE_NONE
        ];

        $client = new SoapClient( $this->wsdl, $options );
        
        return $client;   
    }
}
