<?php
    session_start();
    if (!($_SESSION['is_admin'] ?? false)) exit('Not authorized');
    $conn = new mysqli('localhost', 'root', '', 'textbookmarketplacedb');
    $res = $conn->query("SELECT username, email, university, is_admin FROM login");
    echo "<table class='table table-striped align-middle'>";
    echo "<thead><tr>
            <th>Username</th>
            <th>Email</th>
            <th>University</th>
            <th>Admin</th>
            <th>Actions</th>
        </tr></thead><tbody>";
    while ($row = $res->fetch_assoc()) {
        echo "<tr>
            <td>{$row['username']}</td>
            <td>{$row['email']}</td>
            <td>{$row['university']}</td>
            <td>{$row['is_admin']}</td>
            <td>
                <button class='btn btn-danger btn-sm mb-1 me-1' onclick=\"deleteUser('{$row['username']}')\">Delete</button>
                <button class='btn btn-primary btn-sm mb-1 me-1' onclick=\"updateUser('{$row['username']}')\">Update Password</button>";
        if (!$row['is_admin']) {
            echo "<button class='btn btn-success btn-sm mb-1' onclick=\"makeAdmin('{$row['username']}')\">Make Admin</button>";
        }
        echo "</td>
        </tr>";
    }
    echo "</tbody></table>";
    $conn->close();
?>