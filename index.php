<?php
// Start the session to use session variables
session_start(); // This initializes a session, allowing you to store and retrieve data across multiple pages

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  // If the user is not logged in, set a session message and redirect to the login page
  $_SESSION['login_message'] = "Please log in to view your tasks.";
  header("Location: login.php");
  exit();
}

// Include the header file
require_once "header.php"; // This includes the header file, which contains the HTML head and opening body tags

// Include the database connection file
require_once 'db.php';

// Pagination settings
$tasks_per_page = 10; // Number of tasks to display per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
$offset = ($page - 1) * $tasks_per_page; // Offset for SQL query

// Create the SQL query to select tasks for the logged-in user with pagination
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM task_table WHERE user_id = $user_id ORDER BY id DESC LIMIT $tasks_per_page OFFSET $offset";
$result = $dbcon->query($query);

// Query to count total tasks for pagination
$query_total = "SELECT COUNT(*) AS total FROM task_table WHERE user_id = $user_id";
$total_tasks = $dbcon->query($query_total)->fetch_assoc()['total'];
$total_pages = ceil($total_tasks / $tasks_per_page);

// Queries to count tasks by status
$query_pending = "SELECT COUNT(*) AS count FROM task_table WHERE user_id = $user_id AND status = 'Pending'";
$query_in_progress = "SELECT COUNT(*) AS count FROM task_table WHERE user_id = $user_id AND status = 'In Progress'";
$query_completed = "SELECT COUNT(*) AS count FROM task_table WHERE user_id = $user_id AND status = 'Completed'";

$count_pending = $dbcon->query($query_pending)->fetch_assoc()['count'];
$count_in_progress = $dbcon->query($query_in_progress)->fetch_assoc()['count'];
$count_completed = $dbcon->query($query_completed)->fetch_assoc()['count'];
?>

<div class="container-fluid mt-5">
  <div class="row justify-content-center">
    <div class="col-12 col-md-10">
      <!-- Widgets to show task counts by status -->
      <div class="row mb-4">
        <div class="col-md-4">
          <div class="card text-white bg-warning">
            <div class="card-body">
              <h5 class="card-title">Pending Tasks</h5>
              <p class="card-text"><?php echo $count_pending; ?></p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-white bg-info">
            <div class="card-body">
              <h5 class="card-title">In Progress Tasks</h5>
              <p class="card-text"><?php echo $count_in_progress; ?></p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-white bg-success">
            <div class="card-body">
              <h5 class="card-title">Completed Tasks</h5>
              <p class="card-text"><?php echo $count_completed; ?></p>
            </div>
          </div>
        </div>
      </div>

      <!-- Bootstrap card for the task list -->
      <div class="card">
        <div class="card-header text-center">
          <h2>My To Do List</h2>
        </div>
        <div class="card-body">
          <!-- Responsive table wrapper -->
          <div class="table-responsive">
            <!-- Table to display tasks -->
            <table class="table table-sm table-borderless table-striped text-center">
              <thead class="bg-dark text-white">
                <tr>
                  <th>Serial</th>
                  <th>Task Name</th>
                  <th>Status</th>
                  <th>Added Date</th>
                  <th>Added Time</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if ($result->num_rows > 0) {
                  $serial = $offset + 1;
                  while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $serial++ . "</td>";
                    echo "<td>" . htmlspecialchars($row['task_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                    echo "<td>" . date('Y-m-d', strtotime($row['created_at'])) . "</td>";
                    echo "<td>" . date('H:i:s', strtotime($row['created_at'])) . "</td>";
                    echo "<td>
                            <a href='update.php?id=" . base64_encode($row['id']) . "' class='btn btn-sm btn-primary'>Edit</a>
                            <a href='delete.php?id=" . base64_encode($row['id']) . "' class='btn btn-sm btn-danger'>Delete</a>
                          </td>";
                    echo "</tr>";
                  }
                } else {
                  echo "<tr><td colspan='7'>No tasks found.</td></tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer">
          <!-- Pagination links -->
      <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
          <?php if ($page > 1): ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
              </a>
            </li>
          <?php endif; ?>
          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
              <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
          <?php endfor; ?>
          <?php if ($page < $total_pages): ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>
        </div>
      </div>

      
    </div>
  </div>
</div>

<?php 
// Include the footer file
require_once "footer.php"; // This includes the footer file, which contains the js scripts and ending body tags
?>