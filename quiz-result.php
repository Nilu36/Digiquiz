<?php

require_once "auth.php";
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: quiz.php");
    exit;
}

$subjectId = intval($_POST["subject_id"] ?? 0);

$answers = $_POST["answers"] ?? [];

if ($subjectId <= 0) {
    die("Invalid subject.");
}


/* Get questions that were submitted */
$questionIds = array_map(
    "intval",
    array_keys($answers)
);

$questions = [];

if (!empty($questionIds)) {

    $placeholders =
        implode(",", array_fill(
            0,
            count($questionIds),
            "?"
        ));

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
        WHERE id IN ($placeholders)
    ");

    $stmt->execute($questionIds);

    $questions = $stmt->fetchAll();
}


/* Calculate result */

$total = count($questions);

$score = 0;

foreach ($questions as $question) {

    $id = $question["id"];

    $userAnswer = $answers[$id] ?? "";

    if (
        strtoupper($userAnswer)
        === strtoupper($question["correct_answer"])
    ) {
        $score++;
    }
}

$percentage = $total > 0
    ? round(($score / $total) * 100)
    : 0;
    /* Save quiz attempt if student is logged in */

if (isset($_SESSION["student_id"])) {

    $stmt = $pdo->prepare("
        INSERT INTO quiz_attempts
        (
            student_id,
            subject_id,
            score,
            total_questions,
            percentage
        )
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $_SESSION["student_id"],
        $subjectId,
        $score,
        $total,
        $percentage
    ]);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Quiz Result - DigiQuiz</title>

    <link rel="stylesheet"
          href="assets/css/style.css">

    <style>

        .result-page {
            max-width: 900px;
            margin: auto;
            padding: 50px 20px;
        }

        .result-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .score-card {
            text-align: center;
            background: #111827;
            border: 1px solid #1f2937;
            border-radius: 18px;
            padding: 35px;
            margin-bottom: 25px;
        }

        .score {
            font-size: 55px;
            font-weight: 800;
            color: #60a5fa;
        }

        .percentage {
            font-size: 20px;
            color: #9ca3af;
        }

        .result-question {
            background: #111827;
            border: 1px solid #1f2937;
            border-radius: 14px;
            padding: 22px;
            margin-bottom: 15px;
        }

        .result-question h3 {
            margin-bottom: 15px;
        }

        .correct-answer {
            color: #86efac;
        }

        .wrong-answer {
            color: #fca5a5;
        }

        .explanation {
            background: #0f172a;
            padding: 14px;
            border-radius: 9px;
            margin-top: 15px;
            color: #cbd5e1;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 9px;
        }

    </style>

</head>

<body>

<div class="result-page">

    <div class="result-header">

        <h1>🎉 Quiz Complete!</h1>

        <p>
            Here is your quiz result.
        </p>

    </div>


    <div class="score-card">

        <div class="score">

            <?= $score ?>
            /
            <?= $total ?>

        </div>

        <div class="percentage">

            <?= $percentage ?>%

        </div>

    </div>


    <?php foreach ($questions as $question): ?>

        <?php

        $id = $question["id"];

        $userAnswer = $answers[$id] ?? "";

        $isCorrect =
            strtoupper($userAnswer)
            === strtoupper(
                $question["correct_answer"]
            );

        ?>

        <div class="result-question">

            <h3>

                <?= htmlspecialchars(
                    $question["question"]
                ) ?>

            </h3>


            <?php if ($isCorrect): ?>

                <p class="correct-answer">

                    ✅ Correct

                </p>

            <?php else: ?>

                <p class="wrong-answer">

                    ❌ Incorrect

                </p>

                <p>

                    Your answer:
                    <strong>
                        <?= htmlspecialchars(
                            $userAnswer ?: "Not answered"
                        ) ?>
                    </strong>

                </p>

                <p>

                    Correct answer:
                    <strong>
                        <?= htmlspecialchars(
                            $question["correct_answer"]
                        ) ?>
                    </strong>

                </p>

            <?php endif; ?>


            <?php if (!empty(
                $question["explanation"]
            )): ?>

                <div class="explanation">

                    <strong>
                        🤖 AI Explanation
                    </strong>

                    <br><br>

                    <?= nl2br(
                        htmlspecialchars(
                            $question["explanation"]
                        )
                    ) ?>

                </div>

            <?php endif; ?>

        </div>

    <?php endforeach; ?>


    <div style="text-align:center;">

        <a
            href="quiz.php"
            class="back-btn"
        >
            Take Another Quiz
        </a>

    </div>

</div>

</body>

</html>
```
