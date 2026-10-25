
CREATE DATABASE IF NOT EXISTS digiquiz
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE digiquiz;


-- =========================
-- SUBJECTS
-- =========================

CREATE TABLE IF NOT EXISTS subjects (

    id INT AUTO_INCREMENT PRIMARY KEY,

    semester TINYINT NOT NULL,

    name VARCHAR(255) NOT NULL,

    subject_code VARCHAR(50) NOT NULL DEFAULT '',

    created_at TIMESTAMP
    DEFAULT CURRENT_TIMESTAMP

);


-- =========================
-- SYLLABUS FILES
-- =========================

CREATE TABLE IF NOT EXISTS syllabus_files (

    id INT AUTO_INCREMENT PRIMARY KEY,

    subject_id INT NOT NULL,

    semester TINYINT NOT NULL,

    file_name VARCHAR(255) NOT NULL,

    file_path VARCHAR(500) NOT NULL,

    extracted_text LONGTEXT NULL,

    status ENUM(
        'uploaded',
        'processing',
        'processed',
        'failed'
    ) DEFAULT 'uploaded',

    created_at TIMESTAMP
    DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (subject_id)
    REFERENCES subjects(id)
    ON DELETE CASCADE

);


-- =========================
-- STUDENTS
-- =========================

CREATE TABLE IF NOT EXISTS students (

    id INT AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(150),

    email VARCHAR(255) UNIQUE,

    password_hash VARCHAR(255),

    created_at TIMESTAMP
    DEFAULT CURRENT_TIMESTAMP

);


-- =========================
-- QUIZ QUESTIONS
-- =========================

CREATE TABLE IF NOT EXISTS quiz_questions (

    id INT AUTO_INCREMENT PRIMARY KEY,

    subject_id INT NULL,

    syllabus_id INT NULL,

    question TEXT NOT NULL,

    option_a TEXT,

    option_b TEXT,

    option_c TEXT,

    option_d TEXT,

    correct_answer VARCHAR(10),

    explanation TEXT,

    created_at TIMESTAMP
    DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY(subject_id)
    REFERENCES subjects(id)
    ON DELETE SET NULL,

    FOREIGN KEY(syllabus_id)
    REFERENCES syllabus_files(id)
    ON DELETE SET NULL

);


-- =========================
-- FLASHCARDS
-- =========================

CREATE TABLE IF NOT EXISTS flashcards (

    id INT AUTO_INCREMENT PRIMARY KEY,

    subject_id INT NULL,

    syllabus_id INT NULL,

    question TEXT NOT NULL,

    answer TEXT NOT NULL,

    created_at TIMESTAMP
    DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY(subject_id)
    REFERENCES subjects(id)
    ON DELETE SET NULL,

    FOREIGN KEY(syllabus_id)
    REFERENCES syllabus_files(id)
    ON DELETE SET NULL

);


-- =========================
-- QUIZ ATTEMPTS
-- =========================

CREATE TABLE IF NOT EXISTS quiz_attempts (

    id INT AUTO_INCREMENT PRIMARY KEY,

    student_id INT NOT NULL,

    subject_id INT NULL,

    score INT NOT NULL DEFAULT 0,

    total_questions INT NOT NULL DEFAULT 0,

    percentage DECIMAL(5,2)
    NOT NULL DEFAULT 0,

    created_at TIMESTAMP
    DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (student_id)
    REFERENCES students(id)
    ON DELETE CASCADE,

    FOREIGN KEY (subject_id)
    REFERENCES subjects(id)
    ON DELETE SET NULL

);

