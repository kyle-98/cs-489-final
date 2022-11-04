<?php 
    session_start();
    $_SESSION['x'] = true;

    if (!isset($_SESSION['x'])) {
        session_destroy();
        
     }

?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.1/dist/leaflet.css" integrity="sha256-sA+zWATbFveLLNqWO2gtiw3HL/lh1giY/Inf1BJ0z14=" crossorigin="" />
        <script src="https://unpkg.com/leaflet@1.9.1/dist/leaflet.js" integrity="sha256-NDI0K41gVbWqfkkaHj15IzU7PtMoelkzyKp8TOaFQ3s=" crossorigin=""></script>
        <link rel="stylesheet" href="/styles/main_style.css">
        <link rel="stylesheet" href="/styles/map.css">
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>

    </head>

    <body>

        <!--Header-->
        <div class="header" id="header">
            <div id="main-head-container" style="height:180px;display:table;width:100%;text-align:center;">
                <span id="main-head-text" style="font-size:37px;font-weight:bold;">Atlantic Tropical Cyclone History Map</span>
                <span id="sub-head-text" style="font-size:23px;">
                    <br>
                    Virginia Wesleyan University
                </span>
            </div>
        </div>

        <!--Nav Bar-->    
        <div id="nav-bar"></div>
        <script>
            $(function(){
                $("#nav-bar").load("navbar.html");
                });
            
            $(this).addClass('active'); $(this).parents('li').addClass('active');
        </script>


        <script>
            function showStorms(year) {
                if (year == "") {
                    document.getElementById("storms").innerHTML = "";
                    return;
                } else {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("storms").innerHTML = this.responseText;
                        }
                    };
                    xmlhttp.open("GET","year_query.php?q="+year,true);
                    xmlhttp.send();
                }
            }

            function getStormData(storm, year){
                if (storm == "") {
                    document.getElementById("variable-script").innerHTML = "";
                    return;
                } else {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("variable-script").innerHTML = this.responseText;
                        }
                    };
                    xmlhttp.open("GET","storm_query.php?q="+[storm, year],true);
                    xmlhttp.send();
                }
            }

        </script>

        <div style="padding-bottom: 40px;"></div>
        
        <div id="top-content">

            <div id="left-content">
                <div id="selectors">
                    <div id="year-selector">
                        <form>
                            <label for="yr">Year:</label>
                            <select name="years" id="yr" onchange="showStorms(this.value)">
                                <option value="">Select a year</option>
                                <option value="2000">2000</option>
                                <option value="2001">2001</option>
                                <option value="2002">2002</option>
                                <option value="2003">2003</option>
                                <option value="2004">2004</option>
                                <option value="2005">2005</option>
                                <option value="2006">2006</option>
                                <option value="2007">2007</option>
                                <option value="2008">2008</option>
                                <option value="2009">2009</option>
                                <option value="2010">2010</option>
                                <option value="2011">2011</option>
                                <option value="2012">2012</option>
                                <option value="2013">2013</option>
                                <option value="2014">2014</option>
                                <option value="2015">2015</option>
                                <option value="2016">2016</option>
                                <option value="2017">2017</option>
                                <option value="2018">2018</option>
                                <option value="2019">2019</option>
                                <option value="2020">2020</option>
                                <option value="2021">2021</option>
                            </select>
                        </form>  
                    </div>
                    <div id="storm-selector">
                        <label for='storms'>Storms:</label>
                        <select name='storms' id='storms'>
                        </select>
                        <button id="storm-submit" onclick="getStormData(document.getElementById('storms').value, document.getElementById('yr').value); window.location.reload();">Go</button>
                    </div>
                </div>
            </div>

            <?php 
                    $name = $_SESSION['name'];
                    $year = $_SESSION['year'];
                    $coords = $_SESSION['coords'];
                    $data = $_SESSION['data'];
                ?>
            <div id="variable-script" style="display:none;"></div>

            <div id="right-content">
                <div id="name-header"></div>
                <script>
                    var header = document.createElement("h1");
                    var n = "<?php echo $name?>";
                    n = n.toLowerCase();
                    var new_n = n.charAt(0).toUpperCase() + n.slice(1);
                    var header_text = document.createTextNode(`Tropical Cyclone ${new_n} Track`);
                    header.appendChild(header_text);
                    var element = document.getElementById("name-header");
                    element.appendChild(header);
                </script>
            </div>
        </div>
        <div id="map"></div>
        

        <script>
            var map = L.map('map').setView([30.0, -80.0], 4);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 10,
                minZoom: 3,
                attribution: '<a href="https://www.nhc.noaa.gov/data/hurdat/" title="NOAA Hurricane History Database" target="_blank">NOAA Data Source',
                subdomains:['mt0','mt1','mt2','mt3']
            }).addTo(map);
                
            var name="<?php echo $name;?>";
            var year= Number("<?php echo $year;?>");
            var coords ="<?php echo $coords;?>";
            var data="<?php echo $data;?>";
            const s = "], [";
            const r = " | ";
            var temp = coords.split(s).join(r);
            temp = temp.replace("[", '');
            temp = temp.replace("]", '');
            temp = temp.split(" | ");
            for (let i = 0; i < temp.length; i++){
                temp[i] = temp[i].split(', ');
            }
            for(let i = 0; i < temp.length; i++){
                temp[i][0] = Number(temp[i][0]);
                temp[i][1] = Number(-temp[i][1]);
            }
            var coordsArray=[];
            for(let i = 0; i < temp.length; i++){
                coordsArray.push(temp[i]);
            }
            
            var temp_data = data.split(", ");
            for (let i = 0; i < temp_data.length; i++){
                temp_data[i] = temp_data[i].split(" ");
            } 

            var infoArray = temp_data;
            
            var track = L.polyline(coordsArray, {
                color: 'black',
                opacity: 0.5
            }).addTo(map);
            
            
            var circleArray = [];
            coordsArray.forEach(function(coords) {
                var circle = L.circle(coords, {
                    color: 'red',
                    fillcolor: '$f03',
                    fillOpacity: 0.5,
                    radius: 10000
                });
                circleArray.push(circle);
            });
            

            var a = 0;
            types_dictionary={
                "TD":"Tropical Depression",
                "TS":"Tropical Storm",
                "HU":"Hurricane",
                "EX":"Extratropical Cyclone",
                "SD":"Subtropical Depression",
                "SS":"Subtropical Cyclone",
                "LO":"Low Pressure Area",
                "WV":"Tropical Wave",
                "DB":"Disturbance"
            };

            function getMonthName(monthNumber) {
                const date = new Date();
                date.setMonth(monthNumber - 1);
                return date.toLocaleString([], { month: 'long' });
            }
            

            circleArray.forEach(function(cir){
                var placeholder_name = infoArray[a][2];
                var full_date = `${getMonthName(infoArray[a][0].substr(0, 2))} ${infoArray[a][0].substr(3, 4)}, ${year}`;
                var full_time = `${infoArray[a][1]} UTC`;
                if(types_dictionary[placeholder_name]){
                    placeholder_name = types_dictionary[placeholder_name];
                }
                cir.bindPopup("Date: " + full_date + "<br> Time: " + full_time + "<br> Type: " + placeholder_name + " <br> Max Sustained Winds: " + infoArray[a][3] + " kt" + "<br> Minimum Pressure: " + infoArray[a][4] + " mb");
                cir.addTo(map);
                a++;
            });
            

            
        </script>
    </body>
</html>
