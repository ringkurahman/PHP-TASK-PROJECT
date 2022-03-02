<?php

include_once "config.php";

$connection = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
if (!$connection){
	throw new Exception("Cannot connect to database");
}else{
	echo "Connected\n";

	// Insert a data into mysql server
	# INSERT INTO tasks (task, date) VALUES ('Do something', '2022-03-01')
	// mysqli_query( $connection, "INSERT INTO tasks (task, date) VALUES ('Do something', '2022-03-01')" );

	// Fetch data from mysql server
	# SELECT * FROM tasks
	/*$result = mysqli_query($connection, "SELECT * FROM tasks");
	while ($data = mysqli_fetch_assoc($result)){
		foreach ($data as $task){
			echo $task."\n";
		}
	}*/

	// Delete data from mysql server
	# DELETE FROM tasks
	// mysqli_query($connection, "DELETE FROM tasks");
	
	mysqli_close($connection);
}




