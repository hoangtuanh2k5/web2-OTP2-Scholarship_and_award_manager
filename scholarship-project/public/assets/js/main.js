/**
 * Scholarship & Award Manager – Main JS
 * Handles global UI interactions.
 */

document.addEventListener('DOMContentLoaded', function () {

    // ── Auto-dismiss alerts after 5 seconds ──────────────────
    document.querySelectorAll('.alert').forEach(function (alert) {
        setTimeout(function () {
            alert.style.transition = 'opacity .4s';
            alert.style.opacity = '0';
            setTimeout(function () { alert.remove(); }, 400);
        }, 5000);
    });

    // ── Confirm delete on data-confirm links ─────────────────
    document.querySelectorAll('[data-confirm]').forEach(function (el) {
        el.addEventListener('click', function (e) {
            if (!confirm(el.dataset.confirm)) {
                e.preventDefault();
            }
        });
    });

    // ── Active nav link highlight ─────────────────────────────
    const currentUrl = window.location.href;
    document.querySelectorAll('.navbar-menu a').forEach(function (link) {
        if (currentUrl.includes(link.getAttribute('href'))) {
            link.style.background = 'rgba(255,255,255,.2)';
            link.style.fontWeight = '600';
        }
    });

});
