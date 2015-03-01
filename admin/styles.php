<?php
/*--------------------------------------------
|	file = admin/styles.php
|	description : add/delete/edit styles
|	--------------
|	copyright = (C) 2005 The Magicasoft Group
|	email = support@magicasoft.net
*-------------------------------------------*/

session_start(); // start the sessions
level_check('4'); // check if access is granted

if(isset($_GET['do']) && $_GET['do'] === 'ec') // if the user requested to edit the content of a style
{
	$name = (string)$_GET['name'];
	$id = (string)$_GET['id'];
	$DB->query("SELECT * FROM {$table_prefix}styles WHERE id = '{$id}' AND style = '{$name}'",'admin-styles-getcontent_sql'); // get the requested style
	$ecstyle = $DB->fetch_assoc('admin-styles-getcontent_sql'); // get its results
	// make the form
	?>
	<div style="text-align:center;"><br>
	<form method="post" action="index.php?mode=styles" name="editcontentform">
	<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="0">
	<tr><td>
	<div class="tcat" style="padding: 4px; text-align: center;"><b>Edit style contents</b></div>
	<table rules="all" class="logincontrols" border="0" cellpadding="4" cellspacing="0" width="100%">
	<tbody>
	<tr><td>Style name</td><td><div style=" height:15px; border:1px solid; background-color:#F6F6F6; text-align:left; padding-left:5px; font-weight:bold;"><?php echo $ecstyle['style']; ?></div>
	<input name="ecid" type="hidden" value="<?php echo $ecstyle['id'];?>">
	<input name="ecstyle" type="hidden" value="<?php echo $ecstyle['style'];?>"></td></tr>
	<tr><td>Style content</td><td><textarea style="background-color:#FFFFFF; color:#000000; border:1px solid; padding-left:5px;" rows="20" cols="50" name="eccontent"><?php echo $ecstyle['tpl']; ?></textarea></td></tr>
	</tbody><tbody><tr>
	<td colspan="100" align="center"><input name="ecsubmit" class="button" value="  Edit  " type="submit" onclick="return lp_check('#yes','#no','if you are sure that you want to edit style : <?php echo $ecstyle['style'];?> \n then write #yes and press OK');"/></td></tr></tbody></table></td></tr>
	</tbody></table>
	</form>
	</div>
	<?php
}
?>
<div style="text-align:center;"><br>
<form method="post" action="index.php?mode=styles" name="addform">
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="0">
<tr><td>
<div class="tcat" style="padding: 4px; text-align: center;"><b>Add Style</b></div>
<table rules="all" class="logincontrols" border="0" cellpadding="4" cellspacing="0" width="100%">
<tbody>
<tr><td>Style name</td></tr>
<tr>
<td><input type="text" name="add_stylename" style="width:550px;"/></td>
</tr>
</tbody><tbody><tr>
<td colspan="100" align="center"><input name="addsubmit" class="button" value="  Add  " type="submit" onclick="return lp_check('#yes','#no','if you are sure that you want to add style : \'' + addform.add_stylename.value + '\' \n then write #yes and press OK');"/></td></tr></tbody></table></td></tr>
</tbody></table>
</form>
</div>
<?php
$action = 'nothing has happend yet';
// if we are adding
if(isset($_POST['add_stylename'],$_POST['addsubmit']) && !empty($_POST['add_stylename']) && $_POST['addsubmit'] === '  Add  ')
{
	$stylename = (string)$_POST['add_stylename'];
	if(!empty($stylename) && !preg_match('/[^a-z\d_\s]+/',$stylename)) // validate
	{
		$DB->query('SELECT style,id FROM '.$table_prefix.'styles ORDER BY id','admin-styles-checkrows_sql'); // get the stylename and id of all the styles
		$i = 1;
		
		while($used_style = $DB->fetch_assoc('admin-styles-checkrows_sql'))
		{
			if($i === $DB->num_rows('admin-styles-checkrows_sql') && !isset($found)) // if there wasn't a style with the same name as this one
			{
				// add the style
				$DB->query("INSERT {$table_prefix}styles(style) VALUES('{$stylename}')",'admin-styles-new_sql');
				$action = "style : {$stylename} was added it will be automaticaly have no content, so you have to edit it";
				echo('<meta http-equiv="Refresh" content="3; url=?mode=styles">');
				run_log("added style : {$stylename}");
			}
			// display error message
			elseif($i === $DB->num_rows('admin-styles-checkrows_sql') && isset($found)) $action = 'there is already one style with the name you specefied, perhaps you want to edit it.<br/>then you can use the style managing function.';
			
			$i++;
			
			if($used_style['style'] !== $stylename) continue;
			else $found = 'set';
		}
		
	}
	else $action = 'your new stylesname is illegal';
}
// if we are editing
elseif(isset($_POST['stylearray'],$_POST['editsubmit']) && $_POST['editsubmit'] === '  Edit  ')
{
	$savedstyles = (array)$_POST['stylearray']; // make sure that $_POST['stylearray'] is an array
	$checklist = '';
	
	foreach($savedstyles as $savedkey => $savedval) // update every style
	{
		// validate
		if(empty($savedval['edit_stylename']) || preg_match('/[^a-z\d_\s]+/',$savedval['edit_stylename'])) continue;
		if(empty($savedval['edit_styleid']) || preg_match('/[\DxX]+/',$savedval['edit_styleid'])) continue;
		if(empty($savedval['edit_oldid']) || preg_match('/[\DxX]+/',$savedval['edit_oldid'])) continue;
		
		// update the style
		$DB->query("UPDATE {$table_prefix}styles SET style = '{$savedval['edit_stylename']}' , id = '{$savedval['edit_styleid']}' WHERE id = '{$savedval['edit_oldid']}'",'admin-styles-editall','yes');
	}
	echo('<meta http-equiv="Refresh" content="3; url=?mode=styles">');
	$action = 'all the styles was edited';
	run_log("edited all the styles");
}
// if we are editing a style's contents
elseif(isset($_POST['ecstyle'],$_POST['ecid'],$_POST['eccontent'],$_POST['ecsubmit']) && !empty($_POST['ecstyle']) && !empty($_POST['ecid']) && $_POST['ecsubmit'] === '  Edit  ' && !preg_match('/[\DxX]+/',$_POST['ecid']) && !preg_match('/[^a-z\d_\s]+/',$_POST['ecstyle']))
{
	$name = (string)$_POST['ecstyle'];
	$id = (string)$_POST['ecid'];
	$tpl = (string)$_POST['eccontent'];
	// make sure that the style exists
	$DB->query("SELECT style FROM {$table_prefix}styles WHERE id = '{$id}' AND style = '{$name}'",'admin-styles-ec-rows_sql');
	if($DB->num_rows('admin-styles-ec-rows_sql') === 1)
	{
		// edit the style's contents
		$DB->query("UPDATE {$table_prefix}styles SET tpl = '{$tpl}' WHERE id = '{$id}' AND style = '{$name}'",'admin-styles-ec-edit_sql');
		echo('<meta http-equiv="Refresh" content="3; url=?mode=styles">');
		$action = 'style : '.$name.' was edited';
		run_log("edited style : {$name}'s contents");
	}
	else die('Hacking atempt!');
}
// if we are deleting a style
elseif(isset($_GET['do'],$_GET['name'],$_GET['id']) && $_GET['do'] === 'del' && !empty($_GET['name']) && !empty($_GET['id'])  && !preg_match('/[\DxX]+/',$_GET['id']) && !preg_match('/[^a-z\d_\s]+/',$_GET['name']))
{
	$name = (string)$_GET['name'];
	$id = (string)$_GET['id'];
	// make sure that the style exists
	$DB->query("SELECT style FROM {$table_prefix}styles WHERE id = '{$id}' AND style = '{$name}'",'admin-styles-del-rows_sql');
	if($DB->num_rows('admin-styles-del-rows_sql') === 1)
	{
		// delete the style
		$DB->query("DELETE FROM {$table_prefix}styles WHERE id = '{$id}' AND style = '{$name}'",'admin-styles-delete_sql');
		echo('<meta http-equiv="Refresh" content="3; url=?mode=styles">');
		$action = 'style : '.$name.' was deleted';
		run_log("deleted style : {$name}");
	}
	else $action = 'there is no such style';
}
echo '<div style="text-align:center;">'.$action.'</div>';
?>

<div style="text-align:center;"><br>
<form method="post" action="index.php?mode=styles" name="editform">
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="0">
<tr><td>
<div class="tcat" style="padding: 4px; text-align: center;"><b>Manage Styles</b></div>
<table rules="all" class="logincontrols" border="0" cellpadding="4" cellspacing="0" width="100%">
<tbody>
<tr><td>Style name</td><td>order id</td><td>edit content</td><td>delete</td></tr>
<?php
$DB->query('SELECT * FROM '.$table_prefix.'styles ORDER BY id','admin-styles-showall_sql'); // get all the styles
$i = 0;
while($style = $DB->fetch_assoc('admin-styles-showall_sql')) // display the manager row for every style
{
	echo"<tr>
	<td><input style=\"padding-left: 5px; font-weight: bold; width: 200px;\" name=\"stylearray[{$i}][edit_stylename]\" type=\"textfield\" value=\"{$style['style']}\"/></td>
	<td><input style=\"padding-left: 5px; font-weight: bold; width: 25px;\" name=\"stylearray[{$i}][edit_styleid]\" type=\"textfield\" value=\"{$style['id']}\"/>
	<input name=\"stylearray[{$i}][edit_oldid]\" type=\"hidden\" value=\"{$style['id']}\"/></td>
	<td><a href=\"?mode=styles&amp;do=ec&amp;name={$style['style']}&amp;id={$style['id']}\">edit content</a></td>
	<td><a href=\"?mode=styles&amp;do=del&amp;name={$style['style']}&amp;id={$style['id']}\" onclick=\"return lp_check('#yes','#no','if you are sure that you want to DELETE style : <{$style['style']}>\\n then write #yes and press OK\\nplease note that all the data which is in that style will be lost');\">delete</a></td>
	</tr>";
	$i++;
}
?>
</tbody><tbody><tr><td colspan="4" align="center">
<input name="editsubmit" class="button" value="  Edit  " type="submit" onclick="return lp_check('#yes','#no','if you are sure that you want to edit all the styles\n then write #yes and press OK');"/>&nbsp;<input class="button" value="  Reset  " type="reset"/>
</td></tr></tbody></table></td></tr>
</tbody></table>
</form></div>