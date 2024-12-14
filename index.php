<?php
// Start the session to use session variables
session_start(); // This initializes a session, allowing you to store and retrieve data across multiple pages

// Include the header file
require_once "header.php"; // This includes the header file, which contains the HTML head and opening body tags
?>

<div class="container-fluid mt-5">
  <div class="row justify-content-center">
    <div class="col-12 col-md-10">
      <!-- Bootstrap card for the task list -->
      <div class="card">
        <div class="card-header text-center">
          <h2>My To Do List</h2>
        </div>
        <div class="card-body">
          <!-- Table to display tasks -->
          <table class="table table-sm table-borderless table-striped text-center">
            <thead class="bg-dark text-white">
              <tr>
                <th>Serial</th>
                <th>Task</th>
                <th>Added Date</th>
                <th>Added Time</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              require_once "db.php"; // Include the database connection file
              $task_show_query = "SELECT * FROM task_table"; // Query to select all tasks from the task_table
              $result = $dbcon->query($task_show_query); // Execute the query
              if($result->num_rows != 0) { // Check if there are any tasks in the table
                $serial = 1; // Initialize the serial number
                foreach ($result as $row) { // Loop through each task
                  $temp_date_time = explode(' ', $row['added_tiime']); // Split the added time into date and time
                  $date = $temp_date_time[0]; // Get the date part
                  $time = $temp_date_time[1]; // Get the time part
              ?>
                <tr>
                  <td><?=$serial++?></td> <!-- Display the serial number -->
                  <td><?=$row['task_name']?></td> <!-- Display the task name -->
                  <td><?=$date?></td> <!-- Display the added date -->
                  <td><?=$time?></td> <!-- Display the added time -->
                  <td>
                    <div class="btn-group">
                      <a class="btn btn-sm btn-warning" href="update.php?id=<?php echo base64_encode($row['id']); ?>">Update</a> <!-- Link to update the task -->
                      <a class="btn btn-sm btn-danger" href="delete.php?id=<?php echo base64_encode($row['id']); ?>">Delete</a> <!-- Link to delete the task -->
                    </div>
                  </td>
                </tr>
              <?php
                }
              } else { // If no tasks are found
              ?>
                <tr>
                  <td colspan="5" class="text-center display-4">No task</td> <!-- Display 'No task' message -->
                </tr>
              <?php
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<?php 
/*
 * Include the footer file
 * This includes the footer file, which contains the js scripts and ending body tags
 */
require_once 'footer.php';
?>
