<?php

/**
 * @author Robert Whetzel
 * @copyright 2011
 */

//view all emails

session_start();

$cancelProcess = false;

if (!isset($_SESSION['authUser'])) {
  $cancelProcess = true;
	header("Location: login.php");
} else {

  require_once('lib/db.php');
  
  $_SESSION['UserID'];
  
$result = mysql_query("SELECT DISTINCT(EmailAddress) FROM registration WHERE UserID='".$_SESSION['UserID']."' AND EmailAddress != '' AND EmailAddress != 'none' ORDER BY EmailAddress");
if ($result)
{
	while ($row = mysql_fetch_array($result))
	{
	  echo $row['EmailAddress'];
	  echo "<br />";
	}
} else {
	echo "<p>Couldn't connect to the database. </p>";
	echo "<br />";
  mysql_close($db_con);
	echo mysql_error($db_con);
}
  


mysql_close($db_con);




}
?>