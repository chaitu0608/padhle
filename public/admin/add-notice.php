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
    <title>Add Notice - Padhle Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom styles for select dropdown */
        select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
        
        /* Focus styles */
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
        }
    </style>
</head>
<body class="bg-black text-white min-h-screen flex">
    <!-- Sidebar -->
    <div class="w-64 bg-[#c0392b] min-h-screen fixed left-0 top-0 z-30 transition-transform duration-300 transform" id="sidebar">
        <div class="p-4">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-xl font-bold text-white">Padhle Admin</h1>
                <button id="closeSidebar" class="text-white md:hidden">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <nav>
                <ul class="space-y-2">
                    <li>
                        <a href="#" class="flex items-center px-4 py-2.5 text-white hover:bg-[#992d22] rounded-md transition-colors">
                            <i class="fas fa-home w-5 mr-3"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-2.5 text-white hover:bg-[#992d22] rounded-md transition-colors">
                            <i class="fas fa-user-graduate w-5 mr-3"></i>
                            <span>Students</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-2.5 text-white hover:bg-[#992d22] rounded-md transition-colors">
                            <i class="fas fa-chalkboard-teacher w-5 mr-3"></i>
                            <span>Teachers</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-2.5 text-white hover:bg-[#992d22] rounded-md transition-colors">
                            <i class="fas fa-book w-5 mr-3"></i>
                            <span>Courses</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-2.5 bg-[#992d22] text-white rounded-md transition-colors">
                            <i class="fas fa-bell w-5 mr-3"></i>
                            <span>Notices</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-2.5 text-white hover:bg-[#992d22] rounded-md transition-colors">
                            <i class="fas fa-calendar-alt w-5 mr-3"></i>
                            <span>Timetable</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-2.5 text-white hover:bg-[#992d22] rounded-md transition-colors">
                            <i class="fas fa-file-alt w-5 mr-3"></i>
                            <span>Exams</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-2.5 text-white hover:bg-[#992d22] rounded-md transition-colors">
                            <i class="fas fa-cog w-5 mr-3"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <!-- Mobile sidebar toggle -->
    <div class="fixed top-4 left-4 z-40 md:hidden">
        <button id="openSidebar" class="text-white bg-[#c0392b] p-2 rounded-md">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Main Content -->
    <div class="flex-1 md:ml-64">
        <div class="p-6">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                <h1 class="text-2xl font-bold text-yellow-400">Add Notice</h1>
                <div class="mt-2 md:mt-0 text-sm">
                    <a href="#" class="text-gray-400 hover:text-white">Dashboard</a>
                    <span class="mx-2 text-gray-600">/</span>
                    <span class="text-white">Add Notice</span>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-[#1f1f1f] rounded-lg shadow-lg p-6">
                <form id="addNoticeForm">
                    <!-- Notice Title -->
                    <div class="mb-6">
                        <label for="noticeTitle" class="block text-white text-sm font-medium mb-2">Notice Title</label>
                        <input 
                            type="text" 
                            id="noticeTitle" 
                            name="noticeTitle" 
                            class="w-full px-4 py-2.5 bg-[#2c2c2c] border border-gray-700 rounded-md text-white placeholder-gray-400"
                            placeholder="Enter notice title"
                            required
                        >
                    </div>

                    <!-- Notice For -->
                    <div class="mb-6">
                        <label for="noticeFor" class="block text-white text-sm font-medium mb-2">Notice For</label>
                        <select 
                            id="noticeFor" 
                            name="noticeFor" 
                            class="w-full px-4 py-2.5 bg-[#2c2c2c] border border-gray-700 rounded-md text-white"
                            required
                        >
                            <option value="" disabled selected>Select class</option>
                            <option value="all">All Classes</option>
                            <option value="class1">Class 1</option>
                            <option value="class2">Class 2</option>
                            <option value="class3">Class 3</option>
                            <option value="class4">Class 4</option>
                            <option value="class5">Class 5</option>
                            <option value="class6">Class 6</option>
                            <option value="class7">Class 7</option>
                            <option value="class8">Class 8</option>
                            <option value="class9">Class 9</option>
                            <option value="class10">Class 10</option>
                            <option value="class11">Class 11</option>
                            <option value="class12">Class 12</option>
                        </select>
                    </div>

                    <!-- Notice Message -->
                    <div class="mb-6">
                        <label for="noticeMessage" class="block text-white text-sm font-medium mb-2">Notice Message</label>
                        <textarea 
                            id="noticeMessage" 
                            name="noticeMessage" 
                            rows="6" 
                            class="w-full px-4 py-2.5 bg-[#2c2c2c] border border-gray-700 rounded-md text-white placeholder-gray-400 resize-none"
                            placeholder="Enter notice message"
                            required
                        ></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button 
                            type="submit" 
                            class="px-6 py-2.5 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
                        >
                            Add
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const openSidebarBtn = document.getElementById('openSidebar');
            const closeSidebarBtn = document.getElementById('closeSidebar');
            
            // Open sidebar
            openSidebarBtn.addEventListener('click', function() {
                sidebar.classList.remove('-translate-x-full');
            });
            
            // Close sidebar
            closeSidebarBtn.addEventListener('click', function() {
                sidebar.classList.add('-translate-x-full');
            });
            
            // Handle form submission
            const form = document.getElementById('addNoticeForm');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get form values
                const title = document.getElementById('noticeTitle').value;
                const forClass = document.getElementById('noticeFor').value;
                const message = document.getElementById('noticeMessage').value;
                
                // In a real application, you would send this data to your server
                console.log({
                    title,
                    forClass,
                    message
                });
                
                // Show success message (for demo purposes)
                alert('Notice added successfully!');
                
                // Reset form
                form.reset();
            });
            
            // Check if we're on mobile and hide sidebar by default
            function checkMobile() {
                if (window.innerWidth < 768) {
                    sidebar.classList.add('-translate-x-full');
                } else {
                    sidebar.classList.remove('-translate-x-full');
                }
            }
            
            // Initial check
            checkMobile();
            
            // Check on resize
            window.addEventListener('resize', checkMobile);
        });
    </script>
</body>
</html>