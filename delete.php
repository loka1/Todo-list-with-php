<?php
/*
 * Start the session to use session variables
 * This initializes a session, allowing you to store and retrieve data across multiple pages
 */
session_start();

/*
 * Include the database connection file
 * This includes the database connection file, allowing you to use the $dbcon variable for database operations
 */
require_once "db.php";

/*
 * Check if the 'id' parameter is set in the URL
 * This checks if the 'id' parameter is present in the URL, ensuring that we have an ID to work with
 */
if (isset($_GET['id'])) {
  /*
   * Decode the base64 encoded 'id' parameter from the URL
   * This decodes the base64 encoded 'id' parameter from the URL to get the original ID value
   */
  $id = base64_decode($_GET['id']);
  
  /*
   * Create the SQL query to delete the task with the specified id
   * This creates an SQL query to delete the task with the specified ID from the task_table
   */
  $delete_query = "DELETE FROM task_table WHERE id=$id";
  
  /*
   * This line is redundant and does nothing
   * This line is unnecessary and does not affect the code execution
   */
  $delete_query;
  
  /*
   * Execute the delete query using the database connection
   * This executes the delete query using the database connection
   */
  $run_query = $dbcon->query($delete_query);
  
  /*
   * Check if the query was executed successfully
   * This checks if the query was executed successfully
   */
  if($run_query){
    /*
     * Set a session variable to indicate successful deletion
     * This sets a session variable to indicate that the task was deleted successfully
     */
    $_SESSION['delete_success'] = "Task delete successfully";
  }

  /*
   * Redirect to the index.php page after deletion
   * This redirects the user to the index.php page after the deletion
   */
  header('location: index.php');
}
/*
 * If the 'id' parameter is not set in the URL
 * This block executes if the 'id' parameter is not set in the URL
 */
else{
  /*
   * Redirect to the index.php page
   * This redirects the user to the index.php page if no 'id' parameter is found
   */
  header('location: index.php');
}

?>
