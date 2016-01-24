<?php

/**
 * @author Chris & Robert Whetzel
 * @copyright 2010
 */


session_start();

$headersForCopy;

$cancelProcess = false;
$emailInUse = false;
$eventid = $_POST['EventID'];

require_once("lib/_classes.php");
require_once("lib/_functions.php");
require_once('lib/db.php');

//Make sure we are coming from the registration page
if ($_SERVER['HTTP_REFERER'] != 'http://www.creativewebworks.net/bascomb/registration.php?e='.$eventid && $_SERVER['HTTP_REFERER'] != 'http://creativewebworks.net/bascomb/registration.php?e='.$eventid) {
  
  echo "Could not find where you were coming from.";
  
  //Don't process anything else on the page. The user did not come from our page
  $cancelProcess = true;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />

	<title>BUMC Scrapbook Crop Registration</title>
  <link href="registration.css" rel="stylesheet" type="text/css" />

</head>
<body>
<?php
if (isset($_POST['name'])) {
  // Add record to database
  if (!$cancelProcess) {
    $_SESSION['name'] = $_POST['name'];
    $_SESSION['emailaddress'] = $_POST['emailaddress'];
    $_SESSION['streetaddress'] = $_POST['streetaddress'];
    $_SESSION['csz'] = $_POST['csz'];
    $_SESSION['phone'] = $_POST['phone'];
    $_SESSION['payby'] = $_POST['payby'];
    $_SESSION['returningguest'] = $_POST['returningguest'];
    $_SESSION['food'] = $_POST['food'];
    $_SESSION['heardabout'] = $_POST['heardabout'];
    $_SESSION['referredby'] = $_POST['referredby'];
    $_SESSION['requestedtablebuddies'] = $_POST['requestedtablebuddies'];
    $_SESSION['notetohostess'] = $_POST['notetohostess'];
    $_SESSION['typeOfFood'] = $_POST['typeOfFood'];
    $userid = getUserID($eventid);
  }
  
  //build e-mail string
  //send email and sql statement
  if (!$cancelProcess) {
    $sql = "INSERT INTO registration (UserID, EventID, Name, EmailAddress, StreetAddress, CSZ, Phone, PayBy, ReturningGuest, Food, HeardAbout, ReferredBy, EnteredBy, RequestedTableBuddies, NoteToHostess, Paid, FoodCategory, CustomMessageBdySent) ";
    $sql = $sql."VALUES ('".$userid."','".$eventid."','".$_SESSION['name']."','".$_SESSION['emailaddress']."','".$_SESSION['streetaddress']."','".$_SESSION['csz']."','".$_SESSION['phone']."','".$_SESSION['payby']."','".$_SESSION['returningguest']."','','".$_SESSION['heardabout']."','".$_SESSION['referredby']."', 'Online','".$_SESSION['requestedtablebuddies']."','".$_SESSION['notetohostess']."', 'N', 'NULL', '0')";

    // multiple recipients
    $to  = $_SESSION['emailaddress'];
    
    // subject
    $subject = 'Crop Registration - '.$_POST['name'];
    
    // message
    $message = "
      <html>
      <head>
        <title>BUMC Crop Registration</title>
      </head>
      <body>
        <table style='margin: auto;border: none;padding: 0em;width: 500px;'>
        <tr>
          <td colspan='2' class='width: 450px;text-align: center;'>
            <p style='text-align: center;font: 25px sans-serif;font-weight: bold;color: #37347A;'>BUMC Scrapbook Crop Registration</p>
            <p style='text-align: center;font: 13px sans-serif;font-weight: bold;color: #37347A;'>Susan Austin</p>
            <p style='text-align: center;font: 13px sans-serif;font-weight: bold;color: #37347A;'><a href='https://sites.google.com/site/bascombcrop/'>BUMC Scrapbook Crop</a></p>
            <p style='text-align: center;font: 13px sans-serif;font-weight: bold;color: #37347A;'>6021 Hollow Dr, Woodstock Ga 30189</p>
          </td>
        </tr>
        <tr>
          <td style='width: 165px;text-align: right;'><label style='font: 13px sans-serif;font-weight: bold;color: #37347A;'>Name: </label></td>
          <td style='width: 265px;text-align: left;'>".$_SESSION['name']."</td>
        </tr>
        <tr>
          <td style='width: 165px;text-align: right;'><label style='font: 13px sans-serif;font-weight: bold;color: #37347A;'>Email Address: </label></td>
          <td style='width: 265px;text-align: left;'>".$_SESSION['emailaddress']."</td>
        </tr>
        <tr>
          <td style='width: 165px;text-align: right;'><label style='font: 13px sans-serif;font-weight: bold;color: #37347A;'>Street Address: </label></td>
          <td style='width: 265px;text-align: left;'>".$_SESSION['streetaddress']."</td>
        </tr>
        <tr>
          <td style='width: 165px;text-align: right;'><label style='font: 13px sans-serif;font-weight: bold;color: #37347A;'>City, State: </label></td>
          <td style='width: 265px;text-align: left;'>".$_SESSION['csz']."</td>
        </tr>
        <tr>
          <td style='width: 165px;text-align: right;'><label style='font: 13px sans-serif;font-weight: bold;color: #37347A;'>Phone #: </label></td>
          <td style='width: 265px;text-align: left;'>".$_SESSION['phone']."</td>
        </tr>
        <tr>
          <td style='width: 165px;text-align: right;vertical-align: top;'><label style='font: 13px sans-serif;font-weight: bold;color: #37347A;'>How are you paying?: </label></td>
          <td style='width: 265px;text-align: left;vertical-align: top;'>";
          if ($_SESSION['payby'] == "paypal") {
            $message .= "PayPal<br />
            <p style='font: 12px sans-serif;color: red;font-weight: bold;text-align: left;'>
            If you have not completed<br />
             your paypal transaction, <br />
             please do so.<br />";
            $message .= getPaypalForm($eventid);
            $message .= "<br />
            </p>";
          }
          if ($_SESSION['payby'] == "check") {
            $message .= "Check/Cash<br />
            <br /><label style='font: 13px sans-serif;font-weight: bold;color: black;'>Make Check Payable to Bascomb <br /> UMC (subject:scrapbook crop)</label>
            <p style='font: 12px sans-serif;color: red;font-weight: bold;text-align: left;'>Please send payment to:<br />
            Susan Austin<br />
            BUMC Crop<br />
            6021 Hollow Dr<br />
            Woodstock, Ga 30189<br /></p>";
          }
          $message .= "</td>
        </tr>
        <tr>
          <td style='width: 165px;text-align: right;'><label style='font: 13px sans-serif;font-weight: bold;color: #37347A;'>Returning Guest: </label></td>
          <td style='width: 265px;text-align: left;'>".$_SESSION['returningguest']."</td>
        </tr>
        <tr>
          <td style='width: 165px;text-align: right;'><label style='font: 13px sans-serif;font-weight: bold;color: #37347A;'>How did you hear about us?: </label></td>
          <td style='width: 265px;text-align: left;'>".$_SESSION['heardabout']."</td>
        </tr>
        <tr>
          <td style='width: 165px;text-align: right;'><label style='font: 13px sans-serif;font-weight: bold;color: #37347A;'>Referred By?: </label></td>
          <td style='width: 265px;text-align: left;'>".$_SESSION['referredby']."</td>
        </tr>              
        <tr>
          <td style='width: 165px;text-align: right;' valign='top'><label style='font: 13px sans-serif;font-weight: bold;color: #37347A;'>Requested Table Buddies:</label></td>
          <td style='width: 265px;text-align: left;'>".stripslashes(nl2br($_SESSION['requestedtablebuddies']))."</td>
        </tr>     
        <tr>
          <td style='width: 165px;text-align: right;' valign='top'><label style='font: 13px sans-serif;font-weight: bold;color: #37347A;'>Note To Hostess:</label></td>
          <td style='width: 265px;text-align: left;'>".stripslashes(nl2br($_SESSION['notetohostess']))."</td>
        </tr>
        ";
        
//        <tr>
//          <td style='width: 165px;text-align: right;vertical-align: top;'><label style='font: 13px sans-serif;font-weight: bold;color: #37347A;'>Food:</label><br /> <label style='font: 12px sans-serif;color: red;'>Earn $5 off your registration fee by bringing food item(s) for your fellow scrappers.</label></td>
//          <td style='width: 265px;text-align: left;vertical-align: top;'>".nl2br($_SESSION['food'])."</td>
//        </tr>
              
//        <tr>
//          <td style='width: 165px;text-align: right;vertical-align: top;'><label style='font: 13px sans-serif;font-weight: bold;color: #37347A;'>Food Category:</label><br /><label style='font: 12px sans-serif;color: red;'>If you are bringing something.</label></td>
//          <td style='width: 265px;text-align: left;vertical-align: top;'>".$_SESSION['typeOfFood']."</td>
//        </tr>
        $message .= "
        <tr>
          <td colspan='2'>
            <p style='text-align: center;font: 25px sans-serif;font-weight: bold;color: #37347A;'>Thank you for registering.</p>
          </td>
        </tr>
      </table>
    </body>
    </html>";

    $data = getSettings($userid);
    $useremail = $data->email;
//    $userfriendlyemail = $data->friendlyemail;
//    $userbcc = $data->bcc;
//
//    if($userbcc != 1) {
//      //it is a no, blank it
//      $userbcc = "";
//    } else {
//      $userbcc = $useremail;
//    }

    // To send HTML mail, the Content-type header must be set
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    
    // Additional headers
    $headers .= 'To: ' . $to . "\r\n";
    $headers .= 'From: ' . $useremail . "\r\n";
    $headers .= 'Cc: ' . $useremail  . "\r\n";

    // Mail it
    mail($useremail, $subject, $message, $headers);
    
    $result = mysql_query($sql);
  	If (!$result) {
  	 echo mysql_error($db_con);
    }
  }
  
  
  //create html
    echo "<table class='form'>
      <tr>
        <td colspan='2' class='center'>
          <p class='title'>BUMC Scrapbook Crop Registration</p>
          ";
                    
          if ($emailInUse)
          {
            echo "<p class='importantBig'>This e-mail is already used.<br />
            Your registration is not complete.</p>
            <p class='p3'>Back to <a href='http://www.creativewebworks.net/bascomb/registration.php'>Registration</a></p>";
          }
          if (!$emailInUse)
          {
            echo "<p class='importantBig' style=''>Thank you for registering.<br />
            A copy of your registration has been e-mailed to you.</p>";
          }
                    
          echo "                    
          <p class='p3'>Susan Austin</p>
          <p class='p3'><a href='https://sites.google.com/site/bascombcrop/'>BUMC Scrapbook Crop</a></p>
          <p class='p3'>6021 Hollow Dr, Woodstock Ga 30189</p>
        </td>
      </tr>
      <tr>
        <td class='right'><label class='l2'>Name: </label></td>
        <td class='left'>"; echo $_SESSION['name']; echo "</td>
      </tr>
      <tr>
        <td class='right'><label class='l2'>Email Address: </label></td>
        <td class='left'>"; echo $_SESSION['emailaddress']; echo "</td>
      </tr>
      <tr>
        <td class='right'><label class='l2'>Street Address: </label></td>
        <td class='left'>"; echo $_SESSION['streetaddress']; echo "</td>
      </tr>
      <tr>
        <td class='right'><label class='l2'>City, State: </label></td>
        <td class='left'>"; echo $_SESSION['csz']; echo "</td>
      </tr>
      <tr>
        <td class='right'><label class='l2'>Phone #: </label></td>
        <td class='left'>"; echo $_SESSION['phone']; echo "</td>
      </tr>
      <tr>
        <td class='right' valign='top'><label class='l2'>How are you paying?: </label></td>
        <td class='left' valign='top'>";
        if ($_SESSION['payby'] == "paypal") {
          echo "PayPal<br />
          <p class='important'>";
          echo getPaypalForm($eventid);
          echo "</p>";
        }
        if ($_SESSION['payby'] == "check") {
          echo "Check/Cash<br />
          <br /><label class='l3'>Make Check Payable to Bascomb <br /> UMC (subject:scrapbook crop)</label>
          <p class='important'>Please send payment to:<br />
          Susan Austin<br />
          BUMC Crop<br />
          6021 Hollow Dr<br />
          Woodstock, Ga 30189<br /></p>";
        }
        echo "</td>
      </tr>
      <tr>
        <td class='right'><label class='l2'>Returning Guest: </label></td>
        <td class='left'>"; echo $_SESSION['returningguest']; echo "</td>
      </tr>
      <tr>
        <td class='right'><label class='l2'>How did you hear about us?: </label></td>
        <td class='left'>"; echo $_SESSION['heardabout']; echo "</td>
      </tr>
      <tr>
        <td class='right'><label class='l2'>Referred By?: </label></td>
        <td class='left'>"; echo $_SESSION['referredby']; echo "</td>
      </tr>              
      <tr>
        <td class='right' valign='top'><label class='l2'>Requested Table Buddies:</label></td>
        <td class='left' valign='top'>"; echo stripslashes(nl2br($_SESSION['requestedtablebuddies'])); echo "</td>
      </tr>     
      <tr>
        <td class='right' valign='top'><label class='l2'>Note To Hostess:</label></td>
        <td class='left' valign='top'>"; echo stripslashes(nl2br($_SESSION['notetohostess'])); echo "</td>
      </tr>
      ";
      
//      <tr>
//        <td class='right' valign='top'><label class='l2'>Food:</label><br /> <label class='isDescription'>Earn $5 off your registration fee by bringing food item(s) for your fellow scrappers.</label></td>
//        <td class='left' valign='top'>"; echo nl2br($_SESSION['food']); echo "</td>
//      </tr>
            
//      <tr>
//        <td class='right' valign='top'><label class='l2'>Food Category:</label><br /><label class='isDescription'>If you are bringing something.</label></td>
//        <td class='left' valign='top'>"; echo $_SESSION['typeOfFood']; echo "</td>
//      </tr>
      echo "
    </table>";
}
?>
</body>
</html>