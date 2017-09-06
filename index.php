<?php

$cookie_name = "location";
$cookie_value = "Birmingham";
// setcookie($cookie_name, $cookie_value);

?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
        <meta charset="utf-8">
        <title>A Nice Little Bus Stop Finder</title>
        <script src="https://use.fontawesome.com/f2c904471e.js"></script>
        <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
        <link rel="stylesheet" type="text/css" href="css/theme.css">
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAvf18QgPvYXLdmlIdY8CBtioBOV9hvRUk&callback=initMap"></script>
        <script type="text/javascript" src="js/init.js"></script>
    </head>
    <body>
        <!-- <?php
        if(!isset($_COOKIE[$cookie_name])) {
            echo "Cookie is not set!";
            setcookie($cookie_name, $cookie_value);
            echo "Cookie name: " . $cookie_name . " and value: " . $cookie_value . " set.";
        } else {
            // echo "Cookie '" . $cookie_name . "' is set!<br>";
            echo "Name is: " . $_COOKIE[$cookie_name] . " value is: " . $_COOKIE[$cookie_name];
        }
        ?> -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4">
                    <div class="results-heading">
                        <h2>Search by location:</h2>
                        <form action="process.php" method="POST">
                            <input id="address" type="text" name="long" value="Birmingham City Centre">
                            <button name="submit" type="submit" value="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                        </form>
                    </div>
                    <div id="output-data">

                    </div>
                    <div id="bus-times-output">

                    </div>
                </div>
                <div class="col-lg-8">
                    <div id="map">

                    </div>
                </div>

            </div>
        </div>
    </body>
</html>
