<?php

require_once "auth.php";
require_once "config.php";


$studentId = $_SESSION["student_id"];

$studentName = $_SESSION["student_name"];


/* Total quizzes */

$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM quiz_attempts
    WHERE student_id = ?
");

$stmt->execute([$studentId]);

$totalQuizzes = $stmt->fetchColumn();


/* Average score */

$stmt = $pdo->prepare("
    SELECT AVG(percentage)
    FROM quiz_attempts
    WHERE student_id = ?
");

$stmt->execute([$studentId]);

$averageScore = $stmt->fetchColumn();

$averageScore = $averageScore
    ? round($averageScore)
    : 0;


/* Best score */

$stmt = $pdo->prepare("
    SELECT MAX(percentage)
    FROM quiz_attempts
    WHERE student_id = ?
");

$stmt->execute([$studentId]);

$bestScore = $stmt->fetchColumn();

$bestScore = $bestScore
    ? round($bestScore)
    : 0;


/* Total questions answered */

$stmt = $pdo->prepare("
    SELECT COALESCE(SUM(total_questions), 0)
    FROM quiz_attempts
    WHERE student_id = ?
");

$stmt->execute([$studentId]);

$totalQuestions = $stmt->fetchColumn();


/* Recent quiz history */

$stmt = $pdo->prepare("
    SELECT
        qa.score,
        qa.total_questions,
        qa.percentage,
        qa.created_at,
        s.name AS subject_name
    FROM quiz_attempts qa

    LEFT JOIN subjects s
        ON qa.subject_id = s.id

    WHERE qa.student_id = ?

    ORDER BY qa.id DESC

    LIMIT 10
");

$stmt->execute([$studentId]);

$history = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>DigiQuiz - Dashboard</title>

    <link rel="stylesheet"
          href="assets/css/style.css">


    <style>

        .dashboard {
            max-width: 1200px;
            margin: auto;
            padding: 40px 20px;
        }


        .welcome {

            background: #111827;

            border: 1px solid #1f2937;

            border-radius: 18px;

            padding: 30px;

            margin-bottom: 25px;

        }


        .welcome h1 {

            margin: 0 0 8px;

            font-size: 30px;

        }


        .welcome p {

            color: #9ca3af;

            margin: 0;

        }


        /* Statistics */

        .stats {

            display: grid;

            grid-template-columns:
                repeat(4, 1fr);

            gap: 18px;

            margin-bottom: 30px;

        }


        .stat-card {

            background: #111827;

            border: 1px solid #1f2937;

            border-radius: 15px;

            padding: 22px;

        }


        .stat-icon {

            font-size: 25px;

            margin-bottom: 12px;

        }


        .stat-title {

            color: #9ca3af;

            font-size: 14px;

            margin-bottom: 8px;

        }


        .stat-value {

            font-size: 28px;

            font-weight: 800;

        }


        /* History */

        .history-card {

            background: #111827;

            border: 1px solid #1f2937;

            border-radius: 16px;

            padding: 25px;

        }


        .history-card h2 {

            margin-top: 0;

        }


        .table-wrapper {

            overflow-x: auto;

        }


        table {

            width: 100%;

            border-collapse: collapse;

        }


        th,
        td {

            padding: 15px;

            text-align: left;

            border-bottom:
                1px solid #1f2937;

        }


        th {

            color: #9ca3af;

            font-size: 14px;

        }


        .score-good {

            color: #86efac;

            font-weight: 700;

        }


        .score-normal {

            color: #60a5fa;

            font-weight: 700;

        }


        .score-low {

            color: #fca5a5;

            font-weight: 700;

        }


        .empty {

            text-align: center;

            padding: 35px;

            color: #9ca3af;

        }


        .actions {

            display: flex;

            gap: 12px;

            margin-top: 20px;

        }


        .action-btn {

            display: inline-block;

            padding: 11px 18px;

            background: #2563eb;

            color: white;

            text-decoration: none;

            border-radius: 9px;

        }


        .logout-btn {

            background: #374151;

        }


        @media (max-width: 850px) {

            .stats {

                grid-template-columns:
                    repeat(2, 1fr);

            }

        }


        @media (max-width: 500px) {

            .dashboard {

                padding: 25px 15px;

            }

            .stats {

                grid-template-columns: 1fr;

            }

            .welcome h1 {

                font-size: 24px;

            }

        }

    </style>

</head>


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



<body>


<div class="dashboard">


    <!-- Welcome -->

    <div class="welcome">

        <h1>
            👋 Welcome, <?= htmlspecialchars($studentName) ?>!
        </h1>

        <p>
            Track your learning progress and keep improving.
        </p>


        <div class="actions">

            <a
                href="quiz.php"
                class="action-btn"
            >
                🧠 Take Quiz
            </a>


            <a
                href="flashcards.php"
                class="action-btn"
            >
                📖 Flashcards
            </a>


            <a
                href="logout.php"
                class="action-btn logout-btn"
            >
                Logout
            </a>

        </div>

    </div>


    <!-- Statistics -->

    <div class="stats">


        <div class="stat-card">

            <div class="stat-icon">
                🧠
            </div>

            <div class="stat-title">
                Quizzes Completed
            </div>

            <div class="stat-value">
                <?= $totalQuizzes ?>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-icon">
                📊
            </div>

            <div class="stat-title">
                Average Score
            </div>

            <div class="stat-value">
                <?= $averageScore ?>%
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-icon">
                🏆
            </div>

            <div class="stat-title">
                Best Score
            </div>

            <div class="stat-value">
                <?= $bestScore ?>%
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-icon">
                📝
            </div>

            <div class="stat-title">
                Questions Answered
            </div>

            <div class="stat-value">
                <?= $totalQuestions ?>
            </div>

        </div>

    </div>


    <!-- Quiz History -->

    <div class="history-card">

        <h2>
            📈 Recent Quiz History
        </h2>


        <?php if (empty($history)): ?>

            <div class="empty">

                <h3>No quiz history yet.</h3>

                <p>
                    Complete your first quiz to see
                    your progress here.
                </p>

            </div>

        <?php else: ?>


            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>Subject</th>

                            <th>Score</th>

                            <th>Percentage</th>

                            <th>Date</th>

                        </tr>

                    </thead>


                    <tbody>


                        <?php foreach ($history as $attempt): ?>


                            <?php

                            $percentage =
                                round(
                                    $attempt["percentage"]
                                );


                            if ($percentage >= 80) {

                                $scoreClass =
                                    "score-good";

                            } elseif (
                                $percentage >= 50
                            ) {

                                $scoreClass =
                                    "score-normal";

                            } else {

                                $scoreClass =
                                    "score-low";

                            }

                            ?>


                            <tr>

                                <td>

                                    <?= htmlspecialchars(
                                        $attempt["subject_name"]
                                        ?? "Unknown"
                                    ) ?>

                                </td>


                                <td>

                                    <?= $attempt["score"] ?>

                                    /

                                    <?= $attempt[
                                        "total_questions"
                                    ] ?>

                                </td>


                                <td
                                    class="<?= $scoreClass ?>"
                                >

                                    <?= $percentage ?>%

                                </td>


                                <td>

                                    <?= date(
                                        "d M Y",
                                        strtotime(
                                            $attempt["created_at"]
                                        )
                                    ) ?>

                                </td>

                            </tr>


                        <?php endforeach; ?>


                    </tbody>

                </table>

            </div>


        <?php endif; ?>

    </div>

</div>


</body>

</html>
```
