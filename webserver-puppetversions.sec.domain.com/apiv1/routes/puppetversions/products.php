<?php

defined('DIRECT') OR exit('No direct script access allowed');

// ===> $ curl https://puppetversions.sec.domain.com/apiv1/puppetversions/products
// ===> $ curl https://puppetversions.sec.domain.com/apiv1/puppetversions/products -H "Content-Type: application/json"
$app->get("/products(/)", function() use($app)
{
        $info    = array();

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
        $query = "select distinct(`Product`) AS PRODUCT from main ORDER BY `Product`";

        $results =  $db->run($query)->fetchAll();

        if (!empty($results))
        {
                foreach ($results as $entry)
                {
                        array_push ($info, $entry['PRODUCT']);
                }
                if ( !empty ($cache) && strlen($cache) ) { cache_write ( $cachefile, 200, 0, $info ); } // cache section
                json(200, 0, $info);
        } else {
                $info = array("response" => "products: Incorrect query or 0 rows found");
                if ( !empty ($cache) && strlen($cache) ) { cache_write ( $cachefile, 200, 1, $info ); } // cache section
                json(200, 1, $info );
        }
});

?>
