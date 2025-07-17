<?php
include '../includes/config.php';

$username = 'admin';
$password = 'admin123';
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

try {
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'Admin')");
    $stmt->execute([$username, $hashed_password]);
    echo "Admin account created successfully!<br>";
    echo "Username: admin<br>Password: admin123";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>