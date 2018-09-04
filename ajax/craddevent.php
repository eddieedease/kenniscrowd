<?php

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
@$eventnaam = $request->eventnaam;
@$onderwerpen = $request->onderwerpen;
$onderwerpar = array('onderwerpen' => $onderwerpen, 'eventnaam' => $eventnaam);

$a = json_encode($onderwerpar);

// we need it back in array form
$allinone = json_decode($a, true);

//user form expects comma seperated values
$cats = implode(",", $allinone[onderwerpen]);
$naam = $allinone[eventnaam];

//generate random string for key
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$randomkey = generateRandomString(7);

//DB-magic
include '../includes/db.php';

// connect to the database
$dbh = new PDO("mysql:host=$hostname;dbname=$db_name", $username, $password);
// a query get all the records from the users table

$sql = "INSERT INTO events (eventname, cats, sleutel)
VALUES ('$naam','$cats','$randomkey')";

// use prepared statements, even if not strictly required is good practice
$stmt = $dbh->prepare($sql);
// execute the query
$stmt->execute();
$dbh = null;
// final callback
echo $randomkey;
?>
