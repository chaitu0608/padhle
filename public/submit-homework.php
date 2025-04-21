<?php
session_start();
if (!isset($_SESSION['sturecmsstuid'])) {
    header("Location: login.php");
    exit();
}

include("../includes/dbconnection.php");
$sid = $_SESSION['sturecmsstuid'];

if (isset($_GET['hwid'])) {
    $hwid = intval($_GET['hwid']);
} else {
    die("Homework ID missing.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pdf'])) {
    $file = $_FILES['pdf'];
    $targetDir = "../uploads/homework_submissions/";
    $filename = uniqid() . "_" . basename($file["name"]);
    $targetFilePath = $targetDir . $filename;

    if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
        $sql = "INSERT INTO tbluploadedhomeworks (StuID, HomeworkID, FilePath) VALUES (:sid, :hwid, :path)";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':sid', $sid);
        $stmt->bindParam(':hwid', $hwid);
        $stmt->bindParam(':path', $filename);
        $stmt->execute();
        $success = "Homework submitted successfully! ✅";
    } else {
        $error = "Upload failed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Padhle - Submit Homework</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom Styles -->
    <link href="../src/assets/styles.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        dark: '#121212',
                        'dark-lighter': '#000000',
                        'dark-border': '#333333',
                        'somaiya-red': '#D90429',
                        'highlight-yellow': '#FFD700',
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                }
            }
        }
    </script>
</head>
<body class="bg-dark text-white font-sans">
    <!-- Custom Cursor -->
    <div class="cursor-dot"></div>
    <div class="cursor-outline"></div>

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="bg-dark-lighter w-64 border-r border-dark-border h-full flex-shrink-0 fixed inset-y-0 left-0 z-30 transition-transform duration-300 ease-in-out transform md:translate-x-0 -translate-x-full">
            <div class="p-4 border-b border-dark-border">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-somaiya-red rounded-md flex items-center justify-center text-white font-bold text-xl">P</div>
                    <span class="ml-3 text-xl font-bold text-highlight-yellow">Padhle</span>
                </div>
            </div>
            <nav class="mt-6 px-4">
                <a href="./dashboard.php" class="flex items-center px-4 py-3 mb-2 text-gray-400 rounded-md hover:bg-white hover:text-black">
                    <i class="fas fa-home w-5 h-5"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
                <a href="./homework.php" class="flex items-center px-4 py-3 mb-2 text-white bg-somaiya-red rounded-md">
                    <i class="fas fa-book w-5 h-5"></i>
                    <span class="ml-3">Homework</span>
                </a>
                <a href="./notices.php" class="flex items-center px-4 py-3 mb-2 text-gray-400 rounded-md hover:bg-white hover:text-black">
                    <i class="fas fa-bell w-5 h-5"></i>
                    <span class="ml-3">Notices</span>
                </a>
                <a href="./student-profile.php" class="flex items-center px-4 py-3 mb-2 text-gray-400 rounded-md hover:bg-white hover:text-black">
                    <i class="fas fa-user w-5 h-5"></i>
                    <span class="ml-3">Profile</span>
                </a>
            </nav>
            <div class="absolute bottom-0 w-full p-4 border-t border-dark-border">
                <a href="#" class="flex items-center px-4 py-3 text-gray-400 rounded-md hover:bg-dark-border hover:text-white">
                    <i class="fas fa-sign-out-alt w-5 h-5"></i>
                    <span class="ml-3">Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto md:ml-64">
            <!-- Top bar -->
            <div class="bg-dark-lighter border-b border-dark-border p-4 flex justify-between items-center sticky top-0 z-20">
                <h1 class="text-xl font-semibold text-highlight-yellow">Submit Homework</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-400"><?php echo htmlentities($_SESSION['studentname']); ?></span>
                    <div class="w-8 h-8 bg-somaiya-red rounded-full flex items-center justify-center text-white font-medium">
                        <?php 
                        $nameParts = explode(' ', $_SESSION['studentname']);
                        echo strtoupper(substr($nameParts[0], 0, 1)) . strtoupper(substr($nameParts[1] ?? '', 0, 1)); 
                        ?>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <!-- Header Section -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-highlight-yellow">Upload Your Homework</h2>
                    <p class="text-gray-400">Submit your homework in PDF format below.</p>
                </div>

                <!-- Submission Form -->
                <div class="max-w-lg mx-auto bg-dark-lighter border border-dark-border p-8 rounded-lg">
                    <?php if (!empty($success)): ?>
                        <p class="text-green-400 mb-4"><?php echo $success; ?></p>
                    <?php elseif (!empty($error)): ?>
                        <p class="text-red-400 mb-4"><?php echo $error; ?></p>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data">
                        <label class="block mb-2 text-sm font-medium text-gray-300">Upload PDF:</label>
                        <input type="file" name="pdf" accept=".pdf" required class="mb-4 bg-gray-800 text-white p-2 rounded w-full">
                        <button type="submit" class="w-full bg-somaiya-red px-4 py-2 rounded hover:bg-opacity-80 text-white font-semibold">Submit</button>
                    </form>
                    <a href="homework.php" class="inline-block mt-4 text-sm text-yellow-300 hover:underline">← Back to Homework</a>
                </div>

                <!-- Footer -->
                <footer class="mt-12 pb-6 text-center text-gray-500 text-sm">
                    Made with ❤️ by Bhoumish and Chaitanya - © Somaiya University
                </footer>
            </div>
        </main>
    </div>

    <!-- Custom Cursor Script -->
    <script src="../src/assets/script.js"></script>
</body>
</html>