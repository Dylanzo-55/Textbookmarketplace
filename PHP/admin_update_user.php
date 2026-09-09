<?php
    session_start();
    if (!($_SESSION['is_admin'] ?? false)) exit('Not authorized');
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    if ($username && $password) {
        $conn = new mysqli('localhost', 'root', '', 'textbookmarketplacedb');
        $conn->query("UPDATE login SET password='$password' WHERE username='$username'");
        $conn->close();
    }
?>