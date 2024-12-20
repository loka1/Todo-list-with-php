<?php 
/*
 * Include the header file
 * This includes the header file, which contains the HTML head and opening body tags
 */
require_once 'header.php';
?>
<!-- Begin Bootstrap container-fluidfor the about section -->
<div class="container-fluidmt-5">
  <!-- Begin Bootstrap row for the about section -->
  <div class='row justify-content-center'>
    <!-- Bootstrap column with full width on all screen sizes, centered text, and top margin -->
    <div class='col-12 col-md-8'>
      <!-- Bootstrap card for the about section -->
      <div class="card">
        <div class="card-header text-center">
          <h1>About Us</h1>
        </div>
        <div class="card-body">
            <!-- Paragraph describing the purpose and functionality of the application -->
            <p class="card-text">This application is a To-Do List that helps you manage and save your tasks efficiently. You can add, update, and delete tasks as needed, ensuring that you stay organized and on top of your responsibilities. Additionally, the application provides user authentication features, allowing you to register for an account and securely log in to access your personalized task list. Once logged in, you can edit your profile information to keep your account details up to date. The index page also includes filtering options, enabling you to easily sort and view tasks based on their status or priority, making task management even more convenient and effective.</p>
            <p class="card-text"><strong>Features:</strong></p>
          <ul>
            <li>User Authentication: Secure login and registration system to manage user access.</li>
            <li>Add Task: Users can add new tasks to their todo list with a task name, description, and status.</li>
            <li>Update Task: Users can update existing tasks to change the task name, description, and status.</li>
            <li>Delete Task: Users can delete tasks that are no longer needed.</li>
            <li>Task Status: Tasks can have different statuses such as Pending, In Progress, and Completed.</li>
            <li>Responsive Design: The application uses Bootstrap for a responsive and modern design.</li>
            <li>Pagination: Tasks are displayed with pagination to handle large lists efficiently.</li>
            <li>Task Count Widgets: Visual widgets to show the count of tasks by status (Pending, In Progress, Completed).</li>
            <li>Session Management: Secure session management to ensure user data is protected.</li>
          </ul>
          <!-- Paragraph introducing the designers of the website -->
          <p class="card-text">This website was designed by:</p>
          <!-- Table of the designers' names -->
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Designers</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Asmaa El-Naggar</td>
              </tr>
              <tr>
                <td>Loka Shafik</td>
              </tr>
              <tr>
                <td>Hesham Yehia</td>
              </tr>
            </tbody>
          </table>
          <!-- Paragraph mentioning the supervision of Dr. Reham -->
          <p class="card-text">Under the supervision of <strong style="color: blue;">Dr. Reham</strong>.</p>
          <!-- Paragraph giving special thanks to Dr. Reham -->
          <p class="card-text">Special thanks to <strong style="color: blue;">Dr. Reham</strong> for her guidance and support throughout the project.</p>
        </div>
      </div>
    </div>
  </div>
  <!-- End Bootstrap row for the about section -->
</div>
<!-- End Bootstrap container-fluidfor the about section -->

<?php 
/*
 * Include the footer file
 * This includes the footer file, which contains the js scripts and ending body tags
 */
require_once 'footer.php';
?>

