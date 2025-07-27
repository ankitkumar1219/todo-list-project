<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "todo_app");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Add Task
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task'])) {
    $task = trim($_POST['task']);
    if (!empty($task)) {
        $stmt = $conn->prepare("INSERT INTO tasks (user_id, task) VALUES (?, ?)");
        $stmt->bind_param("is", $_SESSION['user_id'], $task);
        $stmt->execute();
        $stmt->close();
    }
}

// Handle Delete Task
if (isset($_GET['delete'])) {
    $taskId = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $taskId, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();
}

// Fetch all tasks
$stmt = $conn->prepare("SELECT id, task, created_at FROM tasks WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$tasks = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - To-Do</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 30px; }
        .container {
            background: white;
            padding: 40px;
            max-width: 600px;
            margin: auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        h2 {
            color: #333;
        }
        form input[type="text"] {
            width: 70%;
            padding: 10px;
            margin-top: 10px;
        }
        form input[type="submit"] {
            padding: 10px 20px;
            background: green;
            color: white;
            border: none;
            margin-top: 10px;
            cursor: pointer;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            background: #eee;
            margin-top: 10px;
            padding: 10px;
            border-radius: 4px;
        }
        .delete-btn {
            color: red;
            text-decoration: none;
            float: right;
        }
        .logout {
            display: inline-block;
            margin-top: 20px;
            color: white;
            background: crimson;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?> 👋</h2>
        <form method="POST">
            <input type="text" name="task" placeholder="Enter new task" required>
            <input type="submit" value="Add Task">
        </form>

        <ul>
            <?php foreach ($tasks as $task): ?>
                <li>
                    <?= htmlspecialchars($task['task']) ?>
                    <a class="delete-btn" href="?delete=<?= $task['id'] ?>" onclick="return confirm('Delete this task?')">🗑️</a>
                </li>
            <?php endforeach; ?>
        </ul>

        <a class="logout" href="logout.php">Logout</a>
    </div>
</body>
</html>
