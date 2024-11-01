<?php
require_once __DIR__ . '/../includes/functions.php';
init_db();

$search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';
$conn = getConnection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>User Management System</h2>
        <div class="row mb-3">
            <div class="col-md-6">
                <a href="create.php" class="btn btn-success">Add New User</a>
            </div>
            <div class="col-md-6">
                <form class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Search users..." value="<?= $search ?>">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
        </div>
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM users";
                if(!empty($search)) {
                    $sql .= " WHERE name LIKE :search OR email LIKE :search";
                }
                $sql .= " ORDER BY id DESC";
                
                $stmt = $conn->prepare($sql);
                if(!empty($search)) {
                    $searchTerm = "%{$search}%";
                    $stmt->bindParam(':search', $searchTerm);
                }
                $stmt->execute();
                
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                    echo "<td>" . $row['created_at'] . "</td>";
                    echo "<td>";
                    echo "<a href='update.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm'>Edit</a> ";
                    echo "<a href='delete.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
