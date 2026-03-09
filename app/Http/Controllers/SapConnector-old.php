<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use SoapClient;

class SapConnector extends Controller
{
    protected $wsdl;

    public function __construct(){
    }

    public function conn()
    {
        $wsdl = config('sap.sap_conn_wsdl_hrp');

        $options = [
            "login"    => config('sap.sap_conn_user'),
            "password" => config('sap.sap_conn_password'),
            "features" => SOAP_SINGLE_ELEMENT_ARRAYS
        ];

        $client = new SoapClient( $wsdl, $options );

        return $client;
    }

}
