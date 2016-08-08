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

        $check = array( 'Release' => "$release");

        // cache section
        $cache = 1;
        $cachefile = "cache/" . pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME) . "_" . implode("_", array_keys($check)) . "_" . implode("_", $check) . '.json';
        if ( !empty ($cache) && strlen($cache) && file_exists($cachefile) ) { cache_read ($cachefile); }


        // DB user/password settings
        include_once 'lib/DB.php';

        // count number of matches
        $total = $db->get_count( 'main', $check );
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
                if ( !empty ($cache) && strlen($cache) ) { cache_write ( $cachefile, 200, 0, $info ); } // cache section
                json(200, 0, $info );
        } else {
                $info = array("response" => "release: 0 rows returned for $release");
                if ( !empty ($cache) && strlen($cache) ) { cache_write ( $cachefile, 200, 1, $info ); } // cache section
                json(200, 1, $info );
        }
});


$app->post("/release(/)", function() use($app)
{

        $info = array();

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

        $check = array( 'Release' => "$release");

        // cache section
        $cache = 1;
        $cachefile = "cache/" . pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME) . "_" . implode("_", array_keys($check)) . "_" . implode("_", $check) . '.json';
        if ( !empty ($cache) && strlen($cache) && file_exists($cachefile) ) { cache_read ($cachefile); }


        // DB user/password settings
        include_once 'lib/DB.php';

        // count number of matches
        $total = $db->get_count( 'main', $check );
        if ( $total > 0 )
        {

                //$results = $db->get_all( 'main', array('Product' => "$product"), array( 'Id', 'Server', 'Product', 'Version', 'Release', 'Date', 'Comment' ) );
                $results = $db->get_all( 'main', $check );

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
                if ( !empty ($cache) && strlen($cache) ) { cache_write ( $cachefile, 200, 0, $info ); } // cache section
                json(200, 0, $info );
        } else {
                $info = array("response" => "release: 0 rows returned for $release");
                if ( !empty ($cache) && strlen($cache) ) { cache_write ( $cachefile, 200, 1, $info ); } // cache section
                json(200, 1, $info );
        }
});


?>
