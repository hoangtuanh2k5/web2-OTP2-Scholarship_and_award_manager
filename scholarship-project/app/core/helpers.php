<?php
/**
 * Global helper functions
 */

/** Redirect to a URL – accepts full URL or path relative to APP_URL */
function redirect(string $url): never
{
    // If already a full URL, use as-is
    if (str_starts_with($url, 'http')) {
        header('Location: ' . $url);
    } else {
        header('Location: ' . rtrim(APP_URL, '/') . '/' . ltrim($url, '/'));
    }
    exit;
}

/** Sanitize output to prevent XSS */
function e(mixed $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/**
 * App routing URL helper.
 *
 * base_url()                          → BASE_URL (index.php)
 * base_url('index.php?url=admin/...')  → APP_URL/index.php?url=admin/...
 * base_url('assets/css/style.css')    → APP_URL/assets/css/style.css
 * base_url('admin/dashboard')         → APP_URL/index.php?url=admin/dashboard  (shorthand route)
 */
function base_url(string $path = ''): string
{
    if ($path === '') {
        return BASE_URL;
    }

    // Already a full index.php?url= path
    if (str_starts_with($path, 'index.php')) {
        return APP_URL . '/' . $path;
    }

    // Static assets – contains a dot extension
    if (preg_match('/\.\w{2,4}$/', $path)) {
        return APP_URL . '/' . ltrim($path, '/');
    }

    // Shorthand route: 'admin/dashboard' → index.php?url=admin/dashboard
    return APP_URL . '/index.php?url=' . ltrim($path, '/');
}

/** Require login – redirect to login page if not authenticated */
function requireLogin(): void
{
    if (!Session::isLoggedIn()) {
        Session::flash('error', 'Vui lòng đăng nhập để tiếp tục.');
        redirect(base_url('auth/login'));
    }
}

/** Require admin role */
function requireAdmin(): void
{
    requireLogin();
    if (!Session::isAdmin()) {
        redirect(base_url('student/dashboard'));
    }
}

/** Require student role */
function requireStudent(): void
{
    requireLogin();
    if (!Session::isStudent()) {
        redirect(base_url('admin/dashboard'));
    }
}

/** Format currency (VND) */
function formatCurrency(float $amount): string
{
    return number_format($amount, 0, ',', '.') . ' ₫';
}

/** Format date to Vietnamese style */
function formatDate(?string $date): string
{
    if (!$date) return '—';
    return date('d/m/Y', strtotime($date));
}

/** Format datetime */
function formatDatetime(?string $dt): string
{
    if (!$dt) return '—';
    return date('d/m/Y H:i', strtotime($dt));
}

/** Status badge label mapping */
function applicationStatusLabel(string $status): string
{
    return match($status) {
        'draft'        => 'Nháp',
        'submitted'    => 'Đã nộp',
        'eligible'     => 'Đủ điều kiện',
        'ineligible'   => 'Không đủ điều kiện',
        'under_review' => 'Đang xét duyệt',
        'rejected'     => 'Bị từ chối',
        'awarded'      => 'Được cấp học bổng',
        default        => $status,
    };
}

function applicationStatusClass(string $status): string
{
    return match($status) {
        'draft'        => 'badge-secondary',
        'submitted'    => 'badge-info',
        'eligible'     => 'badge-primary',
        'ineligible'   => 'badge-danger',
        'under_review' => 'badge-warning',
        'rejected'     => 'badge-danger',
        'awarded'      => 'badge-success',
        default        => 'badge-secondary',
    };
}

function rankingStatusLabel(string $status): string
{
    return match($status) {
        'suggested'     => 'Đề xuất',
        'award_granted' => 'Được cấp',
        'rejected'      => 'Bị từ chối',
        default         => $status,
    };
}

function disbursementStatusLabel(string $status): string
{
    return match($status) {
        'pending'    => 'Chờ xử lý',
        'processing' => 'Đang xử lý',
        'paid'       => 'Đã chi trả',
        'cancelled'  => 'Đã hủy',
        default      => $status,
    };
}

/** Generate a unique certificate code */
function generateCertificateCode(): string
{
    return 'CERT-' . date('Y') . '-' . strtoupper(substr(uniqid(), -6));
}
