<?php
session_start();
if (!isset($_SESSION['sturecmsstuid'])) {
    header("Location: login.php");
    exit();
}

include("../includes/dbconnection.php");

// Fetch class ID of student
$sid = $_SESSION['sturecmsstuid'];
$studentSql = "SELECT StudentClass, StudentName FROM tblstudent WHERE StuID = :sid";
$studentQuery = $dbh->prepare($studentSql);
$studentQuery->bindParam(':sid', $sid, PDO::PARAM_STR);
$studentQuery->execute();
$student = $studentQuery->fetch(PDO::FETCH_OBJ);
$stuclass = $student->StudentClass;
$_SESSION['studentname'] = $student->StudentName; // for displaying on top right

// Fetch homework for that class
$hwSql = "SELECT tblhomework.*, tblclass.ClassName, tblclass.Section 
          FROM tblhomework 
          JOIN tblclass ON tblclass.ID = tblhomework.classId 
          WHERE tblhomework.classId = :stuclass 
          ORDER BY tblhomework.postingDate DESC";
$hwQuery = $dbh->prepare($hwSql);
$hwQuery->bindParam(':stuclass', $stuclass, PDO::PARAM_STR);
$hwQuery->execute();
$homeworks = $hwQuery->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Padhle - Homework</title>
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
                <a href="./homework.php" class="flex items-center px-4 py-3 mb-2 text-white bg-somaiya-red rounded-md transition-colors duration-200 hover:bg-somaiya-red hover:bg-opacity-30">
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
                <a href="#" class="flex items-center px-4 py-3 text-gray-400 rounded-md transition-colors duration-200 hover:bg-dark-border hover:text-white">
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
                <h1 class="text-xl font-semibold text-highlight-yellow">Homework</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-400">Rahul Sharma</span>
                    <div class="w-8 h-8 bg-somaiya-red rounded-full flex items-center justify-center text-white font-medium">
                        RS
                    </div>
                </div>
            </div>
            
            <!-- Homework Content -->
            <div class="p-6">
                <!-- Header Section -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold mb-2">Assigned Homework</h2>
                    <p class="text-gray-400">View and manage all your assigned homework tasks.</p>
                </div>
                
                <!-- Filter/Search Section -->
                <div class="mb-8 flex flex-col sm:flex-row gap-4">
                    <div class="relative flex-1">
                        <input type="text" placeholder="Search homework..." class="w-full bg-dark-lighter border border-dark-border rounded-md py-2 px-4 pl-10 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <div class="flex gap-4">
                        <select class="bg-dark-lighter border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200">
                            <option value="all">All Classes</option>
                            <option value="math">Mathematics</option>
                            <option value="physics">Physics</option>
                            <option value="chemistry">Chemistry</option>
                            <option value="biology">Biology</option>
                        </select>
                        <select class="bg-dark-lighter border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200">
                            <option value="all">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="submitted">Submitted</option>
                            <option value="overdue">Overdue</option>
                        </select>
                    </div>
                </div>
                
                <!-- Homework Table -->
                <div class="overflow-x-auto">
                    <div class="border border-dark-border rounded-lg overflow-hidden">
                        <!-- Table Header -->
                        <div class="bg-dark-lighter text-gray-300 text-sm font-medium">
                            <div class="grid grid-cols-12 gap-4 px-6 py-3">
                                <div class="col-span-1">S.No</div>
                                <div class="col-span-3">Homework Title</div>
                                <div class="col-span-2">Class</div>
                                <div class="col-span-1">Section</div>
                                <div class="col-span-2">Last Submission</div>
                                <div class="col-span-2">Posting Date</div>
                                <div class="col-span-1 text-right">Action</div>
                            </div>
                        </div>
                        
                        <!-- Table Body -->
                        <div class="divide-y divide-dark-border">
                        <?php
                            $cnt = 1;
                            if ($hwQuery->rowCount() > 0) {
                                foreach ($homeworks as $row) {
                            ?>
                            <div class="grid grid-cols-12 gap-4 px-6 py-4 hover:bg-dark-lighter transition-colors duration-200">
                                <div class="col-span-1 text-gray-400"><?php echo $cnt++; ?></div>
                                <div class="col-span-3 font-medium"><?php echo htmlentities($row->homeworkTitle); ?></div>
                                <div class="col-span-2 text-gray-300"><?php echo htmlentities($row->ClassName); ?></div>
                                <div class="col-span-1 text-gray-300"><?php echo htmlentities($row->Section); ?></div>
                                <div class="col-span-2 text-gray-300"><?php echo date("d M Y", strtotime($row->lastDateofSubmission)); ?></div>
                                <div class="col-span-2 text-gray-400"><?php echo date("d M Y", strtotime($row->postingDate)); ?></div>
                                <div class="col-span-1 text-right">
                                    <a href="submit-homework.php?hwid=<?php echo $row->id; ?>" class="px-3 py-1 bg-somaiya-red text-white text-sm rounded transition-colors duration-200 hover:bg-opacity-90">Submit</a>
                                </div>
                            </div>
                            <?php
                                }
                            } else {
                            ?>
                            <div class="px-6 py-4 text-gray-400">
                                No homework found for your class.
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                
                <!-- Pagination
                <div class="mt-6 flex justify-between items-center">
                    <div class="text-sm text-gray-400">
                        Showing <span class="font-medium text-white">1</span> to <span class="font-medium text-white">7</span> of <span class="font-medium text-white">24</span> entries
                    </div>
                    <div class="flex items-center space-x-2">
                        <button class="w-8 h-8 flex items-center justify-center rounded border border-dark-border text-gray-400 hover:border-somaiya-red hover:text-somaiya-red transition-colors duration-200">
                            <i class="fas fa-chevron-left text-xs"></i>
                        </button>
                        <button class="w-8 h-8 flex items-center justify-center rounded bg-somaiya-red text-white">1</button>
                        <button class="w-8 h-8 flex items-center justify-center rounded border border-dark-border text-gray-400 hover:border-somaiya-red hover:text-somaiya-red transition-colors duration-200">2</button>
                        <button class="w-8 h-8 flex items-center justify-center rounded border border-dark-border text-gray-400 hover:border-somaiya-red hover:text-somaiya-red transition-colors duration-200">3</button>
                        <button class="w-8 h-8 flex items-center justify-center rounded border border-dark-border text-gray-400 hover:border-somaiya-red hover:text-somaiya-red transition-colors duration-200">4</button>
                        <button class="w-8 h-8 flex items-center justify-center rounded border border-dark-border text-gray-400 hover:border-somaiya-red hover:text-somaiya-red transition-colors duration-200">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </button>
                    </div>
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