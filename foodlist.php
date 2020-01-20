<?php
session_start();


$foodList;
$totalFoodTypes;
$i;

$eventID = $_GET["e"];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />

	<title>BUMC Scrapbook Crop Registratrion</title>
  <link href="registration.css" rel="stylesheet" type="text/css" />
  <script src="registration.js" type="text/javascript"></script>
  
</head>
<body>
<div style="padding-left: 15px;">
  <?php
  $foodList = array("Entr�e","Misc","Snacks","Sweets","Drinks");
  $totalFoodTypes = "5";
  require 'lib/db.php';
  
  for($i = 0; $i <= $totalFoodTypes; $i++)
  {
    echo "<p class='title' style='text-align: left;'>";
    echo $foodList[$i];
    echo "</p>";
    $result = db::get_connection("SELECT Food FROM registration WHERE FoodCategory='".$foodList[$i]."' AND EventID='".$eventID."'");
    if ($result)
    {
      echo "<ul>";
      while($row = mysqli_fetch_array($result))
      {
        if ($row['Food'] != "")
        {
          echo "<li><label class='l2'>";
          echo $row['Food'];
          echo "<br />";
          echo "</label></li>";
        }
      }
      echo "</ul>";
    } else {
    	echo "<p>Couldn't connect to the database. </p>";
    	echo mysqli_error($result);
//      mysqli_close($db_con);
    }
  }
//  mysqli_close($db_con);
  ?>
</div>
</body>
</html>