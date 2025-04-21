<?php
session_start();
include('../../includes/dbconnection.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['submit'])) {
    // Collect data from the form
    $studentname = $_POST['studentname'];
    $studentemail = $_POST['studentemail'];
    $studentclass = $_POST['studentclass'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $stuid = $_POST['stuid'];
    $fathername = $_POST['fathername'];
    $mothername = $_POST['mothername'];
    $contactnumber = $_POST['contactnumber'];
    $altnumber = $_POST['altnumber'];
    $address = $_POST['address'];
    $username = $_POST['username'];
    $password = md5($_POST['password']); // You can replace with password_hash() for better security
    $dateofadmission = $_POST['dateofadmission']; // Taken from form instead of current timestamp

    // SQL Insert Query
    $sql = "INSERT INTO tblstudent (
                StudentName, StudentEmail, StudentClass, Gender, DOB, StuID,
                FatherName, MotherName, ContactNumber, AltenateNumber,
                Address, UserName, Password, DateofAdmission
            ) VALUES (
                :studentname, :studentemail, :studentclass, :gender, :dob, :stuid,
                :fathername, :mothername, :contactnumber, :altnumber,
                :address, :username, :password, :dateofadmission
            )";

    $query = $dbh->prepare($sql);
    $query->bindParam(':studentname', $studentname);
    $query->bindParam(':studentemail', $studentemail);
    $query->bindParam(':studentclass', $studentclass);
    $query->bindParam(':gender', $gender);
    $query->bindParam(':dob', $dob);
    $query->bindParam(':stuid', $stuid);
    $query->bindParam(':fathername', $fathername);
    $query->bindParam(':mothername', $mothername);
    $query->bindParam(':contactnumber', $contactnumber);
    $query->bindParam(':altnumber', $altnumber);
    $query->bindParam(':address', $address);
    $query->bindParam(':username', $username);
    $query->bindParam(':password', $password);
    $query->bindParam(':dateofadmission', $dateofadmission);

    // Execute and check
    if ($query->execute()) {
        echo "<script>alert('Student added successfully'); window.location.href='manage-students.php';</script>";
    } else {
        echo "<script>alert('Something went wrong. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Padhle - Add Student</title>
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
        
        /* Custom file input */
        .custom-file-input::-webkit-file-upload-button {
            visibility: hidden;
            display: none;
        }
        .custom-file-input::before {
            content: 'Choose file';
            display: inline-block;
            background: #333;
            color: white;
            border-radius: 0.375rem;
            padding: 0.5rem 1rem;
            outline: none;
            white-space: nowrap;
            cursor: pointer;
            font-weight: 500;
            font-size: 0.875rem;
        }
        .custom-file-input:hover::before {
            background: #444;
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
                
                <!-- Students Dropdown (Active) -->
                <div class="mb-2">
                    <button id="students-dropdown-btn" class="w-full flex items-center justify-between px-4 py-3 text-white bg-black bg-opacity-30 rounded-md transition-colors duration-200 hover:bg-black hover:bg-opacity-40">
                        <div class="flex items-center">
                            <i class="fas fa-user-graduate w-5 h-5"></i>
                            <span class="ml-3">Students</span>
                        </div>
                        <i id="students-dropdown-icon" class="fas fa-chevron-down rotate-180 transition-transform duration-200"></i>
                    </button>
                    
                    <div id="students-dropdown" class="pl-4 mt-1">
                        <a href="./add-students.php" class="flex items-center px-4 py-2 mb-1 text-white bg-black bg-opacity-30 rounded-md transition-colors duration-200 hover:bg-black hover:bg-opacity-40">
                            <i class="fas fa-user-plus w-5 h-5"></i>
                            <span class="ml-3">Add Student</span>
                        </a>
                        <a href="./manage-students.php" class="flex items-center px-4 py-2 mb-1 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
                            <i class="fas fa-users w-5 h-5"></i>
                            <span class="ml-3">Manage Students</span>
                        </a>
                    </div>
                </div>
                
                <a href="#" class="flex items-center px-4 py-3 mb-2 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
                    <i class="fas fa-book w-5 h-5"></i>
                    <span class="ml-3">Homework</span>
                </a>
                <a href="#" class="flex items-center px-4 py-3 mb-2 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
                    <i class="fas fa-bell w-5 h-5"></i>
                    <span class="ml-3">Notice</span>
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
                <h1 class="text-xl font-semibold text-highlight-yellow">Add Student</h1>
                <div class="flex items-center space-x-4">
                    <div class="text-sm text-gray-400">
                        <a href="#" class="hover:text-white transition-colors duration-200">Dashboard</a>
                        <span class="mx-2">/</span>
                        <span class="text-white">Add Student</span>
                    </div>
                    <div class="w-8 h-8 bg-somaiya-red rounded-full flex items-center justify-center text-white font-medium">
                        A
                    </div>
                </div>
            </div>
            
            <!-- Add Student Content -->
            <div class="p-6 animate-fade-in">
                <!-- Header Section -->
                <div class="mb-6">
                    <h2 class="text-2xl font-bold mb-2">Student Registration</h2>
                    <p class="text-gray-400">Add a new student to the system. Fields marked with * are required.</p>
                </div>
                
                <form class="bg-dark-lighter border border-dark-border rounded-lg p-6" method="POST">
    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Left Column: Student Details -->
        <div>
            <h3 class="text-highlight-yellow text-lg font-semibold mb-4">Student Details</h3>

            <div class="mb-4">
                <label for="studentname" class="block text-sm font-medium mb-2">Student Name *</label>
                <input type="text" id="studentname" name="studentname" required class="w-full bg-dark border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200">
            </div>

            <div class="mb-4">
                <label for="studentemail" class="block text-sm font-medium mb-2">Student Email *</label>
                <input type="email" id="studentemail" name="studentemail" required class="w-full bg-dark border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200">
            </div>

            <div class="mb-4">
                <label for="studentclass" class="block text-sm font-medium mb-2">Student Class *</label>
                <select id="studentclass" name="studentclass" required class="w-full bg-dark border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200">
                    <option value="">Select Class</option>
                    <option value="1">Class 1</option>
                    <option value="2">Class 2</option>
                    <option value="3">Class 3</option>
                    <option value="4">Class 4</option>
                    <option value="5">Class 5</option>
                    <option value="6">Class 6</option>
                    <option value="7">Class 7</option>
                    <option value="8">Class 8</option>
                    <option value="9">Class 9</option>
                    <option value="10">Class 10</option>
                    <option value="11">Class 11</option>
                    <option value="12">Class 12</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="gender" class="block text-sm font-medium mb-2">Gender *</label>
                <select id="gender" name="gender" required class="w-full bg-dark border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200">
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="dob" class="block text-sm font-medium mb-2">Date of Birth *</label>
                <input type="date" id="dob" name="dob" required class="w-full bg-dark border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200">
            </div>
        </div>

        <!-- Right Column: Parent/Guardian Details -->
        <div>
            <h3 class="text-highlight-yellow text-lg font-semibold mb-4">Parent/Guardian Details</h3>

            <div class="mb-4">
                <label for="fathername" class="block text-sm font-medium mb-2">Father's Name *</label>
                <input type="text" id="fathername" name="fathername" required class="w-full bg-dark border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200">
            </div>

            <div class="mb-4">
                <label for="mothername" class="block text-sm font-medium mb-2">Mother's Name *</label>
                <input type="text" id="mothername" name="mothername" required class="w-full bg-dark border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200">
            </div>

            <div class="mb-4">
                <label for="contactnumber" class="block text-sm font-medium mb-2">Contact Number *</label>
                <input type="tel" id="contactnumber" name="contactnumber" required class="w-full bg-dark border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200">
            </div>

            <div class="mb-4">
                <label for="altnumber" class="block text-sm font-medium mb-2">Alternate Contact Number</label>
                <input type="tel" id="altnumber" name="altnumber" class="w-full bg-dark border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200">
            </div>

            <div class="mb-4">
                <label for="address" class="block text-sm font-medium mb-2">Address *</label>
                <textarea id="address" name="address" rows="4" required class="w-full bg-dark border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200"></textarea>
            </div>
        </div>
    </div>

    <!-- Additional Fields -->
    <div class="mb-8">
        <h3 class="text-highlight-yellow text-lg font-semibold mb-4">Additional Information</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="mb-4">
                <label for="stuid" class="block text-sm font-medium mb-2">Student ID *</label>
                <input type="text" id="stuid" name="stuid" required class="w-full bg-dark border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200">
            </div>

            <div class="mb-4">
                <label for="dateofadmission" class="block text-sm font-medium mb-2">Date of Admission *</label>
                <input type="date" id="dateofadmission" name="dateofadmission" required class="w-full bg-dark border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200">
            </div>
        </div>
    </div>

    <!-- Login Credentials -->
    <div class="mb-8">
        <h3 class="text-highlight-yellow text-lg font-semibold mb-4">Login Credentials</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium mb-2">Username *</label>
                <input type="text" id="username" name="username" required class="w-full bg-dark border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200">
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium mb-2">Password *</label>
                <input type="password" id="password" name="password" required class="w-full bg-dark border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200">
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="flex justify-end space-x-4">
        <button type="button" class="px-6 py-2 bg-dark-border text-white rounded-md hover:bg-opacity-80 transition-colors duration-200">Cancel</button>
        <button type="submit" class="px-6 py-2 bg-somaiya-red text-white font-medium rounded-md hover:bg-opacity-90 transition-colors duration-200 flex items-center" name="submit">
            <i class="fas fa-save mr-2"></i>
            <span>Register Student</span>
        </button>
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
        
        // Class dropdown toggle
        const classDropdownBtn = document.getElementById('class-dropdown-btn');
        const classDropdown = document.getElementById('class-dropdown');
        const classDropdownIcon = document.getElementById('class-dropdown-icon');
        
        classDropdownBtn.addEventListener('click', () => {
            classDropdown.classList.toggle('hidden');
            classDropdownIcon.classList.toggle('rotate-180');
        });
        
        // Students dropdown toggle (already open by default)
        const studentsDropdownBtn = document.getElementById('students-dropdown-btn');
        const studentsDropdown = document.getElementById('students-dropdown');
        const studentsDropdownIcon = document.getElementById('students-dropdown-icon');
        
        studentsDropdownBtn.addEventListener('click', () => {
            studentsDropdown.classList.toggle('hidden');
            studentsDropdownIcon.classList.toggle('rotate-180');
        });
        
        // Set current date as default for Date of Admission
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('dateOfAdmission').value = today;
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