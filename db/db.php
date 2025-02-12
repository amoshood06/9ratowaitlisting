<?php
$host = "";
$dbname = "hqyubnjp_9ratowaitlising";
$username = "hqyubnjp_9ratowaitlising";
$password = "9ratowaitlising";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
