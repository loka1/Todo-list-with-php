<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <!-- Set the character encoding for the document -->
    <meta charset="utf-8">
    
    <!-- Set the title of the webpage -->
    <title>Todo list</title>

    <!-- Link to Bootstrap CSS for styling and responsive design -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <!-- Internal CSS for styling the body -->
    <style>
        /* Ensure the footer is always at the bottom */
    html, body {
      height: 100%;
    }
    body{
      /* Remove padding from the body */
      padding:0;
      display: flex;
      flex-direction: column;
      
      /* Remove margin from the body */
      margin:0;
      
      /* Set the background image for the body */
      background:url('background.jpg');
      
      /* Ensure the background image covers the entire body */
      background-size:cover;
      
      /* Prevent the background image from repeating */
      background-repeat:no-repeat;
    }

    .content {
      flex: 1;
    }
    </style>

  </head>
  <body></body>


  
<nav class="navbar navbar-expand-lg navbar-light bg-light ">
<div class="container-fluid">
<a class="navbar-brand" href="/">Todo List</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="index.php">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="add.php">Add Task</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="about.php">About</a>
      </li>
    </ul>
  </div>
  </div>
</nav>
<div class="container-fluid mb-5">
<!-- Show delete alert message if a task was successfully deleted -->
<?php 
if(isset($_SESSION['delete_success'])) { // Check if the 'delete_success' session variable is set
?>
  <div class="alert alert-warning text-dark mx-auto mt-4" role="alert" style="width:66%;">
    <?=$_SESSION['delete_success'];?> <!-- Display the delete success message -->
  </div>
<?php
  unset($_SESSION['delete_success']); // Unset the 'delete_success' session variable after displaying the message
}
?>

<!-- Show update alert message if a task was successfully updated -->
<?php 
if(isset($_SESSION['upadate_success'])) { // Check if the 'upadate_success' session variable is set
?>
  <div class="alert alert-warning text-dark mx-auto mt-4" role="alert" style="width:66%;">
    <?=$_SESSION['upadate_success'];?> <!-- Display the update success message -->
  </div>
<?php
  unset($_SESSION['upadate_success']); // Unset the 'upadate_success' session variable after displaying the message
}
?>

<!-- Show add alert message if a task was successfully added -->
<?php 
if(isset($_SESSION['add_success'])) { // Check if the 'add_success' session variable is set
?>
  <div class="alert alert-success text-dark mx-auto mt-4" role="alert" style="width:66%;">
    <?=$_SESSION['add_success'];?> <!-- Display the add success message -->
  </div>
<?php
  unset($_SESSION['add_success']); // Unset the 'add_success' session variable after displaying the message
}
?>

<!-- Show add alert message if there was a failure in adding a task -->
<?php 
if(isset($_SESSION['add_failure'])) { // Check if the 'add_failure' session variable is set
?>
  <div class="alert alert-danger text-dark mx-auto mt-4" role="alert" style="width:66%;">
    <?=$_SESSION['add_failure'];?> <!-- Display the add failure message -->
  </div>
<?php
  unset($_SESSION['add_failure']); // Unset the 'add_failure' session variable after displaying the message
}
?>
</div>

<div class="content">
    