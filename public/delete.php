<?php
require_once __DIR__ . '/../includes/functions.php';

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    $conn = getConnection();
    $sql = "DELETE FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql);
    
    $id = sanitize_input($_GET["id"]);
    $stmt->bindParam(":id", $id);
    
    try {
        $stmt->execute();
        header("location: index.php");
        exit();
    } catch(PDOException $e) {
        die("Error deleting record.");
    }
}
?>