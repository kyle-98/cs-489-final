<!DOCTYPE html>
<html>
    <head>
        <style>
        </style>
    </head>

    <body>
        <?php
            $q = $_GET['q'];
            
            $connect = mysqli_connect('localhost', '*******', '*******');
            if(!$connect){
                die("Couldn't connect.");
            }

            mysqli_select_db($connect, "*******");
            $query = ("SELECT * FROM hurricanes WHERE year=".$q);
            $result = mysqli_query($connect, $query);



            while($row = mysqli_fetch_array($result)) {
            echo "<option value=".$row['name'].">".$row['name']."</option>";
            }
            mysqli_close($connect);

        ?>
    </body>

</html>


