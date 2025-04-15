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
    <title>Submit Homework</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="bg-dark-lighter w-64 border-r border-dark-border h-full flex-shrink-0 fixed inset-y-0 left-0 z-30">
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
                <a href="./homework.php" class="flex items-center px-4 py-3 mb-2 text-gray-400 rounded-md hover:bg-white hover:text-black">
                    <i class="fas fa-book w-5 h-5"></i>
                    <span class="ml-3">Homework</span>
                </a>
                <a href="./submit-homework.php" class="flex items-center px-4 py-3 mb-2 text-white bg-somaiya-red rounded-md">
                    <i class="fas fa-upload w-5 h-5"></i>
                    <span class="ml-3">Submit Homework</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto md:ml-64">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-highlight-yellow mb-4">📤 Submit Homework</h1>

                <?php if (!empty($success)): ?>
                    <p class="text-green-400 mb-4"><?php echo $success; ?></p>
                <?php elseif (!empty($error)): ?>
                    <p class="text-red-400 mb-4"><?php echo $error; ?></p>
                <?php endif; ?>

                <div class="bg-dark-lighter border border-dark-border p-6 rounded-lg">
                    <form method="POST" enctype="multipart/form-data">
                        <label class="block mb-2 text-gray-300">Upload PDF:</label>
                        <input type="file" name="pdf" accept=".pdf" required class="mb-4 bg-gray-800 text-white p-2 rounded w-full">
                        <button type="submit" class="bg-somaiya-red px-4 py-2 rounded hover:bg-opacity-80">Submit</button>
                    </form>
                    <a href="homework.php" class="inline-block mt-4 text-sm text-yellow-300">← Back to Homework</a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>