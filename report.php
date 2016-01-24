<?php

session_start();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />

	<title>BUMC Scrapbook Crop Registration</title>
  <link href="registration.css" rel="stylesheet" type="text/css" />
  <script language="javascript" type="text/javascript">
  function limitText(id, limitNum) 
  {
    var field = document.getElementById(id);
  	if (field.value.length > limitNum) {
  		field.value = field.value.substring(0, limitNum);
  	}
  }
  
  function reportErrors() 
  {
    var errors = false;
    var e1 = document.getElementById("emailCheck").value;
    var e2 = document.getElementById("emailCheck2").value;
    var errorType = document.getElementById("errorType").value;
    var errorTypeDesc = document.getElementById("errorTypeDesc").value;
    var msg = "";
    if (e1 == "")
    {
      msg = msg + "Email address is required.\n\r";
      errors = true;
    }
    if (e1 != e2)
    {
      msg = msg + "Submitted emails do not match.\n\r";
      errors = true;
    }
    if (errorType == "")
    {
      msg = msg + "Type of error must be filled out.\n\r";
      errors = true;
    }
    if (errorTypeDesc == "")
    {
      msg = msg + "Please give a brief description of what you experienced.\n\r";
      errors = true;
    }
    if (errors)
    {
      alert(msg);
    }
    if (!errors)
    {
      document.getElementById("errorForm").submit();
    }
  }
  </script>
</head>
<body>

<table class='form'>
  <tr>
    <td colspan='2' class='center'>
      <p class='title'>BUMC Scrapbook Crop Registration</p>
      <p class='p3'>Susan Austin</p>
      <p class='p3'><a href='https://sites.google.com/site/bascombcrop/'>BUMC Scrapbook Crop</a></p>
      <p class='p3'>6021 Hollow Dr, Woodstock Ga 30189</p>
      <p class='p3'><label class='required'>*</label> <label class='l2'>= Required.</p>
    </td>
  </tr>
  <form id='errorForm' method='post' action='sendReport.php'>
  <tr>
    <td class='right' valign="top"><label class='l2'>Email Address: </label><label class='required'>*</label>
    <br /><label class='isDescription'>So we may contact you about the error</label></td>
    <td class='left' valign="top"><input id='emailCheck' class='i1' name='emailaddress' type='text' /></td>
  </tr>
  <tr>
    <td class='right' valign="top"><label class='l2'>Confirm Email Address: </label><label class='required'>*</label></td>
    <td class='left' valign="top"><input id='emailCheck2' class='i1' name='emailaddress2' type='text' /></td>
  </tr>
  <tr>
    <td class='right' valign="top"><label class='l2'>Type of error: </label><label class='required'>*</label>
    <br /><label class='isDescription'>What happened?</label></td>
    <td class='left' valign="top"><input id='errorType' class='i1' name='errorType' type='text' /></td>
  </tr>
  <tr>
    <td class='right' valign="top"><label class='l2'>Brief Description: </label><label class='required'>*</label>
    <br /><label class='isDescription'>When did it occur?<br />What were you doing when it occurred?</label></td>
    <td class='left' valign="top">
    <textarea rows="9" id='errorTypeDesc' name='errorTypeDesc' style='width: 240px;' 
    onKeyDown="limitText('errorTypeDesc','250');" 
    onKeyUp="limitText('errorTypeDesc','250');"></textarea><br />
    <label class='isDescription'>(Maximum characters: 250)</label>
  </td>
  </tr>
  </form>
  <tr>
    <td colspan='2'><p class='title'><button onclick='reportErrors()'>Report</button></p></td>
  </tr>
</table>

</body>
</html>