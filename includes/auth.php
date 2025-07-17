<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'config.php';

// Redirect if not logged in
// if (!isset($_SESSION['user_id'])) {
//     header("Location: ../index.php");
//     exit();
// }

// Check user role if needed
//function checkAdmin() {
    // if ($_SESSION['role'] != 'Admin') {
    //     header("Location: ../index.php");
    //     exit();
    // }
//}
?>