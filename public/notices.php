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
    <title>Padhle - Notices</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts - Orbitron for futuristic headings -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        dark: '#121212',
                        'dark-lighter': '#000000',
                        'dark-border': '#333333',
                        'neon-pink': '#ff5c8d',
                        'neon-blue': '#00f0ff',
                        'neon-purple': '#9b5de5',
                        'neon-yellow': '#fff700',
                        'somaiya-red': '#D90429',
                        'highlight-yellow': '#FFD700',
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                        orbitron: ['Orbitron', 'sans-serif'],
                    },
                    animation: {
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'glow': 'glow 2s ease-in-out infinite alternate',
                    },
                    keyframes: {
                        glow: {
                            '0%': { 
                                'text-shadow': '0 0 5px rgba(255, 255, 255, 0.5), 0 0 10px rgba(255, 255, 255, 0.3)'
                            },
                            '100%': { 
                                'text-shadow': '0 0 10px rgba(255, 255, 255, 0.8), 0 0 20px rgba(255, 255, 255, 0.5), 0 0 30px rgba(0, 240, 255, 0.4)'
                            }
                        }
                    },
                    boxShadow: {
                        'neon-pink': '0 0 5px rgba(255, 92, 141, 0.5), 0 0 10px rgba(255, 92, 141, 0.3)',
                        'neon-blue': '0 0 5px rgba(0, 240, 255, 0.5), 0 0 10px rgba(0, 240, 255, 0.3)',
                        'neon-purple': '0 0 5px rgba(155, 93, 229, 0.5), 0 0 10px rgba(155, 93, 229, 0.3)',
                        'neon-yellow': '0 0 5px rgba(255, 247, 0, 0.5), 0 0 10px rgba(255, 247, 0, 0.3)',
                    }
                }
            }
        }
    </script>
    <style>
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
            background: #ff5c8d;
        }
        
        /* Neon border glow effects */
        .glow-pink {
            box-shadow: 0 0 5px rgba(255, 92, 141, 0.5), 0 0 10px rgba(255, 92, 141, 0.3);
        }
        .glow-blue {
            box-shadow: 0 0 5px rgba(0, 240, 255, 0.5), 0 0 10px rgba(0, 240, 255, 0.3);
        }
        .glow-purple {
            box-shadow: 0 0 5px rgba(155, 93, 229, 0.5), 0 0 10px rgba(155, 93, 229, 0.3);
        }
        .glow-yellow {
            box-shadow: 0 0 5px rgba(255, 247, 0, 0.5), 0 0 10px rgba(255, 247, 0, 0.3);
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
                <a href="./notices.php" class="flex items-center px-4 py-3 mb-2 text-white bg-somaiya-red bg-opacity-100 rounded-md transition-colors duration-200 hover:bg-somaiya-red hover:bg-opacity-20">
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
                <a href="#" class="flex items-center px-4 py-3 text-gray-400 rounded-md transition-colors duration-200 hover:bg-dark-border hover:text-white">
                    <i class="fas fa-sign-out-alt w-5 h-5"></i>
                    <span class="ml-3">Logout</span>
                </a>
            </div>
        </aside>
        
        <!-- Mobile sidebar toggle -->
        <div class="fixed bottom-4 right-4 md:hidden z-40">
            <button id="sidebar-toggle" class="bg-neon-pink text-white p-3 rounded-full shadow-lg transition-transform duration-200 hover:scale-105 focus:outline-none">
                <i id="sidebar-icon" class="fas fa-bars"></i>
            </button>
        </div>
        
        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto md:ml-64 transition-all duration-300 ease-in-out">
            <!-- Top bar with toggle button -->
            <div class="bg-dark-lighter border-b border-dark-border p-4 flex justify-between items-center sticky top-0 z-20">
                <h1 class="text-xl font-orbitron font-semibold text-highlight-yellow animate-glow">Notices</h1>
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
            
            <!-- Notices Content -->
            <div class="p-6">
                <!-- Header Section -->
                <div class="mb-8">
                    <h2 class="text-2xl font-orbitron mb-2 bg-gradient-to-r from-neon-blue to-neon-purple bg-clip-text text-transparent">
                        Latest Announcements
                    </h2>
                    <p class="text-gray-400">Stay updated with the latest notices and announcements from your institution.</p>
                </div>
                
                <!-- Filter/Search Section -->
                <div class="mb-8 flex flex-col sm:flex-row gap-4">
                    <div class="relative flex-1">
                        <input type="text" placeholder="Search notices..." class="w-full bg-dark-lighter border border-dark-border rounded-md py-2 px-4 pl-10 text-white focus:outline-none focus:border-neon-blue transition-colors duration-200">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <select class="bg-dark-lighter border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-neon-blue transition-colors duration-200">
                        <option value="all">All Categories</option>
                        <option value="academic">Academic</option>
                        <option value="events">Events</option>
                        <option value="exams">Exams</option>
                        <option value="holidays">Holidays</option>
                    </select>
                </div>
                
                <!-- Notices Timeline -->
                <div class="space-y-6">
                    <?php
                    $sql = "SELECT * FROM tblnotice ORDER BY CreationDate DESC";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $notices = $query->fetchAll(PDO::FETCH_OBJ);

                    $colors = ['neon-pink', 'neon-blue', 'neon-purple', 'neon-yellow'];
                    $i = 0;

                    foreach ($notices as $notice):
                        $color = $colors[$i % count($colors)];
                        $i++;
                    ?>
                    <div class="relative pl-6 border-l-4 border-<?php echo $color; ?> glow-<?php echo $color; ?>">
                        <div class="absolute -left-2.5 top-0 w-5 h-5 rounded-full bg-<?php echo $color; ?>"></div>
                        <div class="bg-gradient-to-r from-dark-lighter to-dark p-5 rounded-lg border border-dark-border transition-all duration-300 hover:border-<?php echo $color; ?>">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-3 pb-3 border-b border-dark-border">
                                <h3 class="font-orbitron text-<?php echo $color; ?> text-lg mb-1 sm:mb-0 hover:animate-pulse-slow">
                                    <?php echo htmlentities($notice->NoticeTitle); ?>
                                </h3>
                                <div class="flex items-center text-xs text-gray-400">
                                    <i class="fas fa-calendar-alt mr-2"></i>
                                    <span>Posted: <?php echo date('d M Y • h:i A', strtotime($notice->CreationDate)); ?></span>
                                </div>
                            </div>
                            <div class="text-gray-300">
                                <p><?php echo nl2br(htmlentities($notice->NoticeMsg)); ?></p>
                            </div>
                            <div class="mt-4 flex justify-end">
                                <button class="text-<?php echo $color; ?> text-sm hover:text-white transition-colors duration-200 flex items-center">
                                    <span>Read More</span>
                                    <i class="fas fa-chevron-right ml-1 text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                
                <!-- Pagination
                <div class="mt-10 flex justify-center">
                    <nav class="flex items-center space-x-2">
                        <button class="w-10 h-10 flex items-center justify-center rounded-md border border-dark-border text-gray-400 hover:border-neon-blue hover:text-neon-blue transition-colors duration-200">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="w-10 h-10 flex items-center justify-center rounded-md bg-neon-blue text-white">1</button>
                        <button class="w-10 h-10 flex items-center justify-center rounded-md border border-dark-border text-gray-400 hover:border-neon-blue hover:text-neon-blue transition-colors duration-200">2</button>
                        <button class="w-10 h-10 flex items-center justify-center rounded-md border border-dark-border text-gray-400 hover:border-neon-blue hover:text-neon-blue transition-colors duration-200">3</button>
                        <span class="w-10 h-10 flex items-center justify-center text-gray-400">...</span>
                        <button class="w-10 h-10 flex items-center justify-center rounded-md border border-dark-border text-gray-400 hover:border-neon-blue hover:text-neon-blue transition-colors duration-200">8</button>
                        <button class="w-10 h-10 flex items-center justify-center rounded-md border border-dark-border text-gray-400 hover:border-neon-blue hover:text-neon-blue transition-colors duration-200">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </nav>
                </div> -->
                
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