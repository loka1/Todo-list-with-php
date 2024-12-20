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

// Get the filter status from the query parameters
$filter_status = isset($_GET['status']) ? $_GET['status'] : 'All';

// Create the SQL query to select tasks for the logged-in user with pagination and filtering
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM task_table WHERE user_id = $user_id";
if ($filter_status != 'All') {
    $query .= " AND status = '$filter_status'";
}
$query .= " ORDER BY id DESC LIMIT $tasks_per_page OFFSET $offset";
$result = $dbcon->query($query);

// Query to count total tasks for pagination
$query_total = "SELECT COUNT(*) AS total FROM task_table WHERE user_id = $user_id";
if ($filter_status != 'All') {
    $query_total .= " AND status = '$filter_status'";
}
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

      <!-- Button to toggle sidebar -->
      <button class="btn btn-primary mb-4" id="toggle-sidebar" type="button">
        Show Filters and Options
      </button>
      <!-- Sidebar for filter buttons and toggle strikethrough button -->
      <div id="sidebar" class="sidebar">
        <div class="sidebar-header">
          <!-- <h5 class="sidebar-title">Filters and Options</h5> -->
          <button type="button" class="btn-close" id="close-sidebar" aria-label="Close">&times;</button>
        </div>
        <div class="sidebar-body">
          <!-- Filter buttons -->
          <div class="mb-4">
        <h5 class="card-title bg-dark text-white p-2">Filter Tasks By Status</h5>
        <div class="btn-group-vertical w-100" role="group" aria-label="Filter tasks">
          <a href="index.php?status=All" class="btn btn-secondary <?php echo $filter_status == 'All' ? 'active' : ''; ?>">All</a>
          <a href="index.php?status=Pending" class="btn btn-warning <?php echo $filter_status == 'Pending' ? 'active' : ''; ?>">Pending</a>
          <a href="index.php?status=In Progress" class="btn btn-info <?php echo $filter_status == 'In Progress' ? 'active' : ''; ?>">In Progress</a>
          <a href="index.php?status=Completed" class="btn btn-success <?php echo $filter_status == 'Completed' ? 'active' : ''; ?>">Completed</a>
        </div>
          </div>

          <!-- Button to toggle strikethrough effect -->
          <div class="mb-4">
        <h5 class="card-title bg-dark text-white p-2">Task Display Options</h5>
        <button id="toggle-strikethrough" class="btn btn-primary">Toggle Strikethrough</button>
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
                    $completed_class = $row['status'] == 'Completed' ? 'class="completed-task"' : '';
                    echo "<tr $completed_class>";
                    echo "<td>" . $serial++ . "</td>";
                    echo "<td>" . htmlspecialchars($row['task_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                    echo "<td>" . date('Y-m-d', strtotime($row['created_at'])) . "</td>";
                    echo "<td>" . date('H:i:s', strtotime($row['created_at'])) . "</td>";
                    echo "<td>
                            <a href='update.php?id=" . base64_encode($row['id']) . "' class='btn btn-sm btn-primary'>Edit</a>
                            <a href='delete.php?id=" . base64_encode($row['id']) . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this task?\");'>Delete</a>
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
      </div>

      <!-- Pagination links -->
      <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
          <?php if ($page > 1): ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?php echo $page - 1; ?>&status=<?php echo $filter_status; ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
              </a>
            </li>
          <?php endif; ?>
          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
              <a class="page-link" href="?page=<?php echo $i; ?>&status=<?php echo $filter_status; ?>"><?php echo $i; ?></a>
            </li>
          <?php endfor; ?>
          <?php if ($page < $total_pages): ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?php echo $page + 1; ?>&status=<?php echo $filter_status; ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var toggleSidebarButton = document.getElementById('toggle-sidebar');
    var closeSidebarButton = document.getElementById('close-sidebar');
    var sidebar = document.getElementById('sidebar');
    var toggleStrikethroughButton = document.getElementById('toggle-strikethrough');
    var completedTasks = document.querySelectorAll('.completed-task');
    var strikethroughEnabled = true;

    toggleSidebarButton.addEventListener('click', function() {
      sidebar.style.display = 'block';
    });

    closeSidebarButton.addEventListener('click', function() {
      sidebar.style.display = 'none';
    });

    function toggleStrikethrough() {
      completedTasks.forEach(function(task) {
        if (strikethroughEnabled) {
          task.style.textDecoration = 'none';
        } else {
          task.style.textDecoration = 'line-through';
        }
      });
      strikethroughEnabled = !strikethroughEnabled;
    }

    toggleStrikethroughButton.addEventListener('click', toggleStrikethrough);

    // Initially apply strikethrough
    toggleStrikethrough();
  });
</script>

<style>
  .sidebar {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 400px  ;
    height: 100%;
    background-color: #f8f9fa;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    z-index: 1050;
    padding: 15px;
    overflow-y: auto;
  }
  .sidebar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .sidebar-title {
    margin: 0;
  }
  .btn-close {
    background: none;
    border: none;
    font-size: 1.5rem;
  }
</style>

<?php 
// Include the footer file
require_once "footer.php"; // This includes the footer file, which contains the js scripts and ending body tags
?>