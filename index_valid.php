<?php
require_once 'db.php';

/*
 * Check if the 'addtask' parameter is set in the POST request
 * This checks if the 'addtask' parameter is present in the POST request, indicating that the form was submitted
 */
if(isset($_POST['addtask'])){
  /*
   * Retrieve the value of the 'textfield' parameter from the POST request
   * This retrieves the value entered in the text field of the form
   */
  $task_add = $_POST['textfield'];
  
  /*
   * Check if the 'textfield' parameter is not empty
   * This checks if the text field is not empty, ensuring that there is a task to add
   */
  if(!empty($task_add)){
    /*
     * Create the SQL query to insert the new task into the task_table
     * This creates an SQL query to insert the new task into the task_table with the task_name column
     */
    $task_add_query = "INSERT INTO task_table (task_name) VALUES  ('$task_add')";
    
    /*
     * Execute the insert query using the database connection
     * This executes the insert query using the database connection
     */
    $add_query = $dbcon->query($task_add_query);
    
    /*
     * Check if the query was executed successfully
     * This checks if the query was executed successfully
     */
    if($add_query){
      /*
       * Set a session variable to indicate successful addition
       * This sets a session variable to indicate that the task was added successfully
       */
      $_SESSION['add_success'] = "Task added successfully!";
    } else {
      /*
       * Set a session variable to indicate failure
       * This sets a session variable to indicate that there was an error adding the task
       */
      $_SESSION['add_failure'] = "Failed to add task!";
    }
  } else {
    /*
     * Set a session variable to indicate empty input
     * This sets a session variable to indicate that the input field was empty
     */
    $_SESSION['add_failure'] = "Task field cannot be empty!";
  }

  /*
   * Redirect to the index.php page after adding the task
   * This redirects the user to the index.php page after the task is added
   */
  header('location: index.php');
}

?>
