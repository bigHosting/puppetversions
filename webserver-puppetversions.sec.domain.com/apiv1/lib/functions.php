<?php

// http://stackoverflow.com/questions/255312/how-to-get-a-variable-name-as-a-string-in-php
function print_var_name($var) {
        foreach($GLOBALS as $var_name => $value) {
                if ($value === $var) {
                        return $var_name;
                }
        }

        return false;
}

function check_params_text ($req = array() )
{
        // initialize Slim
        $app = \Slim\Slim::getInstance();

        if ( !is_array ($req))
        {
                json(422, 1, array("response" =>  "check_param_text: expected param is array()") );
        }

        if ( sizeof ($req,0) == 0 )
        {
                json(422, 1, array("response" =>  "check_param_text: array is empty") );
        }

        // check for required params
        foreach ( $req as $item)
        {
                if ( (! $app->request->post($item) ) || (strlen(trim($app->request->post($item))) <= 0))
                {
                            json(422, 1, array("response" =>  "$item - invalid param") );
                }
        }

        // check for proper format: a-z A-Z 0-9 _ - . ( no spaces )
        foreach ( $req as $item)
        {
                if (!preg_match('/^[a-zA-Z0-9-\._]+$/',$app->request->post($item)))
                {
                        json(200, 1, array("response" => "$item did not pass validation a-zA-Z0-9-._") );
                }
        }

}

function check_params_json ($req = array() )
{
        // initialize Slim
        $app = \Slim\Slim::getInstance();

        if ( !is_array ($req))
        {
                json(422, 1, array("response" =>  "check_param_text: expected param is array()") );
        }

        if ( sizeof ($req,0) == 0 )
        {
                json(422, 1, array("response" =>  "check_param_text: array is empty") );
        }

        $params = $app->request->getbody();
        if ( (!$params) || (empty($params)) )
        {
                json(200, 0, array("response" => "Invalid POST") );
        }

        $json   = json_decode($params,true);

        // check for required params
        foreach ( $req as $item)
        {
                if ( !isset($json[$item]) )
                {
                        json(422, 1, array("response" =>  "$item - invalid params") );
                }
        }

        // check for for params format
        foreach ( $req as $item)
        {

                // check for proper format: a-z A-Z 0-9 _ - . ( no spaces )
                if (!preg_match('/^[a-zA-Z0-9-\._]+$/',$json[$item]))
                {
                        json(200, 1, array("response" => "$item did not pass validation a-zA-Z0-9-._") );
                }
        }
}


function json($status_code = 200, $app_code = 0, $data = array())
{
        // initialize Slim
        $app = \Slim\Slim::getInstance();

        // log answer
        $app->log->info("Method [".$app->request->getMethod() ."]". " from IP " .$app->request->getIP() . " for ". $_SERVER['REQUEST_URI'] . " w http status code " . $status_code . " and app status code " . $app_code . " Body [" . $app->request->getBody() . "]" );

        // Http response code
        $app->status($status_code);

        // setting response content type to json
        $app->contentType('application/json');

        //if ($data)
        //{
                  echo json_encode(array( 'httpstatus' => "$status_code", 'error' => "$app_code", 'data' => $data ),JSON_PRETTY_PRINT);
        //} else {
        //        echo json_encode(array( 'httpstatus' => "$status_code", 'message' => $message ),JSON_PRETTY_PRINT);
        //}
        $app->stop();
        die();
}


/*
function echo_json( $response, $status_code ) {
        $app = \Slim\Slim::getInstance();

        // log answer
        $app->log->info($app->request->getMethod() . " from IP " .$app->request->getIP() . " for ". $_SERVER['REQUEST_URI'] . " w status code " . $status_code);

        // Http response code
        $app->status($status_code);

        // setting response content type to json
        $app->contentType('application/json');
        echo (json_encode($response, JSON_PRETTY_PRINT));
}

function echo_json_exit( $response, $status_code ) {
        $app = \Slim\Slim::getInstance();

        // log answer
        $app->log->info($app->request->getMethod() . " from IP " .$app->request->getIP() . " for ". $_SERVER['REQUEST_URI'] . " w status code " . $status_code);

        // Http response code
        $app->status($status_code);

        // setting response content type to json
        $app->contentType('application/json');
        echo (json_encode($response, JSON_PRETTY_PRINT));
        $app->stop();
        die();
}


function get_api_key($key)
{

        $req_key = array( 'apiKey'=>"$key" );

        $db = new smplPDO( "mysql:host=127.0.0.1;dbname=vuln", "root", 'PASSWORD_HERE' );

        if( $db->exists( 'api_keys', $req_key ) )
        {
                return TRUE;
        } else {
                return FALSE;
        }
}
*/

?>

