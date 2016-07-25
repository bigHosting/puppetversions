<?php

defined('DIRECT') OR exit('No direct script access allowed');

/* ===> curl -X POST -d "Server=rrad4c50.domain.eu&Product=glibc&Version=2.12&Release=1.166.el6_7.7&Comment=AutomaticInsert" https://puppetversions.sec.domain.com/apiv1/puppetversions/insert/  */
$app->post("/insert/", function() use($app)
{
        $info = array();

        // set date to be inserted into Date field otherwise UPDATE statement will fail if all fields are the same as DB entries
        date_default_timezone_set('America/Toronto');
        $date = date('Y-m-d h:i:s', time());

        $server   = $app->request->post("Server");
        $product  = $app->request->post("Product");
        $version  = $app->request->post("Version");

        $release  = $app->request->post("Release");
        $comment  = $app->request->post("Comment");

        // check for required params
        if ( ((! $server ) || (strlen(trim($server)) <= 0)) || ((! $product ) || (strlen(trim($product)) <= 0)) || ((! $version ) || (strlen(trim($version)) <= 0)) )
        {
                json(422, 1, array("response" =>  "Server | Product | Version - invalid params") );
        }

        // check for required params
        if ( ((! $release ) || (strlen(trim($release)) <= 0)) || ((! $comment ) || (strlen(trim($comment)) <= 0 )) )
        {
                json(422, 1, array("response" =>  "Release | comment - invalid params") );
        }



        // check for proper format
        if (!preg_match('/^[a-zA-Z0-9-\._]+$/',$product))
        {
                json(200, 1, array("response" => "Product did not pass validation a-zA-Z0-9-._") );
        }
        // check for proper format
        if (!preg_match('/^[a-zA-Z0-9-\._]+$/',$server))
        {
                json(200, 1, array("response" => "Server did not pass validation a-zA-Z0-9-._") );
        }
        // check for proper format
        if (!preg_match('/^[a-zA-Z0-9-\._]+$/',$version))
        {
                json(200, 1, array("response" => "Version did not pass validation a-zA-Z0-9-._") );
        }
        // check for proper format
        if (!preg_match('/^[a-zA-Z0-9-\._]+$/',$release))
        {
                json(200, 1, array("response" => "Release did not pass validation a-zA-Z0-9 -._") );
        }
        // check for proper format
        if (!preg_match('/^[a-zA-Z0-9-\. _]+$/',$comment))
        {
                json(200, 1, array("response" => "Release did not pass validation a-zA-Z0-9 -._") );
        }


        $check = array( 'Server'=>"$server", 'Product'=>"$product", 'Version' => "$version", 'Release' => "$release", 'Comment' => "$comment" );
        $db = new smplPDO( "mysql:host=DATABASE_SERVER_HERE;dbname=puppetversions", "puppetversions", 'PASSWORD_HERE' );

        if( $db->exists( 'main', $check ) )
        {
                json(422, 1, array("response" => "Entry already exists") );

        } else {
                $db->insert( 'main',array('Server'  => "$server",
                                          'Product' => "$product",
                                          'Version' => "$version",
                                          'Release' => "$release",
                                          'Comment' => "$comment",
                                          'Date'    => "$date"
                                         )
                );

                if ( $db->num_rows == 1)
                {
                        json(200, 0, array("response" => "insert: row inserted successfully", "Id" => $db->insert_id ) );
                } else {
                        json(422, 1, array("response" => "insert: row insert failed") );
                }
        }

});

?>
