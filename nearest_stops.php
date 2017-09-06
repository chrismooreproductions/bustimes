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

// Get address field as string:
$search_address = $_GET["a"];

// Get location based on input address from Location class:
$location = new Location();
$get_location = $location -> getGeocodeUrl( $search_address );
$latlong = $location -> getLatLong( $get_location );
$center_lat = simplexml_load_string($latlong)->result->geometry->location->lat;
$center_long = simplexml_load_string($latlong)->result->geometry->location->lng;

// Begin constructing an array of lats and longs for map view purposes:
$lats = [];
$longs = [];
// i.e. $lats[0] is $center_lat and $longs[0] is $center_long
array_push($lats, $center_lat);
array_push($longs, $center_long);


/*
 * This bit doesn't really do anything at the moment.
 * I want to introduce logic to store bus numbers by stop in the database
 * and to pull that data from the database on stop retrieval.
 * If there is no data for buses from stops it pulls it from API.
 * The reason being if you have lots of ajax functions to an API
 * it can really slow things down.
 * I need to rewrite the database class to handle storage of buses by stop.
 * I already have a table of buses which needs modification to accept all fields from the API.
 */

// Connect to db:
$db = new Database("localhost", "transport", "Fdsx2T3a5%J@~{,d", "transportdemophp");
// Make query from address co-ordinates ( select all, order by longitude and latitude proximity and return top 10 ):
$query = "SET @lat_center=" . $center_lat . ";" ;
$query.= "SET @long_center=" . $center_long . ";" ;
$query.= "SELECT * FROM stopsdata ORDER BY ABS (longitude - @long_center), ABS (latitude - @lat_center) limit 10;";
// Make query (note multi_query in Database class query function):
$stops = $db->multi_query($query);

// New instance of XML class to generate returned XML:


$xml = new XML();

/*
 * Here we are going to calculate the distance from the search point to the first stop.
 * If it's less than say 200m we'll do a call to the api as a starter for reining this thing in.
 * Then need to do a call by date so update stop markers in database say once a week.
*/

$center_lat = ((float)$center_lat);
$center_long = ((float)$center_long);
$latitudeTo = ((float)$stops[0][9]);
$longitudeTo = ((float)$stops[0][8]);
$distance = $location->distance( $center_lat, $center_long, $latitudeTo, $longitudeTo );
// if ( $distance > 1000 ) {
    $lookupstops = new Stops("localhost", "transport", "Fdsx2T3a5%J@~{,d", "transportdemophp");
    $stops_url = $lookupstops->stops_by_location($center_lat, $center_long);
    $get_stops = $lookupstops->curl_api($stops_url);
    $xml_output = $xml -> json_stops_data_to_XML($center_lat, $center_long, $get_stops);
// } else {
//     $xml_output = $xml -> db_stops_data_to_XML($center_lat, $center_long, $stops);
// }

// print_r($xml_output);

?>
