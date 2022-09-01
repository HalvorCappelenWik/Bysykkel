<!DOCTYPE html>
<html>
    <head>
        <style>
            table, td {
            border: 1px solid;
            color: black;
            font-family: times;
            width: 50%;
            height: 20px;
            text-align: center;
            }
            tr:nth-child(even) {
                background-color: #f2f2f2;
            }
            th {
            background-color: #F0F8FF;
            color: black;
            }
        </style>
        <title>INF115 - CE3</title>
    </head>
    <body>
        <h1>INF115 - Compulsory exercise 3</h1>

            <?php
            /*
                Database configuration
            */

            // Connection parameters
            $host 		= 'localhost';
            $user 		= 'root';
            $password 	= '';
            $db 		= 'bysykkel';

            // Connect to the database
            $conn = mysqli_connect($host, $user, $password, $db);

            // Connection check
            if(!$conn) {
                exit('Error: Could not connect to the database.');
            }

            // Set the charset
            mysqli_set_charset($conn, 'utf8');
            ?>

        <h1> Task 1 </h1>
            <h2> a) </h2>

            <?php
            # Lager 2 variabler som holder mitt navn og student id, og printer i italics. 
            $navn = "Halvor Cappelen Wik";
            $studentID = "hwi039";
            echo "<I> $navn </I> <br>";
            echo "<I> $studentID </I>";  
            ?>

            <h2> b) </h2>
            <!-- Lager et form som tar inn phonenumer og email, med required attribute slik at de må fylles
                før submitting the from. -->
            <form action = "?" method = "post">
                <label for = "phonenumber"> Phone number: </label><br>
                <input type = "number" Name = "phonenumber" id = "phonenumber" required><br>

                <label for = "email"> Email: </label><br>
                <input type = "text" Name = "email" id = "email" required><br>

                <input type = "submit" value = "Submit">
                <input type = "reset" value = "Reset">
            </form> <br> 

            <?php 
            # Hvis variablene er satt printer vi ut. 
            if (isset($_POST['phonenumber']) && isset($_POST['email'])) {
                $phonenumber = $_POST['phonenumber'];
                $email = $_POST['email'];

                echo "<b> $phonenumber </b> <br>";
                echo "<b> $email </b>";
            }
            ?>
            
            <h2> c) </h2>

            <?php 
            # Validerer tlf nummer ved å sjekke om det består av tall (er numeric) og om det har lengde 8. 
            if (isset($_POST['phonenumber'])) {
                $phonenumber = $_POST['phonenumber'];
                if (is_numeric($phonenumber) and strlen($phonenumber) == 8) {
                    echo "$phonenumber - valid <br>";
                }
                else {
                    echo "$phonenumber - not valid <br>";
                }
            }

            # Validerer email ved å dele det opp å bruke preg_match for å sjekke om firstname og lastname har stor
            # bokstav, sjekker deretter om den inneholder "@" med strpos function. 
            if (isset($_POST['email'])){
                $email = $_POST['email'];
                $emailtobechecked = str_replace('.','@',$email);
                $emailparts = explode('@', $emailtobechecked);
                if (preg_match('~^\p{Lu}~u', $emailparts[0]) and preg_match('~^\p{Lu}~u', $emailparts[1]) and strpos($email,'@')) {
                    echo "$email - valid <br>";
                } 
                else {
                    echo "$email - not valid <br>";
                }
            }
            ?>
            
            
            
        
        <h1> Task 2 </h1>
            <h2> a) </h2>

            <?php

            # Lager en spørring 
            $sql_query1 = "UPDATE users SET name = 'Tore Antonsen' WHERE user_id = 20";

            # Forsøker å gjøre spørringen på databasen 
            try {
                $conn -> query($sql_query1);
                echo "SQL query1 sucsessful!";
            }
            # Hvis en exception er fanget kjører denne koden og vi henter ut error meldiongen. 
            catch (Exception $ex) {
                echo "Error when perfomring SQL Query 1. <br> Error message: ". $ex -> getMessage();
            }
            echo "<br>";

            $sql_query2 = "UPDATE users SET user_ID = 21 WHERE user_id = 20"; 
            try {
                $conn -> query($sql_query2);
                echo "SQL query2 sucsessful!";
            }
            catch (exception $error) {
                echo "Error when perfomring SQL Query 2. <br> Error message: ". $error -> getMessage();
            }
            ?>

            <h2> b) </h2>

            <?php
            # Lager spørring og henter ut resultat 
            $sql = "SELECT b.bike_id, b.name as bike_name, s.name as station_name 
                from bikes as b INNER JOIN stations as s 
                ON b.station_id = s.station_id 
                WHERE b.status = 'Active' ";
            $result = $conn->query($sql);

            # Definerer table 
            echo "<table border = '1'> 
            <tr> 
                <th> Bike ID </th> 
                <th> Bike Name </th> 
                <th> Station Name </th> 
            </tr>";

            # fyller opp table med resultat fra spørringen
            while($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                echo "<td> " . $row["bike_id"] . " </td> ";
                echo "<td> " . $row["bike_name"] . " </td> ";
                echo "<td> " . $row["station_name"] . " </td>"; 
                echo "</tr>"; 
            }
            echo "</table>";
            ?>

            <h2> c) </h2>

            <?php
            # lager spørring og henter ut resultat 
            $sql = "SELECT SUM(`TABLE_ROWS`) as totalNumberOfRows 
            FROM INFORMATION_SCHEMA.TABLES 
            WHERE TABLE_SCHEMA='bysykkel'";
            $result = mysqli_query($conn,$sql);

            # Printer ut resultat 
            while($row = mysqli_fetch_array($result)) {
                echo "<b> Total number of rows: </b> " . $row["totalNumberOfRows"];
            }
            ?>

        <h1> Task 3 </h1>
            <h2> a) </h2>
            
            <?php
            # Lager spørring og henter ut resultat
            $sql = "SELECT users.name, subscriptions.start_time, COUNT(subscriptions.user_id) as numberOfSubs
            FROM users, subscriptions 
            WHERE users.user_id = subscriptions.user_id 
            AND subscriptions.start_time > '2020-01-01'
            GROUP BY subscriptions.user_id";
            $result = mysqli_query($conn,$sql);
            

            # Definerer table 
            echo "<table border = '1'> 
            <tr> 
            <th> Name </th> 
            <th> Subscritpion start time </th> 
            <th> # of Subscriptions </th> 
            </tr>";

            # Fyller opp table med resultat fra sprringen 
            while ($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                echo "<td> " . $row["name"] . " </td> ";
                echo "<td> " . $row["start_time"] . " </td> ";
                echo "<td> " . $row["numberOfSubs"] . " </td>"; 
                echo "</tr>"; 
            }
            echo "</table>";
            ?>

            <h2> b) </h2>

            <?php 
            # Lager spørring og henter ut resultat 
            $sql = "SELECT bikes.name as bikeName, bikes.status, stations.name as stationName
            from bikes, stations, trips 
            WHERE trips.bike_id = bikes.bike_id 
            AND trips.start_station = stations.station_id
            ORDER BY count(bikes.station_id) desc
            LIMIT 1";
            $result = mysqli_query($conn,$sql);

            # Definerer table 
            echo "<table border = '1'> 
            <tr> 
            <th> Bike Name </th> 
            <th> Bike Status </th> 
            <th> Last start station </th> 
            </tr>";

            # Fyller opp i table med resultat fra spørring 
            while($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                echo "<td> " . $row["bikeName"] . " </td> ";
                echo "<td> " . $row["status"] . " </td> ";
                echo "<td> " . $row["stationName"] . " </td>"; 
                echo "</tr>"; 
            }
            echo "</table>";
            ?>

            <h2> c) </h2>

            <?php 
            # Lager spørring og henter ut resultat 
            $sql = "SELECT users.user_id, users.name,
            count(case year(subscriptions.start_time) when 2018 then 1 else null end) as '2018',
            count(case year(subscriptions.start_time) when 2019 then 1 else null end) as '2019',
            count(case year(subscriptions.start_time) when 2020 then 1 else null end) as '2020',
            count(case year(subscriptions.start_time) when 2021 then 1 else null end) as '2021'
            FROM `users` INNER JOIN subscriptions on users.user_id = subscriptions.user_id GROUP BY users.user_id";
            $result = mysqli_query($conn,$sql);

            # Definerer table 
            echo "<table border = '1'>
            <tr>
            <th> User ID </th> 
            <th> User Name </th> 
            <th> 2018 </th> 
            <th> 2019 </th> 
            <th> 2020 </th> 
            <th> 2021 </th> 
            </tr>";

            # Fyller table med resultat fra spørringen 
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['user_id'] . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['2018'] . "</td>";
                echo "<td>" . $row['2019'] . "</td>";
                echo "<td>" . $row['2020'] . "</td>";
                echo "<td>" . $row['2021'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            ?>


        
        <h1> Task 4 </h1>
        <!-- Lager en selector med alle de forskjellige subscription typene -->
        <form action="?", method="POST">
            <label for="subscriptions">Choose a subscription:</label>
            <select name="subscriptions" id="subscriptions">
                <option value=1>Day</option>
                <option value=2>Week</option>
                <option value=3>Month</option>
                <option value=4>Year</option>
            </select> 
            <br>
            <input type="submit" value="Submit">
        </form>

        <?php
        # Hvis en subscription type er satt, utfører vi en spørring hvor vi henter ut info om denne 
        # subscription typen. 
        if (isset($_POST['subscriptions'])) {
            $subscriptiontype = $_POST['subscriptions'];

            $sql = "SELECT type, 
            COUNT(type) as numOfSubscriptionType, 
            ROUND((COUNT(type)*100/ (SELECT COUNT(*) FROM subscriptions))) as percent
            FROM subscriptions
            WHERE type = $subscriptiontype";
            
            $result =  mysqli_query($conn,$sql);
            
            # Definerer table 
            echo "<table border = '1'>  
            <tr> 
            <th> Subscription Type </th> 
            <th> # of Subscriptions </th> 
            <th> % of all Subscriptions </th> 
            </tr>";

            # Fyller table med resultat fra spørringen. 
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['type'] . "</td>";
                echo "<td>" . $row['numOfSubscriptionType'] . "</td>";
                echo "<td>" . $row['percent']. "%" . "</td>";
                echo "</tr>";
            }
            echo "<br> </table>";
        }
        ?>
    </body>
</html>
