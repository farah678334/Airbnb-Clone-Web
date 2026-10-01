<?php
session_start();
require 'connection.php';

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
$stmt->execute(['email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && $password === $user['password']) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $user['name'];
    echo "success"; // ✅ JavaScript will catch this and redirect
} else {
    echo "error"; // ✅ stays inside modal
}
?>