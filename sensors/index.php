<?php



$servername = "localhost";
$username = "root";
$password = "Yahya8523!";
$dbname = "test_sen";

$temp1 = $_GET["t1"];
$temp2 = $_GET["t2"];
$temp3 = $_GET["t3"];
$hum = $_GET["h"];
$voltage = $_GET["v"];
$current = $_GET["c"];
$time = $_GET["time"];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "INSERT INTO sensors (temp1, temp2, temp3, Hum, voltage, current, time) 
VALUES ($temp1, $temp2, $temp3, $hum, $voltage, $current, $time)";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully <br>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

echo "Connected Successfully.";
?>