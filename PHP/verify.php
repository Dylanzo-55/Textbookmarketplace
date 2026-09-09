<?php
    session_start();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $input_code = $_POST['code'];
        $pending = $_SESSION['pending_user'] ?? null;

        if ($pending && $input_code == $pending['code']) {
            // Insert user into database
            $host = 'localhost';
            $dbusername = 'root';
            $dbpassword = '';
            $dbname = 'textbookmarketplacedb';
            $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $username = $pending['username'];
            $email = $pending['email'];
            $university = $pending['university'];
            $password = $pending['password'];

            $query = "INSERT INTO login (username, email, university, password) VALUES ('$username', '$email', '$university', '$password')";
            if ($conn->query($query) === TRUE) {
                $_SESSION['username'] = $username;
                unset($_SESSION['pending_user']);
                header("Location: ../HTML/home.html"); // <-- Fix path
                exit();
            } else {
                header("Location: ../index.html?error=Registration failed"); // <-- Fix path
                exit();
            }
        } else {
            header("Location: ../HTML/verify.html?error=Invalid code"); // <-- Fix path
            exit();
        }
    }
?>