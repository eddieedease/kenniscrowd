<?php

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

@$naam = $request->naam;
@$email = $request->email;
@$catstring = $request->catstring;
@$elink = $request->elink;
@$imglink = $request->imglink;
@$vervolg = $request->vervolg;

if ($vervolg == 1){
    $vervolg = "yes";
}



$postar = array('naam' => $naam, 'email' => $email, 'catstring' => $catstring, 'elink' => $elink, 'imglink' => $imglink,'vervolg' => $vervolg);

$a = json_encode($postar);

// we need it back in array form
$allinone = json_decode($a, true);

//user form expects comma seperated values
//$naam = $allinone[eventnaam];

$newimglink = "http://www.kenniscrowd.nl/kenniscrowd/uploads/" . $imglink;
//DB-magic
include '../includes/db.php';

// connect to the database
$dbh = new PDO("mysql:host=$hostname;dbname=$db_name", $username, $password);
// a query get all the records from the users table

$sql = "INSERT INTO user (afbeelding, naam, emailadres,tags,usecontact, elink)
VALUES ('$newimglink','$naam','$email','$catstring','$vervolg','$elink')";

// use prepared statements, even if not strictly required is good practice
$stmt = $dbh->prepare($sql);
// execute the query
$dbh = null;
$stmt->execute();

// final callback
?>
