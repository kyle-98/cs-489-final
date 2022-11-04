<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="/styles/main_style.css">
        <link rel="stylesheet" href="/styles/tempcorr_style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tingle/0.16.0/tingle.min.css" integrity="sha512-b+T2i3P45i1LZM7I00Ci5QquB9szqaxu+uuk5TUSGjZQ4w4n+qujQiIuvTv2BxE7WCGQCifNMksyKILDiHzsOg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-annotation/1.4.0/chartjs-plugin-annotation.min.js" integrity="sha512-HrwQrg8S/xLPE6Qwe7XOghA/FOxX+tuVF4TxbvS73/zKJSs/b1gVl/P4MsdfTFWYFYg/ISVNYIINcg35Xvr6QQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tingle/0.16.0/tingle.min.js" integrity="sha512-2B9/byNV1KKRm5nQ2RLViPFD6U4dUjDGwuW1GU+ImJh8YinPU9Zlq1GzdTMO+G2ROrB5o1qasJBy1ttYz0wCug==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
        <script> Chart.register({id: 'chartjs-plugin-annotation'});</script>
    </head>

    <body>
        <!--Header-->
        <div class="header" id="header">
            <div id="main-head-container" style="height:180px;display:table;width:100%;text-align:center;">
                <span id="main-head-text" style="font-size:37px;font-weight:bold;">Date Correlations</span>
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

        <div id="top-content">
                
        </div>


        <?php
            $connect = mysqli_connect('localhost', '*******', '*******');
            if(!$connect){
                die("Couldn't connect.");
            }
            $data_array = [];
            mysqli_select_db($connect, '*******');
            $query = "SELECT COUNT(name) hpy, year FROM hurricanes GROUP BY year";
            $result = mysqli_query($connect, $query);
            while($row = mysqli_fetch_array($result)){ 
                array_push($data_array, $row['hpy']);
            }
        ?>

        <div id="bottom-content">
            <div id="c1">
                <label id="choice-1-lbl" >Degrees</label>
                <input type="checkbox" value="selected" id="choice-1" onchange="check_select_1(chart1)"/><label id="choice-1-helper" for="choice-1">Placeholder</label>
            </div>
            <div class="help-data">
                <h1>Extra Information</h1>
                <p>
                    The data for this chart comes from <a href="https://www.ncei.noaa.gov/access/monitoring/climate-at-a-glance/global/time-series/atlanticMdr/land_ocean/12/12/2000-2022/data.json" target="_blank">this</a> json file hosted
                        and created by NOAA.  These temperatures come from the upper ocean heat content, first 700 meters in the water, combined with surface air temperature of the water.
                    <br><br>
                    Below is a visualization of where the MDR or main development region is in the Atlantic basin:
                    <img src="/pictures/MDR_region.jpg">
                    <br><br>
                    The chart gets this data from a Python script which tranforms the data from JSON to an array format to allow javascript to process the data for the visualization.
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

            <div id="chart-1-helpicon">
                <a id="help-1" onclick="help_popup.open();">
                    <img id="help-icon" src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e4/Infobox_info_icon.svg/1024px-Infobox_info_icon.svg.png" height="25" width="25">
                </a>    
            </div>

            <canvas id="ocean-heat-bar" width="350px" height="100px" style="margin:50px;"></canvas>
            <script>
                //used in both charts
                const YEAR_DATA = ['2000', '2001', '2002', '2003', '2004', '2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020', '2021'];
                const AVG_DATA = [0.08, 0.32, 0.37, 0.5, 0.63, 0.97, 0.63, 0.48, 0.48, 0.39, 1.2, 0.72, 0.6, 0.71, 0.41, 0.59, 0.85, 0.85, 0.31, 0.5, 0.88, 0.63];

                var a_data_per = [];
                for (var i = 0; i < AVG_DATA.length; i++){
                    a_data_per.push(AVG_DATA[i] * 100);
                }

                //choices for chart 1

                var check_select_1 = (chart) => {
                    if(document.getElementById("choice-1").checked){
                        chart.data.datasets[0].data = a_data_per;
                        chart.data.datasets[0].label = '% Deviance';
                        chart.options.plugins.title.text = 'Upper Ocean Heat Content (MDR) Percent Deviance From Mean';
                        chart.update();
                        document.getElementById('choice-1-lbl').innerHTML = 'Percentage'
                    } else {
                        chart.data.datasets[0].data = AVG_DATA;
                        chart.data.datasets[0].label = 'Deviance in Celsius';
                        chart.options.plugins.title.text = 'Upper Ocean Heat Content (MDR) Deviance From Mean (Celsius)';
                        chart.update();
                        document.getElementById('choice-1-lbl').innerHTML = 'Degrees';
                    }
                    
                };
                

                var chart1 = new Chart(document.getElementById("ocean-heat-bar"), {
                    type: 'bar',
                    plugins: ['chartjs-plugin-annotation'],
                    data: {
                        labels: YEAR_DATA,
                        datasets: [{
                            label: 'Deviance in Celsius',
                            backgroundColor: ['#FF6262', '#FF3D3D', '#FF3636', '#FF2323', '#FF1414', '#D60808', '#FF1414', '#FF2828', '#FF2828', '#FF3232', '#C10909', '#FF0909', '#FF1818', '#FF0C0C', '#FF2F2F', '#FF1919', '#F50909', '#F50909', '#FF3E3E', '#FF2323', '#EC0707', '#FF1414'],
                            data: AVG_DATA
                        }]
                    },
                    options: {
                        plugins: {
                            title: {
                                display: true,
                                text: 'Upper Ocean Heat Content (MDR) Deviance From Mean (Celsius)',
                                color: 'black',
                                font: {
                                    size: 20
                                }
                            }
                        }
                    }
                });
                /*
                    5.67  -> #EA7B7B
                    4.12  -> #FC8D8D
                    6.79  -> #E76F6F
                    9.95  -> #E66161
                    10.24 -> #E65959
                    8.41  -> #E56868
                    10.43 -> #E55454
                    9.48  -> #E66262
                    10.05 -> #E65D5D
                    10.13 -> #E65C5C
                    10.37 -> #E55656
                    10.87 -> #E55151
                    10.94 -> #E54F4F
                    12.6  -> #E54949
                    13.26 -> #E64444
                    15.12 -> #E73E3E
                    13.97 -> #E64141
                    15.88 -> #E83D3D
                    16.72 -> #EA3838
                    17.73 -> #EC3030
                    17.52 -> #EB3232
                    18.67 -> #F02B2B
                */
            </script>

            <div id=c1-explain>
                <h2>Chart Information</h2>
                <p id="chart1-explain">The chart above deals with the upper ocean heat content of the MDR in the Atlantic Ocean.  Upper ocean heat content is total energy stored as heat in the first 700 meters of the ocean.
                    The bars represent the deviance from the average temperature in this region in degrees celsius.  The chart can be changed to percentaged increased as well.  
                    
                    <br><br>Upper ocean heat content is 
                    imporant to the development of tropical cyclones because tropical cyclones use energy stored as heat in the water to form instability in the atmosphere.  The heat from the water warms the air,
                    the warm air rises.  When the warm air rises, there is less air near the surface of the water which causes an area of low pressure under the warm air to form.  This process repeats as the warm air mass 
                    gets heavier and the low pressure gets stronger.  This causes instability in the upper levels of the atmosphere and the area of low pressure will begin to start a spinning formation.  This is why there
                    is a correlation between the upper level ocean heat content and the formation of tropical cyclones.
                </p>
                
            </div>


            <div id="c2">
                <label id="choice-2-lbl" for="choice-2">Count</label>
                <input type="checkbox" value="selected" id="choice-2" onchange="check_select_2(chart2)"/><label id="choice-2-helper" for="choice-2"></label>
            </div>

            <canvas id="storms-devi-bar" width="350px" height="100px" style="margin:50px;"></canvas>
            
            <script>
                //average number of named storms is 14 per Year
                var data_array = (<?php echo json_encode($data_array); ?>);
                var storms_from_avg_per = []; 
                var storms_from_avg = [];
                const AVG = 14;
                for(var i = 0; i < data_array.length; i++){
                    data_array[i] = Number(data_array[i]);
                    if(data_array[i] > 14){
                        let per_grtr = ((data_array[i] - AVG) / data_array[i]) * 100;
                        storms_from_avg_per.push(per_grtr);
                        storms_from_avg.push(data_array[i] - AVG);
                    } else if(data_array[i] < 14) {
                        let per_less = ((data_array[i] - AVG) / data_array[i]) * 100;
                        storms_from_avg_per.push(per_less);
                        storms_from_avg.push(data_array[i] - AVG);
                    } else{
                        storms_from_avg_per.push(0);
                        storms_from_avg.push(0);
                    }
                }

                //choices for chart 1

                var check_select_2 = (chart) => {
                    if(document.getElementById("choice-2").checked){
                        chart.data.datasets[0].data = storms_from_avg_per;
                        chart.data.datasets[0].label = '% Deviance';
                        chart.options.plugins.title.text = 'Tropical Cyclone Formation Percent Deviance From Mean';
                        chart.update();
                        document.getElementById("choice-2-lbl").innerHTML = 'Percentage';
                    } else {
                        chart.data.datasets[0].data = storms_from_avg;
                        chart.data.datasets[0].label = 'Deviance by Count';
                        chart.options.plugins.title.text = 'Tropical Cyclone Formation Deviance From Mean (Count)';
                        chart.update();
                        document.getElementById("choice-2-lbl").innerHTML = 'Count';
                    }
                };

                var chart2 = new Chart(document.getElementById("storms-devi-bar"), {
                    type: 'bar',
                    plugins: ['chartjs-plugin-annotation'],
                    data: {
                        labels: YEAR_DATA,
                        datasets: [{
                            label: 'Deviance by Count',
                            data: storms_from_avg,
                            backgroundColor: ['#FFFFFF', '#FF6C6C', '#5353FF', '#FF5353', '#FF6C6C', '#C00000', '#2424FF', '#FF3636', '#FF3636', '#3636FF', '#FF1919', '#FF2424', '#FF2424', '#FFFFFF', '#2424FF', '#5353FF', '#FF5353', '#FF2D2D', '#FF5353', '#FF1F1F', '#B80000', '#FF1919']
                        
                        }]
                    },
                    options: {
                        plugins: {
                            title: {
                                display: true,
                                text: 'Tropical Cyclone Formation Deviance From Mean (Count)',
                                color: 'black',
                                font: {
                                    size: 20
                                }
                            },
                            autocolors: false,
                            annotation: {
                                annotations: {
                                    average_line: {
                                        type: 'line',
                                        yMin: 0,
                                        yMax: 0,
                                        borderColor: 'rgba(38, 78, 117, 0.7)',
                                        borderWidth: 3,
                                        label: {
                                            borderColor: 'rgba(38, 78, 117, 0.7)',
                                            drawTime: 'afterDatasetsDraw',
                                            color: 'white',
                                            content: `Average Number of Storms 14 -> 0%`
                                        }
                                    }
                                }
                            }
                        }
                    }
                });



            </script>
            
            <div id="c2-explain"> 
                <h2>Chart Information</h2>
                <p id="chart2-explain"> The chart above details the deviance from the mean number of named tropical cyclones in a year in the Atlantic basin.  The average number of named tropical cyclones in a year
                    designated by NOAA is 14 per year.  This chart shows the deviance from that mean by count and percentage.
                    
                    <br><br>Formula for percentage calculation: ((increase - average) / average) * 100
                </p>
            </div>

            <script>
                
            </script>


        </div>
    </body>

</html>
