<?php
session_start();
if (!isset($_SESSION['sturecmsstuid'])) {
    header("Location: login.php");
    exit();
}

include("../includes/dbconnection.php");
$sid = $_SESSION['sturecmsstuid'];

if (isset($_GET['hwid'])) {
    $hwid = intval($_GET['hwid']);
} else {
    die("Homework ID missing.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pdf'])) {
    $file = $_FILES['pdf'];
    $targetDir = "../uploads/homework_submissions/";
    $filename = uniqid() . "_" . basename($file["name"]);
    $targetFilePath = $targetDir . $filename;

    if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
        $sql = "INSERT INTO tbluploadedhomeworks (StuID, HomeworkID, FilePath) VALUES (:sid, :hwid, :path)";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':sid', $sid);
        $stmt->bindParam(':hwid', $hwid);
        $stmt->bindParam(':path', $filename);
        $stmt->execute();
        $success = "Homework submitted successfully! ✅";
    } else {
        $error = "Upload failed.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Submit Homework</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
</head>
<body class="bg-black text-white p-10">
    <div class="max-w-lg mx-auto bg-gray-900 border border-gray-700 p-8 rounded">
        <h2 class="text-2xl font-bold text-yellow-400 mb-4">📤 Submit Homework</h2>

        <?php if (!empty($success)): ?>
            <p class="text-green-400 mb-4"><?php echo $success; ?></p>
        <?php elseif (!empty($error)): ?>
            <p class="text-red-400 mb-4"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <label class="block mb-2">Upload PDF:</label>
            <input type="file" name="pdf" accept=".pdf" required class="mb-4 bg-gray-800 text-white p-2 rounded w-full">
            <button type="submit" class="bg-somaiya-red px-4 py-2 rounded hover:bg-opacity-80">Submit</button>
        </form>
        <a href="homework.php" class="inline-block mt-4 text-sm text-yellow-300">← Back to Homework</a>
    </div>
</body>
</html>