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
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // Prepare and execute the query to check if the username already exists
        $query = "SELECT * FROM users WHERE username = ?";
        if ($stmt = $dbcon->prepare($query)) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if the username already exists
            if ($result->num_rows > 0) {
                $error_message = "Username already exists. Please choose a different username.";
            } else {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Prepare and execute the query to insert the new user
                $query = "INSERT INTO users (username, password) VALUES (?, ?)";
                if ($stmt = $dbcon->prepare($query)) {
                    $stmt->bind_param("ss", $username, $hashed_password);
                    if ($stmt->execute()) {
                        // Redirect to login page with success message
                        $_SESSION['register_success'] = "Registration successful. Please log in.";
                        header("Location: login.php");
                        exit;
                    } else {
                        $error_message = "Registration failed. Please try again.";
                    }
                    $stmt->close();
                } else {
                    $error_message = "Database query failed.";
                }
            }
            $stmt->close();
        } else {
            $error_message = "Database query failed.";
        }
    }
}
?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-12 col-md-6">
      <!-- Bootstrap card for the registration form -->
      <div class="card">
        <div class="card-header text-center">
          <h2>Register</h2>
        </div>
        <div class="card-body">
          <!-- Display error message if set -->
          <?php
          if (isset($error_message)) {
              echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
          }
          ?>
          <!-- Registration form -->
          <form action="register.php" method="post">
            <div class="form-group">
              <label for="username">Username</label>
              <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
              <label for="confirm_password">Confirm Password</label>
              <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Register</button>
          </form>
          <div class="text-center mt-3">
            <a href="login.php">Already have an account? Login here</a>
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