<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>9rato Withdrawal</title>
    <link rel="stylesheet" href="./asset/toast/toastr.min.css">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2573977636104945"
     crossorigin="anonymous"></script>
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
            <a href="index.php" class="text-white hover:text-yellow-400">Back to Dashboard</a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow container mx-auto p-4 md:p-8 flex items-center justify-center">
        <div class="bg-[#0B4D3A] rounded-lg p-8 max-w-md w-full">
            <h1 class="text-3xl font-bold text-white mb-6 text-center">Withdrawal</h1>
            
            <!-- Notification -->
            <div class="bg-yellow-400 text-[#0B4D3A] p-4 rounded-md mb-6">
                <p class="font-semibold">🚀 Coming Soon!</p>
                <p>Withdrawals will be available once the project launches. Stay tuned!</p>
            </div>

            <!-- Current Balance -->
            <div class="text-white mb-6">
                <p class="text-lg">Your Current Balance:</p>
                <p id="balance" class="text-3xl font-bold text-yellow-400">₦0</p>
            </div>

            <!-- Withdrawal Form (Disabled) -->
            <form class="space-y-4">
                <div>
                    <label for="amount" class="block text-sm font-medium text-white mb-1">Withdrawal Amount (₦)</label>
                    <input type="number" id="amount" name="amount" placeholder="Enter amount" 
                           class="w-full bg-white border-0 rounded-md p-2 text-[#0B4D3A]" disabled>
                </div>
                <div>
                    <label for="bank" class="block text-sm font-medium text-white mb-1">Bank Name</label>
                    <input type="text" id="bank" name="bank" placeholder="Enter your bank name" 
                           class="w-full bg-white border-0 rounded-md p-2 text-[#0B4D3A]" disabled>
                </div>
                <div>
                    <label for="account" class="block text-sm font-medium text-white mb-1">Account Number</label>
                    <input type="text" id="account" name="account" placeholder="Enter your account number" 
                           class="w-full bg-white border-0 rounded-md p-2 text-[#0B4D3A]" disabled>
                </div>
                <button type="submit" 
                        class="w-full bg-gray-400 text-[#0B4D3A] px-4 py-2 rounded-md font-semibold transition duration-300 cursor-not-allowed"
                        disabled>
                    Withdraw (Coming Soon)
                </button>
            </form>

            <!-- Additional Information -->
            <div class="mt-6 text-white text-sm">
                <p>Once withdrawals are available:</p>
                <ul class="list-disc list-inside mt-2">
                    <li>Minimum withdrawal amount: ₦1,000</li>
                    <li>Processing time: 1-3 business days</li>
                    <li>Withdrawal fees may apply</li>
                </ul>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-[#0B4D3A] text-white p-4 mt-8">
        <div class="container mx-auto text-center">
            <p>&copy; 2025 9rato. All rights reserved.</p>
        </div>
    </footer>

    <script src="./asset/toast/jquery-3.7.1.min.js"></script>
    <script src="./asset/toast/toastr.min.js"></script>
    <script>
        $(document).ready(function () {
            // Fetch balance dynamically
            $.ajax({
                url: "get_balance.php",
                type: "GET",
                success: function(response) {
                    $("#balance").text("₦" + response.balance);
                }
            });

            $("#withdrawForm").on("submit", function (e) {
                e.preventDefault();
                var formData = $(this).serialize();

                $.ajax({
                    type: "POST",
                    url: "process_withdraw.php",
                    data: formData,
                    dataType: "json",
                    success: function (response) {
                        toastr.options = {
                            "closeButton": true,
                            "progressBar": true,
                            "positionClass": "toast-top-right",
                            "timeOut": "3000"
                        };

                        if (response.status == "success") {
                            toastr["success"](response.message, "Withdrawal Successful");
                            setTimeout(function () {
                                window.location.reload();
                            }, 2000);
                        } else {
                            toastr["error"](response.message, "Withdrawal Failed");
                        }
                    },
                    error: function () {
                        toastr["error"]("Something went wrong!", "Error");
                    }
                });
            });
        });
    </script>

</body>
</html>