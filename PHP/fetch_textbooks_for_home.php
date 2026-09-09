<?php

session_start();
$loggedInUser = isset($_SESSION['username']) ? $_SESSION['username'] : null;

if (!$loggedInUser) {
    echo '<div class="buy-product-card"><p>Please log in to view textbooks.</p></div>';
    exit();
}

$host = 'localhost';
$dbusername = 'root';
$dbpassword = '';
$dbname = 'textbookmarketplacedb';
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    echo '<div class="buy-product-card"><p>Database connection failed.</p></div>';
    exit();
}

// Get the user's university
$stmt = $conn->prepare("SELECT university FROM login WHERE username=?");
$stmt->bind_param("s", $loggedInUser);
$stmt->execute();
$stmt->bind_result($userUniversity);
$stmt->fetch();
$stmt->close();

if (!$userUniversity) {
    echo '<div class="buy-product-card"><p>Could not determine your university.</p></div>';
    $conn->close();
    exit();
}

// Fetch textbooks from the same university
$sql = "SELECT t.*, l.university FROM textbooks t 
        JOIN login l ON t.username = l.username 
        WHERE l.university = ?
        ORDER BY t.ISBN DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userUniversity);
$stmt->execute();
$result = $stmt->get_result();

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
            echo '</div>';
        echo '</div>'; 
    }
} else {
    echo '<div class="buy-product-card"><p>No textbooks found for your university.</p></div>';
}
$stmt->close();
$conn->close();
?>