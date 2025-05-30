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
    <!-- Custom Styles -->
    <link href="../src/assets/styles.css" rel="stylesheet">
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
</head>
<body class="bg-dark text-white font-sans">
    <!-- Custom Cursor -->
    <div class="cursor-dot"></div>
    <div class="cursor-outline"></div>

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="bg-dark-lighter w-64 border-r border-dark-border h-full flex-shrink-0 fixed inset-y-0 left-0 z-30 transition-transform duration-300 ease-in-out transform md:translate-x-0 -translate-x-full">
            <div class="p-4 border-b border-dark-border">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-somaiya-red rounded-md flex items-center justify-center text-white font-bold text-xl">P</div>
                    <span class="ml-3 text-xl font-bold text-highlight-yellow">Padhle</span>
                </div>
            </div>
            <nav class="mt-6 px-4">
                <a href="./dashboard.php" class="flex items-center px-4 py-3 mb-2 text-gray-400 rounded-md hover:bg-white hover:text-black">
                    <i class="fas fa-home w-5 h-5"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
                <a href="./homework.php" class="flex items-center px-4 py-3 mb-2 text-white bg-somaiya-red rounded-md">
                    <i class="fas fa-book w-5 h-5"></i>
                    <span class="ml-3">Homework</span>
                </a>
                <a href="./notices.php" class="flex items-center px-4 py-3 mb-2 text-gray-400 rounded-md hover:bg-white hover:text-black">
                    <i class="fas fa-bell w-5 h-5"></i>
                    <span class="ml-3">Notices</span>
                </a>
                <a href="./student-profile.php" class="flex items-center px-4 py-3 mb-2 text-gray-400 rounded-md hover:bg-white hover:text-black">
                    <i class="fas fa-user w-5 h-5"></i>
                    <span class="ml-3">Profile</span>
                </a>
            </nav>
            <div class="absolute bottom-0 w-full p-4 border-t border-dark-border">
                <a href="./logout.php" class="flex items-center px-4 py-3 text-gray-400 rounded-md hover:bg-dark-border hover:text-white">
                    <i class="fas fa-sign-out-alt w-5 h-5"></i>
                    <span class="ml-3">Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto md:ml-64">
            <!-- Top bar -->
            <div class="bg-dark-lighter border-b border-dark-border p-4 flex justify-between items-center sticky top-0 z-20">
                <h1 class="text-xl font-semibold text-highlight-yellow">Homework</h1>
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
                    <h2 class="text-2xl font-bold text-highlight-yellow">Assigned Homework</h2>
                    <p class="text-gray-400">View and manage all your assigned homework tasks.</p>
                </div>

                <!-- Homework Table -->
                <div class="overflow-x-auto">
                    <div class="border border-dark-border rounded-lg overflow-hidden">
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
                        <div class="divide-y divide-dark-border">
                            <?php
                            $cnt = 1;
                            if ($hwQuery->rowCount() > 0) {
                                foreach ($homeworks as $row) {
                                    echo '<div class="grid grid-cols-12 gap-4 px-6 py-4 hover:bg-dark-lighter">';
                                    echo '<div class="col-span-1 text-gray-400">' . $cnt++ . '</div>';
                                    echo '<div class="col-span-3 font-medium">' . htmlentities($row->homeworkTitle) . '</div>';
                                    echo '<div class="col-span-2 text-gray-300">' . htmlentities($row->ClassName) . '</div>';
                                    echo '<div class="col-span-1 text-gray-300">' . htmlentities($row->Section) . '</div>';
                                    echo '<div class="col-span-2 text-gray-300">' . date("d M Y", strtotime($row->lastDateofSubmission)) . '</div>';
                                    echo '<div class="col-span-2 text-gray-400">' . date("d M Y", strtotime($row->postingDate)) . '</div>';
                                    echo '<div class="col-span-1 text-right">';
                                    echo '<a href="submit-homework.php?hwid=' . $row->id . '" class="px-3 py-1 bg-somaiya-red text-white text-sm rounded hover:bg-opacity-90">Submit</a>';
                                    echo '</div></div>';
                                }
                            } else {
                                echo '<div class="px-6 py-4 text-gray-400">No homework found for your class.</div>';
                            }
                            ?>
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

    <!-- Custom Cursor Script -->
    <script src="../src/assets/script.js"></script>
</body>
</html>