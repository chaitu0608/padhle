<?php
session_start();

if (!isset($_SESSION['sturecmsstuid'])) {
    header("Location: login.php");
    exit();
}

include("../includes/dbconnection.php");

// Fetch notices
$sql = "SELECT * FROM tblnotice ORDER BY CreationDate DESC";
$query = $dbh->prepare($sql);
$query->execute();
$notices = $query->fetchAll(PDO::FETCH_OBJ);
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
    <style>
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
        <aside id="sidebar" class="bg-dark-lighter w-64 border-r border-dark-border h-full flex-shrink-0 fixed inset-y-0 left-0 z-30 transition-transform duration-300 ease-in-out transform md:translate-x-0 -translate-x-full">
            <div class="p-4 border-b border-dark-border">
                <a href="./dashboard.php" class="flex items-center">
                    <div class="w-10 h-10 bg-somaiya-red rounded-md flex items-center justify-center text-white font-bold text-xl">P</div>
                    <span class="ml-3 text-xl font-bold text-highlight-yellow">Padhle</span>
                </a>
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
                <a href="./notices.php" class="flex items-center px-4 py-3 mb-2 text-white bg-somaiya-red rounded-md">
                    <i class="fas fa-bell w-5 h-5"></i>
                    <span class="ml-3">Notices</span>
                </a>
                <a href="./student-profile.php" class="flex items-center px-4 py-3 mb-2 text-gray-400 rounded-md hover:bg-white hover:text-black">
                    <i class="fas fa-user w-5 h-5"></i>
                    <span class="ml-3">Profile</span>
                </a>
            </nav>
            <div class="absolute bottom-0 w-full p-4 border-t border-dark-border">
                <a href="#" class="flex items-center px-4 py-3 text-gray-400 rounded-md hover:bg-dark-border hover:text-white">
                    <i class="fas fa-sign-out-alt w-5 h-5"></i>
                    <span class="ml-3">Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto md:ml-64">
            <!-- Top bar -->
            <div class="bg-dark-lighter border-b border-dark-border p-4 flex justify-between items-center sticky top-0 z-20">
                <h1 class="text-xl font-semibold text-highlight-yellow">Notices</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-400"><?php echo htmlentities($_SESSION['studentname']); ?></span>
                    <div class="w-8 h-8 bg-somaiya-red rounded-full flex items-center justify-center text-white font-medium">
                        <?php 
                        $nameParts = explode(' ', $_SESSION['studentname']);
                        echo strtoupper(substr($nameParts[0], 0, 1)) . strtoupper(substr($nameParts[1] ?? '', 0, 1)); 
                        ?>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <!-- Header Section -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-highlight-yellow">Notices</h2>
                    <p class="text-gray-400">Stay updated with the latest notices and announcements.</p>
                </div>

                <!-- Filter/Search Section -->
                <div class="mb-8 flex flex-col sm:flex-row gap-4">
                    <div class="relative flex-1">
                        <input type="text" placeholder="Search notices..." class="w-full bg-dark-lighter border border-dark-border rounded-md py-2 px-4 pl-10 text-white focus:outline-none focus:border-somaiya-red">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <select class="bg-dark-lighter border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red">
                        <option value="all">All Categories</option>
                        <option value="academic">Academic</option>
                        <option value="events">Events</option>
                        <option value="exams">Exams</option>
                        <option value="holidays">Holidays</option>
                    </select>
                </div>

                <!-- Notices List -->
                <div class="space-y-6">
                    <?php if ($query->rowCount() > 0): ?>
                        <?php foreach ($notices as $notice): ?>
                            <div class="bg-dark-lighter border border-dark-border p-6 rounded-lg hover:border-somaiya-red transition-all duration-300">
                                <h3 class="text-lg font-bold text-highlight-yellow mb-2"><?php echo htmlentities($notice->NoticeTitle); ?></h3>
                                <p class="text-gray-300 mb-4"><?php echo nl2br(htmlentities($notice->NoticeMsg)); ?></p>
                                <div class="text-sm text-gray-400">
                                    <i class="fas fa-calendar-alt mr-2"></i>
                                    Posted on: <?php echo date('d M Y, h:i A', strtotime($notice->CreationDate)); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-gray-400">No notices available at the moment.</p>
                    <?php endif; ?>
                </div>

                <!-- Footer -->
                <footer class="mt-12 pb-6 text-center text-gray-500 text-sm">
                    Made with ❤️ by Bhoumish and Chaitanya - © Somaiya University
                </footer>
            </div>
        </main>
    </div>
</body>
</html>