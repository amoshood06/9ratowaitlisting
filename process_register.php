<?php
require './db/db.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $referrer_code = isset($_POST['referrer']) ? trim($_POST['referrer']) : null;

    // Check if user already exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["status" => "error", "message" => "Email already registered."]);
        exit();
    }

    // Generate unique referral code
    $referral_code = substr(md5(uniqid($email, true)), 0, 8);

    // Get referrer email from referral code
    $referrer_email = null;
    if ($referrer_code) {
        $referrerStmt = $pdo->prepare("SELECT email FROM users WHERE referral_code = ?");
        $referrerStmt->execute([$referrer_code]);
        $referrer = $referrerStmt->fetch(PDO::FETCH_ASSOC);

        if ($referrer) {
            $referrer_email = $referrer['email'];
        }
    }

    // Insert user into database
    $stmt = $pdo->prepare("INSERT INTO users (email, referral_code, referred_by) VALUES (?, ?, ?)");
    if ($stmt->execute([$email, $referral_code, $referrer_email])) {
        // If referred, update referral system
        if ($referrer_email) {
            // Insert into referrals table
            $stmt = $pdo->prepare("INSERT INTO referrals (referrer_email, referred_email, amount_credited) VALUES (?, ?, ?)");
            $stmt->execute([$referrer_email, $email, 1000]);

            // Credit ₦1000 to referrer
            $stmt = $pdo->prepare("UPDATE users SET wallet_balance = wallet_balance + 1000 WHERE email = ?");
            $stmt->execute([$referrer_email]);
        }

        echo json_encode(["status" => "success", "message" => "Registration successful! Redirecting to login..."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error registering user."]);
    }
}
?>

