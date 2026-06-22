<?php
/**
 * Route definitions (informational – actual routing handled by Router.php)
 *
 * Pattern: BASE_URL/index.php?url=controller/method[/param]
 *
 * Auth:
 *   auth/login          GET/POST
 *   auth/register       GET/POST
 *   auth/logout         GET
 *
 * Student:
 *   student/dashboard
 *   student/apply/[program_id]
 *   student/applications
 *   student/application/[id]
 *   student/cancelApplication/[id]
 *
 * Admin:
 *   admin/dashboard
 *   admin/users
 *   admin/programs
 *   admin/applications
 *   admin/scores/[application_id]
 *   admin/rankings/[program_id]
 *   admin/disbursements
 *   admin/certificates
 */
