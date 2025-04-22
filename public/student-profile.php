<?php
session_start();
if (!isset($_SESSION['sturecmsstuid'])) {
    header("Location: login.php");
    exit();
}
include("../includes/dbconnection.php");

$sid = $_SESSION['sturecmsstuid'];
$sql = "SELECT 
    tblstudent.StudentName, tblstudent.StudentEmail, tblstudent.StudentClass,
    tblstudent.Gender, tblstudent.DOB, tblstudent.StuID, tblstudent.FatherName,
    tblstudent.MotherName, tblstudent.ContactNumber, tblstudent.AltenateNumber,
    tblstudent.Address, tblstudent.UserName, tblstudent.Image, tblstudent.DateofAdmission,
    tblclass.ClassName, tblclass.Section 
    FROM tblstudent 
    JOIN tblclass ON tblclass.ID = tblstudent.StudentClass 
    WHERE tblstudent.StuID = :sid";
$query = $dbh->prepare($sql);
$query->bindParam(':sid', $sid, PDO::PARAM_STR);
$query->execute();
$row = $query->fetch(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Padhle - Student Profile</title>
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
                        'card-dark': '#000000',
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
        <aside id="sidebar" class="bg-dark-lighter w-64 border-r border-dark-border h-full flex-shrink-0 fixed inset-y-0 left-0 z-30 transition-transform duration-300 ease-in-out transform md:translate-x-0 -translate-x-full">
            <!-- Logo -->
            <div class="p-4 border-b border-dark-border">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-somaiya-red rounded-md flex items-center justify-center text-white font-bold text-xl">P</div>
                    <span class="ml-3 text-xl font-bold text-highlight-yellow">Padhle</span>
                </div>
            </div>
            
            <!-- Navigation Links -->
            <nav class="mt-6 px-4">
                <a href="./dashboard.php" class="flex items-center px-4 py-3 mb-2 text-gray-400 rounded-md transition-colors duration-200 hover:bg-white hover:text-black">
                    <i class="fas fa-home w-5 h-5"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
                <a href="./homework.php" class="flex items-center px-4 py-3 mb-2 text-gray-400 rounded-md transition-colors duration-200 hover:bg-white hover:text-black">
                    <i class="fas fa-book w-5 h-5"></i>
                    <span class="ml-3">Homework</span>
                </a>
                <a href="./notices.php" class="flex items-center px-4 py-3 mb-2 text-gray-400 rounded-md transition-colors duration-200 hover:bg-white hover:text-black">
                    <i class="fas fa-bell w-5 h-5"></i>
                    <span class="ml-3">Notices</span>
                </a>
                <a href="./student-profile.php" class="flex items-center px-4 py-3 mb-2 text-white bg-somaiya-red bg-opacity-100 rounded-md transition-colors duration-200 hover:bg-somaiya-red hover:bg-opacity-30">
                    <i class="fas fa-user w-5 h-5"></i>
                    <span class="ml-3">Profile</span>
                </a>
            </nav>
            
            <!-- Logout at bottom -->
            <div class="absolute bottom-0 w-full p-4 border-t border-dark-border">
                <a href="./logout.php" class="flex items-center px-4 py-3 text-gray-400 rounded-md transition-colors duration-200 hover:bg-dark-border hover:text-white">
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
            <!-- Top bar with toggle button -->
            <div class="bg-dark-lighter border-b border-dark-border p-4 flex justify-between items-center sticky top-0 z-20">
                <h1 class="text-xl font-semibold text-highlight-yellow">Profile</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-400"><?php echo htmlentities($_SESSION['studentname']); ?></span>
                    <div class="w-8 h-8 bg-somaiya-red rounded-full flex items-center justify-center text-white font-medium">
                        <?php 
                        $nameParts = explode(' ', $row->StudentName);
                        echo strtoupper(substr($nameParts[0], 0, 1)) . strtoupper(substr($nameParts[1] ?? '', 0, 1)); 
                        ?>
                    </div>
                </div>
            </div>
            
            <!-- Profile Content -->
            <div class="p-6 animate-fade-in">
                <!-- Profile Header with Avatar -->
                <div class="bg-card-dark border border-dark-border rounded-lg p-6 mb-8 flex flex-col md:flex-row items-center md:items-start gap-6">
                    <div class="w-24 h-24 bg-somaiya-red rounded-full flex items-center justify-center text-white text-3xl font-bold">
                        <?php 
                        $nameParts = explode(' ', $row->StudentName);
                        echo strtoupper(substr($nameParts[0], 0, 1)) . strtoupper(substr($nameParts[1] ?? '', 0, 1)); 
                        ?>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold mb-1 text-center md:text-left"><?php echo htmlentities($row->StudentName); ?></h2>
                        <p class="text-gray-400 mb-3 text-center md:text-left"><?php echo htmlentities($row->ClassName) . " - " . htmlentities($row->Section); ?></p>
                        <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                            <span class="px-3 py-1 bg-somaiya-red bg-opacity-10 text-somaiya-red text-xs rounded-full border border-somaiya-red">
                                Student ID: <?php echo htmlentities($row->StuID); ?>
                            </span>
                            <span class="px-3 py-1 bg-dark-lighter text-gray-300 text-xs rounded-full border border-dark-border">
                                Joined: <?php echo date("M Y", strtotime($row->DateofAdmission)); ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Student Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Personal Information -->
                    <div class="bg-card-dark border border-dark-border rounded-lg p-4 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1">
                        <h3 class="text-highlight-yellow font-medium mb-3 pb-2 border-b border-dark-border">Personal Information</h3>
                        <div class="space-y-3">
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-400">Full Name</span>
                                <span class="text-sm"><?php echo htmlentities($row->StudentName); ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-400">Email Address</span>
                                <span class="text-sm"><?php echo htmlentities($row->StudentEmail); ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-400">Gender</span>
                                <span class="text-sm"><?php echo htmlentities($row->Gender); ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-400">Date of Birth</span>
                                <span class="text-sm"><?php echo htmlentities($row->DOB); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Information -->
                    <div class="bg-card-dark border border-dark-border rounded-lg p-4 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1">
                        <h3 class="text-highlight-yellow font-medium mb-3 pb-2 border-b border-dark-border">Academic Information</h3>
                        <div class="space-y-3">
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-400">Student ID</span>
                                <span class="text-sm"><?php echo htmlentities($row->StuID); ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-400">Class</span>
                                <span class="text-sm"><?php echo htmlentities($row->ClassName . " - " . $row->Section); ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-400">Date of Admission</span>
                                <span class="text-sm"><?php echo htmlentities($row->DateofAdmission); ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-400">Username</span>
                                <span class="text-sm"><?php echo htmlentities($row->UserName); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="bg-card-dark border border-dark-border rounded-lg p-4 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1">
                        <h3 class="text-highlight-yellow font-medium mb-3 pb-2 border-b border-dark-border">Contact Information</h3>
                        <div class="space-y-3">
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-400">Contact Number</span>
                                <span class="text-sm"><?php echo htmlentities($row->ContactNumber); ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-400">Alternate Number</span>
                                <span class="text-sm"><?php echo htmlentities($row->AltenateNumber); ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-400">Address</span>
                                <span class="text-sm"><?php echo htmlentities($row->Address); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Family Information -->
                    <div class="bg-card-dark border border-dark-border rounded-lg p-4 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1">
                        <h3 class="text-highlight-yellow font-medium mb-3 pb-2 border-b border-dark-border">Family Information</h3>
                        <div class="space-y-3">
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-400">Father's Name</span>
                                <span class="text-sm"><?php echo htmlentities($row->FatherName); ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-400">Mother's Name</span>
                                <span class="text-sm"><?php echo htmlentities($row->MotherName); ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-400">Parent's Contact</span>
                                <span class="text-sm"><?php echo htmlentities($row->ContactNumber); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buttons Section -->
                <div class="mt-6 flex flex-col md:flex-row justify-center md:justify-end gap-4">
                    <!-- Change Password Button -->
                    <a href="./change-password.php" class="px-4 py-2 bg-yellow-400 text-black rounded-md transition-all duration-300 hover:bg-yellow-300 flex items-center gap-2">
                        <i class="fas fa-key"></i>
                        <span>Change Password</span>
                    </a>

                    <!-- Edit Profile Button -->
                    <button class="px-4 py-2 bg-somaiya-red text-white rounded-md transition-all duration-300 hover:bg-opacity-90 flex items-center gap-2">
                        <i class="fas fa-edit"></i>
                        <span>Edit Profile</span>
                    </button>
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