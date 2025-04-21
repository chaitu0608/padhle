<?php
session_start();
include('../../includes/dbconnection.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['homeworktitle'])) {
    $title = $_POST['homeworktitle'];
    $classSection = $_POST['homeworkfor']; // e.g., "10A"
    $description = $_POST['description'];
    $lastSubmission = $_POST['lastsubmission'];
    $postingDate = date("Y-m-d H:i:s");

    // Separate class and section
    $classNum = intval($classSection); // e.g., "10"
    $section = substr($classSection, -1); // e.g., "A"

    // Get classId from tblclass
    $sql = "SELECT ID FROM tblclass WHERE ClassName = :classname AND Section = :section LIMIT 1";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':classname', $classNum);
    $stmt->bindParam(':section', $section);
    $stmt->execute();
    $class = $stmt->fetch(PDO::FETCH_OBJ);

    if ($class) {
        $classId = $class->ID;

        // Insert homework
        $sql = "INSERT INTO tblhomework (homeworkTitle, classId, homeworkDescription, postingDate, lastDateOfSubmission)
                VALUES (:title, :classid, :description, :postingdate, :lastsubmission)";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':classid', $classId);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':postingdate', $postingDate);
        $stmt->bindParam(':lastsubmission', $lastSubmission);

        if ($stmt->execute()) {
            echo "<script>alert('Homework added successfully'); window.location.href='manage-homework.php';</script>";
        } else {
            echo "<script>alert('Something went wrong while inserting');</script>";
        }
    } else {
        echo "<script>alert('Class not found in database');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Padhle - Add Homework</title>
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
                    'dark-lighter': '#1e1e1e',
                    'dark-border': '#333333',
                    'somaiya-red': '#D90429',
                    'highlight-yellow': '#FFD700',
                },
                fontFamily: {
                    sans: ['Inter', 'system-ui', 'sans-serif'],
                },
                animation: {
                    'fade-in': 'fadeIn 0.5s ease-in-out',
                },
                keyframes: {
                    fadeIn: {
                        '0%': { opacity: '0' },
                        '100%': { opacity: '1' },
                    }
                }
            }
        }
    }
</script>
<style>
    /* Import Inter font */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
    }
    ::-webkit-scrollbar-track {
        background: #1e1e1e;
    }
    ::-webkit-scrollbar-thumb {
        background: #333333;
        border-radius: 3px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #D90429;
    }
    
    /* Custom date input styling */
    input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(1);
        cursor: pointer;
    }
</style>
</head>
<body class="bg-dark text-white font-sans">
<div class="flex h-screen overflow-hidden">
    <!-- Common Sidebar -->
    <aside id="sidebar" class="bg-somaiya-red w-64 h-full flex-shrink-0 fixed inset-y-0 left-0 z-30 transition-transform duration-300 ease-in-out transform md:translate-x-0 -translate-x-full">
        <!-- Logo -->
        <div class="p-4 border-b border-red-800">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white rounded-md flex items-center justify-center text-somaiya-red font-bold text-xl">P</div>
                <span class="ml-3 text-xl font-bold text-white">Padhle Admin</span>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="mt-6 px-4 overflow-y-auto" style="max-height: calc(100vh - 140px);">
            <!-- Dashboard -->
            <a href="./dashboard.php" class="flex items-center px-4 py-3 mb-2 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
                <i class="fas fa-tachometer-alt w-5 h-5"></i>
                <span class="ml-3">Dashboard</span>
            </a>

            <!-- Class Dropdown -->
            <div class="mb-2">
                <button id="class-dropdown-btn" class="w-full flex items-center justify-between px-4 py-3 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
                    <div class="flex items-center">
                        <i class="fas fa-folder w-5 h-5"></i>
                        <span class="ml-3">Class</span>
                    </div>
                    <i id="class-dropdown-icon" class="fas fa-chevron-down transition-transform duration-200"></i>
                </button>
                <div id="class-dropdown" class="pl-4 mt-1 hidden">
                    <a href="./add-class.php" class="flex items-center px-4 py-2 mb-1 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
                        <i class="fas fa-plus w-5 h-5"></i>
                        <span class="ml-3">Add Class</span>
                    </a>
                    <a href="./manage-class.php" class="flex items-center px-4 py-2 mb-1 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
                        <i class="fas fa-table w-5 h-5"></i>
                        <span class="ml-3">Manage Class</span>
                    </a>
                </div>
            </div>

            <!-- Students Dropdown -->
            <div class="mb-2">
                <button id="students-dropdown-btn" class="w-full flex items-center justify-between px-4 py-3 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
                    <div class="flex items-center">
                        <i class="fas fa-user-graduate w-5 h-5"></i>
                        <span class="ml-3">Students</span>
                    </div>
                    <i id="students-dropdown-icon" class="fas fa-chevron-down transition-transform duration-200"></i>
                </button>
                <div id="students-dropdown" class="pl-4 mt-1 hidden">
                    <a href="./add-students.php" class="flex items-center px-4 py-2 mb-1 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
                        <i class="fas fa-user-plus w-5 h-5"></i>
                        <span class="ml-3">Add Student</span>
                    </a>
                    <a href="./manage-students.php" class="flex items-center px-4 py-2 mb-1 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
                        <i class="fas fa-users w-5 h-5"></i>
                        <span class="ml-3">Manage Students</span>
                    </a>
                </div>
            </div>

            <!-- Homework Dropdown -->
            <div class="mb-2">
                <button id="homework-dropdown-btn" class="w-full flex items-center justify-between px-4 py-3 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
                    <div class="flex items-center">
                        <i class="fas fa-book w-5 h-5"></i>
                        <span class="ml-3">Homework</span>
                    </div>
                    <i id="homework-dropdown-icon" class="fas fa-chevron-down transition-transform duration-200"></i>
                </button>
                <div id="homework-dropdown" class="pl-4 mt-1 hidden">
                    <a href="./add-homework.php" class="flex items-center px-4 py-2 mb-1 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
                        <i class="fas fa-plus w-5 h-5"></i>
                        <span class="ml-3">Add Homework</span>
                    </a>
                    <a href="./manage-homework.php" class="flex items-center px-4 py-2 mb-1 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
                        <i class="fas fa-tasks w-5 h-5"></i>
                        <span class="ml-3">Manage Homework</span>
                    </a>
                </div>
            </div>

            <!-- Notices Dropdown -->
            <div class="mb-2">
                <button id="notices-dropdown-btn" class="w-full flex items-center justify-between px-4 py-3 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
                    <div class="flex items-center">
                        <i class="fas fa-bell w-5 h-5"></i>
                        <span class="ml-3">Notices</span>
                    </div>
                    <i id="notices-dropdown-icon" class="fas fa-chevron-down transition-transform duration-200"></i>
                </button>
                <div id="notices-dropdown" class="pl-4 mt-1 hidden">
                    <a href="./add-notice.php" class="flex items-center px-4 py-2 mb-1 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
                        <i class="fas fa-plus w-5 h-5"></i>
                        <span class="ml-3">Add Notice</span>
                    </a>
                    <a href="./manage-notice.php" class="flex items-center px-4 py-2 mb-1 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
                        <i class="fas fa-list w-5 h-5"></i>
                        <span class="ml-3">Manage Notices</span>
                    </a>
                </div>
            </div>
        </nav>

        <!-- Logout at bottom -->
        <div class="absolute bottom-0 w-full p-4 border-t border-red-800">
            <a href="./logout.php" class="flex items-center px-4 py-3 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
                <i class="fas fa-sign-out-alt w-5 h-5"></i>
                <span class="ml-3">Logout</span>
            </a>
        </div>
    </aside>
    
    <!-- Mobile sidebar toggle -->
    <div class="fixed bottom-4 right-4 md:hidden z-40">
        <button id="sidebar-toggle" class="bg-somaiya-red text-white p-3 rounded-full shadow-lg transition-transform duration-200 hover:scale-105 focus:outline-none">
            <i id="sidebar-icon" class="fas fa-bars"></i>
        </button>
    </div>
    
    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto md:ml-64 transition-all duration-300 ease-in-out">
        <!-- Top bar with admin info -->
        <div class="bg-dark-lighter border-b border-dark-border p-4 flex justify-between items-center sticky top-0 z-20">
            <h1 class="text-xl font-semibold text-highlight-yellow">Add Homework</h1>
            <div class="flex items-center space-x-4">
                <div class="text-sm text-gray-400">
                    <a href="#" class="hover:text-white transition-colors duration-200">Dashboard</a>
                    <span class="mx-2">/</span>
                    <span class="text-white">Add Homework</span>
                </div>
                <div class="w-8 h-8 bg-somaiya-red rounded-full flex items-center justify-center text-white font-medium">
                    A
                </div>
            </div>
        </div>
        
        <!-- Add Homework Content -->
        <div class="p-6 animate-fade-in">
            <!-- Header Section -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold mb-2">Add Homework</h2>
                <p class="text-gray-400">Enter homework details below. All fields are required.</p>
            </div>
            
            <!-- Homework Form -->
            <form class="bg-dark-lighter border border-dark-border rounded-lg p-6" method = "POST">
                <!-- Homework Title -->
                <div class="mb-6">
                    <label for="homeworktitle" class="block text-sm font-medium mb-2">
                        <i class="fas fa-heading mr-2 text-gray-400"></i>
                        Homework Title
                    </label>
                    <input 
                        type="text" 
                        id="homeworktitle" 
                        name="homeworktitle" 
                        placeholder="Enter homework title" 
                        class="w-full bg-dark border border-dark-border rounded-md py-3 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200"
                        required
                    >
                </div>
                
                <!-- Homework For (Class) -->
                <div class="mb-6">
                    <label for="homeworkfor" class="block text-sm font-medium mb-2">
                        <i class="fas fa-chalkboard mr-2 text-gray-400"></i>
                        Homework For
                    </label>
                    <select 
                        id="homeworkfor" 
                        name="homeworkfor" 
                        class="w-full bg-dark border border-dark-border rounded-md py-3 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200"
                        required
                    >
                        <option value="">Select Class</option>
                        <option value="1A">Class 1A</option>
                        <option value="1B">Class 1B</option>
                        <option value="2A">Class 2A</option>
                        <option value="2B">Class 2B</option>
                        <option value="3A">Class 3A</option>
                        <option value="3B">Class 3B</option>
                        <option value="4A">Class 4A</option>
                        <option value="4B">Class 4B</option>
                        <option value="5A">Class 5A</option>
                        <option value="5B">Class 5B</option>
                        <option value="6A">Class 6A</option>
                        <option value="6B">Class 6B</option>
                        <option value="7A">Class 7A</option>
                        <option value="7B">Class 7B</option>
                        <option value="8A">Class 8A</option>
                        <option value="8B">Class 8B</option>
                        <option value="9A">Class 9A</option>
                        <option value="9B">Class 9B</option>
                        <option value="10A">Class 10A</option>
                        <option value="10B">Class 10B</option>
                        <option value="11A">Class 11A</option>
                        <option value="11B">Class 11B</option>
                        <option value="12A">Class 12A</option>
                        <option value="12B">Class 12B</option>
                    </select>
                </div>
                
                <!-- Homework Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium mb-2">
                        <i class="fas fa-align-left mr-2 text-gray-400"></i>
                        Homework Description
                    </label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="6" 
                        placeholder="Enter detailed homework description" 
                        class="w-full bg-dark border border-dark-border rounded-md py-3 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200"
                        required
                    ></textarea>
                </div>
                
                <!-- Last Date of Submission -->
                <div class="mb-6">
                    <label for="lastsubmission" class="block text-sm font-medium mb-2">
                        <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>
                        Last Date of Submission
                    </label>
                    <input 
                        type="date" 
                        id="lastsubmission" 
                        name="lastsubmission" 
                        class="w-full bg-dark border border-dark-border rounded-md py-3 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200"
                        required
                    >
                </div>
                
                <!-- Submit Button -->
                <div class="flex flex-col">
                    <button 
                        type="submit" name="submit"
                        class="w-full px-6 py-3 bg-somaiya-red text-white font-medium rounded-md hover:bg-opacity-90 transition-all duration-200 transform hover:scale-[1.02] flex items-center justify-center"
                    >
                        <i class="fas fa-plus mr-2"></i>
                        <span>Add</span>
                    </button>
                    <div class="mt-4 text-center">
                        <a href="./manage-homework.php" class="text-gray-400 hover:text-white transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-1"></i> Back to Manage Homework
                        </a>
                    </div>
                </div>
            </form>
            
            <!-- Footer -->
            <footer class="mt-12 pb-6 text-center text-gray-500 text-sm">
                Made with ❤️ by Bhoumish and Chaitanya - © Somaiya University
            </footer>
        </div>
    </main>
</div>

<script>
    // Sidebar toggle functionality
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarIcon = document.getElementById('sidebar-icon');
    
    sidebarToggle.addEventListener('click', () => {
        if (sidebar.classList.contains('-translate-x-full')) {
            sidebar.classList.remove('-translate-x-full');
            sidebarIcon.classList.remove('fa-bars');
            sidebarIcon.classList.add('fa-times');
        } else {
            sidebar.classList.add('-translate-x-full');
            sidebarIcon.classList.remove('fa-times');
            sidebarIcon.classList.add('fa-bars');
        }
    });

    // Dropdown functionality
    document.addEventListener('DOMContentLoaded', () => {
        // Dropdown buttons and containers
        const dropdowns = [
            { btn: 'class-dropdown-btn', container: 'class-dropdown', icon: 'class-dropdown-icon' },
            { btn: 'students-dropdown-btn', container: 'students-dropdown', icon: 'students-dropdown-icon' },
            { btn: 'homework-dropdown-btn', container: 'homework-dropdown', icon: 'homework-dropdown-icon' },
            { btn: 'notices-dropdown-btn', container: 'notices-dropdown', icon: 'notices-dropdown-icon' },
        ];

        // Add event listeners for each dropdown
        dropdowns.forEach(({ btn, container, icon }) => {
            const button = document.getElementById(btn);
            const dropdown = document.getElementById(container);
            const dropdownIcon = document.getElementById(icon);

            button.addEventListener('click', () => {
                dropdown.classList.toggle('hidden');
                dropdownIcon.classList.toggle('rotate-180');
            });
        });
    });
</script>
</body>
</html>