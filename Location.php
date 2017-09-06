<?php

class Location {

    private $address;

    public function __construct( ) {

    }

    public function getGeocodeUrl( $address ) {
        $address = str_replace (" ", "+", urlencode($address));
        $geocode_url = "http://maps.googleapis.com/maps/api/geocode/xml?address=".$address."&components=country:GB";
        return $geocode_url;
    }

    public function getLatLong( $geocode_url ) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $geocode_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        return $result;
    }

    public function distance(
          $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo )
        {
          $earthRadius = (float)6371000;
          //   var_dump($earthRadius);
          // convert from degrees to radians
          $latFrom = deg2rad($latitudeFrom);
          $lonFrom = deg2rad($longitudeFrom);
          $latTo = deg2rad($latitudeTo);
          $lonTo = deg2rad($longitudeTo);

          $latDelta = $latTo - $latFrom;
          $lonDelta = $lonTo - $lonFrom;

          $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
          return $angle * $earthRadius;
        }


}



 ?>
