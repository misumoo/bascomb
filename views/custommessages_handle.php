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

$number;
$_SESSION['UserID'];

require_once("../lib/_classes.php");
require_once("../lib/_functions.php");
require_once('../phpmailer/class.phpmailer.php');

require_once('../lib/db.php');

if(isset($_POST['deleteEmail']))
{
  if(!$cancelProcess)
  {
  	$result = db::get_connection("DELETE FROM email WHERE EmailID='".$_POST['deleteEmail']."' AND UserID='".$_SESSION['UserID']."'");
  	if ($result)
  	{
  	  echo "Done";
    } else {
  	  $cancelProcess = true;
  		echo "<p>Couldn't connect to the database. </p>";
  		echo "<br />";
  		echo mysqli_error($result);
//  	  mysqli_close($db_con);
  	}
  }
}

if(isset($_POST['emailName']))
{
  //reset the received to 0
	$result = db::get_connection("INSERT INTO `email` 
  (`EmailID`, `UserID`, `TemplateName`, `Header`, `Subject`, `Body`, `Footer`) 
  VALUES 
  (NULL, '".$_SESSION['UserID']."', '".$_POST['emailName']."', NULL, NULL, NULL, NULL)");
	if ($result)
	{
	  echo "Done";
  } else {
	  $cancelProcess = true;
		echo "<p>Couldn't connect to the database. </p>";
		echo "<br />";
		echo mysqli_error($result);
//	  mysqli_close($db_con);
	}
}

if(isset($_POST['resetCustomMessageBody']))
{
  //reset the received to 0
	$result = db::get_connection("UPDATE registration SET CustomMessageBody=''");
	if ($result)
	{
	  echo "Done";
  } else {
	  $cancelProcess = true;
		echo "<p>Couldn't connect to the database. </p>";
		echo "<br />";
		echo mysqli_error($result);
//	  mysqli_close($db_con);
	}
}

if(isset($_POST['resetReceived']))
{
  //reset the received to 0
	$result = db::get_connection("UPDATE registration SET CustomMessageBdySent='0'");
	if ($result)
	{
	  echo "Done";
  } else {
	  $cancelProcess = true;
		echo "<p>Couldn't connect to the database. </p>";
		echo "<br />";
		echo mysqli_error($result);
//	  mysqli_close($db_con);
	}
}



if(isset($_POST['massEmailDoAll']) && isset($_POST['template']))
{
  $template;
  $Subject;
  $Header;
  $Body;
  $Footer;
  $email;
  $i;

	$userid = $_SESSION['UserID'];

	$data = getSettings($userid);
	$useremail = $data->email;
	$userfriendlyemail = $data->friendlyemail;
	$userbcc = $data->bcc;

	if($userbcc != 1) {
		//it is a no, blank it
		$userbcc = "";
	} else {
		$userbcc = $useremail;
	}
  
  $template = $_POST['template'];
  
  //grab the email setup
	$result = db::get_connection("SELECT Header,Subject,Body,Footer FROM email WHERE UserID='".$_SESSION['UserID']."' AND EmailID='".$template."'");
	if ($result)
	{
	  while($row = mysqli_fetch_array($result))
    {
	    $Subject = $row['Subject'];
      $Header = $row['Header'];
      $Body = $row['Body'];
      $Footer = $row['Footer'];
    }
	} else {
	  $cancelProcess = true;
		echo "<p>Couldn't connect to the database. </p>";
		echo "<br />";
		echo mysqli_error($result);
//	  mysqli_close($db_con);
	}
  
	$result = db::get_connection("SELECT DISTINCT(EmailAddress) FROM registration WHERE UserID='".$_SESSION['UserID']."' AND EmailAddress != '' AND EmailAddress != 'none'");
	if ($result)
	{
	  while($row = mysqli_fetch_array($result))
    {
      $email = $row['EmailAddress'];
      if(!$cancelProcess)
      {
        // message
        $message = "<html>
          <head>
            <title>BUMC Crop Registration</title>
          </head>
          <body>
            <p style='font: 14px Comic Sans MS;'>".$Header."</p>
            <p style='font: 14px Comic Sans MS;'>".$CustomMsg."</p>
            <p style='font: 14px Comic Sans MS;'>".$Body."</p>
            <p style='font: 14px Comic Sans MS;'>".$Footer."</p>
          </body>
          </html>";
        
      }
      
      if (!$cancelProcess)
      {
        //include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded
         
        $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
         
        $mail->IsSMTP(); // telling the class to use SMTP
         
        try {
          //who it's going to
          $mail->AddReplyTo($useremail, $userfriendlyemail);
          $mail->AddAddress($email);
          $mail->AddBCC($userbcc);
    
          //who it's from
          $mail->SetFrom($useremail, $userfriendlyemail);
          
          //subject
          $mail->Subject = $Subject;
          //alt body
          $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
          //$mail->MsgHTML(file_get_contents('contents.html'));
          
          //attachments
          //$mail->AddAttachment('images/webheader.jpg');
          
          
          //$mail->AddEmbeddedImage('images/webheader.jpg', 'webheader', 'webheader.jpg'); // attach file logo.jpg, and later link to it using identfier logoimg
          //$mail->AddEmbeddedImage('images/susansignature.png', 'susansignature', 'susansignature.png'); // attach file logo.jpg, and later link to it using identfier logoimg
          $mail->Body = $message;
            
          $mail->Send();
        } catch (phpmailerException $e) {
          echo $e->errorMessage(); //Pretty error messages from PHPMailer
        } catch (Exception $e) {
          echo $e->getMessage(); //Boring error messages from anything else!
        }
        echo $row['EmailAddress'];
        echo " received an e-mail. <br />";
      }
    }
	} else {
	  $cancelProcess = true;
		echo "<p>Couldn't connect to the database. </p>";
		echo "<br />";
		echo mysqli_error($result);
//	  mysqli_close($db_con);
	}
  
}

if(isset($_POST['massEmailDo']) && isset($_POST['list']) && isset($_POST['template']))
{
  $list;
  $template;
  $Subject;
  $Header;
  $Body;
  $Footer;
  $email;
  $i;

	$userid = $_SESSION['UserID'];

	$data = getSettings($userid);
	$useremail = $data->email;
	$userfriendlyemail = $data->friendlyemail;
	$userbcc = $data->bcc;

	if($userbcc != 1) {
		//it is a no, blank it
		$userbcc = "";
	} else {
		$userbcc = $useremail;
	}
  
  $list = $_POST['list'];
  $template = $_POST['template'];
  
  //grab the email setup
	$result = db::get_connection("SELECT Header,Subject,Body,Footer FROM email WHERE UserID='".$_SESSION['UserID']."' AND EmailID='".$template."'");
	if ($result)
	{
	  while($row = mysqli_fetch_array($result))
    {
	    $Subject = $row['Subject'];
      $Header = $row['Header'];
      $Body = $row['Body'];
      $Footer = $row['Footer'];
    }
	} else {
	  $cancelProcess = true;
		echo "<p>Couldn't connect to the database. </p>";
		echo "<br />";
		echo mysqli_error($result);
//	  mysqli_close($db_con);
	}
  
	$result = db::get_connection("SELECT CustomMessageBody,EmailAddress FROM registration WHERE UserID='".$_SESSION['UserID']."' AND EventID='".$list."'");
	if ($result)
	{
	  while($row = mysqli_fetch_array($result))
    {
      if($row['EmailAddress'] == "" || $row['EmailAddress'] == "none")
      {
        echo " did NOT receive an email.<br />";
      } else {
        echo $row['EmailAddress'];
        echo " received an e-mail. <br />";
        $email = $row['EmailAddress'];
        if(!$cancelProcess)
        {
          // message
          $message = "<html>
            <head>
              <title>BUMC Crop Registration</title>
            </head>
            <body>
              <p style='font: 14px Comic Sans MS;'>".$Header."</p>
              <p style='font: 14px Comic Sans MS;'>".$CustomMsg."</p>
              <p style='font: 14px Comic Sans MS;'>".$Body."</p>
              <p style='font: 14px Comic Sans MS;'>".$Footer."</p>
            </body>
            </html>";

        }
//        if(!$cancelProcess)
//        {
//          $result = db::get_connection("UPDATE registration SET CustomMessageBdySent='1' WHERE regid='".$regid."'");
//          if ($result)
//          {
//            //do nothing
//          } else {
//            $cancelProcess = true;
//          	echo "<p>Couldn't connect to the database. </p>";
//          	echo "<br />";
//          	echo mysqli_error($db_con);
//            mysqli_close($db_con);
//          }
//        }
        if (!$cancelProcess)
        {
          //include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded
           
          $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
           
          $mail->IsSMTP(); // telling the class to use SMTP
           
          try {
            //who it's going to
            $mail->AddReplyTo($useremail, $userfriendlyemail);
            $mail->AddAddress($email);
            $mail->AddBCC($userbcc);
            //who it's from
            $mail->SetFrom($useremail, $userfriendlyemail);
            //subject
            $mail->Subject = $Subject;
            //alt body
            $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
            //$mail->MsgHTML(file_get_contents('contents.html'));
            
            //attachments
            //$mail->AddAttachment('images/webheader.jpg');
            
            
            //$mail->AddEmbeddedImage('images/webheader.jpg', 'webheader', 'webheader.jpg'); // attach file logo.jpg, and later link to it using identfier logoimg
            //$mail->AddEmbeddedImage('images/susansignature.png', 'susansignature', 'susansignature.png'); // attach file logo.jpg, and later link to it using identfier logoimg
            $mail->Body = $message;
              
            $mail->Send();
          } catch (phpmailerException $e) {
            echo $e->errorMessage(); //Pretty error messages from PHPMailer
          } catch (Exception $e) {
            echo $e->getMessage(); //Boring error messages from anything else!
          }
        }
      }
    }
	} else {
	  $cancelProcess = true;
		echo "<p>Couldn't connect to the database. </p>";
		echo "<br />";
		echo mysqli_error($result);
//	  mysqli_close($db_con);
	}
}


if(isset($_POST['popEmailList']))
{
  $ready = false;
  if($ready)
  {
    //find email
  	$result = db::get_connection("SELECT regid,Name,EmailAddress,CustomMessageBody FROM registration WHERE CustomMessageBdySent='0' AND EmailAddress!='none'");
  	if ($result)
  	{
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
  		echo "<button onclick=\"sendNewEmail('".$row['regid']."')\">Send</button>";
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
    
  	$result = db::get_connection("SELECT regid,Name,EmailAddress,CustomMessageBody FROM registration WHERE CustomMessageBdySent='1' AND EmailAddress!='none'");
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
//    	  mysqli_close($db_con);
    		echo mysqli_error($result);
    	}
      
  	$result = db::get_connection("SELECT regid,Name,EmailAddress,CustomMessageBody FROM registration WHERE EmailAddress='none'");
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
//  	  mysqli_close($db_con);
  		echo mysqli_error($result);
  	}
  }
}

if(isset($_POST['changeTo']))
{
	$result = db::get_connection("SELECT Header,Subject,Body,Footer FROM email WHERE UserID='".$_SESSION['UserID']."' AND EmailID='".$_POST['changeTo']."'");
	if ($result)
	{
		while ($row = mysqli_fetch_array($result))
		{
      $header = $row['Header'];
      $subject = $row['Subject'];
      $body = $row['Body'];
      $footer = $row['Footer'];
		}
	} else {
		echo "<p>Couldn't connect to the database. </p>";
//	  mysqli_close($db_con);
		echo mysqli_error($result);
	}
  echo "<table>";
  
  echo "<tr>";
  echo "<td valign=\"top\" style='width: 75px;'>Subject</td>";
  echo "<td valign=\"top\" style='width: 16px;'><div id=\"imgEditSubjectCancel\"></div></td>";
  echo "<td valign=\"top\" style='width: 16px;'><div id=\"imgEditSubject\"><img style=\"cursor: pointer;\" src=\"images/edit-small.png\" onclick=\"changeEmailLayout('subjectAjaxResult','imgEditSubject','Subject','92','1','false')\" /></div></td>";
  echo "<td valign=\"top\"><div id='subjectAjaxResult'>".$subject."</div></td>";
  echo "</tr>";
  
  echo "<tr>";
  echo "<td valign=\"top\" style='width: 75px;'>Header</td>";
  echo "<td valign=\"top\" style='width: 16px;'><div id=\"imgEditHeaderCancel\"></div></td>";
  echo "<td valign=\"top\" style='width: 16px;'><div id=\"imgEditHeader\"><img style=\"cursor: pointer;\" src=\"images/edit-small.png\" onclick=\"changeEmailLayout('headerAjaxResult','imgEditHeader','Header','92','5','true')\" /></div></td>";
  echo "<td valign=\"top\"><div id='headerAjaxResult'>".$header."</div></td>";
  echo "</tr>";
  
  echo "<tr>";
  echo "<td valign=\"top\" style='width: 75px;'>Custom Msg</td>";
  echo "<td></td>";
  echo "<td></td>";
  echo "<td></td>";
  echo "</tr>";
  
  echo "<tr>";
  echo "<td valign=\"top\" style='width: 75px;'>Body</td>";
  echo "<td valign=\"top\" style='width: 16px;'><div id=\"imgEditBodyCancel\"></div></td>";
  echo "<td valign=\"top\" style='width: 16px;'><div id=\"imgEditBody\"><img style=\"cursor: pointer;\" src=\"images/edit-small.png\" onclick=\"changeEmailLayout('bodyAjaxResult','imgEditBody','Body','92','11','true')\" /></div></td>";
  echo "<td valign=\"top\"><div id='bodyAjaxResult'>".$body."</div></td>";
  echo "</tr>";
  
  echo "<tr>";
  echo "<td valign=\"top\" style='width: 75px;'>Footer</td>";
  echo "<td valign=\"top\" style='width: 16px;'><div id=\"imgEditFooterCancel\"></div></td>";
  echo "<td valign=\"top\" style='width: 16px;'><div id=\"imgEditFooter\"><img style=\"cursor: pointer;\" src=\"images/edit-small.png\" onclick=\"changeEmailLayout('footerAjaxResult','imgEditFooter','Footer','92','4','true')\" /></div></td>";
  echo "<td valign=\"top\"><div id='footerAjaxResult'>".$footer."</div></td>";
  echo "</tr>";
//            <button style='width: 68px;' onclick=\"saveHeader()\">Change</button>
  echo "</tr>";
  echo "</table>";
  echo "<input type='hidden' id='checkIfOpen' value='0' />";
  echo "<input type='hidden' id='currentTemplate' value='".$_POST['changeTo']."' />";
  
}

if(isset($_POST['emailLayoutSetup']))
{
  $header = "";
  $subject = "";
  $body = "";
  $footer = "";
  $EmailID = "";

  if(isset($_POST['current'])) {
    $whereClause = " WHERE EmailID='".$_POST['current']."' AND UserID='".$_SESSION['UserID']."'";
  } else {
    $whereClause = " WHERE UserID='".$_SESSION['UserID']."' LIMIT 1";
  }

	$result = db::get_connection("SELECT EmailID,Header,Subject,Body,Footer FROM email".$whereClause);

	if ($result) {
		while ($row = mysqli_fetch_array($result)) {
      $header = $row['Header'];
      $subject = $row['Subject'];
      $body = $row['Body'];
      $footer = $row['Footer'];
      $EmailID = $row['EmailID'];
		}
	} else {
		echo "<p>Couldn't connect to the database. </p>";
//	  mysqli_close($db_con);
		echo mysqli_error($result);
	}

  echo "<table>";
  
  echo "<tr>";
  echo "<td valign=\"top\" style='width: 75px;'>Subject</td>";
  echo "<td valign=\"top\" style='width: 16px;'><div id=\"imgEditSubjectCancel\"></div></td>";
  echo "<td valign=\"top\" style='width: 16px;'><div id=\"imgEditSubject\"><img style=\"cursor: pointer;\" src=\"images/edit-small.png\" onclick=\"changeEmailLayout('subjectAjaxResult','imgEditSubject','Subject','92','1','false')\" /></div></td>";
  echo "<td valign=\"top\"><div id='subjectAjaxResult'>".$subject."</div></td>";
  echo "</tr>";
  
  echo "<tr>";
  echo "<td valign=\"top\" style='width: 75px;'>Header</td>";
  echo "<td valign=\"top\" style='width: 16px;'><div id=\"imgEditHeaderCancel\"></div></td>";
  echo "<td valign=\"top\" style='width: 16px;'><div id=\"imgEditHeader\"><img style=\"cursor: pointer;\" src=\"images/edit-small.png\" onclick=\"changeEmailLayout('headerAjaxResult','imgEditHeader','Header','92','5','true')\" /></div></td>";
  echo "<td valign=\"top\"><div id='headerAjaxResult'>".$header."</div></td>";
  echo "</tr>";
  
  echo "<tr>";
  echo "<td valign=\"top\" style='width: 75px;'>Custom Msg</td>";
  echo "<td></td>";
  echo "<td></td>";
  echo "<td></td>";
  echo "</tr>";
  
  echo "<tr>";
  echo "<td valign=\"top\" style='width: 75px;'>Body</td>";
  echo "<td valign=\"top\" style='width: 16px;'><div id=\"imgEditBodyCancel\"></div></td>";
  echo "<td valign=\"top\" style='width: 16px;'><div id=\"imgEditBody\"><img style=\"cursor: pointer;\" src=\"images/edit-small.png\" onclick=\"changeEmailLayout('bodyAjaxResult','imgEditBody','Body','92','11','true')\" /></div></td>";
  echo "<td valign=\"top\"><div id='bodyAjaxResult'>".$body."</div></td>";
  echo "</tr>";
  
  echo "<tr>";
  echo "<td valign=\"top\" style='width: 75px;'>Footer</td>";
  echo "<td valign=\"top\" style='width: 16px;'><div id=\"imgEditFooterCancel\"></div></td>";
  echo "<td valign=\"top\" style='width: 16px;'><div id=\"imgEditFooter\"><img style=\"cursor: pointer;\" src=\"images/edit-small.png\" onclick=\"changeEmailLayout('footerAjaxResult','imgEditFooter','Footer','92','4','true')\" /></div></td>";
  echo "<td valign=\"top\"><div id='footerAjaxResult'>".$footer."</div></td>";
  echo "</tr>";

  echo "</tr>";
  echo "</table>";
  echo "<input type='hidden' id='checkIfOpen' value='0' />";
  echo "<input type='hidden' id='currentTemplate' value='".$EmailID."' />";
}

if(isset($_POST['dbColumn']) && isset($_POST['getInformation']) && isset($_POST['id']) && !$cancelProcess)
{
  $id = $_POST['id'];
  $dbColumn = $_POST['dbColumn'];
	$result = db::get_connection("SELECT ".$dbColumn." FROM email WHERE UserID='".$_SESSION['UserID']."' AND EmailID='".$id."'");
	if ($result)
	{
	  while($row = mysqli_fetch_array($result))
    {
      echo $row[$dbColumn];
    }
	} else {
	  $cancelProcess = true;
		echo "<p>Couldn't connect to the database. </p>";
		echo "<br />";
		echo mysqli_error($result);
//	  mysqli_close($db_con);
	}
}

if(isset($_POST['newInnerHTML']) && isset($_POST['dbColumn']) && isset($_POST['setNewEmailLayout']) && !$cancelProcess)
{
  $currentID = $_POST['current'];
  $column = $_POST['dbColumn'];
  $new = $_POST['newInnerHTML'];
  $result = db::get_connection("UPDATE email SET ".$column."='".$new."' WHERE EmailID='".$currentID."' AND UserID='".$_SESSION['UserID']."'");
  if ($result)
  {
    echo $new;
  } else {
    $cancelProcess = true;
  	echo "<p>Couldn't connect to the database. </p>";
  	echo "<br />";
  	echo mysqli_error($result);
//    mysqli_close($db_con);
  }
}

if(isset($_POST['sendEmailAll']) && isset($_POST['address']))
{
  $Header;
  $Subject;
  $Body;
  $Footer;
  $email;
  $template;

	$userid = $_SESSION['UserID'];

	$data = getSettings($userid);
	$useremail = $data->email;
	$userfriendlyemail = $data->friendlyemail;
	$userbcc = $data->bcc;

	if($userbcc != 1) {
		//it is a no, blank it
		$userbcc = "";
	} else {
		$userbcc = $useremail;
	}
  
  $email = $_POST['address'];
  $template = $_POST['template'];
  
  if(!$cancelProcess)
  {
  	$result = db::get_connection("SELECT Header,Subject,Body,Footer FROM email WHERE UserID='".$_SESSION['UserID']."' AND EmailID='".$template."'");
  	if ($result)
  	{
  	  while($row = mysqli_fetch_array($result))
      {
  	    $Subject = $row['Subject'];
        $Header = $row['Header'];
        $Body = $row['Body'];
        $Footer = $row['Footer'];
      }
  	} else {
  	  $cancelProcess = true;
  		echo "<p>Couldn't connect to the database. </p>";
  		echo "<br />";
  		echo mysqli_error($result);
//  	  mysqli_close($db_con);
  	}
    
    // message
    $message = "<html>
      <head>
        <title>BUMC Crop Registration</title>
      </head>
      <body>
        <p style='font: 14px Comic Sans MS;'>".$Header."</p>
        <p style='font: 14px Comic Sans MS;'>".$CustomMsg."</p>
        <p style='font: 14px Comic Sans MS;'>".$Body."</p>
        <p style='font: 14px Comic Sans MS;'>".$Footer."</p>
      </body>
      </html>";
  }
  if (!$cancelProcess)
  {
    //phpmailer test
    //include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded
     
    $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
     
    $mail->IsSMTP(); // telling the class to use SMTP
     
    try {
      //who it's going to
      $mail->AddReplyTo($useremail, $userfriendlyemail);
      $mail->AddAddress($email);
      $mail->AddBCC($userbcc);
      //who it's from
      $mail->SetFrom($useremail, $userfriendlyemail);
      //subject
      $mail->Subject = $Subject;
      //alt body
      $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
      //$mail->MsgHTML(file_get_contents('contents.html'));
      
      //attachments
      //$mail->AddAttachment('images/webheader.jpg');
      
      
      //$mail->AddEmbeddedImage('images/webheader.jpg', 'webheader', 'webheader.jpg'); // attach file logo.jpg, and later link to it using identfier logoimg
      //$mail->AddEmbeddedImage('images/susansignature.png', 'susansignature', 'susansignature.png'); // attach file logo.jpg, and later link to it using identfier logoimg
      $mail->Body = $message;
        
      $mail->Send();
      //echo "<div id='customMessageOutput'>Sent</div>";
    } catch (phpmailerException $e) {
      echo $e->errorMessage(); //Pretty error messages from PHPMailer
    } catch (Exception $e) {
      echo $e->getMessage(); //Boring error messages from anything else!
    }
  }
  
}
if(isset($_POST['regid']) && isset($_POST['sendEmail']) && isset($_POST['template']) && !$cancelProcess)
{
  $CustomMsg;
  $Header;
  $Subject;
  $Body;
  $Footer;
  $email;
  $template;

	$userid = $_SESSION['UserID'];

	$data = getSettings($userid);
	$useremail = $data->email;
	$userfriendlyemail = $data->friendlyemail;
	$userbcc = $data->bcc;

	if($userbcc != 1) {
		//it is a no, blank it
		$userbcc = "";
	} else {
		$userbcc = $useremail;
	}
  
  $template = $_POST['template'];
  $regid = $_POST['regid'];
  $tbl = "registration";
  
  //grab the message to be sent
  if(!$cancelProcess)
  {
  	$result = db::get_connection("SELECT CustomMessageBody,EmailAddress FROM ".$tbl." WHERE regid='".$regid."'");
  	if ($result) {
  	  while($row = mysqli_fetch_array($result)) {
        $CustomMsg = $row['CustomMessageBody'];
        $email = $row['EmailAddress'];
      }
  	} else {
  	  $cancelProcess = true;
  		echo "<p>Couldn't connect to the database. </p>";
  		echo "<br />";
  		echo mysqli_error($result);
//  	  mysqli_close($db_con);
  	}
  }
  
  //grab the current header
  if(!$cancelProcess)
  {
  	$result = db::get_connection("SELECT Header,Subject,Body,Footer FROM email WHERE UserID='".$_SESSION['UserID']."' AND EmailID='".$template."'");
  	if ($result)
  	{
  	  while($row = mysqli_fetch_array($result))
      {
  	    $Subject = $row['Subject'];
        $Header = $row['Header'];
        $Body = $row['Body'];
        $Footer = $row['Footer'];
      }
  	} else {
  	  $cancelProcess = true;
  		echo "<p>Couldn't connect to the database. </p>";
  		echo "<br />";
  		echo mysqli_error($result);
//  	  mysqli_close($db_con);
  	}
  }
  //we have grabbed the message to send
  
  if(!$cancelProcess)
  {
    // message
    $message = "<html>
      <head>
        <title>BUMC Crop Registration</title>
      </head>
      <body>
        <p style='font: 14px Comic Sans MS;'>".$Header."</p>
        <p style='font: 14px Comic Sans MS;'>".$CustomMsg."</p>
        <p style='font: 14px Comic Sans MS;'>".$Body."</p>
        <p style='font: 14px Comic Sans MS;'>".$Footer."</p>
      </body>
      </html>";
    
  }
  if(!$cancelProcess)
  {
    $result = db::get_connection("UPDATE ".$tbl." SET CustomMessageBdySent='1' WHERE regid='".$regid."'");
    if ($result)
    {
      //do nothing
    } else {
      $cancelProcess = true;
    	echo "<p>Couldn't connect to the database. </p>";
    	echo "<br />";
    	echo mysqli_error($result);
//      mysqli_close($db_con);
    }
  }
  
  if (!$cancelProcess)
  {
    //phpmailer test
    //include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded
     
    $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
     
    $mail->IsSMTP(); // telling the class to use SMTP
     
    try {
      //who it's going to
      $mail->AddReplyTo($useremail, $userfriendlyemail);
      $mail->AddAddress($email);
      $mail->AddBCC($userbcc);
      //who it's from
      $mail->SetFrom($useremail, $userfriendlyemail);
      //subject
      $mail->Subject = $Subject;
      //alt body
      $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
      //$mail->MsgHTML(file_get_contents('contents.html'));
      
      //attachments
      //$mail->AddAttachment('images/webheader.jpg');
      
      
      //$mail->AddEmbeddedImage('images/webheader.jpg', 'webheader', 'webheader.jpg'); // attach file logo.jpg, and later link to it using identfier logoimg
      //$mail->AddEmbeddedImage('images/susansignature.png', 'susansignature', 'susansignature.png'); // attach file logo.jpg, and later link to it using identfier logoimg
      $mail->Body = $message;
        
      $mail->Send();
      //echo "<div id='customMessageOutput'>Sent</div>";
    } catch (phpmailerException $e) {
      echo $e->errorMessage(); //Pretty error messages from PHPMailer
    } catch (Exception $e) {
      echo $e->getMessage(); //Boring error messages from anything else!
    }
  }
}

if(isset($_POST['newHeader']) && isset($_POST['changeHeader']) && !$cancelProcess)
{
	$result = db::get_connection("UPDATE email SET Header='".$_POST['newHeader']."'");
	if ($result)
	{
	  echo $_POST['newHeader'];
	} else {
	  $cancelProcess = true;
		echo "<p>Couldn't connect to the database. </p>";
		echo "<br />";
		echo mysqli_error($result);
//	  mysqli_close($db_con);
	}
}

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
//    mysqli_close($db_con);
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
//    mysqli_close($db_con);
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
//    mysqli_close($db_con);
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
//    mysqli_close($db_con);
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
//    mysqli_close($db_con);
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
//    mysqli_close($db_con);
    echo mysqli_error($result);
  }


}

//mysqli_close($db_con);

}
?>