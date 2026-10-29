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

$language = post_string('language');
if (in_array($language, ['id', 'en'], true)) {
    $_SESSION['language'] = $language;
}
redirect(!empty($_SESSION['user_id']) ? 'index.php' : 'login.php');
