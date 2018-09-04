<?php
	$mysql_hostname = "localhost";
	$mysql_user = "deb78448_knc";
	$mysql_password = "knccnkknc";
	$mysql_database = "deb78448_knc";
	$prefix = "";
	$bd = mysql_connect($mysql_hostname, $mysql_user, $mysql_password) or die("DBerror");
	$con = mysql_select_db($mysql_database, $bd) or die("QueryError");
	$query = "SELECT * FROM current";
	$result = mysql_query($query);
	
	while($row = mysql_fetch_assoc($result))
	{
		$temp = $row['current'];
	}
	//TODO ----------------  SUCCES, variable below = array of the current IDnumber!
	$currentid = $temp;
	$sql2 = "SELECT cats FROM events WHERE id='$currentid'";
	$result2 = mysql_query($sql2);
	while($row2 = mysql_fetch_assoc($result2))
	{
		$temp2 = $row2['cats'];
		//echo "<input type='checkbox' name='formDoor[]' value='{$temp}' />&nbsp&nbsp{$temp}<br/><br>";
	}
	
	//finally
	$currentevent = $temp;
	$bloop = explode( ',', $temp2 );
	$catar = array_filter($bloop, create_function('$a','return trim($a)!=="";'));
	$catar = array_filter($bloop);
	//echo count($catar);
	
	
	//TODO ----------------  SUCCES, variable below = array of the current Activities!
	$currentcats = $catar;
	
	//for ($i = 0; $i < count($catar); $i++) {
    //	echo "<input type='checkbox' name='formDoor[]' value='{$catar[$i]}' />&nbsp&nbsp{$catar[$i]}<br/><br>";
	//}
	
	//TODO starting with 3!
	$sql3 = "SELECT * FROM user WHERE elink='$currentid'";
	
	$result3 = mysql_query($sql3);
	$pretags = array();
	
	//maak the arrays aan
	$usernameArray = array();
	$photolinkArray = array();
	$tagsArray = array();
	
	
	$indexxkey = 0;
	
	while($row3 = mysql_fetch_assoc($result3))
	{	//echo "<input type='checkbox' name='formDoor[]' value='{$temp3}' />&nbsp&nbsp{$temp3}<br/><br>";
		$usernameArray[$indexxkey] = $row3['naam'];
		$photolinkArray[$indexxkey] = $row3['afbeelding'];
		$pretags[$indexxkey] = $row3['tags'];
		$bloop2 = explode( ',', $pretags[$indexxkey] );
		$catar2 = array_filter($bloop2, create_function('$a','return trim($a)!=="";'));
		$catar2 = array_filter($bloop2);
		$tagsArray[$indexxkey] = $catar2;
     	$indexxkey++;
	}
	//finally
	//testing
	//echo $currentcats[0];
	//echo $usernameArray[0];
	//echo $photolinkArray[0];
	//echo $tagsArray[0][0];
	//works wonderful
	?>
  
<script type='text/javascript'>
		<?php
				//$php_array = array('abc','def','ghi');
				$js_array1 = json_encode($currentcats);
				echo "var catsArray = ". $js_array1 . ";\n";
				
				$js_array2 = json_encode($usernameArray);
				echo "var usernameArray = ". $js_array2 . ";\n";
				
				$js_array3 = json_encode($photolinkArray);
				echo "var photolinkArray = ". $js_array3 . ";\n";
				
				$js_array4 = json_encode($tagsArray);
				echo "var tagsArray = ". $js_array4 . ";\n";
				
				
				// TODO - variabelen 'currentCats' - 'catsArray' - 'usernameArray' - 'photoLink' - 'tagsArray'
				// Array of current catagories
				echo 'console.log(catsArray);';
				
				//User Arrays
				echo 'console.log(usernameArray);';
				echo 'console.log(photolinkArray);';
				// Kijk uit! Onderstaande met index geeft een Array terug!
				echo 'console.log(tagsArray);';
				
		?>
</script>