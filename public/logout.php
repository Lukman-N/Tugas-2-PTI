<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/app.php';

if (!is_post()) {
    http_response_code(405);
    header('Allow: POST');
    exit('Method not allowed.');
}
if (!csrf_is_valid($_POST['_csrf'] ?? null)) {
    http_response_code(403);
    exit(h(t('csrf_error')));
}

$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}
session_destroy();
redirect('login.php?logged_out=1');
