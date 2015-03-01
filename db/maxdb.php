<?php
/*--------------------------------------------
|	file = db/maxdb.php
|	description : A database class
|	--------------
|	copyright = (C) 2005 The Magicasoft Group
|	email = support@magicasoft.net
*-------------------------------------------*/

class DB
{
	var $host; // The hostname
	var $user; // The username
	var $password; // The password
	var $database; // The database
	var $conn = array('server' => 0, 'database' => 0); // Database and Server connection recource
	var $query = array(); // The exsisting querys
	var $currMethod; // The last result
	var $lang_struct = array('text' => 'LONG',
	'primkey const' => 'NOT NULL DEFAULT SERIAL',
	'primkey' => 'PRIMARY KEY (#2)',
	'spec prim' => '',
	'primkey dt int' => 'INTEGER(#1)');

	function DB($host = 'localhost',$user = 'root',$password = '',$database = 'bbman') // PHP4 class constructor
	{
		// populate the properties thats needed for connection
		$this->host = gethostbyname($host); // change hostname to an ip address
		$this->user = $user;
		$this->password = $password;
		$this->database = $database;
	}

	function connect($errormode = 'print') // connect to the database
	{
		if($errormode === 'return')
		{
			$ext_test = ( function_exists('maxdb_connect') ? '' : -1);
			if($ext_test === -1) return -1;
			return (@maxdb_connect($this->host,$this->user,$this->password,$this->database) ? 1 : 0);
		}

		$this->conn['server'] = $this->conn['database'] = maxdb_connect($this->host,$this->user,$this->password,$this->database) or $this->error('Can\'t connect to the database or server');
	}

	function close() // close the database connection
	{
		$this->query = NULL;
		maxdb_close($this->conn['server']);
	}

	function query($query,$queryname,$overwrite = 'no') // run a query
	{
		if(!isset($this->query[$queryname]) || $overwrite === 'yes') // make sure there is no chance of unwanted conlicts with queries
		{
			$this->query[$queryname] = $this->currMethod = maxdb_query($this->conn['server'],$query) or $this->error('Invalid query');
			return $this->currMethod;
		}
		else $this->error('Can\t overwrite query : '.$queryname); // display error
	}

	function fetch_row($queryname) // fetch a row as an array
	{
		$this->currMethod = maxdb_fetch_row($this->query[$queryname]);
		return $this->currMethod;
	}

	function fetch_assoc($queryname,$load = 'yes') // fetch a row as an hash
	{
		if($load === 'no') // check if query is outside $this->query
		{
			$this->currMethod = maxdb_fetch_assoc($queryname);
			return $this->currMethod;
		}
		else
		{
			$this->currMethod = maxdb_fetch_assoc($this->query[$queryname]);
			return $this->currMethod;
		}
	}

	function num_rows($queryname) // get the number of rows in a query
	{
		$this->currMethod = maxdb_num_rows($this->query[$queryname]) or $this->error('Can\'t get the number of rows from query : '.$queryname);
		return (int)$this->currMethod;
	}

	function showcolumns($table,$keep = 'no') // show the columns of a table
	{
		$this->query("SELECT columnname,datatype,len FROM DOMAIN.COLUMNS WHERE tablename = '$table'",'showcolumns_'.$table);
		while($col = $this->fetch_assoc('showcolumns_'.$table))
		{
			extract($col);
			if(strtolower($DATATYPE) === 'fixed') $DATATYPE = 'integer';
			$columns[$COLUMNNAME]['name'] = $COLUMNNAME;
			$columns[$COLUMNNAME]['type'] = $DATATYPE;
			$columns[$COLUMNNAME]['maxlength'] = $LEN;
		}
		$this->currMethod = $columns;
		
		if($keep === 'no') unset($this->query['showcolumns_'.$table]);
		
		return $this->currMethod;
	}

	function ALTER_TABLE($table,$action,$column,$type = '',$length = '',$newcolumn = '') // add,edit,delete columns from a table
	{
		if($action === 'add') // handle adding columns
		{
			if($type === 'INTEGER') $type = 'FIXED';
			$this->query("ALTER TABLE {$table} ADD {$column} {$type}{$length} NOT NULL",'alter-table-add_sql');
		}
		elseif($action === 'edit') // handle editing columns
		{
			if($type === 'INTEGER') $type = 'FIXED';
			$this->query("RENAME COLUMN {$table}.{$column} TO {$newcolumn}",'alter-table-edit_sql','yes');
			$this->query("ALTER TABLE {$table} MODIFY ({$newcolumn} {$type}{$length})",'alter-table-edit_sql');
		}
		elseif($action === 'delete') // handle deleting columns
		{
			$this->query("ALTER TABLE {$table} DROP {$column}",'alter-table-delete_sql');
		}
	}

	function lang() // get a sql language structure
	{
		$num_args = func_num_args(); // number of arguments
		if($num_args === 0) trigger_error('Invalid argument count for function : lang, the function need atleast 1 argument',E_USER_ERROR); // function can't be void
		elseif($num_args === 1) return $this->lang_struct[func_get_arg(0)]; // if only 1 argument the return the language stucture
		else // handle language structures with arguments/variables
		{
			$code = $this->lang_struct[func_get_arg(0)];
			
			for($i = 1; $i < $num_args; $i++)
			{
				${$i} = func_get_arg($i);
				$code = preg_replace('/#'.$i.'/',$$i,$code);
			}
			return $code;
		}
	}

	function error($msg) // display the last error and terminate
	{
		$error = maxdb_error($this->conn['server']);
		$errorcode = maxdb_errno($this->conn['server']);

		$message = 'Database error in Magicasoft BBman 2:'.$msg.'
		Database Error:'.$error.'
		Error Code: '.$errorcode.'
		Date: '.date('F j, Y h:i A').'
		Script: '.$_SERVER['REQUEST_URI'];
		echo'
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
		<html>
		<head>
		<title>Magicasoft BBman 2 Database Error</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<style type="text/css">
		body, p
		{ 
			font-family: verdana,arial,helvetica,sans-serif;
			font-size: 11px;
		}
		</style>
		</head>
		<body>
		<blockquote><p><strong>Fatal error caused by fault in database.</strong><br/>
		You may try this action again by pressing <a href="javascript:window.location=window.location;">refresh</a>.</p>
		<p>We apologise for any inconvenience.</p>
		<form action="null"><textarea rows="10" cols="55">'.$message.'</textarea></form>
		</blockquote>
		</body>
		</html>';
		die;
	}
}
?>