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

$_SESSION['UserID'];

require_once('lib/db.php');

if(isset($_POST['dropDownList']))
{
  //generate their list
	$result = db::get_connection("SELECT EventID,EventName FROM tblEvents WHERE UserID='".$_SESSION['UserID']."'");
	if ($result)
	{
    echo "<div style='padding-top: 5px; padding-bottom: 5px;'><select id='ddList' onchange='ddListChange();' style='width: 250px;'>
            <option value=''>Select From Mailing List...</option>
            <option value='all'>All</option>";
  	while ($row = mysqli_fetch_array($result))
  	{
      echo "<option value='".$row['EventID']."'>".$row['EventName']."</option>";
  	}
    echo "</select>";
	} else {
		echo "<p>Couldn't connect to the database. </p>";
		echo "<br />";
//	  mysqli_close($db_con);
		echo mysqli_error($result);
	}
}

if(isset($_POST['generateTemplates']))
{
  //generate their list
	$result = db::get_connection("SELECT EmailID,TemplateName,Header,Body,Footer FROM email WHERE UserID='".$_SESSION['UserID']."'");
	if ($result)
	{
    echo "<div style='padding-top: 5px; padding-bottom: 5px;'><select id='templateList' onchange='selectNewTemplate();' style='width: 250px;'>
            <option value=''>Select a different template</option>";
  	while ($row = mysqli_fetch_array($result))
  	{
      echo "<option value='".$row['EmailID']."'>".$row['TemplateName']."</option>";
  	}
    echo "</select>";
	} else {
		echo "<p>Couldn't connect to the database. </p>";
		echo "<br />";
//	  mysqli_close($db_con);
		echo mysqli_error($result);
	}
  //TODO: have DB Driven and make the one they were last using pop up in drop down
  
  //TODO: Make button for Create New
  //TODO: Make create new selectable whether or not they want to use the current templates info or make it fresh
  echo ' <a href="javascript:toggleDisplay();">Create New</a>';
  echo ' | <a href="javascript:deleteEmail();">Delete</a></div>';
}

if(isset($_POST['generateMailListAll']))
{
  $result = db::get_connection("SELECT DISTINCT(EmailAddress) FROM registration WHERE UserID='".$_SESSION['UserID']."' AND EmailAddress != '' AND EmailAddress != 'none' ORDER BY EmailAddress");
  if ($result)
	{
    echo "<div style='width:100%; text-align: center;'><button onclick='massEmailAll()'>Mail List</button></div>";
    echo "<div class='email'>";
    echo "<div class='clrBoth'></div>";
    echo "</div>";
    echo "<table border='1'>
  				<tr>";
  					echo "<th style='width: 48px;'>&nbsp;</th>
  					<th style='width: 297px;'><label>Email Address</label></th>
  					<th style='width: 560px;'><label>Custom Message Body</label></th>
  				</tr>";
  	while ($row = mysqli_fetch_array($result))
  	{
  		echo "<tr>";
  		echo "<td id=\"btn".$row['EmailAddress']."\">";
  		echo "<button onclick=\"sendEmailAll('".$row['EmailAddress']."')\">Send</button>";
  		echo "</td>";
  		echo "<td>";
  		echo $row['EmailAddress'];
  		echo "</td>";
  		echo "<td>";
  		echo "</td>";
  		echo "<td id=\"bdy".$row['EmailAddress']."\">";
      echo "&nbsp;";
  		echo "</td>";
  		echo "</tr>";
  	}
    echo "</table>";
	} else {
		echo "<p>Couldn't connect to the database. </p>";
		echo "<br />";
//	  mysqli_close($db_con);
		echo mysqli_error($result);
	}
}

if(isset($_POST['generateMailList']) && isset($_POST['EventID']))
{
  $table = "registration";
  $EventID = $_POST['EventID'];
  //find email
  $result = db::get_connection("SELECT regid,Name,EmailAddress,CustomMessageBody FROM ".$table." WHERE CustomMessageBdySent='0' AND EmailAddress!='none' AND EventID='".$EventID."' AND UserID='".$_SESSION['UserID']."'");
	if ($result)
	{
  echo "<div style='width:100%; text-align: center;'><button onclick='massEmail(".$_POST['EventID'].")'>Mail List</button></div>";
  echo "<div class='email'>";
  echo "<div class='emailLeft'><label class='green'>Have not received</label></div>";
  echo "<div class='emailRight' id='reset'><button onclick=\"resetCustomMessageBody()\">Reset ALL Custom Messages</button></div>";
  echo "<div class='clrBoth'></div>";
  echo "</div>";
  echo "<table border='1'>
				<tr>";
					echo "<th style='width: 48px;'>&nbsp;</th>
					<th style='width: 97px;'><label>Name</label></th>
					<th style='width: 200px;'><label>Email Address</label></th>
					<th style='width: 560px;'><label>Custom Message Body</label></th>
				</tr>";
	while ($row = mysqli_fetch_array($result))
	{
		echo "<tr>";
		echo "<td id=\"btn".$row['regid']."\">";
		echo "<button onclick=\"sendEmail('".$row['regid']."')\">Send</button>";
		echo "</td>";
		echo "<td>";
		echo $row['Name'];
		echo "</td>";
		echo "<td>";
		echo $row['EmailAddress'];
		echo "</td>";
		echo "<td id=\"bdy".$row['regid']."\">";
    if($row['CustomMessageBody'] == "")
    {
      echo "&nbsp;";
    } else {
		  echo $row['CustomMessageBody'];
    }
		echo "</td>";
		echo "</tr>";
	}
  echo "</table>";
	} else {
		echo "<p>Couldn't connect to the database. </p>";
		echo "<br />";
//	  mysqli_close($db_con);
		echo mysqli_error($result);
	}
  
	$result = db::get_connection("SELECT regid,Name,EmailAddress,CustomMessageBody FROM ".$table." WHERE CustomMessageBdySent='1' AND EmailAddress!='none' AND EventID='".$EventID."' AND UserID='".$_SESSION['UserID']."'");
	if ($result)
	{
	  echo "<div class='email'>";
	  echo "<div class='emailLeft'><label class='green'>Have received</label></div>";
	  echo "<div class='emailRight' id='reset'><button onclick=\"resetReceived()\">Reset Received</button></div>";
	  echo "<div class='clrBoth'></div>";
	  echo "</div>";
	  echo "<table border='1'>
  				<tr>";
  					echo "<th style='width: 48px;'>&nbsp;</th>
  					<th style='width: 97px;'><label>Name</label></th>
  					<th style='width: 200px;'><label>Email Address</label></th>
  					<th style='width: 560px;'><label>Custom Message Body</label></th>
  				</tr>";
		while ($row = mysqli_fetch_array($result))
		{
  		echo "<tr>";
			echo "<td id=\"btn".$row['regid']."\">";
			echo "<button onclick=\"sendEmail('".$row['regid']."')\">Send</button>";
			echo "</td>";
			echo "<td>";
			echo $row['Name'];
			echo "</td>";
			echo "<td>";
			echo $row['EmailAddress'];
			echo "</td>";
			echo "<td id=\"bdy".$row['regid']."\">";
      if($row['CustomMessageBody'] == "")
      {
        echo "&nbsp;";
      } else {
			  echo $row['CustomMessageBody'];
      }
			echo "</td>";
			echo "</tr>";
		}
    echo "</table>";
  	} else {
  		echo "<p>Couldn't connect to the database. </p>";
  		echo "<br />";
//  	  mysqli_close($db_con);
  		echo mysqli_error($result);
  	}
    
	$result = db::get_connection("SELECT regid,Name,EmailAddress,CustomMessageBody FROM ".$table." WHERE EmailAddress='none' AND EventID='".$EventID."' AND UserID='".$_SESSION['UserID']."'");
	if ($result)
	{
  echo "<div class='email'>";
  echo "<div class='emailLeft'><label class='orange'>Unable to receive</label></div>";
  //echo "<div class='emailRight' id='reset'><button onclick=\"resetReceived()\">Reset Received</button></div>";
  echo "<div class='clrBoth'></div>";
  echo "</div>";
  echo "<table border='1'>
				<tr>
					<th style='width: 97px;'><label>Name</label></th>
					<th style='width: 200px;'><label>Email Address</label></th>
					<th style='width: 608px;'><label>Custom Message Body</label></th>
				</tr>";
	while ($row = mysqli_fetch_array($result))
	{
		echo "<tr>";
		echo "<td>";
		echo $row['Name'];
		echo "</td>";
		echo "<td>";
		echo $row['EmailAddress'];
		echo "</td>";
		echo "<td>";
    if($row['CustomMessageBody'] == "")
    {
      echo "&nbsp;";
    } else {
		  echo $row['CustomMessageBody'];
    }
		echo "</td>";
		echo "</tr>";
	}
  echo "</table>";
	} else {
		echo "<p>Couldn't connect to the database. </p>";
		echo "<br />";
//	  mysqli_close($db_con);
		echo mysqli_error($result);
	}
  
  
}

//mysqli_close($db_con);
}

?>