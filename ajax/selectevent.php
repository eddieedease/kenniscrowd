<?php
$servername = "localhost";
$username = "deb78448_knc";
$password = "knccnkknc";
$dbname = "deb78448_knc";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$eventchoise = $_POST['eventchoise'];


$sql1 = "UPDATE current SET current='$eventchoise'";


if ($conn->query($sql1) === TRUE) {
    echo "Huidig evenement succesvol veranderd";
} else {
    echo "Error: " . $sql1 . "<br>" . $conn->error;
}

$conn->close();

?>