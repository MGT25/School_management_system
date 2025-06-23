<?php

session_start();
if(!isset($_SESSION['role'])){
    header("location: login.php ");
}elseif($_SESSION['role']=="student"){
    header("location: index.php");
}

?>

</html><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="dashboard.css">
  <title>Dashboard UI</title>
<style>
 
</style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h2>COMPANY LOGO</h2>
    <ul class="nav">
      <li><a href="#">🏠 Dashboard</a></li>
      <li><a href="#">📊 Chart</a></li>
      <li><a href="#">📝 Form</a></li>
      <li><a href="#">✉️ Email</a></li>
      <li><a href="#">👤 Profile</a></li>
      <li><a href="#">⚙️ Settings</a></li>
      <li><a href="logout.php">⚙️ Logout</a></li>
    </ul>
    <div class="signout">
        <a href="logout.php">SIGN OUT</a>
    </div>
  </div>

  <!-- Main content -->
  <div class="main">
    <div class="topbar">
      <h1>Dashboard</h1>
      <input type="text" placeholder="Search...">
    </div>

    <div class="dashboard-grid">
      <div class="card">
        <h3>Charts</h3>
        <div class="placeholder"></div>
      </div>
      <div class="card">
        <h3>Statistics</h3>
        <div class="placeholder"></div>
      </div>
      <div class="card">
        <h3>Progress</h3>
        <div class="placeholder"></div>
      </div>
      <div class="card">
        <h3>Users</h3>
        <div class="placeholder"></div>
      </div>
      <div class="card">
        <h3>Activity</h3>
        <div class="placeholder"></div>
      </div>
      <div class="card">
        <h3>Performance</h3>
        <div class="placeholder"></div>
      </div>
      
    </div>
  </div>

</body>
</html>

