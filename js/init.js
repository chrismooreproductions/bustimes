
// Init function - creates new map centred on address (for init see above variable).
// Gets address from address input and uses Google Geocode to convert to XML database
// request which returns lat and long.
// Could use cookies to store user's default location.


var map;

function initMap() {
    var loadaddress = 'Newcastle';
    $("#address").val(loadaddress);
    makeMap(loadaddress);
}

function makeMap(address) {
    var busInfo = null;
    $('input').blur();
    $.get({
        url:'nearest_stops.php?a=' + address,
        data:{},
        success:function(data){

            // To parse xml in jQuery here use this:
            xmlDoc = $.parseXML( data ),
            $xml = $( xmlDoc ),
            $stop = $xml.find( "stop" )

            // Here's some stuff to output data...

            // Creates a map with center point specified in XML header by search_lat and search_long
            $( '#output-data' ).empty();

            var markerstop = "";
            var lat_center = $xml.find('search_lat').text();
            var long_center = $xml.find('search_long').text();
            var center = new google.maps.LatLng(lat_center, long_center);
            var mapOptions = { zoom:17, mapTypeId: google.maps.MapTypeId.ROADMAP, center: center };
            map = new google.maps.Map(document.getElementById("map"), mapOptions);
            var marker_home = new google.maps.Marker({
                map: map,
                position: center,
                icon: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png'
            });
            var locwindow = new google.maps.InfoWindow({
                content: '<div id="content">' + '<div id="title">' + 'Search location' + '</div>' + '</div>'
            })
            locwindow.open(map, marker_home);
            google.maps.event.addListener(map, 'click', function() {
                locwindow.close();
            });
            var latlong = [];
            // $( '#output-data' ).append(
            //     "<div class='hide'><div id='stops'>"
            // );
            var i = 0

            $stop.each(function(i) {
                // console.log($(this));
                // Get some variables from each stop as single line array
                var name = $(this).find('stop_name').text();
                var lat_stop = $(this).find('latitude').text();
                var long_stop = $(this).find('longitude').text();
                var letter = $(this).find('letter').text();
                var atcocode = $(this).find('atcocode').text();
                // Output to page
                $( '#output-data' ).append(
                    "<div id='stop-" + atcocode + "' data-atcocode='" + atcocode + "' class='stop'>" +
                        "<div class='letter'>" + letter + "</div>" + "<div class='location-name'>" + name + "</div>" +
                        "<div class='information ml-auto'><i class='fa fa-info' aria-hidden='true'></i></div>" +
                    "</div>"
                );

                $('#stop-' + atcocode).data( "foo", atcocode );
                if (busInfo) {
                    busInfo.close();
                }
                var busInfo = new google.maps.InfoWindow({
                    content: 'Buses from ' + name
                });

                // Google Map set up markers
                var point = new google.maps.LatLng( lat_stop, long_stop );
                var markerStop = new google.maps.Marker({
                    animation: google.maps.Animation.DROP,
                    map: map,
                    position: point,
                    label: letter
                });

                google.maps.event.addListener( markerStop, 'click', (function () {
                    locwindow.close();
                    busInfo.open( map, markerStop );
                    $(".results").slideUp();
                    $().ajaxRequest(atcocode);
                }));

                google.maps.event.addListener( map, 'click', function() {
                    busInfo.close();
                });

                i++; // next i

            });
        }
    });
}


$(document).ready(function() {
    $(function () {
        $('form').on('submit', function (e) {
            e.preventDefault();
            var address = $('#address').val();
            makeMap( address );
        });
    });
    $('#output-data').on('click', "div[id^='stop-']", function() {
        var $div = $(this),
        atcocode = $div.data('atcocode');
        if($('#results-' + atcocode).is(':visible')) {
            $(".results").slideUp();
        } else {
            $(".results").slideUp();
            $().ajaxRequest(atcocode);
        }
    });
    $.fn.ajaxRequest = function(atcocode) {
        $.ajax({
            url: 'buses_by_stop.php?a=' + atcocode,
            data:{},
            success:function(data){
                // console.log(data);
                xmlDoc = $.parseXML( data ),
                $xml = $( xmlDoc ),
                $bus = $xml.find( "bus" )

                var atcocodeApi = $xml.find('atcocode').text();
                var stopdom = $('#stop-' + atcocode);
                stopdom.after("<div id='results-" + atcocode + "' class='results'>" + "</div>");
                var resultsdom = $('#results-' + atcocode);
                $bus.each(function() {
                    var lineName = $(this).find('line_name').text();
                    var expectedTime = $(this).find('estimated_time').text();
                    var direction = $(this).find('direction').text();
                    resultsdom.append( "<div class='times'>" +
                    "<div class='line-name'>" + lineName + "</div>" +
                    "<div class='direction'>" + direction + "</div>" +
                    "<div class='expected-time ml-auto'>" + expectedTime +
                    "</div>" );
                });
                $('#output-data').scrollTop(0);
                $('#output-data').animate({scrollTop: stopdom.position().top - stopdom.parent().position().top}, "slow");
                resultsdom.slideDown();
            }
        });
    }
});
