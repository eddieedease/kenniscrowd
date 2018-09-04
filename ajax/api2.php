<?php

        // set up the connection variables
        $db_name  = 'deb78448_knc';
        $hostname = 'localhost';
        $username = 'deb78448_knc';
        $password = 'knccnkknc';

        // connect to the database
        $dbh = new PDO("mysql:host=$hostname;dbname=$db_name", $username, $password);

        // a query get all the records from the users table
        $sql = 'SELECT naam, emailadres, tags, datum, usecontact, elink, id FROM user';

        // use prepared statements, even if not strictly required is good practice
        $stmt = $dbh->prepare( $sql );

        // execute the query
        $stmt->execute();

        // fetch the results into an array
        $result = $stmt->fetchAll( PDO::FETCH_ASSOC );

        // convert to json
        $json = json_encode( $result );
        $dbh = null;

        // echo the json string
        echo $json;
?>