<?php

//// http://stackoverflow.com/questions/255312/how-to-get-a-variable-name-as-a-string-in-php
//function print_var_name($var) {
//        foreach($GLOBALS as $var_name => $value) {
//                if ($value === $var) {
//                        return $var_name;
//                }
//        }
//
//        return false;
//}

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

// simple cache implementation
function cache_read ($file = NULL)
{
        // initialize Slim
        $app = \Slim\Slim::getInstance();

        if ( !$file )
        {
                json(200, 1, array ( "response" => "cache_read needs a file as argument") );
        }

        $cachetime = 600; // Seconds to cache files for

        //print " Checking if file " . $file . " exists\n";
        //exit;

        // read the file only if : file exists, mod time is less than $cachetime and file is readable
        if ( (file_exists($file)) && (time() - filemtime($file) < $cachetime) && (is_readable($file)) )
        {
                $string = file_get_contents ($file);
                echo $string;
                //@readfile($file);
        }

        // terminate connection after cache read
        $app->stop();
        die();
}

function cache_write($file = NULL, $status_code = 200, $app_code = 0, $data = array())
{
        // initialize Slim
        $app = \Slim\Slim::getInstance();

        if ( !$file )
        {
                json(200, 1, array ( "response" => "cache_write needs a file as argument") );
        }

        // data to be written to disk
        $buffer = json_encode(array( 'httpstatus' => "$status_code", 'error' => "$app_code", 'data' => $data ),JSON_PRETTY_PRINT);

        if ( file_exists($file) )
        {
                // can we delete existing file ?
                if ( !unlink($file) )
                {
                        json(200, 1, array ( "response" => "cache_write cannot delete file '$file'") );
                }
        }

                //can we create new file ?
        if ( !file_put_contents ($file, $buffer) )
        {
                json(200, 1, array ( "response" => "cache_write cannot wrote to file '$file'") );
        }

}



?>

