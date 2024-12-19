<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  // If the user is not logged in, set a session message and redirect to the login page
  $_SESSION['login_message'] = "Please log in to add a task.";
  header("Location: login.php");
  exit();
}

// Include the database connection file
require_once "db.php";

// Include the header file
require_once "header.php";

// Fetch the user's information from the database
$username = $_SESSION['username'];
$query = "SELECT * FROM users WHERE username = ?";
if ($stmt = $dbcon->prepare($query)) {
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
} else {
    echo "Database query failed.";
    exit;
}

// Initialize error messages
$error_messages = [
    'current_password' => '',
    'new_password' => '',
    'confirm_password' => '',
    'new_username' => '',
    'update_password' => '',
    'update_username' => ''
];

// Handle form submission for updating username or password
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_username'])) {
        $new_username = trim(filter_input(INPUT_POST, 'new_username', FILTER_SANITIZE_STRING));

        // Validate new username
        if (empty($new_username)) {
            $error_messages['new_username'] = "New username is required.";
        } elseif (strlen($new_username) < 3 || strlen($new_username) > 255) {
            $error_messages['new_username'] = "Username must be between 3 and 255 characters.";
        } else {
            // Check if the new username already exists
            $query = "SELECT * FROM users WHERE username = ?";
            if ($stmt = $dbcon->prepare($query)) {
                $stmt->bind_param("s", $new_username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $error_messages['new_username'] = "Username already exists. Please choose a different username.";
                } else {
                    // Update the username
                    $query = "UPDATE users SET username = ? WHERE id = ?";
                    if ($stmt = $dbcon->prepare($query)) {
                        $stmt->bind_param("si", $new_username, $user['id']);
                        if ($stmt->execute()) {
                            $_SESSION['username'] = $new_username;
                            $success_message = "Username updated successfully.";
                        } else {
                            $error_messages['update_username'] = "Failed to update username. Please try again.";
                        }
                        $stmt->close();
                    } else {
                        $error_messages['update_username'] = "Database query failed.";
                    }
                }
                $stmt->close();
            } else {
                $error_messages['update_username'] = "Database query failed.";
            }
        }
    } elseif (isset($_POST['update_password'])) {
        $current_password = trim(filter_input(INPUT_POST, 'current_password', FILTER_SANITIZE_STRING));
        $new_password = trim(filter_input(INPUT_POST, 'new_password', FILTER_SANITIZE_STRING));
        $confirm_password = trim(filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_STRING));

        // Validate input fields
        if (empty($current_password)) {
            $error_messages['current_password'] = "Current password is required.";
        }
        if (empty($new_password)) {
            $error_messages['new_password'] = "New password is required.";
        } elseif (strlen($new_password) < 8) {
            $error_messages['new_password'] = "New password must be at least 8 characters long.";
        }
        if (empty($confirm_password)) {
            $error_messages['confirm_password'] = "Confirm password is required.";
        } elseif ($new_password !== $confirm_password) {
            $error_messages['confirm_password'] = "New passwords do not match.";
        }

        // If there are no validation errors, proceed with the password update
        if (empty($error_messages['current_password']) && empty($error_messages['new_password']) && empty($error_messages['confirm_password'])) {
            // Verify the current password
            if (password_verify($current_password, $user['password'])) {
                // Hash the new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update the password
                $query = "UPDATE users SET password = ? WHERE username = ?";
                if ($stmt = $dbcon->prepare($query)) {
                    $stmt->bind_param("ss", $hashed_password, $username);
                    if ($stmt->execute()) {
                        $success_message = "Password updated successfully.";
                    } else {
                        $error_messages['update_password'] = "Failed to update password. Please try again.";
                    }
                    $stmt->close();
                } else {
                    $error_messages['update_password'] = "Database query failed.";
                }
            } else {
                $error_messages['current_password'] = "Current password is incorrect.";
            }
        }
    }
}
?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-12 col-md-6">
      <!-- Bootstrap card for the profile information -->
      <div class="card">
        <div class="card-header text-center">
          <h2>Profile</h2>
        </div>
        <div class="card-body">
          <!-- Display success or error message if set -->
          <?php
          if (isset($success_message)) {
              echo '<div class="alert alert-success" role="alert">' . $success_message . '</div>';
          }
          if (isset($error_message)) {
              echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
          }
          ?>
          <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
          <p><strong>Account Created:</strong> <?php echo htmlspecialchars($user['created_at']); ?></p>
          <!-- Form to update username -->
          <form action="profile.php" method="post">
            <div class="form-group">
              <label for="new_username">New Username</label>
              <input type="text" class="form-control <?php echo !empty($error_messages['new_username']) ? 'is-invalid' : ''; ?>" id="new_username" name="new_username" required>
              <?php if (!empty($error_messages['new_username'])): ?>
                <div class="invalid-feedback">
                  <?php echo $error_messages['new_username']; ?>
                </div>
              <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary btn-block" name="update_username">Update Username</button>
          </form>
          <hr>
          <!-- Form to update password -->
          <form action="profile.php" method="post" class="mt-5">
            <div class="form-group">
              <label for="current_password">Current Password</label>
              <input type="password" class="form-control <?php echo !empty($error_messages['current_password']) ? 'is-invalid' : ''; ?>" id="current_password" name="current_password" required>
              <?php if (!empty($error_messages['current_password'])): ?>
                <div class="invalid-feedback">
                  <?php echo $error_messages['current_password']; ?>
                </div>
              <?php endif; ?>
            </div>
            <div class="form-group">
              <label for="new_password">New Password</label>
              <input type="password" class="form-control <?php echo !empty($error_messages['new_password']) ? 'is-invalid' : ''; ?>" id="new_password" name="new_password" required>
              <?php if (!empty($error_messages['new_password'])): ?>
                <div class="invalid-feedback">
                  <?php echo $error_messages['new_password']; ?>
                </div>
              <?php endif; ?>
            </div>
            <div class="form-group">
              <label for="confirm_password">Confirm New Password</label>
              <input type="password" class="form-control <?php echo !empty($error_messages['confirm_password']) ? 'is-invalid' : ''; ?>" id="confirm_password" name="confirm_password" required>
              <?php if (!empty($error_messages['confirm_password'])): ?>
                <div class="invalid-feedback">
                  <?php echo $error_messages['confirm_password']; ?>
                </div>
              <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary btn-block" name="update_password">Update Password</button>
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