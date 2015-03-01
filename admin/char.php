<?php
/*--------------------------------------------
|	file = admin/char.php
|	description : add/delete/edit characters
|	--------------
|	copyright = (C) 2005 The Magicasoft Group
|	email = support@magicasoft.net
*-------------------------------------------*/

session_start(); // start the sessions
level_check('1'); // check if access is granted
?>
<div style="text-align:center; "><br>
<form method="post" action="" name="addform">
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td>
<div class="tcat" style="padding: 4px; text-align: center;"><b>Add Character</b></div>
<table rules="all" class="logincontrols" border="0" cellpadding="4" cellspacing="0" width="100%">
<tbody>
<tr>
<?php
$notneeded = array('Online' => '','id' => ''); // the fields that isn't needed (Online,id)
// for each through the columns in table 'main', and fix $the input fields and texts
foreach($DB->showcolumns($table_prefix.'main') as $field)
{
	if(isset($notneeded[$field['name']]))
	{
		continue;
	}
	$headers .= "<td>{$field['name']}</td>";
	$inputs .= "<td><input style=\"padding-left: 5px; font-weight: bold; width: 90px;\" name=\"use_{$field['name']}\" type=\"textfield\"/></td>";
	$editinp .= "<td><input style=\"padding-left: 5px; font-weight: bold; width: 80px;\" name=\"edit_{$field['name']}\" type=\"textfield\" value=\"".'{$'.$field['name']."}\"/></td>";
}
echo $headers.'</tr>';
echo '<tr>'.$inputs;
?>
</tr></tbody><tbody><tr>
<td colspan="100" align="center"><input name="submit" class="button" value="  Add  " type="submit" onclick="return lp_check('#yes','#no','if you are sure that you want to add \'' + addform.use_Name.value + '\' \n then write #yes and press OK');"/></td></tr></tbody></table></td></tr>
</tbody></table>
</form>
</div>
<?php
$action = 'nothing has happend yet';
// adding a player to the blackbook
if(isset($_POST['submit']) && $_POST['submit'] === '  Add  ')
{
	$i = 0;
	// check if all the fields are filled and make parts of the insert statement
	foreach($_POST as $postkey => $postval)
	{
		if(!strstr($postkey,'use_')) continue;
		
		if(empty($_POST[$postkey])) {$action = 'All the fields were not filled, please fill them.'; $goon = 'no'; break;}
		
		$postkey = str_replace('use_','',$postkey);
		if($i === 0) {$columns .= $postkey; $i++; $values .= "'{$postval}'"; $action = $postval.' was added'; $useinlog = $postval;}
		else {$columns .= ','.$postkey; $values .= ",'{$postval}'";}
	}
	if($goon !== 'no') // if we are to go on
	{
		// add the player to the blackbook
		$DB->query("INSERT {$table_prefix}main({$columns}) VALUES({$values})",'admin-char-new_sql');
		echo('<meta http-equiv="Refresh" content="3; url=index.php">');
		run_log("added {$useinlog} to the BlackBook");
	}
}
// editing a player
elseif(isset($_POST['editsubmit']) && $_POST['editsubmit'] === 'Edit')
{
	$i = 0;
	// check if all the fields are filled and make parts of the insert statement
	foreach($_POST as $postkey => $postval)
	{
		if(!strstr($postkey,'edit_')) continue;
		$id = $_POST['id'];
		if(empty($_POST[$postkey])) {$action = 'All the fields in the managing place were not filled, please fill them.'; $goon = 'no'; break;}
		
		$postkey = str_replace('edit_','',$postkey);
		if($i === 0) {$i++; $set .= "{$postkey} = '{$postval}'"; $action = $postval.' was edited'; $useinlog = $postval;}
		else {$set .= ", {$postkey} = '{$postval}'";}
	}
	if($goon !== 'no') // if we are to go on
	{
		// edit the blackbooked player
		$DB->query("UPDATE {$table_prefix}main SET {$set} WHERE id = {$id}",'admin-char-edit_sql');
		echo('<meta http-equiv="Refresh" content="3; url=index.php">');
		run_log("edited BlackBooked player {$useinlog}");
	}
}
// delete a player from the blackbook
elseif(isset($_GET['delid']) && preg_match('/^0x\d+/',$_GET['delid']))
{
	$delid = str_replace('0x','',$_GET['delid']); // delete preceding 0x string
	if(!preg_match('/[\D]+/',$delid)) // make sure that no illegal letters is in it
	{
		eval("\$delid = $delid;");
		$delid = base_convert($delid,2,10); // make a base convert
		if($delid === 0) // the id can not be 0 (then the user is trying to hack the admin part)
		{
			die('<center>Hacking atempt!</center>');
		}
	}
	else die('<center>Hacking atempt!</center>');
	
	// test if there were no players with that name (otherwise the user is doing an hacking atempt)
	$DB->query("SELECT id FROM {$table_prefix}main WHERE id = '{$delid}'",'admin-char-delete-ifexists_sql');
	if($DB->num_rows('admin-char-delete-ifexists_sql') < 1) die('<center>Hacking atempt!</center>');
	
	$DB->query("DELETE FROM {$table_prefix}main WHERE id = '{$delid}'",'admin-char-delete_sql'); // delete the BBed player
	echo('<meta http-equiv="Refresh" content="3; url=index.php">');
	$name = (string)preg_replace('/[\s\W]+/','',$_GET['name']);
	$action = $name.' was deleted';
	run_log("deleted BlackBooked player {$name}");
}

echo '<div style="text-align:center;">'.$action.'</div>';
?>
<div style="text-align:center;"><br>
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="0">
<tr>
<td>
<div class="tcat" style="padding: 4px; text-align: center;"><b>Manage Characters</b></div>
<table rules="all" class="logincontrols" border="0" cellpadding="4" cellspacing="0" width="100%">
<tbody>
<tr>
<?php
echo $headers.'<td>edit</td><td>delete</td></tr>';

$DB->query('SELECT * FROM '.$table_prefix.'main','admin-char-listshowall_sql'); // get all the BBed players
$i = 1; // this is used to know which form we are on
while($val = $DB->fetch_assoc('admin-char-listshowall_sql'))
{
	extract($val);
	$reset .= "document.forms[{$i}].reset(); "; // add the reset code for the form
	$i++;
	
	// print out the manage row for the BBed player
	eval("\$delid = \"$id\";");
	$editinput = preg_replace('/\"/','\\\"',$editinp);
	eval("\$editinput = \"$editinput\";");
	echo "<form method=\"post\" action=\"\" name=\"{$Name}editform\"><tr>".$editinput."<td><input type=\"hidden\" name=\"id\" value=\"{$id}\"><input name=\"editsubmit\" class=\"button\" value=\"Edit\" type=\"submit\" onclick=\"return lp_check('#yes','#no','if you are sure that you want to edit {$Name} \\n then write #yes and press OK');\" style=\"width:30px;\"/></td><td><a href=\"index.php?name={$Name}&amp;delid=0x".base_convert($delid,10,2)."\" onclick=\"return lp_check('#yes','#no','are you sure that you want to delete {$Name} \\n after deleting it there will be no way to get it back\\nthen write #yes and press OK');\">delete</a></td>".'</tr></form>
';
}
?>
</tbody><tbody><tr>
<td colspan="100" align="center"><input class="button" value="  Reset  " type="reset" onClick="<?php echo $reset;?>"/></td></tr></tbody></table></td></tr>
</tbody></table>
</div>