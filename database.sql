-- ──────────────────────────────────────────
--  AcaHub Native PHP - Database Schema
-- ──────────────────────────────────────────

CREATE DATABASE IF NOT EXISTS acahub_native
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE acahub_native;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    email       VARCHAR(150) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    role        ENUM('admin','teacher','student','parent') NOT NULL DEFAULT 'student',
    school_id   INT DEFAULT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Schools Table
CREATE TABLE IF NOT EXISTS schools (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(200) NOT NULL,
    address     TEXT,
    phone       VARCHAR(20),
    email       VARCHAR(150),
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Subjects Table
CREATE TABLE IF NOT EXISTS subjects (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    code        VARCHAR(20),
    school_id   INT,
    teacher_id  INT,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Classrooms Table
CREATE TABLE IF NOT EXISTS classrooms (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(50) NOT NULL,
    grade       VARCHAR(10),
    school_id   INT,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Grades Table
CREATE TABLE IF NOT EXISTS grades (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    student_id  INT NOT NULL,
    subject_id  INT NOT NULL,
    score       DECIMAL(5,2) NOT NULL,
    type        ENUM('UH','UTS','UAS','Tugas') DEFAULT 'UH',
    semester    TINYINT DEFAULT 1,
    notes       TEXT,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Announcements Table
CREATE TABLE IF NOT EXISTS announcements (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(200) NOT NULL,
    body        TEXT NOT NULL,
    author_id   INT NOT NULL,
    school_id   INT,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ─── SAMPLE DATA ───────────────────────────────────

INSERT INTO schools (name, address, phone, email) VALUES
('SMA Negeri 1 AcaHub', 'Jl. Pendidikan No. 1, Jakarta', '021-1234567', 'sman1@acahub.id');

-- Password: password (bcrypt)
INSERT INTO users (name, email, password, role, school_id) VALUES
('Administrator', 'admin@acahub.id', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1),
('Budi Santoso', 'guru@acahub.id', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher', 1),
('Siti Rahayu', 'siswa@acahub.id', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 1);

INSERT INTO subjects (name, code, school_id, teacher_id) VALUES
('Matematika', 'MTK', 1, 2),
('Bahasa Indonesia', 'BIND', 1, 2),
('Fisika', 'FIS', 1, 2),
('Kimia', 'KIM', 1, 2),
('Biologi', 'BIO', 1, 2);

INSERT INTO grades (student_id, subject_id, score, type, semester) VALUES
(3, 1, 88, 'UH', 1),
(3, 2, 92, 'UH', 1),
(3, 3, 75, 'UH', 1),
(3, 4, 80, 'UTS', 1),
(3, 5, 95, 'UAS', 1);

INSERT INTO announcements (title, body, author_id, school_id) VALUES
('Selamat Datang di AcaHub!', 'Platform pendidikan AcaHub telah resmi diluncurkan. Semoga bermanfaat bagi seluruh warga sekolah.', 1, 1),
('Jadwal Ujian Tengah Semester', 'UTS akan dilaksanakan mulai tanggal 15 April 2026. Persiapkan diri Anda dengan baik.', 2, 1);
