<?php
include 'db.php';

// Add task if form submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task = trim($_POST['task']);
    if (!empty($task)) {
        $stmt = $conn->prepare("INSERT INTO tasks (task) VALUES (?)");
        $stmt->bind_param("s", $task);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: index.php");
    exit();
}

// Get all tasks
$result = $conn->query("SELECT * FROM tasks ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>PHP To-Do List</title>
    <style>
        body {
            font-family: Arial;
            max-width: 500px;
            margin: auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        h2 {
            text-align: center;
        }
        form input[type="text"] {
            width: 75%;
            padding: 10px;
        }
        form input[type="submit"] {
            padding: 10px;
        }
        ul {
            list-style-type: none;
            padding-left: 0;
        }
        li {
            background: #fff;
            margin: 10px 0;
            padding: 10px;
            border-left: 5px solid #007bff;
            display: flex;
            justify-content: space-between;
        }
        a {
            color: red;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <h2>To-Do List</h2>

    <form method="POST" action="">
        <input type="text" name="task" placeholder="Enter new task" required>
        <input type="submit" value="Add">
    </form>

    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li>
                <?php echo htmlspecialchars($row['task']); ?>
                <a href="delete.php?id=<?php echo $row['id']; ?>">✖</a>
            </li>
        <?php endwhile; ?>
    </ul>

</body>
</html>
