<?php

session_start();

require_once "config.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    $stmt = $pdo->prepare("
        SELECT
            id,
            name,
            email,
            password_hash
        FROM students
        WHERE email = ?
    ");

    $stmt->execute([$email]);

    $student = $stmt->fetch();

    if (
        $student &&
        password_verify(
            $password,
            $student["password_hash"]
        )
    ) {

        session_regenerate_id(true);

        $_SESSION["student_id"] =
            $student["id"];

        $_SESSION["student_name"] =
            $student["name"];

        $_SESSION["student_email"] =
            $student["email"];

        header("Location: dashboard.php");
        exit;

    } else {

        $message = "Invalid email or password.";

    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login - DigiQuiz</title>

    <link rel="stylesheet"
          href="assets/css/style.css">

    <style>

        .auth-page {
            min-height: 80vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .auth-card {
            width: 100%;
            max-width: 450px;
            background: #111827;
            border: 1px solid #1f2937;
            border-radius: 18px;
            padding: 35px;
        }

        .auth-card h1 {
            text-align: center;
            margin-bottom: 10px;
        }

        .auth-subtitle {
            text-align: center;
            color: #9ca3af;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            box-sizing: border-box;
            padding: 13px;
            background: #0f172a;
            border: 1px solid #374151;
            border-radius: 9px;
            color: white;
            font-size: 15px;
        }

        .auth-btn {
            width: 100%;
            padding: 14px;
            background: #2563eb;
            border: none;
            border-radius: 9px;
            color: white;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
        }

        .auth-btn:hover {
            background: #1d4ed8;
        }

        .error-message {
            background: #451a1a;
            color: #fca5a5;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 20px;
        }

        .auth-link {
            text-align: center;
            margin-top: 22px;
            color: #9ca3af;
        }

        .auth-link a {
            color: #60a5fa;
            text-decoration: none;
        }

    </style>

</head>

<body>
    
<!-- ================= NAVBAR ================= -->

<header class="navbar">

    <div class="container nav-container">

        <!-- Logo -->
        <a href="index.php" class="logo">

            <div class="logo-icon">
                <i data-lucide="brain-circuit"></i>
            </div>

            <span>Digi<span>Quiz</span></span>

        </a>


        <!-- Navigation -->

        <nav class="nav-links">

            <a href="index.php" class="active">Home</a>

            <a href="subjects.php">Subjects</a>

            <a href="syllabus.php">Syllabus</a>

            <a href="quiz.php">Quiz</a>

            <a href="flashcards.php">Flashcards</a>

            <a href="dashboard.php">Dashboard</a>

        </nav>


        <!-- Buttons -->

        <div class="nav-buttons">

            <?php if (!empty($_SESSION["student_id"])): ?>
                <a href="logout.php" class="register-btn">
                    Log Out
                </a>
            <?php else: ?>
                <a href="login.php" class="login-btn">
                    Login
                </a>

                <a href="register.php" class="register-btn">
                    Get Started
                </a>
            <?php endif; ?>

        </div>


        <!-- Mobile Menu -->

        <button class="menu-btn" id="menuBtn">

            <i data-lucide="menu"></i>

        </button>

    </div>

</header>


<div class="auth-page">

    <div class="auth-card">

        <h1>🔐 Welcome Back</h1>

        <p class="auth-subtitle">
            Login to continue learning.
        </p>


        <?php if ($message !== ""): ?>

            <div class="error-message">

                <?= htmlspecialchars($message) ?>

            </div>

        <?php endif; ?>


        <form method="POST">

            <div class="form-group">

                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    placeholder="Enter your email"
                    required
                >

            </div>


            <div class="form-group">

                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    placeholder="Enter your password"
                    required
                >

            </div>


            <button
                type="submit"
                class="auth-btn"
            >
                Login
            </button>

        </form>


        <div class="auth-link">

            Don't have an account?

            <a href="register.php">
                Create Account
            </a>

        </div>

    </div>

</div>

</body>

</html>
```
