<?php

/**
 * @author Chris & Robert Whetzel
 * @copyright 2010
 */

session_start();


if (!isset($_SESSION['authUser'])) {
	header("Location: login.php");
} else {

$number;
$header;
$subject;
$body;
$footer;

require 'lib/db.php';
    
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">


<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />

	<title>BUMC Admin</title>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
  <link rel="shortcut icon" href="images/site.ico">

  <script src="js/regadmin.js" type="text/javascript"></script>
  <link href="css/regadmin.css" rel="stylesheet" type="text/css" />
  <script src="js/cstmmsgs.js" type="text/javascript"></script>
  <!-- TinyMCE -->
  <script type="text/javascript" src="tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
  <!-- /TinyMCE -->
  <style type="text/css">
  
  .darkTh
  {
    width: 124px;
    padding: 5px;
    border-color: black; 
    border-width: 1px;
    background-color: #C5C5C5;
  }
  .darkCell
  {
    height:125px;
    border-color: black;
    border-width: 1px;
    background-color: #E4E4E4;
  }
  
  
  .cellTop
  {
    border-style: solid none solid solid;
  }
  .cellTopRight
  {
    border-style: solid solid solid solid;
  }
  .cellBottomLeft
  {
    border-style: none none solid solid;
  }
  .cellBottomRight
  {
    border-style: none solid solid solid;
  }
  
  .overLay
  {
    width: 100%;
    height: 100%;
    position: fixed;
    z-index: 10;
    background-color: black;
    opacity:0.3;
    filter:alpha(opacity=30);
    display: none;
  }
  
  </style>
</head>

<body onload="setup()">
<div id="overLay" class="overLay" style="display: none;"></div>
<table id="createNew" bgcolor="white" cellpadding="4" cellspacing="0" style="width: 300px; position: absolute; left: 500px; top: 200px; z-index: 11; display: none;">
  <tr>
    <th class="darkTh cellTop" style="width: 285px;">Create New Email</th>
    <th onclick="javascript:toggleDisplay();" class="darkTh cellTopRight" style="width: 15px; cursor: pointer;">X</th>
  </tr>
  <tr>
    <td colspan="2" class="darkCell cellBottomRight" style="text-align: center;">
    
      <br />
      <br />
      Name: <input id="emailName" type="text" />
      <br />
      <button onclick="javascript:submitNewEmail();">Submit</button>
      <br />
      <br />
      <br />
    
    </td>
  </tr>
</table>
<?php

?>
<div id="header" class="header"></div>
<div class="page">
<?php
require "header.php";
?>
  <div id="containerHolder" class="contHolderSingle">
    <?php
    
    //generate error(if any)
    echo "<div id='viewError' style='width: 100%; display: none;'></div>";
    
    echo "<div id='emailTemplates' style='width: 100%;'></div>";
    
    //filled by ajax, editable subject, header, body, footer
    echo "<div id='emailLayout'></div>";
    
    echo "<input type='hidden' id='tinyCount' value='0'>";
    
    echo "<div id='mass' style='text-align: center;'></div>";
  
    //render mail list
    echo "<div id='emailList'></div>";
    
//    mysqli_close($db_con);
   
    ?>
    <br />
    <div style="clear: both;"></div>
  </div>
</div>
<br />
</body>
</html>
<?php
}
?>