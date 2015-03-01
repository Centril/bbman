<?php
/*--------------------------------------------
|	file = admin/guildtax.php
|	description : add/delete/edit users
|	guildtaxes
|	--------------
|	copyright = (C) 2005 The Magicasoft Group
|	email = support@magicasoft.net
*-------------------------------------------*/

session_start(); // start the sessions
level_check('8'); // check if access is granted

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

	// add the users that do not exist in the guildtax table
	$DB->query('SELECT '.$table_prefix.'users.username FROM '.$table_prefix.'users LEFT OUTER JOIN '.$table_prefix.'guildtaxes AS gt ON gt.uname = '.$table_prefix.'users.username WHERE gt.uname = '.$table_prefix.'users.username IS NULL','check-all-users-exists-in-gt_sql');
	while($notin_gt = $DB->fetch_assoc('check-all-users-exists-in-gt_sql'))
	{
		$remains = $setting['guildtax.tax'];
		$inactive = 'no';
		$uname = $notin_gt['username'];
		$DB->query("INSERT {$table_prefix}guildtaxes(remains,inactive,uname) VALUES('{$remains}','no','{$uname}')",'add-user-to-guild-taxes_sql','yes');
	}

	// edit an user's guildtax
	if(isset($_POST['edit']) && $_POST['edit'] === 'Edit')
	{
		// populate the variables with the information thats needed
		$uname = (string)$_POST['uname'];
		$inactive = (string)$_POST['inactive'];

		$payed = (int)$_POST['payed'];
		$remains = (int)$_POST['remains'];
		$topay = $remains - $payed;

		$DB->query("UPDATE {$table_prefix}guildtaxes SET remains = '{$topay}',inactive = '{$inactive}' WHERE uname = '{$uname}'",'_sql'); // update the user's guildtax

		// print the action that was done
		if($inactive === 'yes')
		{
			echo "<div style=\"text-align:center;\"><br/>{$uname} is now inactive and has nothing to pay in taxes</div>";
		}
		else
		{
			echo "<div style=\"text-align:center;\"><br/>{$uname} has got to pay {$topay} more in tax<br/>and is not inactive</div>";
		}
		echo('<meta http-equiv="Refresh" content="3; url=index.php?mode=guildtax">');
		run_log("edited {$uname}'s tax specefications");
	}
	?>
	<div style="text-align:center;"><br/>
	<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="0">
	<tr><td>
	<div class="tcat" style="padding: 4px; text-align: center;"><b>Manage Guildtaxes</b></div>
	<table rules="all" class="logincontrols" border="0" cellpadding="4" cellspacing="0" width="100%">
	<tbody>
	<tr><td>username</td><td>payed</td><td>inactive</td><td>left to pay</td><td>edit</td></tr>
	<?php
	$DB->query('SELECT * FROM '.$table_prefix.'guildtaxes','manage-guildtaxes_sql'); // get the users
	$i = 0; // this is used to know which form we are on
	while($gt = $DB->fetch_assoc('manage-guildtaxes_sql'))
	{
		// print out the manage row for the BBed player
		$selected[$gt['inactive']] = ' selected';
		$reset .= "document.forms[{$i}].reset(); ";
		echo "<form method=\"post\" action=\"?mode=guildtax\">
		<tr><td>{$gt['uname']}<input name=\"uname\" type=\"hidden\" value=\"{$gt['uname']}\"/></td><td><input name=\"payed\" type=\"text\"/></td>
		<td><select name=\"inactive\"><option value=\"yes\"{$selected['yes']}>yes</option><option value=\"no\"{$selected['no']}>no</option></select></td>
		<td>{$gt['remains']}<input name=\"remains\" type=\"hidden\" value=\"{$gt['remains']}\"/></td><td><input type=\"submit\" name=\"edit\" value=\"Edit\" onclick=\"return lp_check('#yes','#no','if you are sure that you want to edit {$gt['uname']}\'s guildtax specefications \\n then write #yes and press OK');\"/></td></tr></form>";
		$i++;
	}
	?>
	</tbody><tbody><tr>
	<td colspan="100" align="center"><input class="button" value="  Reset  " type="reset" onClick="<?php echo $reset; ?>"/></td></tr></tbody></table></td></tr>
	</tbody></table>
	</div>
	<?php
}
else // the guildtax feature is not enabled
{
	echo 'not enabled by the administrators';
}
?>