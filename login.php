<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2573977636104945"
     crossorigin="anonymous"></script>
    <title>9rato Login</title>
    <link rel="stylesheet" href="./asset/toast/toastr.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black min-h-screen flex items-center justify-center p-4 md:p-8">
    <div class="w-full max-w-md bg-[#0B4D3A] rounded-3xl overflow-hidden shadow-xl">
        <div class="p-8 md:p-12">
            <!-- Top Icons -->
            <div class="flex items-center gap-4 mb-8">
                <div class="p-0">
                    <img src="./asset/image/9ratoLogo.png" alt="Shopping Cart" class="w-10 h-10">
                </div>
                <div class="flex gap-2 ml-auto">
                    <span class="text-2xl">🔐</span>
                </div>
            </div>

            <!-- Main Content -->
            <div class="space-y-6">
                <h1 class="text-3xl md:text-4xl font-bold text-white leading-tight">
                    Welcome Back to 9rato
                </h1>
                
                <p class="text-white/90">
                    Enter your email to log in and continue your 9rato experience.
                </p>

                <!-- Email Form -->
                <form id="loginForm" class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-white mb-1">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="you@example.com" required
                               class="w-full bg-white border-0 rounded-md p-2 text-[#0B4D3A]">
                    </div>
                    <button type="submit" 
                            class="w-full bg-yellow-400 text-[#0B4D3A] hover:bg-yellow-500 px-4 py-2 rounded-md font-semibold transition duration-300">
                        Log In
                    </button>
                </form>

                <div class="text-center text-white">
                    <p>Don't have an account? <a href="index.php" class="text-yellow-400 hover:underline">Join the waitlist</a></p>
                </div>
            </div>
        </div>
    </div>
    <script src="./asset/toast/jquery-3.7.1.min.js"></script>
    <script src="./asset/toast/toastr.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#loginForm").on("submit", function (e) {
                e.preventDefault();
                var formData = $(this).serialize();

                $.ajax({
                    type: "POST",
                    url: "process_login.php",
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
                            toastr["success"](response.message, "Login Successful");
                            setTimeout(function () {
                                window.location.href = "user/index.php"; 
                            }, 2000);
                        } else {
                            toastr["error"](response.message, "Login Failed");
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