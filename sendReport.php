<?php

session_start();
$cancelProcess = false;

//Make sure we are coming from the registration page
if ($_SERVER['HTTP_REFERER'] != 'http://www.creativewebworks.net/bascomb/report.php' && $_SERVER['HTTP_REFERER'] != 'http://creativewebworks.net/bascomb/report.php') {
  //Don't process anything else on the page. The user did not come from our page
  $cancelProcess = true;  
  
  //Redirect them
  header("Location: registration.php");
}
if (!$_POST['emailaddress'])
{
  $cancelProcess = true;
}
$_SESSION['emailaddress'] = $_POST['emailaddress'];
$_SESSION['errorType'] = $_POST['errorType'];
$_SESSION['errorTypeDesc'] = $_POST['errorTypeDesc'];

require('lib/db.php');
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
if (!$cancelProcess)
{
  $sql = "INSERT INTO reports (EmailAddress, TypeOfReport, Description) VALUES ('".$_SESSION['emailaddress']."','".$_SESSION['errorType']."','".$_SESSION['errorTypeDesc']."')";
  $result = db::get_connection($sql);
	If (!$result) {
	 $cancelProcess = true;
	 echo mysqli_error($result);
  }
}  
echo "<table class='form'>
  <tr>
    <td colspan='2' class='center'>
      <p class='title'>BUMC Scrapbook Crop Registration</p>
      <p class='p3'>BUMC Scrapbook Crop</p>
      <p class='p3'>2295 Bascomb Carmel Rd, Woodstock Ga 30189</p>
    </td>
  </tr>
  <tr>
    <td class='right'><label class='l2'>Email Address: </label></td>
    <td class='left'>"; echo $_SESSION['emailaddress']; echo "</td>
  </tr>
  <tr>
    <td class='right'><label class='l2'>Error Type: </label></td>
    <td class='left'>"; echo $_SESSION['errorType']; echo "</td>
  </tr>
  <tr>
    <td class='right' valign='top'><label class='l2'>Brief Description:</label></td>
    <td class='left' valign='top'>"; echo nl2br($_SESSION['errorTypeDesc']); echo "</td>
  </tr>  
  <tr>
    <td class='center' colspan='2'>";
    if (!$cancelProcess)
    {
      echo "<p class='title'>We are processing your report.</p>
      <p class='p3'>Thank you for submitting your report. We will try to fix it as soon as possible.<br />
      You *MAY* be contacted for us to further investigate the problem.</p>";
    } else {
      echo "<p class='title'>There was an error submitting your report.</p>
      <p class='p3'>Please try again later.</p>";
    }
    echo "</td>
  </tr>
</table>";
?>

</body>
</html>