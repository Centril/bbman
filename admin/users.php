<?php
/*--------------------------------------------
|	file = admin/users.php
|	description : add/delete/edit users
|	--------------
|	copyright = (C) 2005 The Magicasoft Group
|	email = support@magicasoft.net
*-------------------------------------------*/

session_start(); // start the sessions
level_check('5'); // check if access is granted
?>
<div style="text-align:center; "><br>
<form method="post" action="?mode=users" name="addform">
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td>
<div class="tcat" style="padding: 4px; text-align: center;"><b>Add User</b></div>
<table rules="all" class="logincontrols" border="0" cellpadding="4" cellspacing="0" width="100%">
<tbody>

<tr>
<td>username</td><td>password</td><td>userlevel</td>
</tr>
<tr>
<td><input style="padding-left: 5px; font-weight: bold; width: 80px;" name="add_username" type="textfield"/></td>
<td><input style="padding-left: 5px; font-weight: bold; width: 80px;" name="add_password" type="textfield"/></td>
<td>
<select name="add_userlevel" style="font-weight:bold;">
<option value="manager">manager</option><option value="onlytaxviewing">can_view_own_taxes</option><option value="designer">designer</option>
<option value="moderator">moderator</option><option value="admin">admin</option>
</select>
</td>
</tr>
</tbody><tbody><tr>
<td colspan="100" align="center"><input name="submit" class="button" value="  Add  " type="submit" onclick="return lp_check('#yes','#no','if you are sure that you want to add user:\'' + addform.add_username.value + '\' \n then write #yes and press OK');"/></td></tr></tbody></table></td></tr>
</tbody></table>
</form>
</div>
<?php
$action = 'nothing has happend yet';
// if we are adding
if(isset($_POST['submit']) && $_POST['submit'] === '  Add  ')
{
	$username = (string)$_POST['add_username'];
	$password = (string)$_POST['add_password'];
	$level = (string)$_POST['add_userlevel'];
	if(!empty($username) && !empty($password) && !empty($level)) // check if the username,password and the level was set
	{
		if(!preg_match('/[^\w\s]+/',$username) && !preg_match('/[^\w\s]+/',$password) && !preg_match('/\W+/',$level)) // validate the username,password and level
		{
			// make suere that there is not an user with the same username
			$DB->query('SELECT username FROM '.$table_prefix.'users','admin-users-new-check_sql');
			while($users = $DB->fetch_assoc('admin-users-new-check_sql'))
			{
				if($users['username'] === $username)
				{
					$found = 'set';
					break;
				}
			}
			if(!isset($found)) // if we are to go on
			{
				// add the user
				$DB->query("INSERT {$table_prefix}users VALUES('$username','$password','$level')",'admin-users-new_sql');
				echo('<meta http-equiv="Refresh" content="3; url=index.php?mode=users">');
				$action = 'user : '.$username.' was added';
				run_log("added user : {$username}");
			}
			else $action = 'there is already one user with that name';
		}
		else $action = 'the username or password you specefied was illegal';
	}
	else $action = 'you didn\'t specefy the username or the password';
}
// if we are editing
elseif(isset($_POST['editsubmit']) && $_POST['editsubmit'] === 'Edit')
{
	$username = (string)$_POST['edit_username'];
	$password = (string)$_POST['edit_password'];
	$level = (string)$_POST['edit_userlevel'];
	$id = (string)$_POST['edit_id'];

	if(!empty($username) && !empty($password) && !empty($level)) // check if the username,password and the level was set
	{
		if(!preg_match('/[^\w\s]+/',$id) && !preg_match('/[^\w\s]+/',$username) && !preg_match('/[^\w\s]+/',$password) && !preg_match('/\W+/',$level))  // validate the id,username,password and level
		{
			// edit the user's specefications
			$DB->query("UPDATE {$table_prefix}users SET username = '$username' , password = '$password' , level = '$level' WHERE username = '{$id}'",'admin-users-edit_sql');
			echo('<meta http-equiv="Refresh" content="3; url=index.php?mode=users">');
			$action = 'user : '.$id.' was edited';
			run_log("edited user : {$id}");
		}
		else $action = 'the username or password you specefied was illegal';
	}
	else $action = 'you didn\'t specefy the username or the password';
}
// if we are deleting an user
elseif(isset($_GET['delname']) && !empty($_GET['delname']) && !preg_match('/[^\w\s]+/',$_GET['delname']))
{
	$id = (string)$_GET['delname'];
	// make sure that the user exists
	$DB->query("SELECT username FROM {$table_prefix}users WHERE username = '{$id}'",'admin-users-del-rows_sql');
	if($DB->num_rows('admin-users-del-rows_sql') === 1)
	{
		// delete the user
		$DB->query("DELETE FROM {$table_prefix}users WHERE username = '{$id}'",'admin-users-delete_sql');
		echo('<meta http-equiv="Refresh" content="3; url=index.php?mode=users">');
		$action = 'user : '.$id.' was deleted';
		run_log("deleted user : {$id}");
	}
	else $action = 'you can not delete the user since it dosen\'t exist';
}

echo '<div style="text-align:center;">'.$action.'</div>';
?>
<div style="text-align:center;"><br>
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="0">
<tr>
<td>
<div class="tcat" style="padding: 4px; text-align: center;"><b>Manage Users</b></div>
<table rules="all" class="logincontrols" border="0" cellpadding="4" cellspacing="0" width="100%">
<tbody>
<tr>
<td>username</td><td>password</td><td>userlevel</td><td>edit</td><td>delete</td>
</tr>
<?php

$DB->query('SELECT * FROM '.$table_prefix.'users','admin-users-showall_sql'); // get all the users
$i = 1; // this is used to know which form we are on
while($val = $DB->fetch_assoc('admin-users-showall_sql'))
{
	extract($val);
	$reset .= "document.forms[{$i}].reset(); "; // add the reset code for the form

	// display the manage row for the user
	$selected[$level] = ' selected';
?>
<tr><form action="?mode=users" method="post">
<td><input type="hidden" name="edit_id" value="<?php echo $username;?>"/>
<input style="padding-left: 5px; font-weight: bold; width: 80px;" name="edit_username" type="textfield" value="<?php echo $username;?>"/></td>
<td><input style="padding-left: 5px; font-weight: bold; width: 80px;" name="edit_password" type="textfield" value="<?php echo $password;?>"/></td>
<td>
<select name="edit_userlevel" style="font-weight:bold;">
<option value="manager"<?php echo $selected['manager'];?>>manager</option><option value="onlytaxviewing" <?php echo $selected['onlytaxviewing'];?>>can_view_own_taxes</option>
<option value="designer"<?php echo $selected['designer'];?>>designer</option>
<option value="moderator"<?php echo $selected['moderator'];?>>moderator</option><option value="admin"<?php echo $selected['admin'];?>>admin</option>
</select>
</td>
<td><input type="submit" name="editsubmit" value="Edit" onclick="return lp_check('#yes','#no','if you are sure that you want to edit user:\'' + document.forms[<?php echo $i ?>].edit_id.value + '\' \n then write #yes and press OK');"/></td>
<td><a href="?mode=users&amp;delname=<?php echo $username;?>" onclick="return lp_check('#yes','#no','if you are sure that you want to delete user:\'' + document.forms[<?php echo $i ?>].edit_id.value + '\' \n then write #yes and press OK');">delete</a></td>
</form></tr>
	<?php
	$i++;
	unset($selected);
}
?>
</tbody><tbody><tr>
<td colspan="100" align="center"><input class="button" value="  Reset  " type="reset" onClick="<?php echo $reset;?>"/></td></tr></tbody></table></td></tr>
</tbody></table>
</div>