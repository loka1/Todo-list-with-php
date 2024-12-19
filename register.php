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

// Initialize error messages
$error_messages = [
    'username' => '',
    'password' => '',
    'confirm_password' => '',
    'register' => ''
];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate the input lengths
    if (strlen($username) < 3 || strlen($username) > 255) {
        $error_messages['username'] = "Username must be between 3 and 255 characters.";
    }
    if (strlen($password) < 6 || strlen($password) > 255) {
        $error_messages['password'] = "Password must be between 6 and 255 characters.";
    }
    if ($password !== $confirm_password) {
        $error_messages['confirm_password'] = "Passwords do not match.";
    }

    // If there are no validation errors, proceed with the registration
    if (empty($error_messages['username']) && empty($error_messages['password']) && empty($error_messages['confirm_password'])) {
        // Prepare and execute the query to check if the username already exists
        $query = "SELECT * FROM users WHERE username = ?";
        if ($stmt = $dbcon->prepare($query)) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if the username already exists
            if ($result->num_rows > 0) {
                $error_messages['register'] = "Username already exists. Please choose a different username.";
            } else {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Prepare and execute the query to insert the new user
                $insert_query = "INSERT INTO users (username, password) VALUES (?, ?)";
                if ($stmt = $dbcon->prepare($insert_query)) {
                    $stmt->bind_param("ss", $username, $hashed_password);
                    if ($stmt->execute()) {
                        // Set session variables and redirect to the index page
                        $_SESSION['user_id'] = $stmt->insert_id;
                        $_SESSION['username'] = $username;
                        header("Location: index.php");
                        exit();
                    } else {
                        $error_messages['register'] = "Registration failed. Please try again.";
                    }
                } else {
                    $error_messages['register'] = "Database query failed.";
                }
            }
            $stmt->close();
        } else {
            $error_messages['register'] = "Database query failed.";
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
          <?php if (!empty($error_messages['register'])): ?>
            <div class="alert alert-danger" role="alert">
              <?php echo $error_messages['register']; ?>
            </div>
          <?php endif; ?>
          <!-- Form to register -->
          <form action="register.php" method="post">
            <div class="form-group">
              <label for="username">Username</label>
              <input type="text" class="form-control <?php echo !empty($error_messages['username']) ? 'is-invalid' : ''; ?>" id="username" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" required>
              <?php if (!empty($error_messages['username'])): ?>
                <div class="invalid-feedback">
                  <?php echo $error_messages['username']; ?>
                </div>
              <?php endif; ?>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" class="form-control <?php echo !empty($error_messages['password']) ? 'is-invalid' : ''; ?>" id="password" name="password" required>
              <?php if (!empty($error_messages['password'])): ?>
                <div class="invalid-feedback">
                  <?php echo $error_messages['password']; ?>
                </div>
              <?php endif; ?>
            </div>
            <div class="form-group">
              <label for="confirm_password">Confirm Password</label>
              <input type="password" class="form-control <?php echo !empty($error_messages['confirm_password']) ? 'is-invalid' : ''; ?>" id="confirm_password" name="confirm_password" required>
              <?php if (!empty($error_messages['confirm_password'])): ?>
                <div class="invalid-feedback">
                  <?php echo $error_messages['confirm_password']; ?>
                </div>
              <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Register</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
// Include the footer file
require_once "footer.php";
?>