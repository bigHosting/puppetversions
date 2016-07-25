<?php

defined('DIRECT') OR exit('No direct script access allowed');

/* ===> curl -X POST -d "Server=api2c45.domain.eu&Product=glibc&Version=2.12&Release=1.166.el6_7.7&Comment=AutomaticInseretTest" https://puppetversions.sec.domain.com/apiv1/puppetversions/insertORupdate/ */
$app->post("/insertORupdate/", function() use($app)
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
                json(422, 1, array("response" =>  "insertORupdate: Server | Product | Version - invalid params") );
        }

        // check for required params
        if ( ((! $release ) || (strlen(trim($release)) <= 0)) || ((! $comment ) || (strlen(trim($comment)) <= 0 )) )
        {
                json(422, 1, array("response" =>  "insertORupdate: Release | comment - invalid params") );
        }



        // check for proper format
        if (!preg_match('/^[a-zA-Z0-9-\._]+$/',$product))
        {
                json(200, 1, array("response" => "insertORupdate: Product did not pass validation a-zA-Z0-9-._") );
        }
        // check for proper format
        if (!preg_match('/^[a-zA-Z0-9-\._]+$/',$server))
        {
                json(200, 1, array("response" => "insertORupdate: Server did not pass validation a-zA-Z0-9-._") );
        }
        // check for proper format
        if (!preg_match('/^[a-zA-Z0-9-\._]+$/',$version))
        {
                json(200, 1, array("response" => "insertORupdate: Version did not pass validation a-zA-Z0-9-._") );
        }
        // check for proper format
        if (!preg_match('/^[a-zA-Z0-9-\._]+$/',$release))
        {
                json(200, 1, array("response" => "insertORupdate: Release did not pass validation a-zA-Z0-9 -._") );
        }
        // check for proper format
        if (!preg_match('/^[a-zA-Z0-9-\. _]+$/',$comment))
        {
                json(200, 1, array("response" => "insertORupdate: Comment did not pass validation a-zA-Z0-9 -._") );
        }

        // only check server and product as db has UNIQUE KEY `Uniq` (`Server`,`Product`)
        $check = array( 'Server'=>"$server", 'Product'=>"$product" );
        $db = new smplPDO( "mysql:host=DATABASE_SERVER_HERE;dbname=puppetversions", "puppetversions", 'PASSWORD_HERE' );

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
                        json(200, 0, array("response" => "insertORupdate: row updated successfully", "Id" => $rowId ) );
                } else {
                        json(200, 1, array("response" => "insertORupdate: row update failed", "Id" => $rowId ) );
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
                        json(200, 0, array("response" => "insertORupdate: row inserted successfully", "Id" => $db->insert_id ) );
                } else {
                        json(200, 1, array("response" => "insertORupdate: row insert failed") );
                }
        }

});

?>
