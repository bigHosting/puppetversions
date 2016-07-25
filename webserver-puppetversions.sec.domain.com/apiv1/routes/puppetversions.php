<?php

defined('DIRECT') OR exit('No direct script access allowed');

// include all .php files from ./billing folder
foreach (glob(dirname(__FILE__) . "/puppetversions/*.php") as $filename)
{
    require_once $filename;
}

?>
