<?php

defined('DIRECT') OR exit('No direct script access allowed');

/* ===> curl -X POST -d "Server=rrad3c50.domain.eu&Product=glibc" https://puppetversions.sec.domain.com/apiv1/puppetversions/exists/ */
$app->post("/exists/", function() use($app)
{
        //$info = array();

        $product = $app->request->post("Product");
        $server  = $app->request->post("Server");

        // check for required params
        if ( ((! $product ) || (strlen(trim($product)) <= 0)) || ((! $server ) || (strlen(trim($server)) <= 0)) )
        {
                json(422, 1, array("response" => "exists: Invalid params") );
        }


        if (!preg_match('/^[a-zA-Z0-9-\._]+$/',$product))
        {
                json(422, 1, array("response" => "exists: Product did not pass validation a-zA-Z0-9-._") );
        }

        if (!preg_match('/^[a-zA-Z0-9-\._]+$/',$server))
        {
                json(422, 1, array("response" => "exists: Server did not pass validation a-zA-Z0-9-._") );
        }

        $check = array( 'Product'=>"$product", 'Server'=>"$server" );
        $db = new smplPDO( "mysql:host=DATABASE_SERVER;dbname=puppetversions", "puppetversions", 'PASSWORD_HERE' );

        if( $db->exists( 'main', $check ) )
        {
                json(200, 1, array("response" => "exists: Entry exists", "rows" => "1") );
        } else {

                json(200, 0, array("response" => "exists: Entry does not exist", "rows" => "0") );
        }

});

?>
