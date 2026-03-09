<?php

return [

    'auth' => [
        'login'    => env('SAP_CONN_USER'),
        'password' => env('SAP_CONN_PASSWORD'),
    ],

    'wsdl' => [
        'hr' => [
            'zws_inf_rend'   => env('ZWS_INF_REND')
        ]
    ],

    'api' => [
        'hr' => [
            'analise_credito' => env('SAP_URL_ANCREDITO'),
            'aptidao'         => env('SAP_URL_APTIDAO'),
            'toxicologico'    => env('SAP_URL_TOX')
        ]
    ]

    // 'sap_conn_wsdl_dev' => env('SAP_CONN_WSDL_DEV'),
    // 'sap_conn_wsdl_qas' => env('SAP_CONN_WSDL_QAS'),
    // 'sap_conn_wsdl_hrp' => env('SAP_CONN_WSDL_HRP')

];