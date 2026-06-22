-- ============================================================
-- Scholarship & Award Manager – Database Schema
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS student_activities;
DROP TABLE IF EXISTS award_certificates;
DROP TABLE IF EXISTS disbursements;
DROP TABLE IF EXISTS ranking_results;
DROP TABLE IF EXISTS evaluation_scores;
DROP TABLE IF EXISTS scoring_criteria;
DROP TABLE IF EXISTS applications;
DROP TABLE IF EXISTS eligibility_rules;
DROP TABLE IF EXISTS scholarship_programs;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS semesters;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;

-- ------------------------------------------------------------
-- 1. users
-- ------------------------------------------------------------
CREATE TABLE users (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    role          ENUM('student', 'admin') NOT NULL DEFAULT 'student',
    username      VARCHAR(50)  UNIQUE NOT NULL,
    email         VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at    DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at    DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 2. semesters
-- ------------------------------------------------------------
CREATE TABLE semesters (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    code       VARCHAR(10) UNIQUE NOT NULL,   -- e.g. '20251', '20252'
    name       VARCHAR(50) NOT NULL,
    start_date DATE,
    end_date   DATE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 3. students  (1-1 with users)
-- ------------------------------------------------------------
CREATE TABLE students (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    user_id      INT NOT NULL UNIQUE,
    student_code VARCHAR(20) UNIQUE NOT NULL,
    full_name    VARCHAR(100) NOT NULL,
    date_of_birth DATE NULL,
    phone        VARCHAR(20) NULL,
    address      TEXT NULL,
    faculty      VARCHAR(100) NULL,
    gpa          DECIMAL(3,2) NOT NULL DEFAULT 0.00,
    has_f_grade  TINYINT(1)   NOT NULL DEFAULT 0,
    income_class ENUM('low','medium','high') NOT NULL DEFAULT 'medium',
    created_at   DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 4. scholarship_programs
-- ------------------------------------------------------------
CREATE TABLE scholarship_programs (
    id                   INT AUTO_INCREMENT PRIMARY KEY,
    title                VARCHAR(150) NOT NULL,
    description          TEXT,
    amount_per_student   DECIMAL(10,2) NOT NULL,
    max_number_of_awards INT NOT NULL,
    semester_id          INT NULL,
    application_deadline DATE NULL,
    status               ENUM('active','closed') DEFAULT 'active',
    created_at           DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (semester_id) REFERENCES semesters(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 5. eligibility_rules
-- ------------------------------------------------------------
CREATE TABLE eligibility_rules (
    id                     INT AUTO_INCREMENT PRIMARY KEY,
    scholarship_program_id INT NOT NULL,
    rule_type              ENUM('gpa_min','no_f_grade','min_activities','income_class') NOT NULL,
    rule_value             VARCHAR(50) NOT NULL,
    is_required            TINYINT(1) DEFAULT 1,
    FOREIGN KEY (scholarship_program_id) REFERENCES scholarship_programs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 6. applications
-- ------------------------------------------------------------
CREATE TABLE applications (
    id                     INT AUTO_INCREMENT PRIMARY KEY,
    student_id             INT NOT NULL,
    scholarship_program_id INT NOT NULL,
    status                 ENUM('draft','submitted','eligible','ineligible','under_review','rejected','awarded') DEFAULT 'draft',
    note                   TEXT NULL,
    submission_date        DATETIME NULL,
    created_at             DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at             DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id)             REFERENCES students(id)             ON DELETE CASCADE,
    FOREIGN KEY (scholarship_program_id) REFERENCES scholarship_programs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 7. scoring_criteria
-- ------------------------------------------------------------
CREATE TABLE scoring_criteria (
    id                     INT AUTO_INCREMENT PRIMARY KEY,
    scholarship_program_id INT NOT NULL,
    criterion_name         VARCHAR(100) NOT NULL,
    description            TEXT,
    weight                 DECIMAL(4,3) NOT NULL,   -- 0.000 – 1.000
    max_score              INT NOT NULL DEFAULT 10,
    FOREIGN KEY (scholarship_program_id) REFERENCES scholarship_programs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 8. evaluation_scores
-- ------------------------------------------------------------
CREATE TABLE evaluation_scores (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    application_id      INT NOT NULL,
    scoring_criterion_id INT NOT NULL,
    score               INT NOT NULL,
    evaluated_by        INT NOT NULL,
    evaluation_date     DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_app_criterion (application_id, scoring_criterion_id),
    FOREIGN KEY (application_id)       REFERENCES applications(id)      ON DELETE CASCADE,
    FOREIGN KEY (scoring_criterion_id) REFERENCES scoring_criteria(id)  ON DELETE CASCADE,
    FOREIGN KEY (evaluated_by)         REFERENCES users(id)             ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 9. ranking_results
-- ------------------------------------------------------------
CREATE TABLE ranking_results (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT NOT NULL UNIQUE,
    total_score    DECIMAL(6,3) NOT NULL DEFAULT 0.000,
    rank_in_program INT NOT NULL DEFAULT 0,
    status         ENUM('suggested','award_granted','rejected') DEFAULT 'suggested',
    created_at     DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at     DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 10. disbursements
-- ------------------------------------------------------------
CREATE TABLE disbursements (
    id                INT AUTO_INCREMENT PRIMARY KEY,
    ranking_result_id INT NOT NULL,
    amount_paid       DECIMAL(10,2) NOT NULL,
    payment_date      DATE NULL,
    status            ENUM('pending','processing','paid','cancelled') DEFAULT 'pending',
    payment_method    VARCHAR(50) NULL,
    note              TEXT NULL,
    created_at        DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at        DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ranking_result_id) REFERENCES ranking_results(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 11. award_certificates
-- ------------------------------------------------------------
CREATE TABLE award_certificates (
    id                INT AUTO_INCREMENT PRIMARY KEY,
    ranking_result_id INT NOT NULL,
    certificate_code  VARCHAR(50) UNIQUE NOT NULL,
    issued_date       DATE NOT NULL,
    pdf_url           TEXT NULL,
    status            ENUM('issued','revoked') DEFAULT 'issued',
    created_at        DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ranking_result_id) REFERENCES ranking_results(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- 12. student_activities
-- ------------------------------------------------------------
CREATE TABLE student_activities (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    student_id    INT NOT NULL,
    semester_id   INT NULL,
    activity_name VARCHAR(150) NOT NULL,
    activity_type ENUM('club','event','competition','community','research') NOT NULL,
    description   TEXT NULL,
    completed     TINYINT(1) DEFAULT 1,
    created_at    DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id)  REFERENCES students(id)  ON DELETE CASCADE,
    FOREIGN KEY (semester_id) REFERENCES semesters(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
