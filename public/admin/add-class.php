<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include('../../includes/dbconnection.php');

if (!isset($_SESSION['sturecmsaid'])) {
    header('location:login.php');
    exit();
}

if (isset($_POST['submit'])) {
    $classname = $_POST['classname'];
    $section = $_POST['section'];
    $created = date("Y-m-d H:i:s");

    $sql = "INSERT INTO tblclass (ClassName, Section, CreationDate) VALUES (:classname, :section, :created)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':classname', $classname, PDO::PARAM_STR);
    $query->bindParam(':section', $section, PDO::PARAM_STR);
    $query->bindParam(':created', $created, PDO::PARAM_STR);

    if ($query->execute()) {
        echo "<script>alert('✅ Class added successfully!');</script>";
        header("Location: manage-class.php");
        exit();
    } else {
        echo "<script>alert('❌ Failed to add class.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Class | Admin - Padhle</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        dark: '#121212',
                        'dark-light': '#1E1E1E',
                        'somaiya-red': '#D90429',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-dark text-white font-sans min-h-screen flex items-center justify-center">
    <form method="post" class="bg-dark-light p-8 rounded-lg shadow-lg w-full max-w-md border border-gray-700">
        <h2 class="text-xl font-semibold mb-6 text-center text-white flex items-center justify-center gap-2">
            <span class="text-somaiya-red text-2xl font-bold">+</span> Add New Class
        </h2>

        <div class="mb-4">
            <label for="classname" class="block text-sm mb-1 text-white">Class Name</label>
            <input type="text" name="classname" id="classname" required
                   class="w-full px-4 py-2 bg-gray-800 text-white rounded border border-gray-600 focus:outline-none focus:ring-2 focus:ring-somaiya-red">
        </div>

        <div class="mb-6">
            <label for="section" class="block text-sm mb-1 text-white">Section</label>
            <input type="text" name="section" id="section" required
                   class="w-full px-4 py-2 bg-gray-800 text-white rounded border border-gray-600 focus:outline-none focus:ring-2 focus:ring-somaiya-red">
        </div>

        <button type="submit" name="submit"
                class="w-full py-2 px-4 bg-somaiya-red hover:bg-red-700 text-white font-bold rounded transition duration-200">
            Add Class
        </button>
    </form>
</body>
</html>