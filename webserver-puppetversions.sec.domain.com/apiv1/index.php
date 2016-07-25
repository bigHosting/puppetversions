<?php

define ("DIRECT",1);

defined('DIRECT') OR exit('No direct script access allowed');

// optional, can be removed
if (strcmp(PHP_SAPI, 'cli') === 0)
{
        die('API should not be run from CLI.' . PHP_EOL);
}

// optional, can be removed
// set timezone
date_default_timezone_set('America/Toronto');


// optional, can be removed
// set security controls
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: *");



require 'vendor/autoload.php';

$app = new \Slim\Slim();

// logging
$app->container->singleton('log', function () {
        $log = new \Monolog\Logger('API');
        $log->pushHandler(new \Monolog\Handler\StreamHandler('logs/'.date('Y-m-d').'.log', \Monolog\Logger::DEBUG));
        return $log;
});


require_once dirname(__FILE__).'/lib/smplPDO.php';     // php mysql pdo driver class
require_once dirname(__FILE__).'/lib/functions.php';   // php common functions file

/* ===> curl https://puppetversions.sec.domain.com/apiv1/  */
$app->get('/', function() use($app)
{
        // http status = 200, error = 0, response = API
        json(200, 0, array("response" => "API"));
});

/* ===> curl https://puppetversions.sec.domain.com/apiv1/echo/abcd  */
$app->get('/echo/:name', function($name)
{
        if(preg_match("/^[a-zA-Z0-9-\._]+$/", $name) ) 
        {
                // http status = 200, error = 0, response = $name
                json(200, 0, array("response" => $name) );
        }
        // if we got here it means we got an error
        json(403, 1, array("response" => "Invalid chars detected") );
});

/* ===> curl https://puppetversions.sec.domain.com/apiv1/denied  */
$app->get('/denied', function() use ($app)
{
        // http status = 403, error = 1, response = denied
        json(403, 1, array("response" => "denied") );
});

/* ===> curl  https://puppetversions.sec.domain.com/apiv1/help  */
$app->get('/help', function() use ($app)
{
        // http status = 403, error = 1, response = denied
        json(200, 0, array("response"               => "help",
                           "/apiv1/exists/"         => "curl -X POST -d 'Server=rrad3c50.domain.eu&Product=glibc' https://puppetversions.sec.domain.com/apiv1/puppetversions/exists/",
                           "/apiv1/insertORupdate/" => "curl -X POST -d 'Server=api2c45.domain.eu&Product=glibc&Version=2.12&Release=1.166.el6_7.7&Comment=AutomaticInseretTest' https://puppetversions.sec.domain.com/apiv1/puppetversions/insertORupdate/",
                           "/apiv1/insert/"         => "curl -X POST -d 'Server=rrad4c50.domain.eu&Product=glibc&Version=2.12&Release=1.166.el6_7.7&Comment=AutomaticInsert' https://puppetversions.sec.domain.com/apiv1/puppetversions/insert/",
                           "/apiv1/select/"         => "curl -X GET  https://puppetversions.sec.domain.com/apiv1/puppetversions/select/2"
                          )
        );
});


// billing section
$app->group('/puppetversions', function () use ($app)
{
        // whitelist IPs to be able to access the API
        $allowed_ips = array("127.0.0.1", "10.70.17.1", "10.70.17.2", "10.70.17.3" );
        if (!in_array ($app->request->getIP(), $allowed_ips))
        {
                json(403, 1, array("response" => "Authorization denied") );
        }

        include_once 'routes/puppetversions.php';

});


/* ===> curl https://puppetversions.sec.domain.com/apiv1/random_url_that_does_not_exist  */
$app->notFound(function ()
{
        json(404, 1, array("response" => "The route you are requesting could not be found. Check /help to ensure your request is spelled correctly.") );
});

$app->error(function ()
{
        json(401, 1, array("response" => "Bad request") );
});


$app->run();

?>

