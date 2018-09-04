<?php
$mysql_hostname = "localhost";
$mysql_user = "deb78448_knc";
$mysql_password = "knccnkknc";
$mysql_database = "deb78448_knc";
$prefix = "";
$bd = mysql_connect($mysql_hostname, $mysql_user, $mysql_password) or die("DBerror");
$con = mysql_select_db($mysql_database, $bd) or die("QueryError");



include( 'function.php');
// settings
$max_file_size = 2000 * 2000; // 200kb
$valid_exts = array('jpeg', 'jpg', 'png', 'gif');
// thumbnail sizes
$sizes = array(250 => 250);

if ($_SERVER['REQUEST_METHOD'] == 'POST' AND isset($_FILES['image'])) {
    if ($_FILES['image']['size'] < $max_file_size) {
        // get file extension
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $valid_exts)) {
            /* resize image */
            foreach ($sizes as $w => $h) {
                $files[] = resize($w, $h);
            }
        } else {
            $msg = 'Geen geldig bestand';
        }
    } else {
        $msg = 'De afbeelding is te groot';
    }
}
?>


<!doctype html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Inschrijven installatie</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="wrap">
            <?php if (isset($msg)): ?>
                <p class='alert'><?php echo $msg ?></p>
            <?php endif ?>
            <img src="logo.png" alt="KennisCloud"><br>
            <br>
            <form action="" method="post" enctype="multipart/form-data">
                Naam:
                <input type="text" name="firstname">
                <br>
                Email:
                <input type="text" name="email">
                <br>
                Geïnteresseerd in:<br>
                <br>
                <br>
                <h3><span>
                        <?php
                        $con = mysqli_connect("localhost", "deb78448_knc", "knccnkknc", "deb78448_knc");
//IMPORTANTE TANNE!!! HIER IS FORMULIERQUERY! Haalt uit array betreffende tabel!
//$query = "SELECT cat FROM kcevent";
//$result = mysql_query($query);
                        $query = "SELECT * FROM current";
                        $result = mysql_query($query);

                        while ($row = mysql_fetch_assoc($result)) {
                            $temp = $row['current'];
                        }

                        $sql2 = "SELECT cats FROM events WHERE id='$temp'";
                        $result2 = mysql_query($sql2);
                        while ($row2 = mysql_fetch_assoc($result2)) {
                            $temp2 = $row2['cats'];
                            //echo "<input type='checkbox' name='formDoor[]' value='{$temp}' />&nbsp&nbsp{$temp}<br/><br>";
                        }

//finally
                        $currentevent = $temp;
                        $bloop = explode(',', $temp2);
                        $catar = array_filter($bloop, create_function('$a', 'return trim($a)!=="";'));
//$catar = array_filter($bloop);


                        for ($i = 0; $i < count($catar); $i++) {
                            echo "<input type='checkbox' name='formDoor[]' value='{$catar[$i]}' />&nbsp&nbsp{$catar[$i]}<br/><br>";
                        }
                        ?>
                    </span></h3><br><br> Upload afbeelding/maak foto:
                <input type="file" name="image" accept="image/*" /><br><br><br>
                <input type='checkbox' name='useemail' value='yes' />&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Als u dit aanvinkt, houden wij u op de hoogte n.a.v. deze bijeenkomst.<br />
                <input type="submit" value="Verstuur" />
            </form>
            <?php
// show image thumbnails
            if (isset($files)) {
                foreach ($files as $image) {



                    $con = mysqli_connect("localhost", "deb78448_knc", "knccnkknc", "deb78448_knc");
// Check connection
                    if (mysqli_connect_errno()) {
                        echo "Failed to connect to MySQL: " . mysqli_connect_error();
                    }

// escape variables for security
                    $firstname = mysqli_real_escape_string($con, $_POST['firstname']);
                    $email = mysqli_real_escape_string($con, $_POST['email']);
                    $image2 = "http://www.kenniscrowd.nl/kenniscrowd/" . $image;
                    $tags = $_POST['formDoor'];
                    $tagsused = implode(",", $tags);

                    if (!empty($othercat)) {
                        $tagsused = $tagsused . ',' . $othercat;
                    }

                    $useemail = mysqli_real_escape_string($con, $_POST['useemail']);

                    $sql = "INSERT INTO user (naam, emailadres, afbeelding, tags, usecontact,elink)
VALUES ('$firstname', '$email','$image2','$tagsused','$useemail',$currentevent)";

                    if (!mysqli_query($con, $sql)) {
                        die('Error: ' . mysqli_error($con));
                    }
                    echo '<script type="text/javascript">window.location = "geupload.html";</script>';
                }
            }
            mysqli_close($con);
            ?>
        </div>
    </body>
</html>