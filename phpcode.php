<?php
	$mysql_hostname = "localhost";
	$mysql_user = "deb78448_knc";
	$mysql_password = "knccnkknc";
	$mysql_database = "deb78448_knc";
	$prefix = "";
	$bd = mysql_connect($mysql_hostname, $mysql_user, $mysql_password) or die("DBerror");
	$con = mysql_select_db($mysql_database, $bd) or die("QueryError");
	
	//$query = "SELECT * FROM current";
	//$result = mysql_query($query);
	$sql6 = "SELECT * FROM user";
	$result6 = mysql_query($sql6);
	$num_rows6 = mysql_num_rows($result6);
	$_POST['key'] = $num_rows6;
	echo $_POST['key'];
?>