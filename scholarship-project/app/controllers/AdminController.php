<?php
class AdminController
{
    private UserModel          $userModel;
    private Student            $studentModel;
    private ScholarshipProgram $programModel;
    private EligibilityRule    $eligibilityModel;
    private ScoringCriterion   $criterionModel;
    private Application        $applicationModel;
    private EvaluationScore    $scoreModel;
    private RankingResult      $rankingModel;
    private Disbursement       $disbursementModel;
    private AwardCertificate   $certModel;
    private Semester           $semesterModel;

    public function __construct()
    {
        $this->userModel         = new UserModel();
        $this->studentModel      = new Student();
        $this->programModel      = new ScholarshipProgram();
        $this->eligibilityModel  = new EligibilityRule();
        $this->criterionModel    = new ScoringCriterion();
        $this->applicationModel  = new Application();
        $this->scoreModel        = new EvaluationScore();
        $this->rankingModel      = new RankingResult();
        $this->disbursementModel = new Disbursement();
        $this->certModel         = new AwardCertificate();
        $this->semesterModel     = new Semester();
    }

    // =========================================================
    // DASHBOARD
    // =========================================================

    public function dashboard(): void
    {
        requireAdmin();
        $stats = [
            'users'        => $this->userModel->countAll(),
            'students'     => $this->studentModel->countAll(),
            'programs'     => $this->programModel->countAll(),
            'applications' => $this->applicationModel->countAll(),
            'awarded'      => $this->rankingModel->countAwarded(),
            'certificates' => $this->certModel->countAll(),
            'total_paid'   => $this->disbursementModel->getTotalPaid(),
            'pending_apps' => $this->applicationModel->countByStatus('submitted'),
        ];

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/admin/dashboard.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    // =========================================================
    // USERS
    // =========================================================

    public function users(): void
    {
        requireAdmin();
        $users   = $this->userModel->getAll();
        $error   = Session::getFlash('error');
        $success = Session::getFlash('success');

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/admin/user_list.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function createUser(): void
    {
        requireAdmin();
        $error = Session::getFlash('error');
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/admin/user_form.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function doCreateUser(): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect(base_url('admin/users'));

        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email']    ?? '');
        $password = $_POST['password']      ?? '';
        $role     = $_POST['role']          ?? 'student';

        if (empty($username) || empty($email) || empty($password)) {
            Session::flash('error', 'Vui lòng điền đầy đủ thông tin.');
            redirect(base_url('admin/createUser'));
        }

        if ($this->userModel->findByUsername($username)) {
            Session::flash('error', 'Tên đăng nhập đã tồn tại.');
            redirect(base_url('admin/createUser'));
        }

        $userId = $this->userModel->create([
            'role'          => $role,
            'username'      => $username,
            'email'         => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        if ($role === 'student') {
            $fullName    = trim($_POST['full_name']    ?? $username);
            $studentCode = trim($_POST['student_code'] ?? 'SV' . $userId);
            $this->studentModel->create([
                'user_id'      => $userId,
                'student_code' => $studentCode,
                'full_name'    => $fullName,
            ]);
        }

        Session::flash('success', 'Tạo tài khoản thành công.');
        redirect(base_url('admin/users'));
    }

    public function editUser(int $id): void
    {
        requireAdmin();
        $user  = $this->userModel->findById($id);
        if (!$user) { Session::flash('error', 'Người dùng không tồn tại.'); redirect(base_url('admin/users')); }
        $error = Session::getFlash('error');
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/admin/user_form.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function doEditUser(int $id): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect(base_url('admin/users'));

        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email']    ?? '');
        $role     = $_POST['role']          ?? 'student';

        $this->userModel->update($id, ['role' => $role, 'username' => $username, 'email' => $email]);

        if (!empty($_POST['password'])) {
            $this->userModel->updatePassword($id, password_hash($_POST['password'], PASSWORD_DEFAULT));
        }

        Session::flash('success', 'Cập nhật tài khoản thành công.');
        redirect(base_url('admin/users'));
    }

    public function deleteUser(int $id): void
    {
        requireAdmin();
        if ($id === Session::userId()) {
            Session::flash('error', 'Không thể xóa tài khoản đang đăng nhập.');
            redirect(base_url('admin/users'));
        }
        $this->userModel->delete($id);
        Session::flash('success', 'Đã xóa tài khoản.');
        redirect(base_url('admin/users'));
    }

    // =========================================================
    // SCHOLARSHIP PROGRAMS
    // =========================================================

    public function programs(): void
    {
        requireAdmin();
        $programs = $this->programModel->getAll();
        $error    = Session::getFlash('error');
        $success  = Session::getFlash('success');
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/admin/scholarship_list.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function createProgram(): void
    {
        requireAdmin();
        $semesters = $this->semesterModel->getAll();
        $error     = Session::getFlash('error');
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/admin/program_form.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function doCreateProgram(): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect(base_url('admin/programs'));

        $data = [
            'title'                => trim($_POST['title']                ?? ''),
            'description'          => trim($_POST['description']          ?? ''),
            'amount_per_student'   => (float)($_POST['amount_per_student']   ?? 0),
            'max_number_of_awards' => (int)($_POST['max_number_of_awards']   ?? 1),
            'semester_id'          => !empty($_POST['semester_id']) ? (int)$_POST['semester_id'] : null,
            'application_deadline' => $_POST['application_deadline'] ?? null,
            'status'               => $_POST['status'] ?? 'active',
        ];

        if (empty($data['title'])) {
            Session::flash('error', 'Tên chương trình không được để trống.');
            redirect(base_url('admin/createProgram'));
        }

        $this->programModel->create($data);
        Session::flash('success', 'Tạo chương trình học bổng thành công.');
        redirect(base_url('admin/programs'));
    }

    public function editProgram(int $id): void
    {
        requireAdmin();
        $program   = $this->programModel->findById($id);
        if (!$program) { Session::flash('error', 'Không tìm thấy chương trình.'); redirect(base_url('admin/programs')); }
        $semesters = $this->semesterModel->getAll();
        $error     = Session::getFlash('error');
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/admin/program_form.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function doEditProgram(int $id): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect(base_url('admin/programs'));

        $data = [
            'title'                => trim($_POST['title']                ?? ''),
            'description'          => trim($_POST['description']          ?? ''),
            'amount_per_student'   => (float)($_POST['amount_per_student']   ?? 0),
            'max_number_of_awards' => (int)($_POST['max_number_of_awards']   ?? 1),
            'semester_id'          => !empty($_POST['semester_id']) ? (int)$_POST['semester_id'] : null,
            'application_deadline' => $_POST['application_deadline'] ?? null,
            'status'               => $_POST['status'] ?? 'active',
        ];

        $this->programModel->update($id, $data);
        Session::flash('success', 'Cập nhật chương trình thành công.');
        redirect(base_url('admin/programs'));
    }

    public function deleteProgram(int $id): void
    {
        requireAdmin();
        $this->programModel->delete($id);
        Session::flash('success', 'Đã xóa chương trình học bổng.');
        redirect(base_url('admin/programs'));
    }

    // =========================================================
    // ELIGIBILITY RULES
    // =========================================================

    public function eligibilityRules(int $programId): void
    {
        requireAdmin();
        $program = $this->programModel->findById($programId);
        if (!$program) { Session::flash('error', 'Không tìm thấy chương trình.'); redirect(base_url('admin/programs')); }
        $rules   = $this->eligibilityModel->getByProgram($programId);
        $error   = Session::getFlash('error');
        $success = Session::getFlash('success');
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/admin/eligibility_rules.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function addEligibilityRule(int $programId): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect(base_url('admin/eligibilityRules/' . $programId));

        $this->eligibilityModel->create([
            'scholarship_program_id' => $programId,
            'rule_type'              => $_POST['rule_type']  ?? 'gpa_min',
            'rule_value'             => trim($_POST['rule_value'] ?? ''),
            'is_required'            => isset($_POST['is_required']) ? 1 : 0,
        ]);

        Session::flash('success', 'Đã thêm điều kiện.');
        redirect(base_url('admin/eligibilityRules/' . $programId));
    }

    public function deleteEligibilityRule(int $id): void
    {
        requireAdmin();
        $rule = $this->eligibilityModel->findById($id);
        $programId = $rule ? $rule['scholarship_program_id'] : 0;
        $this->eligibilityModel->delete($id);
        Session::flash('success', 'Đã xóa điều kiện.');
        redirect(base_url('admin/eligibilityRules/' . $programId));
    }

    // =========================================================
    // SCORING CRITERIA
    // =========================================================

    public function scoringCriteria(int $programId): void
    {
        requireAdmin();
        $program  = $this->programModel->findById($programId);
        if (!$program) { Session::flash('error', 'Không tìm thấy chương trình.'); redirect(base_url('admin/programs')); }
        $criteria = $this->criterionModel->getByProgram($programId);
        $error    = Session::getFlash('error');
        $success  = Session::getFlash('success');
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/admin/scoring_criteria.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function addScoringCriterion(int $programId): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect(base_url('admin/scoringCriteria/' . $programId));

        $weight = (float)($_POST['weight'] ?? 0);
        if ($weight <= 0 || $weight > 1) {
            Session::flash('error', 'Trọng số phải trong khoảng 0.01 – 1.00.');
            redirect(base_url('admin/scoringCriteria/' . $programId));
        }

        $this->criterionModel->create([
            'scholarship_program_id' => $programId,
            'criterion_name'         => trim($_POST['criterion_name'] ?? ''),
            'description'            => trim($_POST['description']    ?? ''),
            'weight'                 => $weight,
            'max_score'              => (int)($_POST['max_score'] ?? 10),
        ]);

        Session::flash('success', 'Đã thêm tiêu chí chấm điểm.');
        redirect(base_url('admin/scoringCriteria/' . $programId));
    }

    public function deleteScoringCriterion(int $id): void
    {
        requireAdmin();
        $criterion = $this->criterionModel->findById($id);
        $programId = $criterion ? $criterion['scholarship_program_id'] : 0;
        $this->criterionModel->delete($id);
        Session::flash('success', 'Đã xóa tiêu chí.');
        redirect(base_url('admin/scoringCriteria/' . $programId));
    }

    // =========================================================
    // APPLICATIONS
    // =========================================================

    public function applications(): void
    {
        requireAdmin();
        $applications = $this->applicationModel->getAll();
        $error        = Session::getFlash('error');
        $success      = Session::getFlash('success');
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/admin/application_list.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function applicationDetail(int $id): void
    {
        requireAdmin();
        $application = $this->applicationModel->findById($id);
        if (!$application) { Session::flash('error', 'Hồ sơ không tồn tại.'); redirect(base_url('admin/applications')); }
        $criteria    = $this->criterionModel->getByProgram($application['scholarship_program_id']);
        $scores      = $this->scoreModel->getByApplication($id);
        $ranking     = $this->rankingModel->findByApplication($id);
        $error       = Session::getFlash('error');
        $success     = Session::getFlash('success');
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/admin/application_detail.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function updateApplicationStatus(int $id): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect(base_url('admin/applications'));
        $status = $_POST['status'] ?? '';
        $this->applicationModel->updateStatus($id, $status);
        Session::flash('success', 'Đã cập nhật trạng thái hồ sơ.');
        redirect(base_url('admin/applicationDetail/' . $id));
    }

    // =========================================================
    // EVALUATION SCORES
    // =========================================================

    public function scores(int $applicationId): void
    {
        requireAdmin();
        $application = $this->applicationModel->findById($applicationId);
        if (!$application) { Session::flash('error', 'Hồ sơ không tồn tại.'); redirect(base_url('admin/applications')); }
        $criteria    = $this->criterionModel->getByProgram($application['scholarship_program_id']);
        $scores      = $this->scoreModel->getByApplication($applicationId);
        $error       = Session::getFlash('error');
        $success     = Session::getFlash('success');
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/admin/evaluation_score_view.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function saveScores(int $applicationId): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect(base_url('admin/scores/' . $applicationId));

        $application = $this->applicationModel->findById($applicationId);
        if (!$application) redirect(base_url('admin/applications'));

        $scores = $_POST['scores'] ?? [];
        foreach ($scores as $criterionId => $score) {
            $criterion = $this->criterionModel->findById((int)$criterionId);
            if (!$criterion) continue;

            $scoreVal = max(0, min((int)$score, $criterion['max_score']));
            $this->scoreModel->upsert([
                'application_id'       => $applicationId,
                'scoring_criterion_id' => (int)$criterionId,
                'score'                => $scoreVal,
                'evaluated_by'         => Session::userId(),
            ]);
        }

        // Recalculate total and update/create ranking
        $total = $this->scoreModel->calculateTotalScore($applicationId);
        $this->rankingModel->upsert($applicationId, $total);
        $this->rankingModel->recalculateRanks($application['scholarship_program_id']);

        // Update application status to under_review if eligible
        if ($application['status'] === 'eligible') {
            $this->applicationModel->updateStatus($applicationId, 'under_review');
        }

        Session::flash('success', 'Đã lưu điểm và cập nhật xếp hạng. Tổng điểm: ' . $total);
        redirect(base_url('admin/scores/' . $applicationId));
    }

    // =========================================================
    // RANKINGS
    // =========================================================

    public function rankings(): void
    {
        requireAdmin();
        $programs = $this->programModel->getAll();
        $error    = Session::getFlash('error');
        $success  = Session::getFlash('success');
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/admin/ranking_list.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function rankingByProgram(int $programId): void
    {
        requireAdmin();
        $program  = $this->programModel->findById($programId);
        if (!$program) { Session::flash('error', 'Không tìm thấy chương trình.'); redirect(base_url('admin/rankings')); }
        $rankings = $this->rankingModel->getByProgram($programId);
        $error    = Session::getFlash('error');
        $success  = Session::getFlash('success');
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/admin/ranking_detail.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function updateRankingStatus(int $id): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect(base_url('admin/rankings'));

        $status    = $_POST['status']     ?? 'suggested';
        $programId = (int)($_POST['program_id'] ?? 0);

        $this->rankingModel->updateStatus($id, $status);

        // If awarded, update application status too
        if ($status === 'award_granted') {
            $ranking = $this->rankingModel->findById($id);
            if ($ranking) {
                $this->applicationModel->updateStatus($ranking['application_id'], 'awarded');
            }
        }

        Session::flash('success', 'Đã cập nhật trạng thái xếp hạng.');
        redirect(base_url('admin/rankingByProgram/' . $programId));
    }

    // =========================================================
    // DISBURSEMENTS
    // =========================================================

    public function disbursements(): void
    {
        requireAdmin();
        $disbursements = $this->disbursementModel->getAll();
        $error         = Session::getFlash('error');
        $success       = Session::getFlash('success');
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/admin/disbursement_list.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function createDisbursement(): void
    {
        requireAdmin();
        // Get awarded rankings without disbursement
        $rankings = $this->rankingModel->getAll();
        $error    = Session::getFlash('error');
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/admin/disbursement_form.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function doCreateDisbursement(): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect(base_url('admin/disbursements'));

        $rankingResultId = (int)($_POST['ranking_result_id'] ?? 0);
        $amountPaid      = (float)($_POST['amount_paid']      ?? 0);
        $paymentMethod   = trim($_POST['payment_method']      ?? '');
        $note            = trim($_POST['note']                ?? '');

        if (!$rankingResultId || $amountPaid <= 0) {
            Session::flash('error', 'Thông tin chi trả không hợp lệ.');
            redirect(base_url('admin/createDisbursement'));
        }

        $this->disbursementModel->create([
            'ranking_result_id' => $rankingResultId,
            'amount_paid'       => $amountPaid,
            'status'            => 'pending',
            'payment_method'    => $paymentMethod,
            'note'              => $note,
        ]);

        Session::flash('success', 'Đã tạo bản ghi chi trả.');
        redirect(base_url('admin/disbursements'));
    }

    public function editDisbursement(int $id): void
    {
        requireAdmin();
        $disbursement = $this->disbursementModel->findById($id);
        if (!$disbursement) { Session::flash('error', 'Không tìm thấy bản ghi.'); redirect(base_url('admin/disbursements')); }
        $error = Session::getFlash('error');
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/admin/disbursement_form.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function doEditDisbursement(int $id): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect(base_url('admin/disbursements'));

        $data = [
            'amount_paid'    => (float)($_POST['amount_paid']    ?? 0),
            'payment_date'   => !empty($_POST['payment_date']) ? $_POST['payment_date'] : null,
            'status'         => $_POST['status']         ?? 'pending',
            'payment_method' => trim($_POST['payment_method'] ?? ''),
            'note'           => trim($_POST['note']           ?? ''),
        ];

        $this->disbursementModel->update($id, $data);
        Session::flash('success', 'Đã cập nhật bản ghi chi trả.');
        redirect(base_url('admin/disbursements'));
    }

    public function deleteDisbursement(int $id): void
    {
        requireAdmin();
        $this->disbursementModel->delete($id);
        Session::flash('success', 'Đã xóa bản ghi chi trả.');
        redirect(base_url('admin/disbursements'));
    }

    // =========================================================
    // AWARD CERTIFICATES
    // =========================================================

    public function certificates(): void
    {
        requireAdmin();
        $certificates = $this->certModel->getAll();
        $error        = Session::getFlash('error');
        $success      = Session::getFlash('success');
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/admin/certificate_list.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function issueCertificate(int $rankingResultId): void
    {
        requireAdmin();
        $ranking = $this->rankingModel->findById($rankingResultId);
        if (!$ranking) { Session::flash('error', 'Không tìm thấy kết quả xếp hạng.'); redirect(base_url('admin/certificates')); }

        // Check if already issued
        $existing = $this->certModel->findByRankingResult($rankingResultId);
        if ($existing) {
            Session::flash('error', 'Chứng nhận đã được cấp cho hồ sơ này.');
            redirect(base_url('admin/certificates'));
        }

        $this->certModel->create([
            'ranking_result_id' => $rankingResultId,
            'certificate_code'  => generateCertificateCode(),
            'issued_date'       => date('Y-m-d'),
            'status'            => 'issued',
        ]);

        Session::flash('success', 'Đã cấp chứng nhận học bổng.');
        redirect(base_url('admin/certificates'));
    }

    public function editCertificate(int $id): void
    {
        requireAdmin();
        $certificate = $this->certModel->findById($id);
        if (!$certificate) { Session::flash('error', 'Không tìm thấy chứng nhận.'); redirect(base_url('admin/certificates')); }
        $error = Session::getFlash('error');
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/admin/certificate_form.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function doEditCertificate(int $id): void
    {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect(base_url('admin/certificates'));

        $data = [
            'certificate_code' => trim($_POST['certificate_code'] ?? ''),
            'issued_date'      => $_POST['issued_date']           ?? date('Y-m-d'),
            'pdf_url'          => trim($_POST['pdf_url']          ?? ''),
            'status'           => $_POST['status']                ?? 'issued',
        ];

        $this->certModel->update($id, $data);
        Session::flash('success', 'Đã cập nhật chứng nhận.');
        redirect(base_url('admin/certificates'));
    }

    public function deleteCertificate(int $id): void
    {
        requireAdmin();
        $this->certModel->delete($id);
        Session::flash('success', 'Đã xóa chứng nhận.');
        redirect(base_url('admin/certificates'));
    }

    // =========================================================
    // VERIFY (public page – no auth required)
    // =========================================================

    public function verify(): void
    {
        $code        = trim($_GET['code'] ?? '');
        $certificate = null;
        if ($code) {
            $certificate = $this->certModel->findByCode($code);
        }
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/admin/verify.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }
}