<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');

if(isset($_POST['submit'])) {
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $newpassword = md5($_POST['newpassword']);

    // Updated with correct column names
    $sql = "SELECT StudentEmail FROM tblstudent WHERE StudentEmail = :email AND ContactNumber = :mobile";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
    $query->execute();

    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if($query->rowCount() > 0) {
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


<form method="post">
    <input type="email" name="email" placeholder="Enter your registered email" required><br><br>
    <input type="text" name="mobile" placeholder="Enter your registered mobile number" required><br><br>
    <input type="password" name="newpassword" placeholder="Enter new password" required><br><br>
    <button type="submit" name="submit">Reset Password</button>
</form>