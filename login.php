<?php
// Database configuration
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "your_database";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Check if user exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();
    
    if ($check->num_rows > 0) {
        $error = "User already exists!";
    } else {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $password);
        
        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Registration failed!";
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CyberSign</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="signupStyles1.css" rel="stylesheet">
</head>
<body>
    <div class="stars"></div>
    <div class="twinkling"></div>
    
    <div class="cyber-container">
        <div class="cyber-form">
            <h2 class="cyber-title">SIGN UP</h2>
            
            <?php if(isset($error)): ?>
                <div class="cyber-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div class="input-group">
                    <input type="email" name="email" required>
                    <label class="cyber-label">EMAIL</label>
                    <div class="glow"></div>
                </div>

                <div class="input-group">
                    <input type="password" name="password" required>
                    <label class="cyber-label">PASSWORD</label>
                    <div class="glow"></div>
                </div>

                <button type="submit" class="cyber-button">
                    <span class="cyber-text">CREATE ACCOUNT</span>
                    <div class="cyber-glitch"></div>
                </button>
            </form>

            <div class="social-auth">
                <button class="google-btn cyber-button">
                    <i class="fab fa-google"></i>
                    <span>Sign Up with Google</span>
                </button>
                <button class="github-btn cyber-button">
                    <i class="fab fa-github"></i>
                    <span>Sign Up with GitHub</span>
                </button>
            </div>

            <p class="cyber-link">
                Already have an account? <a href="login.php">LOGIN HERE</a>
            </p>
        </div>
    </div>

    <script>
        // Generate stars
        for(let i = 0; i < 100; i++) {
            const star = document.createElement('div');
            star.className = 'star';
            star.style.left = Math.random() * 100 + '%';
            star.style.top = Math.random() * 100 + '%';
            star.style.animationDelay = Math.random() * 2 + 's';
            document.querySelector('.stars').appendChild(star);
        }
    </script>
</body>
</html>