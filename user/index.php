<?php
require '../db/db.php';
session_start();

if (!isset($_SESSION['user_email'])) {
    header("Location: ../login.php");
    exit();
}

$user_email = $_SESSION['user_email'];

// Fetch user's referral code & balance
$stmt = $pdo->prepare("SELECT referral_code, wallet_balance FROM users WHERE email = ?");
$stmt->execute([$user_email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$referral_code = $user['referral_code'];
$wallet_balance = $user['wallet_balance'];

// Get Referral Stats
$stmt = $pdo->prepare("SELECT COUNT(*) AS total_referrals, SUM(amount_credited) AS total_earned FROM referrals WHERE referrer_email = ?");
$stmt->execute([$user_email]);
$referral_data = $stmt->fetch(PDO::FETCH_ASSOC);
$total_referrals = $referral_data['total_referrals'];
$total_earned = $referral_data['total_earned'] ?? 0; // If null, set to 0

// Pagination setup
$limit = 5; // Referrals per page
$page = isset($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1; // Ensure page is at least 1
$offset = max(($page - 1) * $limit, 0); // Ensure offset is never negative

// Get Referred Users List with Pagination
$stmt = $pdo->prepare("SELECT referred_email, amount_credited, created_at FROM referrals WHERE referrer_email = ? LIMIT ? OFFSET ?");
$stmt->bindValue(1, $user_email, PDO::PARAM_STR);
$stmt->bindValue(2, (int)$limit, PDO::PARAM_INT);
$stmt->bindValue(3, (int)$offset, PDO::PARAM_INT);
$stmt->execute();
$referred_users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Total referrals count for pagination
$stmt = $pdo->prepare("SELECT COUNT(*) FROM referrals WHERE referrer_email = ?");
$stmt->execute([$user_email]);
$total_records = $stmt->fetchColumn();
$total_pages = ceil($total_records / $limit);

// Generate referral link
$referral_link = "https://9rato.com/index.php?ref=" . $referral_code;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>9rato User Dashboard</title>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2573977636104945"
     crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../asset/toast/toastr.min.css">
    <link rel="shortcut icon" href="../asset/image/9ratoLogo.png" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black min-h-screen flex flex-col">
    <!-- Navigation -->
    <nav class="bg-[#0B4D3A] p-4">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center">
                <img src="../asset/image/9ratoLogo.png" alt="9rato Logo" class="w-8 h-8 mr-2">
            </div>
            <a href="logout.php">
            <button class="text-white hover:text-yellow-400">Logout</button>
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow container mx-auto p-4 md:p-8">
        <div class="grid md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- User Info -->
                <div class="bg-[#0B4D3A] rounded-lg p-6 text-white">
                    <h2 class="text-2xl font-bold mb-4">Welcome, <?php echo "$user_email"; ?></h2>
                    <p class="text-yellow-400 text-lg">Your Balance: ₦<?php echo number_format($wallet_balance, 2); ?></p>
                    <a href="withdrawal.php">
                    <button class="mt-4 bg-yellow-400 text-[#0B4D3A] px-4 py-2 rounded-md hover:bg-yellow-500 transition duration-300">
                        Withdraw Funds
                    </button>
                    </a>

                    <!-- Referral Link -->
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold">Your Referral Link:</h3>
                        <input type="text" id="referralLink" class="w-full p-2 border rounded-lg text-[#0B4D3A]" value="<?php echo $referral_link; ?>" readonly>
                        <button onclick="copyReferral()" class="mt-2 bg-yellow-400 text-[#0B4D3A] px-4 py-2 rounded-lg">Copy Referral Link</button>
                    </div>
                </div>

                <!-- Referral Stats -->
                <div class="bg-[#0B4D3A] rounded-lg p-6 text-white">
                    <h2 class="text-2xl font-bold mb-4">Your Referrals</h2>
                    <p class="text-4xl font-bold text-yellow-400"><?php echo "$total_referrals"; ?></p>
                    <p class="text-sm">Total Referrals</p>
                </div>
            </div>

            <!-- Right Column -->
            <div class="bg-[#0B4D3A] rounded-lg p-6 text-white">
            <h2 class="text-2xl font-bold mb-4">Referral List</h2>
            <div class="bg-[#0B4D3A] rounded-lg p-6 text-white">
            <?php if (count($referred_users) > 0): ?>
                <?php foreach ($referred_users as $ref): ?>
                    <div class="flex justify-between items-center border-b border-gray-600 pb-2">
                        <span><?php echo htmlspecialchars($ref['referred_email']); ?></span>
                        <span class="text-yellow-400">₦<?php echo number_format($ref['amount_credited'], 2); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">No referrals yet 😔</p>
            <?php endif; ?>
        </div>
        
        <!-- Pagination Buttons -->
        <div class="flex justify-between mt-6">
            <a href="?page=<?php echo max(1, $page - 1); ?>" 
               class="bg-yellow-400 text-[#0B4D3A] px-4 py-2 rounded-md hover:bg-yellow-500 transition duration-300 <?php echo ($page <= 1) ? 'opacity-50 cursor-not-allowed' : ''; ?>" 
               <?php echo ($page <= 1) ? 'disabled' : ''; ?>>
                Previous
            </a>
            
            <a href="?page=<?php echo min($total_pages, $page + 1); ?>" 
               class="bg-yellow-400 text-[#0B4D3A] px-4 py-2 rounded-md hover:bg-yellow-500 transition duration-300 <?php echo ($page >= $total_pages) ? 'opacity-50 cursor-not-allowed' : ''; ?>" 
               <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>>
                Next
            </a>
        </div>
    </div>
               
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-[#0B4D3A] text-white p-4 mt-8">
        <div class="container mx-auto text-center">
            <p>&copy; 2025 9rato. All rights reserved.</p>
        </div>
    </footer>
    <script src="../asset/toast/jquery-3.7.1.min.js"></script>
    <script src="../asset/toast/toastr.min.js"></script>
    <script>
        function copyReferral() {
            var copyText = document.getElementById("referralLink");
            copyText.select();
            document.execCommand("copy");
            
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "3000"
            };
            toastr["success"]("Referral link copied!", "Success");
        }
    </script>
</body>
</html>