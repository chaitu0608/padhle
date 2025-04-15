<?php
session_start();
if (isset($_POST['verify'])) {
    $enteredOtp = $_POST['otp'];
    if ($_SESSION['otp'] == $enteredOtp) {
        echo "<script>window.location.href='update-password.php';</script>";
    } else {
        echo "<script>alert('Invalid OTP. Try again.');</script>";
    }
}
?>

<form method="post">
    <input type="text" name="otp" placeholder="Enter OTP" required>
    <button type="submit" name="verify">Verify OTP</button>
</form>