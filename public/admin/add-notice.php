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
<title>Padhle - Manage Notices</title>
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
        height: 6px;
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
</style>
</head>
<body class="bg-dark text-white font-sans">
<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
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
            <a href="#" class="flex items-center px-4 py-3 mb-2 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
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
                    <a href="#" class="flex items-center px-4 py-2 mb-1 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
                        <i class="fas fa-plus w-5 h-5"></i>
                        <span class="ml-3">Add Class</span>
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 mb-1 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
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
                    <a href="#" class="flex items-center px-4 py-2 mb-1 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
                        <i class="fas fa-user-plus w-5 h-5"></i>
                        <span class="ml-3">Add Student</span>
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 mb-1 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
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
                    <a href="#" class="flex items-center px-4 py-2 mb-1 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
                        <i class="fas fa-plus w-5 h-5"></i>
                        <span class="ml-3">Add Homework</span>
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 mb-1 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
                        <i class="fas fa-tasks w-5 h-5"></i>
                        <span class="ml-3">Manage Homework</span>
                    </a>
                </div>
            </div>
            
            <!-- Notice (Active) -->
            <div class="mb-2">
                <button id="notice-dropdown-btn" class="w-full flex items-center justify-between px-4 py-3 text-white bg-black bg-opacity-30 rounded-md transition-colors duration-200 hover:bg-black hover:bg-opacity-40">
                    <div class="flex items-center">
                        <i class="fas fa-bell w-5 h-5"></i>
                        <span class="ml-3">Notice</span>
                    </div>
                    <i id="notice-dropdown-icon" class="fas fa-chevron-down rotate-180 transition-transform duration-200"></i>
                </button>
                
                <div id="notice-dropdown" class="pl-4 mt-1">
                    <a href="#" class="flex items-center px-4 py-2 mb-1 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
                        <i class="fas fa-plus w-5 h-5"></i>
                        <span class="ml-3">Add Notice</span>
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 mb-1 text-white bg-black bg-opacity-30 rounded-md transition-colors duration-200 hover:bg-black hover:bg-opacity-40">
                        <i class="fas fa-list w-5 h-5"></i>
                        <span class="ml-3">Manage Notice</span>
                    </a>
                </div>
            </div>
            
            <a href="#" class="flex items-center px-4 py-3 mb-2 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
                <i class="fas fa-bullhorn w-5 h-5"></i>
                <span class="ml-3">Public Notice</span>
            </a>
            <a href="#" class="flex items-center px-4 py-3 mb-2 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
                <i class="fas fa-file-alt w-5 h-5"></i>
                <span class="ml-3">Pages</span>
            </a>
            <a href="#" class="flex items-center px-4 py-3 mb-2 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
                <i class="fas fa-chart-bar w-5 h-5"></i>
                <span class="ml-3">Reports</span>
            </a>
            <a href="#" class="flex items-center px-4 py-3 mb-2 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
                <i class="fas fa-search w-5 h-5"></i>
                <span class="ml-3">Search</span>
            </a>
        </nav>
        
        <!-- Logout at bottom -->
        <div class="absolute bottom-0 w-full p-4 border-t border-red-800">
            <a href="#" class="flex items-center px-4 py-3 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
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
            <h1 class="text-xl font-semibold text-highlight-yellow">Manage Notices</h1>
            <div class="flex items-center space-x-4">
                <div class="text-sm text-gray-400">
                    <a href="#" class="hover:text-white transition-colors duration-200">Dashboard</a>
                    <span class="mx-2">/</span>
                    <span class="text-white">Manage Notices</span>
                </div>
                <div class="w-8 h-8 bg-somaiya-red rounded-full flex items-center justify-center text-white font-medium">
                    A
                </div>
            </div>
        </div>
        
        <!-- Manage Notices Content -->
        <div class="p-6 animate-fade-in">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold mb-2">Notice Management</h2>
                    <p class="text-gray-400">View and manage all notices in the system.</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="#" class="bg-somaiya-red text-white px-4 py-2 rounded-md hover:bg-opacity-90 transition-colors duration-200 flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        <span>Add New Notice</span>
                    </a>
                </div>
            </div>
            
            <!-- Filter/Search Section -->
            <div class="mb-6 flex flex-col sm:flex-row gap-4">
                <div class="relative flex-1">
                    <input type="text" placeholder="Search notices..." class="w-full bg-dark-lighter border border-dark-border rounded-md py-2 px-4 pl-10 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
                <div class="flex gap-4">
                    <select class="bg-dark-lighter border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200">
                        <option value="all">All Classes</option>
                        <option value="1A">Class 1A</option>
                        <option value="1B">Class 1B</option>
                        <option value="2A">Class 2A</option>
                        <option value="2B">Class 2B</option>
                        <option value="10A">Class 10A</option>
                        <option value="10B">Class 10B</option>
                    </select>
                    <select class="bg-dark-lighter border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200">
                        <option value="all">All Sections</option>
                        <option value="A">Section A</option>
                        <option value="B">Section B</option>
                    </select>
                </div>
            </div>
            
            <!-- Notices Table -->
            <div class="overflow-x-auto">
                <div class="border border-dark-border rounded-lg overflow-hidden">
                    <!-- Table Header -->
                    <div class="bg-dark-lighter text-gray-300 text-sm font-medium">
                        <div class="grid grid-cols-12 gap-4 px-6 py-3">
                            <div class="col-span-1">S.No</div>
                            <div class="col-span-5">Notice Title</div>
                            <div class="col-span-2">Class</div>
                            <div class="col-span-2">Section</div>
                            <div class="col-span-1">Notice Date</div>
                            <div class="col-span-1 text-right">Action</div>
                        </div>
                    </div>
                    
                    <!-- Table Body -->
                    <div class="divide-y divide-dark-border">
                        <!-- Row 1 -->
                        <div class="grid grid-cols-12 gap-4 px-6 py-4 hover:bg-dark-lighter transition-colors duration-200">
                            <div class="col-span-1 text-gray-400">01</div>
                            <div class="col-span-5 font-medium">Annual Sports Day Announcement</div>
                            <div class="col-span-2 text-gray-300">All Classes</div>
                            <div class="col-span-2 text-gray-300">All Sections</div>
                            <div class="col-span-1 text-gray-300">20 Jun 2025</div>
                            <div class="col-span-1 text-right">
                                <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition-colors duration-200 shadow-sm hover:shadow">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Row 2 -->
                        <div class="grid grid-cols-12 gap-4 px-6 py-4 hover:bg-dark-lighter transition-colors duration-200">
                            <div class="col-span-1 text-gray-400">02</div>
                            <div class="col-span-5 font-medium">Science Exhibition Registration</div>
                            <div class="col-span-2 text-gray-300">10</div>
                            <div class="col-span-2 text-gray-300">A, B</div>
                            <div class="col-span-1 text-gray-300">21 Jun 2025</div>
                            <div class="col-span-1 text-right">
                                <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition-colors duration-200 shadow-sm hover:shadow">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Row 3 -->
                        <div class="grid grid-cols-12 gap-4 px-6 py-4 hover:bg-dark-lighter transition-colors duration-200">
                            <div class="col-span-1 text-gray-400">03</div>
                            <div class="col-span-5 font-medium">Parent-Teacher Meeting Schedule</div>
                            <div class="col-span-2 text-gray-300">All Classes</div>
                            <div class="col-span-2 text-gray-300">All Sections</div>
                            <div class="col-span-1 text-gray-300">22 Jun 2025</div>
                            <div class="col-span-1 text-right">
                                <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition-colors duration-200 shadow-sm hover:shadow">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Row 4 -->
                        <div class="grid grid-cols-12 gap-4 px-6 py-4 hover:bg-dark-lighter transition-colors duration-200">
                            <div class="col-span-1 text-gray-400">04</div>
                            <div class="col-span-5 font-medium">Mathematics Olympiad Registration</div>
                            <div class="col-span-2 text-gray-300">9, 10</div>
                            <div class="col-span-2 text-gray-300">All Sections</div>
                            <div class="col-span-1 text-gray-300">23 Jun 2025</div>
                            <div class="col-span-1 text-right">
                                <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition-colors duration-200 shadow-sm hover:shadow">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Row 5 -->
                        <div class="grid grid-cols-12 gap-4 px-6 py-4 hover:bg-dark-lighter transition-colors duration-200">
                            <div class="col-span-1 text-gray-400">05</div>
                            <div class="col-span-5 font-medium">Holiday Announcement: Independence Day</div>
                            <div class="col-span-2 text-gray-300">All Classes</div>
                            <div class="col-span-2 text-gray-300">All Sections</div>
                            <div class="col-span-1 text-gray-300">24 Jun 2025</div>
                            <div class="col-span-1 text-right">
                                <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition-colors duration-200 shadow-sm hover:shadow">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Row 6 -->
                        <div class="grid grid-cols-12 gap-4 px-6 py-4 hover:bg-dark-lighter transition-colors duration-200">
                            <div class="col-span-1 text-gray-400">06</div>
                            <div class="col-span-5 font-medium">Computer Lab Schedule Change</div>
                            <div class="col-span-2 text-gray-300">11, 12</div>
                            <div class="col-span-2 text-gray-300">A</div>
                            <div class="col-span-1 text-gray-300">25 Jun 2025</div>
                            <div class="col-span-1 text-right">
                                <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition-colors duration-200 shadow-sm hover:shadow">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Row 7 -->
                        <div class="grid grid-cols-12 gap-4 px-6 py-4 hover:bg-dark-lighter transition-colors duration-200">
                            <div class="col-span-1 text-gray-400">07</div>
                            <div class="col-span-5 font-medium">Library Book Return Reminder</div>
                            <div class="col-span-2 text-gray-300">All Classes</div>
                            <div class="col-span-2 text-gray-300">All Sections</div>
                            <div class="col-span-1 text-gray-300">26 Jun 2025</div>
                            <div class="col-span-1 text-right">
                                <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition-colors duration-200 shadow-sm hover:shadow">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Row 8 -->
                        <div class="grid grid-cols-12 gap-4 px-6 py-4 hover:bg-dark-lighter transition-colors duration-200">
                            <div class="col-span-1 text-gray-400">08</div>
                            <div class="col-span-5 font-medium">School Trip Permission Forms</div>
                            <div class="col-span-2 text-gray-300">8, 9</div>
                            <div class="col-span-2 text-gray-300">All Sections</div>
                            <div class="col-span-1 text-gray-300">27 Jun 2025</div>
                            <div class="col-span-1 text-right">
                                <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition-colors duration-200 shadow-sm hover:shadow">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="mt-6 flex justify-between items-center">
                <div class="text-sm text-gray-400">
                    Showing <span class="font-medium text-white">1</span> to <span class="font-medium text-white">8</span> of <span class="font-medium text-white">24</span> entries
                </div>
                <div class="flex items-center space-x-2">
                    <button class="w-8 h-8 flex items-center justify-center rounded border border-dark-border text-gray-400 hover:border-somaiya-red hover:text-somaiya-red transition-colors duration-200">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </button>
                    <button class="w-8 h-8 flex items-center justify-center rounded bg-somaiya-red text-white">1</button>
                    <button class="w-8 h-8 flex items-center justify-center rounded border border-dark-border text-gray-400 hover:border-somaiya-red hover:text-somaiya-red transition-colors duration-200">2</button>
                    <button class="w-8 h-8 flex items-center justify-center rounded border border-dark-border text-gray-400 hover:border-somaiya-red hover:text-somaiya-red transition-colors duration-200">3</button>
                    <button class="w-8 h-8 flex items-center justify-center rounded border border-dark-border text-gray-400 hover:border-somaiya-red hover:text-somaiya-red transition-colors duration-200">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>
            
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
    
    // Class dropdown toggle
    const classDropdownBtn = document.getElementById('class-dropdown-btn');
    const classDropdown = document.getElementById('class-dropdown');
    const classDropdownIcon = document.getElementById('class-dropdown-icon');
    
    classDropdownBtn.addEventListener('click', () => {
        classDropdown.classList.toggle('hidden');
        classDropdownIcon.classList.toggle('rotate-180');
    });
    
    // Students dropdown toggle
    const studentsDropdownBtn = document.getElementById('students-dropdown-btn');
    const studentsDropdown = document.getElementById('students-dropdown');
    const studentsDropdownIcon = document.getElementById('students-dropdown-icon');
    
    studentsDropdownBtn.addEventListener('click', () => {
        studentsDropdown.classList.toggle('hidden');
        studentsDropdownIcon.classList.toggle('rotate-180');
    });
    
    // Homework dropdown toggle
    const homeworkDropdownBtn = document.getElementById('homework-dropdown-btn');
    const homeworkDropdown = document.getElementById('homework-dropdown');
    const homeworkDropdownIcon = document.getElementById('homework-dropdown-icon');
    
    homeworkDropdownBtn.addEventListener('click', () => {
        homeworkDropdown.classList.toggle('hidden');
        homeworkDropdownIcon.classList.toggle('rotate-180');
    });
    
    // Notice dropdown toggle (already open by default)
    const noticeDropdownBtn = document.getElementById('notice-dropdown-btn');
    const noticeDropdown = document.getElementById('notice-dropdown');
    const noticeDropdownIcon = document.getElementById('notice-dropdown-icon');
    
    noticeDropdownBtn.addEventListener('click', () => {
        noticeDropdown.classList.toggle('hidden');
        noticeDropdownIcon.classList.toggle('rotate-180');
    });
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', (e) => {
        const isMobile = window.innerWidth < 768;
        const isOutsideSidebar = !sidebar  (e) => {
        const isMobile = window.innerWidth < 768;
        const isOutsideSidebar = !sidebar.contains(e.target) && !sidebarToggle.contains(e.target);
        
        if (isMobile && isOutsideSidebar && !sidebar.classList.contains('-translate-x-full')) {
            sidebar.classList.add('-translate-x-full');
            sidebarIcon.classList.remove('fa-times');
            sidebarIcon.classList.add('fa-bars');
        }
    });
</script>
</body>
</html>