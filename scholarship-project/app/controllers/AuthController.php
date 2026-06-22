<?php
class AuthController
{
    private UserModel $userModel;
    private Student   $studentModel;

    public function __construct()
    {
        $this->userModel    = new UserModel();
        $this->studentModel = new Student();
    }

    /** GET /auth/login */
    public function login(): void
    {
        if (Session::isLoggedIn()) {
            $this->redirectByRole();
        }

        $error = Session::getFlash('error');
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/auth/login.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    /** POST /auth/login */
    public function doLogin(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('auth/login'));
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        // Basic validation
        if (empty($username) || empty($password)) {
            Session::flash('error', 'Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.');
            redirect(base_url('auth/login'));
        }

        $user = $this->userModel->findByUsername($username);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            Session::flash('error', 'Tên đăng nhập hoặc mật khẩu không đúng.');
            redirect(base_url('auth/login'));
        }

        // Store session
        Session::set('user_id',  $user['id']);
        Session::set('username', $user['username']);
        Session::set('role',     $user['role']);

        // If student, also store student_id
        if ($user['role'] === 'student') {
            $student = $this->studentModel->findByUserId($user['id']);
            if ($student) {
                Session::set('student_id', $student['id']);
                Session::set('full_name',  $student['full_name']);
            }
        }

        $this->redirectByRole();
    }

    /** GET /auth/register */
    public function register(): void
    {
        if (Session::isLoggedIn()) {
            $this->redirectByRole();
        }

        $error   = Session::getFlash('error');
        $success = Session::getFlash('success');
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/auth/register.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    /** POST /auth/doRegister */
    public function doRegister(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('auth/register'));
        }

        $username     = trim($_POST['username']     ?? '');
        $email        = trim($_POST['email']        ?? '');
        $password     = $_POST['password']          ?? '';
        $confirm      = $_POST['confirm_password']  ?? '';
        $fullName     = trim($_POST['full_name']    ?? '');
        $studentCode  = trim($_POST['student_code'] ?? '');
        $faculty      = trim($_POST['faculty']      ?? '');

        // Validation
        $errors = [];
        if (empty($username))    $errors[] = 'Tên đăng nhập không được để trống.';
        if (empty($email))       $errors[] = 'Email không được để trống.';
        if (empty($password))    $errors[] = 'Mật khẩu không được để trống.';
        if ($password !== $confirm) $errors[] = 'Mật khẩu xác nhận không khớp.';
        if (strlen($password) < 6)  $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự.';
        if (empty($fullName))    $errors[] = 'Họ tên không được để trống.';
        if (empty($studentCode)) $errors[] = 'Mã sinh viên không được để trống.';

        if (!empty($errors)) {
            Session::flash('error', implode('<br>', $errors));
            redirect(base_url('auth/register'));
        }

        // Check duplicates
        if ($this->userModel->findByUsername($username)) {
            Session::flash('error', 'Tên đăng nhập đã tồn tại.');
            redirect(base_url('auth/register'));
        }
        if ($this->userModel->findByEmail($email)) {
            Session::flash('error', 'Email đã được sử dụng.');
            redirect(base_url('auth/register'));
        }

        // Create user
        $userId = $this->userModel->create([
            'role'          => 'student',
            'username'      => $username,
            'email'         => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        // Create student profile
        $this->studentModel->create([
            'user_id'      => $userId,
            'student_code' => $studentCode,
            'full_name'    => $fullName,
            'faculty'      => $faculty,
        ]);

        Session::flash('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
        redirect(base_url('auth/login'));
    }

    /** GET /auth/logout */
    public function logout(): void
    {
        Session::destroy();
        redirect(base_url('auth/login'));
    }

    private function redirectByRole(): never
    {
        if (Session::isAdmin()) {
            redirect(base_url('admin/dashboard'));
        }
        redirect(base_url('student/dashboard'));
    }
}
