<?php

defined('DIRECT') OR exit('No direct script access allowed');

$app->get("/select/:id(/)", function($id) use($app)
{
        $info = array();

        $id = filter_var(filter_var($id, FILTER_SANITIZE_NUMBER_INT),FILTER_VALIDATE_INT);
        if (false === $id) {
                json(200, 1, array("response" => "select: Invalid number") );
        }

        $req_id = array( 'id'=>$id );

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
                //echo_json_exit ($info, 200);
                json(200, 0, array("response" => $info) );


        } else {
                json(200, 1, array("response" => "select: No such row Id found in the database") );
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
        $req_id = array( 'id'=>$id );

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
                //echo_json_exit ($info, 200);
                json(200, 0, array("response" => $info) );


        } else {
                json(200, 1, array("response" => "select: No such row Id found in the database") );
        }
});

?>
