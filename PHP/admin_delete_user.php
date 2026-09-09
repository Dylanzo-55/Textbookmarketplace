<?php
    session_start();
    if (!($_SESSION['is_admin'] ?? false)) exit('Not authorized');
    $username = $_POST['username'] ?? '';
    if ($username) {
        $conn = new mysqli('localhost', 'root', '', 'textbookmarketplacedb');
        $conn->query("DELETE FROM login WHERE username='$username'");
        $conn->query("DELETE FROM textbooks WHERE username='$username'");
        $conn->close();
    }
?>