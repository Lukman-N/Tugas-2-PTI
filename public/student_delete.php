<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/app.php';
require_auth();

if (!is_post()) {
    http_response_code(405);
    header('Allow: POST');
    exit('Method not allowed.');
}
if (!csrf_is_valid($_POST['_csrf'] ?? null)) {
    http_response_code(403);
    exit(h(t('csrf_error')));
}
$id = filter_var(post_string('id'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if ($id === false) {
    set_flash('error', t('invalid_id'));
    redirect('index.php');
}

try {
    $statement = db()->prepare('DELETE FROM students WHERE id=:id');
    $statement->execute(['id' => $id]);
    set_flash($statement->rowCount() > 0 ? 'success' : 'error', $statement->rowCount() > 0 ? t('deleted') : t('not_found'));
} catch (PDOException $exception) {
    set_flash('error', t('db_error'));
}
redirect('index.php');
