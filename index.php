<?php

include_once "config.php";

$connection = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
$tableName = DB_TABLE;

if (!$connection){
	throw new Exception("Cannot connect to database");
}

// Store all tasks data into $query for display
$query = "SELECT * FROM {$tableName} WHERE complete=0 ORDER BY date";
$result = mysqli_query($connection, $query);

// Store all complete tasks data into $completeTasksQuery for display
$completeTasksQuery = "SELECT * FROM {$tableName} WHERE complete=1 ORDER BY date";
$resultCompleteTasks = mysqli_query($connection, $completeTasksQuery);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tasks Project</title>
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="//cdn.rawgit.com/necolas/normalize.css/master/normalize.css">
    <link rel="stylesheet" href="//cdn.rawgit.com/milligram/milligram/master/dist/milligram.min.css">
    <style>
        body { margin-top: 30px; }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="column column-60 column-offset-20">
            <h2>Project 4 - Tasks Project</h2>
            <p>This a simple task project with php</p>

<!-- Display Complete Tasks Data Start -->

<?php 
	if (mysqli_num_rows($resultCompleteTasks) > 0):

?>
<h4>Complete Tasks</h4>
<table>
	<thead>
		<tr>
			<th></th>
			<th>ID</th>
			<th>TASKS</th>
			<th>DATE</th>
			<th>ACTION</th>
		</tr>
	</thead>
	<tbody>
<?php

		while ($_data = mysqli_fetch_assoc($resultCompleteTasks)):
			$timestamp = strtotime($_data['date']);
			$_date = date("jS M, Y", $timestamp);

			$_id = $_data['id'];
			$_task = $_data['task'];
?> 
					<tr>
        				<td></td>
        				<td><?php echo $_id; ?></td>
        				<td><?php echo $_task; ?></td>
        				<td><?php echo $_date; ?></td>
        				<td><a class="delete" data-taskid="<?php echo $_id; ?>" href="#">Delete</a> |
        					<a class="incomplete" data-taskid="<?php echo $_id; ?>" href="#">Incomplete</a>
        				</td>
					</tr>
<?php
		endwhile;
?>
		</tbody>
	</table>
	<p>...</p>
<?php
	endif;
?>

<!-- Display Complete Tasks Data End -->

<!-- Display Upcoming Task Data Start -->
<h4>Upcoming Tasks</h4>

<?php 
	if (mysqli_num_rows($result) == 0):

      ?><p>No Task Found</p><?php

	else:
?>
<form action="tasks.php" method="POST">
	<table>
		<thead>
			<tr>
				<th></th>
				<th>ID</th>
				<th>TASKS</th>
				<th>DATE</th>
				<th>ACTION</th>
			</tr>
		</thead>
		<tbody>
<?php 
	while ($data = mysqli_fetch_assoc($result)):
		$timestamp = strtotime($data['date']);
		$date = date("jS M, Y", $timestamp);

		$id = $data['id'];
		$task = $data['task'];
?>
		<tr>
			<td><input name="taskids[]" class="label-inline" type="checkbox" value="<?php echo $id; ?>"></td>
			<td><?php echo $id; ?></td>
			<td><?php echo $task; ?></td>
			<td><?php echo $date; ?></td>
			<td><a class="delete" data-taskid="<?php echo $id; ?>" href="#">Delete</a> | 
				<a class="complete" data-taskid="<?php echo $id; ?>" href="#">Complete</a>
			</td>
		</tr>
<?php
	endwhile;
	mysqli_close($connection);
?>
		</tbody>
	</table>
	<select id="action" name="action">
		<option value="0">With Selected</option>
		<option value="bulkdelete">Delete</option>
		<option value="bulkcomplete">Mark As Complete</option>
	</select>
	<input type="submit" class="button-primary" value="Submit">
</form>
<?php
endif;
?>
<!-- Display Upcoming Task Data End -->

<!-- Display Add Task Data Start -->
<h4>Add Tasks</h4>
<form method="POST" action="tasks.php">
  <fieldset>
<?php 
	// GET method to catch url end point data for display 
	$added = $_GET['added'] ?? '';
	if ($added){
		echo "<p>Task Successfully Added</p>";
	}
?>
	              <label for="task">Task</label>
	              <input type="text" name="task" id="task" placeholder="Task details">
	              <label for="date">Date</label>
	              <input type="date" name="date" id="date" placeholder="Task date">
	              <input type="submit" class="button-primary" value="Add Task">
	              <input type="hiden" name="action" value="add">
              </fieldset>
            </form>
        </div>
    </div>
</div>
<!-- Display Add Task Data End -->

<!-- Complete button action form start-->
<form action="tasks.php" method="POST" id="completeform">
	<input type="hiden" id="caction" name="action" value="complete">
	<input type="hiden" id="taskid" name="taskid" >
</form>
<!-- Complete button action form end-->

<!-- Delete button action form start-->
<form action="tasks.php" method="POST" id="deleteform">
	<input type="hiden" id="daction" name="action" value="delete">
	<input type="hiden" id="dtaskid" name="taskid" >
</form>
<!-- Delete button action form end-->

<!-- Incomplete button action form start-->
<form action="tasks.php" method="POST" id="incompleteform">
	<input type="hiden" id="iaction" name="action" value="incomplete">
	<input type="hiden" id="itaskid" name="taskid" >
</form>
<!-- Incomplete button action form end-->

</body>

<script src="https://code.jquery.com/jquery-3.6.0.slim.js"></script>
<script type="text/javascript" src="assets/js/script.js"></script>
<script>
	;(function($){
		$(document).ready(function(){
			$(".complete").on('click',function(){
				var id = $(this).data("taskid");
				$("#taskid").val(id);
				$("#completeform").submit();
			});
			$(".delete").on('click',function(){
				if (confirm('Are you sure to delete this task?')) {
					var id = $(this).data("taskid");
					$("#dtaskid").val(id);
					$("#deleteform").submit();
				}
			});
			$(".incomplete").on('click',function(){
				if (confirm('Are you sure to incomplete this task?')) {
					var id = $(this).data("taskid");
					$("#itaskid").val(id);
					$("#incompleteform").submit();
				}
			});
		});
	})(jQuery);
</script>
</html>

