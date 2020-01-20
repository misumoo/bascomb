<?php

/**
 * @author Chris & Robert Whetzel
 * @copyright 2010
 */

session_start();

$cancelProcess = false;

if (!isset($_SESSION['authUser'])) {
  $cancelProcess = true;
	header("Location: login.php");
} else {

require 'lib/db.php';

$KoolControlsFolder = "KoolControls";

require $KoolControlsFolder."/KoolAjax/koolajax.php";

$i;
$eventName;
$expirationDate;
$UserID = $_SESSION['UserID'];
$getWhere = $_GET['u'];
if($getWhere == "")
{
  $cancelProcess = true;
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">


<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />

	<title>BUMC Admin</title>
  <script src="js/regadmin.js" type="text/javascript"></script>
  <link href="css/regadmin.css" rel="stylesheet" type="text/css" />
  <link rel="shortcut icon" href="images/site.ico">
  <script type="text/javascript">
  
  
  </script>
</head>

<body>
<div id="header" class="header"></div>
<div class="page">
<?php
require "header.php";
?>
  <div id="containerHolder" class="contHolderSingle">
		<?php
    echo "<div style='width:100%'>";
    
    echo "<br />!!!Panels!!!<br /><br />";
    echo "&nbsp;&nbsp;&nbsp;&nbsp;Create New<br /><br />";
    
		$result = db::get_connection("SELECT IsRequired,Left,Right FROM tblPanels WHERE UserID='".$UserID."'");
    if($result)
    {
      //do something with them
    } else {
      echo "No fields added yet.";
    }
		?>
    <div style="clear: both;"></div>
  </div>
</div>
</body>
</html>
<?php
//mysqli_close($db_con);
}
?>