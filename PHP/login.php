<?php

    session_start(); // <-- Add this at the top

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        // retrieve form data
        $username = $_POST['username'];
        $password = $_POST['password'];

        // database connection
        $host = 'localhost';
        $dbusername = 'root';
        $dbpassword = '';
        $dbname = 'textbookmarketplacedb';

        $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

        // check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // validate login authentication
        $query = "SELECT * FROM login WHERE username='$username' AND password='$password'";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $_SESSION['username'] = $username;
            $_SESSION['is_admin'] = $user['is_admin'] ?? 0;
            if ($_SESSION['is_admin']) {
                header("Location: ../HTML/admin.html");
            } else {
                header("Location: ../HTML/home.html");
            }
            exit();
        } else {
            header("Location: ../index.html?error=Invalid username or password");
            exit();
        }

        // close connection
        $conn->close();
    }

?>