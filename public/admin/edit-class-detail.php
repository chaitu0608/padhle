<?php
session_start();
include('../../includes/dbconnection.php');

if (!isset($_SESSION['sturecmsaid'])) {
    header('location:login.php');
    exit();
}

if (isset($_POST['update'])) {
    $cid = intval($_GET['editid']);
    $classname = $_POST['classname'];
    $section = $_POST['section'];

    $sql = "UPDATE tblclass SET ClassName=:classname, Section=:section WHERE ID=:cid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':classname', $classname, PDO::PARAM_STR);
    $query->bindParam(':section', $section, PDO::PARAM_STR);
    $query->bindParam(':cid', $cid, PDO::PARAM_INT);

    if ($query->execute()) {
        echo "<script>alert('Class updated successfully!'); window.location.href='manage-class.php';</script>";
    } else {
        echo "<script>alert('Something went wrong. Try again!');</script>";
    }
}

$cid = intval($_GET['editid']);
$sql = "SELECT * FROM tblclass WHERE ID=:cid";
$query = $dbh->prepare($sql);
$query->bindParam(':cid', $cid, PDO::PARAM_INT);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);
?>

<?php
session_start();
include('../includes/dbconnection.php');

if (!isset($_SESSION['sturecmsaid'])) {
    header('location:login.php');
    exit();
}

if (isset($_POST['update'])) {
    $cid = intval($_GET['editid']);
    $classname = $_POST['classname'];
    $section = $_POST['section'];

    $sql = "UPDATE tblclass SET ClassName=:classname, Section=:section WHERE ID=:cid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':classname', $classname, PDO::PARAM_STR);
    $query->bindParam(':section', $section, PDO::PARAM_STR);
    $query->bindParam(':cid', $cid, PDO::PARAM_INT);

    if ($query->execute()) {
        echo "<script>alert('Class updated successfully!'); window.location.href='manage-class.php';</script>";
    } else {
        echo "<script>alert('Something went wrong. Try again!');</script>";
    }
}

$cid = intval($_GET['editid']);
$sql = "SELECT * FROM tblclass WHERE ID=:cid";
$query = $dbh->prepare($sql);
$query->bindParam(':cid', $cid, PDO::PARAM_INT);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Class - Admin | Padhle</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            dark: '#121212',
            'dark-lighter': '#1e1e1e',
            'dark-border': '#333333',
            'highlight-yellow': '#FFD700',
            'somaiya-red': '#D90429',
          }
        }
      }
    }
  </script>
</head>
<body class="bg-dark text-white min-h-screen flex items-center justify-center font-sans px-4">
  <div class="w-full max-w-md bg-dark-lighter p-6 rounded-lg border border-dark-border shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-highlight-yellow text-center">Edit Class</h2>
    <form method="post">
      <div class="mb-4">
        <label for="classname" class="block text-sm mb-1">Class Name</label>
        <input type="text" id="classname" name="classname" required 
               value="<?php echo htmlentities($result->ClassName); ?>"
               class="w-full px-4 py-2 bg-dark border border-dark-border rounded focus:outline-none focus:border-somaiya-red text-white placeholder-gray-400">
      </div>
      <div class="mb-6">
        <label for="section" class="block text-sm mb-1">Section</label>
        <input type="text" id="section" name="section" required 
               value="<?php echo htmlentities($result->Section); ?>"
               class="w-full px-4 py-2 bg-dark border border-dark-border rounded focus:outline-none focus:border-somaiya-red text-white placeholder-gray-400">
      </div>
      <div class="flex justify-between">
        <a href="manage-class.php" class="text-sm text-gray-400 hover:text-highlight-yellow">← Back to Classes</a>
        <button type="submit" name="update" class="bg-somaiya-red hover:bg-red-700 text-white px-4 py-2 rounded">
          Update
        </button>
      </div>
    </form>
  </div>
</body>
</html>