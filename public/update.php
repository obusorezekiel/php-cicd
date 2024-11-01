<?php
require_once __DIR__ . '/../includes/functions.php';
$conn = getConnection();

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    $id = sanitize_input($_GET["id"]);
    
    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    
    if($stmt->rowCount() == 1) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $name = $row["name"];
        $email = $row["email"];
        $phone = $row["phone"];
    } else {
        header("location: index.php");
        exit();
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = sanitize_input($_POST["id"]);
    $name = sanitize_input($_POST["name"]);
    $email = sanitize_input($_POST["email"]);
    $phone = sanitize_input($_POST["phone"]);
    
    $errors = [];
    if(empty($name)) {
        $errors[] = "Name is required.";
    }
    if(empty($email)) {
        $errors[] = "Email is required.";
    } elseif(!validate_email($email)) {
        $errors[] = "Invalid email format.";
    }
    
    if(empty($errors)) {
        $sql = "UPDATE users SET name = :name, email = :email, phone = :phone WHERE id = :id";
        $stmt = $conn->prepare($sql);
        
        try {
            $stmt->execute([':id' => $id, ':name' => $name, ':email' => $email, ':phone' => $phone]);
            header("location: index.php");
            exit();
        } catch(PDOException $e) {
            $errors[] = "Something went wrong. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Update User</h2>
        <?php
        if(!empty($errors)) {
            echo '<div class="alert alert-danger">';
            foreach($errors as $error) {
                echo $error . "<br>";
            }
            echo '</div>';
        }
        ?>
        <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <input type="hidden" name="id" value="<?= $id ?>">
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="<?= $name ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= $email ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="<?= $phone ?>">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>