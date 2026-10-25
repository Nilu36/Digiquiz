<?php

require_once "auth.php";

require_once "config.php";

/* Get subjects that have questions */
$stmt = $pdo->query("
    SELECT DISTINCT
        s.id,
        s.name,
        s.subject_code,
        s.semester
    FROM subjects s
    INNER JOIN quiz_questions q
        ON s.id = q.subject_id
    ORDER BY s.semester, s.name
");

$subjects = $stmt->fetchAll();


/* Selected subject */
$subjectId = isset($_GET['subject'])
    ? intval($_GET['subject'])
    : 0;

/* Allow a subject name as a fallback when opening from the subjects page. */
if ($subjectId <= 0 && !empty($_GET['subject_name'])) {
    $subjectLookup = $pdo->prepare("SELECT id FROM subjects WHERE name = ? LIMIT 1");
    $subjectLookup->execute([trim($_GET['subject_name'])]);
    $subjectId = (int)($subjectLookup->fetchColumn() ?: 0);
}

$selectedSubject = null;
if ($subjectId > 0) {
    $subjectLookup = $pdo->prepare("SELECT id, name, subject_code, semester FROM subjects WHERE id = ? LIMIT 1");
    $subjectLookup->execute([$subjectId]);
    $selectedSubject = $subjectLookup->fetch() ?: null;
    if (!$selectedSubject) {
        $subjectId = 0;
    }
}

$questions = [];

if ($subjectId > 0) {

    /*
     * Get random questions.
     * Change 10 to the number of questions
     * you want in one quiz.
     */
    $stmt = $pdo->prepare("
        SELECT
            id,
            question,
            option_a,
            option_b,
            option_c,
            option_d,
            correct_answer,
            explanation
        FROM quiz_questions
        WHERE subject_id = ?
        ORDER BY RAND()
        LIMIT 10
    ");

    $stmt->execute([$subjectId]);

    $questions = $stmt->fetchAll();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>DigiQuiz - Quiz</title>

    <link rel="stylesheet"
          href="assets/css/style.css">

    <style>

        .quiz-page {
            max-width: 1000px;
            margin: auto;
            padding: 40px 20px;
        }

        .quiz-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .quiz-header h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }

        .quiz-header p {
            color: #9ca3af;
        }

        .selected-subject {
            margin-top: 12px;
            color: #60a5fa !important;
            font-weight: 700;
        }

        /* Subject selection */

        .subject-box {
            max-width: 600px;
            margin: 50px auto;
            background: #111827;
            border: 1px solid #1f2937;
            border-radius: 16px;
            padding: 30px;
        }

        .subject-box label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .subject-box select {
            width: 100%;
            padding: 14px;
            border-radius: 10px;
            border: 1px solid #374151;
            background: #0f172a;
            color: white;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .start-btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            background: #2563eb;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }

        .start-btn:hover {
            background: #1d4ed8;
        }

        /* Timer */

        .quiz-topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #111827;
            border: 1px solid #1f2937;
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            position: sticky;
            top: 10px;
            z-index: 10;
        }

        .timer {
            font-size: 18px;
            font-weight: 700;
            color: #60a5fa;
        }

        /* Question */

        .question-card {
            background: #111827;
            border: 1px solid #1f2937;
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 20px;
        }

        .question-number {
            color: #60a5fa;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .question {
            font-size: 19px;
            font-weight: 600;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .options {
            display: grid;
            gap: 12px;
        }

        .option {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px;
            background: #1f2937;
            border: 1px solid #374151;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.2s;
        }

        .option:hover {
            border-color: #3b82f6;
        }

        .option input {
            accent-color: #2563eb;
        }

        .submit-btn {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 12px;
            background: #2563eb;
            color: white;
            font-size: 17px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 10px;
        }

        .submit-btn:hover {
            background: #1d4ed8;
        }

        .empty-message {
            text-align: center;
            padding: 50px;
            background: #111827;
            border-radius: 15px;
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



<div class="quiz-page">

    <div class="quiz-header">

        <h1>🧠 AI Quiz</h1>

        <p>
            Test your knowledge with questions generated
            from your syllabus.
        </p>

        <?php if (!empty($selectedSubject)): ?>
            <p class="selected-subject">
                <?= htmlspecialchars($selectedSubject['subject_code'] ?? '') ?>
                <?= !empty($selectedSubject['subject_code']) ? ' — ' : '' ?>
                <?= htmlspecialchars($selectedSubject['name']) ?>
            </p>
        <?php endif; ?>

    </div>


    <?php if ($subjectId === 0): ?>

        <!-- SUBJECT SELECTION -->

        <div class="subject-box">

            <h2>Select Subject</h2>

            <p>
                Choose a subject to start your quiz.
            </p>

            <form method="GET">

                <label for="subject">
                    Subject
                </label>

                <select
                    name="subject"
                    id="subject"
                    required
                >

                    <option value="">
                        Select a subject
                    </option>

                    <?php foreach ($subjects as $subject): ?>

                        <option
                            value="<?= $subject['id'] ?>"
                        >

                            Semester <?= (int)$subject['semester'] ?> -
                            <?= htmlspecialchars(trim((string)($subject['subject_code'] ?? '')) !== '' ? $subject['subject_code'] . ' - ' : '') ?>
                            <?= htmlspecialchars($subject['name']) ?>

                        </option>

                    <?php endforeach; ?>

                </select>


                <button
                    type="submit"
                    class="start-btn"
                >
                    Start Quiz →
                </button>

            </form>

        </div>


    <?php elseif (empty($questions)): ?>

        <div class="empty-message">

            <h2>No Questions Available</h2>

            <p>
                This subject does not have generated
                questions yet.
            </p>

            <a href="quiz.php">
                Choose another subject
            </a>

        </div>


    <?php else: ?>

        <!-- QUIZ -->

        <form
            method="POST"
            action="quiz-result.php"
            id="quizForm"
        >

            <input
                type="hidden"
                name="subject_id"
                value="<?= $subjectId ?>"
            >


            <div class="quiz-topbar">

                <strong>
                    <?= count($questions) ?> Questions
                </strong>

                <div class="timer">
                    ⏱️
                    <span id="timer">
                        10:00
                    </span>
                </div>

            </div>


            <?php foreach ($questions as $index => $q): ?>

                <div class="question-card">

                    <div class="question-number">

                        Question <?= $index + 1 ?>
                        of <?= count($questions) ?>

                    </div>


                    <div class="question">

                        <?= htmlspecialchars(
                            $q['question']
                        ) ?>

                    </div>


                    <div class="options">

                        <label class="option">

                            <input
                                type="radio"
                                name="answers[<?= $q['id'] ?>]"
                                value="A"
                            >

                            <span>
                                <strong>A.</strong>
                                <?= htmlspecialchars(
                                    $q['option_a']
                                ) ?>
                            </span>

                        </label>


                        <label class="option">

                            <input
                                type="radio"
                                name="answers[<?= $q['id'] ?>]"
                                value="B"
                            >

                            <span>
                                <strong>B.</strong>
                                <?= htmlspecialchars(
                                    $q['option_b']
                                ) ?>
                            </span>

                        </label>


                        <label class="option">

                            <input
                                type="radio"
                                name="answers[<?= $q['id'] ?>]"
                                value="C"
                            >

                            <span>
                                <strong>C.</strong>
                                <?= htmlspecialchars(
                                    $q['option_c']
                                ) ?>
                            </span>

                        </label>


                        <label class="option">

                            <input
                                type="radio"
                                name="answers[<?= $q['id'] ?>]"
                                value="D"
                            >

                            <span>
                                <strong>D.</strong>
                                <?= htmlspecialchars(
                                    $q['option_d']
                                ) ?>
                            </span>

                        </label>

                    </div>

                </div>

            <?php endforeach; ?>


            <button
                type="submit"
                class="submit-btn"
            >
                Submit Quiz
            </button>

        </form>


        <script>

            let timeLeft = 600;

            const timer = document.getElementById("timer");

            const quizForm =
                document.getElementById("quizForm");


            const countdown = setInterval(() => {

                const minutes =
                    Math.floor(timeLeft / 60);

                const seconds =
                    timeLeft % 60;

                timer.textContent =
                    minutes + ":" +
                    String(seconds).padStart(2, "0");


                if (timeLeft <= 0) {

                    clearInterval(countdown);

                    alert(
                        "Time is up! Your quiz will be submitted."
                    );

                    quizForm.submit();

                }

                timeLeft--;

            }, 1000);

        </script>

    <?php endif; ?>

</div>

</body>

</html>
```
