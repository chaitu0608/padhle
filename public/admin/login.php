<?php
session_start();
error_reporting(E_ALL); // show all errors
ini_set('display_errors', 1); // display them on the screen
error_reporting(0);
include('../../includes/dbconnection.php');

if (isset($_POST['login'])) {
    $adminuser = $_POST['username'];
    $password = md5($_POST['password']); // Password is stored as md5 in DB

    $sql = "SELECT ID FROM tbladmin WHERE UserName = :adminuser AND Password = :password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':adminuser', $adminuser, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();

    $results = $query->fetchAll(PDO::FETCH_OBJ);
    if ($query->rowCount() > 0) {
        $_SESSION['sturecmsaid'] = $results[0]->ID; // Save admin ID in session
        header('Location: dashboard.php');
        exit(); // Important to prevent further code execution
    } else {
        echo "<script>alert('Invalid Details');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Padhle Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen m-0 p-0 overflow-hidden">
    <!-- Vanta Background Container -->
    <div id="vanta-bg" class="h-full w-full flex items-center justify-center">
        <!-- Login Card -->
        <div class="w-full max-w-md rounded-xl bg-[#0e0e10] p-8 shadow-2xl border border-gray-800 backdrop-blur-md">
            <h2 class="mb-6 text-center text-3xl font-bold text-white">
                Admin Login to <span class="text-yellow-400">Padhle!</span>
            </h2>

            <form method="post" class="space-y-4">
                <!-- Username Field -->
                <div>
                    <label for="username" class="block text-sm font-medium text-white">Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Enter your Username"
                        class="mt-1 w-full rounded-lg bg-[#1a1a1d] text-yellow-400 border border-gray-700 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        required
                    />
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-white">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your Password"
                        class="mt-1 w-full rounded-lg bg-[#1a1a1d] text-yellow-400 border border-gray-700 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        required
                    />
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    name="login"
                    class="w-full rounded-lg bg-white py-3 font-semibold text-black shadow-lg transition-all duration-300 hover:bg-red-700">
                    Login
                </button>
            </form>
        </div>
    </div>

    <!-- Vanta Background Scripts -->
    <script src="../../src/assets/p5.min.js"></script>
    <script src="../../src/assets/vanta.topology.min.js"></script>
    <script>
        VANTA.TOPOLOGY({
            el: "#vanta-bg",
            mouseControls: true,
            touchControls: true,
            gyroControls: false,
            minHeight: 200.00,
            minWidth: 200.00,
            scale: 1.00,
            scaleMobile: 1.00,
            color: 0xeb0e0e,
            backgroundColor: 0x000000
        });
    </script>
</body>
</html>