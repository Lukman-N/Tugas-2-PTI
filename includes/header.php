<?php
declare(strict_types=1);
$pageTitle = $pageTitle ?? t('dashboard');
$flash = take_flash();
?>
<!doctype html>
<html lang="<?= h($_SESSION['language'] ?? 'id') ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= h($pageTitle) ?> - <?= h(t('app_name')) ?></title>
  <link rel="stylesheet" href="assets/app.css?v=<?= filemtime(__DIR__ . '/../public/assets/app.css') ?>">
</head>
<body>
<div class="app-shell">
  <header class="topbar">
    <a class="brand" href="index.php" aria-label="<?= h(t('app_name')) ?>">
      <span class="brand-copy"><strong><?= h(t('app_name')) ?></strong><small><?= h(t('app_tagline')) ?></small></span>
    </a>
    <nav class="main-nav" aria-label="<?= h(t('app_name')) ?>">
      <a href="index.php" class="nav-link"><?= h(t('nav_home')) ?></a>
      <a href="student_form.php" class="nav-link"><?= h(t('nav_add')) ?></a>
    </nav>
    <div class="top-actions">
      <form action="language.php" method="post" class="inline-form">
        <input type="hidden" name="_csrf" value="<?= h(csrf_token()) ?>">
        <input type="hidden" name="language" value="<?= ($_SESSION['language'] ?? 'id') === 'id' ? 'en' : 'id' ?>">
        <button class="language-button" type="submit"><?= h(t('switch_language')) ?></button>
      </form>
      <span class="user-name"><?= h($_SESSION['username'] ?? '') ?></span>
      <form action="logout.php" method="post" class="inline-form">
        <input type="hidden" name="_csrf" value="<?= h(csrf_token()) ?>">
        <button class="text-button" type="submit"><?= h(t('logout')) ?></button>
      </form>
    </div>
  </header>
  <main class="main-content">
    <?php if ($flash !== null): ?>
      <div class="alert alert-<?= h($flash['type'] ?? 'info') ?>" role="status"><?= h($flash['message'] ?? '') ?></div>
    <?php endif; ?>