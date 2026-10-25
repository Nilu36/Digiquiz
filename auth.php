<?php
/*
 * DigiQuiz student login protection.
 * Include this file at the top of every student-only page.
 */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (empty($_SESSION["student_id"])) {
    header("Location: login.php");
    exit;
}
