<?php
session_start();
include('../includes/dbconnection.php');

if (!isset($_SESSION['sturecmsaid'])) {
    header('location:login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Padhle - Admin Dashboard</title>
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
                        'dark-lighter': '#000000',
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
            <nav class="mt-6 px-4">
                <a href="#" class="flex items-center px-4 py-3 mb-2 text-white bg-red-800 rounded-md transition-colors duration-200 hover:bg-red-900">
                    <i class="fas fa-tachometer-alt w-5 h-5"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
                <a href="#" class="flex items-center px-4 py-3 mb-2 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
                    <i class="fas fa-chalkboard w-5 h-5"></i>
                    <span class="ml-3">Class</span>
                </a>
                <a href="#" class="flex items-center px-4 py-3 mb-2 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
                    <i class="fas fa-user-graduate w-5 h-5"></i>
                    <span class="ml-3">Students</span>
                </a>
                <a href="#" class="flex items-center px-4 py-3 mb-2 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
                    <i class="fas fa-book w-5 h-5"></i>
                    <span class="ml-3">Homework</span>
                </a>
                <a href="#" class="flex items-center px-4 py-3 mb-2 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
                    <i class="fas fa-bell w-5 h-5"></i>
                    <span class="ml-3">Notice</span>
                </a>
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
                <h1 class="text-xl font-semibold text-highlight-yellow">Admin Dashboard</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-400">Admin | admin@gmail.com</span>
                    <div class="w-8 h-8 bg-somaiya-red rounded-full flex items-center justify-center text-white font-medium">
                        A
                    </div>
                </div>
            </div>
            
            <!-- Dashboard Content -->
            <div class="p-6 animate-fade-in">
                <!-- Welcome Section -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold mb-2">Welcome Admin!</h2>
                    <p class="text-gray-400">Here's an overview of your student portal statistics.</p>
                </div>
                
                <!-- Report Summary Section -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4 text-highlight-yellow">Report Summary</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
                        <!-- Summary Box 1: Classes -->
                        <div class="bg-dark-lighter border border-dark-border rounded-lg p-4 transition-all duration-300 hover:border-somaiya-red hover:shadow-md">
                            <div class="flex items-start justify-between">
                                <div>
                                <?php
                                    $sql1 = "SELECT ID FROM tblclass";
                                    $query1 = $dbh->prepare($sql1);
                                    $query1->execute();
                                    $totclass = $query1->rowCount();
                                    ?>
                                    <?php echo htmlentities($totclass); ?></p>
                                    <p class="text-3xl font-bold mb-1">
                                    <p class="text-gray-400 text-sm">Total Classes</p>
                                </div>
                                <div class="w-10 h-10 bg-somaiya-red bg-opacity-20 rounded-full flex items-center justify-center text-somaiya-red">
                                    <i class="fas fa-chalkboard"></i>
                                </div>
                            </div>
                            <div class="mt-4 pt-3 border-t border-dark-border">
                                <a href="#" class="text-somaiya-red text-sm hover:text-highlight-yellow transition-colors duration-200 flex items-center">
                                    <span>View Classes</span>
                                    <i class="fas fa-arrow-right ml-1 text-xs"></i>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Summary Box 2: Students -->
                        <div class="bg-dark-lighter border border-dark-border rounded-lg p-4 transition-all duration-300 hover:border-somaiya-red hover:shadow-md">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-3xl font-bold mb-1">156</p>
                                    <p class="text-gray-400 text-sm">Total Students</p>
                                </div>
                                <div class="w-10 h-10 bg-blue-500 bg-opacity-20 rounded-full flex items-center justify-center text-blue-500">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                            </div>
                            <div class="mt-4 pt-3 border-t border-dark-border">
                                <a href="#" class="text-somaiya-red text-sm hover:text-highlight-yellow transition-colors duration-200 flex items-center">
                                    <span>View Students</span>
                                    <i class="fas fa-arrow-right ml-1 text-xs"></i>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Summary Box 3: Homework -->
                        <div class="bg-dark-lighter border border-dark-border rounded-lg p-4 transition-all duration-300 hover:border-somaiya-red hover:shadow-md">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-3xl font-bold mb-1">24</p>
                                    <p class="text-gray-400 text-sm">Active Homework</p>
                                </div>
                                <div class="w-10 h-10 bg-green-500 bg-opacity-20 rounded-full flex items-center justify-center text-green-500">
                                    <i class="fas fa-book"></i>
                                </div>
                            </div>
                            <div class="mt-4 pt-3 border-t border-dark-border">
                                <a href="#" class="text-somaiya-red text-sm hover:text-highlight-yellow transition-colors duration-200 flex items-center">
                                    <span>View Homework</span>
                                    <i class="fas fa-arrow-right ml-1 text-xs"></i>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Summary Box 4: Notices -->
                        <div class="bg-dark-lighter border border-dark-border rounded-lg p-4 transition-all duration-300 hover:border-somaiya-red hover:shadow-md">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-3xl font-bold mb-1">12</p>
                                    <p class="text-gray-400 text-sm">Active Notices</p>
                                </div>
                                <div class="w-10 h-10 bg-purple-500 bg-opacity-20 rounded-full flex items-center justify-center text-purple-500">
                                    <i class="fas fa-bell"></i>
                                </div>
                            </div>
                            <div class="mt-4 pt-3 border-t border-dark-border">
                                <a href="#" class="text-somaiya-red text-sm hover:text-highlight-yellow transition-colors duration-200 flex items-center">
                                    <span>View Notices</span>
                                    <i class="fas fa-arrow-right ml-1 text-xs"></i>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Summary Box 5: Reports -->
                        <div class="bg-dark-lighter border border-dark-border rounded-lg p-4 transition-all duration-300 hover:border-somaiya-red hover:shadow-md">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-3xl font-bold mb-1">5</p>
                                    <p class="text-gray-400 text-sm">New Reports</p>
                                </div>
                                <div class="w-10 h-10 bg-yellow-500 bg-opacity-20 rounded-full flex items-center justify-center text-yellow-500">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                            </div>
                            <div class="mt-4 pt-3 border-t border-dark-border">
                                <a href="#" class="text-somaiya-red text-sm hover:text-highlight-yellow transition-colors duration-200 flex items-center">
                                    <span>View Reports</span>
                                    <i class="fas fa-arrow-right ml-1 text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activity Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Recent Students -->
                    <div class="bg-dark-lighter border border-dark-border rounded-lg overflow-hidden">
                        <div class="p-4 border-b border-dark-border flex justify-between items-center">
                            <h3 class="font-semibold">Recent Students</h3>
                            <a href="#" class="text-somaiya-red text-sm hover:text-highlight-yellow transition-colors duration-200">View All</a>
                        </div>
                        <div class="divide-y divide-dark-border">
                            <div class="p-4 flex items-center justify-between hover:bg-dark transition-colors duration-200">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-medium mr-3">
                                        RS
                                    </div>
                                    <div>
                                        <p class="font-medium">Rahul Sharma</p>
                                        <p class="text-xs text-gray-400">Computer Science - Year 3</p>
                                    </div>
                                </div>
                                <span class="text-xs text-gray-400">Added 2 days ago</span>
                            </div>
                            <div class="p-4 flex items-center justify-between hover:bg-dark transition-colors duration-200">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-xs font-medium mr-3">
                                        AP
                                    </div>
                                    <div>
                                        <p class="font-medium">Ananya Patel</p>
                                        <p class="text-xs text-gray-400">Physics - Year 2</p>
                                    </div>
                                </div>
                                <span class="text-xs text-gray-400">Added 3 days ago</span>
                            </div>
                            <div class="p-4 flex items-center justify-between hover:bg-dark transition-colors duration-200">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center text-white text-xs font-medium mr-3">
                                        VK
                                    </div>
                                    <div>
                                        <p class="font-medium">Vikram Kumar</p>
                                        <p class="text-xs text-gray-400">Mathematics - Year 1</p>
                                    </div>
                                </div>
                                <span class="text-xs text-gray-400">Added 5 days ago</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Homework -->
                    <div class="bg-dark-lighter border border-dark-border rounded-lg overflow-hidden">
                        <div class="p-4 border-b border-dark-border flex justify-between items-center">
                            <h3 class="font-semibold">Recent Homework</h3>
                            <a href="#" class="text-somaiya-red text-sm hover:text-highlight-yellow transition-colors duration-200">View All</a>
                        </div>
                        <div class="divide-y divide-dark-border">
                            <div class="p-4 hover:bg-dark transition-colors duration-200">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-medium">Calculus Assignment 3</h4>
                                    <span class="px-2 py-1 bg-green-900 bg-opacity-20 text-green-400 text-xs rounded">Active</span>
                                </div>
                                <p class="text-sm text-gray-400 mb-2">Mathematics - Due: Dec 15, 2025</p>
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-gray-400">Assigned to 32 students</span>
                                    <span class="text-gray-400">Posted 2 days ago</span>
                                </div>
                            </div>
                            <div class="p-4 hover:bg-dark transition-colors duration-200">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-medium">Mechanics Lab Report</h4>
                                    <span class="px-2 py-1 bg-green-900 bg-opacity-20 text-green-400 text-xs rounded">Active</span>
                                </div>
                                <p class="text-sm text-gray-400 mb-2">Physics - Due: Dec 10, 2025</p>
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-gray-400">Assigned to 28 students</span>
                                    <span class="text-gray-400">Posted 3 days ago</span>
                                </div>
                            </div>
                            <div class="p-4 hover:bg-dark transition-colors duration-200">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-medium">Algorithm Analysis</h4>
                                    <span class="px-2 py-1 bg-green-900 bg-opacity-20 text-green-400 text-xs rounded">Active</span>
                                </div>
                                <p class="text-sm text-gray-400 mb-2">Computer Science - Due: Dec 18, 2025</p>
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-gray-400">Assigned to 45 students</span>
                                    <span class="text-gray-400">Posted 4 days ago</span>
                                </div>
                            </div>
                        </div>
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
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
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