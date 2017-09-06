<?php

class XML {

    public function db_stops_data_to_XML($lat, $long, $stops) {

        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<stops_search_results>';
            echo '<search_info>';
                echo '<search_lat>' . $lat . '</search_lat>';
                echo '<search_long>' . $long . '</search_long>';
            echo '</search_info>';
            echo '<all_stops>';
            $letter = 'A';
                foreach ($stops as $stop) {
                    // print_r($stop);
                echo '<stop>';
                    echo '<letter>' . $letter . '</letter>';
                    $letter++;
                    echo '<id>' . $stop[0] . '</id>';
                    echo '<atcocode>' . $stop[1] . '</atcocode>';
                    echo '<mode>' . $stop[2] . '</mode>';
                    echo '<name>' . $stop[3] . '</name>';
                    echo '<stop_name>' . $stop[4] . '</stop_name>';
                    echo '<smscode>' . $stop[5] . '</smscode>';
                    echo '<locality>' . $stop[6] . '</locality>';
                    echo '<indicator>' . $stop[7] . '</indicator>';
                    echo '<longitude>' . $stop[8] . '</longitude>';
                    echo '<latitude>' . $stop[9] . '</latitude>';
                echo '</stop>';
                }

            echo '</all_stops>';
        echo '</stops_search_results>';

    }

    public function json_stops_data_to_XML($lat, $long, $stops) {

        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<stops_search_results>';
            echo '<search_info>';
                echo '<search_lat>' . $lat . '</search_lat>';
                echo '<search_long>' . $long . '</search_long>';
            echo '</search_info>';
            echo '<all_stops>';
            $letter = 'A';
                foreach ($stops as $stop) {
                echo '<stop>';
                    echo '<letter>' . $letter . '</letter>';
                    $letter++;
                    echo '<atcocode>' . $stop[atcocode] . '</atcocode>';
                    echo '<mode>' . $stop[mode] . '</mode>';
                    echo '<name>' . $stop[name] . '</name>';
                    echo '<stop_name>' . $stop[stop_name] . '</stop_name>';
                    echo '<smscode>' . $stop[smscode] . '</smscode>';
                    echo '<locality>' . $stop[locality] . '</locality>';
                    echo '<indicator>' . $stop[indicator] . '</indicator>';
                    echo '<longitude>' . $stop[longitude] . '</longitude>';
                    echo '<latitude>' . $stop[latitude] . '</latitude>';
                echo '</stop>';
                }

            echo '</all_stops>';
        echo '</stops_search_results>';

    }

    public function json_times_to_XML($buses) {
        $departures = $buses[departures];
        $writer = new XMLWriter();
        $writer->openURI('php://output');
        $writer->startDocument('1.0','UTF-8');
        $writer->setIndent(4);
        $writer->startElement('times_search_results');
            $writer->startElement('search_info');
                $writer->writeElement('atcocode', $buses[atcocode] );
            $writer->endElement();
            $writer->startElement('all_buses_and_times');
            foreach ($departures as $departure) {
                $writer->startElement('bus_line');
                foreach ($departure as $bus) {
                    $writer->startElement('bus');
                    $writer->writeElement('line_name', $bus[line_name]);
                    $writer->writeElement('direction', $bus[direction]);
                    $writer->writeElement('expected_time', $bus[expected_departure_time]);
                    $writer->writeElement('estimated_time', $bus[best_departure_estimate]);
                    $writer->writeElement('timetable_time', $bus[aimed_departure_time]);
                    $writer->endElement();
                }

                $writer->endElement();
            }
            $writer->endElement();
        $writer->endElement();
        $writer->endDocument();
        $writer->flush();
    }

}

?>
