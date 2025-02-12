<?php
require '../db/db.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_email'])) {
        echo json_encode(["status" => "error", "message" => "You must be logged in to withdraw."]);
        exit();
    }

    $email = $_SESSION['user_email'];
    $amount = floatval($_POST['amount']);
    $bank_name = trim($_POST['bank_name']);
    $account_number = trim($_POST['account_number']);

    // Fetch user's current balance
    $stmt = $pdo->prepare("SELECT wallet_balance FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        echo json_encode(["status" => "error", "message" => "User not found."]);
        exit();
    }

    $balance = $user['wallet_balance'];

    // Validate withdrawal amount
    if ($amount <= 0) {
        echo json_encode(["status" => "error", "message" => "Invalid withdrawal amount."]);
        exit();
    }

    if ($amount > $balance) {
        echo json_encode(["status" => "error", "message" => "Insufficient balance."]);
        exit();
    }

    try {
        $pdo->beginTransaction();

        // Deduct the amount from user's balance
        $stmt = $pdo->prepare("UPDATE users SET wallet_balance = wallet_balance - ? WHERE email = ?");
        $stmt->execute([$amount, $email]);

        // Insert into withdrawals table (Pending status)
        $stmt = $pdo->prepare("INSERT INTO withdrawals (email, amount, bank_name, account_number, status) VALUES (?, ?, ?, ?, 'Pending')");
        $stmt->execute([$email, $amount, $bank_name, $account_number]);

        // Insert into transactions table
        $stmt = $pdo->prepare("INSERT INTO transactions (email, type, amount, description, status) VALUES (?, 'Withdrawal', ?, ?, 'Pending')");
        $stmt->execute([$email, $amount, 'Withdrawal request submitted']);

        $pdo->commit();

        echo json_encode(["status" => "success", "message" => "Withdrawal request submitted successfully."]);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(["status" => "error", "message" => "Transaction failed. Try again."]);
    }
}
?>
