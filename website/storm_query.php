<?php 
    session_start();
    if (isset($_SESSION['x'])) {
        if (basename($_SERVER['PHP_SELF']) != $_SESSION['x']) {
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
            
            $connect = mysqli_connect('localhost', '********', '********');
            if(!$connect){
                die("Couldn't connect.");
            }
            $q_arr = explode(",", $q);
            mysqli_select_db($connect, "********");
            $query = ("SELECT * FROM hurricanes WHERE name=". "'".$q_arr[0]."'" . "AND year=". $q_arr[1]);
            $result = mysqli_query($connect, $query);

            

            while($row = mysqli_fetch_array($result)) {
                $name = $row['name'];
                $year = $row['year'];
                $coords = strval($row['coords']);
                $data = strval($row['data']);
                
            }
            mysqli_close($connect);
            $_SESSION['name'] = $name;
            $_SESSION['year'] = $year;
            $_SESSION['coords'] = $coords;
            $_SESSION['data'] = $data;
        ?>  
    </body>

</html>


