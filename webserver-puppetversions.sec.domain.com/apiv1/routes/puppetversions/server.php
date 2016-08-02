<?php

defined('DIRECT') OR exit('No direct script access allowed');

$app->get("/server/:server(/)", function($server) use($app)
{

        if (!preg_match('/^[a-zA-Z0-9\.-_]+$/',$server))
        {
                json(404, 1, array("response" => "server: Invalid chars in server name") );
        }

        $info = array();

        // DB user/password settings
        include_once 'lib/DB.php';

        // count number of matches
        $total = $db->get_count( 'main', array( 'Server' => "$server") );
        if ( $total > 0 )
        {

                //$results = $db->get_all( 'main', array('Product' => "$product"), array( 'Id', 'Server', 'Product', 'Version', 'Release', 'Date', 'Comment' ) );
                $results = $db->get_all( 'main', array('Server' => "$server") );

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
                json(404, 1, array("response" => "server: 0 rows returned for $server") );
        }
});


$app->post("/server(/)", function() use($app)
{

        $info = array();

        // required param(s)
        $req = array ( 'Server' );

        // get media type so we can tell the difference between x-www-form-urlencoded and json
        $mediaType = $app->request()->getMediaType() ?: 'text/html';
        switch ($mediaType)
        {

                case "application/json":
                        check_params_json($req); // see functions.php
                        // define vars for DB insert
                        $json   = json_decode($app->request->getbody(),true);
                        $server  = $json['Server'];
                        break;

                case "application/x-www-form-urlencoded":
                case "text/html":
                        check_params_text($req); // see functions.php
                        // define vars for DB insert
                        $server   = $app->request->post("Server");
                        break;

                default:
                        json(200, 0, array("response" => "Invalid content-type: '$mediaType'") );
                        break;
        }


        // DATABASE SECTION
        // DB user/password settings
        include_once 'lib/DB.php';

        // count number of matches
        $total = $db->get_count( 'main', array( 'Server' => "$server") );
        if ( $total > 0 )
        {

                //$results = $db->get_all( 'main', array('Product' => "$product"), array( 'Id', 'Server', 'Product', 'Version', 'Release', 'Date', 'Comment' ) );
                $results = $db->get_all( 'main', array('Server' => "$server") );

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
                json(200, 1, array("response" => "server: 0 rows returned for $server") );
        }
});

?>
