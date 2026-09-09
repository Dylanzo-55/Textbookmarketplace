<?php

session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isbn = $_POST['isbn'] ?? '';
    $username = $_SESSION['username'] ?? '';

    if ($isbn && $username) {
        $host = 'localhost';
        $dbusername = 'root';
        $dbpassword = '';
        $dbname = 'textbookmarketplacedb';
        $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

        if ($conn->connect_error) {
            http_response_code(500);
            echo "Database connection failed.";
            exit();
        }

        $stmt = $conn->prepare("DELETE FROM textbooks WHERE ISBN=? AND username=?");
        $stmt->bind_param("ss", $isbn, $username);
        if ($stmt->execute()) {
            echo "success";
        } else {
            http_response_code(500);
            echo "Delete failed.";
        }
        $stmt->close();
        $conn->close();
    } else {
        http_response_code(400);
        echo "Invalid request.";
    }
} else {
    http_response_code(405);
    echo "Method not allowed.";
}

?>