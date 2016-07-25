<?php


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

?>

