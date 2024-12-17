<?php
// Start the session
session_start();


// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
  // If the user is logged in, redirect to the index page
  header("Location: index.php");
  exit();
}

// Include the database connection file
require_once "db.php";

// Include the header file
require_once "header.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute the query to check the user's credentials
    $query = "SELECT * FROM users WHERE username = ?";
    if ($stmt = $dbcon->prepare($query)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the user exists
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Set session variables and redirect to the index page
                $_SESSION['user_id'] = $user['id']; // Set the user ID in the session
                $_SESSION['username'] = $user['username'];
                header("Location: index.php");
                exit;
            } else {
                $error_message = "Incorrect password.";
            }
        } else {
            $error_message = "Username not found.";
        }
        $stmt->close();
    } else {
        $error_message = "Database query failed.";
    }
}
?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-12 col-md-6">
      <!-- Bootstrap card for the login form -->
      <div class="card">
        <div class="card-header text-center">
          <h2>Login</h2>
        </div>
        <div class="card-body">
          <!-- Display logout message if set -->
          <?php
          if (isset($_SESSION['logout_message'])) {
              echo '<div class="alert alert-success" role="alert">' . $_SESSION['logout_message'] . '</div>';
              unset($_SESSION['logout_message']);
          }
          ?>
          <!-- Display register success message if set -->
          <?php
          if (isset($_SESSION['register_success'])) {
              echo '<div class="alert alert-success" role="alert">' . $_SESSION['register_success'] . '</div>';
              unset($_SESSION['register_success']);
          }
          ?>
          <!-- Display error message if set -->
          <?php
          if (isset($error_message)) {
              echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
          }
          ?>
          <!-- Login form -->
          <form action="login.php" method="post">
            <div class="form-group">
              <label for="username">Username</label>
              <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
          </form>
          <div class="text-center mt-3">
            <a href="register.php">Don't have an account? Register here</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
// Include the footer file
require_once "footer.php";
?>