<?php

defined('DIRECT') OR exit('No direct script access allowed');

/* ===> curl -X POST -d "Server=web1c45.domain&Product=glibc&Version=2.12&Release=1.166.el6_7.7&Comment=AutomaticInseretTest" https://puppetversions.sec.domain.com/apiv1/puppetversions/insertORupdate/ */
$app->post("/insertORupdate/", function() use($app)
{
        $info = array();

        // set date to be inserted into Date field otherwise UPDATE statement will fail if all fields are the same as DB entries
        date_default_timezone_set('America/Toronto');
        $date = date('Y-m-d h:i:s', time());


        $req = array ('Server', 'Product', 'Version', 'Release', 'Comment');

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
                        $version  = $json['Version'];
                        $release  = $json['Release'];
                        $comment  = $json['Comment'];
                        break;


                case "application/x-www-form-urlencoded":
                case "text/html":
                        check_params_text($req); // see functions.php
                        // define vars for DB insert
                        $server   = $app->request->post("Server");
                        $product  = $app->request->post("Product");
                        $version  = $app->request->post("Version");
                        $release  = $app->request->post("Release");
                        $comment  = $app->request->post("Comment");
                        break;

                default:
                        json(200, 0, array("response" => "Invalid content-type: '$mediaType'") );
                        break;
        }




        // DATABASE SECTION
        $check = array( 'Server'=>"$server", 'Product'=>"$product" );

        // cache section
        $cache = 0;
        $cachefile = "cache/" . pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME) . "_" . implode("_", array_keys($check)) . "_" . implode("_", $check) . '.json';
        if ( !empty ($cache) && strlen($cache) && file_exists($cachefile) ) { cache_read ($cachefile); }

        // DB user/password settings
        include_once 'lib/DB.php';

        if( $db->exists( 'main', $check ) )
        {
        // UPDATE
                // get Id of existing record
                $rowId = $db->get_var( 'main', $check, 'Id' );


                // UPDATE database
                $db->update( 'main', array( 'Server'  => "$server",
                                            'Product' => "$product",
                                            'Version' => "$version",
                                            'Release' => "$release",
                                            'Comment' => "$comment",
                                            'Date'    => "$date"
                                            ), $check );

                if ( $db->num_rows == 1 )
                {
                        $info = array("response" => "insertORupdate: row updated successfully", "Id" => $rowId );
                        if ( !empty ($cache) && strlen($cache) ) { cache_write ( $cachefile, 200, 0, $info ); } // cache section
                        json(200, 0, $info );
                } else {
                        $info = array("response" => "insertORupdate: row update failed", "Id" => $rowId );
                        if ( !empty ($cache) && strlen($cache) ) { cache_write ( $cachefile, 200, 1, $info ); } // cache section
                        json(200, 1, $info );
                }

        } else {
        // INSERT
                $db->insert( 'main',array( 'Server'  => "$server",
                                           'Product' => "$product",
                                           'Version' => "$version",
                                           'Release' => "$release",
                                           'Comment' => "$comment",
                                           'Date'    => "$date"
                                         )
                );

                if ( $db->num_rows == 1)
                {
                        $info = array("response" => "insertORupdate: row inserted successfully", "Id" => $db->insert_id );
                        if ( !empty ($cache) && strlen($cache) ) { cache_write ( $cachefile, 200, 0, $info ); } // cache section
                        json(200, 0, $info );
                } else {
                        $info = array("response" => "insertORupdate: row insert failed");
                        if ( !empty ($cache) && strlen($cache) ) { cache_write ( $cachefile, 200, 1, $info ); } // cache section
                        json(200, 1, $info );
                }
        }

});

?>
