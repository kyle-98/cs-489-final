<?php 
    session_start();
    $_SESSION['y'] = true;

    if (!isset($_SESSION['y'])) {
        session_destroy();
        
     }
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="/styles/main_style.css">
    <link rel="stylesheet" href="styles/datestats_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tingle/0.16.0/tingle.min.css" integrity="sha512-b+T2i3P45i1LZM7I00Ci5QquB9szqaxu+uuk5TUSGjZQ4w4n+qujQiIuvTv2BxE7WCGQCifNMksyKILDiHzsOg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tingle/0.16.0/tingle.min.js" integrity="sha512-2B9/byNV1KKRm5nQ2RLViPFD6U4dUjDGwuW1GU+ImJh8YinPU9Zlq1GzdTMO+G2ROrB5o1qasJBy1ttYz0wCug==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
    <!--Header-->
    <div class="header" id="header">
            <div id="main-head-container" style="height:180px;display:table;width:100%;text-align:center;">
                <span id="main-head-text" style="font-size:37px;font-weight:bold;">Date Stats</span>
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

            function selectType(type){
                if (type == "") {
                    document.getElementById("variables").innerHTML = "";
                    return;
                } else {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("variables").innerHTML = this.responseText;
                        }
                    };
                    xmlhttp.open("GET","chart_query.php?q="+type,true);
                    xmlhttp.send();
                }
            }

        </script>

        <?php
            $hpy_array = $_SESSION['hpy'];
            $q = $_SESSION['q'];
            
            $connect = mysqli_connect('localhost', '******', '******');
            if(!$connect){
                die("Couldn't connect.");
            }
            
            mysqli_select_db($connect, "******");
            $query = "SELECT COUNT(name) hpy, year FROM hurricanes GROUP BY year";
            $result = mysqli_query($connect, $query);
            $temp_all_values = [];

            while($row = mysqli_fetch_array($result)) {
                array_push($temp_all_values, $row['hpy']);
            }

            $temp_all_cleaned = implode(",", $temp_all_values);
            mysqli_close($connect);
        ?>
        <div id="top-content">
            <div id="variables"></div>
            
            <div id="selectors">
                <label for="type-select">Sort By:</label>
                <select name="type-select" id="type-select">
                    <option value="all">Show All</option>
                    <option value="td">Tropical Depression</option>
                    <option value="ts">Tropical Storm</option>
                    <option value="hu">Hurricane</option>
                    <option value="mhu">Major Hurricane</option>

                </select>
                <button id="type-submit" onclick="selectType(document.getElementById('type-select').value);setTimeout(function(){window.location.reload()},350);">Go</button>
            </div>
        </div>
        
        <script>
            $(function(){
                if (localStorage.getItem('type-select')){
                    $("#type-select option").eq(localStorage.getItem('type-select')).prop('selected', true);
                }
                $("#type-select").on('change', function(){
                    localStorage.setItem('type-select', $('option:selected', this).index());
                });
            });
        </script>

        <div id="chart-helpicon">
            <a id="help-1" onclick="help_popup.open();">
                <img id="help-icon" src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e4/Infobox_info_icon.svg/1024px-Infobox_info_icon.svg.png" height="25" width="25">
            </a>    
        </div>

        <br><br>
        <div id="bottom-content">
            <canvas id="bar-chart" width="350px" height="100px"></canvas>
            <script>
                var hpy_string="<?php echo $hpy_array;?>";
                var hpy_array = [];
                if (hpy_string == ""){
                    hpy_string = "<?php echo $temp_all_cleaned;?>";
                    hpy_array = hpy_string.split(",");
                    for(var i = 0; i < hpy_array.length; i++){
                        hpy_array[i] = Number(hpy_array[i]);
                    }
                }
                
                hpy_array = hpy_string.split(",");
                for(var i = 0; i < hpy_array.length; i++){
                    hpy_array[i] = Number(hpy_array[i]);
                }
                var t = "<?php echo $q;?>";
                var type_dict = {
                    "all":"Tropical Cyclones",
                    "td":"Tropical Depressions",
                    "ts":"Tropical Storms",
                    "hu":"Hurricanes",
                    "mhu":"Major Hurricanes"
                };

                var chart_type = "";
                if (t == ""){
                    t = "all";
                    chart_type = type_dict[t];
                }
                else{
                    if (type_dict[t]){
                        chart_type = type_dict[t];
                    }
                }

            </script>
            <div class="help-data">
                <h1>Extra Information</h1>
                <p>
                    The picture below depicts the saffir simpson scale and the wind speeds that correlate with each classification of storm.
                    <br><br>
                    <img src="/pictures/saffir_simpson_scale.png">
                </p>
            </div>

            <script>
                var help_popup = new tingle.modal({
                    footer: true,
                    closeMethods: ['button', 'escape'],
                    closeLabel: "Close"
                });

                help_popup.setContent(document.querySelector('.help-data').innerHTML);
                help_popup.addFooterBtn('Close', 'help-popup-btn', function() {
                    help_popup.close();
                });
            </script>

            

            <script>
                new Chart(document.getElementById("bar-chart"), {
                    type: 'bar',
                    data: {
                    labels: ["2000", "2001", "2002", "2003", "2004", "2005", "2006", "2007", "2008", "2009", "2010", "2011", "2012", "2013", "2014", "2015", "2016", "2017", "2018", "2019", "2020", "2021"],
                    datasets: [
                        {
                        label: "Number of Storms",
                        backgroundColor: ['#bce4d8', '#aedcd5', '#a1d5d2', '#95cecf', '#89c8cc', '#7ec1ca', '#72bac6', '#66b2c2', '#59acbe', '#4ba5ba', '#419eb6', '#3b96b2', '#358ead', '#3586a7', '#347ea1', '#32779b', '#316f96', '#2f6790', '#2d608a', '#2c5985', "#264E75", "#113A60"],
                        data: hpy_array
                        }
                    ]
                    },
                    options: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: `Number of ${chart_type} Per Year`,
                        fontColor: 'black',
                        fontSize: 20
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                fontColor: 'black',
                                fontSize: 16
                            },
                        }],
                        xAxes: [{
                            ticks: {
                                fontColor: 'black',
                                fontSize: 16
                            },
                        }]
                    }
                    }
                });
            </script>
            <div id="description">
                <h2>Chart Information</h2>
                <p id="chart-explain">
                    The chart above shows the number of tropical cyclones and the variations of them depicted by the saffir simpson scale.
                    Each option in the drop down sorts by the category of tropical cyclone that is indicated.  The saffir simpson scale bases 
                    intensity of a storm off of wind speed alone and disregards pressure.  While this scale does not necessarily convey the damage and impact of a storm properly, 
                    this scale is still used by NOAA and weather officals to determine the intensity of storms today.
                </p>
            </div>
        </div>
</body>



</html>
