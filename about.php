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
          <p class="card-text">This application is a To-Do List that helps you manage and save your tasks efficiently. You can add, update, and delete tasks as needed, ensuring that you stay organized and on top of your responsibilities.</p>
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

