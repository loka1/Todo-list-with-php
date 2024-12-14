<?php
// Start the session to use session variables
session_start(); // This initializes a session, allowing you to store and retrieve data across multiple pages

// Include the header file
require_once "header.php"; // This includes the header file, which contains the HTML head and opening body tags
?>

<div class="container-fluidmt-5">
  <div class="row justify-content-center">
    <div class="col-12 col-md-8">
      <!-- Bootstrap card for the add task form -->
      <div class="card">
        <div class="card-header text-center">
          <h2>Add New Task</h2>
        </div>
        <div class="card-body">
          <!-- Form to add a new task -->
          <form action="index_valid.php" method="post">
            <div class="form-group">
              <!-- Input field for entering a new task -->
              <input class="form-control form-control-lg" type="text" name="textfield" placeholder="Enter your task">
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
/*
 * Include the footer file
 * This includes the footer file, which contains the js scripts and ending body tags
 */
require_once 'footer.php';
?>
