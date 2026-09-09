<?php
    session_start();
    $loggedInUser = isset($_SESSION['username']) ? $_SESSION['username'] : null;
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
    $host = 'localhost';
    $dbusername = 'root';
    $dbpassword = '';
    $dbname = 'textbookmarketplacedb';
    $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

    if ($conn->connect_error) {
        echo '<div class="buy-product-card"><p>Database connection failed.</p></div>';
        exit();
    }

    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    if ($search !== '') {
        // Search by Title or ISBN (case-insensitive)
        $search = $conn->real_escape_string($search);
        $sql = "SELECT t.*, l.university FROM textbooks t 
                JOIN login l ON t.username = l.username
                WHERE t.Title LIKE '%$search%' OR t.ISBN LIKE '%$search%'
                ORDER BY t.ISBN DESC";
    } else {
        $sql = "SELECT t.*, l.university FROM textbooks t 
                JOIN login l ON t.username = l.username
                ORDER BY t.ISBN DESC";
    }
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $imgSrc = !empty($row['imgPath']) ? '../' . $row['imgPath'] : '../Pictures_for_website/book-cover-placeholder.png';
            echo '<div class="buy-product-card">';
                echo '<img src="' . htmlspecialchars($imgSrc) . '" alt="Product" class="buy-product-image">';
                echo '<div class="buy-product-info">';
                    echo '<h3>' . htmlspecialchars($row['Title']) . '</h3>';
                    echo '<p>Seller: ' . htmlspecialchars($row['username']) . '</p>';
                    echo '<p>University: ' . htmlspecialchars($row['university']) . '</p>';
                    echo '<p>Author: ' . htmlspecialchars($row['Author']) . '</p>';
                    echo '<p>ISBN: ' . htmlspecialchars($row['ISBN']) . '</p>';
                    echo '<p>Edition: ' . htmlspecialchars($row['Edition']) . '</p>';
                    echo '<p>Price: R' . htmlspecialchars($row['Price']) . '</p>';
                    echo '<p>Condition: ' . htmlspecialchars($row['ConditionsofBook']) . '</p>';
                    if ($loggedInUser && $row['username'] !== $loggedInUser) {
                        echo '<form action="buy_request.php" method="POST" style="margin-top:10px;">';
                        echo '<input type="hidden" name="seller_username" value="' . htmlspecialchars($row['username']) . '">';
                        echo '<input type="hidden" name="title" value="' . htmlspecialchars($row['Title']) . '">';
                        echo '<input type="hidden" name="price" value="' . htmlspecialchars($row['Price']) . '">';
                        echo '<input type="hidden" name="buyer_username" value="' . htmlspecialchars($loggedInUser) . '">';
                        echo '<button type="submit" class="buy-btn">Buy</button>';
                        echo '</form>';
                    }
                echo '</div>'; // .sell-product-info
            echo '</div>'; // .sell-product-card
        }
    } else {
        echo '<div class="buy-product-card"><p>Inventory is empty</p></div>';
    }
    $conn->close();
?>