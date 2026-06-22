-- ============================================================
-- Sample Data – Scholarship & Award Manager
-- ============================================================

-- Users (password = 'password123' hashed with password_hash())
INSERT INTO users (role, username, email, password_hash) VALUES
('admin',   'admin',    'admin@school.edu.vn',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('student', 'sv001',    'sv001@school.edu.vn',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('student', 'sv002',    'sv002@school.edu.vn',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('student', 'sv003',    'sv003@school.edu.vn',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('student', 'sv004',    'sv004@school.edu.vn',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('admin',   'admin2',   'admin2@school.edu.vn',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Semesters
INSERT INTO semesters (code, name, start_date, end_date) VALUES
('20251', 'Học kỳ 1 – 2024-2025', '2024-09-01', '2025-01-15'),
('20252', 'Học kỳ 2 – 2024-2025', '2025-02-01', '2025-06-15'),
('20261', 'Học kỳ 1 – 2025-2026', '2025-09-01', '2026-01-15');

-- Students
INSERT INTO students (user_id, student_code, full_name, date_of_birth, phone, faculty, gpa, has_f_grade, income_class) VALUES
(2, 'SV20210001', 'Nguyễn Văn An',   '2003-05-10', '0901234567', 'Công nghệ thông tin', 3.50, 0, 'low'),
(3, 'SV20210002', 'Trần Thị Bình',   '2003-08-22', '0912345678', 'Kinh tế',             3.20, 0, 'medium'),
(4, 'SV20210003', 'Lê Văn Cường',    '2002-12-01', '0923456789', 'Công nghệ thông tin', 2.90, 1, 'low'),
(5, 'SV20210004', 'Phạm Thị Dung',   '2003-03-15', '0934567890', 'Ngoại ngữ',           3.70, 0, 'high');

-- Scholarship Programs
INSERT INTO scholarship_programs (title, description, amount_per_student, max_number_of_awards, semester_id, application_deadline, status) VALUES
('Học bổng Xuất sắc HK2 2024-2025',  'Dành cho sinh viên có GPA ≥ 3.5, không có điểm F',                    5000000, 5, 2, '2025-03-15', 'active'),
('Học bổng Hoàn cảnh Khó khăn',       'Hỗ trợ sinh viên có hoàn cảnh khó khăn, GPA ≥ 2.5',                  3000000, 10, 2, '2025-03-20', 'active'),
('Học bổng Nghiên cứu Khoa học',       'Dành cho sinh viên tham gia NCKH, GPA ≥ 3.2',                        4000000, 3, 2, '2025-03-10', 'closed'),
('Giải thưởng Hoạt động Ngoại khóa',  'Sinh viên tham gia ≥ 3 hoạt động ngoại khóa trong học kỳ',           2000000, 8, 2, '2025-04-01', 'active');

-- Eligibility Rules
INSERT INTO eligibility_rules (scholarship_program_id, rule_type, rule_value, is_required) VALUES
(1, 'gpa_min',       '3.5', 1),
(1, 'no_f_grade',    '1',   1),
(2, 'gpa_min',       '2.5', 1),
(2, 'income_class',  'low', 1),
(3, 'gpa_min',       '3.2', 1),
(3, 'no_f_grade',    '1',   1),
(3, 'min_activities','1',   1),
(4, 'min_activities','3',   1);

-- Scoring Criteria
INSERT INTO scoring_criteria (scholarship_program_id, criterion_name, description, weight, max_score) VALUES
(1, 'Điểm học tập',       'GPA quy đổi thang 10',                    0.500, 10),
(1, 'Nghiên cứu KH',      'Số bài báo / đề tài tham gia',            0.300, 10),
(1, 'Hoạt động ngoại khóa','Số hoạt động đã tham gia',               0.200, 10),
(2, 'Điểm học tập',       'GPA quy đổi thang 10',                    0.400, 10),
(2, 'Hoàn cảnh gia đình', 'Mức độ khó khăn kinh tế',                 0.600, 10),
(3, 'Điểm học tập',       'GPA quy đổi thang 10',                    0.400, 10),
(3, 'Nghiên cứu KH',      'Chất lượng và số lượng công trình NCKH',  0.600, 10),
(4, 'Hoạt động ngoại khóa','Số lượng và chất lượng hoạt động',       0.700, 10),
(4, 'Điểm học tập',       'GPA quy đổi thang 10',                    0.300, 10);

-- Applications
INSERT INTO applications (student_id, scholarship_program_id, status, submission_date) VALUES
(1, 1, 'eligible',      '2025-03-01 09:00:00'),
(2, 1, 'eligible',      '2025-03-02 10:30:00'),
(3, 1, 'ineligible',    '2025-03-03 14:00:00'),
(1, 2, 'under_review',  '2025-03-05 08:00:00'),
(2, 4, 'submitted',     '2025-03-10 11:00:00'),
(4, 1, 'awarded',       '2025-03-01 15:00:00');

-- Student Activities
INSERT INTO student_activities (student_id, semester_id, activity_name, activity_type, description, completed) VALUES
(1, 2, 'CLB Lập trình ACM',          'club',        'Tham gia câu lạc bộ lập trình',          1),
(1, 2, 'Cuộc thi Hackathon 2025',    'competition', 'Đạt giải nhì cuộc thi hackathon',        1),
(1, 2, 'Tình nguyện mùa hè xanh',   'community',   'Tham gia chiến dịch tình nguyện',        1),
(2, 2, 'CLB Tiếng Anh',             'club',        'Thành viên tích cực CLB tiếng Anh',      1),
(2, 2, 'Hội thảo Kinh tế số',       'event',       'Tham dự hội thảo',                       1),
(3, 2, 'CLB Bóng đá',               'club',        'Thành viên đội bóng khoa',               1),
(4, 2, 'NCKH Trí tuệ nhân tạo',     'research',    'Tham gia đề tài NCKH cấp trường',        1),
(4, 2, 'CLB Lập trình ACM',          'club',        'Tham gia câu lạc bộ lập trình',          1),
(4, 2, 'Cuộc thi Olympic Tin học',   'competition', 'Đạt giải khuyến khích',                  1),
(4, 2, 'Tình nguyện hiến máu',       'community',   'Tham gia hiến máu nhân đạo',             1);

-- Evaluation Scores (for application 1 – student 1, program 1)
INSERT INTO evaluation_scores (application_id, scoring_criterion_id, score, evaluated_by) VALUES
(1, 1, 9, 1),
(1, 2, 7, 1),
(1, 3, 8, 1),
(2, 1, 8, 1),
(2, 2, 5, 1),
(2, 3, 6, 1),
(6, 1, 10, 1),
(6, 2, 9,  1),
(6, 3, 9,  1);

-- Ranking Results
INSERT INTO ranking_results (application_id, total_score, rank_in_program, status) VALUES
(6, 9.500, 1, 'award_granted'),
(1, 8.500, 2, 'suggested'),
(2, 6.900, 3, 'suggested');

-- Disbursements
INSERT INTO disbursements (ranking_result_id, amount_paid, payment_date, status, payment_method) VALUES
(1, 5000000, '2025-04-01', 'paid',    'Chuyển khoản ngân hàng'),
(2, 5000000, NULL,         'pending', 'Chuyển khoản ngân hàng');

-- Award Certificates
INSERT INTO award_certificates (ranking_result_id, certificate_code, issued_date, status) VALUES
(1, 'CERT-2025-0001', '2025-04-05', 'issued');
