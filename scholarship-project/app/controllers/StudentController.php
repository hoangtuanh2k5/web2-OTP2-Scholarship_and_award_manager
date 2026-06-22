<?php
class StudentController
{
    private Student          $studentModel;
    private Application      $applicationModel;
    private ScholarshipProgram $programModel;
    private EligibilityRule  $eligibilityModel;
    private StudentActivity  $activityModel;
    private RankingResult    $rankingModel;
    private AwardCertificate $certModel;
    private Disbursement     $disbursementModel;
    private Semester         $semesterModel;

    public function __construct()
    {
        $this->studentModel      = new Student();
        $this->applicationModel  = new Application();
        $this->programModel      = new ScholarshipProgram();
        $this->eligibilityModel  = new EligibilityRule();
        $this->activityModel     = new StudentActivity();
        $this->rankingModel      = new RankingResult();
        $this->certModel         = new AwardCertificate();
        $this->disbursementModel = new Disbursement();
        $this->semesterModel     = new Semester();
    }

    /** GET /student/dashboard */
    public function dashboard(): void
    {
        requireStudent();
        $student      = $this->studentModel->findByUserId(Session::userId());
        $applications = $this->applicationModel->getByStudent($student['id']);
        $programs     = $this->programModel->getActive();
        $activities   = $this->activityModel->getByStudent($student['id']);

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/student/dashboard.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    /** GET /student/applications */
    public function applications(): void
    {
        requireStudent();
        $student      = $this->studentModel->findByUserId(Session::userId());
        $applications = $this->applicationModel->getByStudent($student['id']);

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/student/my_applications.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    /** GET /student/apply/[program_id] */
    public function apply(int $programId): void
    {
        requireStudent();
        $student = $this->studentModel->findByUserId(Session::userId());
        $program = $this->programModel->findById($programId);

        if (!$program || $program['status'] !== 'active') {
            Session::flash('error', 'Chương trình học bổng không tồn tại hoặc đã đóng.');
            redirect(base_url('student/dashboard'));
        }

        // Check if already applied
        $existing = $this->applicationModel->findByStudentAndProgram($student['id'], $programId);
        if ($existing) {
            Session::flash('error', 'Bạn đã nộp hồ sơ cho chương trình này rồi.');
            redirect(base_url('student/dashboard'));
        }

        $rules         = $this->eligibilityModel->getByProgram($programId);
        $activityCount = $this->activityModel->countCompleted($student['id']);
        $eligibility   = $this->eligibilityModel->checkEligibility($programId, $student, $activityCount);
        $error         = Session::getFlash('error');

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/student/application_form.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    /** POST /student/doApply */
    public function doApply(): void
    {
        requireStudent();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('student/dashboard'));
        }

        $programId = (int)($_POST['scholarship_program_id'] ?? 0);
        $note      = trim($_POST['note'] ?? '');
        $student   = $this->studentModel->findByUserId(Session::userId());

        if (!$programId) {
            Session::flash('error', 'Chương trình học bổng không hợp lệ.');
            redirect(base_url('student/dashboard'));
        }

        $program = $this->programModel->findById($programId);
        if (!$program || $program['status'] !== 'active') {
            Session::flash('error', 'Chương trình học bổng không tồn tại hoặc đã đóng.');
            redirect(base_url('student/dashboard'));
        }

        // Check duplicate
        $existing = $this->applicationModel->findByStudentAndProgram($student['id'], $programId);
        if ($existing) {
            Session::flash('error', 'Bạn đã nộp hồ sơ cho chương trình này rồi.');
            redirect(base_url('student/dashboard'));
        }

        // Check eligibility
        $activityCount = $this->activityModel->countCompleted($student['id']);
        $eligibility   = $this->eligibilityModel->checkEligibility($programId, $student, $activityCount);
        $status        = $eligibility['passed'] ? 'eligible' : 'ineligible';

        $appId = $this->applicationModel->create([
            'student_id'             => $student['id'],
            'scholarship_program_id' => $programId,
            'status'                 => $status,
            'note'                   => $note,
            'submission_date'        => date('Y-m-d H:i:s'),
        ]);

        if (!$eligibility['passed']) {
            $msg = 'Hồ sơ đã nộp nhưng không đủ điều kiện: ' . implode('; ', $eligibility['failures']);
            Session::flash('error', $msg);
        } else {
            Session::flash('success', 'Nộp hồ sơ thành công! Hồ sơ đang chờ xét duyệt.');
        }

        redirect(base_url('student/applications'));
    }

    /** GET /student/applicationDetail/[id] */
    public function applicationDetail(int $id): void
    {
        requireStudent();
        $student     = $this->studentModel->findByUserId(Session::userId());
        $application = $this->applicationModel->findById($id);

        if (!$application || (int)$application['student_id'] !== (int)$student['id']) {
            Session::flash('error', 'Hồ sơ không tồn tại.');
            redirect(base_url('student/applications'));
        }

        $ranking     = $this->rankingModel->findByApplication($id);
        $certificate = $ranking ? $this->certModel->findByRankingResult($ranking['id']) : null;
        $disbursement = $ranking ? $this->disbursementModel->findByRankingResult($ranking['id']) : null;

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/student/application_detail.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    /** POST /student/cancelApplication/[id] */
    public function cancelApplication(int $id): void
    {
        requireStudent();
        $student     = $this->studentModel->findByUserId(Session::userId());
        $application = $this->applicationModel->findById($id);

        if (!$application || (int)$application['student_id'] !== (int)$student['id']) {
            Session::flash('error', 'Hồ sơ không tồn tại.');
            redirect(base_url('student/applications'));
        }

        if (!in_array($application['status'], ['draft', 'submitted', 'eligible', 'ineligible'])) {
            Session::flash('error', 'Không thể hủy hồ sơ ở trạng thái này.');
            redirect(base_url('student/applications'));
        }

        $this->applicationModel->delete($id);
        Session::flash('success', 'Đã hủy hồ sơ thành công.');
        redirect(base_url('student/applications'));
    }

    /** GET /student/activities */
    public function activities(): void
    {
        requireStudent();
        $student    = $this->studentModel->findByUserId(Session::userId());
        $activities = $this->activityModel->getByStudent($student['id']);
        $semesters  = $this->semesterModel->getAll();
        $error      = Session::getFlash('error');
        $success    = Session::getFlash('success');

        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/student/activities.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    /** POST /student/addActivity */
    public function addActivity(): void
    {
        requireStudent();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('student/activities'));
        }

        $student = $this->studentModel->findByUserId(Session::userId());

        $activityName = trim($_POST['activity_name'] ?? '');
        $activityType = $_POST['activity_type'] ?? '';
        $semesterId   = !empty($_POST['semester_id']) ? (int)$_POST['semester_id'] : null;
        $description  = trim($_POST['description'] ?? '');

        if (empty($activityName) || empty($activityType)) {
            Session::flash('error', 'Vui lòng điền đầy đủ thông tin hoạt động.');
            redirect(base_url('student/activities'));
        }

        $this->activityModel->create([
            'student_id'    => $student['id'],
            'semester_id'   => $semesterId,
            'activity_name' => $activityName,
            'activity_type' => $activityType,
            'description'   => $description,
            'completed'     => 1,
        ]);

        Session::flash('success', 'Đã thêm hoạt động thành công.');
        redirect(base_url('student/activities'));
    }

    /** POST /student/deleteActivity/[id] */
    public function deleteActivity(int $id): void
    {
        requireStudent();
        $student  = $this->studentModel->findByUserId(Session::userId());
        $activity = $this->activityModel->findById($id);

        if (!$activity || (int)$activity['student_id'] !== (int)$student['id']) {
            Session::flash('error', 'Hoạt động không tồn tại.');
            redirect(base_url('student/activities'));
        }

        $this->activityModel->delete($id);
        Session::flash('success', 'Đã xóa hoạt động.');
        redirect(base_url('student/activities'));
    }
}
