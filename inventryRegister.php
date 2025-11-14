<?php
include "inventryConfig.php";

$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
$stmt->execute([':username' => $username, ':password' => $password]);

echo "✅ Registration successful. <a href='inventryLogin.php'>Login</a>";
?>
