<?php

/**
 * @author Chris & Robert Whetzel
 * @copyright 2010
 */

session_start();


if (!isset($_SESSION['authUser'])) {
	header("Location: login.php");
} else {

require 'lib/db.php';

$KoolControlsFolder = "KoolControls";

require $KoolControlsFolder."/KoolAjax/koolajax.php";
    
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">


<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />

	<title>BUMC Admin</title>
  <script src="js/regadmin.js" type="text/javascript"></script>
  <link href="css/regadmin.css" rel="stylesheet" type="text/css" />
  <link rel="shortcut icon" href="images/site.ico">
  
</head>

<body>
<div id="header" class="header"></div>
<div class="page">
<?php
require "header.php";
?>
  <div id="containerHolder" class="contHolder">
  	<div class="leftContainer">
  		<?php echo $koolajax->Render(); ?>
  			
  	</div>
  	<div class="rightContainer">
    </div>
    <div style="clear: both;"></div>
  </div>
</div>
</body>
</html>
<?php
}
?>