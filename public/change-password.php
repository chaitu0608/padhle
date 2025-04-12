<?php
session_start();
include('../includes/dbconnection.php');

if (!isset($_SESSION['sturecmsstuid'])) {
    header('Location: login.php');
    exit();
}

if (isset($_POST['submit'])) {
    $sid = $_SESSION['sturecmsstuid'];
    $cpassword = md5($_POST['currentpassword']);
    $newpassword = md5($_POST['newpassword']);

    $sql = "SELECT StuID FROM tblstudent WHERE StuID=:sid AND Password=:cpassword";
    $query = $dbh->prepare($sql);
    $query->bindParam(':sid', $sid, PDO::PARAM_STR);
    $query->bindParam(':cpassword', $cpassword, PDO::PARAM_STR);
    $query->execute();

    if ($query->rowCount() > 0) {
        $con = "UPDATE tblstudent SET Password=:newpassword WHERE StuID=:sid";
        $chngpwd1 = $dbh->prepare($con);
        $chngpwd1->bindParam(':sid', $sid, PDO::PARAM_STR);
        $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
        $chngpwd1->execute();
        $message = "Password successfully changed!";
    } else {
        $message = "Current password is incorrect!";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Change Password</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen bg-black flex items-center justify-center text-white font-sans">
  <div class="max-w-md w-full p-8 rounded-xl border border-gray-800 shadow-2xl bg-[#0e0e10] backdrop-blur-md">
    <h2 class="text-3xl font-bold text-center text-yellow-400 mb-6">Change Password</h2>

    <?php if (isset($message)): ?>
      <div class="mb-4 text-center text-sm text-green-400"><?php echo htmlentities($message); ?></div>
    <?php endif; ?>

    <form method="POST" onsubmit="return validatePasswordMatch();" class="space-y-4">
      <div>
        <label for="currentpassword" class="block text-sm font-medium text-white">Current Password</label>
        <input type="password" name="currentpassword" id="currentpassword" required
               class="mt-1 w-full rounded-lg bg-[#1a1a1d] text-yellow-400 border border-gray-700 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
      </div>

      <div>
        <label for="newpassword" class="block text-sm font-medium text-white">New Password</label>
        <input type="password" name="newpassword" id="newpassword" required
               class="mt-1 w-full rounded-lg bg-[#1a1a1d] text-yellow-400 border border-gray-700 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
      </div>

      <div>
        <label for="confirmpassword" class="block text-sm font-medium text-white">Confirm New Password</label>
        <input type="password" name="confirmpassword" id="confirmpassword" required
               class="mt-1 w-full rounded-lg bg-[#1a1a1d] text-yellow-400 border border-gray-700 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
      </div>

      <button type="submit" name="submit"
              class="w-full mt-4 rounded-lg bg-white py-3 font-semibold text-black shadow-lg hover:bg-red-700 transition-all duration-300">
        Update Password
      </button>
    </form>
  </div>

  <script>
    function validatePasswordMatch() {
      const newPassword = document.getElementById("newpassword").value;
      const confirmPassword = document.getElementById("confirmpassword").value;
      if (newPassword !== confirmPassword) {
        alert("New Password and Confirm Password do not match!");
        return false;
      }
      return true;
    }
  </script>
    <script src="../src/assets/three.r134.min.js"></script>
        <script src="../src/assets/vanta.birds.min.js"></script>
        <script>
        VANTA.BIRDS({
        el: "#your-element-selector",
        mouseControls: true,
        touchControls: true,
        gyroControls: false,
        minHeight: 200.00,
        minWidth: 200.00,
        scale: 1.00,
        scaleMobile: 1.00,
        backgroundColor: 0x70707,
        color1: 0xe60000,
        color2: 0xe1ff00,
        colorMode: "lerp",
        birdSize: 1.50
        });
    </script>
</body>
</html>