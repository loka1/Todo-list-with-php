<?php
// Start the session to use session variables
session_start(); // This initializes a session, allowing you to store and retrieve data across multiple pages

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, set a session message and redirect to the login page
    $_SESSION['login_message'] = "Please log in to add a task.";
    header("Location: login.php");
    exit();
}

// Include the header file
require_once "header.php"; // This includes the header file, which contains the HTML head and opening body tags

// Include the database connection file
require_once 'db.php';

// Check if the 'addtask' parameter is set in the POST request
if(isset($_POST['addtask'])){
  // Retrieve the values from the POST request
  $task_name = $_POST['task_name'];
  $description = $_POST['description'];
  $status = $_POST['status'];
  $username = $_SESSION['user_id']; // Get the user_id from the session

  // Check if the required fields are not empty
  if(!empty($task_name) && !empty($description) && !empty($status)){
    // Create the SQL query to insert the new task into the task_table
    $task_add_query = "INSERT INTO task_table (task_name, description, status, user_id) VALUES ('$task_name', '$description', '$status', '$username')";
    
    // Execute the insert query using the database connection
    $add_query = $dbcon->query($task_add_query);
    
    // Check if the query was executed successfully
    if($add_query){
      // Set a session variable to indicate successful addition
      $_SESSION['add_success'] = "Task added successfully!";
    } else {
      // Set a session variable to indicate failure
      $_SESSION['add_failure'] = "Failed to add task!";
    }
  } else {
    // Set a session variable to indicate empty input
    $_SESSION['add_failure'] = "All fields are required!";
  }

  // Redirect to the index.php page after adding the task
  header('location: index.php');
  exit();
}
?>

<div class="container-fluid">

  <div class="row justify-content-center">
    <div class="col-12 col-md-8">
      <!-- Bootstrap card for the add task form -->
      <div class="card">
        <div class="card-header text-center">
          <h2>Add New Task</h2>
        </div>
        <div class="card-body">
          <!-- Form to add a new task -->
          <form action="add.php" method="post">
            <div class="form-group">
              <!-- Input field for entering a new task name -->
              <input class="form-control form-control-lg" type="text" name="task_name" placeholder="Enter task name" required>
            </div>
            <div class="form-group">
              <!-- Input field for entering a description -->
              <textarea class="form-control form-control-lg" name="description" placeholder="Enter task description" required></textarea>
            </div>
            <div class="form-group">
              <!-- Select field for task status -->
              <select class="form-control form-control-lg" name="status" required>
                <option value="">Select status</option>
                <option value="Pending">Pending</option>
                <option value="In Progress">In Progress</option>
                <option value="Completed">Completed</option>
              </select>
            </div>
            <div class="form-group">
              <!-- Submit button to add the task -->
              <input class="btn btn-success btn-block" type="submit" name="addtask" value="Add Task">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php 
// Include the footer file
require_once "footer.php"; // This includes the footer file, which contains the js scripts and ending body tags
?>