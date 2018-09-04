<?php


require_once '../includes/db.php'; // The mysql database connection script

$eventname = $_POST['eventname'];
$eventpic = $_POST['eventpic'];

$getall = $_POST["check1"] . "," . $_POST["check2"] . "," . $_POST["check3"] . "," . $_POST["check4"] . "," . $_POST["check5"] . "," . $_POST["check6"] . "," . $_POST["check7"] . "," . $_POST["check8"] . "," . $_POST["check9"] . "," . $_POST["check10"] . "," . $_POST["check11"] . "," . $_POST["check12"] . "," . $_POST["check13"] . "," . $_POST["check14"] . "," . $_POST["check15"] . "," . $_POST["check16"];

$cats = preg_replace('/,+/', ',', $getall);


function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$randomkey = generateRandomString(5);
$randomkey2 = generateRandomString(5);


$sql = "INSERT INTO events (eventname, eventpic, cats, sleutel)
VALUES ('$eventname','$eventpic','$cats','$randomkey')";

if ($conn -> query($sql) === TRUE) {
	echo '<h2>Gelukt! Nieuwe bijeenkomst aangemaakt. <a href="http://www.kenniscloud.nl/kenniscrowd/admin.php?n='.$randomkey2.'"> KLIK HIER</a> om terug te keren naar het admin gedeelte.</h2>' ;
} else {
	echo "Error: " . $sql . "<br>" . $conn -> error;
}
?>