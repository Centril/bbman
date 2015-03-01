<?php
/*--------------------------------------------
|	file = admin/viewguildtax.php
|	description : users can view their tax
|	info here
|	--------------
|	copyright = (C) 2005 The Magicasoft Group
|	email = support@magicasoft.net
*-------------------------------------------*/

session_start(); // start the sessions
level_check('0'); // check if access is granted

// get settings
$setting = array();
$DB->query('SELECT * FROM '.$table_prefix.'settings','settings_sql');
while($row = $DB->fetch_assoc('settings_sql'))
$setting[$row['name']] = $row['setting'];

if($setting['guildtax'] === 'yes') // check if the guildtax feature is enabled
{
	$elapsedseconds = date('U'); // seconds gone since 1/1 - 1970
	if(floor( ($elapsedseconds - $setting['guildtax.lasttimeupdate']) / 86400) >= $setting['guildtax.taxevery']) // get how many days ago there was an update and check if its >= guildtax.taxevery
	{
		// update the guildtax.lasttimeupdate setting with $elapsedseconds
		$DB->query("UPDATE {$table_prefix}settings SET setting = '{$elapsedseconds}' WHERE name = 'guildtax.lasttimeupdate'",'update-guildtax.lasttimeupdate_sql');
		// add $setting['guildtax.tax'] to every users remains
		$DB->query("UPDATE {$table_prefix}guildtaxes SET remains = remains + {$setting['guildtax.tax']}",'update-all-userstaxes_sql');
	}

	// if the user dose not exist we will make it
	$DB->query("SELECT remains,inactive FROM {$table_prefix}guildtaxes WHERE uname = '{$_SESSION['uname']}'",'unameguildtax_sql');
	$own = $DB->fetch_assoc('unameguildtax_sql');
	$remains = $own['remains'];
	$inactive = $own['inactive'];
	if($DB->num_rows('unameguildtax_sql') === 0)
	{
		$remains = $setting['guildtax.tax'];
		$inactive = 'no';
		$uname = $_SESSION['uname'];
		$DB->query("INSERT {$table_prefix}guildtaxes(remains,inactive,uname) VALUES('{$remains}','no','{$uname}')",'add-user-to-guild-taxes_sql');
	}
	
	// display the users guildtax
	if($inactive === 'yes') //  if the user is inactive
	{
		echo '<div style="text-align:center; margin-top:20px;">since you are inactive, you don\'t have to pay<div>';
	}
	elseif($remains <= 0 && $inactive === 'no') // if the user has nothing left to pay
	{
		echo '<div style="text-align:center; margin-top:20px;">you have payed your taxes for this period of '.$setting['guildtax.taxevery'].'days<div>';
	}
	elseif($remains > 0 && $inactive === 'no') // if the user has something left to pay
	{
		echo '<div style="text-align:center; margin-top:20px;">you have '.$remains.' left to pay<div>';
	}
	run_log("viewed his/her tax specefications");
}
else // the guildtax feature is not enabled
{
	echo 'not enabled by the administrators';
}
?>