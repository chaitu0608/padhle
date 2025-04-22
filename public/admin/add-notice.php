<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../../includes/dbconnection.php');

if (!isset($_SESSION['sturecmsaid'])) {
    header('location:login.php');
    exit();
}

// Handle form submission
if (isset($_POST['submit'])) {
    $noticetitle = $_POST['noticetitle'];
    $notice = $_POST['notice'];
    $classid = $_POST['classid'];

    $sql = "INSERT INTO tblnotice (NoticeTitle, NoticeMsg, classId, CreationDate) 
            VALUES (:title, :msg, :classid, NOW())";

    $query = $dbh->prepare($sql);
    $query->bindParam(':title', $noticetitle, PDO::PARAM_STR);
    $query->bindParam(':msg', $notice, PDO::PARAM_STR);
    $query->bindParam(':classid', $classid, PDO::PARAM_INT);

    if ($query->execute()) {
        echo "<script>alert('✅ Notice added successfully!'); window.location.href='manage-notice.php';</script>";
    } else {
        echo "<script>alert('❌ Failed to add notice.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Padhle Admin - Add Notice</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #27272a;
        }
        ::-webkit-scrollbar-thumb {
            background: #3f3f46;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #4b5563;
        }
        
        /* Fix for select dropdown */
        select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
    </style>
</head>
<body class="bg-black text-white font-sans">

    <!-- Main Content Area -->
    <div class="p-6 md:p-8 max-w-4xl mx-auto">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-yellow-400">Add Notice</h1>
            <p class="text-gray-400 mt-1">Create a new notice for students and parents</p>
        </div>
        
        <!-- Notice Form Card -->
        <div class="bg-zinc-900 rounded-xl px-6 py-8 shadow-lg">
            <form method="post" action="">
                <!-- Notice Title -->
                <div class="mb-6">
                    <label for="noticetitle" class="block text-sm text-gray-300 font-medium mb-2">
                        <i class="fas fa-heading mr-2 text-gray-400"></i>
                        Notice Title
                    </label>
                    <input 
                        type="text" 
                        id="noticetitle" 
                        name="noticetitle" 
                        placeholder="Enter notice title" 
                        class="bg-zinc-800 text-white border border-zinc-700 placeholder-gray-500 rounded-md px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-600 transition-all duration-200"
                        required
                    >
                </div>
                
                <!-- Notice For (Class Selection) -->
                <div class="mb-6">
                    <label for="classid" class="block text-sm text-gray-300 font-medium mb-2">
                        <i class="fas fa-users mr-2 text-gray-400"></i>
                        Notice For
                    </label>
                    <select 
                        id="classid" 
                        name="classid" 
                        class="bg-zinc-800 text-white border border-zinc-700 placeholder-gray-500 rounded-md px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-600 transition-all duration-200"
                        required
                    >
                        <option value="" disabled selected>Select Class</option>
                        <option value="0">All Classes</option>
                        <option value="1">Class 1</option>
                        <option value="2">Class 2</option>
                        <option value="3">Class 3</option>
                        <option value="4">Class 4</option>
                        <option value="5">Class 5</option>
                        <option value="6">Class 6</option>
                        <option value="7">Class 7</option>
                        <option value="8">Class 8</option>
                        <option value="9">Class 9</option>
                        <option value="10">Class 10</option>
                        <option value="11">Class 11</option>
                        <option value="12">Class 12</option>
                    </select>
                </div>
                
                <!-- Notice Message -->
                <div class="mb-8">
                    <label for="noticemsg" class="block text-sm text-gray-300 font-medium mb-2">
                        <i class="fas fa-comment-alt mr-2 text-gray-400"></i>
                        Notice Message
                    </label>
                    <textarea 
                        id="noticemsg" 
                        name="noticemsg" 
                        rows="6" 
                        placeholder="Enter notice message" 
                        class="bg-zinc-800 text-white border border-zinc-700 placeholder-gray-500 rounded-md px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-600 transition-all duration-200"
                        required
                    ></textarea>
                </div>
                
                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row gap-4 pt-2">
                    <button 
                        type="submit" 
                        name="submit" 
                        class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition flex items-center justify-center"
                    >
                        <i class="fas fa-plus mr-2"></i>
                        Add Notice
                    </button>
                    <a 
                        href="manage-notice.php" 
                        class="bg-zinc-700 text-white px-5 py-2 rounded hover:bg-zinc-600 transition flex items-center justify-center"
                    >
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Notices
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Optional JavaScript for form enhancements -->
    <script>
        // Add character counter for the notice message
        const textarea = document.getElementById('noticemsg');
        
        if (textarea) {
            textarea.addEventListener('input', function() {
                // You could add a character counter here if needed
                // Example:
                // const maxLength = 500;
                // const remaining = maxLength - this.value.length;
                // document.getElementById('char-counter').textContent = `${remaining} characters remaining`;
            });
        }
        
        // You could add form validation if needed
        document.querySelector('form').addEventListener('submit', function(e) {
            // Any client-side validation can go here if needed
            // Note: We're not preventing default as the PHP backend will handle form submission
        });
    </script>
</body>
</html>