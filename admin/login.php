<?php
/*--------------------------------------------
|	file = admin/login.php
|	description : login/logout page for users
|	--------------
|	copyright = (C) 2005 The Magicasoft Group
|	email = support@magicasoft.net
*-------------------------------------------*/

session_start(); // start the sessions
// include dependiances
require('../config.php');
require('../db/'.$servertype.'.php');
require('cpfunc.php');

// connect to the database
$DB = new DB($host,$username,$password,$database);
$DB->connect();

switch($_GET['action'])
{
case'login': // logging in
	if(isset($_SESSION['auth'])) // check if we are already logged in
	{
		header("location:./");
	}
	
	// display the login form
	echo'<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
	<html><style type="text/css"><!--.style1 {font-size: 12pt}--></style><title>BBman CP :: login</title>
	<link href="cpstyles.css" rel="stylesheet" type="text/css"></head><body>
	<div style="margin: 10px;"><br>
	<form method="POST" action="?action=loginver">
	<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="0" width="450">
	<tbody><tr><td><div class="tcat" style="padding: 4px; text-align: center;"><b>Log in</b></div>
	<table class="logincontrols" border="0" cellpadding="4" cellspacing="0" width="100%">
	<col style="text-align: right; white-space: nowrap;" width="50%" /><col/><col width="50%" />
	<tbody><tr><td>User Name</td><td><input style="padding-left: 5px; font-weight: bold; width: 250px;" name="username" value="" type="text"/></td></tr><tr>
	<td>Password</td><td><input style="padding-left: 5px; font-weight: bold; width: 250px;" name="password" type="password"/></td></tr></tbody>
	<tbody id="loginoptions" style="display: none;"></tbody><tbody><tr>
	<td colspan="3" align="center"><input class="button" value="  Log in  " type="submit"/></td></tr></tbody></table></td></tr>
	</tbody></table></form></div></body></html>';
break;

case'loginver': // verificate the login request
	$username = (string)$_POST['username'];
	$password = (string)$_POST['password'];
	if(preg_match('/[^\w\s]+/',$username) && preg_match('/[^\w\s]+/',$password)) die('Illegal password or username'); // make sure that the username and the passwords do not contain illegal data
	
	$DB->query("SELECT * FROM {$table_prefix}users WHERE username='{$username}'",'admin-login-userspec_sql'); // get the user with the specefied username
	$user = $DB->fetch_assoc('admin-login-userspec_sql');
	if($user['username'] === $username && $user['password'] === $password && (int)$DB->num_rows('admin-login-userspec_sql') === 1) // check if the data matches
	{
		// assign the users level to a session
		if($user['level'] === 'admin')
		{
			$_SESSION['auth'] = array('0','1','2','3','4','5','6','7','8'); // all
		}
		elseif($user['level'] === 'moderator')
		{
			$_SESSION['auth'] = array('0','1','2','3','4','6','8'); // all exept users and log
		}
		elseif($user['level'] === 'manager')
		{
			$_SESSION['auth'] = array('0','1'); // tax viewing,characters
		}
		elseif($user['level'] === 'designer')
		{
			$_SESSION['auth'] = array('0','2','3','4'); // tax viewing,fields,easyfind,styles
		}
		elseif($user['level'] === 'onlytaxviewing')
		{
			$_SESSION['auth'] = array('0'); // only tax viewing
		}
		$_SESSION['uname'] = $user['username']; // assign the username to a session
		run_log("logged in");
		header("location:./"); // send the user to the administration page
	}
	else
	{
		// display an error message
		echo'<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
		<html><style type="text/css"><!--.style1 {font-size: 12pt}--></style><title>BBman CP :: login</title>
		<link href="cpstyles.css" rel="stylesheet" type="text/css"></head><body><br/><div style="text-align:center;">wrong username or password</div>
		</body></html>';
	}
break;

case'logout': // if we are logging out
	run_log("logged out");
	// unset and destroy the sessions
	session_unset();
	session_destroy();
	header("location: ?action=login"); // send the user to the login page
break;
}
?>