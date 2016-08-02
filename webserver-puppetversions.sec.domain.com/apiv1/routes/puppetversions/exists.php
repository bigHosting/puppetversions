<?php

defined('DIRECT') OR exit('No direct script access allowed');
//regular $ curl -X POST https://puppetversions.sec.domain.com/apiv1/puppetversions/exists/ -d 'Server=web3c50.domain.com&Product=glibc'
//json    $ curl -X POST https://puppetversions.sec.domain.com/apiv1/puppetversions/exists/ -d '{"Server":"web3c50.domain.com", "Product":"glibc"}' -H "Content-Type: application/json"
$app->post("/exists(/)", function() use($app)
{

        // required parameters
        $req = array ('Server', 'Product' );

        // get media type so we can tell the difference between x-www-form-urlencoded and json
        $mediaType = $app->request()->getMediaType() ?: 'text/html';
        switch ($mediaType)
        {

                case "application/json":
                        check_params_json($req); // see functions.php
                        // define vars for DB insert
                        $json   = json_decode($app->request->getbody(),true);
                        $server  = $json['Server'];
                        $product = $json['Product'];
                        break;


                case "application/x-www-form-urlencoded":
                case "text/html":
                        check_params_text($req); // see functions.php
                        // define vars for DB insert
                        $server   = $app->request->post("Server");
                        $product  = $app->request->post("Product");
                        break;

                default:
                        json(200, 0, array("response" => "Invalid content-type: '$mediaType'") );
                        break;
        }



        // DB SECTION
        $check = array( 'Product'=>"$product", 'Server'=>"$server" );

        // DB user/password settings
        include_once 'lib/DB.php';

        if( $db->exists( 'main', $check ) )
        {
                json(200, 0, array("response" => "exists: Entry exists", "rows" => "1") );
        } else {

                json(200, 1, array("response" => "exists: Entry does not exist", "rows" => "0") );
        }

});

?>
