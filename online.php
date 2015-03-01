<?php
/*--------------------------------------------
|	file = online.php
|	description : checks if a bbedplayer is
|	online and then updates the database
|	--------------
|	copyright = (C) 2005 The Magicasoft Group
|	email = support@magicasoft.net
*-------------------------------------------*/

$currdate = date('U'); // seconds gone since 1/1 - 1970
$gonedate = (int) $setting['online.unixtime']; // seconds from 1/1 - 1970 to the last online update

// this condtional if statement is used for updating the online list
if(round( ($currdate - $gonedate) / 60) >= $setting['online.reftime']) // get how many minutes ago there was an update and check if its >= the reftime
{
	$world = ucfirst($setting['online.world']); // get game world

	// online players , make an array of the online players on a specefied tibia server
	$p_list = file_get_contents('http://www.tibia.com/statistics/?subtopic=whoisonline&world='.$world); // get the players file
	$p_list = split("<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%>\s|\S*</TABLE>",$p_list);
	$p_list = strip_tags($p_list[12],'<tr>,<td>');
	$p_list = preg_replace('!<TR BGCOLOR=#\w{6,6}|</TR>|</TD>!','',$p_list);
	$p_list = preg_replace('/<TD>|<TD WIDTH=[0-9]+%>/','+',$p_list);
	$p_list = str_replace('&#160;',' ',$p_list);
	$p_list = explode('>',$p_list);
	unset($p_list[0],$p_list[1]);

	$DB->query("DELETE FROM {$table_prefix}online WHERE delid = '1'",'online-deleteall_sql'); // delete every row in the online table

	foreach($p_list as $val)
	{
		$temp = explode('+',$val); // split it
		$name = strtolower($temp[1]); // lowercase name
		$lvl = strtolower($temp[2]); // lowercase level

		$DB->query("INSERT {$table_prefix}online (delid,name,lvl) VALUES('1',\"{$name}\",'{$lvl}')",'online-insert_sql','yes'); // insert a Tibia player to the online chache
	}
	$DB->query("UPDATE {$table_prefix}settings SET setting = '$currdate' WHERE name = 'online.unixtime'",'online-update-unixtime_sql'); // set the last time updated to now
}

$DB->query('SELECT name,lvl FROM '.$table_prefix.'online ORDER BY name','online-onusers_sql'); // get every Tibia player in the online cache
while($load = $DB->fetch_assoc('online-onusers_sql'))
{
	$on_list[$load['name']] = $load['lvl']; // save each player in an array
}

$DB->query('SELECT Name FROM '.$table_prefix.'main','online-bbedplayers_sql'); // get all the bbed players
while($name = $DB->fetch_row('online-bbedplayers_sql'))
{
	$name = strtolower($name[0]); // lowercase so that we can match them
	if(isset($on_list[$name])) // if the element with $name as key is found the player is online
	{
		$stat = 'yes';
		$lvl = $on_list[$name];
		// since the player is online, we will update the players level
		$DB->query("UPDATE {$table_prefix}main SET Lvl = '{$lvl}' WHERE Name = '".addslashes($name)."'",'online-update-bbedplayerslvl_sql','yes');
	}
	else // if the statement above didn't execute the player is offline
	{
		$stat = 'no';
	}
	// update the players online status
	$DB->query("UPDATE {$table_prefix}main SET Online = '{$stat}' WHERE Name = '".addslashes($name)."'",'online-update-bbedplayers_sql','yes');
}
?>