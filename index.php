<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>9rato Waitlist</title>
    <link rel="stylesheet" href="./asset/css/home.css">
    <link rel="shortcut icon" href="./asset/image/9ratoLogo.png" type="image/x-icon">
    <link rel="stylesheet" href="./asset/toast/toastr.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black min-h-screen flex items-center justify-center p-4 md:p-8">
    <div class="w-full max-w-6xl grid md:grid-cols-2 gap-6 rounded-3xl overflow-hidden">
        <!-- Left Section -->
        <div class="bg-[#0B4D3A] p-2 md:p-12 relative">
            <!-- Top Icons -->
            <div class="flex items-center gap-4 mb-8">
                <div class="p-0">
                    <img src="./asset/image/9ratoLogo.png" alt="Shopping Cart" class="w-10 h-10">
                </div>
                <div class="flex gap-2 ml-[70px] sm:ml-auto">
                    <span class="text-2xl"><img src="./asset/image/party.png" alt="" class="w-[40px]"></span>
                    <span class="text-2xl"><img src="./asset/image/time.png" alt="" class="w-[40px]"></span>
                    <span class="text-2xl"><img src="./asset/image/alarm.png" alt="" class="w-[40px]"></span>
                </div>
            </div>

            <!-- Main Content -->
            <div class="space-y-6">
                <h1 class="text-2xl md:text-[40px] font-bold text-white leading-tight">
                    Join the 9rato Waitlist <br>
                    – Be Among the First!
                </h1>
                
                <p class="text-white/90">
                    Something exciting is coming soon! Join the exclusive <span class="text-yellow-400">9rato waitlist</span> and be among the first to experience a new way of engaging online. By signing up, you'll gain early access to <span class="text-yellow-400">exclusive updates, and special perks</span> before the official launch.
                </p>

                <div class="space-y-2">
                    <div class="flex flex-col sm:flex-row sm:space-x-8 space-y-2 sm:space-y-0">
                        <div class="text-white text-xl">Join over</div>
                        <div class="text-yellow-400 text-5xl font-bold">400,000</div>
                    </div>
                    <div class="text-white text-xl">accounts</div>
                </div>

                <!-- Email Form -->
                 <form id="registerForm">
                    <div class="flex gap-2">
                        <input type="email" name="email" required placeholder="Email address" class="flex-1 bg-white border-0 rounded-md p-2">
                        <button type="submit" class="bg-yellow-400 text-[#0B4D3A] hover:bg-yellow-500 px-4 py-2 rounded-md ml-[-20px]">Submit</button>
                    </div>
                </form>
                <!-- Bottom Section -->
                <div class="mt-12 flex items-end justify-between">
                    <div>
                        <a href="login.php">
                            <button class="bg-yellow-400 text-[#0B4D3A] hover:bg-yellow-500 px-4 py-2 rounded-md w-32">Login</button>
                        </a>
                        <div class="text-white mt-4">
                            Get <span class="text-yellow-400">₦1000 per</span> referral to Jion Waitlist
                        </div>
                    </div>
                    <div class="flex items-end gap-4">
                        <div class="w-16 h-16 rounded-full flex items-center justify-center text-3xl">
                            <img src="./asset/image/shake.gif" alt="">
                        </div>
                        <div class="w-16 h-16 text-4xl">
                           <img src="./asset/image/Promo-code.svg.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Section -->
        <div class="bg-cover bg-center p-8 flex flex-col items-center justify-end" style="background-image: url('./asset/image/div.png');">
            <div class="space-y-4 w-full max-w-xs">
                <!-- <button class="w-full bg-white hover:bg-gray-100 text-black flex items-center justify-center gap-2 px-4 py-2 rounded-md">
                    <img src="/placeholder.svg" alt="Google Play" class="w-6 h-6">
                    Download on Google Play
                </button>
                <button class="w-full bg-white hover:bg-gray-100 text-black flex items-center justify-center gap-2 px-4 py-2 rounded-md">
                    <img src="/placeholder.svg" alt="App Store" class="w-6 h-6">
                    Download on App Store
                </button> -->
            </div>
        </div>
    </div>
    <script src="./asset/toast/jquery-3.7.1.min.js"></script>
    <script src="./asset/toast/toastr.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#registerForm").on("submit", function (e) {
                e.preventDefault(); 
                var formData = $(this).serialize(); 

                $.ajax({
                    type: "POST",
                    url: "process_register.php",
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
                            toastr["success"](response.message, "Registration Successful");
                            setTimeout(function () {
                                window.location.href = "login.php"; 
                            }, 2000);
                        } else {
                            toastr["error"](response.message, "Registration Failed");
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