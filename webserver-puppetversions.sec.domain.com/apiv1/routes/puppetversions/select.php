<?php

defined('DIRECT') OR exit('No direct script access allowed');

/* ===> curl https://puppetversions.sec.domain.com/apiv1/puppetversions/select/2  */
$app->get("/select/:id", function($id) use($app)
{
        $info = array();

        $id = filter_var(filter_var($id, FILTER_SANITIZE_NUMBER_INT),FILTER_VALIDATE_INT);
        if (false === $id) {
                json(404, 1, array("response" => "select: Invalid number") );
        }

        $req_id = array( 'id'=>$id );
        $db = new smplPDO( "mysql:host=DATABASE_SERVER_HERE;dbname=puppetversions", "puppetversions", 'PASSWORD_HERE' );

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
                json(404, 1, array("response" => "select: No such row Id found in the database") );
        }
});

?>
