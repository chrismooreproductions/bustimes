<?php

require_once('Database.php');

class Stops extends Database {

    public $base_url = 'https://transportapi.com';
    public $app_id_txt = '&app_id=';
    public $app_id = 'b5206863';
    public $app_key_txt = '&app_key=';
    public $app_key = 'b0fda07e1d5925fce459b9f02526f8ac';
    public $long_text = '&lon=';
    public $lat_text = '&lat=';

    public function stops_by_location($center_lat, $center_long) {
        $api_places = "/v3/uk/bus/stops/near.json?";
        $auth = $this->app_id_txt . $this->app_id . $this->app_key_txt . $this->app_key;
        $url = $this->base_url . $api_places . $auth . $this->lat_text . $center_lat . $this->long_text . $center_long;
        return $url;
    }

    public function curl_api($url) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url
        ));
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_error($curl);
        curl_close($curl);
        $stops_array = json_decode($response, true); //because of true, it's in an array
        $stops_data = $stops_array[stops];
        $this->add_stops_to_db($stops_data);
        return $stops_data;
    }

    public function buses_by_stop($atcocode) {
        $api_bus_times_start = "/v3/uk/bus/stop/";
        $api_bus_times_end = "/live.json";
        $app_id_txt = "?app_id=";
        $app_key_txt = "&app_key=";
        $auth = $app_id_txt . $this->app_id . $app_key_txt . $this->app_key;
        // print_r($auth);
        $group_txt = "&group=";
        $group = "route";
        $nextbuses_txt = "&next_buses=";
        $nextbuses = "yes";
        $url = $this->base_url . $api_bus_times_start . $atcocode . $api_bus_times_end . $auth . $group_txt . $group . $nextbuses_txt . $nextbuses;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url
        ));
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        $bus_times_array = json_decode($response, true);
        return $bus_times_array;
    }

    //
    // public function json_lookup_bus_times( $base_url, $atcocodes ) {
    //
    //     echo "<div id='live-times'>";
    //     echo "<h3>Live Bus Times: </h3>";
    //     // echo '<div id="live-times">';
    //
    //     $api_bus_times_start = "/v3/uk/bus/stop/";
    //     $api_bus_times_end = "/live.json";
    //     require 'transportapi_auth.php';
    //     $app_id_txt = "?app_id=";
    //     $app_key_txt = "&app_key=";
    //     $auth = $app_id_txt . $app_id . $app_key_txt . $app_key;
    //     $group_txt = "&group=";
    //     $group = "route";
    //     $nextbuses_txt = "&next_buses=";
    //     $nextbuses = "yes";
    //     // This right here is the code to get bus times by stop.
    //
    //     for ($i= 0 ; $i < 5 ; $i++) {
    //         $url = $base_url . $api_bus_times_start . $atcocodes[$i] . $api_bus_times_end . $auth . $group_txt . $group . $nextbuses_txt . $nextbuses;

    //         // print_r($bus_times_array);
    //         echo "<h4>Live departures from " . $bus_times_array[stop_name] . "</h4>";
    //         echo "<ul class='live-list-item'>";
    //         $bus_times_array_length = count($bus_times_array[departures]);
    //         foreach ($bus_times_array[departures] as $departure) {
    //             for ($j = 0; $j <= 2; $j++) {
    //                 $whole_destination = explode(",", $departure[$j][direction], 2);
    //                 $destination = $whole_destination[0];
    //                 echo "<li>" . $departure[$j][line_name] . " - " . $destination . " - " . $departure[$j][expected_departure_time] . "</li>";
    //             }
    //         }
    //         echo "</ul>";
    //     }
    //     echo "</div>";
    // }
    //

}

?>
