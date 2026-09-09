<?php
    session_start();
    if (!($_SESSION['is_admin'] ?? false)) exit('Not authorized');
    $username = $_POST['username'] ?? '';
    if ($username) {
        $conn = new mysqli('localhost', 'root', '', 'textbookmarketplacedb');
        $conn->query("UPDATE login SET is_admin=1 WHERE username='$username'");
        $conn->close();
    }
?>