<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.1/dist/leaflet.css" integrity="sha256-sA+zWATbFveLLNqWO2gtiw3HL/lh1giY/Inf1BJ0z14=" crossorigin="" />
        <script src="https://unpkg.com/leaflet@1.9.1/dist/leaflet.js" integrity="sha256-NDI0K41gVbWqfkkaHj15IzU7PtMoelkzyKp8TOaFQ3s=" crossorigin=""></script>
        
        <script src="/heatmap.js"></script>
        <script src="/leaflet-heatmap.js"></script>
        <link rel="stylesheet" href="/styles/main_style.css"/>
        <link rel="stylesheet" href="/styles/heatmap_style.css"/>
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    </head>

    <body>
        <!--Header-->
        <div class="header" id="header">
            <div id="main-head-container" style="height:180px;display:table;width:100%;text-align:center;">
                <span id="main-head-text" style="font-size:37px;font-weight:bold;">Atlantic Tropical Cyclone Formation Heatmap</span>
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

        <div style="padding-bottom: 20px;"></div>

        <div id="top-content">
            <div id="title">
                <h3>Tropical Cyclone Formation Heatmap</h3>
                <p>This map visualizes the areas in which are most common for a tropical cyclone to start in the Atlantic during the hurricane season from the years 2000-2021.</p>
            </div>


        </div>

        <?php
            $connect = mysqli_connect('localhost', '*******', '*******');
            if(!$connect){
                die("Couldn't connect.");
            }

            mysqli_select_db($connect, '*******');
            $query = "SELECT coords FROM hurricanes";
            $result = mysqli_query($connect, $query);
            $coords_array = [];
            while($row = mysqli_fetch_array($result)){
                array_push($coords_array, $row['coords']);
            }
            $final_coords_array = [];
            for ($i = 0; $i < count($coords_array); $i++) {
                $temp_array = explode(",", $coords_array[$i]);
                $temp_coord = $temp_array[0] . $temp_array[1];
                array_push($final_coords_array, $temp_coord);
            }
            $coords_string = implode(",", $final_coords_array);
        ?>

        <div id="map-content">
            <div id="map"></div>
            <script>
                var coords_string = "<?php echo $coords_string;?>";
                var data_array = [];
                var temp = coords_string.split("],[");
                for(var i = 0; i < temp.length; i++){
                    temp[i] = temp[i].replace("[", "");
                    temp[i] = temp[i].replace("]", "");
                    var lat_lng_array = temp[i].split(" ");
                    const coords = {};
                    const coords_obj = Object.create(coords);
                    coords_obj.lat = Number(lat_lng_array[0]);
                    coords_obj.lng = Number(-lat_lng_array[1]);
                    coords_obj.count = 1;
                    data_array.push(coords_obj);
                }


                console.log(data_array);

                window.onload = function(){
                    var data = {
                            max: 1.5,
                            data: data_array
                        };

                        var base_layer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
                            attribution: '',
                            maxZoom: 10,
                            minZoom: 3
                        });

                        var map_config = {
                            "radius": 1.8,
                            "maxOpacity": .75,
                            "scaleRadius": true,
                            "useLocalExtrema": false,
                            latField: 'lat',
                            lngField: 'lng',
                            valueField: 'count'
                        };


                        var heatmap_layer = new HeatmapOverlay(map_config);

                        var map = new L.Map('map', {
                            center: new L.LatLng(30.0, -80.0),
                            zoom: 4,
                            layers: [base_layer, heatmap_layer]
                        });

                        heatmap_layer.setData(data);
                    };
            </script>
        </div>
    </body>


</html>
