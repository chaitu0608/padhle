<?php
session_start();
include('../includes/dbconnection.php');

if (isset($_POST['update'])) {
    $newpassword = md5($_POST['newpassword']);
    $email = $_SESSION['email'];

    $sql = "UPDATE tblstudent SET Password = :newpassword WHERE Email = :email";
    $query = $dbh->prepare($sql);
    $query->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);

    if ($query->execute()) {
        echo "<script>alert('Password updated successfully.');</script>";
        session_destroy();
        echo "<script>window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Something went wrong.');</script>";
    }
}
?>

<form method="post">
    <input type="password" name="newpassword" placeholder="Enter new password" required>
    <button type="submit" name="update">Update Password</button>
</form>