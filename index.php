<?php
/*--------------------------------------------
|	file = index.php
|	description : The main file that shows the
|	blackbook
|	--------------
|	copyright = (C) 2005 The Magicasoft Group
|	email = support@magicasoft.net
*-------------------------------------------*/

// include dependiances
require('config.php');
require('db/'.$servertype.'.php');
require('classes.php');

// connect to the database in use
$DB = new DB($host,$username,$password,$database);
$DB->connect();

// get settings
$setting = array();
$DB->query('SELECT * FROM '.$table_prefix.'settings','settings_sql');
while($row = $DB->fetch_assoc('settings_sql'))
$setting[$row['name']] = $row['setting'];

// get the way of doing things
$s_by = ''; // s_by is only for search
$neg = ''; // negation is by default not used
$order = (!isset($_GET['order']) ? 'Name' : $_GET['order']); // which column to order by
$order_rule = (!isset($_GET['order_rule']) ? 'ASC' : 'DESC'); // to order from smallest to highest or highest to smallest

if($order_rule === 'ASC') // if order_rule is ASC then the link will point to DESC
{
	$sort_link = '&amp;order_rule=DESC';
}
elseif($order_rule === 'DESC') // if order_rule is DESC then the link will point to ASC
{
	$sort_link = '';
}

if(!isset($_GET['by'])) // if querystring 'by' is not set then then the request way is to get all
{
	$by = 'all';
	$value = '';
}
elseif($_GET['by'] === 'search') // if querystring 'by' is search
{
	if($setting['search'] === 'yes') // check if searching is enabled
	{
		$by = 'search'; // request way is now 'search'
		$value = $_GET['val']; // get the search value
		$s_by = $_GET['s_by']; // get the searching way
		if(isset($_GET['neg'])) // check if we are using negation
		{
			$neg = 'set';
		}
	}
	else // if searching is not enabled then we will get all instead
	{
		$by = 'all';
		$value = '';
	}
}
elseif($_GET['by'] === 'alfa') // if querystring 'by' is alfa (by alfa we mean that we are getting everything that starts with a specefied letter)
{
	if($setting['alfa'] === 'yes')
	{
		$by = 'alfa'; // request way is now alfa
		$value = $_GET['val']; // get the alfa way
	}
	else
	{
		$by = 'all';
		$value = '';
	}
}
else // if querystring 'by' isn't all,search or alfa then we set the request way to a column
{
	$by = $_GET['by'];
	$value = $_GET['val'];
}

// get content information
$obj = new bbm_Char($by,$value,$s_by); // use the bbm_Char class
$obj->Get($neg,$order,$order_rule); // update the online status if enabled and get the query thats needed
$spes['content'] = $obj->Parse(); // save the content

require('spes.php'); // user defined specials

// output everthing
$DB->query('SELECT * FROM '.$table_prefix.'styles WHERE style != "no match" AND style != "guildmemberlist" ORDER BY id','opt-styles_sql'); // get the needed styles
while($style = $DB->fetch_assoc('opt-styles_sql'))
{
	$s_name = $style['style'];
	if(isset($setting[$s_name]) && $setting[$s_name] !== 'yes') // if the style is not enabled (its a special feature thing)
	{
		continue;
	}
	if(isset($spes[$s_name]))  // if style is a special module
	{
		$s_obj = new bbm_Style($spes[$s_name]);
		echo $s_obj->output();
	}
	else
	{
		$s_obj = new bbm_Style($style['tpl']);
		echo $s_obj->output();
	}
}
$DB->close(); // close the database
?>