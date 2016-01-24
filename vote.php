<?php
session_start();

require 'lib/db.php';

function convertForInsert($str)
{
  if ($str != "")
  {
    $str = "\"".$str."\"";
  }
  else
  {
    $str = "NULL";
  }
  return $str;
}

$ip = convertForInsert($_SERVER["REMOTE_ADDR"]);
$vote = convertForInsert($_GET["vote"]);


$sql = "
  INSERT INTO tbl_Votes
    (VoteID, IP_Address, Vote)
  VALUES
    (NULL, $ip, $vote);
";

$result = mysql_query($sql);
if(!$result)
{
  echo mysql_error($result);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />

	<title>BUMC Scrapbook Crop Registratrion</title>
  <link href="registration.css" rel="stylesheet" type="text/css" />
  <link rel="shortcut icon" href="images/site.ico">
  
</head>
  <body>
  
    <p style='text-align: center;font: 25px sans-serif;font-weight: bold;color: #37347A;'>Thank you for your vote!</p>
    
  </body>
</html>