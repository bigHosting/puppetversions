<?php

defined('DIRECT') OR exit('No direct script access allowed');

$app->get("/version/:version(/)", function($version) use($app)
{
        $info = array();

        // get media type so we can tell the difference between x-www-form-urlencoded and json
        $mediaType = $app->request()->getMediaType() ?: 'text/html';

        switch (strtolower($mediaType))
        {

                case "application/json":
                        // json query !!!
                        break;

                case "application/x-www-form-urlencoded":
                case "text/html":
                      if (!preg_match('/^[a-zA-Z\.-_]+$/',$version))
                      {
                              json(404, 1, array("response" => "version: Invalid chars in version name") );
                      }
                        // regular query !!!
                        break;

                default:
                        json(200, 0, array("response" => "Invalid content-type: '$mediaType'") );
                        break;
        }


        $check = array( 'Version' => "$version");

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
                $info = array("response" => "version: 0 rows returned for $version");
                if ( !empty ($cache) && strlen($cache) ) { cache_write ( $cachefile, 201, 1, $info ); } // cache section
                json(200, 1, $info );
        }
});

$app->post("/version(/)", function() use($app)
{
        $info = array();

        $req = array ('Version');

        // get media type so we can tell the difference between x-www-form-urlencoded and json
        $mediaType = $app->request()->getMediaType() ?: 'text/html';
        switch ($mediaType)
        {

                case "application/json":
                        check_params_json($req); // see functions.php
                        // define vars for DB insert
                        $json     = json_decode($app->request->getbody(),true);
                        $version  = $json['Version'];
                        break;

                case "application/x-www-form-urlencoded":
                case "text/html":
                        check_params_text($req); // see functions.php
                        // define vars for DB insert
                        $version  = $app->request->post("Version");
                        break;

                default:
                        json(200, 0, array("response" => "Invalid content-type: '$mediaType'") );
                        break;
        }

        $check = array( 'Version' => "$version");

        // cache section
        $cache = 1;
        $cachefile = "cache/" . pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME) . "_" . implode("_", array_keys($check)) . "_" . implode("_", $check) . '.json';
        if ( !empty ($cache) && strlen($cache) && file_exists($cachefile) ) { cache_read ($cachefile); }

        // DATABASE SECTION
        // DB user/password settings
        include_once 'lib/DB.php';

        // count number of matches
        $total = $db->get_count( 'main', $check );
        if ( $total > 0 )
        {

                //$results = $db->get_all( 'main', array('Product' => "$product"), array( 'Id', 'Server', 'Product', 'Version', 'Release', 'Date', 'Comment' ) );
                $results = $db->get_all( 'main', array('Version' => "$version") );

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
                $info = array("response" => "version: 0 rows returned for $version");
                if ( !empty ($cache) && strlen($cache) ) { cache_write ( $cachefile, 200, 1, $info ); } // cache section
                json(200, 1, $info );
        }
});

?>
