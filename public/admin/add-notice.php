<?php
session_start();
include('../../includes/dbconnection.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['sturecmsaid'])) {
    header('location:login.php');
    exit();
}

// Handle form submission
if (isset($_POST['submit'])) {
    $noticetitle = $_POST['noticetitle'];
    $notice = $_POST['notice'];
    $classid = $_POST['classid'];
    $created = date("Y-m-d H:i:s");

    $sql = "INSERT INTO tblnotice (NoticeTitle, NoticeMsg, classId, CreationDate) 
            VALUES (:noticetitle, :notice, :classid, :created)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':noticetitle', $noticetitle, PDO::PARAM_STR);
    $query->bindParam(':notice', $notice, PDO::PARAM_STR);
    $query->bindParam(':classid', $classid, PDO::PARAM_INT);
    $query->bindParam(':created', $created, PDO::PARAM_STR);

    if ($query->execute()) {
        echo "<script>alert('✅ Notice added successfully!'); window.location.href='manage-notice.php';</script>";
        exit();
    } else {
        echo "<script>alert('❌ Failed to add notice. Please try again.');</script>";
    }
}

// Fetch notices for pagination and search
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sql = "SELECT n.ID, n.NoticeTitle, n.CreationDate, c.ClassName, c.Section
        FROM tblnotice n
        JOIN tblclass c ON n.ClassId = c.ID
        WHERE 1=1";

// Add search functionality
if (!empty($search)) {
    $sql .= " AND (n.NoticeTitle LIKE :search OR c.ClassName LIKE :search OR c.Section LIKE :search)";
}

$sql .= " ORDER BY n.ID DESC";
$query = $dbh->prepare($sql);

// Bind search parameter
if (!empty($search)) {
    $searchParam = '%' . $search . '%';
    $query->bindParam(':search', $searchParam, PDO::PARAM_STR);
}

$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Padhle - Add Notice</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        dark: '#121212',
                        'dark-lighter': '#1E1E1E',
                        'dark-border': '#333333',
                        'somaiya-red': '#D90429',
                        'highlight-yellow': '#FFD700',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-dark text-white font-sans min-h-screen">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="bg-somaiya-red w-64 h-full flex-shrink-0 fixed inset-y-0 left-0 z-30 transition-transform duration-300 ease-in-out transform md:translate-x-0 -translate-x-full">
            <!-- Sidebar content -->
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto md:ml-64 transition-all duration-300 ease-in-out">
            <!-- Top bar -->
            <div class="bg-dark-lighter border-b border-dark-border p-4 flex justify-between items-center sticky top-0 z-20">
                <h1 class="text-xl font-semibold text-highlight-yellow">Add Notice</h1>
                <div class="flex items-center space-x-4">
                    <div class="text-sm text-gray-400">
                        <a href="./dashboard.php" class="hover:text-white transition-colors duration-200">Dashboard</a>
                        <span class="mx-2">/</span>
                        <span class="text-white">Add Notice</span>
                    </div>
                    <div class="w-8 h-8 bg-somaiya-red rounded-full flex items-center justify-center text-white font-medium">
                        A
                    </div>
                </div>
            </div>

            <!-- Add Notice Content -->
            <div class="p-6 animate-fade-in">
                <!-- Header Section -->
                <div class="mb-6">
                    <h2 class="text-2xl font-bold mb-2">Add Notice</h2>
                    <p class="text-gray-400">Enter notice details below. All fields are required.</p>
                </div>

                <!-- Notice Form -->
                <form method="post" class="bg-dark-lighter border border-dark-border rounded-lg p-6">
                    <!-- Notice Title -->
                    <div class="mb-6">
                        <label for="noticetitle" class="block text-sm font-medium mb-2">
                            <i class="fas fa-heading mr-2 text-gray-400"></i>
                            Notice Title
                        </label>
                        <input 
                            type="text" 
                            id="noticetitle" 
                            name="noticetitle" 
                            placeholder="Enter notice title" 
                            class="w-full bg-dark border border-dark-border rounded-md py-3 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200"
                            required
                        >
                    </div>

                    <!-- Notice For -->
                    <div class="mb-6">
                        <label for="classid" class="block text-sm font-medium mb-2">
                            <i class="fas fa-users mr-2 text-gray-400"></i>
                            Notice For
                        </label>
                        <select 
                            id="classid" 
                            name="classid" 
                            class="w-full bg-dark border border-dark-border rounded-md py-3 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200"
                            required
                        >
                            <option value="">Select Audience</option>
                            <option value="0">All Classes</option>
                            <?php
                            $classQuery = $dbh->prepare("SELECT ID, ClassName, Section FROM tblclass ORDER BY ClassName ASC");
                            $classQuery->execute();
                            $classes = $classQuery->fetchAll(PDO::FETCH_OBJ);
                            foreach ($classes as $class) {
                                echo "<option value='{$class->ID}'>{$class->ClassName} - {$class->Section}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Notice Message -->
                    <div class="mb-6">
                        <label for="notice" class="block text-sm font-medium mb-2">
                            <i class="fas fa-align-left mr-2 text-gray-400"></i>
                            Notice Message
                        </label>
                        <textarea 
                            id="notice" 
                            name="notice" 
                            rows="6" 
                            placeholder="Enter notice message" 
                            class="w-full bg-dark border border-dark-border rounded-md py-3 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200"
                            required
                        ></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex flex-col">
                        <button 
                            type="submit" 
                            name="submit"
                            class="w-full px-6 py-3 bg-somaiya-red text-white font-medium rounded-md hover:bg-opacity-90 transition-all duration-200 transform hover:scale-[1.02] flex items-center justify-center"
                        >
                            <i class="fas fa-plus mr-2"></i>
                            <span>Add Notice</span>
                        </button>
                        <div class="mt-4 text-center">
                            <a href="./manage-notice.php" class="text-gray-400 hover:text-white transition-colors duration-200">
                                <i class="fas fa-arrow-left mr-1"></i> Back to Manage Notices
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>