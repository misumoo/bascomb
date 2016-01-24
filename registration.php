<?php
session_start();

require_once("lib/_classes.php");
require_once("lib/_functions.php");
require('lib/db.php');

$eventID = $_GET["e"];
$capped = false;
$cancelProcess = false;
$full = false;

if($eventID == "" || !$eventID || checkEvent($eventID) == false) {
  $cancelProcess = true;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />

	<title>BUMC Scrapbook Crop Registratrion</title>
  <link href="registration.css" rel="stylesheet" type="text/css" />
  <script src="registration.js" type="text/javascript"></script>
  <link rel="shortcut icon" href="images/site.ico">
  
</head>
<body<?php if(!$cancelProcess) { ?> onload="onLoadFunction();"<?php } ?>>
<?php
  if(!$cancelProcess)
  {
    $capacity = getCapacity($eventID);
    $capdisplay = getCapacityDisplay($eventID, $capacity);

    $totalregistered = totalRegistered($eventID);
    ($totalregistered >= $capacity ? $capped = true : $capped = false);

    if($capped) {
      $registereddisplay = "<p class='p3'>Total Registered: <label class='required'><b>".$totalregistered."/".$capacity."</b></label></p>";
      $registereddisplay .= "<p class='p3'><label class='required'><b>This crop is full.  Our crops are held on the 1st Saturday of March and the 1st Saturday of October.</b></label></p>";
      $full = true;
    } else {
      $registereddisplay = "<p class='p3'>Total Registered: ".$totalregistered."/".$capacity."</p>";
    }

    if(!$capdisplay) {
      $registereddisplay = "";
    }

    echo "<form id='registrationForm' method='post' action='registrationsave.php'>";
    //if $_SESSIONS are set, echo the data (they've already posted it, convenience.) Add a clear all button.
    echo "<table class='form'>
      <tr>
        <td colspan='2' class='center'>
          <p class='title'>BUMC Scrapbook Crop Registration</p>
          <p class='p3'>Susan Austin</p>
          <p class='p3'><a href='https://sites.google.com/site/bascombcrop/'>BUMC Scrapbook Crop</a></p>
          <p class='p3'>6021 Hollow Dr, Woodstock Ga 30189</p>
          <p class='p3'><label class='required'>*</label> <label class='l2'>= Required.</label></p>
          ".$registereddisplay."
        </td>
      </tr>
      <tr>
        <td class='right'><label class='l2'>Name: </label><label class='required'>*</label></td>
        <td class='left'><input id='nameCheck' class='i1'"; if($full) { echo " disabled='Disabled' style:\"background: #CCCCCC\""; } echo " value='"; if(isset($_SESSION['name'])) {echo $_SESSION['name']; } echo "' name='name' type='text'></input></td>
      </tr>
      <tr>
        <td class='right'><label class='l2'>Email Address: </label><label class='required'>*</label></td>
        <td class='left'><input id='emailCheck' class='i1'"; if($full) { echo " disabled='Disabled' style:\"background: #CCCCCC\""; } echo " name='emailaddress' value='"; if(isset($_SESSION['emailaddress'])) {echo $_SESSION['emailaddress']; } echo "' type='text'></input></td>
      </tr>
      <tr>
        <td class='right'><label class='l2'>Confirm Email Address: </label><label class='required'>*</label></td>
        <td class='left'><input id='emailCheck2' class='i1'"; if($full) { echo " disabled='Disabled' style:\"background: #CCCCCC\""; } echo " name='emailaddress2' value='"; if(isset($_SESSION['emailaddress'])) {echo $_SESSION['emailaddress']; } echo "' type='text'></input></td>
      </tr>      
      <tr>
        <td class='right'><label class='l2'>Street Address: </label></td>
        <td class='left'><input id='streetaddressCheck' class='i1'"; if($full) { echo " disabled='Disabled' style:\"background: #CCCCCC\""; } echo " name='streetaddress' value='"; if(isset($_SESSION['streetaddress'])) {echo $_SESSION['streetaddress']; } echo "' type='text'></input></td>
      </tr>
      <tr>
        <td class='right'><label class='l2'>City, State: </label></td>
        <td class='left'><input id='cszCheck' class='i1'"; if($full) { echo " disabled='Disabled' style:\"background: #CCCCCC\""; } echo " name='csz' value='"; if(isset($_SESSION['csz'])) {echo $_SESSION['csz']; } echo "' type='text'></input></td>
      </tr>
      <tr>
        <td class='right'><label class='l2'>Phone #: </label></td>
        <td class='left'><input id='phoneCheck' class='i1'"; if($full) { echo " disabled='Disabled' style:\"background: #CCCCCC\""; } echo " name='phone' value='"; if(isset($_SESSION['phone'])) {echo $_SESSION['phone']; } echo "' type='text'></input></td>
      </tr>
      <tr>
        <td class='right' valign='top'><label class='l2'>How are you paying?: </label><label class='required'>*</label></td>
        <td class='left' valign='top'>
        <select id='paymentCheck' onchange='paymentHandle()' class='selectTable'"; if($full) { echo " disabled='Disabled' style:\"background: #CCCCCC\""; } echo " name='payby'>
          <option value=''></option>
          <option value='paypal'>PayPal</option>
          <option value='check'>Check/Cash</option>
        </select>
        <div id='method'></div>
        </td>
      </tr>
      <tr>
        <td class='right'><label class='l2'>Returning Guest: </label></td>
        <td class='left'>
        <select id='returningguestCheck' class='selectTable'"; if($full) { echo " disabled='Disabled' style:\"background: #CCCCCC\""; } echo " name='returningguest'>
          <option value='No'>No</option>
          <option value='Yes'>Yes</option>
        </select>
        </td>
      </tr>
      <tr>
        <td class='right'><label class='l2'>How did you hear about us?: </label></td>
        <td class='left'><input id='heardaboutCheck' class='i1'"; if($full) { echo " disabled='Disabled' style:\"background: #CCCCCC\""; } echo " name='heardabout' value='"; if(isset($_SESSION['heardabout'])) {echo $_SESSION['heardabout']; } echo "' type='text'></input></td>
      </tr>  
      <tr>
        <td class='right'><label class='l2'>Referred By: </label></td>
        <td class='left'><input id='referredbyCheck' class='i1'"; if($full) { echo " disabled='Disabled' style:\"background: #CCCCCC\""; } echo " value='"; if(isset($_SESSION['referredby'])) {echo $_SESSION['referredby']; } echo "' name='referredby' type='text'></input></td>
      </tr>      
      <tr>
        <td class='right' valign='top'><label class='l2'>Requested Table Buddies: </label></td>
        <td class='left'><textarea id='requestedtablebuddiesCheck' rows='2' style='width: 240px;'"; if($full) { echo " disabled='Disabled' style:\"background: #CCCCCC\""; } echo " name='requestedtablebuddies' type='text'>"; if(isset($_SESSION['requestedtablebuddies'])) {echo str_replace("<br />", "", $_SESSION['requestedtablebuddies']); } echo "</textarea></td>
      </tr>  
      <tr>
        <td class='right' valign='top'><label class='l2'>Note to Hostess: </label></td>
        <td class='left'><textarea id='notetohostessCheck' rows='2' style='width: 240px;'"; if($full) { echo " disabled='Disabled' style:\"background: #CCCCCC\""; } echo " name='notetohostess' type='text'>"; if(isset($_SESSION['notetohostess'])) {echo str_replace("<br />", "", $_SESSION['notetohostess']); } echo "</textarea></td>
      </tr>
      ";
//      
//      <tr>
//        <td class='right' valign='top'><label class='l2'>Food:</label><br /> <label class='isDescription'>Earn $5 off your registration fee by bringing food item(s) for your fellow scrappers.</label><br /><a href='foodlist.php?e=".$eventID."' target='_blank'>Check out what others are bringing</a></div></td>
//        <td class='left' valign='top'>
//          <textarea id='foodCheck' rows='3' style='width: 240px;' name='food' type='text'>"; if(isset($_SESSION['food'])) {echo $_SESSION['food']; } echo "</textarea>
//        </td>
//      </tr>
//                   
//      <tr>
//        <td class='right' valign='top'><label class='l2'>Food Category:</label><br /><label class='isDescription'>If you are bringing something.</label></td>
//        <td class='left' valign='top'>
//          <select id='typeOfFoodCheck' class='selectTable' name='typeOfFood'>
//            <option value=''></option>
//            <option value='Entr�e'>Entr�e</option>
//            <option value='Misc'>Misc</option>
//            <option value='Snacks'>Snacks</option>
//            <option value='Sweets'>Sweets</option>
//            <option value='Drinks'>Drinks</option>
//          </select>
//        </td>
//      </tr>
      echo "
    </table>";
    if(isset($_SESSION['payby']))
    {
      echo "<input id='paybyValue' type='hidden' value='".$_SESSION['payby']."' />";
    } else {
      echo "<input id='paybyValue' type='hidden' value='' />";
    }
    if(isset($_SESSION['returningguest']))
    {
      echo "<input id='returningguestValue' type='hidden' value='".$_SESSION['returningguest']."' />";
    } else {
      echo "<input id='returningguestValue' type='hidden' value='' />";
    }
    if(isset($_SESSION['typeOfFood']))
    {
      echo "<input id='typeOfFoodValue' type='hidden' value='".$_SESSION['typeOfFood']."' />";
    } else {
      echo "<input id='typeOfFoodValue' type='hidden' value='' />";
    }
    echo "<input name='EventID' type='hidden' value='".$eventID."' />";
    echo "</form>";
    echo "<div style='width: 500px; text-align: center; margin: auto;'>";
    
    if($capped) {
      $button = "<button disabled style:'background: #CCCCCC' onclick='registerCheckErrors()'>Register</button>";
    } else {
      $button = "<button onclick='registerCheckErrors()'>Register</button>";
    }
    
    echo $button;
    echo "</div>";
    echo "<input name='submitfix' type='hidden' />";
  }
?>
</body>
</html>