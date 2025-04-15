<?php
session_start();

if (!isset($_SESSION['otp'])) {
    header("Location: forgot-password.php");
    exit();
}

if (isset($_POST['verify'])) {
    $otp_input = $_POST['otp'];
    if ($_SESSION['otp'] == $otp_input) {
        $_SESSION['otp_verified'] = true;
        header("Location: update-password.php");
        exit();
    } else {
        $msg = "Incorrect OTP.";
    }
}
?>

<form method="post">
    <h2>Verify OTP</h2>
    <p>Use this OTP: <strong><?php echo $_SESSION['otp']; ?></strong> (for now)</p>
    <input type="number" name="otp" placeholder="Enter OTP" required>
    <button name="verify">Verify</button>
    <?php if (isset($msg)) echo "<p style='color:red;'>$msg</p>"; ?>
</form>