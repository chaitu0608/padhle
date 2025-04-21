<?php
session_start();
include('../../includes/dbconnection.php');

// Delete logic
if (isset($_GET['delid'])) {
    $id = intval($_GET['delid']);
    $sql = "DELETE FROM tblstudent WHERE ID=:id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id);
    $query->execute();
    echo "<script>alert('Student deleted successfully'); window.location.href='manage-students.php';</script>";
}

// Pagination variables
$entriesPerPage = 8; // Number of students per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Current page
$offset = ($page - 1) * $entriesPerPage; // Offset for SQL query

// Search and filter variables
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$classFilter = isset($_GET['class']) ? trim($_GET['class']) : 'all';

// Base SQL query
$sql = "SELECT * FROM tblstudent WHERE 1=1";

// Add class filter
if ($classFilter !== 'all') {
    $sql .= " AND StudentClass = :classFilter";
}

// Add search functionality
if (!empty($search)) {
    $sql .= " AND (StudentName LIKE :search OR StudentEmail LIKE :search OR StuID LIKE :search)";
}

// Add pagination
$sql .= " ORDER BY ID DESC LIMIT :offset, :entriesPerPage";

$query = $dbh->prepare($sql);

// Bind parameters
if ($classFilter !== 'all') {
    $query->bindParam(':classFilter', $classFilter, PDO::PARAM_STR);
}
if (!empty($search)) {
    $searchParam = '%' . $search . '%';
    $query->bindParam(':search', $searchParam, PDO::PARAM_STR);
}
$query->bindParam(':offset', $offset, PDO::PARAM_INT);
$query->bindParam(':entriesPerPage', $entriesPerPage, PDO::PARAM_INT);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);

// Count total entries for pagination
$totalEntriesQuery = $dbh->prepare("SELECT COUNT(*) FROM tblstudent WHERE 1=1" . 
    ($classFilter !== 'all' ? " AND StudentClass = :classFilter" : "") . 
    (!empty($search) ? " AND (StudentName LIKE :search OR StudentEmail LIKE :search OR StuID LIKE :search)" : ""));
if ($classFilter !== 'all') {
    $totalEntriesQuery->bindParam(':classFilter', $classFilter, PDO::PARAM_STR);
}
if (!empty($search)) {
    $totalEntriesQuery->bindParam(':search', $searchParam, PDO::PARAM_STR);
}
$totalEntriesQuery->execute();
$totalEntries = $totalEntriesQuery->fetchColumn();
$totalPages = ceil($totalEntries / $entriesPerPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Padhle - Manage Students</title>
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
                <h1 class="text-xl font-semibold text-highlight-yellow">Manage Students</h1>
                <div class="flex items-center space-x-4">
                    <div class="text-sm text-gray-400">
                        <a href="#" class="hover:text-white transition-colors duration-200">Dashboard</a>
                        <span class="mx-2">/</span>
                        <span class="text-white">Manage Students</span>
                    </div>
                    <div class="w-8 h-8 bg-somaiya-red rounded-full flex items-center justify-center text-white font-medium">
                        A
                    </div>
                </div>
            </div>
            
            <!-- Manage Students Content -->
            <div class="p-6 animate-fade-in">
                <!-- Header Section -->
                <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold mb-2">Student Management</h2>
                        <p class="text-gray-400">View and manage all students in the system.</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="manage-students.php" class="bg-somaiya-red text-white px-4 py-2 rounded-md hover:bg-opacity-90 transition-colors duration-200 flex items-center">
                            <i class="fas fa-eye mr-2"></i>
                            <span>View All Students</span>
                        </a>
                    </div>
                </div>
                
                <!-- Filter/Search Section -->
                <div class="mb-6 flex flex-col sm:flex-row gap-4">
                    <form method="get" class="flex flex-1 gap-4">
                        <!-- Search Input -->
                        <div class="relative flex-1">
                            <input type="text" name="search" placeholder="Search students..." value="<?php echo htmlentities($search); ?>"
                                   class="w-full bg-dark-lighter border border-dark-border rounded-md py-2 px-4 pl-10 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                        <!-- Class Filter -->
                        <div>
                            <select name="class" class="bg-dark-lighter border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200">
                                <option value="all" <?php echo $classFilter === 'all' ? 'selected' : ''; ?>>All Classes</option>
                                <?php
                                // Fetch distinct classes from the database
                                $classQuery = $dbh->query("SELECT DISTINCT StudentClass FROM tblstudent ORDER BY StudentClass ASC");
                                $classes = $classQuery->fetchAll(PDO::FETCH_OBJ);
                                foreach ($classes as $class) {
                                    $selected = ($classFilter === $class->StudentClass) ? 'selected' : '';
                                    echo '<option value="' . htmlentities($class->StudentClass) . '" ' . $selected . '>' . htmlentities($class->StudentClass) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <!-- Filter Button -->
                        <button type="submit" class="bg-somaiya-red text-white px-4 py-2 rounded-md hover:bg-opacity-90 transition-colors duration-200 flex items-center">
                            <i class="fas fa-filter mr-2"></i>
                            <span>Filter</span>
                        </button>
                    </form>
                    <!-- Add Student Button -->
                    <a href="add-students.php">
                        <button class="bg-somaiya-red text-white px-4 py-2 rounded-md hover:bg-opacity-90 transition-colors duration-200 flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            <span>Add Student</span>
                        </button>
                    </a>
                </div>
                
                <!-- Students Table -->
                <div class="overflow-x-auto">
                    <div class="border border-dark-border rounded-lg overflow-hidden">
                        <!-- Table Header -->
                        <div class="bg-dark-lighter text-gray-300 text-sm font-medium">
                            <div class="grid grid-cols-12 gap-4 px-6 py-3">
                                <div class="col-span-1 text-center">S.No</div>
                                <div class="col-span-2 text-center">Student ID</div>
                                <div class="col-span-2 text-center">Class</div>
                                <div class="col-span-3 text-center">Student Name</div>
                                <div class="col-span-2 text-center">Email</div>
                                <div class="col-span-1 text-center">Admission Date</div>
                                <div class="col-span-1 text-center">Action</div>
                            </div>
                        </div>

                        <!-- Table Body -->
                        <div class="divide-y divide-dark-border">
                            <?php
                            $cnt = $offset + 1;

                            foreach ($results as $row) {
                                echo '
                                <div class="grid grid-cols-12 gap-4 items-center border-t border-dark-border px-6 py-4 hover:bg-dark">
                                    <div class="col-span-1 text-sm text-gray-300 font-medium text-center">' . str_pad($cnt, 2, "0", STR_PAD_LEFT) . '</div>
                                    <div class="col-span-2 text-sm text-white font-semibold text-center">' . htmlentities($row->StuID) . '</div>
                                    <div class="col-span-2 text-sm text-gray-300 text-center">' . htmlentities($row->StudentClass) . '</div>
                                    <div class="col-span-3 text-sm text-white font-semibold text-center">' . htmlentities($row->StudentName) . '</div>
                                    <div class="col-span-2 text-sm text-gray-300 text-center">' . htmlentities($row->StudentEmail) . '</div>
                                    <div class="col-span-1 text-sm text-gray-400 text-center">' . date('d M Y', strtotime($row->DateofAdmission)) . '</div>
                                    <div class="col-span-1 flex justify-center space-x-2">
                                        <a href="edit-student-detail.php?editid=' . $row->ID . '" class="bg-purple-600 hover:bg-purple-700 text-white px-2 py-1 rounded transition">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="manage-students.php?delid=' . $row->ID . '" onclick="return confirm(\'Are you sure you want to delete this student?\');" class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded transition">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </div>';
                                $cnt++;
                            }
                            ?>
                        </div>
                    </div>
                </div>
                
                <!-- Pagination -->
                <div class="mt-6 flex justify-between items-center">
                    <div class="text-sm text-gray-400">
                        Showing 
                        <span class="font-medium text-white"><?php echo $offset + 1; ?></span> to 
                        <span class="font-medium text-white"><?php echo min($offset + $entriesPerPage, $totalEntries); ?></span> of 
                        <span class="font-medium text-white"><?php echo $totalEntries; ?></span> entries
                    </div>
                    <div class="flex items-center space-x-2">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?php echo $page - 1; ?>">
                                <button class="w-8 h-8 flex items-center justify-center rounded border border-dark-border text-gray-400 hover:border-somaiya-red hover:text-somaiya-red transition-colors duration-200">
                                    <i class="fas fa-chevron-left text-xs"></i>
                                </button>
                            </a>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="?page=<?php echo $i; ?>">
                                <button class="w-8 h-8 flex items-center justify-center rounded <?php echo $i == $page ? 'bg-somaiya-red text-white' : 'border border-dark-border text-gray-400 hover:border-somaiya-red hover:text-somaiya-red'; ?> transition-colors duration-200">
                                    <?php echo $i; ?>
                                </button>
                            </a>
                        <?php endfor; ?>
                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?php echo $page + 1; ?>">
                                <button class="w-8 h-8 flex items-center justify-center rounded border border-dark-border text-gray-400 hover:border-somaiya-red hover:text-somaiya-red transition-colors duration-200">
                                    <i class="fas fa-chevron-right text-xs"></i>
                                </button>
                            </a>
                        <?php endif; ?>
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

        // Notices dropdown toggle
        const noticesDropdownBtn = document.getElementById('notices-dropdown-btn');
        const noticesDropdown = document.getElementById('notices-dropdown');
        const noticesDropdownIcon = document.getElementById('notices-dropdown-icon');

        noticesDropdownBtn.addEventListener('click', () => {
            noticesDropdown.classList.toggle('hidden');
            noticesDropdownIcon.classList.toggle('rotate-180');
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