<?php

// Autoload class files; this isn't working on server, see commet below...
spl_autoload_register(function ($class_name) {
    require_once $class_name . '.php';
});

/*
 * Manual load of class files because it didn't work on server.
 * I think this was because I hadn't specified the file root (i.e.
 * $local_dir which was created after).
*/

// Retrieve local folder on server:
$local_dir = dirname(__FILE__) . '/';

// Require class files:
require_once $local_dir . 'Location.php';
require_once $local_dir . 'Database.php';
require_once $local_dir . 'XML.php';
require_once $local_dir . 'Stops.php';

// Get atcocode from input
$atcocode = $_GET["a"];
$stops = new Stops("localhost", "transport", "Fdsx2T3a5%J@~{,d", "transportdemophp");
$buses = $stops->buses_by_stop($atcocode);
// $buses = $stops->curl_api($buses_url);
// print_r($buses);

$xml = new XML();
$xml_output = $xml->json_times_to_XML($buses);

//


?>
