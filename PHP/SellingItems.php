<?php
    session_start(); // Start session at the top

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $Title = $_POST['Title'];
        $Author = $_POST['Author'];
        $ISBN = $_POST['ISBN'];
        $Edition = $_POST['Edition'];
        $Price = $_POST['Price'];
        $Condition = $_POST['Condition'];

        // Get username from session
        $username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
        if (!$username) {
            header("Location: ../index.html?error=Please login to sell your book");
            exit();
        }

        // handle file upload
        $imagePath = "";
        $imageName = "";
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $targetDir = "Uploaded_Images/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $imageName = basename($_FILES["image"]["name"]);
            $targetFile = $targetDir . uniqid() . "_" . $imageName;
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                $imagePath = $targetFile;
            }
        }

        // database connection
        $host = 'localhost';
        $dbusername = 'root';
        $dbpassword = '';
        $dbname = 'textbookmarketplacedb';
        $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // check if ISBN already exists for this user
        $query = "SELECT * FROM textbooks WHERE ISBN='$ISBN' AND username='$username'";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            header("Location: ../HTML/sell.html?error=ISBN already exists"); // <-- Fix path
            exit();
        } else {
            // insert new book into database
            $query = "INSERT INTO textbooks (Title, Author, ISBN, Edition, Price, ConditionsofBook, username, imgName, imgPath) VALUES ('$Title', '$Author', '$ISBN', '$Edition', '$Price', '$Condition', '$username', '$imageName', '$imagePath')";
            if ($conn->query($query) === TRUE) {
                echo "<script>alert('Book added successfully!');</script>";
                header("Location: ../HTML/home.html"); // <-- Fix path
                exit();   
            } else {
                header("Location: ../HTML/sell.html?error=Insertion failed"); // <-- Fix path
                exit();
            }
        }
    }
?>