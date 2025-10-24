<?php
// auth/check_admin.php
session_start();

// If user is not logged in or not an admin, redirect to login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
  header("Location: ../auth/login.php");
  exit;
}
