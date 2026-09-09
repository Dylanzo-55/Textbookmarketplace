<?php
    session_start();
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
    $host = 'localhost';
    $dbusername = 'root';
    $dbpassword = '';
    $dbname = 'textbookmarketplacedb';
    $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

    if ($conn->connect_error) {
        echo '<div class="sell-product-card"><p>Database connection failed.</p></div>';
        exit();
    }

    $sql = "SELECT * FROM textbooks WHERE username='$username' ORDER BY ISBN DESC";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $imgSrc = !empty($row['imgPath']) ? '../' . $row['imgPath'] : '../Pictures_for_website/book-cover-placeholder.png';
            echo '<div class="sell-product-card">';
                echo '<img src="' . htmlspecialchars($imgSrc) . '" alt="Product" class="sell-product-image">';
                echo '<div class="sell-product-info">';
                    echo '<h3>' . htmlspecialchars($row['Title']) . '</h3>';
                    echo '<p>Author: ' . htmlspecialchars($row['Author']) . '</p>';
                    echo '<p>ISBN: ' . htmlspecialchars($row['ISBN']) . '</p>';
                    echo '<p>Edition: ' . htmlspecialchars($row['Edition']) . '</p>';
                    echo '<p>Price: R' . htmlspecialchars($row['Price']) . '</p>';
                    echo '<p>Condition: ' . htmlspecialchars($row['ConditionsofBook']) . '</p>';
                    echo '<button class= "delete-btn" data-isbn="'.htmlspecialchars($row['ISBN']).'">Delete</button>';
                echo '</div>'; // .sell-product-info
            echo '</div>'; // .sell-product-card
        }
    } else {
        echo '<div class="sell-product-card"><p>Inventory is empty</p></div>';
    }
    $conn->close();
?>