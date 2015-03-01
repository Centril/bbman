<?php
/*--------------------------------------------
|	file = admin/cpfunc.php
|	description : The admin functions
|	--------------
|	copyright = (C) 2005 The Magicasoft Group
|	email = support@magicasoft.net
*-------------------------------------------*/

// check authorization for a page
function level_check($level = '')
{
	// checks if the user has not logged in
	if(!isset($_SESSION['auth']))
	{
		return die(header("location: login.php?action=login")); // send the user to the login page
	}
	// the logical opposite of not beeing logged in is to be logged in
	else
	{
		if($level === '') // a level check is not requested so do nothing
		{
		}
		elseif(in_array($level,$_SESSION['auth'])) // check if the user has the level that was requested then do nothing
		{
		}
		else // since a level check is used and the user hasn't the needed level, the page must terminate emediatly
		{
			die('Access Denied!');
		}
	}
}
// log a users action
function run_log($action)
{
	global $DB,$table_prefix; // globalize important things
	$day = (string)date('j/F - Y'); // todays date in dd/textual month representation - yyyy format
	$time = (string)date("H:i:s"); // todays time in hours:minutes:seconds with leading zeroes format
	$uname = (string)$_SESSION['uname']; // the user that did the action
	$DB->query("INSERT {$table_prefix}log (day,time,uname,action) VALUES(\"{$day}\",\"{$time}\",\"{$uname}\",\"{$action}\")",'logger-query_sql'); // insert to the log
}
?>