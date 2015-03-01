<?php
/*--------------------------------------------
|	file = spes.php
|	description : some special functions
|	--------------
|	copyright = (C) 2005 The Magicasoft Group
|	email = support@magicasoft.net
*-------------------------------------------*/

// get easyfind information
if($setting['easyfind'] === 'yes') // check if easyfind is enabled
{
	$DB->query('SELECT tpl FROM '.$table_prefix.'styles WHERE style = "easyfind"','spes-style-easyfind_sql'); // get the easyfind style
	$style_easyfind = $DB->fetch_row('spes-style-easyfind_sql');
	$loop = split('_loop_',$style_easyfind[0]);
	$spes['easyfind'] = $loop[0]; // save things before _loop_
	
	$DB->query('SELECT * FROM '.$table_prefix.'easyfind','spes-easyfind_sql'); // get all easyfind rows
	while($ef = $DB->fetch_assoc('spes-easyfind_sql'))
	{
		$efname = $ef['field']; // get the name of the easyfind row
		$values = explode(',',$ef['vals']); // get its possible values
		
		$iloop = split('%@loop%',$loop[1]); // get things inside %@loop%
		
		// add things after _loop_ and before %@loop%
		$c_loop = preg_replace('/"/','\"',$iloop[0]);
		eval("\$c_loop = \"$c_loop\";");
		$spes['easyfind'] .= $c_loop;
		
		// handle every value and add it
		for($i = 0; $i < count($values); $i++)
		{
			$link = "?by={$efname}&amp;val={$values[$i]}&amp;order={$efname}{$sort_link}"; // make a link
			$name = $values[$i];
			$c_iloop = preg_replace('/"/','\"',$iloop[1]);
			eval("\$c_iloop = \"$c_iloop\";");
			$spes['easyfind'] .= $c_iloop; // add 
		}
		
		// add the things after the last %@loop%
		$c_loop = preg_replace('/"/','\"',$iloop[2]);
		eval("\$c_loop = \"$c_loop\";");
		$spes['easyfind'] .= $c_loop;
	}
	// add the things after the last _loop_
	$spes['easyfind'] .= $loop[2];
}
// get alfa information
if($setting['alfa'] === 'yes') // check if alfa is enabled
{
	$DB->query('SELECT tpl FROM '.$table_prefix.'styles WHERE style = "alfa"','spes-style-alfa_sql'); // get the easyfind style
	$style_alfa = $DB->fetch_row('spes-style-alfa_sql');
	$loop = split('_loop_',$style_alfa[0]);
	$spes['alfa'] = $loop[0]; // add things before _loop_
	
	// handle all the letters in the alphabet and add them
	for($i = 97; $i <= 122; $i++)
	{
		$char = chr($i);
		$link = "?by=alfa&amp;val={$char}&amp;order={$order}{$sort_link}";
		$c_loop = preg_replace('/"/','\"',$loop[1]);
		eval("\$c_loop = \"$c_loop\";");
		$spes['alfa'] .= $c_loop;
	}
	$spes['alfa'] .= $loop[2]; // add things after the last _loop_
}
// get sorting information
if($setting['sort'] === 'yes') // check if alfa is enabled
{
	$DB->query('SELECT tpl FROM '.$table_prefix.'styles WHERE style = "sort"','spes-style-sort_sql'); // get the easyfind style
	$style_sort = $DB->fetch_row('spes-style-sort_sql');
	$loop = split('_loop_',$style_sort[0]);
	$spes['sort'] = $loop[0]; // add things before _loop_
	
	// make a link for every column exept id and Online and add it
	foreach($DB->showcolumns($table_prefix.'main') as $col)
	{
		$name = $col['name'];
		if($name === 'id' || $name === 'Online') continue;
		$link = "?by={$by}&amp;val={$value}&amp;order={$name}{$sort_link}"; // make the link, $sort_link is in index.php
		$c_loop = preg_replace('/"/','\"',$loop[1]);
		eval("\$c_loop = \"$c_loop\";");
		$spes['sort'] .= $c_loop; // add the link and its style
	}
	
	$spes['sort'] .= $loop[2]; // add things after the last _loop_
}
?>