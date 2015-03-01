<?php
/*--------------------------------------------
|	file = admin/setts.php
|	description : edit the settings
|	--------------
|	copyright = (C) 2005 The Magicasoft Group
|	email = support@magicasoft.net
*-------------------------------------------*/

session_start(); // start the sessions
level_check('5'); // check if access is granted
?>
<div style="text-align:center;"><p>please do not edit the online.unixtime setting (it is used to know when to refresh the online cache)</p>
<p>The online.reftime setting decides how many minutes the cache should be used before refreshing</p>
<p>The glist.guild setting is used to determine which guild to load its members from</p>
<p>guildtax.tax is the amount of tax users are to pay each time</p>
<p>guildtax.taxevery is the amount of days that the users tax is for</p>
<p>please do not edit the guildtax.lasttimeupdate setting (it is used to know when to set new taxes)</p>
<p>The rest is yes/no settings that turn the settings off or on</p>
<form action="?mode=setts" method="post">
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="0">
<tr>
<td>
<div class="tcat" style="padding: 4px; text-align: center;"><b>Manage Settings</b></div>
<table rules="all" class="logincontrols" border="0" cellpadding="4" cellspacing="0" width="100%">
<tbody>
<tr><td>setting</td><td>value</td></tr>
<?php
$DB->query('SELECT * FROM '.$table_prefix.'settings','admin-settings-showall_sql'); // get the settings
while($setting = $DB->fetch_assoc('admin-settings-showall_sql'))
{
	// display the manage row for the setting
	?>
	<tr>
	<td><?php echo $setting['name'];?></td>
	<td><input type="text" name="<?php echo $setting['name'];?>" value="<?php echo $setting['setting'];?>"/></td>
	</tr>
	<?php
}
?>
</tbody><tbody><tr>
<td colspan="100" align="center"><input value="  Reset  " type="reset"/>&nbsp;<input value=" Edit " name="edit" type="submit" onclick="return lp_check('#yes','#no','if you are sure that you want to edit all the settings\n then write #yes and press OK');"/></td></tr></tbody></table></td></tr>
</tbody></table></form>
</div>
<?php
// continue function
function contin()
{
	continue;
}

// if we are editing the settings
if(isset($_POST['edit']) && $_POST['edit'] === ' Edit ')
{
	unset($_POST['edit']); // this will not be needed
	foreach($_POST as $name => $setting) // edit every setting
	{
		if(preg_match('/\W/',$setting)) continue; // validate
		$name = preg_replace('/_/','.',$name); // change _s to .s
		// edit it
		$DB->query("UPDATE {$table_prefix}settings SET setting = '{$setting}' WHERE name = '{$name}'",'admin-settings-updateall_sql','yes') or contin();
		echo '<div style="text-align:center;">setting : '.$name.' was edited</div>';
	}
	echo('<meta http-equiv="Refresh" content="5; url=?mode=setts">');
	run_log("edited all the settings");
}
?>