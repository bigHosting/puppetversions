<?php

defined('DIRECT') OR exit('No direct script access allowed');

$app->post("/insertowner(/)", function() use($app)
{
        $info = array();

        // set date to be inserted into Date field otherwise UPDATE statement will fail if all fields are the same as DB entries
        date_default_timezone_set('America/Toronto');
        $date = date('Y-m-d h:i:s', time());

        $req = array ('Owner', 'Server');

        // get media type so we can tell the difference between x-www-form-urlencoded and json
        $mediaType = $app->request()->getMediaType() ?: 'text/html';
        switch ($mediaType)
        {

                case "application/json":
                        check_params_json($req); // see functions.php
                        // define vars for DB insert
                        $json   = json_decode($app->request->getbody(),true);
                        $server  = $json['Server'];
                        $owner   = $json['Owner'];
                        break;


                case "application/x-www-form-urlencoded":
                case "text/html":
                        check_params_text($req); // see functions.php
                        // define vars for DB insert
                        $server   = $app->request->post("Server");
                        $owner    = $app->request->post("Owner");
                        break;

                default:
                        json(200, 0, array("response" => "Invalid content-type: '$mediaType'") );
                        break;
        }


        // DATABASE SECTION
        $check = array( 'Server'=>"$server" );

        // cache section
        $cache = 1;
        $cachefile = "cache/" . pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME) . "_" . implode("_", array_keys($check)) . "_" . implode("_", $check) . '.json';
        if ( !empty ($cache) && strlen($cache) && file_exists($cachefile) ) { cache_read ($cachefile); }

        // DB user/password settings
        include_once 'lib/DB.php';

        if( $db->exists( 'owners', $check ) )
        {
        // UPDATE
                // get Id of existing record
                $rowId = $db->get_var( 'Owners', $check, 'Id' );


                // UPDATE database
                $db->update( 'owners', array( 'Server'  => "$server",
                                              'Owner'   => "$owner",
                                              'Date'    => "$date"
                                            ), $check );

                if ( $db->num_rows == 1 )
                {
                        $info = array("response" => "insertowner: row updated successfully");
                        if ( !empty ($cache) && strlen($cache) ) { cache_write ( $cachefile, 200, 0, $info ); } // cache section
                        json(200, 0, $info );
                } else {
                        $info = array("response" => "insertowner: row update failed" );
                        if ( !empty ($cache) && strlen($cache) ) { cache_write ( $cachefile, 200, 1, $info ); } // cache section
                        json(200, 1, $info );
                }

        } else {
        // INSERT
                $db->insert( 'owners',array( 'Server'  => "$server",
                                             'Owner'   => "$owner",
                                             'Date'    => "$date"
                                           )
                );

                if ( $db->num_rows == 1)
                {
                        $info = array("response" => "insertowner: row inserted successfully", "Id" => $db->insert_id );
                        if ( !empty ($cache) && strlen($cache) ) { cache_write ( $cachefile, 200, 0, $info ); } // cache section
                        json(200, 0, $info );
                } else {
                        $info = array("response" => "insertowner: row update failed" );
                        if ( !empty ($cache) && strlen($cache) ) { cache_write ( $cachefile, 200, 1, $info ); } // cache section
                        json(200, 1, $info );
                }
        }

});

?>
