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

// Initialize error messages
$error_messages = [
    'task_name' => '',
    'description' => '',
    'status' => '',
    'unique' => ''
];

// Check if the 'addtask' parameter is set in the POST request
if (isset($_POST['addtask'])) {
    // Retrieve the values from the POST request
    $task_name = $_POST['task_name'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $user_id = $_SESSION['user_id']; // Get the user_id from the session
    // Trim and sanitize the input data
    $task_name = trim(filter_var($task_name, FILTER_SANITIZE_STRING));
    $description = trim(filter_var($description, FILTER_SANITIZE_STRING));
    $status = trim(filter_var($status, FILTER_SANITIZE_STRING));

    // Validate the input lengths
    if (strlen($task_name) < 3 || strlen($task_name) > 255) {
        $error_messages['task_name'] = "Task name must be between 3 and 255 characters.";
    }
    if (strlen($description) < 5 || strlen($description) > 1000) {
        $error_messages['description'] = "Description must be between 5 and 1000 characters.";
    }
    // Validate the status input
    $valid_statuses = ['Pending', 'In Progress', 'Completed'];
    if (!in_array($status, $valid_statuses)) {
        $error_messages['status'] = "Invalid status selected.";
    }

    // Check if a task with the same name already exists for the same user
    $check_query = "SELECT * FROM task_table WHERE task_name = ? AND user_id = ?";
    if ($stmt = $dbcon->prepare($check_query)) {
        $stmt->bind_param("si", $task_name, $user_id);
        $stmt->execute();
        $check_result = $stmt->get_result();

        if ($check_result->num_rows > 0) {
            $error_messages['unique'] = "A task with the same name already exists.";
        }
        $stmt->close();
    } else {
        $error_messages['unique'] = "Database query failed.";
    }

    // If there are no validation errors, proceed with the insertion
    if (empty($error_messages['task_name']) && empty($error_messages['description']) && empty($error_messages['status']) && empty($error_messages['unique'])) {
        // Create the SQL query to insert the new task into the task_table
        $task_add_query = "INSERT INTO task_table (task_name, description, status, user_id) VALUES ('$task_name', '$description', '$status', '$user_id')";

        // Execute the insert query using the database connection
        $add_query = $dbcon->query($task_add_query);

        // Check if the query was executed successfully
        if ($add_query) {
            // Set a session variable to indicate successful addition
            $_SESSION['add_success'] = "Task added successfully!";
        } else {
            // Set a session variable to indicate failure
            $_SESSION['add_failure'] = "Failed to add task!";
        }

        // Redirect to the index.php page after adding the task
        header('location: index.php');
        exit();
    }
}
?>

<div class="container-fluid mt-5">
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
              <label for="task_name">Task Name</label>
              <input class="form-control form-control-lg <?php echo !empty($error_messages['task_name']) || !empty($error_messages['unique']) ? 'is-invalid' : ''; ?>" type="text" id="task_name" name="task_name" value="<?php echo isset($task_name) ? htmlspecialchars($task_name) : ''; ?>" placeholder="Enter task name" required>
              <?php if (!empty($error_messages['task_name'])): ?>
                <div class="invalid-feedback">
                  <?php echo $error_messages['task_name']; ?>
                </div>
              <?php endif; ?>
              <?php if (!empty($error_messages['unique'])): ?>
                <div class="invalid-feedback">
                  <?php echo $error_messages['unique']; ?>
                </div>
              <?php endif; ?>
            </div>
            <div class="form-group">
              <!-- Input field for entering a description -->
              <label for="description">Description</label>
              <textarea class="form-control form-control-lg <?php echo !empty($error_messages['description']) ? 'is-invalid' : ''; ?>" id="description" name="description" placeholder="Enter task description" required><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea>
              <?php if (!empty($error_messages['description'])): ?>
                <div class="invalid-feedback">
                  <?php echo $error_messages['description']; ?>
                </div>
              <?php endif; ?>
            </div>
            <div class="form-group">
              <!-- Select field for task status -->
              <label for="status">Status</label>
              <select class="form-control form-control-lg <?php echo !empty($error_messages['status']) ? 'is-invalid' : ''; ?>" id="status" name="status" required>
                <option value="">Select status</option>
                <option value="Pending" <?php if (isset($status) && $status == 'Pending') echo 'selected'; ?>>Pending</option>
                <option value="In Progress" <?php if (isset($status) && $status == 'In Progress') echo 'selected'; ?>>In Progress</option>
                <option value="Completed" <?php if (isset($status) && $status == 'Completed') echo 'selected'; ?>>Completed</option>
              </select>
              <?php if (!empty($error_messages['status'])): ?>
                <div class="invalid-feedback">
                  <?php echo $error_messages['status']; ?>
                </div>
              <?php endif; ?>
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