<?php
require_once __DIR__ . '/../includes/functions.php';

if($_SERVER["REQUEST_METHOD"] == "POST") {
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
        $conn = getConnection();
        $sql = "INSERT INTO users (name, email, phone) VALUES (:name, :email, :phone)";
        $stmt = $conn->prepare($sql);
        
        try {
            $stmt->execute([':name' => $name, ':email' => $email, ':phone' => $phone]);
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
    <title>Create User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Create User</h2>
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
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="<?= isset($name) ? $name : '' ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= isset($email) ? $email : '' ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="<?= isset($phone) ? $phone : '' ?>">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>