<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$reservation_id = $_POST['reservation_id'];

$stmt = $pdo->prepare("DELETE FROM reservations WHERE id = :id AND user_id = :user_id");
$stmt->execute([
    'id' => $reservation_id,
    'user_id' => $user_id
]);

header("Location: my_reservations.php");
exit();
?>
