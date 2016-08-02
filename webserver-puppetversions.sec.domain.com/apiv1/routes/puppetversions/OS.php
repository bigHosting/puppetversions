<?php

defined('DIRECT') OR exit('No direct script access allowed');

// ===> $ curl https://puppetversions.sec.domain.com/apiv1/puppetversions/OSs
// ===> $ curl https://puppetversions.sec.domain.com/apiv1/puppetversions/OSs -H "Content-Type: application/json"
$app->get("/OSs(/)", function() use($app)
{

        // get media type so we can tell the difference between x-www-form-urlencoded and json
        $mediaType = $app->request()->getMediaType() ?: 'text/html';

        switch (strtolower($mediaType))
        {

                case "application/json":
                        // json query !!!
                        break;

                case "application/x-www-form-urlencoded":
                case "text/html":
                        // regular query !!!
                        break;

                default:
                        json(200, 0, array("response" => "Invalid content-type; '$mediaType'") );
                        break;
        }

        // DB user/password settings
        include_once 'lib/DB.php';

        $query = "SELECT `Id`, `Server`, SUBSTRING_INDEX(CONCAT(`Version`, '.', `Release`),'.',2) as OS from main where `Product`='centos-release' ORDER BY `Server`";

        $results =  $db->run($query)->fetchAll();

        if (!empty($results))
        {

                $info    = array();
                foreach( $results as $row)
                {
                        $info[] = array( 'Id'      => $row['Id'],
                                         'Server'  => $row['Server'],
                                         'OS'      => $row['OS']
                                       );
                }
                json(200, 0, $info );
        } else {
                json(404, 1, array("response" => "select: Incorrect query") );
        }
});

?>
