



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

        // cache section
        $cache = 1;
        $cachefile = "cache/" . pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME) . "_" . implode("_", array_keys($check)) . "_" . implode("_", $check) . '.json';
        if ( !empty ($cache) && strlen($cache) && file_exists($cachefile) ) { cache_read ($cachefile); }


        // DB user/password settings
        include_once 'lib/DB.php';

        if( $db->exists( 'main', $check ) )
        {
                $message = array("response" => "exists: Entry exists", "rows" => "1");
                if ( !empty ($cache) && strlen($cache) ) { cache_write ( $cachefile, 200, 0, $message ); } // cache section
                json(200, 0, $message );

        } else {
                $message = array("response" => "exists: Entry does not exist", "rows" => "0");
                if ( !empty ($cache) && strlen($cache) ) { cache_write ( $cachefile, 200, 1, $message ); } // cache section
                json(200, 1, $message );
        }

});

?>
