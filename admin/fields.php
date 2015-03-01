<?php
/*--------------------------------------------
|	file = admin/fields.php
|	description : add/delete/edit fields
|	--------------
|	copyright = (C) 2005 The Magicasoft Group
|	email = support@magicasoft.net
*-------------------------------------------*/

session_start(); // start the sessions
level_check('2'); // check if access is granted
?>
<div style="text-align:center; ">
<form method="post" action="index.php?mode=fields" name="addform">
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="0">
<tbody><tr><td>
<div class="tcat" style="padding: 4px; text-align: center;"><b>Add Field</b></div>
<table rules="all" class="logincontrols" border="0" cellpadding="4" cellspacing="0" width="100%">
<tbody>
<tr><td>Field name</td><td>type</td><td>max length</td></tr>
<tr>
<td><input style="padding-left: 5px; font-weight: bold; width: 80px;" name="add_fieldname" type=\"textfield"/></td>
<td><select style="padding-left: 5px; font-weight:bold; width: 90px;" name="add_type"><option value="INTEGER">number</option><option value="VARCHAR">string</option>
</select></td>
<td><input style="padding-left: 5px; font-weight: bold; width: 80px;" name="add_maxlength" type=\"textfield"/></td>
</tr>
</tbody><tbody><tr>
<td colspan="100" align="center"><input name="addsubmit" class="button" value="  Add  " type="submit" onclick="return lp_check('#yes','#no','if you are sure that you want to add field : \'' + addform.add_fieldname.value + '\' \n then write #yes and press OK');"/></td></tr></tbody></table></td></tr>
</tbody></table>
</form>
</div>
<?php
$action = 'nothing has happend yet';
// add a field
if(isset($_POST['addsubmit']) && $_POST['addsubmit'] === '  Add  ')
{
	if(!empty($_POST['add_fieldname']) && !empty($_POST['add_maxlength']) && !preg_match('/[\W\d_]+/',$_POST['add_fieldname']) && !preg_match('/[\W\DxX]+/',$_POST['add_maxlength'])&& !preg_match('/^0+/',$_POST['add_maxlength'])) // validate
	{
		// populate the needed vaiables
		$fieldname = (string)$_POST['add_fieldname'];
		$maxlength = '('.(string)$_POST['add_maxlength'].')';
		$mintest = (int)$_POST['add_maxlength'];
		$type = (string)$_POST['add_type'];
		
		// make sure that the type is INTEGER or VARCHAR
		if($type === 'INTEGER' || 'VARCHAR');
		else die('<center>Hacking atempt!</center>');
		
		if($mintest < 5 && $type === 'VARCHAR') // if the type is VARCHAR then the maximum length must be atleast 5
		{
			// set the new length to 5
			$maxlength = '(5)';
			$actionerr = 'a string must atleast have a max length of 5, therefore the new field\'s maxlength will be set to 5<br/>';
		}
		// add the field
		$DB->ALTER_TABLE($table_prefix.'main','add',$fieldname,$type,$maxlength);
		echo('<meta http-equiv="Refresh" content="3; url=index.php?mode=fields">');
		$action = $actionerr.'field : '.$fieldname.' was added';
		run_log("added field : {$fieldname}");
	}
	else $action = 'you didn\'t fill all the fields or you didn\'t fill the fields with the right values or you filled the maxlength with 0.';
}
// edit a field
elseif($_POST['editsubmit'] === 'Edit')
{
	if(!empty($_POST['edit_fieldname']) && !preg_match('/[\W\d_]+/',$_POST['edit_fieldname'])) // validate
	{
		// populate the needed vaiables
		$fieldname = (string)$_POST['edit_fieldname'];
		$type = (string)$_POST['edit_type'];
		$maxlength = '('.(string)$_POST['edit_maxlenght'].')';
		$mintest = (int)$_POST['edit_maxlenght'];
		$id = (string)$_POST['id_name'];

		// make sure that the type is INTEGER or VARCHAR
		if($type === 'INTEGER' || $type === 'VARCHAR')
		{
			if(!preg_match('/[\W\DxX]+/',$_POST['add_maxlength'])) // validate maxlength
			{
				if($mintest === 0) $skip = 'a number or string can\'t have the maxlength value : 0'; // check if the maxlength is 0
				if($type === 'VARCHAR' && $mintest < 5) // if the type is VARCHAR then the maximum length must be atleast 5
				{
					// set the new length to 5
					$maxlength = '(5)';
					$actionerr = 'a string must atleast have a maxlength of 5, therefore the new field\'s maxlength will be set to 5<br/>';
				}
			}
			else $skip = 'the maxlength value you specefied was illegal';
		}
		else die('<center>Hacking atempt!</center>');
		if(!isset($skip)) // if we are to go on
		{
			// edit the field
			$DB->ALTER_TABLE($table_prefix.'main','edit',$id,$type,$maxlength,$fieldname);
			echo('<meta http-equiv="Refresh" content="3; url=index.php?mode=fields">');
			$action = $actionerr.'field : '.$id.' was edited';
			run_log("edited field : {$fieldname}");
		}
		else $action = $skip;
	}
	else $action = 'either you filled the fieldname with illegal values or you didn\'t fill it at all.';
}
// delete a field
elseif(isset($_GET['del_fieldname']) && !preg_match('/[\W\d_]+/',$_GET['del_fieldname']))
{
	$delname = (string)$_GET['del_fieldname'];
	
	// delete the field
	$DB->ALTER_TABLE($table_prefix.'main','delete',$delname);
	echo('<meta http-equiv="Refresh" content="3; url=index.php?mode=fields">');
	$action = 'field : '.$delname.' was deleted';
	run_log("deleted field : {$delname}");
}
echo '<div style="text-align:center;">'.$action.'</div>';
?>
<div style="text-align:center;"><br>
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="0">
<tr><td>
<div class="tcat" style="padding: 4px; text-align: center;"><b>Manage Fields</b></div>
<table rules="all" class="logincontrols" border="0" cellpadding="4" cellspacing="0" width="100%">
<tbody>
<tr><td>Field name</td><td>type</td><td>max lenght</td><td>edit</td><td>delete</td></tr>
<?php
$notneeded = array('Online' => '','id' => ''); // the fields that are parts of the system that must not be included
$i = 1; // this is used to know which form we are on
foreach($DB->showcolumns($table_prefix.'main') as $field)
{
	// make sure that the unwanted fields won't be displayed
	if(isset($notneeded[$field['name']]))
	{
		continue;
	}
	$reset .= "document.forms[{$i}].reset(); "; // add the reset code for the form
	$i++;
	
	// print out the manage row for the field
	$ss = $field['type'];
	$selected[$ss] = ' selected';
	echo"<form name=\"{$field['name']}\" action=\"index.php?mode=fields\" method=\"post\"><tr>
	<td><input style=\"padding-left: 5px; font-weight: bold; width: 80px;\" name=\"edit_fieldname\" type=\"textfield\" value=\"{$field['name']}\"/></td>
	<td>
	<select style=\"padding-left: 5px; font-weight: bold; width: 90px;\" name=\"edit_type\">
	<option value=\"INTEGER\"{$selected['integer']}>number</option>
	<option value=\"VARCHAR\"{$selected['varchar']}>string</option>
	</select>
	</td>
	<td><input style=\"padding-left: 5px; font-weight: bold; width: 80px;\" name=\"edit_maxlenght\" type=\"textfield\" value=\"{$field['maxlength']}\"/></td>
	<td><input type=\"hidden\" name=\"id_name\" value=\"{$field['name']}\"><input name=\"editsubmit\" class=\"button\" value=\"Edit\" type=\"submit\" onclick=\"return lp_check('#yes','#no','if you are sure that you want to edit field : <{$field['name']}>\\n then write #yes and press OK');\" style=\"width:30px;\"/></td>
	<td><a href=\"?mode=fields&amp;del_fieldname={$field['name']}\" onclick=\"return lp_check('#yes','#no','if you are sure that you want to DELETE field : <{$field['name']}>\\n then write #yes and press OK\\nplease note that all the data which is in that field will be lost');\">delete</a></td>
	</tr></form>";
	unset($selected);
}
?>
</tbody><tbody><tr>
<td colspan="100" align="center"><input class="button" value="  Reset  " type="reset" onClick="<?php echo $reset; ?>"/></td></tr></tbody></table></td></tr>
</tbody></table>
</div>