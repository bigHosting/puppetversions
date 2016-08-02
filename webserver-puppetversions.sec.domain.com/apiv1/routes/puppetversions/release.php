<?php

defined('DIRECT') OR exit('No direct script access allowed');

/* ===> curl https://puppetversions.sec.domain.com/apiv1/puppetversions/version/5.0.2.7  */
$app->get("/release/:release(/)", function($release) use($app)
{


        if (!preg_match('/^[a-zA-Z\.-_]+$/',$release))
        {
                json(404, 1, array("response" => "release: Invalid chars in release name") );
        }

        $info = array();

        // DB user/password settings
        include_once 'lib/DB.php';

        // count number of matches
        $total = $db->get_count( 'main', array( 'Release' => "$release") );
        if ( $total > 0 )
        {

                //$results = $db->get_all( 'main', array('Product' => "$product"), array( 'Id', 'Server', 'Product', 'Version', 'Release', 'Date', 'Comment' ) );
                $results = $db->get_all( 'main', array('Release' => "$release") );

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
                json(404, 1, array("response" => "release: 0 rows returned for $release") );
        }
});


$app->post("/release(/)", function() use($app)
{

        $req = array ('Release');

        // get media type so we can tell the difference between x-www-form-urlencoded and json
        $mediaType = $app->request()->getMediaType() ?: 'text/html';
        switch ($mediaType)
        {

                case "application/json":
                        check_params_json($req); // see functions.php
                        // define vars for DB insert
                        $json    = json_decode($app->request->getbody(),true);
                        $release = $json['Release'];
                        break;


                case "application/x-www-form-urlencoded":
                case "text/html":
                        check_params_text($req); // see functions.php
                        // define vars for DB insert
                        $release  = $app->request->post("Release");
                        break;

                default:
                        json(200, 0, array("response" => "Invalid content-type: '$mediaType'") );
                        break;
        }


        $info = array();

        // DB user/password settings
        include_once 'lib/DB.php';

        // count number of matches
        $total = $db->get_count( 'main', array( 'Release' => "$release") );
        if ( $total > 0 )
        {

                //$results = $db->get_all( 'main', array('Product' => "$product"), array( 'Id', 'Server', 'Product', 'Version', 'Release', 'Date', 'Comment' ) );
                $results = $db->get_all( 'main', array('Release' => "$release") );

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
                json(404, 1, array("response" => "release: 0 rows returned for $release") );
        }
});


?>
