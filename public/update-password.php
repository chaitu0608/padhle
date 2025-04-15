<?php
session_start();
include("includes/dbconnection.php");

if (!isset($_SESSION['otp_verified'])) {
    header("Location: forgot-password.php");
    exit();
}

if (isset($_POST['update'])) {
    $newpass = password_hash($_POST['newpass'], PASSWORD_DEFAULT);
    $email = $_SESSION['reset_email'];

    $sql = "UPDATE tblstudent SET Password = :newpass WHERE Email = :email";
    $query = $dbh->prepare($sql);
    $query->bindParam(':newpass', $newpass, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();

    unset($_SESSION['otp_verified'], $_SESSION['otp'], $_SESSION['reset_email']);
    $msg = "Password updated! You can now <a href='login.php'>login</a>.";
}
?>

<form method="post">
    <h2>Reset Password</h2>
    <input type="password" name="newpass" placeholder="New Password" required>
    <button name="update">Update Password</button>
    <?php if (isset($msg)) echo "<p style='color:green;'>$msg</p>"; ?>
</form>