<?php

require_once "auth.php";
require_once "config.php";

/*
 * Match the display cards with the subjects stored in the database.
 * This lets the Quiz and Flashcards buttons open the selected subject directly.
 */
$dbSubjectsByName = [];
try {
    $dbSubjectRows = $pdo->query("SELECT id, name, subject_code FROM subjects")->fetchAll();
    foreach ($dbSubjectRows as $row) {
        $dbSubjectsByName[mb_strtolower(trim($row["name"]))] = $row;
    }
} catch (PDOException $e) {
    // If the database has not been updated yet, the page still displays its subject cards.
    $dbSubjectRows = [];
}

$subjects = [
    1 => [
        ["name" => "Python Programming", "icon" => "code-2", "desc" => "Learn Python basics, syntax, functions and programming concepts."],
        ["name" => "Introduction to IT System", "icon" => "monitor", "desc" => "Understand computer systems, hardware, software and IT fundamentals."]
    ],

    2 => [
        ["name" => "Advance Python Programming", "icon" => "braces", "desc" => "Explore advanced Python programming and problem-solving techniques."],
        ["name" => "Fundamentals of Software Development", "icon" => "laptop", "desc" => "Learn software development concepts, processes and methodologies."]
    ],

    3 => [
        ["name" => "Data Structure with Python", "icon" => "network", "desc" => "Learn arrays, stacks, queues, linked lists, trees and algorithms."],
        ["name" => "Database Management System", "icon" => "database", "desc" => "Understand databases, SQL, tables, relationships and queries."],
        ["name" => "Cryptography with Web Security", "icon" => "shield-check", "desc" => "Learn encryption, cryptography and web security concepts."],
        ["name" => "Object Oriented Programming with Java", "icon" => "coffee", "desc" => "Learn Java and object-oriented programming concepts."],
        ["name" => "Digital Marketing", "icon" => "megaphone", "desc" => "Understand SEO, social media, email marketing and digital strategies."],
        ["name" => "Operating System", "icon" => "settings", "desc" => "Learn processes, memory, files, scheduling and operating system concepts."]
    ],

    4 => [
        ["name" => "Fundamentals of Machine Learning", "icon" => "brain", "desc" => "Learn machine learning basics, algorithms and model concepts."],
        ["name" => "Cyber Security and Digital Forensics", "icon" => "shield", "desc" => "Understand cybersecurity, threats, attacks and digital forensics."],
        ["name" => "Mobile Computing Networks", "icon" => "wifi", "desc" => "Learn mobile communication, networks and wireless technologies."],
        ["name" => "Entrepreneurship and Startup", "icon" => "rocket", "desc" => "Learn startup ideas, business models and entrepreneurship."],
        ["name" => "Mobile Application Development", "icon" => "smartphone", "desc" => "Learn concepts and technologies used to develop mobile applications."],
        ["name" => "Data Mining and Warehousing", "icon" => "bar-chart-3", "desc" => "Learn data mining, data warehouses and knowledge discovery."]
    ],

    5 => [
        ["name" => "AI Product Design", "icon" => "sparkles", "desc" => "Learn how AI-powered products are designed and developed."],
        ["name" => "Artificial Intelligence with Prompt Engineering", "icon" => "bot", "desc" => "Learn AI concepts and effective prompt engineering techniques."],
        ["name" => "Cloud and Data Centre Technology", "icon" => "cloud", "desc" => "Understand cloud computing, servers and data centre technologies."],
        ["name" => "Foundation of Blockchain", "icon" => "blocks", "desc" => "Learn blockchain fundamentals, transactions and decentralized systems."]
    ],

    6 => []
];

$selectedSemester = isset($_GET['semester']) ? (int)$_GET['semester'] : 1;

if ($selectedSemester < 1 || $selectedSemester > 6) {
    $selectedSemester = 1;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Subjects | DigiQuiz</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/lucide@latest"></script>

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/subjects.css">
    <style>
        .subject-code {
            margin: 10px 0 12px;
            color: #9ca3af;
            font-size: 13px;
        }
        .subject-code strong {
            color: #60a5fa;
            font-weight: 700;
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


<main>

    <!-- Page Header -->
    <section class="subjects-hero">

        <div class="hero-badge">
            <i data-lucide="book-open"></i>
            DIPLOMA IT SUBJECTS
        </div>

        <h1>
            Explore Your <span>Subjects</span>
        </h1>

        <p>
            Select your semester and start learning with
            AI-powered quizzes, flashcards and personalized practice.
        </p>

    </section>


    <!-- Semester Selection -->
    <section class="semester-section">

        <div class="semester-header">
            <div>
                <h2>Select Semester</h2>
                <p>Choose your current semester to view subjects.</p>
            </div>

            <span class="subject-count">
                <?= count($subjects[$selectedSemester]) ?> Subjects
            </span>
        </div>


        <div class="semester-tabs">

            <?php for ($i = 1; $i <= 6; $i++): ?>

                <a
                    href="subjects.php?semester=<?= $i ?>"
                    class="semester-tab <?= $selectedSemester === $i ? 'selected' : '' ?>"
                >
                    <span>Sem</span>
                    <strong><?= $i ?></strong>
                </a>

            <?php endfor; ?>

        </div>

    </section>


    <!-- Subjects -->
    <section class="subjects-section">

        <div class="section-title">
            <div>
                <span class="small-label">SEMESTER <?= $selectedSemester ?></span>

                <h2>
                    <?= count($subjects[$selectedSemester]) > 0
                        ? "Your Subjects"
                        : "Subjects Coming Soon" ?>
                </h2>
            </div>
        </div>


        <?php if (count($subjects[$selectedSemester]) > 0): ?>

            <div class="subject-grid">

                <?php foreach ($subjects[$selectedSemester] as $index => $subject):
                    $dbSubject = $dbSubjectsByName[mb_strtolower(trim($subject['name']))] ?? null;
                    $subjectCode = trim((string)($dbSubject['subject_code'] ?? ''));
                    $subjectIdForLink = (int)($dbSubject['id'] ?? 0);
                    $quizUrl = $subjectIdForLink > 0
                        ? "quiz.php?subject=" . $subjectIdForLink
                        : "quiz.php?subject_name=" . urlencode($subject['name']);
                    $flashcardsUrl = $subjectIdForLink > 0
                        ? "flashcards.php?subject=" . $subjectIdForLink
                        : "flashcards.php?subject_name=" . urlencode($subject['name']);
                    ?>

                    <div class="subject-card">

                        <div class="subject-top">

                            <div class="subject-icon">
                                <i data-lucide="<?= $subject['icon'] ?>"></i>
                            </div>

                            <span class="subject-number">
                                <?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?>
                            </span>

                        </div>

                        <h3>
                            <?= htmlspecialchars($subject['name']) ?>
                        </h3>

                        <div class="subject-code">
                            Subject Code:
                            <strong><?= htmlspecialchars($subjectCode !== '' ? $subjectCode : 'Not added yet') ?></strong>
                        </div>

                        <p>
                            <?= htmlspecialchars($subject['desc']) ?>
                        </p>


                        <div class="subject-actions">

                            <a href="<?= htmlspecialchars($quizUrl, ENT_QUOTES, 'UTF-8') ?>"
                               class="action-btn quiz-btn">
                                <i data-lucide="brain"></i>
                                Quiz
                            </a>

                            <a href="<?= htmlspecialchars($flashcardsUrl, ENT_QUOTES, 'UTF-8') ?>"
                               class="action-btn flashcard-btn">
                                <i data-lucide="layers"></i>
                                Flashcards
                            </a>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="coming-soon">

                <div class="coming-icon">
                    <i data-lucide="clock-3"></i>
                </div>

                <h3>Semester 6 is in progress</h3>

                <p>
                    Subjects for Semester 6 will be added soon by the administrator.
                </p>

                <a href="index.php" class="btn btn-primary">
                    Back to Home
                </a>

            </div>

        <?php endif; ?>

    </section>


    <!-- AI Banner -->
    <section class="ai-banner">

        <div class="ai-banner-icon">
            <i data-lucide="sparkles"></i>
        </div>

        <div class="ai-banner-content">
            <span>POWERED BY AI</span>

            <h2>
                Your syllabus. Your practice. Your progress.
            </h2>

            <p>
                DigiQuiz uses your selected subject syllabus to create
                personalized quizzes and flashcards.
            </p>
        </div>

        <a href="quiz.php" class="btn btn-primary">
            Start Learning
            <i data-lucide="arrow-right"></i>
        </a>

    </section>

</main>


<footer class="footer">

    <div class="footer-container">

        <div>
            <div class="logo">
                <span class="logo-icon">
                    <i data-lucide="brain-circuit"></i>
                </span>
                <span>Digi<span>Quiz</span></span>
            </div>

            <p>
                AI-powered learning for Diploma IT students.
            </p>
        </div>

        <div class="footer-links">
            <a href="index.php">Home</a>
            <a href="subjects.php">Subjects</a>
            <a href="quiz.php">Quiz</a>
            <a href="flashcards.php">Flashcards</a>
        </div>

    </div>

    <div class="footer-bottom">
        © <?= date('Y') ?> DigiQuiz. All rights reserved.
    </div>

</footer>


<script src="assets/js/subjects.js"></script>

</body>
</html>