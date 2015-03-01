<?php
/*--------------------------------------------
|	file = classes.php
|	description : The 2 main program classes
|	Char and Style
|	--------------
|	copyright = (C) 2005 The Magicasoft Group
|	email = support@magicasoft.net
*-------------------------------------------*/

// The function globalizes all the variables in a string


// the class that gets the requested blackbooked players
class bbm_Char
{
	var $way; // the request way
	var $val; // the value that we will use in request
	var $s_val; // if request way is search then this is the searching way
	var $nm; // set when there were no match
	var $output;
	
	function bbm_Char($way,$val,$s_val = '') // PHP4 class constructor
	{
		// populate the following properties
		$this->way = $way;
		$this->val = $val;
		$this->s_val = $s_val;
	}
	
	function Get($neg,$order_by,$order_rule) // the method makes the query that is wanted
	{
		global $DB,$table_prefix,$setting; // globalize important things
		if($setting['online'] === 'yes') // if the Tibia online feature is enabled
		{
			include('online.php');
		}

		if($this->way === 'all') // if the request way is 'all', $this->val wont be used
		{
			// get the players in a query and save in 'char-query_sql'
			$DB->query("SELECT * FROM {$table_prefix}main ORDER BY {$order_by} {$order_rule}",'char-query_sql');
			if((int)$DB->num_rows('char-query_sql') === 0) // check if there were no match
			{
				$this->nm = 'no match';
			}
		}
		elseif($this->s_val != '' && $this->way === 'search') // if the request way is 'search'
		{
			// get the searching mode we want
			$mode = (isset($_GET['exact']) ? '=' : 'LIKE');
			$jokers[1] = $jokers[2] = (isset($_GET['exact']) ? '' : '%');
			if($neg != '')
			{
				$neg = (isset($_GET['exact']) ? '!' : 'NOT ');
			}
			// get the players in a query and save in 'char-query_sql'
			$DB->query("SELECT * FROM {$table_prefix}main WHERE {$this->s_val} {$neg}{$mode} '{$jokers[1]}{$this->val}{$jokers[2]}' ORDER BY {$order_by} {$order_rule}",'char-query_sql');
			if((int)$DB->num_rows('char-query_sql') === 0) // check if there were no match
			{
				$this->nm = 'no match';
			}
		}
		elseif($this->way === 'alfa') // if the request way is 'alfa' (by first letter)
		{
			$this->val =  substr_replace($this->val, '',1, strlen($this->val) - 1); // get first letter of 'val'
			// get the players in a query and save in 'char-query_sql'
			$DB->query("SELECT * FROM {$table_prefix}main WHERE name LIKE '{$this->val}%' ORDER BY {$order_by} {$order_rule}",'char-query_sql');
			if((int)$DB->num_rows('char-query_sql') === 0) // check if there were no match
			{
				$this->nm = 'no match';
			}
		}
		
		elseif($this->way !== 'alfa' && 'all' && 'search') // if the request way is by a column
		{
			// make sure the column exist
			$columns = $DB->showcolumns($table_prefix.'main');
			$count = count($columns);
			$i = 1;
			foreach($columns as $col)
			{
				if($this->way === $col['name'])
				{
					$this->way = $col['name'];
					break;
				}
				elseif($i === $count && $this->way !== $col['name'])
				{
					$this->nm = 'no match';
				}
				$i++;
			}
			// check if the column was found
			if($this->nm !== 'no match')
			{
				// get the players in a query and save in 'char-query_sql'
				$DB->query("SELECT * FROM {$table_prefix}main WHERE {$this->way} = '{$this->val}' ORDER BY {$order_by} $order_rule",'char-query_sql');
				if((int)$DB->num_rows('char-query_sql') === 0) // check if there were no match
				{
					$this->nm = 'no match';
				}
			}
		}
	}
	function Parse() // hanldes the result of 'char-query_sql'
	{
		global $DB,$table_prefix; // globalize important variables
		// if there were no match
		if($this->nm === 'no match')
		{
			$DB->query('SELECT tpl FROM '.$table_prefix.'styles WHERE style = "no match"','char-opt-nomatch_sql'); // get the 'no match' style
			// handle it
			$nm_tpl = $DB->fetch_row('char-opt-nomatch_sql');
			$this->output = preg_replace('/"/','\"',$nm_tpl[0]);
			eval("\$this->output = \"$this->output\";");
			return $this->output; // return it
		}
		
		$DB->query('SELECT tpl FROM '.$table_prefix.'styles WHERE style = "content"','char-opt-content_sql'); // get the 'content' style
		// handle it
		$contstyle = $DB->fetch_row('char-opt-content_sql');
		$loop = split('_loop_',$contstyle[0]);
		$this->output = $loop[0];
		while($field = $DB->fetch_assoc('char-query_sql'))
		{
			$c_loop = preg_replace('/"/','\"',$loop[1]);
			eval("\$c_loop = \"$c_loop\";");
			$this->output .= $c_loop;
		}
		$this->output .= $loop[2];
		return $this->output; // returm it
	}
}

class bbm_Style // style handler
{
	var $s_cont; // content will be saved here
	
	function bbm_Style($use_cont) // PHP4 class constructor
	{
		$this->s_cont = $use_cont; // populate s_cont
	}
	function globalize()
	{
		preg_match_all ('/{(\$[^}]*)}/sU',$this->s_cont,$match,PREG_SET_ORDER);
		$retval = '';
		foreach($match as $i => $value)
		{
			foreach($match[$i] as $i2 => $value2)
			{
				if($i2 === 1)
				{
					if($i !== count($match) - 1)
					{
						$retval .= $match[$i][$i2]. ',';
					}
					elseif($i === count($match) - 1)
					{
						$retval .= $match[$i][$i2];
						$retval .= ';';
					}
				}
			}
		}
		if($retval != '')
		{
			eval('global '.retval); // do the globalization
		}
	}
	function output()
	{
		// handle the output
		$this->globalize();
		$this->s_cont = preg_replace('/"/','\"',$this->s_cont);
		eval("\$this->s_cont = \"$this->s_cont\";");
		return $this->s_cont; // return it
	}
}
?>