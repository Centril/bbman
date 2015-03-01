<?php
/*--------------------------------------------
|	file = admin/log.php
|	description : check what the other users
|	have done
|	--------------
|	copyright = (C) 2005 The Magicasoft Group
|	email = support@magicasoft.net
*-------------------------------------------*/

session_start(); // start the sessions
level_check('7'); // check if access is granted

$DB->query('SELECT * FROM '.$table_prefix.'log ORDER BY id DESC','admin-log-all_sql'); // get all the loggings that were made
if($DB->num_rows('admin-log-all_sql') > 0) // check if there were any logs
{
	echo '<a href="?mode=log&amp;&reset=yes">reset</a>'; // display the reset log link
	echo '<pre><b>';
	// display each log
	while($logpart = $DB->fetch_assoc('admin-log-all_sql'))
	{
		extract($logpart);
		echo 'at '.$day.' '.$time.' '.$uname.' '.$action.'<br/>';
	}
}
else echo '<pre><b>the log is empty'; // display that the log is empty
echo '</b></pre>';

// reseting the log
if(isset($_GET['reset']) && $_GET['reset'] === 'yes')
{
	// reset it
	$DB->query('DELETE FROM '.$table_prefix.'log','admin-log-resetlog_sql');
	echo '<br/>the log was reseted';
	echo('<meta http-equiv="Refresh" content="3; url=?mode=log">');
}
?>