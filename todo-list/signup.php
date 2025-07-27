<?php
include 'db.php';

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $error = "Email already registered.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            $success = "Registration successful. You can now <a href='login.php'>login</a>.";
        } else {
            $error = "Something went wrong. Please try again.";
        }
        $stmt->close();
    }
    $check->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; }
        form { background: #fff; padding: 30px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input { display: block; width: 100%; padding: 10px; margin-bottom: 15px; }
        .message { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Signup</h2>
        <?php if ($success): ?><p class="message"><?= $success ?></p><?php endif; ?>
        <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>

        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </form>
</body>
</html>
