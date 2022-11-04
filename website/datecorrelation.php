<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="/styles/main_style.css">
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-annotation/1.4.0/chartjs-plugin-annotation.min.js" integrity="sha512-HrwQrg8S/xLPE6Qwe7XOghA/FOxX+tuVF4TxbvS73/zKJSs/b1gVl/P4MsdfTFWYFYg/ISVNYIINcg35Xvr6QQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <style>
            #bottom-content{
                margin: 50px;
            }
        </style>

        <script>Chart.register({id: 'chartjs-plugin-annotation'});</script>
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
            $connect = mysqli_connect('localhost', '******', '******');
            if(!$connect){
            die("Couldn't connect.");
            } 
            $months_array = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            $data_array = [];
            mysqli_select_db($connect, '*****');
            for($i = 0; $i < count($months_array); $i++){
                $query = "SELECT COUNT(name) count FROM hurricanes where starting_month = '{$months_array[$i]}'";
                $result = mysqli_query($connect, $query);
                $data = mysqli_fetch_array($result);
                array_push($data_array, $data['count']);
            }
        ?>

        <script>
            var data_array = (<?php echo json_encode($data_array);?>);
            for(var i = 0; i < data_array.length; i++){
                data_array[i] = Number(data_array[i]);
            }
        </script>

        <div id="bottom-content">
            <canvas id="line-chart" width="350px" height="100px"></canvas>

            <script>
                new Chart(document.getElementById("line-chart"),{
                    type: 'line',
                    plugins: ['chartjs-plugin-annotation'],
                    data: {
                        labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                        datasets: [{
                            label: "Number of Storms Since 2000",
                            data: data_array,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            borderColor: 'rgba(0, 0, 0, 0.3)',
                            fill: true,
                            backgroundColor: 'rgba(102, 178, 194, 0.5)'
                        }]
                    },
                    options: {                        
                        plugins: {
                            title: {
                                display: true,
                                text: "Number of Tropical Cyclones Per Month",
                                color: 'black',
                                font: {
                                    size: 20 
                                }
                            },
                            autocolors: false,
                            annotation: {
                                annotations: {
                                    hurr_sea_start: {
                                        type: 'line',
                                        xMin: 5,
                                        xMax: 5,
                                        borderColor: 'rgba(38, 78, 117, 0.7)',
                                        drawTime: 'beforeDatasetsDraw',
                                        borderWidth: 3,
                                        label: {
                                            enabled: true,
                                            backgroundColor: 'rgba(0, 0, 0, 0.75)',
                                            drawTime: 'afterDatasetsDraw',
                                            color: 'white',
                                            content: `Hurricane Season Start`
                                        }
                                    },
                                    hurr_sea_end: {
                                        type: 'line',
                                        xMin: 10.8,
                                        xMax: 10.8,
                                        borderColor: 'rgba(38, 78, 117, 0.7)',
                                        borderWidth: 3,
                                        label: {
                                            enabled: true,
                                            backgroundColor: 'rgba(0, 0, 0, 0.75)',
                                            drawTime: 'afterDatasetsDraw',
                                            color: 'white',
                                            content: `Hurricane Season End`
                                        }
                                    }
                                }
                            }
                        }
                    } 
                });
            </script>

        </div>




    </body>



</html>
