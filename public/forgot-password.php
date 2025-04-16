<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $newpassword = md5($_POST['newpassword']);

    // Updated with correct column names
    $sql = "SELECT StudentEmail FROM tblstudent WHERE StudentEmail = :email AND ContactNumber = :mobile";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
    $query->execute();

    if ($query->rowCount() > 0) {
        $updateSql = "UPDATE tblstudent SET Password = :newpassword WHERE StudentEmail = :email AND ContactNumber = :mobile";
        $updateQuery = $dbh->prepare($updateSql);
        $updateQuery->bindParam(':email', $email, PDO::PARAM_STR);
        $updateQuery->bindParam(':mobile', $mobile, PDO::PARAM_STR);
        $updateQuery->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
        $updateQuery->execute();

        echo "<script>alert('Your Password successfully changed');</script>";
    } else {
        echo "<script>alert('Invalid Email or Mobile Number');</script>";
    }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Custom Styles -->
    <link href="../src/assets/styles.css" rel="stylesheet">
  </head>
  <body class="h-screen m-0 p-0 overflow-hidden">
    <!-- Custom Cursor -->
    <div class="cursor-dot"></div>
    <div class="cursor-outline"></div>

    <!-- Vanta Background Container -->
    <div id="vanta-bg" class="h-full w-full flex items-center justify-center">
      <!-- Forgot Password Card -->
      <div class="w-full max-w-md rounded-xl bg-[#0e0e10] p-8 shadow-2xl border border-gray-800 backdrop-blur-md">
        <h2 class="mb-6 text-center text-3xl font-bold text-white">
          Forgot <span class="text-yellow-400">Password?</span>
        </h2>

        <!-- Reset Password Form -->
        <form action="forgot-password.php" method="POST" class="space-y-4">
          <!-- Email Field -->
          <div>
            <label for="email" class="block text-sm font-medium text-white">Email</label>
            <input
              type="email"
              id="email"
              name="email"
              placeholder="Enter your registered email"
              class="mt-1 w-full rounded-lg bg-[#1a1a1d] text-yellow-400 border border-gray-700 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
              required
            />
          </div>

          <!-- Mobile Field -->
          <div>
            <label for="mobile" class="block text-sm font-medium text-white">Mobile Number</label>
            <input
              type="text"
              id="mobile"
              name="mobile"
              placeholder="Enter your registered mobile number"
              class="mt-1 w-full rounded-lg bg-[#1a1a1d] text-yellow-400 border border-gray-700 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
              required
            />
          </div>

          <!-- New Password Field -->
          <div>
            <label for="newpassword" class="block text-sm font-medium text-white">New Password</label>
            <input
              type="password"
              id="newpassword"
              name="newpassword"
              placeholder="Enter your new password"
              class="mt-1 w-full rounded-lg bg-[#1a1a1d] text-yellow-400 border border-gray-700 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
              required
            />
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            name="submit"
            class="w-full rounded-lg bg-white py-3 font-semibold text-black shadow-lg transition-all duration-300 hover:bg-red-700">
            Reset Password
          </button>
        </form>

        <!-- Reset Password via OTP -->
        <div class="mt-4 text-center">
          <a href="reset-password-otp.php" class="text-sm text-red-500 hover:underline">
            Reset Password via OTP
          </a>
        </div>
      </div>
    </div>

    <!-- Vanta Background Scripts -->
    <script src="../src/assets/p5.min.js"></script>
    <script src="../src/assets/vanta.topology.min.js"></script>
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

    <!-- Custom Cursor Script -->
    <script src="../src/assets/cursor.js"></script>
  </body>
</html>