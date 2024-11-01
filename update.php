<?php
include 'db.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM items WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("UPDATE items SET name = ?, description = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name, $description, $id);
    $stmt->execute();

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Item</title>
</head>
<body>
    <h1>Edit Item</h1>
    <form method="post" action="update.php?id=<?= $id ?>">
        <label>Name:</label>
        <input type="text" name="name" value="<?= $item['name'] ?>" required>
        <br>
        <label>Description:</label>
        <textarea name="description"><?= $item['description'] ?></textarea>
        <br>
        <button type="submit">Update</button>
    </form>
    <a href="index.php">Back to List</a>
</body>
</html>
