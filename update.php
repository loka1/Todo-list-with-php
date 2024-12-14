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
require_once 'db.php';

/*
 * Decode the base64 encoded 'id' parameter from the URL
 * This decodes the base64 encoded 'id' parameter from the URL to get the original ID value
 */
$id = base64_decode($_GET['id']);

/*
 * Create the SQL query to select the task with the specified id
 * This creates an SQL query to select the task with the specified ID from the task_table
 */
$data = "SELECT * FROM task_table WHERE id=$id";

/*
 * Execute the select query using the database connection
 * This executes the select query using the database connection
 */
$data_from_db = $dbcon->query($data);

/*
 * Fetch the result as an associative array
 * This fetches the result of the query as an associative array
 */
$f_result = $data_from_db->fetch_assoc();

/*
 * Check if the 'update' parameter is set in the POST request
 * This checks if the 'update' parameter is present in the POST request, indicating that the form was submitted
 */
if(isset($_POST['update'])){
  /*
   * Retrieve the value of the 'update_text' parameter from the POST request
   * This retrieves the value entered in the update text field of the form
   */
  $update_text = $_POST['update_text'];
  
  /*
   * Create the SQL query to update the task with the specified id
   * This creates an SQL query to update the task with the specified ID in the task_table
   */
  $update_query = "UPDATE task_table SET task_name='$update_text' WHERE id=$id";
  
  /*
   * Execute the update query using the database connection
   * This executes the update query using the database connection
   */
  $update_date = $dbcon->query($update_query);
  
  /*
   * Check if the query was executed successfully
   * This checks if the query was executed successfully
   */
  if($update_date){
    /*
     * Set a session variable to indicate successful update
     * This sets a session variable to indicate that the task was updated successfully
     */
    $_SESSION['update_success'] = "Task updated successfully!";
  }
  
  /*
   * Redirect to the index.php page after updating the task
   * This redirects the user to the index.php page after the task is updated
   */
  header('location: index.php');
}

?>

<?php 
/*
 * Include the header file
 * This includes the header file, which contains the HTML head and opening body tags
 */
require_once 'header.php';
?>

<div class="container-fluidmt-5">
  <div class='row justify-content-center'>
    <div class='col-12 col-md-8'>
      <!-- Bootstrap card for the update task form -->
      <div class="card">
        <div class="card-header text-center">
          <h2>Update Task</h2>
        </div>
        <div class="card-body">
          <!-- Form to update the task -->
          <form action="" method="post">
            <div class='form-group'>
              <!-- Input field for updating the task -->
              <input class="form-control form-control-lg" type="text" name="update_text" value="<?=$f_result['task_name'] ?>">
            </div>
            <div class='form-group'>
              <!-- Submit button to update the task -->
              <input class="btn btn-warning btn-block" type="submit" name="update" value="Update">
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

