<?php
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=exported-data.csv');

$eventcsv = $_GET['elink'];

$conn = mysql_connect('localhost', 'deb78448_knc', 'knccnkknc') or die(mysql_error());
$db = mysql_select_db('deb78448_knc', $conn) or die(mysql_error());

//header to give the order to the browser


//select table to export the data
$select_table = mysql_query("select * from user where elink='$eventcsv'");
$rows = mysql_fetch_assoc($select_table);

if ($rows) {
    getcsv(array_keys($rows));
}
while ($rows) {
    getcsv($rows);
    $rows = mysql_fetch_assoc($select_table);
}

// get total number of fields present in the database
function getcsv($no_of_field_names) {
    $separate = '';

    // do the action for all field names as field name
    foreach ($no_of_field_names as $field_name) {
        if (preg_match('/\\r|\\n|,|"/', $field_name)) {
            //$field_name = '' . str_replace('', $field_name) . '';
        }
        echo $separate . $field_name;

        //sepearte with the comma
        $separate = ';';
    }

    //make new row and line
    echo "\r\n";
}
?>