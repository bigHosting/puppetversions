<?php

defined('DIRECT') OR exit('No direct script access allowed');

// ===> $ curl https://puppetversions.sec.domain.com/apiv1/puppetversions/releases
// ===> $ curl https://puppetversions.sec.domain.com/apiv1/puppetversions/releases -H "Content-Type: application/json"
$app->get("/releases(/)", function() use($app)
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
                        json(200, 0, array("response" => "Invalid content-type: '$mediaType'") );
                        break;
        }

        // cache section
        $cache = 1;
        $cachefile = "cache/" . pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME)  . '.json';
        if ( !empty ($cache) && strlen($cache) && file_exists($cachefile) ) { cache_read ($cachefile); }

        // DB user/password settings
        include_once 'lib/DB.php';
        $query = "select distinct(`Release`) AS `RELEASE` from main ORDER BY `Release`";

        $results =  $db->run($query)->fetchAll();

        if (!empty($results))
        {
                $info    = array();
                foreach ($results as $entry)
                {
                        array_push ($info, $entry['RELEASE']);
                }
                if ( !empty ($cache) && strlen($cache) ) { cache_write ( $cachefile, 200, 0, $info ); } // cache section
                json(200, 0, $info);
        } else {
                $message = array("response" => "releases: Incorrect query or 0 results");
                if ( !empty ($cache) && strlen($cache) ) { cache_write ( $cachefile, 200, 0, $message ); } // cache section
                json(200, 1, $message );
        }
});

?>
