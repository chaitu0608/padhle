<?php
session_start();
if (!isset($_SESSION['sturecmsstuid'])) {
    header("Location: login.php");
    exit();
}
include("../includes/dbconnection.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Padhle - Student Dashboard</title>
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
                        'dark-lighter': '#000000',   //'#1e1e1e'
                        'dark-border': '#333333',    //'#333333'
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
            width: 10px;
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

    <!-- GSAP Library -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.2/dist/gsap.min.js"></script> -->
</head>
<body class="bg-dark text-white font-sans">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="bg-dark-lighter w-64 border-r border-dark-border h-full flex-shrink-0 fixed inset-y-0 left-0 z-30 transition-transform duration-300 ease-in-out transform md:translate-x-0 -translate-x-full">
            <!-- Logo -->
            <div class="p-4 border-b border-dark-border">
                <a href="./dashboard.php" class="flex items-center">
                    <div class="w-10 h-10 bg-somaiya-red rounded-md flex items-center justify-center text-white font-bold text-xl">P</div>
                    <span class="ml-3 text-xl font-bold text-highlight-yellow">Padhle</span>
                </a>
            </div>
            
            <!-- Navigation Links -->
            <nav class="mt-6 px-4">
                <a href="./dashboard.php" class="flex items-center px-4 py-3 mb-2 text-white bg-somaiya-red bg-opacity-100 rounded-md transition-colors duration-200 hover:bg-somaiya-red hover:bg-opacity-20">
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
                <a href="./student-profile.php" class="flex items-center px-4 py-3 mb-2 text-gray-400 rounded-md transition-colors duration-200 hover:bg-white hover:text-black">
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
                <h1 class="text-xl font-semibold text-highlight-yellow">Student Dashboard</h1>
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
            
            <!-- Dashboard Content -->
            <div class="p-6 animate-fade-in">
                <!-- Welcome Section -->
                <div class="bg-dark-lighter border border-dark-border rounded-lg p-6 mb-8 transform transition-all duration-300 hover:shadow-lg">
                    <h2 class="text-2xl font-bold mb-2">Welcome back, <?php echo htmlentities($_SESSION['studentname']); ?></h2>
                    <p class="text-gray-400">You have 2 pending assignments and 3 new notices.</p>
                </div>
                
                <!-- Notices Section -->
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-highlight-yellow">Latest Notices</h2>
                        <a href="./notices.php" class="text-somaiya-red hover:underline text-sm transition-colors duration-200">View All</a>
                    </div>
                    
                    <!-- <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"> -->
                        <!-- Notice Card 1 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"> <!--Dynamic cards-->
                            <?php
                            $sql = "SELECT * FROM tblpublicnotice ORDER BY CreationDate DESC LIMIT 3";
                            $query = $dbh->prepare($sql);
                            $query->execute();
                            $results = $query->fetchAll(PDO::FETCH_OBJ);

                            if ($query->rowCount() > 0) {
                                foreach ($results as $row) {
                                echo '
                                <div class="card bg-dark-lighter border border-dark-border rounded-lg overflow-hidden transform transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                                    <div class="p-4 border-b border-dark-border">
                                    <h3 class="font-semibold mb-1">' . htmlentities($row->NoticeTitle) . '</h3>
                                    <span class="text-xs text-gray-400">' . htmlentities($row->CreationDate) . '</span>
                                    </div>
                                    <div class="p-4">
                                    <p class="text-sm text-gray-300 mb-3">' . htmlentities($row->NoticeMessage) . '</p>
                                    <a href="#" class="text-somaiya-red text-sm hover:underline transition-colors duration-200">View Details</a>
                                    </div>
                                </div>';
                                }
                            } else {
                                echo '<p class="text-gray-500">No notices available.</p>';
                            }
                            ?>
                        </div>
                </div>
                
                <!-- Homework Section -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-highlight-yellow">Homework</h2>
                        <a href="./homework.php" class="text-somaiya-red hover:underline text-sm transition-colors duration-200">View All</a>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Dynamic Cards -->
                        <?php
                        $uid = $_SESSION['sturecmsuid'];

                        // First, get the classId of the student
                        $sql = "SELECT StudentClass FROM tblstudent WHERE ID = :uid";
                        $query = $dbh->prepare($sql);
                        $query->bindParam(':uid', $uid, PDO::PARAM_INT);
                        $query->execute();
                        $student = $query->fetch(PDO::FETCH_OBJ);
                        $classId = $student->StudentClass;

                        // Now fetch homework assigned to that class
                        $sql = "SELECT * FROM tblhomework WHERE classId = :classId ORDER BY postingDate DESC LIMIT 4";
                        $query = $dbh->prepare($sql);
                        $query->bindParam(':classId', $classId, PDO::PARAM_INT);
                        $query->execute();
                        $results = $query->fetchAll(PDO::FETCH_OBJ);

                        if ($query->rowCount() > 0) {
                            foreach ($results as $row) {
                                echo '<div class="card bg-dark-lighter border border-dark-border rounded-lg overflow-hidden transform transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                                        <div class="p-4 border-b border-dark-border flex justify-between items-start">
                                            <div>
                                                <h3 class="font-semibold mb-1">' . htmlentities($row->homeworkTitle) . '</h3>
                                                <span class="text-xs text-gray-400">Posted: ' . htmlentities($row->postingDate) . '</span>
                                            </div>
                                            <span class="px-2 py-1 bg-somaiya-red bg-opacity-20 text-somaiya-red text-xs rounded-md">Pending</span>
                                        </div>
                                        <div class="p-4 flex justify-between items-center">
                                            <div class="flex items-center text-sm text-gray-400">
                                                <i class="fas fa-calendar-alt mr-2"></i>
                                                Due: ' . htmlentities($row->lastDateofSubmission) . '
                                            </div>
                                            <button class="px-3 py-1 bg-somaiya-red text-white text-sm rounded transition-colors duration-200 hover:bg-opacity-90">Submit</button>
                                        </div>
                                    </div>';
                            }
                        } else {
                            echo '<p class="text-gray-400">No homework assigned for your class.</p>';
                        }
                        ?> 
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
    <!--GSAP Animations-->
    <!-- <script>
        document.addEventListener('DOMContentLoaded', () => {
        gsap.utils.toArray(".card").forEach((card, i) => {
            gsap.fromTo(card, {
            opacity: 0,
            y: 30
            }, {
            opacity: 1,
            y: 0,
            delay: i * 0.05,  // small delay instead of stagger
            duration: 0.4,
            ease: "power2.out"
            });
         });
        });
      </script> -->
</body>
</html>