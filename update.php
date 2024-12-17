<?php
// Start the session to use session variables
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, set a session message and redirect to the login page
    $_SESSION['login_message'] = "Please log in to update a task.";
    header("Location: login.php");
    exit();
}

// Include the header file
require_once "header.php"; // This includes the header file, which contains the HTML head and opening body tags

// Include the database connection file
require_once 'db.php';

// Decode the base64 encoded 'id' parameter from the URL
$id = base64_decode($_GET['id']);

// Create the SQL query to select the task with the specified id
$data = "SELECT * FROM task_table WHERE id=$id";

// Execute the select query using the database connection
$result = $dbcon->query($data);

// Check if the task exists
if ($result->num_rows == 1) {
    $task = $result->fetch_assoc();
} else {
    // If the task does not exist, redirect to the index page
    $_SESSION['update_failure'] = "Task not found.";
    header("Location: index.php");
    exit();
}

// Check if the 'updatetask' parameter is set in the POST request
if (isset($_POST['updatetask'])) {
    // Retrieve the values from the POST request
    $task_name = $_POST['task_name'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    // Check if the required fields are not empty
    if (!empty($task_name) && !empty($description) && !empty($status)) {
        // Create the SQL query to update the task in the task_table
        $task_update_query = "UPDATE task_table SET task_name='$task_name', description='$description', status='$status' WHERE id=$id";

        // Execute the update query using the database connection
        $update_query = $dbcon->query($task_update_query);

        // Check if the query was executed successfully
        if ($update_query) {
            // Set a session variable to indicate successful update
            $_SESSION['update_success'] = "Task updated successfully!";
        } else {
            // Set a session variable to indicate failure
            $_SESSION['update_failure'] = "Failed to update task!";
        }
    } else {
        // Set a session variable to indicate empty input
        $_SESSION['update_failure'] = "All fields are required!";
    }

    // Redirect to the index.php page after updating the task
    header('location: index.php');
    exit();
}
?>

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-12 col-md-8">
      <!-- Bootstrap card for the update task form -->
      <div class="card">
        <div class="card-header text-center">
          <h2>Update Task</h2>
        </div>
        <div class="card-body">
          <!-- Form to update the task -->
          <form action="update.php?id=<?php echo base64_encode($id); ?>" method="post">
            <div class="form-group">
              <!-- Input field for updating the task name -->
              <input class="form-control form-control-lg" type="text" name="task_name" value="<?php echo $task['task_name']; ?>" placeholder="Enter task name" required>
            </div>
            <div class="form-group">
              <!-- Input field for updating the description -->
              <textarea class="form-control form-control-lg" name="description" placeholder="Enter task description" required><?php echo $task['description']; ?></textarea>
            </div>
            <div class="form-group">
              <!-- Select field for task status -->
              <select class="form-control form-control-lg" name="status" required>
                <option value="">Select status</option>
                <option value="Pending" <?php if ($task['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                <option value="In Progress" <?php if ($task['status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
                <option value="Completed" <?php if ($task['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
              </select>
            </div>
            <div class="form-group">
              <!-- Submit button to update the task -->
              <input class="btn btn-success btn-block" type="submit" name="updatetask" value="Update Task">
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