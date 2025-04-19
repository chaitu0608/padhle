<?php
session_start();
require '../vendor/autoload.php'; // Include PHPMailer autoload file
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['send_otp'])) {
    $email = $_POST['email'];

    // Generate a random 6-digit OTP
    $otp = rand(100000, 999999);

    // Store OTP in session
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_email'] = $email;

    // Send OTP using PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Use your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'john@gmail.com'; // Your email address
        $mail->Password = 'your-email-pas'; // Your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('your-email@gmail.com', 'Padhle');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP for Login';
        $mail->Body = "Your OTP is: <b>$otp</b>. It is valid for 5 minutes.";

        $mail->send();
        echo "<script>alert('OTP sent to your email.'); window.location.href='verify-otp.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Failed to send OTP. Mailer Error: {$mail->ErrorInfo}');</script>";
    }
}

if (isset($_POST['verify'])) {
    $enteredOtp = $_POST['otp'];

    // Verify OTP
    if ($_SESSION['otp'] == $enteredOtp) {
        echo "<script>window.location.href='update-password.php';</script>";
    } else {
        echo "<script>alert('Invalid OTP. Try again.');</script>";
    }
}
?>

<!-- OTP Form -->
<form method="post">
    <?php if (!isset($_SESSION['otp'])) { ?>
        <!-- Email Input -->
        <input type="email" name="email" placeholder="Enter your email" required>
        <button type="submit" name="send_otp">Send OTP</button>
    <?php } else { ?>
        <!-- OTP Input -->
        <input type="text" name="otp" placeholder="Enter OTP" required>
        <button type="submit" name="verify">Verify OTP</button>
    <?php } ?>
</form>