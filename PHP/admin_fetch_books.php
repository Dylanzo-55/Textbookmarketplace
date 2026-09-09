<?php
    session_start();
    if (!($_SESSION['is_admin'] ?? false)) exit('Not authorized');
    $conn = new mysqli('localhost', 'root', '', 'textbookmarketplacedb');
    $res = $conn->query("SELECT * FROM textbooks");
    echo "<table class='table table-striped align-middle'>";
    echo "<thead><tr>
            <th>Title</th>
            <th>Author</th>
            <th>ISBN</th>
            <th>Seller</th>
            <th>Actions</th>
        </tr></thead><tbody>";
    while ($row = $res->fetch_assoc()) {
        echo "<tr>
            <td>{$row['Title']}</td>
            <td>{$row['Author']}</td>
            <td>{$row['ISBN']}</td>
            <td>{$row['username']}</td>
            <td>
                <button class='btn btn-danger btn-sm mb-1' onclick=\"deleteBook('{$row['ISBN']}')\">Delete</button>
            </td>
        </tr>";
    }
    echo "</tbody></table>";
    $conn->close();
?>