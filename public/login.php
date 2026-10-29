<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/app.php';

if (!empty($_SESSION['user_id'])) {
    redirect('index.php');
}

$error = '';
$username = '';
if (is_post()) {
    if (!csrf_is_valid($_POST['_csrf'] ?? null)) {
        $error = t('csrf_error');
    } else {
        $username = trim(post_string('username'));
        $password = post_string('password');
        try {
            $statement = db()->prepare('SELECT id, username, password_hash FROM users WHERE username = :username LIMIT 1');
            $statement->execute(['username' => $username]);
            $user = $statement->fetch();
            if ($user && password_verify($password, $user['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = (int) $user['id'];
                $_SESSION['username'] = $user['username'];
                redirect('index.php');
            }
            $error = t('login_error');
        } catch (PDOException $exception) {
            $error = t('db_error');
        }
    }
}

$logoutNotice = isset($_GET['logged_out']) ? t('logged_out') : '';
?>
<!doctype html>
<html lang="<?= h($_SESSION['language'] ?? 'id') ?>">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= h(t('login')) ?> - <?= h(t('app_name')) ?></title>
  <link rel="stylesheet" href="assets/app.css?v=<?= filemtime(__DIR__ . '/assets/app.css') ?>">
</head>
<body class="login-page">
<div class="login-shell">
  <header class="login-header">
    <a class="brand" href="login.php">
      <span class="brand-copy"><strong><?= h(t('app_name')) ?></strong><small><?= h(t('app_tagline')) ?></small></span>
    </a>
    <form action="language.php" method="post" class="inline-form">
      <input type="hidden" name="_csrf" value="<?= h(csrf_token()) ?>">
      <input type="hidden" name="language" value="<?= ($_SESSION['language'] ?? 'id') === 'id' ? 'en' : 'id' ?>">
      <button class="language-button" type="submit"><?= h(t('switch_language')) ?></button>
    </form>
  </header>
  <main class="login-main">
    <section class="login-card">
      <span class="login-greeting"><?= h(t('login_title')) ?></span>
      <h1><?= h(t('login')) ?></h1>
      <p class="muted"><?= h(t('login_subtitle')) ?></p>
      <?php if ($logoutNotice !== ''): ?><div class="alert alert-success"><?= h($logoutNotice) ?></div><?php endif; ?>
      <?php if ($error !== ''): ?><div class="alert alert-error" role="alert"><?= h($error) ?></div><?php endif; ?>
      <form action="login.php" method="post" class="stack-form">
        <input type="hidden" name="_csrf" value="<?= h(csrf_token()) ?>">
        <label><?= h(t('username')) ?><input type="text" name="username" value="<?= h($username) ?>" autocomplete="username" required maxlength="60"></label>
        <label><?= h(t('password')) ?><input type="password" name="password" autocomplete="current-password" required></label>
        <button class="button button-primary button-wide" type="submit"><?= h(t('login')) ?></button>
      </form>
      <p class="demo-note"><?= h(t('demo_account')) ?></p>
    </section>
    <p class="login-footer"><?= h(t('footer_note')) ?></p>
  </main>
</div>
</body>
</html>