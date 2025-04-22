<?php
session_start();
include '../../includes/dbconnection.php';

// Fetch homework details if `editid` is set
if (isset($_GET['editid'])) {
    $id = intval($_GET['editid']);
    $sql = "SELECT * FROM tblhomework WHERE ID=:id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    if (!$result) {
        echo "<script>alert('Homework not found!'); window.location.href='manage-homework.php';</script>";
        exit;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    try {
        // Fetch updated homework details from the form
        $homeworkTitle = $_POST['homeworkTitle'];
        $homeworkDescription = $_POST['homeworkDescription'];
        $lastDateOfSubmission = $_POST['lastDateOfSubmission'];
        $classId = $_POST['classId'];

        // Update query
        $sql = "UPDATE tblhomework SET 
                    HomeworkTitle=:homeworkTitle, 
                    homeworkDescription=:homeworkDescription, 
                    lastDateOfSubmission=:lastDateOfSubmission, 
                    ClassId=:classId 
                WHERE ID=:id";
        $query = $dbh->prepare($sql);

        // Bind parameters
        $query->bindParam(':homeworkTitle', $homeworkTitle);
        $query->bindParam(':homeworkDescription', $homeworkDescription);
        $query->bindParam(':lastDateOfSubmission', $lastDateOfSubmission);
        $query->bindParam(':classId', $classId);
        $query->bindParam(':id', $id);

        // Execute query
        if ($query->execute()) {
            echo "<script>alert('Homework updated successfully!'); window.location.href='manage-homework.php';</script>";
            exit;
        } else {
            throw new Exception("Failed to update homework.");
        }
    } catch (Exception $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Homework</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-dark text-white font-sans">
    <div class="flex h-screen overflow-hidden">
        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto p-6">
            <h1 class="text-2xl font-bold mb-6">Edit Homework</h1>
            <form method="POST" class="bg-dark-lighter border border-dark-border rounded-lg p-6">
                <div class="mb-4">
                    <label for="homeworkTitle" class="block text-sm font-medium mb-2">Homework Title</label>
                    <input type="text" id="homeworkTitle" name="homeworkTitle" value="<?php echo htmlentities($result->HomeworkTitle); ?>" class="w-full bg-dark border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200" required>
                </div>
                <div class="mb-4">
                    <label for="homeworkDescription" class="block text-sm font-medium mb-2">Homework Description</label>
                    <textarea id="homeworkDescription" name="homeworkDescription" rows="4" class="w-full bg-dark border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200" required><?php echo htmlentities($result->homeworkDescription); ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="lastDateOfSubmission" class="block text-sm font-medium mb-2">Last Date of Submission</label>
                    <input type="date" id="lastDateOfSubmission" name="lastDateOfSubmission" value="<?php echo htmlentities($result->lastDateOfSubmission); ?>" class="w-full bg-dark border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200" required>
                </div>
                <div class="mb-4">
                    <label for="classId" class="block text-sm font-medium mb-2">Class</label>
                    <select id="classId" name="classId" class="w-full bg-dark border border-dark-border rounded-md py-2 px-4 text-white focus:outline-none focus:border-somaiya-red transition-colors duration-200" required>
                        <?php
                        $classQuery = $dbh->prepare("SELECT ID, ClassName, Section FROM tblclass");
                        $classQuery->execute();
                        $classes = $classQuery->fetchAll(PDO::FETCH_OBJ);
                        foreach ($classes as $class) {
                            $selected = $class->ID == $result->ClassId ? 'selected' : '';
                            echo "<option value='{$class->ID}' {$selected}>{$class->ClassName} - {$class->Section}</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" name="update" class="w-full px-6 py-3 bg-somaiya-red text-white font-medium rounded-md hover:bg-opacity-90 transition-all duration-200 transform hover:scale-[1.02]">
                    Update Homework
                </button>
            </form>
        </main>
    </div>
</body>
</html>