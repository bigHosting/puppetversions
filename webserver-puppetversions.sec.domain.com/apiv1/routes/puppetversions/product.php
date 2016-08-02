<?php

defined('DIRECT') OR exit('No direct script access allowed');

$app->post("/product(/)", function() use($app)
{

        $req = array ( 'Product');

        // get media type so we can tell the difference between x-www-form-urlencoded and json
        $mediaType = $app->request()->getMediaType() ?: 'text/html';
        switch ($mediaType)
        {

                case "application/json":
                        check_params_json($req); // see functions.php
                        // define vars for DB insert
                        $json   = json_decode($app->request->getbody(),true);
                        $product = $json['Product'];
                        break;


                case "application/x-www-form-urlencoded":
                case "text/html":
                        check_params_text($req); // see functions.php
                        // define vars for DB insert
                        $product  = $app->request->post("Product");
                        break;

                default:
                        json(200, 0, array("response" => "Invalid content-type: '$mediaType'") );
                        break;
        }


        $info = array();

        // DB user/password settings
        include_once 'lib/DB.php';

        // count number of matches
        $total = $db->get_count( 'main', array( 'Product' => "$product") );
        if ( $total > 0 )
        {

                //$results = $db->get_all( 'main', array('Product' => "$product"), array( 'Id', 'Server', 'Product', 'Version', 'Release', 'Date', 'Comment' ) );
                $results = $db->get_all( 'main', array('Product' => "$product") );

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
                json(404, 1, array("response" => "product: 0 rows returned for $product") );
        }
});

?>