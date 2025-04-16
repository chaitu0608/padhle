<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Check if student is logged in
if (strlen($_SESSION['sturecmsstuid']) == 0) {
    header('location:logout.php');
    exit();
}

// On form submit
if (isset($_POST['submit'])) {
    $sid = $_SESSION['sturecmsstuid'];
    $cpassword = md5($_POST['currentpassword']);
    $newpassword = md5($_POST['newpassword']);

    // Check if current password is correct
    $sql = "SELECT StuID FROM tblstudent WHERE StuID = :sid AND Password = :cpassword";
    $query = $dbh->prepare($sql);
    $query->bindParam(':sid', $sid, PDO::PARAM_STR);
    $query->bindParam(':cpassword', $cpassword, PDO::PARAM_STR);
    $query->execute();

    if ($query->rowCount() > 0) {
        // Update new password
        $updateSql = "UPDATE tblstudent SET Password = :newpassword WHERE StuID = :sid";
        $updateQuery = $dbh->prepare($updateSql);
        $updateQuery->bindParam(':sid', $sid, PDO::PARAM_STR);
        $updateQuery->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
        $updateQuery->execute();

        echo "<script>alert('Your password was successfully changed');</script>";
    } else {
        echo "<script>alert('Your current password is incorrect');</script>";
    }
}
?>

<form method="post">
    
    <input type="password" name="currentpassword" placeholder="Current Password" required><br><br>
    <input type="password" name="newpassword" placeholder="New Password" required><br><br>
    <button type="submit" name="submit">Change Password</button>
</form>