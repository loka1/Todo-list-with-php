<?php
/*
 * Define the database host
 * This constant defines the hostname of the database server
 */
const HOST = 'localhost';

/*
 * Define the database username
 * This constant defines the username used to connect to the database
 */
const USERNAME = 'user_name';

/*
 * Define the database password
 * This constant defines the password used to connect to the database
 */
const PASSWORD = 'password';

/*
 * Define the database name
 * This constant defines the name of the database to connect to
 */
const DBNAME = 'todo_app';

/*
 * Create a new MySQLi connection
 * This creates a new connection to the MySQL database using the defined constants
 */
$dbcon = new mysqli(HOST, USERNAME, PASSWORD, DBNAME);

/*
 * Check if the connection has any errors
 * This checks if there was an error while trying to connect to the database
 */
if ($dbcon->connect_error) {
  /*
   * Terminate the script if there is a connection error
   * This terminates the script and outputs an error message if the connection fails
   */
  die("connect error: " . $dbcon->connect_error);
}
// Set the character set to utf8mb4
$dbcon->set_charset("utf8mb4");
?>