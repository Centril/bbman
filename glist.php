<?php
/*--------------------------------------------
|	file = glist.php
|	description : A module which shows all the
|	members in a guild in the game Tibia
|	--------------
|	copyright = (C) 2005 The Magicasoft Group
|	email = support@magicasoft.net
*-------------------------------------------*/

// include dependiances
require('config.php');
require('db/'.$servertype.'.php');

// connect to the database in use
$DB = new DB($host,$username,$password,$database);
$DB->connect();

// get settings
$setting = array();
$DB->query('SELECT * FROM '.$table_prefix.'settings','settings_sql');
while($row = $DB->fetch_assoc('settings_sql'))
$setting[$row['name']] = $row['setting'];

// check if the glist feature is not enabled
if($setting['glist'] !== 'yes') die('Sorry, the glist function is not enabled by the administrator');

// if we are to show all the guildmembers or the ones that are online
$show = (isset($_GET['oo']) AND $_GET['oo'] === 'yes') || (isset($_POST['oo']) AND $_POST['oo'] === 'yes') ? 'online' : 'all';

// get the guild name
$guild = ucfirst($setting['glist.guild']);
$guild = preg_replace('/\s/','+',$guild);

$world = ucfirst($setting['online.world']); // get game world

// online work
$currdate = date('U'); // seconds gone since 1/1 - 1970
$gonedate = (int) $setting['online.unixtime']; // seconds from 1/1 - 1970 to the last online update

// this condtional if statement is used for updating the online list
if(round( ($currdate - $gonedate) / 60) >= $setting['online.reftime']) // get how many minutes ago there was an update and check if its >= the reftime
{
	$p_list = file_get_contents('http://www.tibia.com/statistics/?subtopic=whoisonline&world='.$world); // get the players file
	// process the array we want to cache
	$p_list = split("<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%>\s|\S*</TABLE>",$p_list);
	$p_list = strip_tags($p_list[12],'<tr>,<td>');
	$p_list = preg_replace('!<TR BGCOLOR=#\w{6,6}|</TR>|</TD>!','',$p_list);
	$p_list = preg_replace('/<TD>|<TD WIDTH=[0-9]+%>/','+',$p_list);
	$p_list = str_replace('&#160;',' ',$p_list);
	$p_list = explode('>',$p_list);
	unset($p_list[0],$p_list[1]);

	$DB->query("DELETE FROM {$table_prefix}online WHERE delid = '1'",'online-deleteall_sql'); // delete every row in the online table

	// cache each player
	foreach($p_list as $val)
	{
		$temp = explode('+',$val); // split it
		$name = strtolower($temp[1]); // lowercase name
		$lvl = strtolower($temp[2]); // lowercase level
		
		$DB->query("INSERT {$table_prefix}online (delid,name,lvl) VALUES('1',\"{$name}\",'{$lvl}')",'online-insert_sql','yes'); // insert a Tibia player to the online chache
	}
	unset($p_list);
	$DB->query("UPDATE {$table_prefix}settings SET setting = '$currdate' WHERE name = 'online.unixtime'",'online-update-unixtime_sql'); // set the last time updated to now
}

$DB->query('SELECT name,lvl FROM '.$table_prefix.'online ORDER BY name','online-onusers_sql'); // get every Tibia player in the online cache
while($load = $DB->fetch_assoc('online-onusers_sql'))
{
	$on_list[$load['name']] = $load['lvl']; // save each player in an array
}

// guild work , make an array of the members of a specefied tibia guild
$members = file_get_contents('http://www.tibia.com/community/?subtopic=guilds&page=view&GuildName='.$guild); // get the members file
$members = split("<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%>\s|\S*</TABLE>",$members); // get the big parts
$members = strip_tags($members[8],'<tr>'); //take the members element
$members = preg_replace('!<TR BGCOLOR=#\w{6,6}|</TR>!','',$members);
$members = str_replace('&#160;',' ',$members);
$members = str_replace(')','',$members);
$members = explode('>',$members);
unset($members[0],$members[1],$members[2]);

// handle guild members
foreach($members as $memberstat)
{
	$memberstat = explode("\n",$memberstat);

	if(preg_match('/\S/',$memberstat[0]))
	{
		$m_rank = preg_replace('/\s$/','',$memberstat[0]);
	}

	// get the member desciption
	list($memberstat[1],$m_desc) = explode(' (',$memberstat[1]);

	$on = ( isset($on_list[strtolower($memberstat[1])]) ? 'yes' : 'no'); // see if the member is online

	// choose mode , online members or all members
	if($show === 'online' && $on === 'yes')
	{
		$m_list[$m_rank][$memberstat[1]] = array('name' => $memberstat[1],'title' => $m_desc,'joined' => $memberstat[2],'online' => $on); // add guild member to the list
	}
	elseif($show === 'all')
	{
		$m_list[$m_rank][$memberstat[1]] = array('name' => $memberstat[1],'title' => $m_desc,'joined' => $memberstat[2],'online' => $on); // add guild member to the list
	}
}

// handle output
$DB->query('SELECT tpl FROM '.$table_prefix.'styles WHERE style = "guildmemberlist"','glist-style-guildmemberlist_sql');
$style_glist = $DB->fetch_row('glist-style-guildmemberlist_sql');
$style_glist[0] = preg_replace('/"/','\"',$style_glist[0]);

$outerstyles = split('_ranks_',$style_glist[0]);
eval('$output = "'.$outerstyles[0].'";');

$rankstyle = split('_member_',$outerstyles[1]);
foreach($m_list as $rank => $members)
{
	$uorank = $rank;
	eval('$output .= "'.$rankstyle[0].'";');

	$memberstyle = $rankstyle[1];
	foreach($members as $vals)
	{
		extract($vals);
		eval('$output .= "'.$memberstyle.'";');
	}
	eval("\$output .= \"$rankstyle[2]\";");
}
eval("\$output .= \"$outerstyles[2]\";");
echo $output; // print it out

$DB->close(); // close the database
?>