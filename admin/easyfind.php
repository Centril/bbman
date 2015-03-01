<?php
/*--------------------------------------------
|	file = admin/easyfind.php
|	description : add/delete/edit
|	easyfind specefications on fields
|	--------------
|	copyright = (C) 2005 The Magicasoft Group
|	email = support@magicasoft.net
*-------------------------------------------*/

session_start(); // start the sessions
level_check('3'); // check if access is granted
?>
<div style="text-align:center;"><br>
<form method="post" action="index.php?mode=easyfind" name="addform">
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="0">
<tr><td>
<div class="tcat" style="padding: 4px; text-align: center;"><b>Add EasyFind Fields</b></div>
<table rules="all" class="logincontrols" border="0" cellpadding="4" cellspacing="0" width="100%">
<tbody>
<tr><td>Field name</td><td>possible values</td></tr>
<tr>
<td><input type="text" name="add_fieldname" style="width:85px;"/></td><td><input type="text" name="add_posval" style="width:479px;"/></td>
</tr>
</tbody><tbody><tr>
<td colspan="100" align="center"><input name="add_submit" class="button" value="  Add  " type="submit" onclick="return lp_check('#yes','#no','if you are sure that you want to add field : \'' + addform.add_fieldname.value + '\' \n then write #yes and press OK');"/></td></tr></tbody></table></td></tr>
</tbody></table>
</form>
</div>

<?php
$action = 'nothing has happend yet';
// add an easyfind specefication
if(isset($_POST['add_submit']) && $_POST['add_submit'] === '  Add  ')
{
	if(!empty($_POST['add_fieldname']) && !empty($_POST['add_posval']) && !preg_match('/[\W\d_]+/',$_POST['add_fieldname']) && !preg_match('/[^a-z,]+/i',$_POST['add_posval'])) // validate
	{
		$fieldname = (string)$_POST['add_fieldname'];
		$posval = (string)$_POST['add_posval'];
		
		// make sure that the field exists
		foreach($DB->showcolumns($table_prefix.'main') as $field)
		{
			if($field['name'] === $fieldname) $found = 'set';
		}
		if(isset($found)) // if we are to go on
		{
			// insert the easyfind specefication
			$DB->query("INSERT {$table_prefix}easyfind VALUES('{$fieldname}','$posval')",'admin-easyfind-new_sql');
			echo('<meta http-equiv="Refresh" content="3; url=?mode=easyfind">');
			$action = 'field : '.$fieldname.' is now used in easyfind';
			run_log("gave field : {$fieldname} easyfind specefications");
		}
		else $action = 'there is no field that is eqal to what you specified';
	}
	else $action = 'you didn\'t fill the fieldname or the possible values with the right values';
}
// edit an easyfind specefication
elseif(isset($_POST['edit_submit']) && $_POST['edit_submit'] === 'Edit')
{
	if(!empty($_POST['edit_fieldname']) && !empty($_POST['edit_posval']) && !preg_match('/[\W\d_]+/',$_POST['edit_fieldname']) && !preg_match('/[^a-z\d,]+/i',$_POST['edit_posval'])) // validate
	{
		$fieldname = (string)$_POST['edit_fieldname'];
		$posval = (string)$_POST['edit_posval'];
		$id = (string)$_POST['id_name'];
		
		// make sure that the field exists
		foreach($DB->showcolumns('main') as $field)
		{
			if($field['name'] === $fieldname) $found = 'set';
		}
		if(isset($found)) // if we are to go on
		{
			// update the easyfind specefication
			$DB->query("UPDATE {$table_prefix}easyfind SET field = '{$fieldname}', vals = '$posval' WHERE field = '{$id}'",'admin-easyfind-edit_sql');
			echo('<meta http-equiv="Refresh" content="3; url=?mode=easyfind">');
			$action = 'field : '.$fieldname.' has now got new easyfield spesifications';
			run_log("gave field : {$fieldname} new easyfind specefications");
		}
		else $action = 'there is no field that is eqal to what you specified';
	}
	else $action = 'you didn\'t fill the fieldname or the possible values with the right values';
}
// delete an easyfind specefication
elseif(isset($_GET['del_name']) && !preg_match('/[\W\d_]+/',$_GET['del_name']))
{
	$delname = (string)$_GET['del_name'];
	
	// make sure that the field exists
	foreach($DB->showcolumns('main') as $field)
	{
		if($field['name'] == $delname) $found = 'set';
	}
	if(!isset($found)) $skip = 'I can not delete the easyfind spesefications for '.$delname.' beacuse the field dose not exist'; // print out an error message
	
	unset($found);
	
	// check if the easyfind specefication really exists
	if(!isset($skip))
	{
		$DB->query('SELECT * FROM '.$table_prefix.'easyfind','admin-easyfind-delete-lookup_sql');
		while($field = $DB->fetch_assoc('admin-easyfind-delete-lookup_sql'))
		{
			if($field['field'] === $delname) $found = 'set'; // the vaiable $found will be set if the specefication exsits
		}
		
		if(!isset($found)) $skip = 'I can not delete the easyfind spesefications for '.$delname.' beacuse there is no such entry in the easyfind database'; // check if it matched and if it didn't then we diplay an error message
	}
	if(!isset($skip)) // if we are to go on
	{
		// delete the easyfind specefication
		$DB->query("DELETE FROM {$table_prefix}easyfind WHERE field = '{$delname}'",'admin-easyfind-delete_sql');
		echo('<meta http-equiv="Refresh" content="3; url=index.php?mode=easyfind">');
		$action = 'field : '.$delname.' easyfind spesifications was deleted';
		run_log("deleted field : {$delname}'s easyfind specefications");
	}
	else $action = $skip;
}
echo '<div style="text-align:center;">'.$action.'</div>';
?>

<div style="text-align:center;"><br>
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="0">
<tr><td>
<div class="tcat" style="padding: 4px; text-align: center;"><b>Manage EasyFind Fields</b></div>
<table rules="all" class="logincontrols" border="0" cellpadding="4" cellspacing="0" width="100%">
<tbody>
<tr><td>Field name</td><td>possible values</td><td>edit</td><td>delete</td></tr>
<?php
$DB->query('SELECT * FROM '.$table_prefix.'easyfind ORDER BY field','admin-easyfind-showall_sql'); // get all the easyfind specefications
$i = 1; // this is used to know which form we are on
while($ef = $DB->fetch_assoc('admin-easyfind-showall_sql'))
{
	$reset .= "document.forms[{$i}].reset(); "; // add the reset code for the form
	$i++;
	
	// print out the manage row for the easyfind specefication
	echo"<tr>
	<form method=\"post\" action=\"index.php?mode=easyfind\" name=\"editform\"><td><input type=\"text\" name=\"edit_fieldname\" value=\"{$ef['field']}\" style=\"width:85px;\"/></td><td><input type=\"text\" name=\"edit_posval\" value=\"{$ef['vals']}\" style=\"width:400px;\"/></td>
	<td><input type=\"hidden\" name=\"id_name\" value=\"{$ef['field']}\"><input type=\"submit\" name=\"edit_submit\" value=\"Edit\" style=\"width:30px;\" onclick=\"return lp_check('#yes','#no','if you are sure that you want to edit the easyfind values for : <{$ef['field']}>\\n then write #yes and press OK');\"/></td>
	<td><a href=\"?mode=easyfind&amp;del_name={$ef['field']}\" onclick=\"return lp_check('#yes','#no','if you are sure that you want to delete the easyfind spesifications for field : <{$ef['field']}>\\n then write #yes and press OK\\nplease note that all the data which is in that field will be lost');\">delete</a></td></form></tr>";
}
?>
</tbody><tbody><tr>
<td colspan="100" align="center"><input class="button" value="  Reset  " type="reset" onClick="<?php echo $reset; ?>"/></td></tr></tbody></table></td></tr>
</tbody></table>
</div>