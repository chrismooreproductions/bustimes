<?php

class Database {

    public $link;
    private $host, $username, $password, $database;

    public function __construct($host, $username, $password, $database){

        $this->host        = $host;
        $this->username    = $username;
        $this->password    = $password;
        $this->database    = $database;

        $this->link = new mysqli($this->host, $this->username, $this->password, $this->database)
            OR die("There was a problem connecting to the database.");
            // print_r($this);
        return true;

    }

    public function multi_query($query) {
        if ($this->link->multi_query($query)) {
            do {
                if ( $result = $this->link->store_result() ) {
                    $results = array();
                    while ($row = $result->fetch_row()) {
                        array_push($results, $row);
                    }
                    $result->free();
                    return $results;
                }
            } while ( $this->link->next_result() );
        } else {
            echo "There was an error with your query";
        }
    }

    public function add_stops_to_db($stops_data) {

        $stops_length = count($stops_data);

        for ( $i = 0; $i < $stops_length; $i++ ) {
                $atcocode = $stops_data[$i][atcocode];
                $query = "SELECT * FROM stopsdata WHERE atcocode = '$atcocode'";
                $test = $this->link->query($query);
                $row_cnt = $test->num_rows;
                // echo $row_cnt . "\n";
                if ( $row_cnt === 0 ) {
                    // echo "We're going to keep this data safe for you\n";
                    $atcocode = $stops_data[$i][atcocode];
                    $mode = $stops_data[$i][mode];
                    $name = $stops_data[$i][name];
                    $stop_name = $stops_data[$i][stop_name];
                    $smscode = $stops_data[$i][smscode];
                    $bearing = $stops_data[$i][bearing];
                    $locality = $stops_data[$i][locality];
                    $indicator = $stops_data[$i][indicator];
                    $long = $stops_data[$i][longitude];
                    $long = floatval($long);
                    $lat = $stops_data[$i][latitude];
                    $lat = floatval($lat);
                    $insert_stops = "INSERT INTO stopsdata ( atcocode, mode, name, stop_name, smscode, locality, indicator, longitude, latitude )
                    VALUES ( '$atcocode', '$mode', '$name', '$stop_name', '$smscode', '$locality', '$indicator', '$long', '$lat' )";
                    if ( $this->link->query($insert_stops) ) {
                        // echo "Value added to database\n";
                    } else {
                        // echo "There was an error adding this to the database\n";
                    }
                    // echo "ok";
                } else {
                    // echo "it's already in the database\n";
            }
        }
    }

    public function __destruct() {
        mysqli_close($this->link)
            OR die("There was a problem disconnecting from the database.");
    }

}

?>
