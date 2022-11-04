<?php 
    session_start();
    if (isset($_SESSION['y'])) {
        if (basename($_SERVER['PHP_SELF']) != $_SESSION['y']) {
             session_destroy();
        }
     }

?>
<!DOCTYPE html>
<html>
    <head>
        <style>
        </style>
    </head>

    <body>
        <?php

            $q = $_GET['q'];
            
            $connect = mysqli_connect('localhost', '******', '******');
            if(!$connect){
                die("Couldn't connect.");
            }
            
            mysqli_select_db($connect, "********");
            if($q == "all"){
                $query = ("SELECT COUNT(name) hpy, year FROM monkas.hurricanes_2 GROUP BY year");
            } else if($q == "td"){
                $query = ("SELECT distinct year, IFNULL((SELECT COUNT(name) hpy FROM hurricanes where year = h.year and highest_wind < 34 GROUP BY year), 0) hpy FROM hurricanes h");
            } else if($q == "ts"){
                $query = ("SELECT distinct year, IFNULL((SELECT COUNT(name) hpy FROM hurricanes where year = h.year and highest_wind >=35 and highest_wind <= 65 GROUP BY year), 0) hpy FROM hurricanes h");
            } else if($q == "hu"){
                $query = ("SELECT distinct year, IFNULL((SELECT COUNT(name) hpy FROM hurricanes where year = h.year and highest_wind >=65 GROUP BY year), 0) hpy FROM hurricanes h");
            } else{
                $query = ("SELECT distinct year, IFNULL((SELECT COUNT(name) hpy FROM hurricanes where year = h.year and highest_wind >=96 GROUP BY year), 0) hpy FROM hurricanes h");
            }
            
            $result = mysqli_query($connect, $query);

            $hpy_array = [];

            while($row = mysqli_fetch_array($result)) {
                array_push($hpy_array, $row['hpy']);
                
            }
            mysqli_close($connect);
            $_SESSION['hpy'] = implode(",", $hpy_array);
            $_SESSION['q'] = $q;
            
        ?>  
    </body>

</html>


