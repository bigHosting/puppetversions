<?php

defined('DIRECT') OR exit('No direct script access allowed');

$app->post("/search(/)", function() use($app)
{
        $info = array();

        // set date to be inserted into Date field otherwise UPDATE statement will fail if all fields are the same as DB entries
        date_default_timezone_set('America/Toronto');
        $date = date('Y-m-d h:i:s', time());


        $req = array ('Product', 'Version');

        // get media type so we can tell the difference between x-www-form-urlencoded and json
        $mediaType = $app->request()->getMediaType() ?: 'text/html';
        switch ($mediaType)
        {

                case "application/json":
                        check_params_json($req); // see functions.php
                        // define vars for DB insert
                        $json     = json_decode($app->request->getbody(),true);
                        $product  = $json['Product'];
                        $version  = $json['Version'];
                        break;


                case "application/x-www-form-urlencoded":
                case "text/html":
                        check_params_text($req); // see functions.php
                        // define vars for DB insert
                        $product   = $app->request->post("Product");
                        $version   = $app->request->post("Version");
                        break;

                default:
                        json(200, 0, array("response" => "Invalid content-type: '$mediaType'") );
                        break;
        }



        // DATABASE SECTION
        $check = array( 'Version'=>"$version", 'Product'=>"$product" );
        // DB user/password settings
        include_once 'lib/DB.php';

        // count number of matches
        $total = $db->get_count( 'main', array( 'Product' => "$product", 'Version' => "$version") );
        if ( $total > 0 )
        {

                $results = $db->get_all( 'main', array('Product' => "$product", 'Version' => "$version") );

                foreach( $results as $row)
                {
                        $info[] = array( 'Id'      => $row['Id'],
                                         'Server'  => $row['Server'],
                                         'Product' => $row['Product'],
                                         'Version' => $row['Version'],
                                         'Release' => $row['Release'],
                                         'Date'    => $row['Date'],
                                         'Comment' => $row['Comment']
                                       );
                }
                json(200, 0, $info );
        } else {
                json(200, 1, array("response" => "search: $total rows returned for $product and $version") );
        }

});

?>
