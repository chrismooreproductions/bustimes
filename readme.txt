******************************************
*                                        *
* Hello, thanks for checking this out    *
*                                        *
* This is an application I wrote for     *
* finding local bus stops (by address)   *
* and then retrieving the latest bus     *
* times.                                 *
*                                        *
******************************************

I'm currently trying to alter my PHP style to be more OOP so there are
a lot of -> arrows in this app. Also working on Class structures - I think
I will adapt the app to use interfaces as it could be fairly easily updated
to consider trains and trams too (each public transport stop has an atco
code.

This is an ongoing project as a demonstrator of stuff I can build.

*********************************
*                               *
*    How it works               *
*                               *
*********************************

The app loads from index.php. When the user completes a search (i.e. enter
address and press enter) the app submits the user input to nearest_stops.php
via an jQuery ajax request.

nearest_stops.php does some stuff, not all of it essential.

The important stuff is:
- API call to google geolocation service to convert address into lat + long 
  co-ords
- Use those co-ords to make API call to transportapi to retrieve nearest 25
  stops
- Check each stop to see if it is in the database and if it is not add it to
  the MySQL database table 'stopsdata'
- Return the data as XML to jQuery which parses it and displays the list of
  nearest bus stops.
- jQuery stores each bus stop atcocode as data tied to the dom (see 
  data-atcocode attributes generated in html). It does also use the atcocode 
  for the div id, but doesn't use the div id for anything (hella insecure).
- Uses the Google Maps Javascript API to display the markers for the search
  location and stop locations (or any locations supplied via the XML return).

On clicking a stop marker, there is another jQuery ajax call, this time to 
buses_by_stop.php using the atcocode of the stop from the data assigned to the
jQuery-generated elements.

- buses_by_stop.php sends a request to the transport api using the atcocode
  to return the latest departures from the api.
- Again this is turned into XML data (using XML writer) which is returned to 
  the front end for display using jQuery to append divs.

**********************************
*                                *
*   Download and usage           *
*                                *
**********************************

The .gitignore file in the root ignores the node_modules folder to reduce size
of the app. To install, download the folder and content to your web server, 
navigate to the root of the folder and do 'npm install'.

The gulp installation implements and browsersync. Ensure the proxy is set
correctly for your environment (on my machine it's "localhost/transportdemophp"
Type "gulp watch" to start watching - the site should load in your default
browser and then you can start SASSin' away.

Have fun and thanks for reading!
