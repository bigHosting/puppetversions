<?php

defined('DIRECT') OR exit('No direct script access allowed');

/* ===> curl https://puppetversions.sec.domain.com/apiv1/puppetversions/products  */
$app->get("/products", function() use($app)
{
        $db = new smplPDO( "mysql:host=DATABASE_HERE;dbname=puppetversions", "puppetversions", 'PASSWORD_HERE' );
        $query = "select distinct(Product) AS PRODUCTS from main";

        $results =  $db->run($query)->fetchAll();

        if (!empty($results))
        {
                $info    = array();
                foreach ($results as $entry)
                {
                        array_push ($info, $entry['PRODUCTS']);
                }
                json(200, 0, $info);
        } else {
                json(404, 1, array("response" => "select: Incorrect query or empty results") );
        }
});

?>
