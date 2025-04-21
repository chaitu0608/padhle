<?php
session_start();
include('../../includes/dbconnection.php');

if (isset($_GET['editid'])) {
    $id = intval($_GET['editid']);
    $sql = "SELECT * FROM tblstudent WHERE ID=:id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
}

// Update logic
if (isset($_POST['update'])) {
    $studentname = $_POST['studentname'];
    $studentemail = $_POST['studentemail'];
    $studentclass = $_POST['studentclass'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $fathersname = $_POST['fathersname'];
    $mothersname = $_POST['mothersname'];
    $contactnumber = $_POST['contactnumber'];
    $alternatenumber = $_POST['alternatenumber'];
    $address = $_POST['address'];

    $sql = "UPDATE tblstudent SET StudentName=:studentname, StudentEmail=:studentemail, Class=:studentclass, Gender=:gender, DOB=:dob, FatherName=:fathersname, MotherName=:mothersname, ContactNumber=:contactnumber, AlternateNumber=:alternatenumber, Address=:address WHERE ID=:id";
    $query = $dbh->prepare($sql);

    $query->bindParam(':studentname', $studentname);
    $query->bindParam(':studentemail', $studentemail);
    $query->bindParam(':studentclass', $studentclass);
    $query->bindParam(':gender', $gender);
    $query->bindParam(':dob', $dob);
    $query->bindParam(':fathersname', $fathersname);
    $query->bindParam(':mothersname', $mothersname);
    $query->bindParam(':contactnumber', $contactnumber);
    $query->bindParam(':alternatenumber', $alternatenumber);
    $query->bindParam(':address', $address);
    $query->bindParam(':id', $id);

    if ($query->execute()) {
        echo "<script>alert('Student details updated'); window.location.href='manage-students.php';</script>";
    }
}

if (isset($_GET['editid'])) {
    $eid = intval($_GET['editid']);
    $sql = "SELECT * FROM tblstudent WHERE ID = :eid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':eid', $eid, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        // Assign fetched values to variables
        $name = htmlentities($result->StudentName);
        $email = htmlentities($result->StudentEmail);
        $class = htmlentities($result->StudentClass);
        $section = htmlentities($result->Section);
        $gender = htmlentities($result->Gender);
        $dob = htmlentities($result->DOB);
        $father = htmlentities($result->FatherName);
        $mother = htmlentities($result->MotherName);
        $contact = htmlentities($result->ContactNumber);
        $altcontact = htmlentities($result->AlternateNumber);
        $address = htmlentities($result->Address);
        $stuid = htmlentities($result->StuID);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Padhle - Edit Student</title>
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
                        <a href="#" class="flex items-center px-4 py-2 mb-1 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
                            <i class="fas fa-user-plus w-5 h-5"></i>
                            <span class="ml-3">Add Student</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 mb-1 text-white/80 rounded-md transition-colors duration-200 hover:bg-red-800">
                            <i class="fas fa-users w-5 h-5"></i>
                            <span class="ml-3">Manage Students</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 mb-1 text-white bg-black bg-opacity-30 rounded-md transition-colors duration-200 hover:bg-black hover:bg-opacity-40">
                            <i class="fas fa-user-edit w-5 h-5"></i>
                            <span class="ml-3">Edit Student</span>
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
                <h1 class="text-xl font-semibold text-highlight-yellow">Edit Student</h1>
                <div class="flex items-center space-x-4">
                    <div class="text-sm text-gray-400">
                        <a href="#" class="hover:text-white transition-colors duration-200">Dashboard</a>
                        <span class="mx-2">/</span>
                        <span class="text-white">Edit Student</span>
                    </div>
                    <div class="w-8 h-8 bg-somaiya-red rounded-full flex items-center justify-center text-white font-medium">
                        A
                    </div>
                </div>
            </div>
            
            <!-- Edit Student Content -->
            <div class="p-6 animate-fade-in">
                <!-- Header Section -->
                <div class="mb-6">
                    <h2 class="text-2xl font-bold mb-2">Edit Student Details</h2>
                    <p class="text-gray-400">Update the student's record below. Only the admin can make changes.</p>
                </div>
                
                <!-- Student Edit Form -->
                <form class="bg-dark-lighter border border-dark-border rounded-lg p-6">
                    <!-- Two Column Layout -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <!-- Left Column: Student Details -->
                        <div>
                            <h3 class="text-highlight-yellow text-lg font-semibold mb-4">Student Details</h3>
                            
                            <div class="mb-4">
                                <label for="studentName" class="block text-sm font-medium mb-2">
                                    <i class="fas fa-user mr-2 text-gray-400"></i>
                                    Student Name
                                </label>
                                <input type="text" id="studentName" name="studentName" value="Rahul Sharma" class="w-full bg-dark border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200">
                            </div>
                            
                            <div class="mb-4">
                                <label for="studentEmail" class="block text-sm font-medium mb-2">
                                    <i class="fas fa-envelope mr-2 text-gray-400"></i>
                                    Student Email
                                </label>
                                <input type="email" id="studentEmail" name="studentEmail" value="rahul.s@example.com" class="w-full bg-dark border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200">
                            </div>
                            
                            <div class="mb-4">
                                <label for="studentClass" class="block text-sm font-medium mb-2">
                                    <i class="fas fa-chalkboard mr-2 text-gray-400"></i>
                                    Student Class
                                </label>
                                <select id="studentClass" name="studentClass" class="w-full bg-dark border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200">
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
                                    <option value="10" selected>Class 10</option>
                                    <option value="11">Class 11</option>
                                    <option value="12">Class 12</option>
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label for="gender" class="block text-sm font-medium mb-2">
                                    <i class="fas fa-venus-mars mr-2 text-gray-400"></i>
                                    Gender
                                </label>
                                <select id="gender" name="gender" class="w-full bg-dark border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200">
                                    <option value="">Select Gender</option>
                                    <option value="male" selected>Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label for="dob" class="block text-sm font-medium mb-2">
                                    <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>
                                    Date of Birth
                                </label>
                                <input type="date" id="dob" name="dob" value="2010-05-15" class="w-full bg-dark border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200">
                            </div>
                            
                            <div class="mb-4">
                                <label for="stuID" class="block text-sm font-medium mb-2">
                                    <i class="fas fa-id-card mr-2 text-gray-400"></i>
                                    Student ID
                                </label>
                                <input type="text" id="stuID" name="stuID" value="STU001" readonly class="w-full bg-dark border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200 bg-opacity-50 cursor-not-allowed">
                                <p class="text-xs text-gray-400 mt-1">Student ID cannot be changed</p>
                            </div>
                            
                            <div class="mb-4">
                                <label for="studentImage" class="block text-sm font-medium mb-2">
                                    <i class="fas fa-camera mr-2 text-gray-400"></i>
                                    Student Photo
                                </label>
                                <div class="flex items-center space-x-4">
                                    <div class="w-16 h-16 bg-dark-border rounded-md overflow-hidden flex items-center justify-center">
                                        <i class="fas fa-user text-2xl text-gray-400"></i>
                                        <!-- This would be replaced with an actual image in a real implementation -->
                                    </div>
                                    <input type="file" id="studentImage" name="studentImage" accept="image/*" class="custom-file-input bg-dark border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200">
                                </div>
                                <p class="text-xs text-gray-400 mt-1">Upload a new photo if you want to change the current one (Max: 2MB)</p>
                            </div>
                        </div>
                        
                        <!-- Right Column: Parent/Guardian Details -->
                        <div>
                            <h3 class="text-highlight-yellow text-lg font-semibold mb-4">Parent/Guardian Details</h3>
                            
                            <div class="mb-4">
                                <label for="fatherName" class="block text-sm font-medium mb-2">
                                    <i class="fas fa-male mr-2 text-gray-400"></i>
                                    Father's Name
                                </label>
                                <input type="text" id="fatherName" name="fatherName" value="Rajesh Sharma" class="w-full bg-dark border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200">
                            </div>
                            
                            <div class="mb-4">
                                <label for="motherName" class="block text-sm font-medium mb-2">
                                    <i class="fas fa-female mr-2 text-gray-400"></i>
                                    Mother's Name
                                </label>
                                <input type="text" id="motherName" name="motherName" value="Sunita Sharma" class="w-full bg-dark border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200">
                            </div>
                            
                            <div class="mb-4">
                                <label for="contactNumber" class="block text-sm font-medium mb-2">
                                    <i class="fas fa-phone mr-2 text-gray-400"></i>
                                    Contact Number
                                </label>
                                <input type="tel" id="contactNumber" name="contactNumber" value="9876543210" class="w-full bg-dark border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200">
                            </div>
                            
                            <div class="mb-4">
                                <label for="alternateContactNumber" class="block text-sm font-medium mb-2">
                                    <i class="fas fa-phone-alt mr-2 text-gray-400"></i>
                                    Alternate Contact Number
                                </label>
                                <input type="tel" id="alternateContactNumber" name="alternateContactNumber" value="8765432109" class="w-full bg-dark border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200">
                            </div>
                            
                            <div class="mb-4">
                                <label for="address" class="block text-sm font-medium mb-2">
                                    <i class="fas fa-home mr-2 text-gray-400"></i>
                                    Address
                                </label>
                                <textarea id="address" name="address" rows="4" class="w-full bg-dark border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200">123 Main Street, Mumbai, Maharashtra - 400001</textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Login Details Section -->
                    <div class="mb-8">
                        <h3 class="text-highlight-yellow text-lg font-semibold mb-4">Login Details</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="username" class="block text-sm font-medium mb-2">
                                    <i class="fas fa-user-tag mr-2 text-gray-400"></i>
                                    Username
                                </label>
                                <input type="text" id="username" name="username" value="rahul.s" readonly class="w-full bg-dark border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200 bg-opacity-50 cursor-not-allowed">
                                <p class="text-xs text-gray-400 mt-1">Username cannot be changed</p>
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="block text-sm font-medium mb-2">
                                    <i class="fas fa-lock mr-2 text-gray-400"></i>
                                    Password
                                </label>
                                <input type="password" id="password" name="password" placeholder="Enter new password to update" class="w-full bg-dark border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200">
                                <p class="text-xs text-gray-400 mt-1">Leave blank to keep current password</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="flex flex-col">
                        <button type="submit" class="w-full px-6 py-3 bg-somaiya-red text-white font-medium rounded-md hover:bg-opacity-90 transition-all duration-200 transform hover:scale-[1.02] flex items-center justify-center">
                            <i class="fas fa-save mr-2"></i>
                            <span>Update Student</span>
                        </button>
                        <div class="mt-4 text-center">
                            <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                                <i class="fas fa-arrow-left mr-1"></i> Back to Manage Students
                            </a>
                        </div>
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