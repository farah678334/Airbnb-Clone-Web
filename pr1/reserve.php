<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['user_id'])) {
    echo "login_required";
    exit();
}

$user_id   = $_SESSION['user_id'];
$apartment = $_POST['apartment'];
$check_in  = $_POST['check_in'];
$check_out = $_POST['check_out'];
$guests    = $_POST['guests'];

try {
    // 1. Check if apartment is already reserved in the requested date range
    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM reservations 
        WHERE apartment = :apartment
          AND (check_in < :check_out AND check_out > :check_in)
    ");
    $stmt->execute([
        'apartment' => $apartment,
        'check_in'  => $check_in,
        'check_out' => $check_out
    ]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        echo "error";
    } else {
        // ✅ Step 2: Define nightly prices
        $apartmentPrices = [
            "Achrafieh Rooftop 1-BR W Jacuzzi" => 245.00,
            "Entire rental unit in Jumayza, Lebanon" => 107.00,
            "Chalet with a Sea View in Batroun 24/7 Electricity" => 99.5,
			"Blue Bird in Batroun Old Souks" => 170.00,
			"100 m2 apartment, spacious and typical Parisian" => 230.00,
			"Comfort a stone's throw from Montmartre" => 153.00,
			"Palm Island: Elegant Oasis 1 Min from the Beach" => 163.00,
			"Nice loft in the Jean Médecin neighborhood" => 97.6,
			"Charming flat with stunning view" => 100.25,
            "07 Superb Terrace, Heart of Cihangir, fibernet" => 154.8,
            "1-Bedroom Duplex Loft Apartment by Cleopatra Beach" => 104.5,
			"airloft room with terrace" => 50.00,
			"Dolce Vita Luxury apartment - Zeno 55" => 265.5,
			"St. Peter Cozy & luminous apartment" => 126.00,
			"Penthouse l' Ambrogina" => 256.00,
			"Panoramic view of Milan's canals" => 121.6
        ];
        $price_per_night = $apartmentPrices[$apartment] ?? 0;

        // ✅ Step 3: Calculate nights and total price
        $checkIn  = new DateTime($check_in);
        $checkOut = new DateTime($check_out);
        $interval = $checkIn->diff($checkOut);
        $nights   = $interval->days;
        $total_price = $nights * $price_per_night;

        // ✅ Step 4: Insert reservation with price info
        $insert = $pdo->prepare("
            INSERT INTO reservations (user_id, apartment, check_in, check_out, guests, price_per_night, total_price) 
            VALUES (:user_id, :apartment, :check_in, :check_out, :guests, :price_per_night, :total_price)
        ");
        $insert->execute([
            'user_id'        => $user_id,
            'apartment'      => $apartment,
            'check_in'       => $check_in,
            'check_out'      => $check_out,
            'guests'         => $guests,
            'price_per_night'=> $price_per_night,
            'total_price'    => $total_price
        ]);
        echo "success";
    }
} catch (PDOException $e) {
    echo "db_error";
}
?>
