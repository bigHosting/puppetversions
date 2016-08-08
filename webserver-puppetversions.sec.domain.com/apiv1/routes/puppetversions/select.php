<?php

defined('DIRECT') OR exit('No direct script access allowed');

$app->get("/select/:id(/)", function($id) use($app)
{
        $info = array();

        $id = filter_var(filter_var($id, FILTER_SANITIZE_NUMBER_INT),FILTER_VALIDATE_INT);
        if (false === $id) {
                json(200, 1, array("response" => "select: Invalid number") );
        }

        $req_id = array( 'Id'=>$id );

        // cache section
        $cache = 1;
        $cachefile = "cache/" . pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME) . "_" . implode("_", array_keys($req_id)) . "_" . implode("_", $req_id) . '.json';
        if ( !empty ($cache) && strlen($cache) && file_exists($cachefile) ) { cache_read ($cachefile); }


        // DB user/password settings
        include_once 'lib/DB.php';

        if( $db->exists( 'main', $req_id ) )
        {
                $results = $db->get_row( 'main', $req_id, array( 'Id', 'Server', 'Product', 'Version', 'Release', 'Date', 'Comment' ) );

                $info["Id"]      = $results['Id'];
                $info["Server"]  = $results["Server"];
                $info["Product"] = $results["Product"];
                $info["Version"] = $results["Version"];
                $info["Release"] = $results["Release"];

                $info["Date"]    = $results["Date"];
                $info["Comment"] = $results["Comment"];

                if ( !empty ($cache) && strlen($cache) ) { cache_write ( $cachefile, 200, 0, array("response" => $info) ); } // cache section
                json(200, 0, array("response" => $info) );

        } else {
                $info = array("response" => "select: No such row Id found in the database");
                if ( !empty ($cache) && strlen($cache) ) { cache_write ( $cachefile, 200, 1, $info ); } // cache section
                json(200, 1, $info );
        }
});


$app->post("/select(/)", function() use($app)
{
        $info = array();

        $req = array ( 'Id' );

        // get media type so we can tell the difference between x-www-form-urlencoded and json
        $mediaType = $app->request()->getMediaType() ?: 'text/html';
        switch ($mediaType)
        {

                case "application/json":
                        check_params_json($req); // see functions.php
                        // define vars for DB insert
                        $json   = json_decode($app->request->getbody(),true);
                        $id     = $json['Id'];
                        break;

                case "application/x-www-form-urlencoded":
                case "text/html":
                        check_params_text($req); // see functions.php
                        // define vars for DB insert
                        $id     = $app->request->post("Id");
                        break;

                default:
                        json(200, 0, array("response" => "Invalid content-type: '$mediaType'") );
                        break;
        }


        $id = filter_var(filter_var($id, FILTER_SANITIZE_NUMBER_INT),FILTER_VALIDATE_INT);
        if (false === $id) {
                json(200, 1, array("response" => "select: Invalid number") );
        }


        // DATABASE SECTION
        $req_id = array( 'Id'=>$id );

        // cache section
        $cache = 1;
        $cachefile = "cache/" . pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME) . "_" . implode("_", array_keys($req_id)) . "_" . implode("_", $req_id) . '.json';
        if ( !empty ($cache) && strlen($cache) && file_exists($cachefile) ) { cache_read ($cachefile); }

        // DB user/password settings
        include_once 'lib/DB.php';

        if( $db->exists( 'main', $req_id ) )
        {
                $results = $db->get_row( 'main', $req_id, array( 'Id', 'Server', 'Product', 'Version', 'Release', 'Date', 'Comment' ) );

                $info["Id"]      = $results['Id'];
                $info["Server"]  = $results["Server"];
                $info["Product"] = $results["Product"];
                $info["Version"] = $results["Version"];
                $info["Release"] = $results["Release"];

                $info["Date"]    = $results["Date"];
                $info["Comment"] = $results["Comment"];

                if ( !empty ($cache) && strlen($cache) ) { cache_write ( $cachefile, 200, 0, array("response" => $info) ); } // cache section
                json(200, 0, array("response" => $info) );


        } else {
                $info = array("response" => "select: No such row Id found in the database");
                if ( !empty ($cache) && strlen($cache) ) { cache_write ( $cachefile, 200, 1, $info ); } // cache section
                json(200, 1, $info );
        }
});

?>
