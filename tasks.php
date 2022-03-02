<?php

include_once "config.php";

$connection = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
$tableName = DB_TABLE;

// Store Task Data into variable $task
if ( isset( $_POST['task'] ) && !empty( $_POST['task'] ) ){
	$task = $_POST['task'];
    $task = htmlspecialchars( $_POST['task'] );
}

// Store Date Data into variable $date
if ( isset( $_POST['date'] ) && !empty( $_POST['date'] ) ){
	$date = $_POST['date'];
    $date = htmlspecialchars( $_POST['date'] );
}

// Store complete/delete button id data into variable $taskid
if ( isset( $_POST['taskid'] ) && !empty( $_POST['taskid'] ) ){
	$taskid = $_POST['taskid'];
	$taskid = htmlspecialchars( $_POST['taskid'] );
}

// Bulk complete/delete data store into variable $taskids
if ( isset( $_POST['taskids'] ) && !empty( $_POST['taskids'] ) ){
	$taskids = $_POST['taskids'];
	$selectedids = join(",", $taskids);
}

$query = "INSERT INTO {$tableName} ( task, date ) VALUES ('$task', '$date')";
$completeQuery = "UPDATE {$tableName} SET complete=1 WHERE id={$taskid} LIMIT 1";
$deleteQuery = "DELETE FROM {$tableName} WHERE id={$taskid} LIMIT 1";
$incompleteQuery = "UPDATE {$tableName} SET complete=0 WHERE id={$taskid} LIMIT 1"; 
$bulkCompleteQuery = "UPDATE {$tableName} SET complete=1 WHERE id in($selectedids)"; 
$bulkDeleteQuery = "DELETE FROM {$tableName} WHERE id in($selectedids)";

// Insert task data into mysql database
if (!$connection){
	throw new Exception("Cannot connect to database");
}else{
	$action = $_POST['action'] ?? '';

	if (!$action){
		header('Location: index.php');
		die();
	}else{
		//Add task data action start
		if ("add" == $action){
			mysqli_query($connection, $query);
			header( 'Location: index.php?added=true' );

		//Complete button action start
		}elseif ("complete" == $action){
			if ($taskid){
				mysqli_query($connection, $completeQuery);
			}
			header( 'Location: index.php' );

		//Delete button action start
		}elseif ("delete" == $action){
			if ($taskid){
				mysqli_query($connection, $deleteQuery);
			}
			header( 'Location: index.php' );

		//Incomplete button action start
		}elseif ("incomplete" == $action){
			if ($taskid){
				mysqli_query($connection, $incompleteQuery);
			}
			header( 'Location: index.php' );

		//Bulk complete action start
		}elseif ("bulkcomplete" == $action){
			if ($taskids){
				mysqli_query($connection, $bulkCompleteQuery);
			}
			header( 'Location: index.php' );

		//Bulk delete action start
		}elseif ("bulkdelete" == $action){
			if ($taskids){
				mysqli_query($connection, $bulkDeleteQuery);
			}
			header( 'Location: index.php' );
		}
	}
}
mysqli_close($connection);
