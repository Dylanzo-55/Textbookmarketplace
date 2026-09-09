<?php
    session_start();
    if (!($_SESSION['is_admin'] ?? false)) exit('Not authorized');
    $isbn = $_POST['isbn'] ?? '';
    if ($isbn) {
        $conn = new mysqli('localhost', 'root', '', 'textbookmarketplacedb');
        $conn->query("DELETE FROM textbooks WHERE ISBN='$isbn'");
        $conn->close();
    }
?>