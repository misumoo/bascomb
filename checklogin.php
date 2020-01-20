<?php

/**
 * @author    Chris & Robert Whetzel
 * @copyright 2010
 */

session_start();

require 'lib/db.php';

$dbError = false;

if (isset($_POST['username']) && isset($_POST['password']))
{
	$username = $_POST['username'];
	$userpass = $_POST['password'];
	$result = db::get_connection("SELECT * FROM tblusers WHERE UserName = '" . $username . "' AND Password = '" . $userpass . "'");
	if (mysqli_num_rows($result) == 1)
	{
		while ($row = mysqli_fetch_array($result))
		{
			$_SESSION['UserID'] = $row['UserID'];
		}
		$_SESSION['authUser'] = "1";
		header("Location: reglogin.php");
	}
	else
	{
		$dbError = true;
		$_SESSION['errorLogin'] = "Invalid Username or Password.";
		header("Location: login.php");
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1"/>
    <title>BUMC Admin</title>
</head>

<body>
</body>

</html>