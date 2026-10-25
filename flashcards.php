<?php

require_once "auth.php";

require_once "config.php";

/* Get subjects having flashcards */
$stmt = $pdo->query("
    SELECT DISTINCT
        s.id,
        s.name,
        s.subject_code,
        s.semester
    FROM subjects s
    INNER JOIN flashcards f
        ON s.id = f.subject_id
    ORDER BY s.semester, s.name
");

$subjects = $stmt->fetchAll();


$subjectId = isset($_GET["subject"])
    ? intval($_GET["subject"])
    : 0;

/* Allow a subject name as a fallback when opening from the subjects page. */
if ($subjectId <= 0 && !empty($_GET["subject_name"])) {
    $subjectLookup = $pdo->prepare("SELECT id FROM subjects WHERE name = ? LIMIT 1");
    $subjectLookup->execute([trim($_GET["subject_name"])]);
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

$flashcards = [];

if ($subjectId > 0) {

    $stmt = $pdo->prepare("
        SELECT
            id,
            question,
            answer
        FROM flashcards
        WHERE subject_id = ?
        ORDER BY RAND()
        LIMIT 20
    ");

    $stmt->execute([$subjectId]);

    $flashcards = $stmt->fetchAll();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>DigiQuiz - Flashcards</title>

    <link rel="stylesheet"
          href="assets/css/style.css">

    <style>

        .flash-page {
            max-width: 1000px;
            margin: auto;
            padding: 45px 20px;
        }

        .flash-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .flash-header h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }

        .flash-header p {
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
            padding: 30px;
            background: #111827;
            border: 1px solid #1f2937;
            border-radius: 16px;
        }

        .subject-box label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .subject-box select {
            width: 100%;
            padding: 14px;
            margin-bottom: 20px;
            border-radius: 10px;
            border: 1px solid #374151;
            background: #0f172a;
            color: white;
            font-size: 16px;
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

        /* Flashcards */

        .cards-grid {
            display: grid;
            grid-template-columns:
                repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .flashcard-wrapper {
            height: 230px;
            perspective: 1000px;
            cursor: pointer;
        }

        .flashcard {
            position: relative;
            width: 100%;
            height: 100%;
            transition: transform 0.6s;
            transform-style: preserve-3d;
        }

        .flashcard-wrapper.flipped
        .flashcard {
            transform: rotateY(180deg);
        }

        .flash-front,
        .flash-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;

            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;

            padding: 25px;
            box-sizing: border-box;

            background: #111827;
            border: 1px solid #1f2937;
            border-radius: 16px;

            text-align: center;
        }

        .flash-front {
            border-top: 3px solid #3b82f6;
        }

        .flash-back {
            transform: rotateY(180deg);
            border-top: 3px solid #22c55e;
        }

        .card-label {
            font-size: 13px;
            color: #60a5fa;
            font-weight: 700;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .answer-label {
            color: #86efac;
        }

        .flash-text {
            font-size: 17px;
            line-height: 1.5;
        }

        .hint {
            text-align: center;
            margin: 25px 0;
            color: #9ca3af;
        }

        .empty-message {
            text-align: center;
            padding: 50px;
            background: #111827;
            border-radius: 15px;
        }

        .back-link {
            display: inline-block;
            margin-top: 25px;
            color: #60a5fa;
            text-decoration: none;
        }

        @media (max-width: 600px) {

            .flash-page {
                padding: 25px 15px;
            }

            .flash-header h1 {
                font-size: 28px;
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

<div class="flash-page">

    <div class="flash-header">

        <h1>📖 AI Flashcards</h1>

        <p>
            Learn important concepts from your syllabus.
        </p>

        <?php if (!empty($selectedSubject)): ?>
            <p class="selected-subject">
                <?= htmlspecialchars($selectedSubject["subject_code"] ?? "") ?>
                <?= !empty($selectedSubject["subject_code"]) ? " — " : "" ?>
                <?= htmlspecialchars($selectedSubject["name"]) ?>
            </p>
        <?php endif; ?>

    </div>


    <?php if ($subjectId === 0): ?>

        <!-- SUBJECT SELECTION -->

        <div class="subject-box">

            <h2>Select Subject</h2>

            <p>
                Choose a subject to start studying.
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
                            value="<?= $subject["id"] ?>"
                        >

                            Semester <?= (int)$subject["semester"] ?> -
                            <?= htmlspecialchars(trim((string)($subject["subject_code"] ?? "")) !== "" ? $subject["subject_code"] . " - " : "") ?>
                            <?= htmlspecialchars($subject["name"]) ?>

                        </option>

                    <?php endforeach; ?>

                </select>

                <button
                    type="submit"
                    class="start-btn"
                >
                    Start Flashcards →
                </button>

            </form>

        </div>


    <?php elseif (empty($flashcards)): ?>

        <div class="empty-message">

            <h2>No Flashcards Available</h2>

            <p>
                This subject does not have generated
                flashcards yet.
            </p>

            <a
                href="flashcards.php"
                class="back-link"
            >
                Choose another subject
            </a>

        </div>


    <?php else: ?>

        <div class="hint">
            👆 Click a card to reveal the answer
        </div>


        <div class="cards-grid">

            <?php foreach (
                $flashcards as $index => $card
            ): ?>

                <div
                    class="flashcard-wrapper"
                    onclick="flipCard(this)"
                >

                    <div class="flashcard">

                        <!-- FRONT -->

                        <div class="flash-front">

                            <div class="card-label">

                                Card <?= $index + 1 ?>

                            </div>

                            <div class="flash-text">

                                <?= htmlspecialchars(
                                    $card["question"]
                                ) ?>

                            </div>

                        </div>


                        <!-- BACK -->

                        <div class="flash-back">

                            <div class="card-label answer-label">

                                Answer

                            </div>

                            <div class="flash-text">

                                <?= nl2br(
                                    htmlspecialchars(
                                        $card["answer"]
                                    )
                                ) ?>

                            </div>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>


        <div style="text-align:center;">

            <a
                href="flashcards.php"
                class="back-link"
            >
                ← Choose Another Subject
            </a>

        </div>

    <?php endif; ?>

</div>


<script>

function flipCard(card) {

    card.classList.toggle("flipped");

}

</script>

</body>

</html>
```
