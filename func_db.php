<?php
function db_escape_str($data)
{
	return "'".addslashes($data)."'";
}
function db_escape_int($data)
{
	return intval($data);
}

function db_query_prepare($dbh, $sql, $args)
{
	$dbst = mysqli_prepare($dbh, $sql);
	
	if(!$dbst) { return 0; }
	
	foreach($args as $arg)
	{
		mysqli_stmt_bind_param($dbst, "s", $arg);
	}
	
	return $dbst;
}

function db_fetch_array(&$dbst)
{
	$data = mysqli_stmt_result_metadata($dbst);
	
	$fields = array();
	$out = array();
	
	$fields[0] = &$dbst;
	$count = 1;
	
	while ($field = $mysqli_fetch_field($data))
	{
		$fields[$count] = &$out[$field->name];
		$count++;
	}
	
	call_user_func_array(mysqli_stmt_bind_result, $fields);
	
	mysqli_stmt_fetch($dbst);
	return $out;
}

?>