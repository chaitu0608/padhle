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
    <title>Admin Login | Padhle Portal</title>
    <link rel="stylesheet" href="../css/style.css"> <!-- Optional -->
</head>
<body>
    <div class="login-box">
        <h2>Admin Login</h2>
        <form method="post">
            <div class="user-box">
                <input type="text" name="username" required>
                <label>Username</label>
            </div>
            <div class="user-box">
                <input type="password" name="password" required>
                <label>Password</label>
            </div>
            <center>
                <button type="submit" name="login" class="login-btn">Login</button>
            </center>
        </form>
    </div>
</body>
</html>