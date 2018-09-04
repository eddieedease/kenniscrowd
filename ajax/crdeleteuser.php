<?php


$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
@$formdata = $request->formdata;
@$aantal = $request->aantal;
//$loginDetails = array('formdata' =>$formdata);
$deleteuserAr = array('formdata' => $formdata, 'aantal' => $aantal);

$a = json_encode($deleteuserAr);

// we need 
$testArray = json_decode($a, true);

//new array 
$stack = array();
//print count($testArray[formdata]);
//Working :)
for ($i = 0; $i < $aantal; ++$i) {
    if ($testArray[formdata][$i] == !false) {
        array_push($stack, $testArray[formdata][$i]);
        //echo $testArray[formdata][$i];
    }
}
//echo count($stack);
$stringer = implode(",",$stack);
//echo $stringer;


//DB-magic
include '../includes/db.php';

// connect to the database
$dbh = new PDO("mysql:host=$hostname;dbname=$db_name", $username, $password);
// a query get all the records from the users table
$sql = 'DELETE from user WHERE id IN ('.$stringer.');';
// use prepared statements, even if not strictly required is good practice
$stmt = $dbh->prepare( $sql );
// execute the query
$stmt->execute();
// final callback
$dbh = null;
echo $a;
?>
