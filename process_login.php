<?php
require './db/db.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    // Check if user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['referral_code'] = $user['referral_code'];

        echo json_encode(["status" => "success", "message" => "Welcome back, " . $user['email'] . "! Redirecting..."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid email address!"]);
    }
}
?>
