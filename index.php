<?php
require_once "auth.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DigiQuiz | AI Learning Platform</title>

    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
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



<!-- ================= HERO ================= -->

<section class="hero">

    <div class="container hero-container">


        <!-- Left Content -->

        <div class="hero-content">

            <div class="badge">

                <i data-lucide="sparkles"></i>

                AI-POWERED LEARNING FOR DIPLOMA IT

            </div>


            <h1>

                Learn Smarter.
                <br>

                <span>Quiz Better.</span>

            </h1>


            <p>

                DigiQuiz is an AI-powered learning platform designed
                specifically for Diploma IT students.

                Practice quizzes, revise with smart flashcards,
                and track your learning progress.

            </p>


            <div class="hero-buttons">

                <a href="subjects.php" class="primary-btn">

                    Explore Subjects

                    <i data-lucide="arrow-right"></i>

                </a>


                <a href="quiz.php" class="secondary-btn">

                    <i data-lucide="play"></i>

                    Start Quiz

                </a>

            </div>


            <!-- Stats -->

            <div class="hero-stats">

                <div>

                    <strong>5+</strong>

                    <span>Semesters</span>

                </div>


                <div>

                    <strong>20+</strong>

                    <span>Subjects</span>

                </div>


                <div>

                    <strong>AI</strong>

                    <span>Powered</span>

                </div>

            </div>

        </div>



        <!-- ================= AI PREVIEW ================= -->

        <div class="hero-preview">

            <div class="glow"></div>


            <div class="ai-card">


                <!-- AI Header -->

                <div class="ai-header">

                    <div class="ai-profile">

                        <div class="ai-icon">

                            <i data-lucide="bot"></i>

                        </div>

                        <div>

                            <strong>DigiQuiz AI</strong>

                            <small>Learning Assistant</small>

                        </div>

                    </div>


                    <div class="online">

                        <span></span>

                        Online

                    </div>

                </div>



                <!-- AI Message -->

                <div class="ai-message">

                    <div class="small-ai-icon">

                        <i data-lucide="sparkles"></i>

                    </div>

                    <p>

                        Select a semester and subject.
                        I'll generate learning content
                        from your syllabus.

                    </p>

                </div>



                <!-- Subject -->

                <div class="subject-box">

                    <small>SELECT SUBJECT</small>


                    <div class="subject">

                        <div class="python-icon">

                            Py

                        </div>


                        <div>

                            <strong>
                                Python Programming
                            </strong>

                            <small>
                                Semester 1
                            </small>

                        </div>


                        <i
                            data-lucide="check-circle"
                            class="check">
                        </i>

                    </div>

                </div>



                <!-- Quiz -->

                <div class="quiz-box">

                    <div class="quiz-title">

                        <div>

                            <i data-lucide="sparkles"></i>

                            <strong>
                                AI Generated Quiz
                            </strong>

                        </div>


                        <span>
                            10 Questions
                        </span>

                    </div>


                    <div class="progress">

                        <div></div>

                    </div>


                    <div class="quiz-details">

                        <span>

                            <i data-lucide="clock-3"></i>

                            10 min

                        </span>


                        <span>

                            <i data-lucide="signal"></i>

                            Medium

                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- ================= FEATURES ================= -->

<section class="features">

    <div class="container">


        <div class="section-heading">

            <span>WHY DIGIQUIZ</span>

            <h2>

                Everything you need to

                <strong>learn better.</strong>

            </h2>

            <p>

                A complete learning platform built
                around your Diploma IT syllabus.

            </p>

        </div>



        <div class="feature-grid">


            <!-- Feature 1 -->

            <div class="feature-card">

                <div class="feature-icon">

                    <i data-lucide="brain-circuit"></i>

                </div>

                <h3>
                    AI Generated Quiz
                </h3>

                <p>

                    Generate quizzes automatically
                    using your selected subject syllabus.

                </p>

                <a href="quiz.php">

                    Try Quiz

                    <i data-lucide="arrow-up-right"></i>

                </a>

            </div>



            <!-- Feature 2 -->

            <div class="feature-card">

                <div class="feature-icon">

                    <i data-lucide="layers-3"></i>

                </div>

                <h3>
                    Smart Flashcards
                </h3>

                <p>

                    Revise important concepts quickly
                    with AI-generated flashcards.

                </p>

                <a href="flashcards.php">

                    Study Cards

                    <i data-lucide="arrow-up-right"></i>

                </a>

            </div>



            <!-- Feature 3 -->

            <div class="feature-card">

                <div class="feature-icon">

                    <i data-lucide="chart-no-axes-combined"></i>

                </div>

                <h3>
                    Student Dashboard
                </h3>

                <p>

                    Track your scores, progress,
                    attempts and learning performance.

                </p>

                <a href="dashboard.php">

                    View Dashboard

                    <i data-lucide="arrow-up-right"></i>

                </a>

            </div>



            <!-- Feature 4 -->

            <div class="feature-card">

                <div class="feature-icon">

                    <i data-lucide="book-open"></i>

                </div>

                <h3>
                    Syllabus Based
                </h3>

                <p>

                    Select semester and subject
                    to keep your learning focused.

                </p>

                <a href="subjects.php">

                    View Subjects

                    <i data-lucide="arrow-up-right"></i>

                </a>

            </div>

        </div>

    </div>

</section>



<!-- ================= HOW IT WORKS ================= -->

<section class="how-section">

    <div class="container">


        <div class="section-heading">

            <span>HOW IT WORKS</span>

            <h2>

                From Syllabus to

                <strong>Smart Learning.</strong>

            </h2>

        </div>



        <div class="steps">


            <div class="step">

                <div class="number">
                    01
                </div>

                <div class="step-icon">
                    <i data-lucide="book-open"></i>
                </div>

                <h3>Select Subject</h3>

                <p>
                    Choose your semester and subject.
                </p>

            </div>



            <div class="step">

                <div class="number">
                    02
                </div>

                <div class="step-icon">
                    <i data-lucide="sparkles"></i>
                </div>

                <h3>AI Generates</h3>

                <p>
                    AI creates quizzes and flashcards
                    from the syllabus.
                </p>

            </div>



            <div class="step">

                <div class="number">
                    03
                </div>

                <div class="step-icon">
                    <i data-lucide="pencil-line"></i>
                </div>

                <h3>Practice</h3>

                <p>
                    Attempt quizzes and study
                    with flashcards.
                </p>

            </div>



            <div class="step">

                <div class="number">
                    04
                </div>

                <div class="step-icon">
                    <i data-lucide="chart-line"></i>
                </div>

                <h3>Improve</h3>

                <p>
                    Dashboard analyzes your
                    learning performance.
                </p>

            </div>

        </div>

    </div>

</section>



<!-- ================= CTA ================= -->

<section class="cta-section">

    <div class="container">

        <div class="cta">

            <div>

                <span>
                    START LEARNING
                </span>

                <h2>

                    Make your syllabus

                    <strong>work for you.</strong>

                </h2>

                <p>

                    Start your Diploma IT preparation
                    with AI-assisted learning.

                </p>

                <a
                    href="register.php"
                    class="primary-btn">

                    Create Free Account

                    <i data-lucide="arrow-right"></i>

                </a>

            </div>


            <i
                data-lucide="brain-circuit"
                class="cta-icon">
            </i>

        </div>

    </div>

</section>



<!-- ================= FOOTER ================= -->

<footer>

    <div class="container footer-container">


        <div class="footer-brand">

            <a href="index.php" class="logo">

                <div class="logo-icon">

                    <i data-lucide="brain-circuit"></i>

                </div>

                <span>
                    Digi<span>Quiz</span>
                </span>

            </a>

            <p>

                AI-powered learning platform
                for Diploma IT students.

            </p>

        </div>



        <div>

            <h4>Platform</h4>

            <a href="subjects.php">Subjects</a>
            <a href="syllabus.php">Syllabus</a>
            <a href="quiz.php">Quiz</a>
            <a href="flashcards.php">Flashcards</a>

        </div>



        <div>

            <h4>Account</h4>

            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
            <a href="forgot-password.php">
                Forgot Password
            </a>

        </div>



        <div>

            <h4>Company</h4>

            <a href="about.php">About Us</a>

        </div>

    </div>


    <div class="footer-bottom">

        <div class="container">

            © 2026 DigiQuiz.
            All rights reserved.

        </div>

    </div>

</footer>


<script src="assets/js/main.js"></script>

<script>
    lucide.createIcons();
</script>

</body>
</html>

